<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 03.05.2020
 * Time: 13:24
 */

namespace Fintest\Model;


class UserModel
{
    public function isSetUserId($userId, $deepTest = false)
    {
        if (! isset($userId)) {
            return false;
        }
        $id = intval($userId);
        if ($id <= 0) {
            return false;
        }
        if ( (string) $id != (string) $userId) {
            return false;
        }
        if ($deepTest) {
            //todo: check $userId in database
        }
        return true;
    }



}