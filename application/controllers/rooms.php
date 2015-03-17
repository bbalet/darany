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

class Rooms extends CI_Controller {
    
    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        //Check if user is connected
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata('last_page', current_url());
            redirect('session/login');
        }
        $this->load->model('rooms_model');
        $this->fullname = $this->session->userdata('firstname') . ' ' .
                $this->session->userdata('lastname');
        $this->is_admin = $this->session->userdata('is_admin');
        $this->is_hr = $this->session->userdata('is_hr');
        $this->user_id = $this->session->userdata('id');
        $this->language = $this->session->userdata('language');
        $this->language_code = $this->session->userdata('language_code');
        $this->load->helper('language');
        $this->lang->load('rooms', $this->language);
    }
    
    /**
     * Prepare an array containing information about the current user
     * @return array data to be passed to the view
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function getUserContext()
    {
        $data['fullname'] = $this->fullname;
        $data['is_admin'] = $this->is_admin;
        $data['is_hr'] = $this->is_hr;
        $data['user_id'] =  $this->user_id;
        $data['language'] = $this->language;
        $data['language_code'] =  $this->language_code;
        return $data;
    }

    /**
     * Display list of rooms for a given location
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index($location) {
        $this->auth->check_is_granted('rooms_list');
        $data = $this->getUserContext();
        $this->load->model('locations_model');
        $data['location'] = $this->locations_model->get_locations($location);
        $data['rooms'] = $this->rooms_model->get_rooms($location, TRUE);
        $data['title'] = lang('rooms_index_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('rooms/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display a qrcode containing the URL that allow to check the status of a room
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function qrcode($room) {
        $this->auth->check_is_granted('rooms_list');
        $data = $this->getUserContext();
        include APPPATH . "/third_party/phpqrcode/qrlib.php";
        $this->expires_now();
        QRcode::png(base_url() . 'api/rooms/' . $room . '/status');
    }
    
    /**
     * Show the status of a room (free or not and next date of change)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function status($room) {
        $this->auth->check_is_granted('rooms_list');
        $data = $this->getUserContext();
        $data['room'] = $this->rooms_model->get_room($room);
        $data['title'] = lang('rooms_index_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('rooms/status', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Show the status of a room (free or not and next date of change)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function calendar($room) {
        //$this->auth->check_is_granted('rooms_list');
        $data = $this->getUserContext();
        $data['room'] = $this->rooms_model->get_room($room);
        $this->lang->load('calendar', $this->language);
        $data['title'] = lang('calendar_room_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('rooms/calendar', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Ajax endpoint : Send a list of fullcalendar events
     * @param int $room Identifier of the meeting room
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function calfeed($room) {
        $this->expires_now();
        header("Content-Type: application/json");
        $start = $this->input->get('start', TRUE);
        $end = $this->input->get('end', TRUE);
        $this->load->model('timeslots_model');
        echo $this->timeslots_model->events($room, $start, $end);
    }
    
    /**
     * Display a form that allows adding a meeting room to a location
     * @param int $location Identifier of the location
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create($location) {
        $this->auth->check_is_granted('rooms_create');
        $data = $this->getUserContext();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('rooms_create_title');
        $data['location'] = $location;

        $this->form_validation->set_rules('name', lang('rooms_create_field_name'), 'required|xss_clean');
        $this->form_validation->set_rules('manager', lang('rooms_create_field_manager'), 'required|xss_clean');
        $this->form_validation->set_rules('floor', lang('rooms_create_field_floor'), 'xss_clean');
        $this->form_validation->set_rules('description', lang('rooms_create_field_description'), 'xss_clean');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('rooms/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->rooms_model->set_rooms($location);
            $this->session->set_flashdata('msg', lang('rooms_create_flash_msg'));
            redirect('locations/' . $location . '/rooms');
        }
    }

    /**
     * Display a form that allows editing a leave type
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function edit($id) {
        $this->auth->check_is_granted('rooms_edit');
        $data = $this->getUserContext();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('positions_edit_title');
        $data['position'] = $this->positions_model->get_positions($id);
        
        $this->form_validation->set_rules('name', lang('positions_edit_field_name'), 'required|xss_clean');
        $this->form_validation->set_rules('description', lang('positions_edit_field_description'), 'xss_clean');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('positions/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->positions_model->update_positions($id);
            $this->session->set_flashdata('msg', lang('positions_edit_flash_msg'));
            redirect('locations/' . $location . '/rooms');
        }
    }
    
    /**
     * Action : delete a room
     * @param int $id room identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($location, $room) {
        $this->auth->check_is_granted('rooms_delete');
        $this->rooms_model->delete_room($room);
        $this->session->set_flashdata('msg', lang('positions_delete_flash_msg'));
        redirect('locations/' . $location . '/rooms');
    }

    /**
     * Action: export the list of rooms attached to a given location into an Excel file
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function export($location) {
        $this->auth->check_is_granted('rooms_export');
        $this->expires_now();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle(lang('rooms_export_title'));
        $this->excel->getActiveSheet()->setCellValue('A1', lang('rooms_export_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B1', lang('rooms_export_thead_name'));
        $this->excel->getActiveSheet()->setCellValue('C1', lang('rooms_export_thead_manager'));
        $this->excel->getActiveSheet()->setCellValue('D1', lang('rooms_export_thead_floor'));
        $this->excel->getActiveSheet()->setCellValue('E1', lang('rooms_export_thead_description'));
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $rooms = $this->rooms_model->get_rooms($location);
        $line = 2;
        foreach ($rooms as $room) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $room['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $room['name']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $room['manager']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $room['floor']);
            $this->excel->getActiveSheet()->setCellValue('E' . $line, $room['description']);
            $line++;
        }

        $filename = 'rooms.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
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
