<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 03.05.2020
 * Time: 13:23
 */

namespace Fintest\Service;

/**
 * Authorization manager
 *
 * Class AuthMgn
 * @package Fintest\Service
 */
class AuthMgn
{
    static function getCurUserId()
    {
        if (isset($_SESSION['cur_user_id'])) {
            return $_SESSION['cur_user_id'];
        } else {
            return null;
        }
    }

    static function isCurUser()
    {
        $curUserId = self::getCurUserId();
        return isset($curUserId);
    }

    static function logoff()
    {
        if (isset($_SESSION['cur_user_id'])) {
            unset($_SESSION['cur_user_id']);
        }
    }

    static function findUserId($login, $psw)
    {
        $userId = null;
        $params =['#login' => $login];
        $query = "SELECT * FROM users WHERE login LIKE '#login'";
        $res = DbMng::query($query, $params);
        if ($res === false) {
            $err = 'Error while authenticate: '. DbMng::error();
            throw new \Exception($err);
        }

        try {
            $numRows = \mysqli_num_rows($res);
            if ($numRows > 1) {
                $err = 'More then 1 results while authenticate';
                throw new \Exception($err);
            }

            $stdErr = 'Login or password incorrect!';
            if ($numRows <= 0) {
                throw new \Exception($stdErr);
            }

            $row = \mysqli_fetch_array($res);
            $userId = $row['id'];
            $userPsw = $row['psw'];

            if (password_verify($psw, $userPsw)) {
                throw new \Exception($stdErr.'(2)');
            }

            $_SESSION['cur_user_id'] = $userId;
        } finally  {
            \mysqli_free_result($res);
        }

        return $userId;
    }


    static function authenticate($login, $psw)
    {
        $_SESSION['cur_user_id'] = null;
        $_SESSION['cur_user_id'] = self::findUserId($login, $psw);
    }

    static function preparePsw($psw)
    {
        $p = 'Rt$-1r'.$psw;
        return password_hash($p, PASSWORD_DEFAULT);
    }

}