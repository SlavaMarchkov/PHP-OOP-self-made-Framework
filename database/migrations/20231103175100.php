<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
	
	public function up( Schema $schema )
	: void
	{
		$table = $schema->createTable( 'comments' );
		$table->addColumn( 'id', Types::INTEGER, [
			'unsigned'      => true,
			'autoincrement' => true,
		] );
		$table->addColumn( 'content', Types::TEXT );
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