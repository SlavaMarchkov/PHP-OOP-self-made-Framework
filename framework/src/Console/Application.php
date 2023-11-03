<?php

namespace Pmguru\Framework\Console;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Application
{
	
	public function __construct(
		private ContainerInterface $container,
	)
	{
	}
	
	/**
	 * @throws ConsoleException
	 */
	public function run()
	: int
	{
		// 1. Получаем имя команды
		$argv = $_SERVER['argv'];
		$commandName = $argv[1] ?? null;
		
		// 2. Возвращаем исключение, если имя команды не указано
		if ($commandName === null) {
			throw new ConsoleException('Invalid command name');
		}
		
		// 3. Используем имя команды для получения объекта класса из контейнера
		/** @var CommandInterface $command */
		try {
			$command = $this->container->get( "console:$commandName" );
		} catch ( NotFoundExceptionInterface|ContainerExceptionInterface $e ) {
			throw new ConsoleException('Command not found with error: ' . $e->getMessage(), 400);
		}
		
		// 4. Получаем опции и аргументы
		$args = array_slice( $argv, 2 );
		$options = $this->parseOptions( $args );
	
		// 5. Выполняем команду и возвращаем код статуса
		$status = $command->execute($options);
		
		return $status;
	}
	
	private function parseOptions( array $args )
	: array
	{
		$options = [];
		foreach ( $args as $arg ) {
			if ( str_starts_with( $arg, '--' ) ) {
				$option = explode( '=', substr( $arg, 2 ) );
				$options[$option[0]] = $option[1] ?? true;
			}
		}
		
		return $options;
	}
	
}