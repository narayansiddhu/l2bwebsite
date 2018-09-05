<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class logs extends CI_Controller {
	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('library_oper');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->operations->is_login();$this->operations->is_login();
            /* cache control */
        }
        
        function index(){
            $this->load->view("logs/view");
        }
        
       public function view(){
           $this->load->view("logs/view");
        }
        
        
}

?>