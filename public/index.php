<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';

use League\Container\Container;
use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

$request = Request::createFromGlobals();

/** @var Container $container */
$container = require BASE_PATH . '/config/services.php';

// подключаем сервис-провайдеры
require_once BASE_PATH . '/bootstrap/bootstrap.php';

try {
    $kernel = $container->get(Kernel::class);
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (NotFoundExceptionInterface|ContainerExceptionInterface|Exception $e) {
}

// dump($_SESSION);