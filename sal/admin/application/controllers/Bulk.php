<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk extends CI_Controller {

        function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->model('studparent');
            $this->load->library("pagination");
            $this->load->model("validations");$this->operations->is_login();
        }
        
        public function index(){
            ?>
<a href="<?php echo base_url() ?>index.php/Bulk/bulk_students">Add Students</a>    <br/><br/>
            <?php
        }
        
        public function bulk_students(){
            $this->load->view("Bulks/students");      
        }
                public function add_students(){
             
            $post=$this->operations->cleanInput($_POST);
            $field = 'bstdclass';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Select Class');
            }else{
               $field = 'bstdsection';
                    if(strlen($post[$field]) ==  0)
                    {
                       $this->form->setError($field,'* Select Section');
                    } elseif(!$this->studparent->cls_section($post['bstdclass'],$post['bstdsection']))
                            {
                        $this->form->setError($field,'* Select Valid Section');
                         }
                }
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
                    echo "<pre>";
			$file_pointer = fopen($file['tmp_name'], "r");
			$file_read = fread($file_pointer, $file['size']);
			
			$newdata=$file_read;
			$ex=explode("\n",$newdata);
                       
                        if(strlen(trim($ex[sizeof($ex)-1]))==0){
                           array_pop($ex);
                        }
                        
                        if(trim($ex[0]) == "Name,Admission Number,DOB,Gender,Father Name,Mother Name,Mobile,Address"){
                          array_shift($ex); 
                        }
                        
			$counter= 0;
			$name = '';
			$i = 0;
			$values="";
                        $id=$this->get_admission_no();
                        $code=$this->studparent->get_institute_code();
                        $dummy=array();
                        $counter=0;
			foreach($ex as $val)
			{
                           
                                $arr=explode(",",$val);
                              //  $arr = array_filter($arr);
                               
                                if(strtolower($arr[0])=="name"){
                                    
                                }else{
                                echo "<pre>";
                               // print_r($arr);
                                //exit;
                                $name = $arr[0];
                                $roll= $arr[1];
                                $admission_no=" ";
                                $dob=$bday="";
                                $str =$arr[2];
                                $str = explode("-", $str);
                                if(sizeof($str)==3){
                                    $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                                    $str =getdate($dob);
                                    $bday=$str['mday']."/".$str['mon'];
                                }
                                
                                $gender=1;
                                if(strtolower($arr[3])=="female"){
                                    $gender=2;
                                }
                                $father= $arr[7];
                                $mother=$arr[8];
                                $caste ="";
                                $id_proof=1;
                                $mobile =$arr[4];
                                              $userid=$code.$this->make_5digit($id);
                                            $id++;
                                            $pass=$this->studparent->generate_pass();
                                           $data = array(
                                                  'iid' => $this->session->userdata('staff_Org_id'),
                                                  'name' => $name,
                                                  'userid'=>$userid,
                                                  'admission_no'=>$admission_no,
                                                  'birthday'=>$dob,
                                                  'sex' => $gender,
                                                  'father_name' =>$father,
                                                  'mother_name' =>$mother,
                                                  'address'=>$arr[9],
                                                  'phone' =>$mobile ,
                                                  'email'=>"",
                                                  'password'=>$pass,
                                                  'class_id' => $post['bstdclass'],
                                                  'section_id'=>$post['bstdsection'],
                                                  'id_proof' =>1,
                                                    'proofid' =>"",
                                                    'caste' =>$caste,
                                                  'roll' => $roll,
                                                  'bday' =>$bday
                                                 );
                                                 $counter++;
                                           $this->db->insert('student',$data); 
                            
                                }
                        }
                       $this->update_admission_no($id);
                        $this->session->unset_userdata("student_count");
                        $this->session->unset_userdata("student_count");
                       $this->session->set_userdata('bulkstd_add', $counter.' Student Records Created Sucessfully ');
                   
		}
             redirect('Bulk/bulk_students', 'refresh'); 
           
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
      
      public function update_student_userids(){
             $query = $this->db->query("SELECT `userid`, count(*) as count ,group_concat(student_id) as ids  FROM `student` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' GROUP BY userid ");
             $query = $query->result();
             foreach ($query as $value) {
                 if($value->count!=1){
                     $ids= $value->ids;
                     $ids= explode(",", $ids);
                     for($i=1;$i<sizeof($ids);$i++){
                    //   echo "<br/>".$ids[$i];  
                     $id=$this->get_admission_no();
                     $code=$this->studparent->get_institute_code();
                     $userid=$code.$this->make_5digit($id);
                     $data = array("userid"=>$userid);
                     $this->db->where('student_id',$ids[$i]);
                     $this->db->update('student', $data); 
                     $id++;
                     $this->update_admission_no($id);
                       
                     }

                 }
             }
      }
      
      
        private function update_admission_no($no){
        $this->db->query("UPDATE `admission` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");  
      }
        
}

//         if(sizeof($arr)>8){
//                                    $addr="";
//                                    for($x=9;$x<=sizeof($arr)-1;$x++){
//                                        $addr.=",".$arr[$x]; 
//                                    }
//                                }
//                                                                
//                                $str=explode("-",$arr[3]);
//                                $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
//                                $phone=$arr['7'];
//                                $sex=1;
//                                if(strtolower(trim($arr['4']))!='male' ){
//                                    $sex=2;   
//                                }
//                                if( $this->validations->validate_mobile($phone)==0 )
//                                {
//                                        $phone="";
//                                }if(!$this->studparent->checkstdroll($post['bstdclass'],$post['bstdsection'],$arr['1'])){
//                                    $arr['error']="Roll No already Assigned";  
//                                    $dummy[]=$arr;
//                                }else{
//                                    if(preg_match("/^[a-zA-Z0-9]+$/", $arr['6']) != 1) {
//                                        $arr['error']="Invalid Admission Number"; $dummy[]=$arr; 
//                                      }elseif(!$this->check_admission_no($arr['6'])){
//                                             $arr['error']="Admission Number Already Assigned"; $dummy[]=$arr; 
//                                           }else{
//                                            $counter++;
//                                            $userid=$code.$this->make_5digit($id);
//                                            $id++;
//                                            $pass=$this->studparent->generate_pass();
//                                           $data = array(
//                                                  'iid' => $this->session->userdata('staff_Org_id'),
//                                                  'name' => $arr['0'],
//                                                  'userid'=>$userid,
//                                                  'admission_no'=>$arr['6'],
//                                                  'birthday'=>$dob,
//                                                  'sex' => $sex,
//                                                  'father_name' =>$arr['7'],
//                                                  'mother_name' =>$arr['8'],
//                                                  'address'=>$addr,
//                                                  'phone' => $arr['4'],
//                                                  'email'=>$arr[5],
//                                                  'password'=>$pass,
//                                                  'class_id' => $post['bstdclass'],
//                                                  'section_id'=>$post['bstdsection'],
//                                                  'roll' => $arr['1'],
//                                                 );
//
//                                           print_r($data);
//                                    //      $this->db->insert('student',$data); 
//                                      //   $this->logs->insert_staff_log(5,'Created Student : '.$arr['6'],  $this->db->insert_id());           
//                                    
//                                         }
// 			}
                       