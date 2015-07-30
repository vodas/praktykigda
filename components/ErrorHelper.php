<?php
namespace app\components;

class ErrorHelper {
    static function errorsToString($errors) {
        $string = "";
        foreach ($errors as $error) {
            foreach ($error as $key => $value) {
                $string .= $value . "\n";
            }
        }
        return $string;
    }
}