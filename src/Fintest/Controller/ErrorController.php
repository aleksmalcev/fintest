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

        echo '<table>';
        foreach ($_SERVER as $k => $val)
        {
            echo '<tr>';
            echo '<td>'.$k;
            echo '</td>';
            echo '<td>'.$val;
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';


        exit;
    }
}