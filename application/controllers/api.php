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

class Api extends CI_Controller {
    
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * REST End Point : Display the list of the rooms (whatever the location)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getRooms() {
        //Check if input parameters are set
        if ($this->input->get_post('login') && $this->input->get_post('password')) {
            //Check user credentials
            $login = $this->input->get_post('login');
            $password = $this->input->get_post('password');
            $this->load->model('users_model');
            $user_id = $this->users_model->check_authentication($login, $password);
            if ($user_id != -1) {
                $this->load->model('rooms_model');
                $this->expires_now();
                header("Content-Type: application/json");
                $rooms = $this->rooms_model->get_all_rooms();
                echo json_encode($rooms);
            } else {    //Wrong inputs
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        } else {    //Unauthorized
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        }
    }

    /**
     * REST End Point : Get the status of a meeting room (i.e. is free or not) 
     * and the next time the status changes (or NULL if it will never change)
     * @param int $id identifier of the meeting room
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getRoomStatus($id) {
        //Test if we access this page from a REST client or from a browser
        $this->load->library('user_agent');
        if ($this->agent->is_browser() || $this->agent->is_mobile())
        {
            $agent = $this->agent->browser().' '.$this->agent->version();
            redirect('locations');
        }
        else
        {        
            //Check if input parameters are set
            if ($this->input->get_post('login') && $this->input->get_post('password')) {
                //Check user credentials
                $login = $this->input->get_post('login');
                $password = $this->input->get_post('password');
                $this->load->model('users_model');
                $user_id = $this->users_model->check_authentication($login, $password);
                if ($user_id != -1) {
                    $this->load->model('timeslots_model');
                    $this->expires_now();
                    header("Content-Type: application/json");
                    $object = new StdClass;
                    $enddate = $this->timeslots_model->end_timeslot($id);
                    if (is_null($enddate)) {
                        $object->IsFree = TRUE;   //No current timeslot
                        $object->NextState = $this->timeslots_model->next_timeslot($id);
                    } else {
                        $object->IsFree = FALSE;   //Return the end of current timeslot
                        $object->NextState = $enddate;
                    }
                    echo json_encode($object);
                } else {    //Wrong inputs
                    $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
                }
            } else {    //Unauthorized
                $this->output->set_header("HTTP/1.1 403 Forbidden");
            }
        }
    }
    
    /**
     * REST End Point : Get the list of timeslots for a given room
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function getTimeslotsByRoomId($id) {
        //Check if input parameters are set
        if ($this->input->get_post('login') && $this->input->get_post('password')) {
            //Check user credentials
            $login = $this->input->get_post('login');
            $password = $this->input->get_post('password');
            $this->load->model('users_model');
            $user_id = $this->users_model->check_authentication($login, $password);
            if ($user_id != -1) {
                $this->load->model('timeslots_model');
                $this->expires_now();
                header("Content-Type: application/json");
                $timeslots = $this->timeslots_model->get_timeslots($id);
                echo json_encode($timeslots);
            } else {    //Wrong inputs
                $this->output->set_header("HTTP/1.1 422 Unprocessable entity");
            }
        } else {    //Unauthorized
            $this->output->set_header("HTTP/1.1 403 Forbidden");
        }
    }
    
    /**
     * Internal utility function
     * make sure a resource is reloaded every time
     */
    private function expires_now() {
        // Date in the past
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // always modified
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // HTTP/1.1
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        // HTTP/1.0
        header("Pragma: no-cache");
    }
}
