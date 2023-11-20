<?php

namespace Pmguru\Framework\Routing;

use FastRoute\{Dispatcher, RouteCollector};
use League\Container\Container;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Exceptions\MethodNotAllowedException;
use Pmguru\Framework\Http\Exceptions\RouteNotFoundException;
use Pmguru\Framework\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
	
	private array $routes;
	
	/**
	 * @param Request $request
	 * @param Container $container
	 * @return array
	 * @throws MethodNotAllowedException
	 * @throws RouteNotFoundException
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function dispatch( Request $request, Container $container )
	: array
	{
		[$handler, $vars] = $this->extractRouteInfo( $request );
		
		if ( is_array( $handler ) ) {
			[$controllerId, $method] = $handler;
			$controller = $container->get( $controllerId );
			
			if ( is_subclass_of( $controller, AbstractController::class ) ) {
				$controller->setRequest( $request );
			}
			
			$handler = [$controller, $method];
		}
		
		return [$handler, $vars];
	}
	
	/**
	 * @param array $routes
	 * @return void
	 */
	public function registerRoutes( array $routes )
	: void
	{
		$this->routes = $routes;
	}
	
	/**
	 * @throws MethodNotAllowedException
	 * @throws RouteNotFoundException
	 */
	private function extractRouteInfo( Request $request )
	: array
	{
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
				return [$routeInfo[1], $routeInfo[2]];
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
	}
	
}