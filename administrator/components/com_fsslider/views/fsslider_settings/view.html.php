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

class FssliderViewFsslider_settings extends JView
{
    /**
     * Fsslider view display method
     *
     * @return void
     **/
    function display($tpl = null)
    {
        //-- Get the settings
        $setting_data =& $this->get('Data');
		
		$settings = new stdClass;
		
		foreach ($setting_data as $setting) {
			$key = $setting['fskey'];
			$settings->$key = $setting['value'];
		}

        $this->assignRef('settings', $settings);
		
		JToolBarHelper::title('FS Slider: <small><small>[ Settings ]</small></small>');
        JToolBarHelper::save('saveSettings');
		JToolBarHelper::cancel();

        parent::display($tpl);
    }//function

}//class
