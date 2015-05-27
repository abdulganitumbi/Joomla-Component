<?php
/**
 * @version     1.0.0
 * @package     com_logregister
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      gani tumbi <gani@tasolglobal.com> - http://
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.modal');
JHtml::_('behavior.calendar');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_logregister/assets/css/logregister.css');
$document->addStyleSheet('components/com_logregister/assets/css/icon.css');
$document->addScript('components/com_logregister/assets/js/chart/Chart.js');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_logregister');
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_logregister&task=activities.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'activitieList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

$fromdate = $this->state->get('filter.fromdate');
$todate   = $this->state->get('filter.todate');

?>
<script>
var fromdate = "<?php echo $this->state->get('filter.fromdate'); ?>";
var todate   = "<?php echo $this->state->get('filter.todate'); ?>";
</script>
<style>
.ac_name
{
	color: #880016;
}
.date-head
{
	text-align: center;
}
.input-append input
{
	width : 104px;
}

#j-sidebar-container .seprator {
    margin-top: 15px;
}
.no-activity
{
	text-align: center;
	font-size: 15px;
	font-style: italic;
	color: #333333;
}
</style>
 <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {

	if (fromdate != null)
	{
		jQuery("#fromdate").val(fromdate);
	}
	if (todate != null)
	{
		jQuery("#todate").val(todate);
	}
});
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<?php

if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
$graphurl = JUri::root().'administrator/index.php?option=com_logregister&view=activities&layout=graph&tmpl=component';

?>

<form action="<?php echo JRoute::_('index.php?option=com_logregister&view=activities'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
	<a class="modal btn btn-info" href="<?php echo $graphurl?>" rel="{handler:'iframe', size:{x:450,y:450}}">Show Activity Graph</a>
	<hr>
		<?php echo $this->sidebar;?>
		<div class="seprator"></div>
		<?php
		$format = '%d/%m/%Y';
		?>
		<label> Select From Date : </label>
		<?php
		echo JHtml::_('calendar',"","fromdate","fromdate",$format);
		?>
		<label>Select To Date : </label>
		<?php
		echo JHtml::_('calendar',"","todate","todate",$format); ?>
	 	<button class="btn btn-primary" type="submit">Filter</button>
	 	<button class="btn" type="button" onclick="document.id('fromdate').value='';document.id('todate').value='';this.form.submit();">Reset</button>

	</div>

	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

		<!-- <div id="filter-bar" class="btn-toolbar"> -->
			<div class="filter-search btn-group pull-left">

			</div>
			<div class="btn-group pull-left">
<!-- 				<button class="btn hasTooltip" type="submit"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button> -->
			</div>
			<!-- <div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>


			 <div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div> -->

		<!-- </div> -->


		<div class="clearfix"> </div>
		<div class="span12 activity">
		<table class="table">
			<th class="hidden-phone"><input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" /></th>
			<th><h3> Messege</h3>
			<th><h3> Date & Time</h3></th>

		<?php
		$items = $this->items;
		if (count($items) == 0)
		{
			echo "<tr><td colspan='3'><p class='no-activity'>No Activity Found</p></td></tr>";
		}
			for ($i = 0;$i < count($items); $i++)
			{
				if ($i > 0)
				{
					$olddate = substr($items[$i-1]->created_on, 0,strpos($items[$i-1]->created_on, " "));
					$newdate = substr($items[$i]->created_on, 0,strpos($items[$i]->created_on, " "));

					if ($olddate != $newdate)
					{
						$date=date_create($newdate);

						echo "<tr><td colspan='3'><h4 class='date-head'>"
						.date_format($date,"d M Y")."</h4></td></tr>";
					}
				}

				$link = JUri::root().'administrator/index.php?option=com_logregister&view=activities&layout=metadata&tmpl=component&activityid='.$items[$i]->id;
				if ($items[$i]->status == "updated")
				{
					echo "<tr><td>" . JHtml::_('grid.id', $i, $items[$i]->id). "</td><td><i class='icon-update'></i> <span class='ac_name'>".$items[$i]->user_name."</span>"." ".$items[$i]->status." ".$items[$i]->title."</td><td>".$items[$i]->created_on." <a href='#myModal' rel='{handler:'iframe', size:{x:700,y:300}}' class='modal'  id='".$items[$i]->id."'>View Details</a></td></tr>";
				}
				else if (strstr($items[$i]->status,"Login") != false)
				{
					echo "<tr><td>" . JHtml::_('grid.id', $i, $items[$i]->id). "</td><td><i class='icon-user'></i> <span class='ac_name'>".$items[$i]->user_name."</span>"." ".$items[$i]->status." <a href='#myModal' role='button' data-toggle='modal' class='modal'  id='".$items[$i]->id."'>View Details</a> </td><td>".$items[$i]->created_on."</td></tr>";
				}
				else if (strstr($items[$i]->status,"Logout") != false)
				{
					?>
					<tr><td><?php echo JHtml::_('grid.id', $i, $items[$i]->id); ?></td><td><span class='icon-off'></span> <span class='ac_name'><?php echo $items[$i]->user_name; ?></span><?php echo $items[$i]->status; ?><a class="modal" href="<?php echo $link?>" rel="{handler:'iframe', size:{x:700,y:300}}">View details</a>
					</td><td><?php echo $items[$i]->created_on; ?></td></tr>
					<?php
				}
				else if($items[$i]->status == "created")
				{
					?>
					<tr><td><?php echo JHtml::_('grid.id', $i, $items[$i]->id); ?></td><td><span class='icon-off'></span> <span class='ac_name'><?php echo $items[$i]->user_name; ?></span><?php echo $items[$i]->status; ?><a class="modal" href="<?php echo $link?>" rel="{handler:'iframe', size:{x:700,y:300}}">View details</a>
					</td><td><?php echo $items[$i]->created_on; ?></td></tr>
					<?php
				}
				else if($items[$i]->status == "deleted")
				{
					?>
					<tr><td><?php echo JHtml::_('grid.id', $i, $items[$i]->id); ?></td><td><span class='icon-off'></span> <span class='ac_name'><?php echo $items[$i]->user_name; ?></span><?php echo $items[$i]->status; ?><a class="modal" href="<?php echo $link?>" rel="{handler:'iframe', size:{x:700,y:300}}">View details</a>
					</td><td><?php echo $items[$i]->created_on; ?></td></tr>
					<?php
				}
				else
				{
					?>
					<tr><td><?php echo JHtml::_('grid.id', $i, $items[$i]->id); ?></td><td><span class='icon-off'></span> <span class='ac_name'><?php echo $items[$i]->user_name; ?></span><?php echo $items[$i]->status; ?><a class="modal" href="<?php echo $link?>" rel="{handler:'iframe', size:{x:700,y:300}}">View details</a>
					</td><td><?php echo $items[$i]->created_on; ?></td></tr>
					<?php
				}
			}


		?>
		</table>
			</div>


		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>


