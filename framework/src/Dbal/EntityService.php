<?php

declare(strict_types=1);

namespace Pmguru\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Pmguru\Framework\Dbal\Event\EntityPersist;
use Pmguru\Framework\Event\EventDispatcher;

final class EntityService
{
    
    public function __construct(
        private Connection $connection,
        private EventDispatcher $eventDispatcher,
    ) {
    }
    
    public function getConnection()
    : Connection
    {
        return $this->connection;
    }
    
    /**
     * @throws Exception
     */
    public function save(Entity $entity)
    : int {
        $entityId = (int) $this->connection->lastInsertId();
        $entity->setId($entityId);
        $this->eventDispatcher->dispatch(new EntityPersist($entity));
        
        return $entityId;
    }
    
}