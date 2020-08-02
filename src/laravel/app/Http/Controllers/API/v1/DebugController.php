<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;

class DebugController extends ApiController
{
    public function index(Request $request)
    {
        $data      = ['item_1' => 1, 'item_2' => 2];
        $extraData = ['extraDataTest' => 1];
        $errors    = ['errors' => 1];
        $extra_items = \App\Models\Locale::query()->select('id', 'sign')->active()->get()->toArray();

        switch($f = $request->get('f'))
        {
            case 'ok': $response = api()->ok('ok', $data, $extraData, compact('extra_items')); break;
            case 'success': $response = api()->success('success', $data, $extraData); break;
            case 'response': $response = api()->response(200, 'response', $data, $extraData); break;
            case 'notFound': $response = api()->notFound('notFound'); break;
            case 'validation': $response = api()->validation('validation', $errors, $extraData); break;
            case 'forbidden': $response = api()->forbidden('forbidden', $data, $extraData); break;
            case 'error': $response = api()->error('error', $data, $extraData); break;
            default: $response = api()->ok('error method', ['f' => $f]); break;
        }

        return $response;
    }

}
