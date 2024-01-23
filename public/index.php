<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/vendor/autoload.php';

use League\Container\Container;
use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Http\Request;

$request = Request::createFromGlobals();

/** @var Container $container */
$container = require BASE_PATH . '/config/services.php';

$eventDispatcher = $container->get(\Pmguru\Framework\Event\EventDispatcher::class);
$eventDispatcher
    ->addListener(
        \Pmguru\Framework\Http\Events\ResponseEvent::class,
        new \App\Listeners\InternalErrorListener()
    )
    ->addListener(
        \Pmguru\Framework\Http\Events\ResponseEvent::class,
        new \App\Listeners\ContentLengthListener()
    )
    ->addListener(
        \Pmguru\Framework\Dbal\Event\EntityPersist::class,
        new \App\Listeners\HandleEntityListener()
    );

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);

// dump($_SESSION);