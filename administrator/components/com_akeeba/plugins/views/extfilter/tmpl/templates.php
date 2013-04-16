<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 2.1
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

jimport('joomla.html.html');

?>
<div id="akeeba-container" style="width:100%">

<fieldset>
	<legend><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?>: #<?php echo $this->profileid; ?></legend>
	<?php echo $this->profilename; ?>
</fieldset>

<h2><?php echo JText::_('EXTFILTER_TEMPLATES'); ?></h2>
<table class="adminlist" width="100%">
	<thead>
		<tr>
			<th width="50px"><?php echo JText::_('EXTFILTER_LABEL_STATE'); ?></th>
			<th><?php echo JText::_('EXTFILTER_LABEL_TEMPLATE'); ?></th>
			<th><?php echo JText::_('EXTFILTER_LABEL_AREA'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$i = 0;
	foreach($this->templates as $template):
	$i++;
	$link = JURI::base().'index.php?option=com_akeeba&view=extfilter&task=toggleTemplate&item='.$template['item'].'&root='.$template['root'].'&random='.time();
	$area = $template['client_id'] == 0 ? JText::_('EXTFILTER_LABEL_FRONTEND') : JText::_('EXTFILTER_LABEL_BACKEND');
	if($template['status'])
	{
		$image = JHTML::_('image.administrator', 'admin/publish_x.png');
		$html = '<b>'.$template['name'].'</b>';
	}
	else
	{
		$image = JHTML::_('image.administrator', 'admin/tick.png');
		$html = $template['name'];
	}

	?>
		<tr class="row<?php echo $i%2; ?>">
			<td style="text-align: center;"><a href="<?php echo $link ?>"><?php echo $image ?></a></td>
			<td><a href="<?php echo $link ?>"><?php echo $html ?></a></td>
			<td><?php echo $area ?></td>
		</tr>
	</tbody>
	<?php
	endforeach;
	?>
</table>

</div>