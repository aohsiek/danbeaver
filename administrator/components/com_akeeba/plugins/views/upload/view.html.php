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

class AkeebaViewUpload extends FOFViewHtml
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
	
	public function onUpload($tpl = null)
	{
		// Add references to CSS and JS files
		AkeebaHelperIncludes::includeMedia(false);
		
		if($this->done) {
			$this->setLayout('done');
		} elseif($this->error) {
			$this->setLayout('error');
		} else {
			$this->setLayout('uploading');
		}
		
		return true;
	}
	
	public function onCancelled($tpl = null)
	{
		// Add references to CSS and JS files
		AkeebaHelperIncludes::includeMedia(false);
		
		$this->setLayout('error');
		
		return true;
	}
	
	public function onStart($tpl = null)
	{
		// Add references to CSS and JS files
		AkeebaHelperIncludes::includeMedia(false);
		
		if($this->done) {
			$this->setLayout('done');
		} elseif($this->error) {
			$this->setLayout('error');
		} else {
			$this->setLayout('default');
		}
		
		return true;
	}
}