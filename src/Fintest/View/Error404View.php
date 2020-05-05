<?php

namespace Fintest\View;

use Fintest\Service\Route;


class Error404View extends BaseView
{
    public function run()
    {
        $title = 'Error404 page';

        $defUrl = Route::getDefaultUrl();

        $pageContent = '<h1>404 error.</h1>';
        $pageContent .= '<p>Ooops... looks like the page is lost...</p>';
        $pageContent .= '<p>What about <a href="'.$defUrl.'">this one?</a></p>';

        $baseTemplate = new BaseTemplate();
        return $baseTemplate->render($title, $pageContent);
    }
}