<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Http\Middleware\MiddlewareInterface;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Pmguru\Framework\Session\SessionInterface;

final class StartSession implements MiddlewareInterface
{
 
    public function __construct(
        private readonly SessionInterface $session
    )
    {
    }
    
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response {
        $this->session->start();
        $request->setSession($this->session);
        
        return $handler->handle($request);
    }
    
}