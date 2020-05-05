<?php

namespace Fintest\Service;


use Fintest\App;


class DbMng
{
    private static $dbLink;

    static function run()
    {
        if (isset(self::$dbLink))
            return;
        $prop = App::getDbConnectionProp();
        $link = \mysqli_connect($prop['host'], $prop['user'], $prop['psw'], $prop['db']);
        if ($link == false){
            print("Error. No connection with database: " . \mysqli_connect_error());
        }
        else {
            self::$dbLink = $link;
        }
    }

    static function getDbLink()
    {
        return self::$dbLink;
    }

    static function query($queryStr, $params)
    {
        $link = self::getDbLink();
        if (! empty($params)) {
            $values = array_values($params);
            foreach ($values as $ind => $val) {
                $val = \mysqli_real_escape_string($link, $val);
                $values[$ind] = $val;
            }
            $queryStr = str_replace(array_keys($params), $values, $queryStr);
        }

        return \mysqli_query($link, $queryStr);
    }

    static function error()
    {
        $link = self::getDbLink();
        return \mysqli_error($link);
    }

}