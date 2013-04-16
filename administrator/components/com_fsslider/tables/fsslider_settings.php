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
class TableFsslider_settings extends JTable
{
    /**
     * Primary Key
     *
     * @var int
     */
    var $id = null;

    /**
     * @var string
     */
    var $fskey = null;
	
	/**
     * @var string
     */
    var $value = null;
    /**
     * Constructor
     *
     * @param object $db Database connector object
     */
    function TableFsslider_settings(& $db)
    {
        parent::__construct('#__fsslider_settings', 'id', $db);
    }//function

}//class
