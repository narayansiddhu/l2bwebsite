<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acessdenied extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->helper('url');
            $this->operations->is_login();
            $this->load->model("validations");
            
            /* cache control */
        }
        
        public function index(){
            if(strlen($this->session->userdata("blocked_module"))==0){
                redirect("Dashboard/","refresh"); 
            }
            $data['module']=$this->session->userdata("blocked_module");
           // $this->session->unset_userdata("blocked_module");
            $this->load->view("blocked_modules",$data);
        }
        
}
?>