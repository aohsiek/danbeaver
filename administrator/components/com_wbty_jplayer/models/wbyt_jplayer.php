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



// no direct access

defined('_JEXEC') or die('Restricted access');



//-- No direct access

defined('_JEXEC') or die('=;)');



jimport('joomla.application.component.model');



/**

 * Wbty_jplayer Model

 *

 * @package    Wbty_jplayer

 * @subpackage Models

 */

class Wbty_jplayerListModelWbty_jplayer extends JModel

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



        $array = JRequest::getVar('cid',  0, '', 'array');

        $this->setId((int)$array[0]);

    }//function



    /**

     * Method to set the Wbty_jplayer identifier

     *

     * @access  public

     * @param   int Wbty_jplayer identifier

     * @return  void

     */

    function setId($id)

    {

        //-- Set id and wipe data

        $this->_id = $id;

        $this->_data = null;

    }//function





    /**

     * Method to get a record

     * @return object with data

     */

    function &getData()

    {

        //-- Load the data

        if(empty($this->_data))

        {

            $query = 'SELECT * FROM #__wbty_jplayer'

                    . ' WHERE id = '.$this->_id;

            $this->_db->setQuery($query);

            $this->_data = $this->_db->loadObject();

        }



        if( ! $this->_data)

        {

            $this->_data = $this->getTable();

        }



        return $this->_data;

    }//function



    /**

     * Method to store a record

     *

     * @access  public

     * @return  boolean True on success

     */

    function store()

    {		

		$row =& $this->getTable();

		

		$file = JRequest::getVar( 'mp3', '', 'files', 'array');

		JRequest::setVar('location', $this->storeFile($file, "mp3"), 'post', true);
		
		$file = JRequest::getVar( 'oggLocation', '', 'files', 'array');

		JRequest::setVar('ogg', $this->storeFile($file, "ogg"), 'post', true);
		
				

		$file = JRequest::getVar( 'jpg', '', 'files', 'array');

		JRequest::setVar('image', $this->storeFile($file, "jpg,jpeg,png,gif"), 'post', true);

		

		$data = JRequest::get('post');

		

        // Bind the form fields to the wbty_jplayer table

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

		$this->create_xml();

        return true;

    }//function

	

	function storeFile(&$file, $type) {

		if ($file['error']==0) {

		

			jimport( 'joomla.filesystem.file' );

			

			$filename = JFile::makeSafe($file['name']);

			$filename = str_replace(" ","_",$filename);

			

			$src = $file['tmp_name'];

			

			$dest = JPATH_COMPONENT . DS . "uploads" . DS . $filename;

			

			$oldfile = JRequest::getVar('location', '', 'post');

			

			//if ($filename!=$oldfile) {
//
//				$oldfile = JPATH_COMPONENT . DS . "uploads" . DS . $oldfile;
//
//				if (JFile::exists($oldfile)) {
//
//					JFile::delete($oldfile);
//
//				}
//
//			}

			

			if (JFile::exists($dest)) {

				JFile::delete($dest);

			}

			

			//Convert file types to array and check if file is acceptable

			

			$type = explode(",",$type);

			

			if ( in_array(strtolower(JFile::getExt($filename)),$type) ) {

			   if ( JFile::upload($src, $dest) ) {

				   $return = $filename;

			   } else {

				   $return = "Upload Failed";

			   }

			} else {

			   //Redirect and notify user file is not right extension

			}

			

			return $return;

			

		}

	}



    /**

     * Method to delete record(s)

     *

     * @access  public

     * @return  boolean True on success

     */

    function delete()

    {

        $cids = JRequest::getVar('cid', array(0), 'post', 'array');



        $row =& $this->getTable();



        if(count($cids))

        {

            foreach($cids as $cid)

            {

				$row->load($cid);

				$oldfile = $row->location;

				

				jimport( 'joomla.filesystem.file' );

				$oldfile = JPATH_COMPONENT . DS . "uploads" . DS . $oldfile;

				if (JFile::exists($oldfile)) {

					JFile::delete($oldfile);

				}

				

                if( ! $row->delete($cid))

                {

                    $this->setError($row->getError());

                    return false;

                }

            }//foreach

        }



		$this->create_xml();

        return true;

    }//function

	

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



}// class

