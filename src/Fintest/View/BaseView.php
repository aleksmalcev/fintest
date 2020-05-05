<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 30.04.2020
 * Time: 23:39
 */

namespace Fintest\View;

use Fintest\Common\UserInput as UI;
use Fintest\Service\FormGuidMng;


abstract class BaseView
{
    static private $fieldGuid = 'form-g-u-i-d';

    abstract public function run();

    /**
     * @return bool
     */
    protected function checkFormGuid()
    {
        $fieldGuid = self::getFieldGuid();
        $guid = UI::strFromPost($fieldGuid,64,false);
        return FormGuidMng::checkFormGuid($guid);
    }

    protected function getFieldGuid()
    {
        return self::$fieldGuid;
    }

    protected function getGuidInputHtml()
    {
        return '<input type="hidden" name="'.self::getFieldGuid().'" value="'.FormGuidMng::getFormGuid().'">';
    }

}