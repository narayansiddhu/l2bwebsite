<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            /* cache control */
        }
        
        public function index()
        {
               if(is_null($this->session->userdata('staff_id'))){
                //   print_r($_SESSION);
                   $this->load->view('login');
               }else{
                   redirect("Login/home",'refresh');
               }

        }
        
        public function home(){
            if(is_null($this->session->userdata('staff_id'))){
                 redirect("Login/",'refresh'); 
              }else{
                    redirect("Dashboard/",'refresh'); 
              }
        }

        public function reg(){
            $this->load->view('client/registration');
        }
        
        public function login_check(){
           if((isset($_POST['username']) && isset($_POST['password'])  )){
                $username=$_POST['username'];
                $password=$_POST['password'];
                $login_status = $this->operations->validate_user($username, $password);
                if($login_status){
                   $response['login_status'] = "Success ";
                }else{
                      $response['login_status'] = "invalid";
                }
                if ($login_status == 'Success') {
                    $response['redirect_url'] = base_url()."/index.php/login";
                }
                //Replying ajax request with validation response
                echo json_encode($response);
           }else{
               redirect("Login/",'refresh');
           }
        }
        
        public function logout() {
            if(!is_null($this->session->userdata('staff_id'))){
            
           
            $this->load->model('logs');
            $this->logs->insert_staff_log(2,'Logged Out ');
            $this->session->sess_destroy();
            $this->session->set_flashdata('logout_notification', 'logged_out');
            }
            header("Location:../");
           // redirect(base_url(), 'refresh');
        }
        
        
        
}



