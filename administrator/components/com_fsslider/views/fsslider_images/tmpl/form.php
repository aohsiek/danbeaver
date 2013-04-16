<?php
/**
 * @version $Id: fsslider.php 2011-01-25 12:41:40 svn $
 * @package    Fsslider
 * @subpackage Base
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */
//-- No direct access
defined('_JEXEC') or die('=;)');
$doc =& JFactory::getDocument();
$doc->addScript( JURI::base() . "components/com_fsslider/libraries/jquery.js");
$doc->addStylesheet( JURI::base() . "components/com_fsslider/libraries/fancybox/jquery.fancybox-1.3.4.css");
$doc->addScript( JURI::base() . "components/com_fsslider/libraries/fancybox/jquery.fancybox-1.3.4.js");
$script = "
$(document).ready( function () {
	$('button').fancybox({hideOnContentClick:true});
	
	$('#fsslider_menu li a').click(function () {
		$('#link').val($(this).attr('rel'));
	});
	
	$('#slide_type').click(function () {
		slide_type = $('input[name=slide_type]:checked').val();
		if (slide_type=='image') {
			$('#image_fieldset').show();
			$('#content_fieldset').hide();
		} else if (slide_type=='content') {
			$('#image_fieldset').hide();
			$('#content_fieldset').show();
		}
			 
	});
});

function addFormField() { 
	var id = parseInt($('#imageId').val());
	id = id + 1;
	var string = '<tr id=\"'+id+'\"><td width=\"100\" align=\"right\" class=\"key\"><label for=\"title\">Image '+id+':</label></td><td><input type=\"file\" name=\"jpg'+id+'\" id=\"jpg'+id+'\" /><input type=\"hidden\" name=\"location'+id+'\" id=\"location'+id+'\" value=\"\" /></td></tr>';
	$('#contentTable tr:last').after(string);
	$('#imageId').val(id);
}

function removeFormField(id) {
	$(id).remove();
}
";
$doc->addScriptDeclaration($script);
?>
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
	<fieldset class="adminform">
    	<legend>
        Base Settings
        </legend>
        <table class="admintable">
        <tr>
			<td width="100" align="right" class="key">
				<?php 
				echo JHTML::tooltip('Enter a name for your image for your reference. This will not show on the front end.', 'Image Name', 
	            '', '<label for="title">
					'. JText::_('Image name') .':
				</label>');?>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo $this->Fsslider->name;?>" />
			</td>
		</tr>
        <tr>
        	<td width="100" align="right" class="key">
            <label>Slide Type</label>
            </td>
            <td><div id="slide_type"><label for="slide_type1"> Image Slide </label><input type="radio" name="slide_type" value="image" id="slide_type1" <?php echo ($this->Fsslider->slide_type=='image' ? "checked='checked'" : ""); ?> /><br />
            <label for="slide_type2"> Content Slide </label><input type="radio" name="slide_type" value="content" id="slide_type2" <?php echo ($this->Fsslider->slide_type=='content' ? "checked='checked'" : ""); ?> />
            </div>
            </td>
        </tr>
        </table>
    </fieldset>
    
	<fieldset class="adminform" id="image_fieldset" <?php if ($this->Fsslider->slide_type!='image') { echo "style='display:none;'"; } ?>>
		<legend>
        <?php
			echo JText::_( 'Image Slide');
		?></legend>
		<table class="admintable">
        <tr>
			<td width="100" align="right" class="key">
            
            <?php 
				echo JHTML::tooltip('Select or enter a link for the image. The link will only be active if "show links" is activated in the settings.', 'Link', 
	            '', '<label for="link">
					'. JText::_('Link') .':
				</label>');?>
			</td>
			<td>
				<input class="text_area" type="text" name="link" id="link" size="32" maxlength="250" value="<?php echo $this->Fsslider->link;?>" /> <button name="selectLink" class="button" href="#fsslider_menu"> View Menus </button>
                
                <div style="display:none">
                	<div id="fsslider_menu" style="width:500px; height:400px; overflow:scroll;">
                    <?php echo $this->menus; ?>
                    </div>
                </div>
			</td>
		</tr>
        <tr>
			<td width="100" align="right" class="key">
            
            <?php 
				echo JHTML::tooltip('Upload an image from your computer for this slide.', 'Image', 
	            '', '<label for="title">
					'. JText::_('Image') .':
				</label>');?>
			</td>
			<td>
            	<?php
                if (isset($this->Fsslider->id)) {
                    echo JText::_('Current File: '.$this->Fsslider->location.'<br>');
                }
				?>
				<input type="file" name="jpg" id="jpg" />
                <input type="hidden" name="location" id="location" value="<?php echo $this->Fsslider->location; ?>" />
			</td>
		</tr>
        <tr>
			<td width="100" align="right" class="key">
            
            <?php 
				echo JHTML::tooltip('Enter a description. This will show as the caption on the image if captions are activated', 'Description', 
	            '', '<label for="title">
					'. JText::_('Description') .':
				</label>');?>
			</td>
			<td>
				<?php
					$editor =& JFactory::getEditor();
					echo $editor->display('description', $this->Fsslider->description, '550', '300', '60', '20', false);
				?>
			</td>
		</tr>
	</table>
	</fieldset>
    
    <fieldset class="adminform" id="content_fieldset" <?php if ($this->Fsslider->slide_type!='content') { echo "style='display:none;'"; } ?>>
		<legend>
        <?php
			echo JText::_( 'Content Slide');
		?></legend>
		<table class="admintable" id="contentTable">
        <tr>
			<td width="100" align="right" class="key">
            
            <?php 
				echo JHTML::tooltip('Enter the content for the slide. ', 'Content', 
	            '', '<label for="content">
					'. JText::_('Content') .':
				</label>');?>
			</td>
			<td>
				<?php
					$editor =& JFactory::getEditor();
					echo $editor->display('content', $this->Fsslider->content, '550', '400', '60', '20', false);
				?>
				<!--<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo $this->Fsslider->description;?>" />-->
			</td>
		</tr>
        <?php
		for ($i = 1; $i<=count($this->Fsslider->images); $i++) {
			?>
        <tr>
			<td width="100" align="right" class="key">
            
            <?php 
				echo JHTML::tooltip('Upload an image from your computer for this slide.', 'Image '.$i, 
	            '', '<label for="title">
					'. JText::_('Image '.$i) .':
				</label>');?>
			</td>
			<td>
            	<?php
                if (isset($this->Fsslider->id)&&$this->Fsslider->images[0]->id!=0) {
                    echo JText::_('Current File: '.$this->Fsslider->images[$i-1]->location.'<br>');
                }
				?>
				<input type="file" name="jpg<?php echo $i; ?>" id="jpg<?php echo $i; ?>" />
                <input type="hidden" name="location<?php echo $i; ?>" id="location<?php echo $i; ?>" value="<?php echo $this->Fsslider->images[$i-1]->location; ?>" />
			</td>
		</tr>
        <?php } ?>
	</table>
    <input type="hidden" name="imageId" id="imageId" value="<?php echo count($this->Fsslider->images); ?>" />
    <a href="" onclick="addFormField(); return false;"> Add another image </a>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="option" value="com_fsslider" />
<input type="hidden" name="ordering" value="<?php echo $this->Fsslider->ordering; ?>" />
<input type="hidden" name="package_id" value="<?php echo $this->Fsslider->package_id; ?>" />
<input type="hidden" name="edit" value="<?php echo $this->Fsslider->id ? 1 : 0; ?>" />
<input type="hidden" name="id" value="<?php echo $this->Fsslider->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="fsslider" />
</form>
