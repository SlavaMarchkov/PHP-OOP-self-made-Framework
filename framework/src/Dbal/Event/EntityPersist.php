<?php

declare(strict_types=1);

namespace Pmguru\Framework\Dbal\Event;

use Pmguru\Framework\Dbal\Entity;
use Pmguru\Framework\Event\Event;

final class EntityPersist extends Event
{
    
    public function __construct(
        private Entity $entity
    ) {
    }
    
    public function getEntity()
    : Entity
    {
        return $this->entity;
    }
    
}