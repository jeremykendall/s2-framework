<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Framework\LeapYearController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('leap_year', new Route('/is_leap_year/{year}', array(
    'year' => null,
    '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction',
)));

return $routes;
