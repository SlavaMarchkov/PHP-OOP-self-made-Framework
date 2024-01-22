<?php

namespace Pmguru\Framework\Routing;

use League\Container\Container;
use Pmguru\Framework\Http\Request;

interface RouterInterface
{
	
	public function dispatch( Request $request, Container $container )
	: array;
	
}