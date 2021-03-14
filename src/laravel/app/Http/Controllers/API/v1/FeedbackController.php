<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends ApiController
{
    public function index(Request $request)
    {
        return api()->ok();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->only('email', 'message', 'recaptcha'), [
            'message' => ['required', 'string'],
            'recaptcha' => ['required'],
        ]);

        if ($validator->fails()) {
            return api()->validation(null, $validator->errors()->toArray());
        }

        $payload = $validator->validated();

        $payload['ip'] = $request->ip();
        $payload['ua'] = $request->userAgent();
        $payload['secure'] = $request->secure();
        $payload['route'] = $request->route();
        $geoip = geoip()->getLocation($payload['ip']);
        $payload['GeoIP'] = $geoip->toArray();

        logger()->channel('telegram')->info($payload['message'], [
            'issue' => 'Сообщение с формы написать нам',
            'country' => $geoip->country ?? 'Unknown',
            'city' => $geoip->city ?? 'Unknown',
        ]);
        logger()->channel('mongodb')->info('Сообщение с формы написать нам:', [
            'collection' => 'feedback',
            'payload' => $payload,
        ]);

        return api()->ok('Ваше сообщение успешно отправлено.', compact('payload'));
    }
}
