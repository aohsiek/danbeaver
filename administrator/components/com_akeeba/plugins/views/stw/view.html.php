<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 1.3
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * Akeeba Backup Configuration Wizard view class
 *
 */
class AkeebaViewStw extends FOFViewHtml
{
    /**
	 * Modified constructor to enable loading layouts from the plug-ins folder
	 * @param $config
	 */
	public function __construct( $config = array() )
	{
		parent::__construct( $config );
		$tmpl_path = dirname(__FILE__).'/tmpl';
		$this->addTemplatePath($tmpl_path);
	}
    
	public function onDisplay($tpl = null)
	{
		$aeconfig = AEFactory::getConfiguration();
				
		// Add references to CSS and JS files
		AkeebaHelperIncludes::includeMedia(false);
		
		// Add live help
		AkeebaHelperIncludes::addHelp('stw');
		
		$model = $this->getModel();
		$step = $model->getState('stwstep', 1);
		
		switch($step) {
			case 1:
			default:
				$cpanelmodel = FOFModel::getTmpInstance('Cpanels','AkeebaModel');
				$this->assign('profilelist', $cpanelmodel->getProfilesList());
				$this->assign('stw_profile_id', $model->getSTWProfileID());
				break;
			
			case 2:
				$this->assignRef('opts', $model->getTransferSettings());
				break;
			
			case 3:
				break;
		}
		
		$this->setLayout('step'.$step);
		
		return true;
	}
}