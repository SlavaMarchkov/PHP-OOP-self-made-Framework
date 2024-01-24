<?php

declare(strict_types=1);

use App\Providers\EventServiceProvider;
use League\Container\Container;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/** @var Container $container */

$providers = [
    EventServiceProvider::class,
];

try {
    foreach ($providers as $provider) {
        $provider = $container->get($provider);
        $provider->register();
    }
} catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
}