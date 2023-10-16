<?php

define('BASE_PATH', dirname( __DIR__ ));
require_once BASE_PATH . '/vendor/autoload.php';

use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Routing\Router;

$request = Request::createFromGlobals();

$router = new Router();

$kernel = new Kernel($router);
$response = $kernel->handle( $request );
$response->send();