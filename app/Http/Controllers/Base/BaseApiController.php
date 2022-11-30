<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{

    /**
     * success response method.
     *
     * @param $message
     *
     * @param array $data
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccessResponse($message, $data = null, $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @param $message
     * @param array $data
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFailedResponse($message, $data = null, $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, $code);
    }
}
