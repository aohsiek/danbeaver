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



/**

 *  Require the base controller.

 */

require_once JPATH_COMPONENT.DS.'controller.php';



//-- Require specific controller if requested

if($controller = JRequest::getCmd('controller'))

{

    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';



    if(file_exists($path))

    {

        require_once $path;

    }

    else

    {

        $controller = '';

    }

}



//-- Create the controller

$classname = 'Wbty_jplayerListController'.$controller;

$controller = new $classname();



//-- Perform the Request task



$controller->execute(JRequest::getCmd('task'));



//-- Redirect if set by the controller

$controller->redirect();

