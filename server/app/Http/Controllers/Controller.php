<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function httpOkResponse($msg = "Response Ok", $data = null, $httpStatus = 200)
    {
        $responseBody = [
            'message' => $msg,
            'status' => $httpStatus,
            'error' => false,
        ];

        if ($data) {
            $responseBody['data'] = $data;
        }

        return response()->json($responseBody, $httpStatus);
    }

    public function httpErrorResponse($msg = "Response Error", $httpStatus = 400)
    {
        return response()->json([
            'message' => $msg,
            'status' => $httpStatus,
            'error' => true,
        ], $httpStatus);
    }
}
