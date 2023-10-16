<?php

namespace Pmguru\Framework\Http\Exceptions;

use Exception;

class HttpException extends Exception
{
	
	private int $statusCode = 400;
	
	public function setStatusCode( int $statusCode )
	: HttpException
	{
		$this->statusCode = $statusCode;
		return $this;
	}
	
	public function getStatusCode()
	: int
	{
		return $this->statusCode;
	}
	
}