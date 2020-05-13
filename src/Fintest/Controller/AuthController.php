<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 03.05.2020
 * Time: 13:48
 */

namespace Fintest\Controller;

use Fintest\Service\AuthMgn;
use Fintest\View\AuthView;
use Fintest\Service\ModelMng;


class AuthController extends BaseController
{
    public function isFreeAuthAction($actionName)
    {
        return true;
    }

    function doExit()
    {
        if (! AuthMgn::isCurUser()) {
            return; // no current user, we don't need run action "Exit"
        }

        $authView = new AuthView();
        if ($authView->existAuthExitRequest()) {
            AuthMgn::logoff();
            header("Location: /");
        }
    }

    function doEnter()
    {
        if (AuthMgn::isCurUser()) {
            // - check user
            /** @var \Fintest\Model\UserModel $userModel */
            $userModel = ModelMng::getModel('UserModel');
            $curUserId = AuthMgn::getCurUserId();
            if (! $userModel->isSetUserId($curUserId, true)) {
                AuthMgn::logoff();
                //todo: log this
            }
            return;
        }

        $authView = new AuthView();
        if ($authView->existRequestPostData() && $authView->validate()) {
            $login = $authView->getLoginFromPost();
            $psw = $authView->getPswFromPost();
            try {
                AuthMgn::authenticate($login, $psw);
                if (AuthMgn::isCurUser()) {
                    return;
                }
            } catch (\Throwable $e) {
            }
        }
    }

    function actionEnter()
    {
        $authView = new AuthView();
        if ($authView->existRequestPostData() && $authView->validate()) {
            $login = $authView->getLoginFromPost();
            $psw = $authView->getPswFromPost();
            try {
                if (! AuthMgn::isCurUser()) {
                    //for check user input only! real authentication by method 'doEnter'
                    AuthMgn::findUserId($login, $psw);
                }
            } catch (\Throwable $e) {
                $err = $e->getMessage();
                $authView->setErrInfoExternal($err);
            }
        }
        echo $authView->run();
    }
}