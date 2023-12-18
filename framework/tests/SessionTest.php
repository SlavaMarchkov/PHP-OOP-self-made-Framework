<?php

namespace Pmguru\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Pmguru\Framework\Session\Session;

class SessionTest extends TestCase
{
	
	protected function setUp()
	: void
	{
		unset( $_SESSION );
	}
	
	public function test_set_and_get_flash()
	{
		$session = new Session();
		$session->setFlash('success', 'Успех!');
		$session->setFlash('error', 'Ошибка!');
		
		$this->assertTrue($session->hasFlash('success'));
		$this->assertTrue($session->hasFlash('error'));
		$this->assertEquals(['Успех!'], $session->getFlash('success'));
		$this->assertEquals(['Ошибка!'], $session->getFlash('error'));
		$this->assertEquals([], $session->getFlash('warning'));
	}
}