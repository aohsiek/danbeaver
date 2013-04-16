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



class FssliderViewFsslider_packages extends JView

{

    /**

     * Fsslider view display method

     * @return void

     **/

    function display($tpl = null)

    {

        JToolBarHelper::title(   JText::_( 'FS Slider Packages' ), 'generic.png' );

        JToolBarHelper::deleteList();

        JToolBarHelper::customX('addPackage','new','new','New Package',false);

		JToolBarHelper::customX('settings','options','options','Global Settings',false);



        //-- Get data from the model

        $items =& $this->get('Data');

        $pagination =& $this->get('Pagination');



        //-- push data into the template

        $this->assignRef('items', $items);

        $this->assignRef('pagination', $pagination);

 

		/* Call the state object */

		$state =& $this->get( 'state' );

 

		/* Get the values from the state object that were inserted in the model's construct function */

		$lists['order_Dir'] = $state->get( 'filter_order_Dir1' );

		$lists['order']     = $state->get( 'filter_order1' );

 

		$this->assignRef( 'lists', $lists );

		$this->assignRef ('option', JRequest::getCmd('option'));

 

		parent::display($tpl);

		

    }//function



}//class

