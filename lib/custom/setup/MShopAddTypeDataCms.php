<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2022
 */


namespace Aimeos\Upscheme\Task;


/**
 * Adds CMS records to tables.
 */
class MShopAddTypeDataCms extends MShopAddTypeData
{
	/**
	 * Returns the list of task names which this task depends on.
	 *
	 * @return string[] List of task names
	 */
	public function after() : array
	{
		return ['TablesCreateCms', 'MShopSetLocale', 'MShopAddTypeData'];
	}


	/**
	 * Executes the task for adding CMS records to tables.
	 */
	public function up()
	{
		$ds = DIRECTORY_SEPARATOR;
		$context = $this->context();
		$editor = $context->editor();
		$sitecode = $this->context()->locale()->getSiteItem()->getCode();

		$this->info( sprintf( 'Adding CMS type data for site "%1$s"', $sitecode ), 'v' );

		$context->setEditor( 'ai-cms-grapesjs:lib/custom' );

		$this->add( __DIR__ . $ds . 'default' . $ds . 'data' . $ds . 'type.php' );

		$context->setEditor( $editor );
	}
}
