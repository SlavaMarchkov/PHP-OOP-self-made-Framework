<?php

namespace Pmguru\Framework\Console\Commands;

use Pmguru\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
	
	private string $name = 'migrate';
	
	public function execute( array $parameters = [] )
	: int
	{
		dd($parameters);
		return 0;
	}
	
}