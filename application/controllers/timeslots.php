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

class Leaves extends CI_Controller {
    
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
        $this->fullname = $this->session->userdata('firstname') . ' ' .
                $this->session->userdata('lastname');
        $this->is_hr = $this->session->userdata('is_hr');
        $this->load->model('leaves_model');
        $this->user_id = $this->session->userdata('id');
        $this->language = $this->session->userdata('language');
        $this->language_code = $this->session->userdata('language_code');
        $this->load->helper('language');
        $this->lang->load('leaves', $this->language);
    }
    
    /**
     * Prepare an array containing information about the current user
     * @return array data to be passed to the view
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    private function getUserContext()
    {
        $data['fullname'] = $this->fullname;
        $data['is_hr'] = $this->is_hr;
        $data['user_id'] =  $this->user_id;
        $data['language'] = $this->language;
        $data['language_code'] =  $this->language_code;
        return $data;
    }

    /**
     * Display the list of the leave requests of the connected user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->auth->check_is_granted('list_leaves');
        $this->expires_now();
        $data = $this->getUserContext();
        $data['leaves'] = $this->leaves_model->get_user_leaves($this->session->userdata('id'));

        $data['title'] = lang('leaves_index_title');
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('leaves/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Action: export the list of all leaves into an Excel file
     */
    public function export() {
        $this->expires_now();
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);

        $this->excel->getActiveSheet()->setTitle(lang('leaves_export_title'));
        $this->excel->getActiveSheet()->setCellValue('A1', lang('leaves_export_thead_id'));
        $this->excel->getActiveSheet()->setCellValue('B1', lang('leaves_export_thead_start_date'));
        $this->excel->getActiveSheet()->setCellValue('C1', lang('leaves_export_thead_start_date_type'));
        $this->excel->getActiveSheet()->setCellValue('D1', lang('leaves_export_thead_end_date'));
        $this->excel->getActiveSheet()->setCellValue('E1', lang('leaves_export_thead_end_date_type'));
        $this->excel->getActiveSheet()->setCellValue('F1', lang('leaves_export_thead_cause'));
        $this->excel->getActiveSheet()->setCellValue('G1', lang('leaves_export_thead_duration'));
        $this->excel->getActiveSheet()->setCellValue('H1', lang('leaves_export_thead_type'));
        $this->excel->getActiveSheet()->setCellValue('I1', lang('leaves_export_thead_status'));
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $leaves = $this->leaves_model->get_user_leaves($this->user_id);
        $this->load->model('status_model');
        $this->load->model('types_model');
        
        $line = 2;
        foreach ($leaves as $leave) {
            $this->excel->getActiveSheet()->setCellValue('A' . $line, $leave['id']);
            $this->excel->getActiveSheet()->setCellValue('B' . $line, $leave['startdate']);
            $this->excel->getActiveSheet()->setCellValue('C' . $line, $leave['startdatetype']);
            $this->excel->getActiveSheet()->setCellValue('D' . $line, $leave['enddate']);
            $this->excel->getActiveSheet()->setCellValue('E' . $line, $leave['enddatetype']);
            $this->excel->getActiveSheet()->setCellValue('F' . $line, $leave['duration']);
            $this->excel->getActiveSheet()->setCellValue('G' . $line, $this->types_model->get_label($leave['type']));
            $this->excel->getActiveSheet()->setCellValue('H' . $line, $leave['cause']);
            $this->excel->getActiveSheet()->setCellValue('I' . $line, $this->status_model->get_label($leave['status']));
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
     * Ajax endpoint : Send a list of fullcalendar events
     */
    public function workmates() {
        $this->expires_now();
        header("Content-Type: application/json");
        $start = $this->input->get('start', TRUE);
        $end = $this->input->get('end', TRUE);
        echo $this->leaves_model->workmates($this->session->userdata('manager'), $start, $end);
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
