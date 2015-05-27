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

jimport('joomla.application.component.controllerform');

/**
 * Logregister controller class.
 */
class LogregisterControllerLogregister extends JControllerForm
{

    function __construct() {
        $this->view_list = 'logregisters';
        parent::__construct();
    }

}