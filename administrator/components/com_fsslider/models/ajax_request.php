<?php

/**

 * @version $Id: fsslider.php 2011-01-25 12:41:40 svn $

 * @package    Fsslider

 * @subpackage Base

 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}

 * @author     David Fritsch {@link fritschservices.com}

 * @author     Created on 27-May-2011

 * @license    GNU/GPL

 */



// no direct access

defined('_JEXEC') or die('Restricted access');



//-- No direct access

defined('_JEXEC') or die('=;)');



jimport('joomla.application.component.model');



/**

 * Fsslider Model

 *

 * @package    Fsslider

 * @subpackage Models

 */

class FssliderModelAjax_request extends JModel

{



    /**

     * Constructor that retrieves the ID from the request

     *

     * @access  public

     * @return  void

     */

    function __construct()

    {

        parent::__construct();

    }//function

	
	function getMenuItems() {
		$db =& JFactory::getDBO();
		
		$query = 'SELECT m.*, (SELECT alias FROM #__menu WHERE id = m.parent_id) as parent_alias FROM #__menu as m LEFT JOIN #__menu_types as t ON m.menutype=t.menutype WHERE m.menutype!=\'menu\' AND m.published=1 ORDER BY m.menutype, m.level, parent_alias ';
		$db->setQuery( $query );
	   	$menus = $db->loadObjectList();
		
		$menutree = $this->buildLevel($menus, 0);
	   
		return $menutree;
	}
	
	function buildLevel($menus, $level, $parentid = 0) {
		$firstitem = true;
		$itemadded = false;
		$imenu = $menus;
		foreach ($imenu as $menu) {
			//$return_data.="<h1>".$menu->level."-" . $menu->parent_id . " - $level - $parentid</h1>";
			if ($menu->level == $level && $parentid == $menu->parent_id) {
				if ($firstitem == true) {
					$return_data.= "<ul class=\"jqueryFileTree\">";
					$firstitem = false;
				}
				$itemadded=true;
				$return_data .= "<li class=\"directory collapsed\"><a href=\"#\" rel=\"/" . $menu->alias . ":" . $menu->id . "/\">" . $menu->title . "</a></li>";
				$check_level = $level + 1;
				//$return_data .= $menus . $check_level . $menu->id;
				$return_data .= $this->buildLevel($menus, $check_level, $menu->id);
			}
		}
		if ($itemadded==true) {
			$return_data.= "</ul>";
		}
		return $return_data;
	}

}// class

