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
class FssliderViewAjax_request extends JView
{
    /**
     * Fsslider view display method
     *
     * @return void
     **/
    function display($tpl = null)
    {
        //-- Get the Fsslider
        $Fsslider = $this->get('MenuItems');
        $this->assignRef('Fsslider', $Fsslider);
        parent::display($tpl);
    }//function
}//class
