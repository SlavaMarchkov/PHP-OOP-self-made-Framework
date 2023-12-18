<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Http\Middleware\MiddlewareInterface;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

final class Authenticate implements MiddlewareInterface
{
    
    private bool $isAuthenticated = true;
    
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response {
        if (!$this->isAuthenticated) {
            return new Response('Authentication failed', 401);
        }
        
        return $handler->handle($request);
    }
    
}