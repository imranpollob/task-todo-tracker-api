<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message, $latest_task_time = null)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        if ($latest_task_time) {
            $response['latest_task_time'] = $latest_task_time;
        }

        return response()->json($response, 200);
    }


    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    public function sendResponseWithCookie($result, $message, $cookieContent)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200)->cookie($cookieContent);
    }
}
