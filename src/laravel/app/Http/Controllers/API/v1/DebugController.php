<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;

class DebugController extends ApiController
{
    public function index(Request $request)
    {
        $data      = ['item_1' => 1, 'item_2' => 2];
        $errors    = ['errors' => 1];
        $extraData = ['f' => $f = $request->get('f')];

        switch($f)
        {
            case 'ok': $response = api()->ok(null, $data, $extraData, compact('extra_items')); break;
            case 'success': $response = api()->success(null, $data, $extraData); break;
            case 'bad': $response = api()->bad(null, $errors, $extraData); break;
            case 'response': $response = api()->response(200, null, $data, $extraData); break;
            case 'notFound': $response = api()->notFound(); break;
            case 'validation': $response = api()->validation( $errors, $extraData); break;
            case 'forbidden': $response = api()->forbidden($data, $extraData); break;
            case 'error': $response = api()->error($data, $extraData); break;
            default: $response = api()->bad('error method', ['f' => $f]); break;
        }

        return $response;
    }

}
