#!/usr/bin/env php

<?php

use League\Container\Container;
use Pmguru\Framework\Console\Kernel;

// 1. Установить базовый путь
define( 'BASE_PATH', dirname( __FILE__ ) );
// var_dump(BASE_PATH); // string(32) "F:\OSPanel\domains\php-framework"

// 2. Добавить autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// 3. Добавить контейнер
/** @var Container $container */
$container = require BASE_PATH . '/config/services.php';

// 4. Получить ядро (Kernel) консоли из контейнера
$kernel = $container->get( Kernel::class );

// 5. Вызвать метод handle в Kernel, вернуть код статуса консольного приложения
$status = $kernel->handle();

// 6. Выйти с этим статусом
exit( $status );