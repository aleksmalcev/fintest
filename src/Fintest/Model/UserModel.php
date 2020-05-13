<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 03.05.2020
 * Time: 13:24
 */

namespace Fintest\Model;

use Fintest\Service\DbMng;

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

    /**
     * If user by $login exists then return array with user info, else return null
     *
     * @param $login
     * @return array|null
     * @throws \Exception
     */
    public function getUserByLogin($login)
    {
        $params =['#login' => $login];
        $query = "SELECT * FROM users WHERE login LIKE '#login'";
        $res = DbMng::query($query, $params);
        if ($res === false) {
            $err = DbMng::error();
            throw new \Exception($err);
        }

        try {
            $numRows = \mysqli_num_rows($res);
            if ($numRows > 1) {
                $err = 'More then 1 results by login';
                throw new \Exception($err);
            }

            if ($numRows <= 0) {
                // user not exists by $login
                return null;
            }

            $userInfo = \mysqli_fetch_array($res);
            return $userInfo;
        } finally  {
            \mysqli_free_result($res);
        }
    }

}