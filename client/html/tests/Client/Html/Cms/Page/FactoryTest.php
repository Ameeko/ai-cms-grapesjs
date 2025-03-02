<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */


namespace Aimeos\Client\Html\Cms\Page;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	private $context;


	protected function setUp() : void
	{
		$this->context = \TestHelperHtml::context();
	}


	protected function tearDown() : void
	{
		unset( $this->context );
	}


	public function testCreateClient()
	{
		$client = \Aimeos\Client\Html\Cms\Page\Factory::create( $this->context );
		$this->assertInstanceOf( '\\Aimeos\\Client\\Html\\Iface', $client );
	}


	public function testCreateClientName()
	{
		$client = \Aimeos\Client\Html\Cms\Page\Factory::create( $this->context, 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Client\\Html\\Iface', $client );
	}


	public function testCreateClientNameInvalid()
	{
		$this->expectException( '\\Aimeos\\Client\\Html\\Exception' );
		\Aimeos\Client\Html\Cms\Page\Factory::create( $this->context, '$$$' );
	}


	public function testCreateClientNameNotFound()
	{
		$this->expectException( '\\Aimeos\\Client\\Html\\Exception' );
		\Aimeos\Client\Html\Cms\Page\Factory::create( $this->context, 'notfound' );
	}

}
