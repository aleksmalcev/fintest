<?php

namespace Fintest;

class App
{
    private static $appNamespace = '\Fintest';

    static function getRootModelNamespace()
    {
        return self::$appNamespace.'\Model';
    }

    static function getRootControllerNamespace()
    {
        return self::$appNamespace.'\Controller';
    }

    static function getRootViewNamespace()
    {
        return self::$appNamespace.'\View';
    }

    static function getDbConnectionProp()
    {
        $res = [
            'host' => 'localhost',
            'user' => 'test',
            'psw' => 'test',
            'db' => 'fintest',
        ];
        return $res;
    }

}