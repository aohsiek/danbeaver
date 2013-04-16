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



jimport( 'joomla.application.component.view');



/**

 * HTML View class for the Wbty_jplayer Component

 *

 * @package    Wbty_jplayer

 * @subpackage Views

 */



class Wbty_jplayerListViewWbty_jplayerList extends JView

{

    /**

     * Wbty_jplayerList view display method

     * @return void

     **/

    function display($tpl = null)

    {

        JToolBarHelper::title(   JText::_( 'Wbty_jplayer Manager' ), 'generic.png' );

        JToolBarHelper::deleteList('delete');

        JToolBarHelper::editList('edit');

        JToolBarHelper::addNew('add');

		JToolBarHelper::preferences('com_wbty_jplayer',400,800);



        //-- Get data from the model

        $items =& $this->get('Data');

        $pagination =& $this->get('Pagination');



        //-- push data into the template

        $this->assignRef('items', $items);

        $this->assignRef('pagination', $pagination);

 

		/* Call the state object */

		$state =& $this->get( 'state' );

 

		/* Get the values from the state object that were inserted in the model's construct function */

		$lists['order_Dir'] = $state->get( 'filter_order_Dir' );

		$lists['order']     = $state->get( 'filter_order' );

 

 		$this->assignRef ('option', JRequest::getCmd('option'));

		$this->assignRef( 'lists', $lists );

 

		parent::display($tpl);

		

    }//function



}//class

