<?php

declare(strict_types=1);

namespace App\Listeners;

use Pmguru\Framework\Http\Events\ResponseEvent;

class ContentLengthListener
{
    
    public function __invoke(ResponseEvent $event)
    : void {
        $response = $event->getResponse();
        
        if (!array_key_exists('X-Content-Length', $response->getHeaders())) {
            $response->setHeader('X-Content-Length', strlen($response->getContent()));
        }
        
        // dump('here');
    }
    
}