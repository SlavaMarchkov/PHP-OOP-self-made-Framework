<?php

namespace Pmguru\Framework\Http;

class Response
{
	
	public function __construct(
		private string $content = '',
		private readonly int   $statusCode = 200,
		private readonly array $headers = [],
	)
	{
		http_response_code( $this->statusCode );
	}
	
	public function send()
	: void
	{
		echo $this->content;
	}
	
	public function setContent( string $content )
	: static
	{
		$this->content = $content;
		return $this;
	}
	
}