<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */


namespace Aimeos\Admin\JQAdm\Cms;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	private $context;


	protected function setUp() : void
	{
		$this->context = \TestHelperJqadm::context();
		$this->context->setView( \TestHelperJqadm::view() );
	}


	public function testCreateClient()
	{
		$client = \Aimeos\Admin\JQAdm\Cms\Factory::create( $this->context );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$client = \Aimeos\Admin\JQAdm\Cms\Factory::create( $this->context, 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Admin\\JQAdm\\Iface', $client );
	}


	public function testCreateClientNameEmpty()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Cms\Factory::create( $this->context, '' );
	}


	public function testCreateClientNameInvalid()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Cms\Factory::create( $this->context, '%service' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->expectException( '\\Aimeos\\Admin\\JQAdm\\Exception' );
		\Aimeos\Admin\JQAdm\Cms\Factory::create( $this->context, 'test' );
	}

}
