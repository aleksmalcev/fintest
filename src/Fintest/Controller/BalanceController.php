<?php

namespace Fintest\Controller;


use Fintest\View\BalanceView;


class BalanceController extends BaseController
{
    public function actionInfo()
    {
        $balanceView = new BalanceView();
        echo $balanceView->run();
    }
}