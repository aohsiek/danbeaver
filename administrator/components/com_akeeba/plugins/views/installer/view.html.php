<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.3
 */

defined( '_JEXEC' ) or die();

jimport('joomla.application.component.view');

class AkeebaViewInstaller extends JView
{
    public function __construct( $config = array() )
	{
		parent::__construct( $config );
		$tmpl_path = dirname(__FILE__).'/tmpl';
		$this->addTemplatePath($tmpl_path);
	}
    
	function display($tpl=null)
	{
		$paths = new stdClass();
		$paths->first = '';

		// Get data from the model
		$state		= $this->get('State');


		// Are there messages to display ?
		$showMessage	= false;
		if ( is_object($state) )
		{
			$message1		= $state->get('message');
			$message2		= $state->get('extension.message');
			$message2_16	= $state->get('extension_message');
			$showMessage	= ( $message1 || $message2 || $message2_16 );
		}
		
		$jconfig = JFactory::getConfig();
		$tmpPath = $jconfig->getValue('config.tmp_path', JPATH_ROOT.'/tmp');

		$this->assign('showMessage',	$showMessage);
		$this->assignRef('paths',		$paths);
		$this->assignRef('state',		$state);
		$this->assign('install.directory', $tmpPath);

		JHTML::_('behavior.tooltip');
		$this->addToolbar();
		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$canDo	= InstallerHelper::getActions();
		JToolBarHelper::title(JText::_('COM_INSTALLER_HEADER_INSTALL'), 'install.png');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_installer');
			JToolBarHelper::divider();
		}

		// Document
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_INSTALLER_HEADER_INSTALL'));
		
		JToolBarHelper::help('JHELP_EXTENSIONS_EXTENSION_MANAGER_INSTALL');
	}
}