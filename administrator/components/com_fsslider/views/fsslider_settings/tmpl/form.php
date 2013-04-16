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

?>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm">

<div class="col100">

	<fieldset class="adminform">

		<legend>

        <?php

        echo JText::_( 'Global Setting');

		?></legend>

        

        <p>Override these settings within any FS Slider Module. Use global settings to make sure that your slideshows work consistently across your site and to make changing settings quick and easy!</p>



		<table class="admintable">

		<tr>

			<td width="100" align="right" class="key">

				<label for="includeJquery">

					<?php echo JText::_('Include jQuery'); ?>:

				</label>

			</td>

			<td>

				<label for="includeJquery1"> Yes </label><input class="text_area" type="radio" name="includeJquery" id="includeJquery1" value="1"<?php if ($this->settings->includeJquery==1) {echo " checked";} ?> />

                <label for="includeJquery0"> No </label><input class="text_area" type="radio" name="includeJquery" id="includeJquery0" value="0"<?php if ($this->settings->includeJquery==0) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="jqueryMethod">

					<?php echo JText::_('Load jQuery Method'); ?>:

				</label>

			</td>

			<td>

				<label for="jqueryMethod0"> Google CDN </label><input class="text_area" type="radio" name="jqueryMethod" id="jqueryMethod0" value="0"<?php if ($this->settings->jqueryMethod==0) {echo " checked";} ?> />

                <label for="jqueryMethod1"> Local Copy </label><input class="text_area" type="radio" name="jqueryMethod" id="jqueryMethod1" value="1"<?php if ($this->settings->jqueryMethod==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="width">

					<?php echo JText::_('Default Width'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="width" id="width" size="32" maxlength="250" value="<?php echo $this->settings->width;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="height">

					<?php echo JText::_('Default Height'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="height" id="height" size="32" maxlength="250" value="<?php echo $this->settings->height;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="linkTarget">

					<?php echo JText::_('Link Target'); ?>:

				</label>

			</td>

			<td>

				<label for="linkTarget0"> Self </label><input class="text_area" type="radio" name="linkTarget" id="linkTarget0" value="0"<?php if ($this->settings->linkTarget==0) {echo " checked";} ?> />

                <label for="linkTarget1"> New Window </label><input class="text_area" type="radio" name="linkTarget" id="linkTarget1" value="1"<?php if ($this->settings->linkTarget==1) {echo " checked";} ?> />

                <label for="linkTarget2"> Parent Window </label><input class="text_area" type="radio" name="linkTarget" id="linkTarget2" value="2"<?php if ($this->settings->linkTarget==2) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="theme">

					<?php echo JText::_('Theme'); ?>:

				</label>

			</td>

			<td>

				<label for="theme0"> Default </label><input class="text_area" type="radio" name="theme" id="theme0" value="0"<?php if ($this->settings->theme==0) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="customCSS">

					<?php echo JText::_('Custom CSS'); ?>:

				</label>

			</td>

			<td>

				<textarea class="text_area" name="customCSS" id="customCSS"><?php echo $this->settings->customCSS;?></textarea>

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="resizeContents">

					<?php echo JText::_('Resize Contents'); ?>:

				</label>

			</td>

			<td>

				<label for="resizeContents0"> No </label><input class="text_area" type="radio" name="resizeContents" id="resizeContents0" value="0"<?php if ($this->settings->resizeContents==0) {echo " checked";} ?> />

                <label for="resizeContents1"> Yes </label><input class="text_area" type="radio" name="resizeContents" id="resizeContents1" value="1"<?php if ($this->settings->resizeContents==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="buildArrows">

					<?php echo JText::_('Include Navigation Arrows'); ?>:

				</label>

			</td>

			<td>

				<label for="buildArrows0"> No </label><input class="text_area" type="radio" name="buildArrows" id="buildArrows0" value="0"<?php if ($this->settings->buildArrows==0) {echo " checked";} ?> />

                <label for="buildArrows1"> Yes </label><input class="text_area" type="radio" name="buildArrows" id="buildArrows1" value="1"<?php if ($this->settings->buildArrows==1) {echo " checked";} ?> />

            </td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="buildNavigation">

					<?php echo JText::_('Include Navigation'); ?>:

				</label>

			</td>

			<td>

				<label for="buildNavigation0"> No </label><input class="text_area" type="radio" name="buildNavigation" id="buildNavigation0" value="0"<?php if ($this->settings->buildNavigation==0) {echo " checked";} ?> />

                <label for="buildNavigation1"> Yes </label><input class="text_area" type="radio" name="buildNavigation" id="buildNavigation1" value="1"<?php if ($this->settings->buildNavigation==1) {echo " checked";} ?> />

            </td>

		</tr>
        
        <tr>

			<td width="100" align="right" class="key">

				<label for="buildStartStop">

					<?php echo JText::_('Include Start-Stop Button'); ?>:

				</label>

			</td>

			<td>

				<label for="buildStartStop0"> No </label><input class="text_area" type="radio" name="buildStartStop" id="buildStartStop0" value="0"<?php if ($this->settings->buildStartStop==0) {echo " checked";} ?> />

                <label for="buildStartStop1"> Yes </label><input class="text_area" type="radio" name="buildStartStop" id="buildStartStop1" value="1"<?php if ($this->settings->buildStartStop==1) {echo " checked";} ?> />

            </td>

		</tr>

        
        <tr>

			<td width="100" align="right" class="key">

				<label for="buildThumbnails">

					<?php echo JText::_('Include Thumbnails'); ?>:

				</label>

			</td>

			<td>

				<label for="buildThumbnails0"> No </label><input class="text_area" type="radio" name="buildThumbnails" id="buildThumbnails0" value="0"<?php if ($this->settings->buildThumbnails==0) {echo " checked";} ?> />

                <label for="buildThumbnails1"> Yes </label><input class="text_area" type="radio" name="buildThumbnails" id="buildThumbnails1" value="1"<?php if ($this->settings->buildThumbnails==1) {echo " checked";} ?> />

            </td>

		</tr>
        
        <tr>

			<td width="100" align="right" class="key">

				<label for="thumbType">

					<?php echo JText::_('Type of Thumbnail'); ?>:

				</label>

			</td>

			<td>

				<label for="thumbType0"> "Dot" </label><input class="text_area" type="radio" name="thumbType" id="thumbType0" value="0"<?php if ($this->settings->thumbType==0) {echo " checked";} ?> />

                <label for="thumbType1"> "Small Image" </label><input class="text_area" type="radio" name="thumbType" id="thumbType1" value="1"<?php if ($this->settings->thumbType==1) {echo " checked";} ?> />

            </td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="forwardText">

					<?php echo JText::_('Forward Text'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="forwardText" id="forwardText" size="32" maxlength="250" value="<?php echo $this->settings->forwardText;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="backText">

					<?php echo JText::_('Back Text'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="backText" id="backText" size="32" maxlength="250" value="<?php echo $this->settings->backText;?>" />

			</td>

		</tr>

        

        <tr>

			<td width="100" align="right" class="key">

				<label for="enableLinks">

					<?php echo JText::_('Enable Links'); ?>:

				</label>

			</td>

			<td>

				<label for="enableLinks0"> No </label><input class="text_area" type="radio" name="enableLinks" id="enableLinks0" value="0"<?php if ($this->settings->enableLinks==0) {echo " checked";} ?> />

                <label for="enableLinks1"> Yes </label><input class="text_area" type="radio" name="enableLinks" id="enableLinks1" value="1"<?php if ($this->settings->enableLinks==1) {echo " checked";} ?> />

            </td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="displayCaption">

					<?php echo JText::_('Show Caption'); ?>:

				</label>

			</td>

			<td>

				<label for="displayCaption0"> No </label><input class="text_area" type="radio" name="displayCaption" id="displayCaption0" value="0"<?php if ($this->settings->displayCaption==0) {echo " checked";} ?> />

                <label for="displayCaption1"> Yes </label><input class="text_area" type="radio" name="displayCaption" id="displayCaption1" value="1"<?php if ($this->settings->displayCaption==1) {echo " checked";} ?> />

            </td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="captionPos">

					<?php echo JText::_('Caption Position'); ?>:

				</label>

			</td>

			<td>

				<select name="captionPos" id="captionPos">

                	<option value="bottom"<?php if ($this->settings->captionPos=='bottom') {echo " selected";}?>>bottom</option>

                    <option value="top"<?php if ($this->settings->captionPos=='top') {echo " selected";}?>>top</option>

                    <option value="left"<?php if ($this->settings->captionPos=='left') {echo " selected";}?>>left</option>

                    <option value="right"<?php if ($this->settings->captionPos=='right') {echo " selected";}?>>right</option>

                    <!--<option value="VALUE"<?php if ($this->settings->captionPos=='VALUE') {echo " selected";}?>>VALUE</option>-->

                </select>  

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="autoPlay">

					<?php echo JText::_('Auto Play'); ?>:

				</label>

			</td>

			<td>

				<label for="autoPlay0"> No </label><input class="text_area" type="radio" name="autoPlay" id="autoPlay0" value="0"<?php if ($this->settings->autoPlay==0) {echo " checked";} ?> />

                <label for="autoPlay1"> Yes </label><input class="text_area" type="radio" name="autoPlay" id="autoPlay1" value="1"<?php if ($this->settings->autoPlay==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="pauseOnHover">

					<?php echo JText::_('Pause on Mouse Hover'); ?>:

				</label>

			</td>

			<td>

				<label for="pauseOnHover0"> No </label><input class="text_area" type="radio" name="pauseOnHover" id="pauseOnHover0" value="0"<?php if ($this->settings->pauseOnHover==0) {echo " checked";} ?> />

                <label for="pauseOnHover1"> Yes </label><input class="text_area" type="radio" name="pauseOnHover" id="pauseOnHover1" value="1"<?php if ($this->settings->pauseOnHover==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="resumeOnVideoEnd">

					<?php echo JText::_('Resume on Video End'); ?>:

				</label>

			</td>

			<td>

				<label for="resumeOnVideoEnd0"> No </label><input class="text_area" type="radio" name="resumeOnVideoEnd" id="resumeOnVideoEnd0" value="0"<?php if ($this->settings->resumeOnVideoEnd==0) {echo " checked";} ?> />

                <label for="resumeOnVideoEnd1"> Yes </label><input class="text_area" type="radio" name="resumeOnVideoEnd" id="resumeOnVideoEnd1" value="1"<?php if ($this->settings->resumeOnVideoEnd==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="stopAtEnd">

					<?php echo JText::_('Stop at End'); ?>:

				</label>

			</td>

			<td>

				<label for="stopAtEnd0"> No </label><input class="text_area" type="radio" name="stopAtEnd" id="stopAtEnd0" value="0"<?php if ($this->settings->stopAtEnd==0) {echo " checked";} ?> />

                <label for="stopAtEnd1"> Yes </label><input class="text_area" type="radio" name="stopAtEnd" id="stopAtEnd1" value="1"<?php if ($this->settings->stopAtEnd==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="playRtl">

					<?php echo JText::_('Play Right to Left'); ?>:

				</label>

			</td>

			<td>

				<label for="playRtl0"> No </label><input class="text_area" type="radio" name="playRtl" id="playRtl0" value="0"<?php if ($this->settings->playRtl==0) {echo " checked";} ?> />

                <label for="playRtl1"> Yes </label><input class="text_area" type="radio" name="playRtl" id="playRtl1" value="1"<?php if ($this->settings->playRtl==1) {echo " checked";} ?> />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="startText">

					<?php echo JText::_('Start Text'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="startText" id="startText" size="32" maxlength="250" value="<?php echo $this->settings->startText;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="stopText">

					<?php echo JText::_('Stop Text'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="stopText" id="stopText" size="32" maxlength="250" value="<?php echo $this->settings->stopText;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="delay">

					<?php echo JText::_('Delay'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="delay" id="delay" size="32" maxlength="250" value="<?php echo $this->settings->delay;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="animationTime">

					<?php echo JText::_('Animation Time'); ?>:

				</label>

			</td>

			<td>

				<input class="text_area" type="text" name="animationTime" id="animationTime" size="32" maxlength="250" value="<?php echo $this->settings->animationTime;?>" />

			</td>

		</tr>

        <tr>

			<td width="100" align="right" class="key">

				<label for="easing">

					<?php echo JText::_('Animation Time'); ?>:

				</label>

			</td>

			<td>

				<select name="easing" id="easing">

                	<option value="linear"<?php if ($this->settings->animationTime=='linear') {echo " selected";}?>>linear</option>

                    <option value="swing"<?php if ($this->settings->animationTime=='swing') {echo " selected";}?>>swing</option>

                    <!--<option value="VALUE"<?php if ($this->settings->animationTime=='VALUE') {echo " selected";}?>>VALUE</option>

                    <option value="VALUE"<?php if ($this->settings->animationTime=='VALUE') {echo " selected";}?>>VALUE</option>

                    <option value="VALUE"<?php if ($this->settings->animationTime=='VALUE') {echo " selected";}?>>VALUE</option>-->

                </select>  

			</td>

		</tr>

	</table>

	</fieldset>

</div>

<div class="clr"></div>



<input type="hidden" name="option" value="com_fsslider" />

<input type="hidden" name="task" value="" />

<input type="hidden" name="controller" value="fsslider" />

</form>

