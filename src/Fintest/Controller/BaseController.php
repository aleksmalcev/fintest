<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 30.04.2020
 * Time: 17:16
 */

namespace Fintest\Controller;


abstract class BaseController
{

    /**
     * Action execution
     *
     * @param $actionName
     * @return bool
     */
    public function execAction($actionName)
    {
        $action = 'action'.$actionName;
        if (method_exists($this, $action)) {
            $this->$action();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return false if action should execute only authenticated user
     *
     * @param $actionName
     * @return bool
     */
    public function isFreeAuthAction($actionName)
    {
        return false;
    }
}