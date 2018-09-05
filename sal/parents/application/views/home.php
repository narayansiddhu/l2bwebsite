<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$arr=$this->session->userdata('students_arr');

$query=$this->db->query("SELECT * FROM `institutes` WHERE id='".$this->session->userdata("parent_org_id")."' "); 
$query=$query->row();
?>


<?php
$this->load->view('structure/footer');
?>