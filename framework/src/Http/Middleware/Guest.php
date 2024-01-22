<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Authentication\SessionAuthInterface;
use Pmguru\Framework\Http\RedirectResponse;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Pmguru\Framework\Session\SessionInterface;

class Guest implements MiddlewareInterface
{
    
    
    public function __construct(
        private readonly SessionAuthInterface $auth,
        private readonly SessionInterface $session,
    )
    {
    }
    
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response {
        $this->session->start();
        
        if ($this->auth->check()) {
            return new RedirectResponse('/dashboard');
        }
        
        return $handler->handle($request);
    }
    
}