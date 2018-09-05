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
         //   print_r($_SESSION);
           if(is_null($this->session->userdata('parent_id'))){
             $this->load->view('login');
           }else{
               redirect("Login/dashboard","refresh");
            //    $this->load->view('home');
           }
	   
	}
        
        public function dashboard(){
           $this->load->view('dashboard'); 
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
                    $response['redirect_url'] = base_url()."index.php/login";
                }

                //Replying ajax request with validation response
                echo json_encode($response);
        
               // echo $login_status;
           }
        }
        
        function logout() {
         //   $this->load->model('logs');
            $this->session->sess_destroy();
            $this->session->set_flashdata('logout_notification', 'logged_out');
            redirect('Login', 'refresh');
        }
        
        
        
}
