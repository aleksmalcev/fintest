<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 03.05.2020
 * Time: 14:23
 */

namespace Fintest\Service;


class FormGuidMng
{
    static function run()
    {
        if (isset($_SESSION['FormGuid'])) {
            return;
        }
        $_SESSION['FormGuid'] = self::generateFormGuidValue();
    }

    static function getFormGuid()
    {
        if (! isset($_SESSION['FormGuid'])) {
            throw new \Exception('Call run method before form guid getting');
        }
        return $_SESSION['FormGuid'];
    }

    static function checkFormGuid($guid)
    {
        if (empty($guid)) {
            return false;
        }
        return self::getFormGuid() === $guid;
    }

    static private function generateFormGuidValue()
    {
        $rb = random_bytes(8);
        return 'FG.'.bin2hex($rb);
    }
}

