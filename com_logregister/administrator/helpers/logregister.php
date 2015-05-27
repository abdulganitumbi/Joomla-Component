<?php

/**
 * @version     1.0.0
 * @package     com_logregister
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      gani tumbi <gani@tasolglobal.com> - http://
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Logregister helper.
 */
class LogregisterHelper {

    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '') {

        JHtmlSidebar::addEntry(
			JText::_('COM_LOGREGISTER_TITLE_ACTIVITIES_ALL'),
			'index.php?option=com_logregister&view=activities',
			$vName == 'activities'
		);

        // Initialiase variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('DISTINCT package')
            ->from($db->quoteName('#__activities_activities') . ' AS a');

        // Set the query and load the result.
        $db->setQuery($query);
        $result = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum())
        {
            JError::raiseWarning(500, $db->getErrorMsg());

            return null;
        }

        for ($i = 0; $i < count($result); $i++)
        {
            JHtmlSidebar::addEntry(
            $result[$i]->package,
            'index.php?option=com_logregister&view=activities&package='.$result[$i]->package,
            $vName == 'logregisters'
            );
        }

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return	JObject
     * @since	1.6
     */
    public static function getActions() {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_logregister';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }


}
