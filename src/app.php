<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('hello', new Route('/hello/{name}', array(
    'name' => 'World',
    '_controller' => 'render_template',
)));

$routes->add('bye', new Route('/bye', array(
    '_controller' => 'render_template',
)));

return $routes;
