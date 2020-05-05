<?php

namespace Fintest\Controller;


use Fintest\View\Error404View;


class ErrorController extends BaseController
{
    public function actionError404()
    {
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");

        $error404View = new Error404View();
        echo $error404View->run();

        exit;
    }
}