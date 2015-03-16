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

class Locations extends CI_Controller {
    
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
        $this->load->model('locations_model');
        $this->fullname = $this->session->userdata('firstname') . ' ' .
                $this->session->userdata('lastname');
        $this->is_admin = $this->session->userdata('is_admin');
        $this->user_id = $this->session->userdata('id');
        $this->language = $this->session->userdata('language');
        $this->language_code = $this->session->userdata('language_code');
        $this->load->helper('language');
        $this->lang->load('locations', $this->language);
        $this->lang->load('global', $this->language);
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
        $data['user_id'] =  $this->user_id;
        $data['language'] = $this->language;
        $data['language_code'] =  $this->language_code;
        return $data;
    }

    /**
     * Display list of leave types
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->auth->check_is_granted('locations_list');
        $data = $this->getUserContext();
        $data['locations'] = $this->locations_model->get_locations();
        $data['title'] = lang('locations_index_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('locations/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display a form that allows adding a location
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        $this->auth->check_is_granted('locations_create');
        $data = $this->getUserContext();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('hr_leaves_popup_create_title');
        
        $this->form_validation->set_rules('name', lang('locations_create_field_name'), 'required|xss_clean');
        $this->form_validation->set_rules('description', lang('locations_create_field_description'), 'xss_clean');
        $this->form_validation->set_rules('address', lang('locations_create_field_address'), 'xss_clean');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('locations/create', $data);
            $this->load->view('templates/footer');
        } else {
            $this->locations_model->set_locations();
            $this->session->set_flashdata('msg', lang('locations_create_flash_msg'));
            redirect('locations');
        }
    }

    /**
     * Display a form that allows editing a location
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function edit($id) {
        $this->auth->check_is_granted('locations_edit');
        $data = $this->getUserContext();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = lang('hr_leaves_popup_update_title');
        
        $this->form_validation->set_rules('name', lang('locations_edit_field_name'), 'required|xss_clean');
        $this->form_validation->set_rules('description', lang('locations_edit_field_description'), 'xss_clean');
        $this->form_validation->set_rules('address', lang('locations_edit_field_address'), 'xss_clean'); 
        
        if ($this->form_validation->run() === FALSE) {
            $data['location'] = $this->locations_model->get_locations($id);
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('locations/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->locations_model->update_locations();
            $this->session->set_flashdata('msg', lang('locations_edit_flash_msg'));
            redirect('locations');
        }
    }
    
    /**
     * Action : delete a leave type
     * @param int $id leave type identifier
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function delete($id) {
        $this->auth->check_is_granted('leavetypes_delete');
        if ($id != 0) {
            if ($this->types_model->usage($id) > 0) {
                $this->session->set_flashdata('msg', lang('hr_leaves_popup_delete_flash_forbidden'));
            } else {
                $this->types_model->delete_type($id);
                $this->session->set_flashdata('msg', lang('hr_leaves_popup_delete_flash_msg'));
            }
        } else {
            $this->session->set_flashdata('msg', lang('hr_leaves_popup_delete_flash_error'));
        }
        redirect('leavetypes');
    }

    /**
     * Action: export the list of all leave types into an Excel file
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function export() {
        $this->auth->check_is_granted('locations_export');
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle(lang('locations_export_title'));
        $this->excel->getActiveSheet()->setCellValue('A1', lang('locations_export_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B1', lang('locations_export_thead_name'));
        $this->excel->getActiveSheet()->setCellValue('C1', lang('locations_export_thead_description'));
        $this->excel->getActiveSheet()->setCellValue('D1', lang('locations_export_thead_address'));
        $this->excel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $types = $this->locations_model->get_locations();
        $line = 2;
        foreach ($types as $type) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $type['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $type['name']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $type['description']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $type['address']);
            $line++;
        }
        
        $filename = 'locations.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
}
