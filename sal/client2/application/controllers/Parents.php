<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class parents extends CI_Controller {
    
	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('studparent');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->model("validations");
            $this->load->library("pagination");
            $this->operations->is_login();
            /* cache control */
        }
        
	public function index()
	{
           redirect("parents/View","refresh");	   
	}
        
        public function parent_add(){
            
            $this->load->view('parents/create');
        }
        
        public function View($key=""){
                   
            $data["results"] = $this->fetch_parents();
            $this->load->view('parents/view',$data);
        }
         
        public function create(){
            
            
            $post=$this->operations->cleanInput($_POST);
            //prntname
            $field = 'prntname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Parent Name');
            }
            
            
           $field = 'prntemail';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Parent E-mail');
            }elseif(!$this->studparent->checkemail(trim($post[$field]))){
                //check_cls_val
                $this->form->setError($field,'* E-mail Already Registered');
            }
            
            $field = 'prntphone';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Parent Phone');
            }elseif(!$this->studparent->checkmobile(trim($post[$field]))){
                //check_cls_val
                $this->form->setError($field,'* Mobile No Already Registered');
            }
            
            $field = 'prntadd';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Parent Address');
            }
            
            $field = 'prntprof';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Parent Profession');
            }
            
            
             if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               
                }else{
                    $pass=$this->studparent->generate_pass();
                    $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'name' => $post['prntname'],
                       'email' => $post['prntemail'],
                       'password' => $pass,
                       'phone' => $post['prntphone'],    
                       'address' =>$post['prntadd'],
                       'profession' => $post['prntprof']    
                      );
                   
                    $this->db->insert('parent',$data);
                    $this->logs->insert_staff_log(6,'Created Parent :'.$post['prntname'],  $this->db->insert_id());
                    $this->session->set_userdata('parent_add_Sucess', 'Sucessfuylly Created Parent ');
                    
                    $msgcontent="Dear Parent,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                            . "url : http://ems.snetworkit.com/schooln/parents \n username: ".$post['prntemail']."\n Password : ".$pass;

                        $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['prntphone']."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

                  $ret = file($url);
                if($ret){

                    $this->studparent->send_sms($msgcontent,$post['prntphone']);
                    //parents/parent_add
                    redirect('parents/view', 'refresh');
                  }
                }           
             redirect('parents/parent_add', 'refresh'); 
        }
        
        public function bulk(){
            $this->load->view('parents/bulk_create');
        }
        
        public function bulk_create(){
            $file= $_FILES['bfile'];
	    $filename=$file['name'];
		if(strlen(trim($filename)) == 0)
		{
			$this->form->setError('bfile','* file is not selected');
		}
		else
		{
                        $end=explode('.',$filename);
			$end = strtolower(end($end));
                        
                        $type = array("csv", "txt");
                        
			if(!(in_array($end, $type)))
			{
				$this->form->setError('bfile','* file is supporrt only csv/txt format');
			}
		}
		
		if($this->form->num_errors >0 )
                {
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $this->form->getErrorArray();
                   
                }
		else
		{
                    $file_pointer = fopen($file['tmp_name'], "r");
                    $file_read = fread($file_pointer, $file['size']);

                    $newdata=$file_read;
                    $ex=explode("\n",$newdata);
                    
                    if(strlen(trim($ex[sizeof($ex)-1]))==0){
                       array_pop($ex);
                    }
                        
                    if(trim($ex[0]) == "name,mobile,email,profession,address"){
                      array_shift($ex); 
                    }
                    $ex = array_filter($ex);
                    $counter= 0;
                    $name = '';
                    $i = 0;
                    $values="";
                    $code=$this->studparent->get_institute_code();
                    $dummy=array();   
                     
                    foreach($ex as $val)
			{      
                         
                        $error =0;
                                $arr=explode(",",$val);
                              
                                $arr = array_filter($arr);
                                  
                                $phone=$arr['1'];
                                $email = $arr['2'];
                                $addr = $arr['4'];
                                if(sizeof($arr)>5){
                                  
                                    for($x=5;$x<=sizeof($arr)-1;$x++){
                                        $addr.=",".$arr[$x]; 
                                    }
                                }
                                $addr =trim(str_replace( '"', "", $addr) );
                                
                                
                               if( $this->validations->validate_mobile($phone)==0 )
                                {
                                   $error++;
                                   $arr['error']="Invalid Mobile Number";  
                                  
                                }else if(!$this->studparent->checkmobile($phone)){
                                    $error++;
                                    $arr['error']="Mobile Number Already Registered"; 
                                   
                                }
                                if( $this->validations->validate_email($email)==0 )
                                {
                                         $error++;
                                   $arr['error']="Invalid E-mail id";  
                                  
                                }elseif( ! $this->studparent->checkemail($email)){
                                   $error++;
                                   $arr['error']="E-mail id Already Registered";  
                                  
                                }   
                                
                                
                                if($error==0){
                                    $counter++;
                                    $pass=$this->studparent->generate_pass();
                                       $data = array(
                                                'iid' => $this->session->userdata('staff_Org_id'),
                                                'name' => $arr[0],
                                                'email' => $email,
                                                'password' => $pass,
                                                'phone' => $phone,    
                                                'address' =>$addr,
                                                'profession' => $arr[3]    
                                               );
                                            $this->db->insert('parent',$data);
                                             $this->logs->insert_staff_log(6,'Created Parent :'.$arr[0],  $this->db->insert_id());
                                             $this->session->set_userdata('parent_add_Sucess', 'Sucessfuylly Created Parent ');

                                             $msgcontent="Dear Parent,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                                                     . "url : http://ems.snetworkit.com/schooln/parents/ \n username: ".$email."\n Password : ".$pass;

                                              $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$phone."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

                                    $ret = file($url);

                                    if($ret){
                                             $this->studparent->send_sms($msgcontent,$phone);
                                    }
                                }else{

                                     $dummy[]=$arr;
                                }
                                
			}
                        if(sizeof($dummy)>0){
                      $_SESSION['prntdummy_data']=$dummy;  
                    }
                    if($counter!=0){
                         $this->session->set_userdata('bulkprnt_add', $counter.' Parents  Records Created Sucessfully ');
                    }
                   
                        
                }
               redirect('parents/bulk', 'refresh'); 
                
        }
        
        private function fetch_parents(){
            $query="SELECT * FROM `parent` where iid ='".$this->session->userdata('staff_Org_id')."'";            
           $query=  $this->db->query($query);
           return $query->result();
        
        }
        
        public function  view_parent($id=""){
            if(strlen($id)==0){
                redirect("parents/View","refresh");
            }else{
                $q="SELECT * FROM `parent` WHERE `parent_id` = '".$id."' AND `iid` ='".$this->session->userdata('staff_Org_id')."' ";
                $q = $this->db->query($q);
                if($q->num_rows()>0){
                  $q=$q->row();
                  $data['parent']=$q;
                  $this->load->view('parents/view_parent',$data);
                }else{
                    redirect("parents/View","refresh");
                }
            }
        }
        
        public function link_students($id=""){
            if(strlen($id)==0){
                redirect("parents/View","refresh");
            }else{
                $q="SELECT * FROM `parent` WHERE `parent_id` = '".$id."' AND `iid` ='".$this->session->userdata('staff_Org_id')."' ";
                $q = $this->db->query($q);
                if($q->num_rows()>0){
                  $q=$q->row();
                  $data['parent']=$q;
                  $this->load->view('parents/assign_students',$data);
                }else{
                    redirect("parents/View","refresh");
                }
            }
        }   
        
        public function create_link(){
           $query="UPDATE `student` SET `parent_id` = '".$this->input->post("parent")."' WHERE `student_id` ='".$this->input->post("student")."'";
           $query= $this->db->query($query);
            $this->session->set_userdata('parent_Link_Sucess', 'Sucessfully Linked Student Account ');
           ?>
                <script>
                      window.location.href = '<?php echo base_url() ?>index.php/Parents/view_parent/<?php  echo $this->input->post("parent") ?>';
                </script>
           <?php
        }
        
}
