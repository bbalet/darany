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

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hr extends CI_Controller {
    
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
        $this->load->model('leaves_model');
        $this->fullname = $this->session->userdata('firstname') . ' ' .
                $this->session->userdata('lastname');
        $this->is_admin = $this->session->userdata('is_admin');
        $this->is_hr = $this->session->userdata('is_hr');
        $this->user_id = $this->session->userdata('id');
        $this->language = $this->session->userdata('language');
        $this->language_code = $this->session->userdata('language_code');
        $this->load->helper('language');
        $this->lang->load('hr', $this->language);
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
     * Display the list of all requests submitted to you
     * Status is submitted
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index($filter = 'requested') {
        $this->auth->check_is_granted('list_requests');
        $this->expires_now();
        if ($filter == 'all') {
            $showAll = true;
        } else {
            $showAll = false;
        }
        $data = $this->getUserContext();
        $data['filter'] = $filter;
        $data['title'] = lang('hr_leaves_title');
        $data['requests'] = $this->leaves_model->requests($this->user_id, $showAll);
        $this->load->model('types_model');
        for ($i = 0; $i < count($data['requests']); ++$i) {
            $data['requests'][$i]['type_label'] = $this->types_model->get_label($data['requests'][$i]['type']);
        }
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/index', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the list of all employees
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function employees() {
        $this->auth->check_is_granted('list_employees');
        $data = $this->getUserContext();
        $data['title'] = lang('hr_employees_title');
        $this->load->model('users_model');
        $data['users'] = $this->users_model->get_employees();
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/employees', $data);
        $this->load->view('templates/footer');
    }    

    /**
     * Display the list of leaves for a given employee
     * @param int $id employee id
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function leaves($id) {
        $this->auth->check_is_granted('list_employees');
        $data = $this->getUserContext();
        $data['title'] = lang('hr_leaves_title');
        $data['user_id'] = $id;
        $this->load->model('leaves_model');
        $data['leaves'] = $this->leaves_model->get_employee_leaves($id);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/leaves', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Display the details of leaves taken/entitled for a given employee
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function counters($id) {
        $this->auth->check_is_granted('list_employees');
        $data = $this->getUserContext();
        $data['summary'] = $this->leaves_model->get_user_leaves_summary($id);
        $this->load->model('types_model');
        $data['types'] = $this->types_model->get_types();
        $this->load->model('users_model');
        $data['employee_name'] = $this->users_model->get_label($id);
        $user = $this->users_model->get_users($id);
        $data['employee_id'] = $id;
        
        if (!is_null($data['summary'])) {
            $this->expires_now();
            $data['title'] = lang('hr_summary_title');
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('hr/counters', $data);
            $this->load->view('templates/footer');
        } else {
            $this->session->set_flashdata('msg', lang('hr_summary_flash_msg_error'));
            redirect('hr/employees');
        }
    }

    /**
     * Get/Set Yearly periods
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function yearlyperiod() {
        //$this->auth->check_is_granted('yearly_period');
        $data = $this->getUserContext();
        $this->expires_now();
        $data['title'] = lang('hr_summary_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('hr/yearlyperiod', $data);
        $this->load->view('templates/footer');
    }
    
    /**
     * Action: export the list of all leaves into an Excel file
     * @param int $id employee id
     */
    public function export_leaves($id) {
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle(lang('hr_export_leaves_title'));
        $this->excel->getActiveSheet()->setCellValue('A3', lang('hr_export_leaves_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B3', lang('hr_export_leaves_thead_status'));
        $this->excel->getActiveSheet()->setCellValue('C3', lang('hr_export_leaves_thead_start'));
        $this->excel->getActiveSheet()->setCellValue('D3', lang('hr_export_leaves_thead_end'));
        $this->excel->getActiveSheet()->setCellValue('E3', lang('hr_export_leaves_thead_duration'));
        $this->excel->getActiveSheet()->setCellValue('F3', lang('hr_export_leaves_thead_type'));
        
        $this->excel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->load->model('leaves_model');
        $leaves = $this->leaves_model->get_employee_leaves($id);
        $this->load->model('users_model');
        $fullname = $this->users_model->get_label($id);
        $this->excel->getActiveSheet()->setCellValue('A1', $fullname);
        
        $line = 4;
        foreach ($leaves as $leave) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $leave['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $leave['status']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $leave['startdate']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $leave['enddate']);
            $this->excel->getActiveSheet()->setCellValue('E' . $line, $leave['duration']);
            $this->excel->getActiveSheet()->setCellValue('F' . $line, $leave['type']);
            $line++;
        }

        $filename = 'leaves.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * Action: export the list of all overtime requests into an Excel file
     * @param int $id employee id
     */
    public function export_overtime($id) {
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle(lang('hr_export_overtime_title'));
        $this->excel->getActiveSheet()->setCellValue('A3', lang('hr_export_overtime_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B3', lang('hr_export_overtime_thead_status'));
        $this->excel->getActiveSheet()->setCellValue('C3', lang('hr_export_overtime_thead_date'));
        $this->excel->getActiveSheet()->setCellValue('D3', lang('hr_export_overtime_thead_duration'));
        $this->excel->getActiveSheet()->setCellValue('E3', lang('hr_export_overtime_thead_cause'));
        $this->excel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->load->model('overtime_model');
        $requests = $this->overtime_model->get_employee_extras($id);
        $this->load->model('users_model');
        $fullname = $this->users_model->get_label($id);
        $this->excel->getActiveSheet()->setCellValue('A1', $fullname);
        
        $line = 4;
        foreach ($requests as $request) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $request['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $request['status']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $request['date']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $request['duration']);
            $this->excel->getActiveSheet()->setCellValue('E' . $line, $request['cause']);
            $line++;
        }

        $filename = 'overtime.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    /**
     * Action: export the list of all employees into an Excel file
     * @param int $id optional id of the entity, all entities if 0
     * @param bool $children true : include sub entities, false otherwise
     */
    public function export_employees() {
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle(lang('hr_export_employees_title'));
        $this->excel->getActiveSheet()->setCellValue('A1', lang('hr_export_employees_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B1', lang('hr_export_employees_thead_firstname'));
        $this->excel->getActiveSheet()->setCellValue('C1', lang('hr_export_employees_thead_lastname'));
        $this->excel->getActiveSheet()->setCellValue('D1', lang('hr_export_employees_thead_email'));
        $this->excel->getActiveSheet()->setCellValue('E1', lang('hr_export_employees_thead_manager'));
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $this->load->model('users_model');
        $employees = $this->users_model->get_employees();
        
        $line = 2;
        foreach ($employees as $employee) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $employee['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $employee['firstname']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $employee['lastname']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $employee['email']);
            $this->excel->getActiveSheet()->setCellValue('E' . $line, $employee['manager_firstname'] . ' ' . $employee['manager_lastname']);
            $line++;
        }

        $filename = 'employees.xls';
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