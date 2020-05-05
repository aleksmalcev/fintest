<?php

namespace Fintest\Controller;


use Fintest\View\BalanceView;
use Fintest\View\BalanceDebitingView;
use Fintest\View\BalanceDebitingCheckView;
use Fintest\Service\ModelMng;
use Fintest\Service\AuthMgn;
use Fintest\Service\Route;


class BalanceController extends BaseController
{
    public function actionInfo()
    {
        $view = new BalanceView();
        echo $view->run();
    }

    public function actionCheckBeforeDebiting()
    {
        $view = new BalanceDebitingCheckView();
        echo $view->run();
    }

    public function actionDebiting()
    {
        $view = new BalanceDebitingView();
        if ($view->existRequestPostData() && $view->validate()) {
            $debitingVal = $view->getDebitingValFromPost();
            $userId = AuthMgn::getCurUserId();
            /** @var \Fintest\Model\BankAccountModel $bankAccountModel */
            $bankAccountModel = ModelMng::getModel('BankAccount');
            try {
                $bankAccountModel->debitingMoney($userId, $debitingVal);
            } catch (\Throwable $e) {
                $err = $e->getMessage();
                $view->setErrInfoExternal($err);
            }
            $debitingUrl = Route::getUrl('Balance', 'Debiting');
            header("Location: ".$debitingUrl);
            exit;
        }

        echo $view->run();
    }
}