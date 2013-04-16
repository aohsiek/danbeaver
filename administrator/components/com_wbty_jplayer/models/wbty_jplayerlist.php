<?php

/**

 * @version $Id: wbty_jplayer.php 2011-01-25 12:41:40 svn $

 * @package    Wbty_jplayer

 * @subpackage Base

 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}

 * @author     David Fritsch {@link fritschservices.com}

 * @author     Created on 27-May-2011

 * @license    GNU/GPL

 */



//-- No direct access

defined('_JEXEC') or die('=;)');



jimport( 'joomla.application.component.model' );



/**

 * Wbty_jplayer Model

 *

 * @package    Wbty_jplayer

 * @subpackage Models

 */

class Wbty_jplayerListModelWbty_jplayerList extends JModel

{



	/**

     * Wbty_jplayerList id

     *

     * @var in

     */

    var $_id = null;

    /**

     * Wbty_jplayerList data array

     *

     * @var array

     */

    var $_data;



    /**

     * Items total

     * @var integer

     */

    var $_total = null;



    /**

     * Pagination object

     * @var object

     */

    var $_pagination = null;



    function __construct()

    {

        parent::__construct();

		

		$option = JRequest::getCmd('option');

		$mainframe = JFactory::getApplication();



        $app = JFactory::getApplication('administrator');



        //-- Get pagination request variables

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');

        $limitstart = $app->getUserStateFromRequest('com_wbty_jplayer.limitstart', 'limitstart', 0, 'int');



        //-- In case limit has been changed, adjust it

        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);



        $this->setState('limit', $limit);

        $this->setState('limitstart', $limitstart);

 

		$filter_order     = $mainframe->getUserStateFromRequest(  $option.'filter_order', 'filter_order', 'ordering', 'cmd' );

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

	$option = JRequest::getCmd('option');

	$mainframe = JFactory::getApplication();

 

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

        $query = 'SELECT * '

        . ' FROM #__wbty_jplayer';

		

		$orderby = $this->_buildContentOrderBy();

		if(!empty($orderby)) {

			$query.=$orderby;

		}



        return $query;

    }

	

	 /**

     * Reorder data

     * @return boolean on success

     */

	

	function move($direction) {

      

      $db = JFactory::getDBO();

      global $mainframe;

	  $mainframe = JFactory::getApplication();

      

      $row =& $this->getTable('wbty_jplayer');

      

      if (!$row->load($this->_id)) {

         $this->setError($db->getErrorMsg());

         return false;

      }



      if (!$row->move( $direction )) {

         $this->setError($db->getErrorMsg());

         return false;

      }

	  

	  $this->create_xml();



      return true;

   }

   

   function create_xml() {

		$db = & JFactory::getDBO();

		$query= "SELECT * FROM #__wbty_jplayer WHERE state=1 ORDER BY ordering";

		$db->setQuery($query);

		$wbty_jplayerlist = $db->loadAssocList();

		

		require_once(dirname(__FILE__)."/../assets/php/mp3_class.php");

		

		$params = &JComponentHelper::getParams('com_wbty_jplayer');

		

		$xml .= '<?xml version="1.0" encoding="UTF-8"?>

<playlist version="1" xmlns="http://xspf.org/ns/0/">

    <title>'.$params->get('playlist_title').'</title>

	<creator>'.$params->get('creator').'</creator>

    <link>'.JURI::root().'</link>

    <info>'.$params->get('playlist_info').'</info>

    <image>'.JURI::root().'images/stories/'.$params->get('playlist_image').'</image>



    <trackList>';

		for ($i=0, $n=count($wbty_jplayerlist); $i<$n; $i++) {

			$f = dirname(__FILE__)."/../uploads/" . $wbty_jplayerlist[$i]['location'];

			$m = new mp3file($f);

			$a = $m->get_metadata();

			

			$xml.='

			<track>

			  <location>' . JURI::root() . "administrator/components/com_wbty_jplayer/uploads/" . $wbty_jplayerlist[$i]['location'].'</location>

			  <creator>'.$wbty_jplayerlist[$i]['artist'].'</creator>

			  <album>'.$wbty_jplayerlist[$i]['album'].'</album>

			  <title>'.$wbty_jplayerlist[$i]['title'].'</title>

			  <annotation></annotation>

			  <duration>'.$a['Length mm:ss'].'</duration>

			  ';

			  if ($wbty_jplayerlist[$i]['image'])

			  $xml.='<image>' . JURI::root() . "administrator/components/com_wbty_jplayer/uploads/" . $wbty_jplayerlist[$i]['image'].'</image>';

			  else

			  $xml.='<image></image>';

			  $xml.='

			  <info></info>

			  <link></link>

			</track>

			';

		}

		$xml .= '    </trackList>

</playlist>';

		$myFile = JPATH_COMPONENT . DS . "playlist.xml";

		echo $myFile;

		$fh = fopen($myFile, 'w');

		fwrite($fh, $xml);

		fclose($fh);

		

		return true;

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



}//class

