<?php

declare(strict_types=1);

namespace App\Listeners;

use Pmguru\Framework\Http\Events\ResponseEvent;

class InternalErrorListener
{
    
    public function __invoke(ResponseEvent $event)
    : void {
        if ($event->getResponse()->getStatusCode() >= 500) {
            $event->stopPropagation();
        }
    }
    
}