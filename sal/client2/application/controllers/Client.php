<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('operations');
        $this->load->model('logs');
        $this->load->model('form');
        $this->load->library("pagination");
        /* cache control */
        $this->operations->is_login();
    }

    public function index() {
        echo 'hi';
       //redirect('client2/registration', 'refresh');
        $this->load->view('login');
    }
    public function get(){
        echo 'hllo';
    }
    public function registration(){
        $this->load->view('client2/registration');
    }
    public function login(){
        $this->load->view('registration');
    }
}