<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 01.05.2020
 * Time: 0:23
 */

namespace Fintest\View;

use Fintest\Service\AuthMgn;


class BaseTemplate
{
    public function render($title, $pageContent)
    {
        $homePart = '<a href="/">Home</a>';
        $logoffPart = $this->getLogoffPart();
        return "<!DOCTYPE html>
<html lang=\"ru\">
<head>
<meta name=\"viewport\" content=\"width=device-width, user-scalable=yes, initial-scale=1\">
<meta charset=\"utf-8\">
<title>$title</title>
</head>
<body>
$pageContent
<div style='margin-top: 100px;'>
<hr>
<p>
$homePart | $logoffPart
</p>
</div>
</body>
</html>";
    }

    private function getLogoffPart()
    {
        if (AuthMgn::isCurUser()) {
            $part = '<a href="?exit=1">Exit</a>';
        } else {
            $part = '<a href="/auth/enter">Enter</a>';
        }
        return $part;
    }


}