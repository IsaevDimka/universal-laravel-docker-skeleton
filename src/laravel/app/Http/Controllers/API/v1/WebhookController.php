<?php

namespace App\Http\Controllers\API\v1;

use App\Events\PrivateMessage;
use App\Events\SystemMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{

    public function test(Request $request)
    {
        event(new SystemMessage('Test socket io public channel'));
        event(new PrivateMessage($request->get('m', 'Test socket io private channel')));
        return api()->ok('Socket.io webhook', [
            'request'               => $request->toArray(),
        ]);
    }
}
