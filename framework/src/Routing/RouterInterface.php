<?php

namespace Pmguru\Framework\Routing;

use Pmguru\Framework\Http\Request;

interface RouterInterface
{
	
	public function dispatch( Request $request )
	: array;
	
}