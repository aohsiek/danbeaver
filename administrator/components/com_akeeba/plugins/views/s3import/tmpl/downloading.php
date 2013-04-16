<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 * @since 3.4
 */

// Protect from unauthorized access
defined('_JEXEC') or die();
?>
<div id="akeeba-container" style="width:100%">
	
<style type="text/css">
	dl { display: none; }
</style>

<div id="backup-percentage">
	<div id="progressbar-inner" class="color-overlay"></div>
</div>

<div id="backup-status">
	<div id="backup-substep">
		<?php echo JText::sprintf('REMOTEFILES_LBL_DOWNLOADEDSOFAR', $this->done, $this->total, $this->percent); ?>
	</div>
</div>

</div>