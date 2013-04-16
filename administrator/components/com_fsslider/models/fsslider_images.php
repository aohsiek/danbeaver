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
class FssliderModelFsslider_images extends JModel
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
     * Method to set the Fsslider identifier
     *
     * @access  public
     * @param   int Fsslider identifier
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
            $query = 'SELECT * FROM #__fsslider_images'
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
	
	function &getContentImages()
    {
        //-- Load the data
        if(empty($this->_images))
        {
            $query = 'SELECT * FROM #__fsslider_content_images'
                    . ' WHERE slide = '.$this->_id;
            $this->_db->setQuery($query);
            $this->_images = $this->_db->loadObjectList();
        }
        return $this->_images;
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
		
		if ($file = JRequest::getVar( 'jpg', '', 'files', 'array'))
			JRequest::setVar('location', $this->storeFile($file, "jpg,jpeg,png,gif"), 'post', true);
		
		$data = JRequest::get('post');
		
		
  		$data['description']=JRequest::getVar( 'description', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$data['content']=JRequest::getVar( 'content', '', 'post', 'string', JREQUEST_ALLOWHTML );
		
		
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
		
		
		// -- Setup Images
		
		for ($i=1;$i<=JRequest::getVar( 'imageId' );$i++) {
			if ($file = JRequest::getVar( 'jpg'.$i, '', 'files', 'array')) {
				$location = $this->storeFile($file, "jpg,jpeg,png,gif");
				if ($location != '') {
					$query = 'SELECT id FROM #__fsslider_content_images'
                    . ' WHERE slide = \''.$row->id.'\''
					. ' AND imageorder = \''.$i.'\'';
					$this->_db->setQuery($query);
					$id = $this->_db->loadResult();
					
					if ($id) {
						$query = 'DELETE FROM #__fsslider_content_images'
						. ' WHERE id = \''.$id.'\'';
						$this->_db->query($query);
					}
					
					$row2 =& $this->getTable('fsslider_content_images');
					$data2 = array(
						"location" => $location, 
						"imageorder" => $i,
						"slide" => $row->id
						);
					if( ! $row2->bind($data2))
					{
						$this->setError($this->_db->getError());
						return false;
					}
			
					// Make sure the record is valid
					if( ! $row2->check())
					{
						$this->setError($this->_db->getError());
						return false;
					}
			
					// Store the table to the database
					if( ! $row2->store())
					{
						$this->setError($row2->getError());
						return false;
					}
				}
			}
		}
		
		//-- Add Image to Current Package if New
		
		if (!$data['edit']) {
			
			$row2 =& $this->getTable('fsslider_package_images');
			
			$data2 = array(
					"package_id" => $data['package_id'],
					"image_id" => $row->id,
					"ordering" => $data['ordering'],
					"state" => "1"
					);
					
			if( ! $row2->bind($data2))
			{
				$this->setError($this->_db->getError());
				return false;
			}
	
			// Make sure the record is valid
			if( ! $row2->check())
			{
				$this->setError($this->_db->getError());
				return false;
			}
	
			// Store the table to the database
			if( ! $row2->store())
			{
				$this->setError($row2->getError());
				return false;
			}
		}
		
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
			
			if ($filename!=$oldfile) {
				$oldfile = JPATH_COMPONENT . DS . "uploads" . DS . $oldfile;
				if (JFile::exists($oldfile)) {
					JFile::delete($oldfile);
				}
			}
			
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
        return true;
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
		if (!isset($return_data)) 
			$return_data='';
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
				$return_data .= "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . $menu->link . (strpos($menu->link, '?')===false ? '?' : '&') . "Itemid=" . $menu->id . "\">" . $menu->title . "</a></li>";
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
