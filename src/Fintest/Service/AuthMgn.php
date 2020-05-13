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
        /** @var \Fintest\Model\UserModel $userModel */
        $userModel = ModelMng::getModel('UserModel');
        $userInfo = $userModel->getUserByLogin($login);
        $stdErr = 'Login or password incorrect!';
        if (empty($userInfo)) {
            throw new \Exception($stdErr);
        }

        $userPsw = $userInfo['psw'];
        if (password_verify($psw, $userPsw)) {
            throw new \Exception($stdErr.'(2)');
        }
        return $userInfo['id'];
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