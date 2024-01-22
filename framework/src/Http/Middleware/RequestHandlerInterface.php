<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

interface RequestHandlerInterface
{
    
    public function handle(Request $request)
    : Response;
    
    public function injectMiddleware(array $middleware)
    : void;
    
}