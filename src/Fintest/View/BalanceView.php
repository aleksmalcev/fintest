<?php

namespace Fintest\View;

use Fintest\Service\AuthMgn;
use Fintest\Service\ModelMng;
use Fintest\Service\Route;


class BalanceView extends BaseView
{
    static private $fieldDebitingVal = 'debiting-val';

    static function fieldDebitingVal()
    {
        return self::$fieldDebitingVal;
    }

    public function run()
    {
        $title = 'Balance page';
        $pageContent = $this->getContent();
        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }

    private function getContent()
    {
        $userId = AuthMgn::getCurUserId();
        /** @var \Fintest\Model\BankAccountModel $bankAccountModel */
        $bankAccountModel = ModelMng::getModel('BankAccount');
        $balanceVal = $bankAccountModel->getBalance($userId);

        $checkUrl = Route::getUrl('Balance', 'CheckBeforeDebiting');

        $pageContent = '
<h1>Balance</h1>

<p>
Your account balance :  '.$balanceVal.'       
</p>
        
<h2>Debiting operation</h2>
<form method="post" action="'.$checkUrl.'">'.$this->getGuidInputHtml().'
    <p>
        Debiting value <input type="text" name="'.self::$fieldDebitingVal.'">
    </p>
    <p>
        <input type="submit" value="Ok">
    </p>
</form>        
';

        return $pageContent;
    }
}