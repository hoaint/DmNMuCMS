<?php

    class Twocheckout_Message
    {
        public static function message($code, $message)
        {
            $response = [];
            $response['response_code'] = $code;
            $response['response_message'] = $message;
            $response = json_encode($response);
            return $response;
        }
    }