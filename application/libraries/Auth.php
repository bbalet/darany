<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * This file is part of darany.
 *
 * darany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * darany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with darany. If not, see <http://www.gnu.org/licenses/>.
 */

class Auth {

    /**
     * Access to CI framework so as to use other libraries
     * @var type Code Igniter framework
     */
    private $CI;

    /**
     * Default constructor
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
    }

    /**
     * Check if the current user can perform a given action on the system.
     * Note that other business rules may be implemented in the controllers.
     * For instance, a user can approve a leave only if it is a manager of the submitter.
     * Or a user may delete a leave only if the leave is at the planned status
     * This function only prevents gross security issues when a user try to access 
     * a restricted screen.
     * Note that any operation needs the user to be connected.
     * @param string $operation Operation attempted by the user
     * @param int $id  optional object identifier of the operation (e.g. user id)
     * @return bool true if the user is granted, false otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function is_granted($operation, $object_id = 0) {
        //log_message('debug', '{librairies/auth/is_granted} Entering method with Operation=' . $operation . ' / object_id=' . $object_id);
        switch ($operation) {
            
            //User management
            case 'list_users' :
            case 'create_user' :
            case 'delete_user' :
            case 'view_user' :
            case 'edit_user' :
            case 'update_user' :
            case 'import_user' :
            case 'export_user' :
                if ($this->CI->session->userdata('is_admin') == true)
                    return true;
                else
                    return false;
                break;

            //Password management
            case 'change_password' :
                if ($this->CI->session->userdata('is_admin') == true)
                    return true;
                else {//a user can change its own password
                    if ($this->CI->session->userdata('id') == $object_id)
                        return true;
                    else
                        return false;
                }
                break;
            
            //locations
            case 'locations_delete' :
            case 'locations_list' :
            case 'locations_export' :
            case 'locations_create' :
            case 'locations_edit' :

            //rooms
            case 'rooms_delete' :
            case 'rooms_list' :
            case 'rooms_export' :
            case 'rooms_create' :
            case 'rooms_edit' :

            //rooms
            case 'timeslots_delete' :
            case 'timeslots_list' :
            case 'timeslots_export' :
            case 'timeslots_create' :
            case 'timeslots_edit' :
            case 'timeslots_accept' :
            case 'timeslots_reject' :
return true;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Check if the current user can perform a given action on the system.
     * @use is_granted
     * @param string $operation Operation attempted by the user
     * @param int $id  optional object identifier of the operation (e.g. user id)
     * @return bool true if the user is granted, false otherwise
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function check_is_granted($operation, $object_id = 0) {
        if (!$this->is_granted($operation, $object_id)) {
            $this->CI->load->helper('url');
            $this->CI->load->helper('language');
            $this->CI->lang->load('global', $this->CI->session->userdata('language'));
            log_message('error', 'User #' . $this->CI->session->userdata('id') . ' illegally tried to access to ' . $operation);
            $this->CI->session->set_flashdata('msg', sprintf(lang('global_msg_error_forbidden'), $operation));
            redirect('forbidden');
        }
        else {
            //log_message('debug', '{libraries/auth/check_is_granted} User #' . $this->CI->session->userdata('id') . ' granted access to ' . $operation);
        }
    }

}
