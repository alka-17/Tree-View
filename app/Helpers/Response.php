<?php

namespace App\Helpers;

class Response
{
   /**
     * Success Response Message
     *
     * @param string $result, string $message, integer $code
     * @return json array
     */
    public static function success($result,$message,$code)
    {
        if($result){
    	$response = [
            "success" => true,
            "data"    => $result,
            "message" => $message,
            'status code' => $code,
        ];
    } else{
        $response = [
            "success" => true,
            "data"    => '',
            "message" => $message,
            'status code' => $code,
        ];
    }
        return response()->json($response, $code);
    }

     /**
     * Error Response Message
     *
     * @param  string $error,string $errorMessages, integer $code
     * @return json array
     */
    public static function error($error,$errorMessages, $code)
    {
    	$response = [
            "success" => false,
            "data"    => '',
            "message" => $error,
            'status code' => $code,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

}
