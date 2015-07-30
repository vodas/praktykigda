<?php

namespace app\modules\webapi;

class StatusCodeMessage
{
    public static $CREATED = 'Created';
    public static $OK = 'Ok';
    public static $NO_RESPONSE = 'No response';
    public static $BAD_REQUEST = 'Bad Request';
    public static $UNAUTHORIZED = 'Unauthorized';
    public static $PAYMENT = 'Payment Required';
    public static $FORBIDDEN = 'Forbidden';
    public static $NOT_FOUND = 'Not Found';
    public static $INERNAL = 'Internal Server Error';
    public static $NOT_IMPLEMENTED = 'Not Implemented';
    public static $LOGGED = 'You are logged';
    public static $REQUIRED = 'Login, mail and pass required';
    public static $ALREADY = 'Already in database';
}