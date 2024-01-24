<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\HandleEntityListener;
use App\Listeners\InternalErrorListener;
use Pmguru\Framework\Dbal\Event\EntityPersist;
use Pmguru\Framework\Event\EventDispatcher;
use Pmguru\Framework\Http\Events\ResponseEvent;
use Pmguru\Framework\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    
    private array $listen = [
        ResponseEvent::class => [
            InternalErrorListener::class,
            ContentLengthListener::class,
        ],
        EntityPersist::class => [
            HandleEntityListener::class,
        ],
    ];
    
    public function __construct(
        private readonly EventDispatcher $eventDispatcher,
    )
    {
    }
    
    /**
     * @return void
     */
    public function register()
    : void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->eventDispatcher->addListener($event, new $listener);
            }
        }
    }
    
}