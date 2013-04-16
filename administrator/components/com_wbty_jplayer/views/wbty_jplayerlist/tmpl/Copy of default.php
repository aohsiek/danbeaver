<?php

defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title(JText::_('Wbty_jplayer'), 'generic.png');



if (isset($_GET['id'])) {

	$id = $_GET['id'];

	

	$db = & JFactory::getDBO();

	$query = "SELECT * FROM jos_wbty_jplayer WHERE id='$id' LIMIT 1";

	$db->setQuery( $query );

	$result = $db->loadObjectList();

	

	foreach ($result as $row) {

		$location = $row->location;

		$title = $row->title;

	}

} else {

	$location='';

	$title='';

}

?>

<form action = "index.php" method =  "post" id = "adminForm" name = "adminForm" enctype="multipart/form-data" />



<div class="col100">

    <fieldset class="adminform">

        <legend>

        <?php 

		if (!isset($_GET['id'])) {

			echo JText::_( 'Add Track');

		} else {

			echo JText::_( 'Edit Track');

		}

		?></legend>

        <table class="admintable">

        <tr>

            <td width="100" align="right" class="key">

                <label for="name">

                    <?php echo JText::_( 'Track Title' ); ?>:

                </label>

            </td>

            <td>

                <input class="text_area" type="text" name="title" id="title" size="32" maxlength="40" value="<?php echo $title;?>" />

            </td>

        </tr>

        <tr>

            <td width="100" align="right" class="key">

                <label for="email">

                    <?php echo JText::_( 'Upload MP3' ); ?>:

                </label>

            </td>

            <td>

            	<?php echo "<p>Current File at $location</p>"; ?><input type="hidden" value="<?php echo $location; ?>" />

                <input type="file" name="mp3[]" id="mp3" />

            </td>

        </tr>

        <tr>

        	<td width="100" align="right" class="key">

            	<label for="order">

                    <?php echo JText::_( 'Track Order' ); ?>:

                </label>

            </td>

            <td>

           		<?php if (!isset($_GET['id'])) {

					echo "<p>New items default to the last position.</p>";

				} else {

					echo "<p>Edit Order on the main screen.</p>";

				}

				?>

            </td>

        </tr>

    </table>

    </fieldset>

</div>

 

<div class="clr"></div>



 <input type = "hidden" name = "id" value = "<?php if (isset($id)) {echo $id;} ?>" />

 <input type = "hidden" name = "task" value = "" />

 <input type = "hidden" name = "option" value = "com_wbty_jplayer" />

</form>

