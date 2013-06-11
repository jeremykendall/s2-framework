<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

function render_template($request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__ . '/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}

if (!isset($request)) {
    $request = Request::createFromGlobals();
}

$routes = include dirname(__DIR__) . '/src/app.php';

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try { 
    $request->attributes->add($matcher->match($request->getPathInfo()));
    $response = call_user_func($request->attributes->get('_controller'), $request);
} catch (ResourceNotFoundException $e) {
    $response = new Response('Not Found', 404);
} catch (Exception $e) {
    $response = new Response('An error occurred', 500);
}

$response->prepare($request);
$response->send();
