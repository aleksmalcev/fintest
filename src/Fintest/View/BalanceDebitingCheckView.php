<?php

namespace Fintest\View;


use Fintest\Service\AuthMgn;
use Fintest\Service\ModelMng;
use Fintest\Service\Route;
use Fintest\Common\UserInput as UI;


class BalanceDebitingCheckView extends BaseView
{
    protected $fromPost = [];
    protected $errInfo;
    protected $curBalanceVal;

    public function run()
    {
        if ($this->existRequestPostData()) {
            $this->validate();
        }

        $title = $this->getTitle();
        $pageContent = $this->getContent();
        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }

    protected function getCurBalanceVal()
    {
        if (! isset($this->curBalanceVal)) {
            $userId = AuthMgn::getCurUserId();
            /** @var \Fintest\Model\BankAccountModel $bankAccountModel */
            $bankAccountModel = ModelMng::getModel('BankAccount');
            $this->curBalanceVal = $bankAccountModel->getBalance($userId);
        }
        return $this->curBalanceVal;
    }

    public function existRequestPostData()
    {
        return isset($_POST[BalanceView::fieldDebitingVal()]);
    }

    protected function prepareFromPostData()
    {
        if (! empty($this->fromPost)) {
            return;
        }
        if (! $this->existRequestPostData()) {
            return;
        }
        $this->fromPost = [];
        $this->fromPost[BalanceView::fieldDebitingVal()] = UI::floatFromPost(BalanceView::fieldDebitingVal(), 64);
    }

    public function getDebitingValFromPost()
    {
        if (isset($this->fromPost[BalanceView::fieldDebitingVal()])) {
            return $this->fromPost[BalanceView::fieldDebitingVal()];
        }
        else {
            throw new \Exception('Method "validate" need success call before');
        }
    }

    public function validate()
    {
        if (! $this->checkFormGuid()) {
            $this->fromPost = [];
            $this->errInfo =  'Data error. Refresh website page and try again';
            return false;
        }

        $this->prepareFromPostData();
        $debitingVal = $this->getDebitingValFromPost();
        if ($debitingVal <=0) {
            $this->errInfo =  'No debiting value. Above zero value required for debiting';
            return false;
        }

        $balanceVal = $this->getCurBalanceVal();
        $newBalanceVal = $balanceVal - $debitingVal;
        if ($newBalanceVal < 0) {
            $this->errInfo =  'Not enough money in the account';
            return false;
        }

        return true;
    }

    protected function getTitle()
    {
        return 'Balance debiting check page';
    }

    protected function getContent()
    {
        if (empty($this->errInfo)) {
            $pageContent = $this->getContentIfOk();
        } else {
            $pageContent = $this->getContentIfErr();
        }

        return $pageContent;
    }

    private function getContentIfOk()
    {
        $balanceVal = $this->getCurBalanceVal();
        $debitingVal = $this->getDebitingValFromPost();
        $newBalanceVal = $balanceVal - $debitingVal;
        $balanceInfoUrl = Route::getUrl('Balance', 'Info');
        $debitingUrl = Route::getUrl('Balance', 'Debiting');
        return '
<h1>Check before debiting</h1>

<p>
Your account balance :  '.$balanceVal.'       
</p>

<p>
You ask for debiting:  '.$debitingVal.'       
</p>

<h2>Account balance after debiting: '.$newBalanceVal.'</h2>

<p>--------------------------</p>
        
<form method="post" action="'.$debitingUrl.'">'.$this->getGuidInputHtml().'
        <input type="hidden" name="'.BalanceView::fieldDebitingVal().'" value="'.$debitingVal.'">
    <p>
        <input type="submit" value="All correct, do debiting">  or <a href="'.$balanceInfoUrl.'">Cancel</a>
    </p>
</form>        
';
    }

    private function getContentIfErr()
    {
        $balanceVal = $this->getCurBalanceVal();
        $debitingVal = $this->getDebitingValFromPost();
        $balanceInfoUrl = Route::getUrl('Balance', 'Info');
        return '
<h1>Check before debiting. ERROR!</h1>

<p>
Your account balance :  '.$balanceVal.'       
</p>

<p>
Your ask for debiting:  '.$debitingVal.'       
</p>

        
<h2>Error</h2>
    <p>
        '.$this->errInfo.'
    </p>
    
<p>--------------------------</p>

    <p>
        <a href="'.$balanceInfoUrl.'">Cancel</a>
    </p>
';
    }

}