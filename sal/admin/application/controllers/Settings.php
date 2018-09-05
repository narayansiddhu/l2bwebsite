<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class settings extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('validations');
            $this->load->model('logs');
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
            }elseif(!$this->validations->validate_mobile($post[$field])){
               $this->form->setError($field,'* Enter Valid Mobile number ');
            }
           
             //sphone
            $field = 'sgender'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide gender ');
            }
            
            $field = 'sbloodg'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide Blood Group ');
            }
            //sdob
            $field = 'sdob'; 
            if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Date Of Birth');
                }else{
                    $str=explode("/",$post[$field]);
                   $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($dob >time()){
                        $this->form->setError($field,'* Please select a valid Date Of Birth');
                    }
                }
            
            
            
            //squalification
            $field ='squalification';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please provide Qualification');
            }
            $image=0;
            if(strlen($_FILES['stdimage']['name'])!=0 ){
                $image=1;
                $field ='stdimage';
                $ext=explode(".",$_FILES['stdimage']['name']);
                $ext=$ext[sizeof($ext)-1 ];
                $img_arr=array('jpg','jpeg','gif','png');
               
                if(!in_array($ext,$img_arr)){
                    $this->form->setError($field,'* Please Select a Image file');
                 }
            }
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
                }else{
                   $file="";
                   $data=array(
                        'name' =>$post['sname'],
                        'phone' =>$post['sphone'],
                        'qualification'=>$post['squalification'],
                        'dob' =>$post['sdob'],
                        'sex' =>$post['sgender'],
                        'dob' =>$post['sdob'],
                    );
                    if($image==1){
                       $ext=explode(".",$_FILES['stdimage']['name']);
                       $ext=$ext[sizeof($ext)-1 ];
                       $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."_".time().".".$ext;
                       $config['upload_path']   = 'C:/wamp/www/school/assests_2/uploads/';
                       //$config['upload_path']   = 'var/www/html/school/assests_2/uploads/'; 
                       $config['allowed_types'] = 'gif|jpg|png'; 
                      
                       $this->load->library('upload', $config);
                       $this->upload->do_upload('stdimage');
                       $data['img'] =$file;
                    }
                    $this->db->where('id',$this->session->userdata('staff_id'));
                    $this->db->update('staff',$data);
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
                $old=  $this->db->query("SELECT password FROM `staff` WHERE id='".$this->session->userdata('staff_id')."' ");
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
                $this->db->where('id',$this->session->userdata('staff_id'));
                $this->db->update('staff',$data);
                 $this->session->set_userdata('user_settings_update', 'Updated Password..'); 
            }
            
           redirect('/settings','refresh');
           
        }
        
        public function edit_homepage(){
            $this->load->view("homepage/textbox.php");
        }
        
        
        public function save_homepage(){
          $post =$_POST;
          
          $field = 'editor1';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Design Box ');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{
                if(isset($post['hid'])){
                    $data=array(
                       'text'  =>$post['editor1']
                       );
                    $this->db->where('position',$post['position']);
                    $this->db->where('id',$post['hid']);
                    $this->db->update('home_page',$data);
                }else{
                    $data = array(
                        'iid' => $this->session->userdata("staff_Org_id"),
                        'position' =>$post['position'],
                        'text'  =>$post['editor1']
                    );
                    $this->db->insert('home_page',$data);
                }
               
                 $this->session->set_userdata('home_page_update', 'Succesfully Updated Home Psage.. '); 
            }
            
           redirect('/settings/edit_homepage','refresh');
           
          
        }
}
?>