<?php

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Routing\Router;
use Pmguru\Framework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// подключение переменных окружения
$dotenv = new Dotenv();
$dotenv->load( BASE_PATH . '/.env' );

// Application parameters
$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH . '/views';

// Application services:
// создаем контейнер
$container = new Container();

// подключаем делегирование
$container->delegate( new ReflectionContainer( true ) );

// добавляем переменную окружения типа string
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

// добавляем загрузчик шаблонизатора twig в контейнер
$container->addShared('twig-loader', FilesystemLoader::class)
	->addArgument(new StringArgument($viewsPath));

$container->addShared( 'twig', Environment::class)
	->addArgument('twig-loader');

$container->inflector( AbstractController::class)
	->invokeMethod('setContainer', [$container]);

return $container;