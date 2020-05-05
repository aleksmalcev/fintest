<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 05.05.2020
 * Time: 0:33
 */

namespace Fintest\Controller;

use Fintest\View\MainPageView;


class MainPageController extends BaseController
{
    public function isFreeAuthAction($actionName)
    {
        return true;
    }

    public function actionInfo()
    {
        $mainPageView = new MainPageView();
        echo $mainPageView->run();
    }
}