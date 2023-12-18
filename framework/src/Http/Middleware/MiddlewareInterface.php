<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

interface MiddlewareInterface
{
    
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response;
    
}