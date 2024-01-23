<?php

declare(strict_types=1);

namespace Pmguru\Framework\Dbal;

abstract class Entity
{
    abstract function setId(int $id);
}