<?php

namespace App\Http\Controllers\API\v1;

use App\Events\PrivateMessage;
use App\Events\SystemMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{

    public function any(Request $request)
    {
        return api()->ok(null, [
            'post'    => $request->post(),
            'query'   => $request->getQueryString(),
            'headers' => $request->headers->all(),
            'method'  => $request->getMethod(),
            'ip'      => $request->ip(),
            'ua'      => $request->userAgent(),
        ]);
    }

    public function test(Request $request)
    {
        event(new SystemMessage('Test socket io SystemMessage'));
        event(new PrivateMessage($request->get('m', 'Test socket io PrivateMessage')));

        return api()->ok('Socket.io webhook', [
            'request' => $request->toArray(),
            'user'    => $request->user(),
        ]);
    }
}
