<?php
/**
 * @package AkeebaBackup
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 *
 * @since 3.0
 */

defined('_JEXEC') or die();

if(!class_exists('AkeebaHelperEscape')) JLoader::import('helpers.escape', JPATH_COMPONENT_ADMINISTRATOR);

?>
<div id="akeeba-container" style="width:100%">
	
<div id="dialog" title="<?php echo JText::_('FSFILTER_ERROR_TITLE') ?>">
</div>

<fieldset>
	<legend><?php echo JText::_('CPANEL_PROFILE_TITLE'); ?>: #<?php echo $this->profileid; ?></legend>
	<?php echo $this->profilename; ?>
</fieldset>

<fieldset>
	<legend><?php echo JText::_('DBFILTER_LABEL_ROOTDIR') ?></legend>
	<div id="ak_roots_container_tab">
		<span><?php echo $this->root_select; ?></span>
	</div>
</fieldset>

<fieldset>
	<div id="ak_list_container">
		<table id="table-container" class="adminlist">
			<thead>
				<tr>
					<td width="48px">&nbsp;</td>
					<td width="250px"><?php echo JText::_('FILTERS_LABEL_TYPE') ?></td>
					<td><?php echo JText::_('FILTERS_LABEL_FILTERITEM') ?></td>
				</tr>
			</thead>
			<tbody id="ak_list_contents" class="table-container">
			</tbody>
		</table>
	</div>
</fieldset>

<script type="text/javascript">
/**
 * Callback function for changing the active root in Filesystem Filters
 */
function akeeba_active_root_changed()
{
	(function($){
		regexfsfilter_load($('#active_root').val());
	})(akeeba.jQuery);
}

akeeba.jQuery(document).ready(function($){
	// Set the AJAX proxy URL
	akeeba_ajax_url = '<?php echo AkeebaHelperEscape::escapeJS(JURI::base().'index.php?option=com_akeeba&view=regexdbfilter&task=ajax') ?>';
	// Set the media root
	akeeba_ui_theme_root = '<?php echo $this->mediadir ?>';
	// Create the dialog
	$("#dialog").dialog({
		autoOpen: false,
		closeOnEscape: false,
		height: 200,
		width: 300,
		hide: 'slide',
		modal: true,
		position: 'center',
		show: 'slide'
	});
	// Create an AJAX error trap
	akeeba_error_callback = function( message ) {
		var dialog_element = $("#dialog");
		dialog_element.html(''); // Clear the dialog's contents
		dialog_element.dialog('option', 'title', '<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TITLE')) ?>');
		$(document.createElement('p')).html('<?php echo AkeebaHelperEscape::escapeJS(JText::_('CONFIG_UI_AJAXERRORDLG_TEXT')) ?>').appendTo(dialog_element);
		$(document.createElement('pre')).html( message ).appendTo(dialog_element);
		dialog_element.dialog('open');
	};
	// Push translations
	akeeba_translations['UI-ROOT'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('FILTERS_LABEL_UIROOT')) ?>';
	akeeba_translations['UI-ERROR-FILTER'] = '<?php echo AkeebaHelperEscape::escapeJS(JText::_('FILTERS_LABEL_UIERRORFILTER')) ?>';
<?php
	$filters = array('tables', 'tabledata');
	foreach($filters as $type)
	{
		echo "\takeeba_translations['UI-FILTERTYPE-REGEX".strtoupper($type)."'] = '".
			AkeebaHelperEscape::escapeJS(JText::_('DBFILTER_TYPE_REGEX'.strtoupper($type))).
			"';\n";
	}
?>
	// Bootstrap the page display
	var data = JSON.parse('<?php echo AkeebaHelperEscape::escapeJS($this->json); ?>');
	regexdbfilter_render(data);
});
</script>

</div>