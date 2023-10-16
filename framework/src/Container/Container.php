<?php

namespace Pmguru\Framework\Container;

use Pmguru\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
	
	private array $services = [];
	
	/**
	 * @throws ContainerException
	 */
	public function add( string $id, string|object $concrete = null )
	: void
	{
		if ( is_null( $concrete ) ) {
			if ( !class_exists( $id ) ) {
				throw new ContainerException( "Service $id not found" );
			}
			$concrete = $id;
		}
		$this->services[$id] = $concrete;
	}
	
	/**
	 * @throws ContainerException
	 * @throws NotFoundExceptionInterface
	 * @throws ReflectionException
	 * @throws ContainerExceptionInterface
	 */
	public function get( string $id )
	{
		if ( !$this->has( $id ) ) {
			if ( !class_exists( $id ) ) {
				throw new ContainerException( "Service $id could not be resolved" );
			}
			$this->add( $id );
		}
		
		$instance = $this->resolve( $this->services[$id] );
		return $instance;
	}
	
	public function has( string $id )
	: bool
	{
//		return isset( $this->services[$id] ); // 1 вариант
		return array_key_exists( $id, $this->services ); // 2 вариант
	}
	
	/**
	 * @throws ContainerExceptionInterface
	 * @throws ReflectionException
	 * @throws NotFoundExceptionInterface
	 */
	private function resolve( $class )
	{
		// 1. Создать экземпляр класса Reflection
		$reflectionClass = new ReflectionClass( $class );
		
		// 2. Использовать Reflection для попытки получить конструктор класса
		$constructor = $reflectionClass->getConstructor();
		
		// 3. Если нет конструктора, то просто создать экземпляр
		if ( is_null( $constructor ) ) {
			return $reflectionClass->newInstance();
		}
		
		// 4. Получить параметры конструктора
		$constructorParams = $constructor->getParameters();
		
		// 5. Получить зависимости из параметров конструктора
		$classDependencies = $this->resolveClassDependencies( $constructorParams );
		
		// 6. Создать экземпляр класса с внедрением полученных зависимостей
		$instance = $reflectionClass->newInstanceArgs( $classDependencies );
		
		// 7. Вернуть объект
		return $instance;
	}
	
	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface|ReflectionException
	 */
	private function resolveClassDependencies( array $constructorParams )
	: array
	{
		// 1. Реализовать пустой список зависимостей
		$classDependencies = [];
		
		// 2. Попытаться найти и создать экземпляр
		/** @var ReflectionParameter $constructorParam */
		foreach ( $constructorParams as $constructorParam ) {
			// получить тип параметра
			$serviceType = $constructorParam->getType();
			
			// через рекурсивный вызов метода resolve в методе get создать экземпляр
			$service = $this->get( $serviceType->getName() );
			
			// добавить сервис в список зависимостей
			$classDependencies[] = $service;
		}
		
		// 3. Вернуть массив с зависимостями
		return $classDependencies;
	}
	
}