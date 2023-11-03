<?php

namespace Pmguru\Framework\Console;

use DirectoryIterator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

class Kernel
{
	
	public function __construct(
		private ContainerInterface $container,
		private Application        $application
	)
	{
	}
	
	/**
	 * @throws ContainerExceptionInterface
	 * @throws ReflectionException
	 * @throws NotFoundExceptionInterface
	 */
	public function handle()
	: int
	{
		// 1. Регистрация команд с помощью контейнера
		$this->registerCommands();
		
		// 2. Запуск команды
		$status = $this->application->run();
		
		// 3. Возвращаем код
		return $status;
	}
	
	/**
	 * @throws ReflectionException
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	private function registerCommands()
	: void
	{
		// Регистрация системных команд
		// 1. Получить все файлы из папки Commands
		$commandFiles = new DirectoryIterator( __DIR__ . '/Commands' );
		$namespace = $this->container->get( 'framework-commands-namespace' ); // "Pmguru\Framework\Console\Commands\"
		
		// 2. Пройти по всем файлам
		foreach ( $commandFiles as $commandFile ) {
			if ( !$commandFile->isFile() ) {
				continue;
			}
			// 3. Получить имя класса команды
			$command = $namespace . pathinfo( $commandFile, PATHINFO_FILENAME );
			// 4. Если это подкласс CommandInterface,
			if ( is_subclass_of( $command, CommandInterface::class ) ) {
				// то добавить в контейнер (ID - это имя команды)
				$name = (new ReflectionClass( $command ))
					->getProperty( 'name' )
					->getDefaultValue();
				
				$this->container->add( "console:$name", $command );
			}
		}
		
		// dd($this->container); // проверка содержимого контейнера
		
		// Регистрация пользовательских команд
	}
	
}