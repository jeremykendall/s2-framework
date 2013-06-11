<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Framework\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$sc = include dirname(__DIR__) . '/src/container.php';
$sc->setParameter('routes', include dirname(__DIR__) . '/src/app.php');

$request = Request::createFromGlobals();
//$request = Request::create('/is_leap_year/2012');

$response = $sc->get('framework')->handle($request);

$response->send();
