<?php

namespace Fintest\View;


use Fintest\Service\Route;


class MainPageView extends BaseView
{
    public function run()
    {
        $title = 'Main page';

        $defUrl = Route::getDefaultUrl();

        $pageContent = '<h1>Welcome to the Fintest application!</h1>';
        $pageContent .= '<p>For manage your bank account - Go to <a href="'.$defUrl.'">Balance page</a></p>';
        $pageContent .= '<p></p>';
        $pageContent .= '<p>Some lost page for test only: <a href="/aaaaa">some lost page</a></p>';

        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }

}