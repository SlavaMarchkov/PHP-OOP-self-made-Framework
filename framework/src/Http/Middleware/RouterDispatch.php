<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Pmguru\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

final class RouterDispatch implements MiddlewareInterface
{
    
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }
    
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response {
         [$routeHandler, $vars] = $this->router->dispatch( $request, $this->container );
         $response = call_user_func_array( $routeHandler, $vars );
         return $response;
    }
}