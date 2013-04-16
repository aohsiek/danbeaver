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
class FssliderModelFsslider_packages extends JModel
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
		
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

        $app = JFactory::getApplication('administrator');

        //-- Get pagination request variables
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest('com_dewplayer.limitstart', 'limitstart', 0, 'int');

        //-- In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
 
		$filter_order1     = $mainframe->getUserStateFromRequest(  $option.'filter_order1', 'filter_order1', 'name', 'cmd' );
		$filter_order_Dir1 = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir1', 'filter_order_Dir1', 'asc', 'word' );

	 
		$this->setState('filter_order1', $filter_order1);
		$this->setState('filter_order_Dir1', $filter_order_Dir1);
		
		$array = JRequest::getVar( 'cid', array(0), '', 'array' );
  	 	$edit = JRequest::getVar( 'edit', true );
  		if($edit) $this->_id = (int)$array[0];
    }//function
	
	/**
     * Method to set the orderby for SQL query
     *
     * @access  restricted
     * @param   none
     * @return  order by string
     */
	function _buildContentOrderBy()
	{
	$mainframe = JFactory::getApplication();
	$option = JRequest::getCmd('option');
 
		$orderby = '';
		$filter_order1     = $this->getState('filter_order1');
		$filter_order_Dir1 = $this->getState('filter_order_Dir1');
 
		/* Error handling is never a bad thing*/
		if(!empty($filter_order) && !empty($filter_order_Dir1) ){
			$orderby = ' ORDER BY '.$filter_order1.' '.$filter_order_Dir1;
		}
 
		return $orderby;
	}

    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = 'SELECT *, (SELECT count(id) FROM #__fsslider_package_images WHERE package_id = p.id) as images'
        . ' FROM #__fsslider_packages as p';
		
		$orderby = $this->_buildContentOrderBy();
		if(!empty($orderby)) {
			$query.=$orderby;
		}

        return $query;
    }

    /**
     * Retrieves the hello data
     * @return array Array of objects containing the data from the database
     */
    function getData()
    {
        //-- Load the data if it doesn't already exist
        if(empty($this->_data))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }//function

    function getTotal()
    {
        //-- Load the content if it doesn't already exist
        if(empty($this->_total))
        {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }//function

    function getPagination()
    {
        //-- Load the content if it doesn't already exist
        if(empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }//function
	
	function store()
    {		
		$row =& $this->getTable();
		
		$data = JRequest::get('post');
		
		
        // Bind the form fields to the fsslider table
        if( ! $row->bind($data))
        {
            $this->setError($this->_db->getError());
            return false;
        }

        // Make sure the record is valid
        if( ! $row->check())
        {
            $this->setError($this->_db->getError());
            return false;
        }

        // Store the table to the database
        if( ! $row->store())
        {
            $this->setError($row->getError());
            return false;
        }
		
        return true;
    }//function

}//class
