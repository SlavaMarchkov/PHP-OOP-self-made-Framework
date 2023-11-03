<?php

namespace Pmguru\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class ConnectionFactory
{
	
	public function __construct(
		private readonly string $databaseUrl,
	)
	{
	}
	
	/**
	 * @throws Exception
	 */
	public function create()
	: Connection
	{
		try {
			return DriverManager::getConnection( [
				'url' => $this->databaseUrl,
			] );
		} catch ( Exception $e ) {
			throw new Exception('Error connecting to Database: ' . $e->getMessage(), 400);
		}
	}
	
}