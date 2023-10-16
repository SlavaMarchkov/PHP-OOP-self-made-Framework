<?php

namespace Pmguru\Framework\Http;

use Pmguru\Framework\Http\Exceptions\HttpException;
use Pmguru\Framework\Routing\RouterInterface;
use Throwable;

class Kernel
{
	
	public function __construct(
		private readonly RouterInterface $router,
	)
	{
	}
	
	public function handle( Request $request )
	: Response
	{
		try {
			[$routeHandler, $vars] = $this->router->dispatch( $request );
			$response = call_user_func_array( $routeHandler, $vars );
		} catch ( HttpException $e ) {
			$response = new Response( $e->getMessage(), $e->getStatusCode() );
		} catch ( Throwable $e ) {
			$response = new Response( $e->getMessage(), 500 );
		}
		
		return $response;
	}
	
}