<?php
/**
 * @package   	JCE
 * @copyright 	Copyright � 2009-2011 Ryan Demmer. All rights reserved.
 * @license   	GNU/GPL 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

defined( '_JEXEC' ) or die('RESTRICTED');


class WFControllerPopup extends JController
{
	/**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */
	function __construct($config = array()){
		parent::__construct($config);
	}

	/**
	 * Displays a view
	 */
	function display($cachable = false, $params = false)
	{		
		$document = JFactory::getDocument();
		
		$this->addViewPath(JPATH_COMPONENT . DS . 'views');

		$view = $this->getView('popup', $document->getType());
		
		$view->assignRef('document', $document);
        $view->display();
	}
}