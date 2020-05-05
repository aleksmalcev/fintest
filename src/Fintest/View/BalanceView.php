<?php

namespace Fintest\View;


class BalanceView extends BaseView
{
    public function run()
    {
        $title = 'Balance page';

        $pageContent = '<p> ~~~~~~~~~~ Hello from BalanceController - actionInfo! ~~~~~~~~~~~~~</p>';

        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }

}