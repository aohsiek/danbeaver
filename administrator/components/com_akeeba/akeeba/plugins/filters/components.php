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
 * Joomla! Components exclusion filter
 */
class AEFilterComponents extends AEAbstractFilter
{
	private $joomla16;

	public function __construct()
	{
		$this->object	= 'components';
		$this->subtype	= 'all';
		$this->method	= 'direct';

		if(empty($this->filter_name)) $this->filter_name = strtolower(basename(__FILE__,'.php'));
		
		if(AEFactory::getKettenrad()->getTag() == 'restorepoint') $this->enabled = false;

		$this->joomla16 = ! @file_exists(JPATH_SITE.'/includes/joomla.php');

		parent::__construct();
	}

	public function &getExtraSQL($root)
	{
		$empty = '';
		if($root != '[SITEDB]') return $empty;

		$sql = '';
		$db = AEFactory::getDatabase();
		$this->getFilters(null); // Forcibly reload the filter data
		// Loop all components and add SQL statements
		if(!empty($this->filter_data))
		{
			foreach($this->filter_data as $type => $items)
			{
				if(!empty($items))
				{
					// Make sure that DB only backups get the correct prefix
					$configuration = AEFactory::getConfiguration();
					$abstract = AEUtilScripting::getScriptingParameter('db.abstractnames', 1);
					if($abstract) {
						$prefix = '#__';
					} else {
						$prefix = $db->getPrefix();
					}

					foreach($items as $item)
					{
						if(!$this->joomla16)
						{
							$sql .= 'DELETE FROM '.$db->nameQuote($prefix.'components').
								' WHERE '.$db->nameQuote('option').' = '.
								$db->Quote($item).";\n";
						}
						else
						{
							$sql .= 'DELETE FROM '.$db->nameQuote($prefix.'extensions').
								' WHERE '.$db->nameQuote('element').' = '.
								$db->Quote($item)." AND ".$db->nameQuote('type').' = '.
								$db->Quote('component').";\n";
						}
						$sql .= 'DELETE FROM '.$db->nameQuote($prefix.'menu').
							' WHERE '.$db->nameQuote('type').' = '.
							$db->Quote('component').' AND '.
							$db->nameQuote('link').' LIKE '.
							$db->Quote('%option='.$item.'%').";\n";
					}
				}
			}
		}

		return $sql;
	}
}