<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notices extends CI_Controller {

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
        
        function index(){
            // $this->load->view("noticeboard/index");
            $this->load->view("noticeboard/view_all");
        }
        
        function add_new(){
            $this->load->view("noticeboard/create");
        }
        
        function create(){
            
            $post=$_POST;
            //setError
            $field = 'title';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please Provide title');
            }
            
            $field = 'description';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please Provide description');
            }
            $not_c=0;
            $for="";
            for($i=1;$i<=3;$i++){
                if(strlen($this->input->post('notify_'.$i))!=0){
                 $not_c++;   
                 $for.=$i.",";
                }
            }
            $for = substr($for, 0, strlen($for)-1);
           if($not_c==0){
                $this->form->setError("notify",'*Please select Any Category.');
           }
         	
           $field = 'expiry';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide expiry Date');
                }else{
                    $str=explode("/",$post[$field]);
                   $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($dob <time()){
                        $this->form->setError($field,'* Please select a valid expiry Date ');
                    }
                }
         
         if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/Notices/add_new', 'refresh'); 
         }else{
                
             $time_nf=time();
             $for = array_filter(explode(',',$for));
             foreach($for as $val){
                 $data=array(
                     'iid' =>  $this->session->userdata('staff_Org_id'),
                     'title' => $post['title'],
                     'description' =>$post['description'],
                     'timestamp' => $time_nf,
                     'status' =>1,
                     'expiry' => $dob ,
                     'added_by' =>$this->session->userdata('staff_id'),
                     'for' => $val ,
                 );
                $this->db->insert('notice_board',$data);
             }  
            $this->session->set_userdata('notice_add_Sucess', 'Sucessfully Created New Notice '); 
            redirect('/Notices/', 'refresh'); 
         }
         
        }
        
       function change_status(){
           $data=array(
                     'status' =>$this->input->post('status'),
                 );
           $this->db->where('id',  $this->input->post('id'));    
           $this->db->update('notice_board',$data);
           $this->session->set_userdata('notice_add_Sucess', 'Sucessfully Changed Status of Notice '); 
           ?>
            <script>location.reload();</script>    
           <?php
       } 
       
       public function view_all(){           
           $this->load->view("noticeboard/view_all");
       }
       
       public function edit_post($id=0){
           if($id==0){
               redirect('/Notices/', 'refresh'); 
           }else{
               
           }
       }
       
       public function fetch_details($id){
           $q="";
       }
       
       public function staff_notices(){
           $data['result']= $this->fetch_notification(1);
           $this->load->view("noticeboard/view",$data);
       }    
       
       public function student_notices(){
           $data['result']= $this->fetch_notification(3);
           $this->load->view("noticeboard/view",$data);
       } 
       
       public function parent_notices(){
           $data['result']= $this->fetch_notification(2);
           $this->load->view("noticeboard/view",$data);
       } 
       
       private function fetch_notification($for){
           $query=$this->db->query("SELECT  n.* , s.name as staff_name  FROM `notice_board` n JOIN staff s ON n.added_by = s.id WHERE n.iid='".$this->session->userdata('staff_Org_id')."' AND n.for='".$for."' AND n.status=1 AND n.expiry > ".time()."  ");
          $query=$query->result(); 
          return $query;
       }
       
       
       
}
?>