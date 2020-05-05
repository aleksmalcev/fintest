<?php

namespace Fintest\View;

use Fintest\Service\Route;


class BalanceDebitingView extends BalanceDebitingCheckView
{
    private $errInfoExternal;


    public function setErrInfoExternal($errInfo)
    {
        $this->errInfoExternal = $errInfo;
    }

    public function getErrInfoExternal()
    {
        return $this->errInfoExternal;
    }

    protected function getTitle()
    {
        return 'Balance debiting page';
    }

    protected function getContent()
    {
        if (empty($this->errInfo) && empty($this->errInfoExternal)) {
            $pageContent = $this->getContentIfOk();
        } else {
            $pageContent = $this->getContentIfErr();
        }

        return $pageContent;
    }


    private function getContentIfOk()
    {
        $balanceVal = $this->getCurBalanceVal();
        $balanceInfoUrl = Route::getUrl('Balance', 'Info');
        return '
<h1>Success debiting</h1>

<p>
Your new account balance :  '.$balanceVal.'       
</p>

<p>
    <a href="'.$balanceInfoUrl.'">Balance page</a>
</p>
';
    }

    private function getContentIfErr()
    {
        $balanceVal = $this->getCurBalanceVal();
        $debitingVal = $this->getDebitingValFromPost();
        $balanceInfoUrl = Route::getUrl('Balance', 'Info');
        $errPart = $this->getErrorContentPart();

        return '
<h1>Debiting ERROR!</h1>

<p>
Your account balance :  '.$balanceVal.'       
</p>

<p>
Your ask for debiting:  '.$debitingVal.'       
</p>

        
<h2>Error</h2>
        '.$errPart.'
    
<p>--------------------------</p>

    <p>
        <a href="'.$balanceInfoUrl.'">Balance page</a>
    </p>
';
    }

    private function getErrorContentPart()
    {
        $res = '';
        $errs = [];
        if (! empty($this->getErrInfo())) {
            $errs[] = $this->getErrInfo();
        }
        if (! empty($this->getErrInfoExternal())) {
            $errs[] = $this->getErrInfoExternal();
        }

        if (! empty($errs)) {
            $res .= '<ul>';
            foreach ($errs as $err) {
                $res .= '<li>'.$err.'</li>';
            }
            $res .= '</ul>';
        }
        return $res;
    }

}