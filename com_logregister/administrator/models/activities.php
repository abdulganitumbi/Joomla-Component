<?php

/**
 * @version     1.0.0
 * @package     com_logregister
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      gani tumbi <gani@tasolglobal.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Logregister records.
 */
class LogregisterModelActivities extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $fromdate = $app->getUserStateFromRequest($this->context . '.fromdate', 'fromdate');
        $this->setState('filter.fromdate', $fromdate);

        $todate = $app->getUserStateFromRequest($this->context . '.todate', 'todate');
        $this->setState('filter.todate', $todate);

        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_logregister');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {

        $input = JFactory::getApplication()->input;
        $package  = $input->get('package');

        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('a.*')
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->order($db->quoteName('a.id') . ' DESC');
        if ($package != NULL)
        {
            $query->where('package = "'.$package.'"');
        }
        $fromdate = $this->getState('filter.fromdate');
        $todate   = $this->getState('filter.todate');
        if ($fromdate != null && $todate != null)
        {
            $query->where ("date BETWEEN '".$fromdate."' and '".$todate."'");
        }

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        for ($i = 0; $i < count($items); $i++)
        {
            // Initialiase variables.
            $db    = JFactory::getDbo();

            // $db    = $this->getDbo();
            $query = $db->getQuery(true);

            // Create the base select statement.
            $query->select('name')
                ->from($db->quoteName('#__users'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($items[$i]->row));
            // Set the query and load the result.
            $db->setQuery($query);
            $result = $db->loadObject();
            $items[$i]->user_name = $result->name;

            // Check for a database error.
            if ($db->getErrorNum())
            {
                JError::raiseWarning(500, $db->getErrorMsg());

                return null;
            }
        }

        return $items;
    }

    public function getChart()
    {
        // Initialiase variables.
        $db         = JFactory::getDbo();
        $chartArray = array();
        // $db    = $this->getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select("count(id)")
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->where($db->quoteName('a.action') . ' = ' . $db->quote('logout'));

        // Fetch record for add action.
        $db->setQuery($query);
        $logout = $db->loadResult();

        $chartArray['logout'] = $logout;

        $query = $db->getQuery(true);

        $query->select("count(id)")
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->where($db->quoteName('a.action') . ' = ' . $db->quote('add'));

        $db->setQuery($query);
        $add = $db->loadResult();

        $chartArray['add'] = $add;


        // Fetch record for delete
        $db->setQuery($query);
        $logout = $db->loadResult();

        $chartArray['logout'] = $logout;

        $query = $db->getQuery(true);

        $query->select("count(id)")
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->where($db->quoteName('a.action') . ' = ' . $db->quote('delete'));

        $db->setQuery($query);
        $delete = $db->loadResult();

        $chartArray['delete'] = $delete;

        // Fetch record for edit
        $db->setQuery($query);
        $logout = $db->loadResult();

        $query = $db->getQuery(true);

        $query->select("count(id)")
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->where($db->quoteName('a.action') . ' = ' . $db->quote('edit'));

        $db->setQuery($query);
        $edit = $db->loadResult();

        $chartArray['edit'] = $edit;

             // Fetch record for login
        $db->setQuery($query);
        $logout = $db->loadResult();

        $chartArray['logout'] = $logout;

        $query = $db->getQuery(true);

        $query->select("count(id)")
            ->from($db->quoteName('#__activities_activities') . ' AS a')
            ->where($db->quoteName('a.action') . ' = ' . $db->quote('login'));

        $db->setQuery($query);
        $login = $db->loadResult();

        $chartArray['login'] = $login;

        return $chartArray;
    }



}
