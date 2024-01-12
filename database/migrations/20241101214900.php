<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
	
	public function up( Schema $schema )
	: void
	{
		$table = $schema->createTable( 'users' );
		$table->addColumn( 'id', Types::INTEGER, [
			'unsigned'      => true,
			'autoincrement' => true,
		] );
		$table->addColumn( 'name', Types::STRING, [
            'length' => 255
        ] );
		$table->addColumn( 'email', Types::STRING, [
            'length' => 255
        ] )->setNotnull(true);
		$table->addColumn( 'password', Types::STRING, [
            'length' => 64
        ] )->setNotnull(true);
		$table->addColumn( 'created_at', Types::DATETIME_IMMUTABLE, [
			'default' => 'CURRENT_TIMESTAMP'
		] );
		$table->setPrimaryKey( ['id'] );
	}
	
	public function down( Schema $schema )
	: void
	{
		// TODO
	}
	
};