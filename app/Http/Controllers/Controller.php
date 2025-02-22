<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $code, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
 
        return response()->json($response, $code);
    }
 
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];
 
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
 
        return response()->json($response, $code);
    }
}
