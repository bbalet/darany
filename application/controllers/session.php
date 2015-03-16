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

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Session extends CI_Controller {

    /**
     * Default constructor
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('polyglot');
        if ($this->session->userdata('language') == FALSE) {
            $this->session->set_userdata('language', $this->config->item('language'));
            $this->session->set_userdata('language_code', $this->polyglot->language2code($this->config->item('language')));
        }
        $this->load->helper('language');
        $this->lang->load('session', $this->session->userdata('language'));
    }

    /**
     * Login form
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function login() {
        $data['title'] = lang('session_login_title');
        $this->load->helper('form');
        $this->load->library('form_validation');
        //Note that we don't receive the password as a clear string
        $this->form_validation->set_rules('login', lang('session_login_field_login'), 'required');
        $this->form_validation->set_rules('password', lang('session_login_field_password'), 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['language'] = $this->session->userdata('language');
            $data['language_code'] = $this->session->userdata('language_code');
            $this->load->view('templates/header', $data);
            $this->load->view('session/login', $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->model('users_model');
            //Set language
            $this->session->set_userdata('language_code', $this->input->post('language'));
            $this->session->set_userdata('language', $this->polyglot->code2language($this->input->post('language')));
            $loggedin = $this->users_model->check_credentials($this->input->post('login'), $this->input->post('password'));
            
            if ($loggedin == FALSE) {
                log_message('error', '{controllers/session/login} Invalid login id or password for user=' . $this->input->post('login'));
                $this->session->set_flashdata('msg', lang('session_login_flash_bad_credentials'));
                $data['language'] = $this->session->userdata('language');
                $data['language_code'] = $this->session->userdata('language_code');
                $this->load->view('templates/header', $data);
                $this->load->view('session/login', $data);
                $this->load->view('templates/footer');
            } else {
                //If the user has a target page (e.g. link in an e-mail), redirect to this destination
                if ($this->session->userdata('last_page') != '') {
                    redirect($this->session->userdata('last_page'));
                } else {
                    redirect(base_url());
                }
            }
        }
    }

    /**
     * Logout the user and destroy the session data
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('session/login');
    }

    /**
     * Change the language and redirect to last page (i.e. page that submit the
     * language form)
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function language() {
        $this->load->helper('form');
        $this->session->set_userdata('language_code', $this->input->post('language'));
        $this->session->set_userdata('language', $this->polyglot->code2language($this->input->post('language')));
        redirect($this->input->post('last_page'));
    }
    
    /**
     * Send the password by e-mail to a user requesting it
     */
    public function forgetpassword() {
        $this->expires_now();
        $this->output->set_content_type('text/plain');
        $login = $this->input->post('login');
        $this->load->model('users_model');
        $user = $this->users_model->getUserByLogin($login);
        if (is_null($user)) {
            echo "UNKNOWN";
        } else {
            //Send an email to the user with its login information
            $this->load->model('settings_model');
            $this->load->library('email');
            $this->lang->load('email', $this->language);
            
            //Generate random password and store its hash into db
            $password = $this->users_model->resetClearPassword($user->id);
            
            //Send an e-mail to the user requesting a new password
            $this->load->library('parser');
            $data = array(
                'Title' => lang('email_password_forgotten_title'),
                'BaseURL' => base_url(),
                'Firstname' => $user->firstname,
                'Lastname' => $user->lastname,
                'Login' => $user->login,
                'Password' => $password
            );
            $message = $this->parser->parse('emails/' . $user->language . '/password_forgotten', $data, TRUE);
            if ($this->email->mailer_engine== 'phpmailer') {
                $this->email->phpmailer->Encoding = 'quoted-printable';
            }

            if ($this->config->item('from_mail') != FALSE && $this->config->item('from_name') != FALSE ) {
                $this->email->from($this->config->item('from_mail'), $this->config->item('from_name'));
            } else {
               $this->email->from('do.not@reply.me', 'LMS');
            }
            $this->email->to($user->email);
            $this->email->subject(lang('email_password_forgotten_subject'));
            $this->email->message($message);
            $this->email->send();
            echo "OK";
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
