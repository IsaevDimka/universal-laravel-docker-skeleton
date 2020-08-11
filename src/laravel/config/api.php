<?php

return [
    'tokens' => [
        'siCDlZC4eNyKw45d1sxq', // test token
    ],

    'ipWhiteList' => [
    ],

    /*
     * Api Debug Mode
     */
    'debug' => env('API_DEBUG', false),

    /*
     * Turn to string the status code in the json response's body.
     */
    'stringify' => false,

    /*
     * Set the status code from the json response to be the same as the status code
     * in the json response's body.
     */
    'match_status' => true,

    /*
     * Include the count of the "data" in the JSON response
     */
    'include_data_count' => true,

    /*
     * Include the info of application: environment, locale, version
     */
    'include_app_info' => true,

    /*
     * Include default message by http code if "message" is null in the JSON response
     */
    'include_nullable_message_status_text' => true,

    /*
     * Json response's body labels.
     */
    'keys'      => [
        'status'     => 'status',
        'message'    => 'message',
        'data'       => 'data',
        'data_count' => 'data_count',
    ],

    /*
     * Response default messages.
     */
    'messages' => [
        'success'    => 'Process is successfully completed',
        'bad'        => 'Bad Request',
        'notfound'   => 'Sorry no results query for your request',
        'validation' => 'Validation Failed please check the request attributes and try again',
        'forbidden'  => 'You don\'t have permission to access this content',
        'error'      => 'Server error, please try again later',
    ],
];
