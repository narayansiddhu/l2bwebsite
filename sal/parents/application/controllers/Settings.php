<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class settings extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
           
           
            $this->load->model('form');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
        }
        
        public function index(){
            $this->load->view("settings");
        }
        
        public function update_profile(){
            
            $post=$this->operations->cleanInput($_POST);
            
            $field = 'sname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide  name');
            }
            
            //sphone
           $field = 'sphone'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide Phone Number ');
            }elseif(!$this->operations->validate_mobile($post[$field])){
               $this->form->setError($field,'* Enter Valid Mobile number ');
            }
                       
            //squalification
            $field ='sprofession';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide profession');
            }
            
            $field ='address';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide Address');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
                }else{
                   $file="";
                   $data=array(
                        'name' =>$post['sname'],
                        'phone' =>$post['sphone'],
                        'profession'=>$post['sprofession'],
                        'address'=>$post['address'],
                    );
                    
                    $this->db->where('parent_id',$this->session->userdata('parent_id'));
                    $this->db->update('parent',$data);
                     $this->session->set_userdata('user_settings_update', 'Sucessfully Updated Profile'); 
                 
            }
        
            redirect('/settings','refresh');
            
        }
        
        
        public function update_password(){
           
           
           $post=$this->operations->cleanInput($_POST);
            
            $field = 'oldpass';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide  Oldpassword');
            }else{
                $old=  $this->db->query("SELECT password FROM `parent` WHERE parent_id='".$this->session->userdata('parent_id')."' ");
                $old=$old->row();
                $old=$old->password;
                if($old!=$post[$field]){
                    $this->form->setError($field,'* Invalid Old Password');
                }
            }
            
            $field = 'newpass';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide  newpassword');
            }elseif( (strlen($post[$field]) <1) ||(strlen($post[$field]) >8) ){
                $this->form->setError($field,'* Password Must be of 8 charcaters');
            }
            
            $field = 'cnfrmpass';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide  cnfrm password');
            }elseif( (strlen($post[$field]) <1) ||(strlen($post[$field]) >8) ){
                $this->form->setError($field,'* Password Must be of 8 charcaters');
            }elseif($post['cnfrmpass']!=$post['newpass']){
                $this->form->setError($field,'* Confirm password Mismatch');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{
               $data=array(
                        'password' =>$post['cnfrmpass']
                       );
                $this->db->where('parent_id',$this->session->userdata('parent_id'));
                $this->db->update('parent',$data);
                 $this->session->set_userdata('user_settings_update', 'Updated Password..'); 
            }
            
           redirect('/settings','refresh');
           
        }
        
        
}
?>