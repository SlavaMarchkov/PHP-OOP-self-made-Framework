<?php

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Routing\Router;
use Pmguru\Framework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;

// подключение переменных окружения
$dotenv = new Dotenv();
$dotenv->load( BASE_PATH . '/.env' );

// Application parameters
$routes = include BASE_PATH . '/routes/web.php';

// Application services:
// создаем контейнер
$container = new Container();

// подключаем делегирование
$container->delegate( new ReflectionContainer( true ) );

// добавляем переменную окружения типа string
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$container->add( 'APP_ENV', new StringArgument( $appEnv ) );

// добавляем в него Router
$container->add( RouterInterface::class, Router::class );

// расширяем контейнер, добавляя в него вызов метода и параметры для метода
$container->extend( RouterInterface::class )
	->addMethodCall( 'registerRoutes', [new ArrayArgument( $routes )] );

// добавляем в контейнер Kernel
$container->add( Kernel::class )
	->addArgument( RouterInterface::class )
	->addArgument( $container );

return $container;