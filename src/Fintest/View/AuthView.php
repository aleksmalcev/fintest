<?php

namespace Fintest\View;

use Fintest\Common\UserInput as UI;
use Fintest\Service\AuthMgn;
use Fintest\Service\Route;


class AuthView extends BaseView
{
    static private $fieldLogin = 'user-login';
    static private $fieldPsw = 'user-psw';
    static private $prmExit = 'exit';

    private $fromPost = [];
    private $errInfo;
    private $errInfoExternal;

    public function existAuthExitRequest()
    {
        return UI::strFromGet(self::$prmExit,1) == '1';
    }

    private function prepareFromPostData()
    {
        if (! empty($this->fromPost)) {
            return;
        }
        if (! $this->existRequestPostData()) {
            return;
        }
        $this->fromPost = [];
        $this->fromPost[self::$fieldLogin] = UI::strFromPost(self::$fieldLogin, 128);
        $this->fromPost[self::$fieldPsw] = UI::strFromPost(self::$fieldPsw, 32);
    }

    public function existRequestPostData()
    {
        return isset($_POST[self::$fieldLogin]) and isset($_POST[self::$fieldPsw]);
    }

    public function getLoginFromPost()
    {
        if (isset($this->fromPost[self::$fieldLogin])) {
            return $this->fromPost[self::$fieldLogin];
        }
        else {
            throw new \Exception('Method "validate" need success call before');
        }
    }

    public function getPswFromPost()
    {
        if (isset($this->fromPost[self::$fieldPsw])) {
            return $this->fromPost[self::$fieldPsw];
        }
        else {
            throw new \Exception('Method "validate" need success call before');
        }
    }

    public function validate()
    {
        if (! $this->checkFormGuid()) {
            $this->fromPost = [];
            $this->errInfo =  'Error while authentication. Refresh website page and try again';
            return false;
        }

        $this->prepareFromPostData();
        if ( empty($this->fromPost[self::$fieldLogin]) || empty($this->fromPost[self::$fieldPsw]) ) {
            $this->errInfo =  'No authentication data';
            return false;
        }

        return true;
    }

    public function getErrInfo()
    {
        return $this->errInfo;
    }

    public function setErrInfoExternal($errInfo)
    {
        $this->errInfoExternal = $errInfo;
    }

    public function getErrInfoExternal()
    {
        return $this->errInfoExternal;
    }

    public function run()
    {
        $title = 'Authentication page';
        if (AuthMgn::isCurUser()) {
            $pageContent = $this->getContentWithCurUser();
        } else {
            $pageContent = $this->getContentWithoutCurUser();
        }

        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }

    private function getContentWithCurUser()
    {
        $defUrl = Route::getDefaultUrl();

        $pageContent = '
<h1>You are already logged in</h1><p><a href="/?exit=1">>>Logoff</a></p>
<p>For balance control go to <a href="'.$defUrl.'">Balance page</a></p>
';
        return $pageContent;
    }

    private function getContentWithoutCurUser()
    {
        $pageContent = '
<h1>Login for Fintest application</h1>';

        $errs = [];
        if (! empty($this->getErrInfo())) {
            $errs[] = $this->getErrInfo();
        }
        if (! empty($this->getErrInfoExternal())) {
            $errs[] = $this->getErrInfoExternal();
        }

        if (! empty($errs)) {
            $pageContent .= '<h3>Error!</h3>';
            $pageContent .= '<ul>';
            foreach ($errs as $err) {
                $pageContent .= '<li>'.$err.'</li>';
            }
            $pageContent .= '</ul>';
        }

        $pageContent .=
            '<form method="post" action="">'.$this->getGuidInputHtml().'
<p>
Try use: test@test.test / test
</p>
    <p>
        Login <input type="text" name="'.self::$fieldLogin.'">
    </p>
    <p>
        Password <input type="text" name="'.self::$fieldPsw.'">
    </p>
    <p>
        <input type="submit" value="Enter">
    </p>
</form>
';
        return $pageContent;
    }

}