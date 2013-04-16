<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0
 */

// Protect from unauthorized access
defined('_JEXEC') or die();
/**
 * Integrated restoration Model
 *
 */
class AkeebaModelRestores extends FOFModel
{
	private $data;
	private $extension;
	private $path;

	public $password;
	public $id;

	/**
	 * Sets the list of IDs from the request data
	 */
	public function setIDsFromRequest()
	{
		// Get the ID or list of IDs from the request or the configuration
		$cid = FOFInput::getVar('cid', null, $this->input, 'array');
		$id = FOFInput::getInt('id', 0, $this->input);

		if(is_array($cid) && !empty($cid))
		{
			$this->setIds($cid);
		}
		else
		{
			if(empty($id)) {
				$this->setId($id);
			}
		}
		
		return $this;
	}
	
	/**
	 * Generates a pseudo-random password
	 * @param int $length The length of the password in characters
	 * @return string The requested password string
	 */
	function makeRandomPassword( $length = 32 )
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789!@#$%&*";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;

		while ($i <= $length) {
			$num = rand() % 40;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}

		return $pass;
	}

	/**
	 * Validates the data passed to the request.
	 * @return mixed True if all is OK, an error string if something is wrong
	 */
	function validateRequest()
	{
		// Is this a valid backup entry?
		$this->setIDsFromRequest();
		$id = $this->getId();
		if(empty($id))
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}

		$data = AEPlatform::getInstance()->get_statistics($id);
		if(empty($data))
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}

		if($data['status'] != 'complete')
		{
			return JText::_('RESTORE_ERROR_INVALID_RECORD');
		}

		// Load the profile ID (so that we can find out the output directory)
		$profile_id = $data['profile_id'];
		AEPlatform::getInstance()->load_configuration($profile_id);

		$path = $data['absolute_path'];
		$exists = @file_exists($path);
		if(!$exists)
		{
			// Let's try figuring out an alternative path
			$config = AEFactory::getConfiguration();
			$path = $config->get('akeeba.basic.output_directory', '').'/'.$data['archivename'];
			$exists = @file_exists($path);
		}

		if(!$exists)
		{
			return JText::_('RESTORE_ERROR_ARCHIVE_MISSING');
		}

		$filename = basename($path);
		$lastdot = strrpos($filename, '.');
		$extension = strtoupper( substr($filename, $lastdot+1) );
		if( !in_array($extension, array('JPA','ZIP')) )
		{
			return JText::_('RESTORE_ERROR_INVALID_TYPE');
		}

		$this->data = $data;
		$this->path = $path;
		$this->extension = $extension;

		return true;
	}

	function createRestorationINI()
	{
		// Get a password
		$this->password = $this->makeRandomPassword(32);
		$this->setState('password', $this->password);

		// Do we have to use FTP?
		$procengine = $this->getState('procengine', 'direct');

		// Get the absolute path to site's root
		$siteroot = JPATH_SITE;

		// Get the JPS password
		$password = AkeebaHelperEscape::escapeJS($this->getState('jps_key'));

		$data = "<?php\ndefined('_AKEEBA_RESTORATION') or die('Restricted access');\n";
		$data .= '$restoration_setup = array('."\n";
		$data .= <<<ENDDATA
	'kickstart.security.password' => '{$this->password}',
	'kickstart.tuning.max_exec_time' => '5',
	'kickstart.tuning.run_time_bias' => '75',
	'kickstart.tuning.min_exec_time' => '0',
	'kickstart.procengine' => '$procengine',
	'kickstart.setup.sourcefile' => '{$this->path}',
	'kickstart.setup.destdir' => '$siteroot',
	'kickstart.setup.restoreperms' => '0',
	'kickstart.setup.filetype' => '{$this->extension}',
	'kickstart.setup.dryrun' => '0',
	'kickstart.jps.password' => '$password'
ENDDATA;

		if($procengine == 'ftp')
		{
			$ftp_host	= $this->getState('ftp_host','');
			$ftp_port	= $this->getState('ftp_port', '21');
			$ftp_user	= $this->getState('ftp_user', '');
			$ftp_pass	= $this->getState('ftp_pass', '');
			$ftp_root	= $this->getState('ftp_root', '');
			$ftp_ssl	= $this->getState('ftp_ssl', 0);
			$ftp_pasv	= $this->getState('ftp_root', 1);
			$tempdir	= $this->getState('tmp_path', '');
			$data.=<<<ENDDATA
	,
	'kickstart.ftp.ssl' => '$ftp_ssl',
	'kickstart.ftp.passive' => '$ftp_pasv',
	'kickstart.ftp.host' => '$ftp_host',
	'kickstart.ftp.port' => '$ftp_port',
	'kickstart.ftp.user' => '$ftp_user',
	'kickstart.ftp.pass' => '$ftp_pass',
	'kickstart.ftp.dir' => '$ftp_root',
	'kickstart.ftp.tempdir' => '$tempdir'
ENDDATA;
		}

		$data .= ');';

		// Remove the old file, if it's there...
		jimport('joomla.filesystem.file');
		$configpath = JPATH_COMPONENT_ADMINISTRATOR.'/restoration.php';
		if( JFile::exists($configpath) )
		{
			JFile::delete($configpath);
		}

		// Write new file
		$result = JFile::write( $configpath, $data );
		return $result;
	}

	function getFTPParams()
	{
		$config = JFactory::getConfig();
		return array(
			'procengine'	=> $config->get('config.ftp_enable', 0) ? 'ftp' : 'direct',
			'ftp_host'		=> $config->get('config.ftp_host', 'localhost'),
			'ftp_port'		=> $config->get('config.ftp_port', '21'),
			'ftp_user'		=> $config->get('config.ftp_user', ''),
			'ftp_pass'		=> $config->get('config.ftp_pass', ''),
			'ftp_root'		=> $config->get('config.ftp_root', ''),
			'tempdir'		=> $config->get('config.tmp_path', '')
		);
	}

	function getExtractionModes()
	{
		$options = array();
		$options[] = JHTML::_('select.option', 'direct', JText::_('RESTORE_LABEL_EXTRACTIONMETHOD_DIRECT'));
		$options[] = JHTML::_('select.option', 'ftp', JText::_('RESTORE_LABEL_EXTRACTIONMETHOD_FTP'));
		return $options;
	}
	
	function doAjax()
	{
		$ajax = $this->getState('ajax');
		switch($ajax)
		{
			// FTP Connection test for DirectFTP
			case 'testftp':
				// Grab request parameters
				$config = array(
					'host' => FOFInput::getVar('host', '', $this->input),
					'port' => FOFInput::getInt('port', 21, $this->input),
					'user' => FOFInput::getVar('user', '', $this->input),
					'pass' => FOFInput::getVar('pass', '', $this->input, 'none', 2),
					'initdir' => FOFInput::getVar('initdir', '', $this->input),
					'usessl' => FOFInput::getCmd('usessl') == 'true',
					'passive' => FOFInput::getCmd('passive') == 'true'
				);

				// Perform the FTP connection test
				$test = new AEArchiverDirectftp();
				$test->initialize('', $config);
				$errors = $test->getError();
				if(empty($errors))
				{
					$result = true;
				}
				else
				{
					$result = $errors;
				}
				break;

			// Unrecognized AJAX task
			default:
				$result = false;
				break;
		}
		
		return $result;
	}
}