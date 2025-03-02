<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */


namespace Aimeos\Admin\JQAdm\Cms\Seo;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp() : void
	{
		$this->view = \TestHelperJqadm::view();
		$this->context = \TestHelperJqadm::context();

		$langManager = \Aimeos\MShop::create( $this->context, 'locale/language' );

		$this->view->pageLanguages = $langManager->search( $langManager->filter() );
		$this->view->item = \Aimeos\MShop::create( $this->context, 'cms' )->create();

		$this->object = new \Aimeos\Admin\JQAdm\Cms\Seo\Standard( $this->context );
		$this->object = new \Aimeos\Admin\JQAdm\Common\Decorator\Page( $this->object, $this->context );
		$this->object->setAimeos( \TestHelperJqadm::getAimeos() );
		$this->object->setView( $this->view );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->view, $this->context );
	}


	public function testCreate()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'cms' );

		$this->view->item = $manager->create();
		$result = $this->object->create();

		$this->assertStringContainsString( 'item-seo', $result );
		$this->assertEmpty( $this->view->get( 'errors' ) );
	}


	public function testCopy()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'cms' );

		$this->view->item = $manager->find( '/contact', ['text'] );
		$result = $this->object->copy();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'Contact', $result );
	}


	public function testDelete()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'cms' );

		$this->view->item = $manager->create();
		$result = $this->object->delete();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
	}


	public function testGet()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'cms' );

		$this->view->item = $manager->find( '/contact', ['text'] );
		$result = $this->object->get();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertStringContainsString( 'Contact', $result );
	}


	public function testSave()
	{
		$manager = \Aimeos\MShop::create( $this->context, 'cms' );
		$item = $manager->create();

		$param = array(
			'site' => 'unittest',
			'seo' => array(
				array(
					'text.id' => '',
					'text.content' => 'test name',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'cms.lists.type' => 'default',
				),
				array(
					'text.id' => '',
					'text.content' => 'short desc',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'cms.lists.type' => 'default',
				),
				array(
					'text.id' => '',
					'text.content' => 'long desc',
					'text.languageid' => 'de',
					'text.type' => 'name',
					'cms.lists.type' => 'default',
				),
			),
		);

		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $param );
		$this->view->addHelper( 'param', $helper );
		$this->view->item = $item;

		$result = $this->object->save();

		$this->assertEmpty( $this->view->get( 'errors' ) );
		$this->assertEmpty( $result );
		$this->assertEquals( 3, count( $item->getListItems() ) );

		foreach( $item->getListItems( 'text' ) as $listItem )
		{
			$this->assertEquals( 'text', $listItem->getDomain() );

			$refItem = $listItem->getRefItem();
			$this->assertEquals( 'de', $refItem->getLanguageId() );
		}
	}


	public function testSaveException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Cms\Seo\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \RuntimeException() ) );

		$this->view = \TestHelperJqadm::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'cms' )->create();

		$object->setView( $this->view );

		$this->expectException( \RuntimeException::class );
		$object->save();
	}


	public function testSaveMShopException()
	{
		$object = $this->getMockBuilder( \Aimeos\Admin\JQAdm\Cms\Seo\Standard::class )
			->setConstructorArgs( array( $this->context, \TestHelperJqadm::getTemplatePaths() ) )
			->setMethods( array( 'fromArray' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'fromArray' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$this->view = \TestHelperJqadm::view();
		$this->view->item = \Aimeos\MShop::create( $this->context, 'cms' )->create();

		$object->setView( $this->view );

		$this->expectException( \Aimeos\MShop\Exception::class );
		$object->save();
	}


	public function testSearch()
	{
		$this->assertEmpty( $this->object->search() );
	}


	public function testGetSubClient()
	{
		$this->expectException( \Aimeos\Admin\JQAdm\Exception::class );
		$this->object->getSubClient( 'unknown' );
	}
}
