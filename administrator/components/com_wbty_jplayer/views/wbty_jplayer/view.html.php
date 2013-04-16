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



class Wbty_jplayerListViewWbty_jplayer extends JView

{

    /**

     * Wbty_jplayer view display method

     *

     * @return void

     **/

    function display($tpl = null)

    {

        //-- Get the Wbty_jplayer

        $Wbty_jplayer =& $this->get('Data');

		

        $isNew = ($Wbty_jplayer->id < 1);



        $text = $isNew ? JText::_('New') : JText::_('Edit');

        JToolBarHelper::title('Wbty_jplayer: <small><small>[ '.$text.' ]</small></small>');

        JToolBarHelper::save();

        if($isNew)

        {

            JToolBarHelper::cancel();

			

			$db = JFactory::getDBO();

			$db->setQuery("SELECT max(ordering) FROM #__wbty_jplayer");

			$Wbty_jplayer->ordering = $db->LoadResult();

			$Wbty_jplayer->ordering+=1;

			//print_r($Wbty_jplayer);

        }

        else

        {

            //-- For existing items the button is renamed `close`

            JToolBarHelper::cancel('cancel', JText::_('Close'));

        }

		

		



        $this->assignRef('Wbty_jplayer', $Wbty_jplayer);



        parent::display($tpl);

    }//function



}//class

