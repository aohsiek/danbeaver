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
 * Database table exclusion filter
 */
class AEFilterRegextables extends AEAbstractFilter
{
	function __construct()
	{
		$this->object	= 'dbobject';
		$this->subtype	= 'all';
		$this->method	= 'regex';
		
		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		if(empty($this->filter_name)) $this->filter_name = strtolower(basename(__FILE__,'.php'));
		parent::__construct();
	}
}