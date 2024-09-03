<?php

namespace App\Foglobal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    public static function rollback($e, $message = "Something went wrong!") {
        // DB::rollback();
        $code = ($e->getCode() != 403) ? 500 : $e->getCode();
        self::throw($e, $message, $code);
    }

    public static function throw($e, $message = "Something went wrong!", $code = 500) {
        Log::info($e);
        $message = (env('APP_DEBUG', false)) ? $e->getMessage() : $message;
        throw new HttpResponseException(response()->json(['message' => $message], $code));
    }

    public static function sendResponse($data, $code = 200) {
        $response = [];

        if(is_array($data) || is_object($data)) {
            $response = ['data' => $data];
        }else{
            $response = ['message' => $data];
        }

        return response()->json($response, $code);
    }
}
