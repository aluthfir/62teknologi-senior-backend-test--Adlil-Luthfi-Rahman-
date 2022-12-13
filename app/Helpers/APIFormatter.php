<?php

namespace App\Helpers;

class APIFormatter //Helper class to format and show respond
{
    protected static $response =
    [
        'code' => null,
        'message' => null,
        'data' => null,
    ];

    public static function createAPI($code = null, $message = null, $data = null)
    {
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }
}
