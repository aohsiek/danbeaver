<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0
 */

// Make sure we're being called from the command line, not a web interface
if( array_key_exists('REQUEST_METHOD', $_SERVER) )
{
	die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
}

// Show a warning
?>
********************************************************************************
IMPORTANT NOTE - THE backup.php FILE IS DEPRECATED
********************************************************************************

Since Akeeba Backup 3.5.0, you are not supposed to use the backup.php script
file inside Akeeba Backup's directory. Instead, please use the akeeba-backup.php
file located inside your site's cli directory.

This file is going to be removed in Akeeba Backup 3.6.0.

********************************************************************************

<?php
// Load the correct file
if(file_exists('../../../cli/akeeba-backup.php')) {
	require_once '../../../cli/akeeba-backup.php';
} else {
	require_once '../../cli/akeeba-backup.php';
}