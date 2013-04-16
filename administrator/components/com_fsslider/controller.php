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

jimport('joomla.application.component.controller');

/**
 * Fsslider default Controller
 *
 * @package    Fsslider
 * @subpackage Controllers
 */
class FssliderController extends JController
{
    /**
     * Method to display the view
     *
     * @access	public
     */
    function display()
    {
		// Make sure we have a default view
        if( !JRequest::getVar( 'view' )) {
		    JRequest::setVar('view', 'fsslider_packages' );
        }
		
        parent::display();
    }// function

}// class

?>