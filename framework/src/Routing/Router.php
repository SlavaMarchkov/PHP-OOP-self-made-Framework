<?php

namespace Pmguru\Framework\Routing;

use League\Container\Container;
use Pmguru\Framework\Controllers\AbstractController;
use Pmguru\Framework\Http\Exceptions\MethodNotAllowedException;
use Pmguru\Framework\Http\Exceptions\RouteNotFoundException;
use Pmguru\Framework\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Router implements RouterInterface
{
	
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
        $handler = $request->getRouteHandler();
        $args = $request->getRouteArgs();
		
		if ( is_array( $handler ) ) {
			[$controllerId, $method] = $handler;
			$controller = $container->get( $controllerId );
			
			if ( is_subclass_of( $controller, AbstractController::class ) ) {
				$controller->setRequest( $request );
			}
			
			$handler = [$controller, $method];
		}
		
		return [$handler, $args];
	}
	
}