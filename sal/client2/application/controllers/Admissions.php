<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admissions extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('validations');
            $this->load->model('studparent');
            $this->load->model('examinations');
            $this->load->model('form');
            $this->load->library("pagination");$this->operations->is_login();
            
        }
    
    public function index(){
        $this->load->view('admissions/index');
    }
    
    public function add_new(){
        $this->load->view('admissions/add');
    }
    
    public function approved(){
        $data['status']="Approved List";
        $data['status_no']="2";
        $this->load->view('admissions/list',$data);
    }
    
    public function rejected(){
        $data['status']="Rejected List";
        $data['status_no']="0";
        $this->load->view('admissions/list',$data);
    }
    public function pending(){
        $data['status']="Pending List";
        $data['status_no']="1";
        $this->load->view('admissions/list',$data);
    }
    
    public function create(){
     
     $post=$this->operations->cleanInput($_POST);
            //prntname
                   
            $field = 'stdname';
          $name_err=$mob_err=0;
            if(strlen($post[$field]) ==  0)
            {
                $name_err++;
               $this->form->setError($field,'* Provide Student Name');
            }
            $field = 'stdsex';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'*Select Student Gender');
            }
            
            $field = 'Mobile';
            if(strlen($post[$field]) ==  0)
            {
                $mob_err++;
                    $this->form->setError($field,'* provide Mobile Number');
            }
            elseif( $this->validations->validate_mobile($post[$field])==0 )
            {
                 $mob_err++;
                    $this->form->setError($field,'* enter valid Mobile Number  ');

            }
            
            if( $name_err==0 && $mob_err==0){
                $ch= "SELECT * from student_admission where name ='".$post['stdname']."' AND  phone='".$post['Mobile']."' ";
                $ch=  $this->db->query($ch);
                if($ch->num_rows()!=0){
                     $this->form->setError("stdname",'* Admission Already Exists.');
                }
            }
            //std_cls
            $field = 'std_cls';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Class Of Admission');
            }
            $field = 'medium';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Prevous Medium Of Study');
            }
            $field = 'prev_cls';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Prevous Class Of Study');
            }
            
            $field = 'medium';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Select Medium Of Study');
            }
            $field = 'prev_School';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Prevous School Of Study');
            }
            $dob="";
            $field = 'stddob';
            if(strlen($post[$field]) ==  0)
            {
                    //$this->form->setError($field,'* provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
               $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
               $bod_day = getdate($dob);
               $bod_day =$bod_day['mday']."/".$bod_day['mon'];
                if($dob >time()){
                    $this->form->setError($field,'* select a valid Date Of Birth');
                }
            }
            
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               redirect('Admissions/add_new', 'refresh'); 
            }else{
                  $data=array(
                      'iid' =>$this->session->userdata("staff_Org_id"),
                      'name' => strtolower($this->input->post("stdname")),
                      'religion' =>  $this->input->post("religion"),
                      'sex' =>  $this->input->post("stdsex"),
                      'birthday' =>  $dob,
                      'address' =>  $this->input->post("stdaddress"),
                      'medium' =>  $this->input->post("medium"),
                      'phone' =>  $this->input->post("Mobile"),
                      'father_name' =>  $this->input->post("stdfather"),
                      'mother_name' =>  $this->input->post("stdmother"),
                      'class_id' =>  $this->input->post("std_cls"),
                      'caste' =>  $this->input->post("caste"),
                      'prev_medium' =>  $this->input->post("medium"),
                      'prev_class' =>  $this->input->post("prev_cls"),
                      'prev_school' =>  $this->input->post("prev_School"),
                      'staff_id' =>$this->session->userdata('staff_id'),
                      'status' =>1
                  );
                  $this->db->insert("student_admission",$data);
                $this->session->set_userdata('student_add_Sucess', 'New Admission Created Sucessfully');
              
            }
            redirect('Admissions/add_new', 'refresh'); 
     
    }
    
    public function reject_update(){
        
        $data= array(
            'status'=>0,
            'remark'=>  $this->input->post("rjctreason")
        );
        $this->db->where('adm_id',  $this->input->post('adminss_id'));
        $this->db->update('student_admission',$data);
        $this->session->set_userdata('admission_reject_Sucess', 'Admission Rejected Sucessfully');
        ?>
        <script>
         location.reload();   
       </script>       
        <?php
                    
    }
    
    public function Proceed_aprove($adms_id){
        if( strlen($adms_id)==0){
            redirect("Admissions/pending","refresh");
        }else{
         $adms_id=" SELECT s.*,c.name as cls_name FROM `student_admission` s JOIN class c ON c.id=s.class_id  where s.iid='".$this->session->userdata("staff_Org_id")."' and adm_id= '".$adms_id."' ";
         $adms_id = $this->db->query($adms_id);
         if(sizeof($adms_id)==0){
             redirect("Admissions/pending","refresh");
         }else{
             $adms_id=$adms_id->row();
            // print_r($adms_id);
             $data["adms_id"]=$adms_id;
             $this->load->view('admissions/proceed_list',$data);
         }
        }
        
         
    }
    
    
    public function View_approved($adms_id){
        if( strlen($adms_id)==0){
            redirect("Admissions/pending","refresh");
        }else{
         $adms_id=" SELECT s.*,c.name as cls_name FROM `student_admission` s JOIN class c ON c.id=s.class_id  where s.iid='".$this->session->userdata("staff_Org_id")."' and adm_id= '".$adms_id."' ";
         $adms_id = $this->db->query($adms_id);
         if(sizeof($adms_id)==0){
             redirect("Admissions/pending","refresh");
         }else{
             $adms_id=$adms_id->row();
            // print_r($adms_id);
             $data["adms_id"]=$adms_id;
            
             $this->load->view('admissions/view_std_data',$data);
         }
        }
        
         
    }
    
    private function check_admission_no($userid,$stud=0){
            if($stud==0){
             $query=  $this->db->query("SELECT *  FROM student where userid='".$userid."'AND iid='".$this->session->userdata('staff_Org_id')."' ");
            }else{
                $query=  $this->db->query("SELECT *  FROM student where userid='".$userid."'AND student_id ='".$stud."'  AND  iid='".$this->session->userdata('staff_Org_id')."' ");
            }
            if($query->num_rows()>0){
                return FALSE;
            }else{
                return TRUE;
            }

       }
       
       
    public function submit_admission(){
   
        
        $post=$this->operations->cleanInput($_POST);
            //prntname
            
            $field = 'userid';                              
            //  preg_match('/[^A-Z]/i',"pabaghvh  vhgvgg");exit;
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* enter Admission No');
            }else{
                if( (strlen($post[$field])<5) || (strlen($post[$field])>25) )  {
                    $this->form->setError($field,'* valid Admission No');
                   // echo "INvalid admission ";
                }else{
                    if(preg_match("/^[a-zA-Z0-9]+$/", $post[$field]) != 1) {
                      $this->form->setError($field,'* valid Admission No');
                    }else {
                    if(!$this->check_admission_no($post[$field])){
                        $this->form->setError($field,'* valid Admission No');
                    }
                     }
                }
            }
            
            $field = 'stdname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Student Name');
            }
            
            $field = 'stdfather';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Father name');
            }
            
            
            $field = 'transfer_cert';
            if(!isset($post[$field])){
                   $this->form->setError($field,'* Provide Status Of Transfer Certificate');
            }else if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Provide Status Of Transfer Certificate');
            }
            
            $field = 'Bonafide';
            if(!isset($post[$field])){
                  $this->form->setError($field,'* Provide Status Of Bonafide Certificate');
            }else if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Provide Status Of Bonafide Certificate');
            }
            
            $field = 'caste_cert';
            if(!isset($post[$field])){
                 $this->form->setError($field,'* Provide Status Of Caste Certificate');
            }else if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Provide Status Of Caste Certificate');
            }
            
            $field = 'income_cert';
            if(!isset($post[$field])){
                   $this->form->setError($field,'* Provide Status Of Income Certificate');
            }else if(strlen($post[$field]) ==  0)
            {
                  $this->form->setError($field,'*Provide Status Of Income Certificate');
            }
            
            
            $field = 'stdmother';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Mother name');
            }
            $field = 'stdsex';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Student Gender');
            }
            
            $field = 'caste';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Caste');
            }
            
            
            $field = 'stdaddress';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Address ');
            }
            $field = 'medium';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Medium Of Study ');
            }
            
           $field = 'stdbg';
            if(strlen($post[$field]) ==  0)
            {
                  //  $this->form->setError($field,'* Select Blood Group');
            }    
            
            
             $field = 'stdclass';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Class Of Study ');
            }else{
                $cls=explode("-",$post[$field]);
                $section =$cls[1];
                $cls =$cls[0];
                $field='stdroll';
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Enter Roll No');
                }elseif(!$this->studparent->checkstdroll($post['stdclass'],$section,$cls)){
                    $this->form->setError($field,'* Roll No Already Assigned');
                }
            }
            
            $id=0;
            $proof_id="";
            $field = 'id_proff';
          
            if( strlen($post[$field]) ==  0 )
            {
              $this->form->setError($field,'* Select Id proff');
          
               if((strlen($post['proffid']) !=  0)){
                   //$this->form->setError($field,'* Select Id proff');
               }
            }else{
               if((strlen($post['proffid']) ==  0)){
                   $this->form->setError('proffid','* enter id Proof');
               }else{
                   switch($post[$field]){
                        case 1 : $id =1;
                                if( (strlen($post['proffid'])!=12) || (!is_numeric($post['proffid'])) ){
                                   $this->form->setError('proffid','* enter Valid AAdhar No');
                                  }else{
                                      $proof_id =$post['proffid'];
                                  }break;
                        case 2 : $id =2;
                                 if( (strlen($post['proffid'])!=8)  ){
                                   $this->form->setError('proffid','* enter passport No  ');
                                  }else{
                                      $proof_id =$post['proffid'];
                                  }
                                  break;
                        default :$this->form->setError($field,'* Select Valid id proff');                                  
                                   break;
                   }
               } 
            }
            
            $field = 'trans_use';
            $trans_use=0;
            if(isset($post[$field])){
                if(strlen($post[$field]) !=  0)
            {
                $trans_use =1;
                $field = 'bus_route';
          
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Provide Bus Route ');
                }
                $field = 'bus_trip';
          
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Select Bus Trip ');
                }
                $field = 'pickup';
          
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Select Pick-Up Point ');
                }
                $field = 'trans_fee';
          
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Enter Transport Fee ');
                }elseif(!is_numeric($post[$field])){
                      $this->form->setError($field,'* Enter Numeric Value');
                }
            }
            }
            
            $dob="";$bod_day ="";
            $field = 'stddob';
            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
               $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
               $bod_day = getdate($dob);
               $bod_day =$bod_day['mday']."/".$bod_day['mon'];
                if($dob >time()){
                    $this->form->setError($field,'* select a valid Date Of Birth');
                }
            }
            
            
            $field = 'Mobile';
		if(strlen($post[$field]) ==  0)
		{
			$this->form->setError($field,'* provide Mobile Number');
		}
		elseif( $this->validations->validate_mobile($post[$field])==0 )
		{
			$this->form->setError($field,'* enter valid Mobile Number  ');
                        
		}
            
          //stemail
          $field = 'email';
		if(strlen($post[$field]) ==  0)
		{
		  //	$this->form->setError($field,'* provide E-mail');
		}
		elseif( $this->validations->validate_email($post[$field])==0 )
		{
			$this->form->setError($field,'* enter Valid E-mail id  ');
		}
            $field = 'prntmobile';
		if(strlen($post[$field]) ==  0)
		{
			//$this->form->setError($field,'* provide Mobile Number');
		}
		elseif( $this->validations->validate_mobile($post[$field])==0 )
		{
			$this->form->setError($field,'* enter valid Mobile Number  ');
                        
		}
            
          //stemail
          $field = 'prntemail';
		if(strlen($post[$field]) ==  0)
		{
		  	$this->form->setError($field,'* provide E-mail');
		}
		elseif( $this->validations->validate_email($post[$field])==0 )
		{
			$this->form->setError($field,'* enter Valid E-mail id  ');
		}
            
          $img_uplod=0;
          if($post['image_type']==1){
              if(strlen($this->session->userdata('student_dummy_img'))!=0){
                  $img_uplod=1;
              }
          }else{
                $field = 'stdimage';
                if(strlen($_FILES[$field]['name']) ==  0)
                {
                      //  $this->form->setError($field,'* Select a profice pic');
                }else{
                    $img_uplod=2;
                    $ext=explode(".",$_FILES['stdimage']['name']);
                    $ext=$ext[sizeof($ext)-1 ];
                    $img_arr=array('jpg','jpeg','gif','png');
                    if(!in_array($ext,$img_arr)){
                        $this->form->setError($field,'* Select a Image file');
                    }
                } 
          } 
          if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect('Admissions/Proceed_aprove/'.$this->input->post("admiss_id"), 'refresh'); 
         }else{
             $pass1=$this->validations->generate_password();
             $file ="";
                  if($img_uplod==2){
                            $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."drv_".time().".".$ext; 
                            $config['upload_path']   = '/var/www/html/schooln/assests_2/uploads/'; 
                            //'var/www/html/schooln/assests_2/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png'; 
                            $this->load->library('upload', $config);
                            $this->upload->do_upload('stphoto');
                    }
                    if($img_uplod==1){
                        $file= $this->session->userdata('staff_Org_id')."_std_".time().".jpg"; 
                        $this->session->unset_userdata('driver_dummy_img');
                        copy('C:/wamp/www/schooln/assests_2/uploads/temp/'.$this->session->userdata('student_dummy_img'),'C:/wamp/www/schooln/assests_2/uploads/'.$file);
                    }
              $parent_id="SELECT * from parent where iid= '".$this->session->userdata('staff_Org_id')."' AND phone= '".$this->input->post('Mobile')."' ";   
           $parent_id = $this->db->query($parent_id);
           if($parent_id->num_rows()>0){
               $parent_id=$parent_id->row();
               $parent=$parent_id->parent_id;
           }else{
               $data = array(
                   'iid' =>$this->session->userdata('staff_Org_id'),
                   'name' =>$post['stdfather'],
                   'email' =>$post['prntemail'],
                   'password' =>$pass1,
                   'phone' =>$post['Mobile'],
                   'address' =>$post['stdaddress'],
                   'profession' =>'',
                );
               $this->db->insert('parent',$data);
               $parent=$this->db->insert_id();
                 $this->logs->insert_staff_log(6,'Created Parent :'.$post['prntemail'],$parent  );
                  
           }
                                            $id=$this->get_admission_no();
                                             $code=$this->studparent->get_institute_code();
                       
                                                $userid=$code.$this->make_5digit($id);
                                            $id++;
                                            $pass=$this->studparent->generate_pass();
         
              $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'name' => $post['stdname'],
                       'userid' => $userid,
                       'admission_no'=>$this->input->post('userid'),         
                       'birthday'=>$dob,
                       'sex' => $post['stdsex'],
                       'address'=>$post['stdaddress'],
                       'phone' => $post['Mobile'],
                       'email'=> $this->input->post('email'),
                        'password'=>$pass,
                        'parent_id'=>  $parent,
                       'class_id' => $cls,
                       'section_id'=>$section,
                       'roll' => $post['stdroll'],
                       'photo'=>$file ,
                       'locality'=>"",
                        'district'=>"",
                        'father_name' =>$post['stdfather'],
                        'mother_name' =>$post['stdmother'],
                        'id_proof' =>$id ,
                        'proofid' =>$proof_id,
                        'bloodgroup' =>$post['stdbg'],
                        'bday' =>$bod_day,
                        'caste' =>$post['caste'],
                        'religion'=>$post['religion']
                    );
              $this->db->insert('student',$data);
                $std_id=$this->db->insert_id();
             
        $data= array(
            'status'=>2,
            'remark'=>  $std_id
        );
        $this->db->where('adm_id',  $this->input->post('admiss_id'));
        $this->db->update('student_admission',$data);
        
              $this->logs->insert_staff_log(5,'Created Student :'.$post['userid'],$std_id);
             $data = array(
                  'std_id' =>$std_id,
                  'iid' =>$this->session->userdata('staff_Org_id'),
                  'transfer' =>$this->input->post('transfer_cert'),
                  'bonafide' =>$this->input->post('Bonafide'),
                  'caste' =>$this->input->post('caste_cert'),
                  'income' =>$this->input->post('income_cert')
              );
              $this->db->insert('stud_documents',$data);
              if($trans_use==1){
                   $trip=explode(',',$this->form->value('pickup'));
                                                      
                  $data=array(
                      'iid' =>$this->session->userdata('staff_Org_id'),
                       'stud_id' =>$std_id,
                      'trip_route_id' => $trip[0],
                       'fee_amount' =>$post['trans_fee'],
                      'timestamp' => time()
                  );
             $this->db->insert('stud_transport',$data); 
          
              }
            $msgcontent="Dear Student,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                     . "url : http://ems.snetworkit.com/schooln/students/ \n username: ".$userid."\n Password : ".$pass;

                $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['Mobile']."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

                  $ret = file($url);
              if($ret){
             $this->studparent->send_sms($msgcontent,$post['Mobile']);
            }

            $msgcontent1="Dear Parent,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                     . "url : http://ems.snetworkit.com/schooln/parents/ \n username: ".$post['prntemail']."\n Password : ".$pass1;

              $url1 = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['Mobile']."&from=School&message=".urlencode($msgcontent1);    //Store data into URL variable

                  $ret1 = file($url1);
            if($ret1){
             $this->studparent->send_sms($msgcontent1,$post['Mobile']);
            }

    $this->update_admission_no($id-1);            
            $this->session->set_userdata('admission_reject_Sucess', 'Student Sucessfully Admitted ');
         }
          
          redirect('Admissions/pending/', 'refresh'); 
        
        
    }
    
    
    private function make_5digit($id){
            if(strlen($id)>5){
                return $id;
            }
            $str="";
            $len=5-strlen($id);
            for($i=0;$i<$len;$i++){
               $str.="0";
            }
            $str.=$id;
            return $str;
        }

        private function get_admission_no(){
             $query = $this->db->query("SELECT `last_id` FROM `admission` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'admissionno'=>1,
                    'last_id'=>1,
                    );
                $this->db->insert('admission',$data);
                return 1;
            }else{
                $result=$query->row();
                return $result->last_id;     
            }          
      }
       
        private function update_admission_no($no){
        $this->db->query("UPDATE `admission` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");  
      }
        
    public function approve_report(){
        $this->load->view('admissions/approved_report');
    }
      
      
}
?>