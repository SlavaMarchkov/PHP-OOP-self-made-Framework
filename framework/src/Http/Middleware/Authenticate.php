<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Authentication\SessionAuthInterface;
use Pmguru\Framework\Http\RedirectResponse;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Pmguru\Framework\Session\SessionInterface;

class Authenticate implements MiddlewareInterface
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
        
        if (!$this->auth->check()) {
            $this->session->setFlash('errors', 'To get started, you have to sign in first');
            return new RedirectResponse('/login');
        }
        
        return $handler->handle($request);
    }
    
}