<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 *
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

/**
 * Extra Directories inclusion filter
 */
class AEFilterExtradirs extends AEAbstractFilter
{
	public function __construct()
	{
		$this->object	= 'dir';
		$this->subtype	= 'inclusion';
		$this->method	= 'direct';
		
		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		if(empty($this->filter_name)) $this->filter_name = strtolower(basename(__FILE__,'.php'));
		parent::__construct();
	}
}