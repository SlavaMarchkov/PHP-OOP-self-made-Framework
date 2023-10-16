<?php

namespace Pmguru\Framework\Tests;

class SomecodeClass
{
	
	public function __construct(
		private readonly Pmguru $pmguru,
	)
	{
	}
	
	public function getPmguru()
	: Pmguru
	{
		return $this->pmguru;
	}
	
}