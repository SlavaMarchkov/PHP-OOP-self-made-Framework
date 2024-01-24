<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Pmguru\Framework\Authentication\SessionAuthentication;
use Pmguru\Framework\Authentication\SessionAuthInterface;
use Pmguru\Framework\Console\Application;
use Pmguru\Framework\Console\Commands\MigrateCommand;
use Pmguru\Framework\Console\Kernel as ConsoleKernel;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Dbal\ConnectionFactory;
use Pmguru\Framework\Event\EventDispatcher;
use Pmguru\Framework\Http\Kernel;
use Pmguru\Framework\Http\Middleware\ExtractRouteInfo;
use Pmguru\Framework\Http\Middleware\RequestHandler;
use Pmguru\Framework\Http\Middleware\RequestHandlerInterface;
use Pmguru\Framework\Http\Middleware\RouterDispatch;
use Pmguru\Framework\Routing\Router;
use Pmguru\Framework\Routing\RouterInterface;
use Pmguru\Framework\Session\Session;
use Pmguru\Framework\Session\SessionInterface;
use Pmguru\Framework\Template\TwigFactory;
use Symfony\Component\Dotenv\Dotenv;

// подключение переменных окружения
$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

// Application parameters
$basePath = dirname(__DIR__);
$routes = include $basePath . '/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = $basePath . '/views';
$databaseUrl = 'pdo-mysql://root@localhost:3306/php_framework?charset=utf8mb4';

// Application services:
// создаем контейнер
$container = new Container();

// добавляем переменную базового пути
$container->add('base-path', new StringArgument($basePath));

// подключаем делегирование
$container->delegate(new ReflectionContainer(true));

// добавляем пространство имен
$container->add('framework-commands-namespace', new StringArgument('Pmguru\\Framework\\Console\\Commands\\'));

// добавляем переменную окружения типа string
$container->add('APP_ENV', new StringArgument($appEnv));

// добавляем в него Router
$container->add(RouterInterface::class, Router::class);

// добавляем обработку Middleware
$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->addShared(EventDispatcher::class);

// добавляем в контейнер Kernel
$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcher::class,
    ]);

// добавляем загрузчик шаблонизатора twig в контейнер
$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class,
        SessionAuthInterface::class
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

// подключаем абстрактный контроллер
$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

// добавляем подключение к базе данных
$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container)
: Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)
    ->addArgument($container);

// добавляем в контейнер консоль-Kernel
$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument($basePath . '/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container
    ]);

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class
    ]);

$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));

return $container;