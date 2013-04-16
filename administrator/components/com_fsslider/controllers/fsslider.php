<?php
/**
 * @version SVN: $Id$
 * @package    Fsslider
 * @subpackage Controllers
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('=;)');
jimport('joomla.application.component.controller');
/**
 * Fsslider Controller
 *
 * @package    Fsslider
 * @subpackage Controllers
 */
class FssliderControllerFsslider extends FssliderController
{
    /**
     * constructor (registers additional tasks to methods)
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        //-- Register Extra tasks
        $this->registerTask('add', 'editImage');
		$this->registerTask('edit', 'editImage');
		
		$option = JRequest::getVar('option', 'com_fsslider', 'get');
    }// function
	
	/**
     * display the edit form
     * @return void
     */
    function back()
    {
        JRequest::setVar('view', 'Fsslider_packages');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 0);
        parent::display();
    }// function
	
	function addPackage()
    {
        JRequest::setVar('view', 'Fsslider_package');
        JRequest::setVar('layout', 'form');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }// function
	
	function savePackage()
    {
        $model = $this->getModel('Fsslider_packages');
        if($model->store())
        {
            $msg = JText::_('Record Saved');
        }
        else
        {
            $msg = JText::_('Error Saving Record');
        }
        $link = 'index.php?option=com_fsslider';
        $this->setRedirect($link, $msg);
    }// function
    /**
     * display the edit form
     * @return void
     */
    function editPackage()
    {
        JRequest::setVar('view', 'Fsslider_package');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }// function
	
	/**
     * display the edit form
     * @return void
     */
    function editImage()
    {
        JRequest::setVar('view', 'Fsslider_images');
        JRequest::setVar('layout', 'form');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }// function
	
	/**
     * display the edit form
     * @return void
     */
    function settings()
    {
        JRequest::setVar('view', 'Fsslider_settings');
        JRequest::setVar('layout', 'form');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }// function
	
	/**
     * display the edit form
     * @return void
     */
    function editName()
    {
		$model = $this->getModel('Fsslider_package');
        if($model->updateName())
		{
            $msg = JText::_('Name Updated');
        }
        else
        {
            $msg = JText::_('Error Updating Name');
        }
		
        JRequest::setVar('view', 'Fsslider_package');
        JRequest::setVar('layout', 'default');
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }// function
	
	function saveSettings()
    {
		$model = $this->getModel('Fsslider_settings');
        if($model->save())
		{
            $msg = JText::_('Settings Updated');
        }
        else
        {
            $msg = JText::_('Error Updating Settings');
        }
		
        $link = 'index.php?option=com_fsslider';
        $this->setRedirect($link, $msg);
    }// function
    /**
     * save a record (and redirect to main page)
     * @return void
     */
    function save()
    {
        $model = $this->getModel('Fsslider_images');
        if($model->store())
        {
            $msg = JText::_('Record Saved');
        }
        else
        {
            $msg = JText::_('Error Saving Record');
        }
        $link = 'index.php?option=com_fsslider&view=fsslider_package&cid[]='.JRequest::getVar('package_id');
        $this->setRedirect($link, $msg);
    }// function
    /**
     * remove record(s)
     * @return void
     */
    function remove()
    {
        $model = $this->getModel('Fsslider_package');
        if(!$model->delete()){
            $msg = JText::_('Error: One or More Records Could not be Deleted');
        } else {
            $msg = JText::_('Records Deleted');
        }
        $this->setRedirect('index.php?option=com_fsslider', $msg);
    }// function
    /**
     * cancel editing a record
     * @return void
     */
    function cancel()
    {
        $msg = JText::_('Operation Cancelled');
        $this->setRedirect('index.php?option=com_fsslider', $msg);
    }//function
	
	function orderdown()
   {
      $model = $this->getModel('fsslider_package');
      $msg = $model->move(1) ? 'Order Changed Successfully' : 'Error Reordering Table';
	  
	  if ($cid = JRequest::getVar( 'cid')) {
      	$this->setRedirect( 'index.php?option=com_fsslider&controller=fsslider&task=editPackage&cid[]='.$cid, $msg);
	  } else {
		$this->setRedirect( 'index.php?option=com_fsslider', $msg);
	  }
   }
   
   function orderup()
   {
		
      $model = $this->getModel('fsslider_package');
      $msg = $model->move(-1) ? 'Order Changed Successfully' : 'Error Reordering Table';
      if ($cid = JRequest::getVar( 'cid')) {
      	$this->setRedirect( 'index.php?option=com_fsslider&controller=fsslider&task=editPackage&cid[]='.$cid, $msg);
	  } else {
		$this->setRedirect( 'index.php?option=com_fsslider', $msg);
	  }
   }
   
   function changeState( $cid=null, $state=0, $option )
   {
	   global $mainframe;
	   
	   $db 	=& JFactory::getDBO();
	   
	   if (count( $cid ) < 1) {
			$action = $state == 1 ? 'publish' : ($state == -1 ? 'archive' : 'unpublish');
			JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
		}
	
		$cids = implode( ',', $cid );
		$query = 'UPDATE #__';
		// - if package_id is set, change image state, not package state
		$query .= JRequest::getVar('package_id') ? 'fsslider_package_images' : 'fsslider_packages';
		$query .= ' SET state = '.(int) $state
		. ' WHERE id IN ( '. $cids .' )'
		;
		$db->setQuery( $query );
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg() );
		}
		if ($package_id = JRequest::getVar( 'package_id')) {
			$this->setRedirect( 'index.php?option=com_fsslider&controller=fsslider&task=editPackage&cid[]='.$package_id, $msg);
		  } else {
			$this->setRedirect( 'index.php?option=com_fsslider', $msg);
		  }
   }
   
   function publish()
   {
	   global $mainframe;
	   
	   $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		
		$option = JRequest::getVar('option', 'com_fsslider', 'get');
		
		$this->changeState( $cid, 1, $option );
	   
   }
   
   function unpublish()
   {
	   global $mainframe;
	   
	   $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		
		$option = JRequest::getVar('option', 'com_fsslider', 'get');
		
		$this->changeState( $cid, 0, $option );
	   
   }
   
   function ajaxRequest () {
	   JRequest::setVar('view', 'Ajax_request');
        JRequest::setVar('layout', 'default');
		JRequest::setVar('format', 'raw');
        JRequest::setVar('hidemainmenu', 0);
		JRequest::setVar('tmpl', 'component');
        parent::display();
   }
}//class
