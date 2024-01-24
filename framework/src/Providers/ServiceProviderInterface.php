<?php

declare(strict_types=1);

namespace Pmguru\Framework\Providers;

interface ServiceProviderInterface
{
    public function register()
    : void;
}