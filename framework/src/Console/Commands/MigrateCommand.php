<?php

namespace Pmguru\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Pmguru\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
	
	private string $name = 'migrate';
	private const MIGRATIONS_TABLE = 'migrations';
	
	public function __construct(
		private readonly Connection $connection,
		private readonly string     $migrationsPath,
	)
	{
	}
	
	/**
	 * @throws SchemaException
	 * @throws Exception
	 * @throws \Exception
	 */
	public function execute( array $parameters = [] )
	: int
	{
		try {
			$this->connection->setAutoCommit(false);
			
			// 1. Создать таблицу миграций (migrations), если таковая ещё не создана
			$this->createMigrationsTable();
			
			$this->connection->beginTransaction();
			
			// 2. Получить $appliedMigrations (миграции, которые уже есть в базе данных в таблице migrations)
			$appliedMigrations = $this->getAppliedMigrations();
			
			// 3. Получить $migrationFiles из папки миграций
			$migrationFiles = $this->getMigrationFiles();
			
			// 4. Получить миграции для применения
			$migrationsToApply = array_values(
				array_diff( $migrationFiles, $appliedMigrations )
			);
			
			$schema = new Schema();
			
			foreach ( $migrationsToApply as $migration ) {
				$migrationInstance = require $this->migrationsPath . '/' . $migration;
				
				// 5. Создать SQL-запрос для миграций, которые ещё не были выполнены
				$migrationInstance->up( $schema );
				
				// 6. Добавить миграцию в базу данных
				$this->addMigration($migration);
			}
			
			// 7. Выполнить SQL-запрос
			$sqlArray = $schema->toSql( $this->connection->getDatabasePlatform() );
			foreach ( $sqlArray as $sql ) {
				$this->connection->executeQuery( $sql );
				echo 'SQL query executed' . PHP_EOL;
			}
			
			$this->connection->commit();
			
		} catch ( \Throwable $exception ) {
			$this->connection->rollBack();
			throw new \Exception( $exception );
		}
		
		$this->connection->setAutoCommit(true);
		return 0;
	}
	
	/**
	 * @throws SchemaException
	 * @throws Exception
	 */
	private function createMigrationsTable()
	: void
	{
		$schemaManager = $this->connection->createSchemaManager();
		if ( !$schemaManager->tablesExist( self::MIGRATIONS_TABLE ) ) {
			$schema = new Schema();
			$table = $schema->createTable( self::MIGRATIONS_TABLE );
			$table->addColumn( 'id', Types::INTEGER, [
				'unsigned'      => true,
				'autoincrement' => true,
			] );
			$table->addColumn( 'migration', Types::STRING );
			$table->addColumn( 'created_at', Types::DATETIME_IMMUTABLE, [
				'default' => 'CURRENT_TIMESTAMP'
			] );
			$table->setPrimaryKey( ['id'] );
			
			$sqlArray = $schema->toSql( $this->connection->getDatabasePlatform() );
			$this->connection->executeQuery( $sqlArray[0] );
			
			echo 'Migrations table created';
		}
	}
	
	/**
	 * @throws Exception
	 */
	private function getAppliedMigrations()
	: array
	{
		$queryBuilder = $this->connection->createQueryBuilder();
		
		return $queryBuilder
			->select( 'migration' )
			->from( self::MIGRATIONS_TABLE )
			->executeQuery()
			->fetchFirstColumn();
	}
	
	private function getMigrationFiles()
	: bool|array
	{
		$migrationFiles = scandir( $this->migrationsPath );
		$filteredArray = array_filter( $migrationFiles, function ( $fileName ) {
			return !in_array( $fileName, ['.', '..'] );
		} );
		
		return array_values( $filteredArray );
	}
	
	/**
	 * @throws Exception
	 */
	private function addMigration( string $migration )
	: void
	{
		$queryBuilder = $this->connection->createQueryBuilder();
		
		$queryBuilder
			->insert( self::MIGRATIONS_TABLE )
			->values( ['migration' => ':migration'] )
			->setParameter( 'migration', $migration )
			->executeQuery();
	}
	
}