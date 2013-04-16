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


//-- No direct access
defined('_JEXEC') or die('=;)');

/**
 * Fsslider Table class
 *
 * @package    Fsslider
 * @subpackage Tables
 */
class TableFsslider_package_images extends JTable
{
    /**
     * Primary Key
     *
     * @var int
     */
    var $id = null;

    /**
     * @var int
     */
    var $package_id = null;
	
	/**
     * @var int
     */
    var $image_id = null;
	
	/**
     * @var int
     */
    var $ordering = null;
	
	/**
     * @var int
     */
    var $state = null;
	
    /**
     * Constructor
     *
     * @param object $db Database connector object
     */
    function TableFsslider_package_images(& $db)
    {
        parent::__construct('#__fsslider_package_images', 'id', $db);
    }//function

}//class
