<?php
/**
 * @version     1.0.0
 * @package     com_logregister
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      gani tumbi <gani@tasolglobal.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Activities list controller class.
 */
class LogregisterControllerActivities extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'activitie', $prefix = 'LogregisterModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

    function getModalData()
    {
		$input = JFactory::getApplication()->input;
		$id    = $input->getInt('id',0);
		$model = $this->getModel();
		if ($id > 0)
		{
			$result = $model->getModalData($id);
			echo json_encode($result->metadata);
			exit;
		}
    }

    function export()
    {

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*')
			->from($db->quoteName('#__activities_activities') . ' AS a');

		// Set the query and load the result.
		$db->setQuery($query);
		$result      = $db->loadObjectList();

		$resultarray = (array) $result[0];
		$this->echocsv(array_keys($resultarray));
		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}
		for ($i = 0; $i < count($result); $i++)
		{
			$resultarray = (array) $result[$i];
			$this->echocsv($resultarray);
		}
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename=export.csv');
		exit;
    }
    function echocsv($fields)
	{
	    $separator = '';
	    foreach ($fields as $field) {
	        if (preg_match('/\\r|\\n|,|"/', $field)) {
	            $field = '"' . str_replace('"', '""', $field) . '"';
	        }
	        echo $separator . $field;
	        $separator = ',';
	    }
	    echo "\r\n";
	}


}