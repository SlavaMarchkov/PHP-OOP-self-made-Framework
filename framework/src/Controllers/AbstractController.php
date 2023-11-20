<?php

namespace Pmguru\Framework\Controllers;

use League\Container\Exception\ContainerException;
use Pmguru\Framework\Http\Request;
use Pmguru\Framework\Http\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractController
{
	
	protected ?ContainerInterface $container = null;
	protected Request $request;
	
	public function setContainer( ContainerInterface $container )
	: void
	{
		$this->container = $container;
	}
	
	public function setRequest( Request $request )
	: void
	{
		$this->request = $request;
	}
	
	public function render( string $view, array $parameters = [], Response $response = null )
	: Response
	{
		try {
			$content = $this->container->get( 'twig' )->render( $view, $parameters );
			$response ??= new Response();
			$response->setContent($content);
			return $response;
		} catch ( NotFoundExceptionInterface|ContainerExceptionInterface $e ) {
			throw new ContainerException($e->getMessage());
		}
	}
	
}