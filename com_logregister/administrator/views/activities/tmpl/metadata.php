<?php

$input = JFactory::getApplication()->input;
$id    = $input->getInt('activityid',0);

if ($id > 0)
{
	// Initialiase variables.
	$db    = JFactory::getDbo();

	$query = $db->getQuery(true);

	// Create the base select statement.
	$query->select('a.*')
		->from($db->quoteName('#__activities_activities') . ' AS a')
		->where($db->quoteName('a.id') . ' = ' . $db->quote($id));

	// Set the query and load the result.
	$db->setQuery($query);
	$result = $db->loadObject();

	// Check for a database error.
	if ($db->getErrorNum())
	{
		JError::raiseWarning(500, $db->getErrorMsg());

		return null;
	}

	?>
	<div class="span12">
	<table class="table">
		<tr>
			<td>Action</td><td>:</td><td><?php echo $result->action; ?></td>
		</tr>
		<tr>
			<td>Element</td><td>:</td><td><?php echo $result->package; ?></td>
		</tr>
		<tr>
			<td>Date Time of Action</td><td>:</td><td><?php echo $result->created_on; ?></td>
		</tr>
		<tr>
			<td>ip</td><td>:</td><td><?php echo $result->ip; ?></td>
		</tr>
		<tr>
			<td>Note</td><td>:</td><td><?php echo $result->note; ?></td>
		</tr>
	</table>
	<?php

	?>
	</div>
	<?php
}