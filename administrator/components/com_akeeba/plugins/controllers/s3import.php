<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.4
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * S3 Import view - Controller
 */
class AkeebaControllerS3import extends FOFController
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
	
	public function execute($task)
	{
		if($task != 'dltoserver') {
			$task = 'browse';
		}
		parent::execute($task);
	}
	
	public function browse($cachable = false, $urlparams = false)
	{
		$s3bucket = FOFInput::getVar('s3bucket', null, $this->input);
		
		$model = $this->getThisModel();
		if($s3bucket) {
			$model->setState('s3bucket', $s3bucket);
		}
		$model->getS3Credentials();
		$model->setS3Credentials(
			$model->getState('s3access'), $model->getState('s3secret')
		);
		
		parent::display($cachable, $urlparams);
	}
	
	/**
	 * Fetches a complete backup set from a remote storage location to the local (server)
	 * storage so that the user can download or restore it.
	 */
	public function dltoserver($cachable = false, $urlparams = false)
	{
		$s3bucket = FOFInput::getVar('s3bucket', null, $this->input);
		
		// Get the parameters
		$model = $this->getThisModel();
		if($s3bucket) {
			$model->setState('s3bucket', $s3bucket);
		}
		$model->getS3Credentials();
		$model->setS3Credentials(
			$model->getState('s3access'), $model->getState('s3secret')
		);
		
		$result = $model->downloadToServer();
		
		if($result === true) {
			// Part(s) downloaded successfully. Render the view.
			parent::display();
		} elseif($result === false) {
			// Part did not download. Redirect to initial page with an error.
			$this->setRedirect('index.php?option=com_akeeba&view=s3import', $model->getError(), 'error');
		} else {
			// All done. Redirect to intial page with a success message.
			$this->setRedirect('index.php?option=com_akeeba&view=s3import', JText::_('S3IMPORT_MSG_IMPORTCOMPLETE'));
		}
	}
}