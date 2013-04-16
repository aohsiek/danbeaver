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

class AkeebaControllerStw extends FOFController
{
	public function  __construct($config = array()) {
		parent::__construct($config);
		// Access check, Joomla! 1.6 style.
		$user = JFactory::getUser();
		if (!$user->authorise('akeeba.backup', 'com_akeeba')) {
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
		if(!in_array($task, array('step1','step2','step3'))) {
			$task = 'step1';
		}
		
		parent::execute($task);
	}
	
	/**
	 * Step 1 - select profile
	 * @param type $cachable 
	 */
	public function step1($cachable = false, $urlparams = false)
	{
		$model = $this->getThisModel();
		$model->setState('stwstep', 1);
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * Applies the profile creation preferences and displays the transfer setup
	 * page.
	 * 
	 * @return void
	 */
	public function step2($cachable = false, $urlparams = false)
	{
		$model = $this->getThisModel();
		$model->setState('stwstep', 2);
		
		$method = FOFInput::getCmd('method', 'none', $this->input);
		$oldprofile = FOFInput::getInt('oldprofile', 0, $this->input);
		
		$model->setState('method', $method);
		$model->setState('oldprofile', $oldprofile);
		
		$result = $model->makeOrUpdateProfile();
		
		if($result == false) {
			$url = 'index.php?option=com_akeeba&view=stw';
			$this->setRedirect($url, JText::_('STW_PROFILE_ERR_COULDNOTCREATESTWPROFILE'), 'error');
			return;
		}
		
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * Apply the site transfer settings, test the connection, upload a test file
	 * and show the last step's page.
	 */
	public function step3($cachable = false, $urlparams = false)
	{
		$model = $this->getThisModel();
		$model->setState('stwstep', 3);
		
		$model->setState('method',		FOFInput::getCmd('method', 'ftp', $this->input));
		$model->setState('hostname',	FOFInput::getVar('hostname', '', $this->input));
		$model->setState('port',		FOFInput::getInt('port', '', $this->input));
		$model->setState('username',	FOFInput::getVar('username', '', $this->input));
		$model->setState('password',	FOFInput::getVar('password', '', $this->input));
		$model->setState('directory',	FOFInput::getVar('directory', '', $this->input));
		$model->setState('passive',		FOFInput::getBool('passive', false, $this->input));
		$model->setState('livesite',	FOFInput::getVar('livesite', '', $this->input));
		$result = $model->applyTransferSettings();
		
		if($result != true) {
			$url = 'index.php?option=com_akeeba&view=stw&task=step2&method=none';
			$this->setRedirect($url, $result, 'error');
			return;
		}
		
		parent::display($cachable, $urlparams);
	}	
}