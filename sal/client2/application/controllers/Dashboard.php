<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->model('logs');
            $this->load->model('barcode');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
        }
        
        public function index(){
            // print_r($_SESSION);
            // echo $this->session->userdata("staff_level");


            switch($this->session->userdata("staff_level")){
                case 8 :  $this->load->view("d2"); break;
                case 7 :  $this->load->view("d2"); break;
                case 6 :  $this->load->view("d2"); break;
                case 5 :  $this->load->view("library_dashboard"); break;
                case 4 :  redirect("Hostel/","refresh"); break;
                case 3  : $this->load->view("office-dashboard");break;
                case 2  : redirect("Transport/","refresh");break;
                case 1  : $this->load->view("faculty-dashboard");break;
            }
            
        }
        
        public function d1(){
             $this->load->view("d1");
        }
        public function notes(){
             $this->load->view("sticky_notes");
        }
        
	    public function library(){
            $this->load->view("library_dashboard");
        }
        
        
        
        
}


?>
