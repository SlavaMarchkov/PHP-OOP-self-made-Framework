<?php

declare(strict_types=1);

namespace App\Listeners;

use Pmguru\Framework\Dbal\Event\EntityPersist;

final class HandleEntityListener
{
    
    public function __invoke(EntityPersist $event)
    {
        dd($event->getEntity());
    }
    
}