<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Exceptions\VerifyEmailException;
use App\Http\Controllers\API\ApiController;
use App\Models\User;
use App\Traits\PhoneNumberFormattingTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends ApiController
{
    use AuthenticatesUsers;
    use PhoneNumberFormattingTrait;

    /**
     * Login username to be used by the controller.
     */
    protected string $username;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->authType();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function authType()
    {
//        $login = request()->input('login');
//        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
//        request()->merge([$fieldType => $login]);
        return request()->get('auth_type', 'email');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return api()->ok('Logout successful');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $validationPhoneNumber = $this->phoneNumberFormatting($request->get('phone'), 'RU');
        $phone = $validationPhoneNumber['formatted']['formatE164'];
        if ($validationPhoneNumber['status']) {
            $request->merge(compact('phone'));
        }
        $token = $this->guard()->attempt($this->credentials($request));

        if (! $token) {
            return false;
        }

        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return false;
        }

        $this->guard()->setToken($token);

        return true;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');
        /** @var User $user */
        $user = $request->user();

        return api()->ok(null, [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
            'locale' => $user->locale,
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        /** @var User $user */
        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            throw VerifyEmailException::forUser($user);
        }

//        return api()->forbidden();
        throw ValidationException::withMessages([
            $this->username() => [],
        ]);
    }
}
