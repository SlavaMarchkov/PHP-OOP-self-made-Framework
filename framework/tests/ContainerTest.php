<?php

namespace Pmguru\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Pmguru\Framework\Container\Container;
use Pmguru\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ContainerTest extends TestCase
{
	
	public function test_assert_true()
	{
		$this->assertTrue( true );
	}
	
	/**
	 * @throws ContainerException
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface|ReflectionException
	 */
	public function test_getting_service_from_container()
	{
		$container = new Container();
		$container->add( 'somecode-class', SomecodeClass::class );
		$this->assertInstanceOf( SomecodeClass::class, $container->get( 'somecode-class' ) );
	}
	
	/**
	 * @throws ContainerException
	 */
	public function test_container_has_exception_ContainerException_if_add_wrong_service()
	{
		$container = new Container();
		$this->expectException( ContainerException::class );
		$container->add( 'no-class' );
	}
	
	/**
	 * @throws ContainerException
	 * @throws ContainerExceptionInterface
	 */
	public function test_has_method()
	{
		$container = new Container();
		$container->add( 'somecode-class', SomecodeClass::class );
		$this->assertTrue( $container->has( 'somecode-class' ) );
		$this->assertFalse( $container->has( 'no-class' ) );
	}
	
	/**
	 * @throws ContainerExceptionInterface
	 * @throws ContainerException
	 * @throws NotFoundExceptionInterface
	 * @throws ReflectionException
	 */
	public function test_recursively_autowired()
	{
		$container = new Container();
		$container->add( 'somecode-class', SomecodeClass::class );
		
		/** @var SomecodeClass $somecode */
		$somecode = $container->get( 'somecode-class' );
		
		$pmguru = $somecode->getPmguru();
		
		$this->assertInstanceOf( Pmguru::class, $somecode->getPmguru() );
		$this->assertInstanceOf( Telegram::class, $pmguru->getTelegram() );
		$this->assertInstanceOf( YouTube::class, $pmguru->getYouTube() );
	}
	
}