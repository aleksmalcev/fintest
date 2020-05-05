<?php

namespace Fintest\Common;

class UserInput
{
    static function strFrom($str, $maxLength, $doSafe = true)
    {
        $res = trim($str);
        if($maxLength > 0) {
            if( strlen($res) > $maxLength ) {
                $res = substr($res, 0, $maxLength);
            }
        }

        if($doSafe)
            $res = htmlspecialchars($res);
        return $res;
    }

    static function strFromPost($name, $maxLength, $doSafe = true)
    {
        if( ! isset($_POST[$name]) )
            return null;

        return self::strFrom($_POST[$name], $maxLength, $doSafe);
    }

    static function strFromGet($name, $maxLength, $doSafe = true)
    {
        if( ! isset($_GET[$name]) )
            return null;

        return self::strFrom($_GET[$name], $maxLength, $doSafe);
    }

    static function intFromPost($name, $max_length = 9)
    {
        $res = self::strFromPost($name, $max_length);
        $res = intval($res);
        return $res;
    }

    static function floatFrom($str)
    {
        $res = str_replace(',', '.', $str);
        return floatval($res);
    }

    static function floatFromPost($name, $max_length = 9)
    {
        $res = self::strFromPost($name, $max_length);
        return self::floatFrom($res);
    }
}