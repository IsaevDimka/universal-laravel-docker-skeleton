<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\ApiController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\PhoneNumberFormattingTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends ApiController
{
    use PhoneNumberFormattingTrait;


    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function __invoke(Request $request)
    {
        $messages = [

        ];
        $rules = [
            'phone'                 => ['required', function ($attribute, $value, $fail) {
                $validationPhoneNumber = $this->phoneNumberFormatting($value, 'RU');
                if (! $validationPhoneNumber['status']) {
                    $fail('Неверный формат номера телефона!', compact('attribute'));
                }
                if (\App\Models\User::where('phone', '=', $validationPhoneNumber['formatted']['formatE164'])->exists()) {
                    $fail('Пользователь с таким номером телефона уже зарегистрирован!', compact('attribute'));
                }
            }],
            'phone_is_verify'       => ['required', 'boolean', 'in:1'],
            'email'                 => [
                'nullable', 'string', 'email', 'max:255', function ($attribute, $value, $fail) {
                    if (\App\Models\User::where('email', 'ilike', mb_strtolower($value))->exists()) {
                        $fail(__('validation.unique', compact('attribute')));
                    }
                }
            ],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // event to clickhouse
            return api()->validation(null, $validator->errors()->toArray());
        }

        $payload = $validator->validated();

        $payload['is_active'] = true;
        $payload['locale'] = app()->getLocale();
        $validationPhoneNumber = $this->phoneNumberFormatting($payload['phone'], 'RU');
        $payload['phone'] = $validationPhoneNumber['formatted']['formatE164'];

        $user = User::create($payload);

        $user->assignRole('voter');
        if ($payload['is_observer']) {
            $user->assignRole('observer');
        }

        if (! $payload['email']) {
            $user->sendEmailVerificationNotification();
            /**
             * Welcome mail
             */
            $data = [
                'subject'      => 'Thank you for registering',
                'replyTo'      => null,
                'line_1'       => 'Login: '.$payload['email'],
                'line_2'       => 'Pass: '.$payload['password'],
                'action_label' => 'Go to site',
                'action_url'   => url()->to('/'),
                'line_3'       => null,
            ];

            $user->notify(new \App\Notifications\MailMessageNotification($data));
        }

        // event to clickhouse

        # Notify new user
        if(app()->environment('production', 'develop', 'local')) {
            /**
             * @todo: Make jobs
             */
            logger()->channel('telegram')->info("Register new user: ".PHP_EOL."ID: ".$user->id);
        }
        return api()->ok('Register successful', UserResource::make($user), compact('payload'));
    }
}
