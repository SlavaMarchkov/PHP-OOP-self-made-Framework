<?php

declare(strict_types=1);

namespace Pmguru\Framework\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Pmguru\Framework\Http\Exceptions\MethodNotAllowedException;
use Pmguru\Framework\Http\Exceptions\RouteNotFoundException;
use Pmguru\Framework\Http\Middleware\MiddlewareInterface;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;

use function FastRoute\simpleDispatcher;

final class ExtractRouteInfo implements MiddlewareInterface
{
    
    public function __construct(
        private readonly array $routes,
    )
    {
    }
    
    /**
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function process(Request $request, RequestHandlerInterface $handler)
    : Response {
        $dispatcher = simpleDispatcher( function ( RouteCollector $collector ) {
            foreach ( $this->routes as $route ) {
                $collector->addRoute( ...$route );
            }
        } );
        
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath(),
        );
        
        switch ( $routeInfo[0] ) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                
                // Внедрим посредники в обработчик
                // $routeInfo[1][1] = [0 => "Pmguru\Framework\Http\Middleware\Authenticate"]
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode( ', ', $routeInfo[1] );
                $e = new MethodNotAllowedException( "Supported HTTP methods: $allowedMethods" );
                $e->setStatusCode( 405 );
                throw $e;
            default:
                $e = new RouteNotFoundException( 'Route not found' );
                $e->setStatusCode( 404 );
                throw $e;
        }
        
        return $handler->handle($request);
    }
    
}