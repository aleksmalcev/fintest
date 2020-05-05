<?php

namespace Fintest\Service;

use Fintest\Controller\AuthController;
use Fintest\Controller\ErrorController;


class Route
{
    /**
     * Return array with controllerName and actionName
     *  ['controllerName'=>, 'actionName'=>]
     *
     * @param $uri
     * @return array
     */
    static function processUri($uri)
    {
        $ipos = strpos($uri, '?');
        if ($ipos !== false) {
            $uri = substr($uri, 0, $ipos);
        }

        $res = [];
        $uri = strtolower($uri);
        $uriParts = explode('/', $uri);
        if (count($uriParts) < 2) {
            return $res;
        }

        // store controller name and action
        $res['controllerName'] = $uriParts[1];
        if (count($uriParts) > 2) {
            $res['actionName'] = $uriParts[2];
        } else {
            $res['actionName'] = '';
        }

        // remove word separator "-" and make camel notation word
        foreach ($res as $key => $val) {
            $words = explode('-', $val);
            foreach ($words as $key1 => $val) {
                $words[$key1] = ucfirst($val);
            }
            $res[$key] = implode('',$words);
        }

        return $res;
    }

    static function run()
    {
        $ctrlData = self::processUri($_SERVER['REQUEST_URI']);

        $authCtrl = new AuthController();
        $authCtrl->doExit();

        $errorController = new ErrorController();
        if (empty($ctrlData)) {
            $errorController->actionError404();
        }

        $controllerName = $ctrlData['controllerName'];
        $actionName = '';
        if (empty($controllerName)) {
            $controllerName = CtrlMng::mainPageControllerName();
            $actionName = CtrlMng::mainPageControllerActionName();
        }

        $controller = CtrlMng::getController($controllerName);
        if (! isset($controller)) {
            $errorController->actionError404();
        }

        if (empty($actionName)) {
            $actionName = $ctrlData['actionName'];
        }

        $authCtrl->doEnter();

        session_write_close(); // Session closed at this point, so no one controller action can write $_SESSION

        if ($controller->isFreeAuthAction($actionName) || AuthMgn::isCurUser()) {
            if (!$controller->execAction($actionName)) {
                $errorController->actionError404();
            }
        } else {
            $authCtrl->actionEnter();
        }
    }

    static private function prepareNamesForUrl($name)
    {
        $res = [];
        preg_match_all('/[A-Z][^A-Z]+/',$name,$res,PREG_PATTERN_ORDER);
        $name = implode('-', $res[0]);
        $name = strtolower($name);
        return $name;
    }

    static function getUrl($controllerName, $actionName)
    {
        $errPart = 'should used latin letters, words without space, each word start uppercase letter';
        $controllerNameForUrl = self::prepareNamesForUrl($controllerName);
        if (empty($controllerNameForUrl)) {
            $err = 'Error controller name:'.$controllerName.' Controller name '.$errPart;
            throw new \Exception($err);
        }

        $actionNameForUrl = self::prepareNamesForUrl($actionName);
        if (empty($actionNameForUrl)) {
            $err = 'Error action name:'.$controllerName.' Action name '.$errPart;
            throw new \Exception($err);
        }
        return '/'.$controllerNameForUrl.'/'.$actionNameForUrl;
    }

    static function getDefaultUrl()
    {
        $controllerName = CtrlMng::defaultControllerName();
        $actionName = CtrlMng::defaultControllerActionName();
        return self::getUrl($controllerName, $actionName);
    }
}
