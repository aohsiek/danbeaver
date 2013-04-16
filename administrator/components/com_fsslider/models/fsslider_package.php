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
class FssliderModelFsslider_package extends JModel
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
 
		$filter_order     = $mainframe->getUserStateFromRequest(  $option.'filter_order', 'filter_order', 'name', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
	 
		$this->setState('filter_order', $filter_order);
		$this->setState('filter_order_Dir', $filter_order_Dir);
		
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
		$filter_order     = $this->getState('filter_order');
		$filter_order_Dir = $this->getState('filter_order_Dir');
 
		/* Error handling is never a bad thing*/
		if(!empty($filter_order) && !empty($filter_order_Dir) ){
			$orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		}
 
		return $orderby;
	}

    /**
     * Returns the query
     * @return string The query to be used to retrieve the rows from the database
     */
    function _buildQuery()
    {
        $query = 'SELECT *, package.id as id FROM #__fsslider_package_images AS package'
					. ' LEFT JOIN #__fsslider_images AS images'
					. ' ON package.image_id = images.id'
                    . ' WHERE package.package_id = '.$this->_id;
		
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
    function &getData()
    {
        //-- Load the data if it doesn't already exist
        if(empty($this->_data))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }//function
	
	function &getPackage()
    {
        //-- Load the data if it doesn't already exist
        if(empty($this->_package))
        {
             $query = 'SELECT * FROM #__fsslider_packages'
                    . ' WHERE id = '.$this->_id;
            $this->_db->setQuery($query);
            $this->_package = $this->_db->loadObject();
        }
		
		if( ! $this->_package)
        {
            $this->_package = $this->getTable('fsslider_packages');
        }

        return $this->_package;
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
	
	function updateName()
    {
        //-- Load the data if it doesn't already exist
        $query = 'UPDATE #__fsslider_packages'
				. ' SET name = \''.JRequest::getVar('packagename').'\''
                . ' WHERE id = '.$this->_id;
		$this->_db->setQuery($query);
		$updateName = $this->_db->query();
        

        return $updateName;
    }//function
	
	function move($direction) {
      
      $db = JFactory::getDBO();
      $mainframe = JFactory::getApplication();
      
      $row =& $this->getTable('fsslider_package_images');

      if (!$row->load($this->_id)) {
         $this->setError($db->getErrorMsg());
         return false;
      }

      if (!$row->move( $direction )) {
         $this->setError($db->getErrorMsg());
         return false;
      }

      return true;
   }
   
   function delete()
    {
        $cids = JRequest::getVar('cid', array(0), 'post', 'array');

        $row =& $this->getTable('fsslider_package_images');

        if(count($cids))
        {
            foreach($cids as $cid)
            {
				$row->load($cid);
                if( ! $row->delete($cid))
                {
                    $this->setError($row->getError());
                    return false;
                }
            }//foreach
        }
		
        return true;
    }//function

}// class
