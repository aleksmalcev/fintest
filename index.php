<?php

	require_once 'vendor/autoload.php';

	session_start();
    \Fintest\Service\FormGuidMng::run();
	\Fintest\Service\DbMng::run();
	\Fintest\Service\Route::run();
