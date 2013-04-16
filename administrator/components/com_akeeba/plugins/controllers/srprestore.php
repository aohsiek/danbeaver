<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Restoration of System Restore Points
 */
class AkeebaControllerSrprestore extends FOFController
{
	public function  __construct($config = array()) {
		parent::__construct($config);
		// Access check, Joomla! 1.6 style.
		$user = JFactory::getUser();
		if (!$user->authorise('akeeba.download', 'com_akeeba')) {
			$this->setRedirect('index.php?option=com_akeeba');
			return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			$this->redirect();
		}
		$base_path = JPATH_COMPONENT_ADMINISTRATOR.'/plugins';
		$model_path = $base_path.'/models';
		$view_path = $base_path.'/views';
		$this->addModelPath($model_path);
		$this->addViewPath($view_path);
	}

	public function execute($task) {
		if(!in_array($task, array('start','ajax'))) {
			$task = 'browse';
		}
		parent::execute($task);
	}
	
	public function browse($cachable = false, $urlparams = false)
	{
		$model = $this->getThisModel();
		$model->setState('restorestep',0);
		$message = $model->validateRequest();
		if( $message !== true )
		{
			$this->setRedirect('index.php?option=com_akeeba&view=buadmin', $message, 'error');
			$this->redirect();
			return;
		}
		
		parent::display($cachable, $urlparams);
	}
	
	function start($cachable = false, $urlparams = false)
	{
		// CSRF prevention
		if($this->csrfProtection) {
			$this->_csrfProtection();
		}
		
		$model = $this->getThisModel();
		$model->setState('restorestep',1);
		
		$message = $model->validateRequest();
		if( $message !== true )
		{
			$this->setRedirect('index.php?option=com_akeeba&view=buadmin', $message, 'error');
			$this->redirect();
			return;
		}
		
		$model->setState('procengine',	FOFInput::getCmd('procengine', 'direct', $this->input));
		$model->setState('ftp_host',	FOFInput::getVar('ftp_host', '', $this->input));
		$model->setState('ftp_port',	FOFInput::getInt('ftp_port', 21, $this->input));
		$model->setState('ftp_user',	FOFInput::getVar('ftp_user', '', $this->input));
		$model->setState('ftp_pass',	FOFInput::getVar('ftp_pass', '', $this->input, 'none', 2));
		$model->setState('ftp_root',	FOFInput::getVar('ftp_root', '', $this->input));
		$model->setState('tmp_path',	FOFInput::getVar('tmp_path', '', $this->input));
		$model->setState('ftp_ssl',		FOFInput::getCmd('usessl', 'false', $this->input) == 'true');
		$model->setState('ftp_pasv',	FOFInput::getCmd('passive', 'true', $this->input) == 'true');
		
		$status = $model->setRestorationParameters();
		
		parent::display($cachable, $urlparams);
	}
	
	function ajax($cachable = false, $urlparams = false)
	{
		$ajax = FOFInput::getCmd('ajax', '', $this->input);
		$model = $this->getThisModel();
		$model->setState('ajax', $ajax);
		
		$ret = $model->doAjax();
		
		@ob_end_clean();
		echo '###'.json_encode($ret).'###';
		flush();
		JFactory::getApplication()->close();
	}
}