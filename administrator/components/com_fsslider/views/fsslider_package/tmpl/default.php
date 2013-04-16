<?php
/**
 * @version $Id: fsslider.php 2011-01-25 12:41:40 svn $
 * @package    Fsslider
 * @subpackage Views
 * @author     EasyJoomla {@link http://www.easy-joomla.org Easy-Joomla.org}
 * @author     David Fritsch {@link fritschservices.com}
 * @author     Created on 27-May-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') or die('=;)');

?>
<form id="adminForm" action="<?php echo JRoute::_( 'index.php?option=com_fsslider' );?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JHTML::_( 'grid.sort', 'ID', 'image_id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JHTML::_( 'grid.sort', 'Name', 'name', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
            <th>
				<?php echo JHTML::_( 'grid.sort', 'Image', 'location', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
            <th>
				<?php echo JHTML::_( 'grid.sort', 'Link', 'link', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
            <th>
				<?php echo JHTML::_( 'grid.sort', 'Description', 'description', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
            <th width="1%" nowrap="nowrap">
				<?php echo JHTML::_( 'grid.sort', 'Enabled', 'state', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
            <th width="100">
				<?php echo JHTML::_( 'grid.sort', 'Order', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>            
		</tr>			
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$link 		= JRoute::_( 'index.php?option=com_fsslider&controller=fsslider&task=edit&package_id='.$this->package->id.'&cid[]='. $row->image_id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->image_id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
            <td>
           		<?php if ($row->location) : ?>
					<a href="<?php echo $link; ?>"><img src="components/com_fsslider/uploads/<?php echo $row->location; ?>" width="200" /></a>
                <?php endif; ?>
				
			</td>
            <td>
				<a href="<?php echo $link; ?>"><?php echo $row->link; ?></a>
			</td>
            <td align="center">
				<a href="<?php echo $link; ?>"><?php echo $row->description; ?></a>
			</td>
            <td align="center">
                <span title="<?php echo JText::_( 'Publish Information' );?>">
                <a href="javascript:void(0);" class="jgrid hasTip" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? 'unpublish' : 'publish' ?>')">
                <span class="state 
                <?php
                if ( $row->state == 1 ) {
					echo "publish";
					$img = 'publish_g.png';
					$alt = JText::_( 'Published' );
				} else if ( $row->state == 0 ) {
					echo "unpublish";
					$img = 'publish_x.png';
					$alt = JText::_( 'Unpublished' );
				} else if ( $row->state == -1 ) {
					echo "archive";
					$img = 'disabled.png';
					$alt = JText::_( 'Archived' );
				}
				?>">
                	<span class="text"><?php echo $alt;?></span>
                </span>
            </td>
            <td style="text-align:center;" class="order">
				<span><?php echo $this->pagination->orderUpIcon( $i, ($row->id), 'orderup', 'Move Up', $row->ordering ); ?></span>
				<?php $disabled = $row->ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="ordering[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
                <span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->id), 'orderdown', 'Move Down', $row->ordering ); ?></span>	
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	 <tfoot>
    <tr>
      <td colspan="8">
      	<?php echo $this->pagination->getListFooter(); ?>
      </td>
    </tr>
  </tfoot>
	</table>
</div>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="task" value="editPackage" />
<input type="hidden" name="package_id" value="<?php echo $this->package->id;?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fsslider" />
</form>



<form id="adminForm2" action="<?php echo JRoute::_( 'index.php?option=com_fsslider' );?>" method="post" name="adminForm2">
<div id="editcell2">
<p></p>
<label for="packagename"><b>Edit Package Name</b></label> <input type="text" name="packagename" value="<?php echo $this->package->name; ?>" />
<input type="submit" value="Edit Package" />
<p></p>
</div>
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
<input type="hidden" name="task" value="editName" />
<input type="hidden" name="package_id" value="<?php echo $this->package->id;?>" />
<input type="hidden" name="cid" value="<?php echo $this->package->id;?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fsslider" />
</form>
