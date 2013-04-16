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
jimport( 'joomla.application.component.view');
/**
 * HTML View class for the Fsslider Component
 *
 * @package    Fsslider
 * @subpackage Views
 */
class FssliderViewFsslider_images extends JView
{
    /**
     * Fsslider view display method
     *
     * @return void
     **/
    function display($tpl = null)
    {
        //-- Get the Fsslider
        $Fsslider =& $this->get('Data');
		$Fsslider->package_id = JRequest::getVar("package_id");
		
		$Fsslider->images =& $this->get('ContentImages');
		if (!isset($Fsslider->images[0]))
			$Fsslider->images[0]->id=0;
		
        $isNew = ($Fsslider->id < 1);
        $text = $isNew ? JText::_('New') : JText::_('Edit');
        JToolBarHelper::title('FS Slider: <small><small>[ '.$text.' ]</small></small>');
        JToolBarHelper::save();
        if($isNew)
        {
            JToolBarHelper::cancel();
			
			$db = JFactory::getDBO();
			$db->setQuery("SELECT max(ordering) FROM #__fsslider_package_images WHERE package_id='".JRequest::getVar("package_id")."'");
			$Fsslider->ordering = $db->LoadResult();
			$Fsslider->ordering+=1;
			//print_r($Fsslider);
        }
        else
        {
            //-- For existing items the button is renamed `close`
            JToolBarHelper::cancel('cancel', JText::_('Close'));
        }
		
		// --- Build Menu items for Link --- //
		
		$menus = $this->get("menuItems");
		$this->assignRef('menus', $menus);
		
		
		JHTML::_('behavior.tooltip');
        $this->assignRef('Fsslider', $Fsslider);
        parent::display($tpl);
    }//function
}//class
