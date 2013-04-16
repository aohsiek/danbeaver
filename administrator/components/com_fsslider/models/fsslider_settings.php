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
class FssliderModelFsslider_settings extends JModel
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

    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = 'SELECT * FROM #__fsslider_settings';

        return $query;
    }

    /**
     * Retrieves the hello data
     * @return array Array of objects containing the data from the database
     */
    function &getData()
    {
        //-- Load the data if it doesn't already exist
        if(empty($this->_data))
        {
            $query = $this->_buildQuery();
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadAssocList();
        }

        return $this->_data;
    }//function
	
	function save()
    {
		
		$data = JRequest::get('post');
		
		foreach ($data as $key=>$value) {
			$checkq = "SELECT id FROM #__fsslider_settings WHERE fskey='$key' LIMIT 1";
			echo $checkq;
			$this->_db->setQuery($checkq);
			$update = $this->_db->loadResult();
			
			if ($update) {
				$query = "UPDATE #__fsslider_settings SET fskey='$key', value='$value' WHERE fskey='$key'";
			} else {
				$query = "INSERT INTO #__fsslider_settings SET fskey='$key', value='$value'";
			}
			echo $query;
			
			$this->_db->setQuery($query);
			$updateSetting = $this->_db->query();
		}
		
        return true;
    }//function

}// class
