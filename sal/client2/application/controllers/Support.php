<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->model('logs');
            $this->load->model('barcode');
            $this->load->model('Email');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
        }
        
	public function index()
	{
           $this->load->view('support/index');	   
	}
        
        public function Add_query()
	{
           $this->load->view('support/add');	   
	}
        
        public function Add(){
            
            $post=$this->operations->cleanInput($_POST);
            //prntname
            $field = 'title';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Title');
            }
            
            $field = 'query';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Query');
            }
            
            $field = 'image';
            $image=1;
            if(strlen($_FILES[$field]['name']) >0){
		
                $ext=explode(".",$_FILES[$field]['name']);
                $ext=$ext[sizeof($ext)-1 ];
                $img_arr=array('jpg','jpeg','gif','png');
               
                if(!in_array($ext,$img_arr)){
                    $this->form->setError($field,'* Please Select a Image file');
                }
                  
            }else{
                $image=0;
            }

            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('Support/Add_query', 'refresh'); 
            }else{
                $support_id=$this->view_active_support();
                if($support_id==0){
                    $status=1;
                }else{
                    $status=2;
                }
                $file="";
                    if($image==1){
                       $ext=explode(".",$_FILES['image']['name']);
                       $ext=$ext[sizeof($ext)-1 ];
                       $file=$config['file_name'] = "supp_".time().".".$ext;
                       //$config['upload_path']   = 'var/www/html/school/assests_2/uploads/'; 
                       $config['upload_path']   =  upload_path; 
                       $config['allowed_types'] = 'gif|jpg|png'; 
                        
                       $this->load->library('upload', $config);
                       $this->upload->do_upload('image');
                    }
                  
                    $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'title' => $post['title'],
                        'support_id' =>$support_id,
                        'query' => $post['query'],
                        'time'=>time(),
                        'status'=>$status,
                        'file' =>$file,
                        'userid'=>"s".$this->session->userdata('staff_id'),
                      );
                    $this->db->insert('support_queries',$data);
                  $sqid=$this->db->insert_id(); 
                  
                 $this->Email->send_supportmail_alert($this->session->userdata('staff_id'),$sqid);
                    $this->session->set_userdata('Query_Add', 'Submitted Query , Support Team will Get Back To you Soon.. ');
                    redirect("Support/",'refresh'); 
            }
            
            
        }
        
        public function view_query($id){
            $query ="SELECT * FROM `support_queries` where   iid = '".$this->session->userdata("staff_Org_id")."' AND userid='s".$this->session->userdata('staff_id')."' AND qid='".$id."' ";
            $query = $this->db->query($query);
            if($query->num_rows()>0){
                $query = $query->row();
                $data['query']=$query;
                $this->load->view('support/view',$data);
            }else{
                 redirect("Support/",'refresh'); 
            }           
        }
        
        
        private function view_active_support(){
            $q=  $this->db->query(" SELECT u.id ,(SELECT count(*) from support_queries s where s.status=2 AND s.support_id=u.id  ) as tickets FROM `users` u where active = 1 AND level in (99,55)  ");   
            if($q->num_rows()>0){
                $q= $q->result();
                $min=0;
                $support_list =array();
                foreach($q as $value){
                    $support_list[$value->tickets][]=$value->id;
                    if($value->tickets <=$min){
                        $min =$value->tickets;
                    }
                }
                if(isset($support_list[$min])){
                    $support_list=$support_list[$min];
                return $support_list[array_rand($support_list)];   
                }else{
                    return 1;
                }
                 
            }   else{
                return 1;
            }        
                        
        }
        
}
?>