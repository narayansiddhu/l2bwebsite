<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class students extends CI_Controller {
	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('validations');
            $this->load->model('studparent');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->operations->is_login();
            /* cache control validations */
            error_reporting(E_ALL & ~E_NOTICE);
        }
        
	public function index()
	{
           if(is_null($this->session->userdata('staff_id'))){
             $this->load->view('login');
           }else{
            //$this->load->view('students/index');  
            redirect('students/View','refresh');
           }	   
	}

    public function promote(){
        if(isset($_GET['sclass']) && isset($_GET['section'])){                
                $sclass="";
                $section="";
                if((strlen( $_GET['sclass'] ) ==0) || (strlen($_GET['section']) ==0) ) {
                     $data['result']=FALSE;
                     $data['error']="provide keys for searching"; 
                }else{
                    if(strlen(trim($this->input->get('sclass')))!=0 ){
                      $sclass =trim($_GET['sclass']); 
                    }
                    if(strlen(trim($this->input->get('section')))!=0 ){
                      $section =trim($_GET['section']); 
                    }
                   // echo $txt ."<br/>".$id."<br/>".$admission_no;
                    $data['result']=  $this->fetch_student_result($sclass,$section);
                    $data['error']=""; 
                }
            }else{
                $data['result']=FALSE;
                $data['error']="";

            }
            $this->load->view('students/promote_student',$data);
        }

        public function promote_students(){
          
          $data1 = $this->input->post('check'); //this returns an array so use foreach to extract data
          
          $data1 = explode(' ',$data1);
          //print_r ($data1);?>
        <div class="container">
          <div class="table-responsive" style="width: 75%;">          
            <table class="table">
              <thead>
                <tr>
                  <th>Student Name</th>
                  <th>Roll No</th>
                  <th>Userid</th>
                  <th>Gender</th>
                  <th>Mobile</th>
                </tr>
              </thead>
    <tbody>
      <?php
            foreach($data1 as $key => $value){
                  $sql = "SELECT * FROM `student` WHERE student_id =".$value;
                  $query = $this->db->query($sql);
                  $query= $query->row();
                 ?>
      <tr>
          
        <td><?php echo $query->name; ?></td>
        <td><?php echo $query->roll; ?></td>
        <td><?php echo $query->userid; ?></td>
        <td><?php if($query->sex == 1){echo 'Male';} else{echo 'Female';} ?></td>
        <td><?php echo $query->phone; ?></td>

        
      </tr>
      <?php  } ?>
    </tbody>
  </table>
  </div>
        
</div>
      <?php


              //echo json_encode($data1);
        }

        public function same_class_stu(){
          
          $data1 = $this->input->post('check'); //this returns an array so use foreach to extract data
          $reason = $this->input->post('reason');
          if(strlen($reason) ==  0)
            {
              echo '* Provide Reason of Same class';
              
            }

else{
          $data1 = explode(' ',$data1);

          $staff = $this->session->userdata('staff_Org_id');

          foreach($data1 as $key => $value){
                  $sql = "select * from `same_class_students` WHERE student_id ='".$value."'and iid=".$staff;

                  $query = $this->db->query($sql);
                  $query= $query->row();

          }

          if($value == $query->student_id){

                    if(count($data1) > 1){
                    echo "* Already students are promoted to same class!";
                  }
                  else{
                    echo "* Already student is promoted to same class!";
                  }
                  }
                  
           else{
            foreach($data1 as $key => $value){

                  
                 
                   //echo $value.' '."</br>";
                

              //$sql="SELECT * FROM `student` WHERE student_id ='$value'";
              //$query = $this->db->query($sql);
              //$query= $query->row();
                  $date = (date("Y") - 1) ;
              $date1 = date("y");
              $date2 = $date. "-" .$date1;
              //echo $date2;
                    

                  $sql = "Insert into `same_class_students`(student_id,iid,session,reason) values('$value','$staff','$date2','$reason')";

                  $query = $this->db->query($sql);
                  //$query= $query->row();
                  //print_r($query);
                  //$sql1 = "Insert into `x_student`(reason) values('".$reason."')";
                 //$query1 = $this->db->query($sql1);
                  //$sql2 = "DELETE FROM `student` WHERE student_id =".$value;
                  //$query2 = $this->db->query($sql2);
                  

          }
       

          if($query){

            if(count($data1) > 1){
                echo "<div class='alert alert-success'>
                  students are promoted to same class successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll numbers of students.</span>";

            }else{
                  
                    echo "<div class='alert alert-success'>
                  student is promoted to same class successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll number of student.</span>";
              }

              }
            else{
              echo "<div class='alert alert-success'>
                  Something went wrong.
                </div>";
            } 


              //echo json_encode($data1);
        }
      }
       

}

         public function terminate_stu(){
          
          $data1 = $this->input->post('check'); //this returns an array so use foreach to extract data
          $reason = $this->input->post('reason');

           $staff = $this->session->userdata('staff_Org_id');

           $data1 = explode(' ',$data1);

          foreach($data1 as $key => $value){
                  $sql = "select * from `same_class_students` WHERE student_id ='".$value."'and iid=".$staff;

                  $query = $this->db->query($sql);
                  $query= $query->row();

          }

          if(strlen($reason) ==  0)
            {

              echo '* Provide Reason of Termination';
            }

            else if($value == $query->student_id){
                    if(count($data1) > 1){
                    echo "* Already students are promoted to same class!";
                  }
                  else{
                    echo "* Already student is promoted to same class!";
                  }
                  }

else{
          //$data1 = explode(' ',$data1);
      
            foreach($data1 as $key => $value){

                   //echo $value.' '."</br>";

              $sql="SELECT * FROM `student` WHERE student_id ='$value'";
              $query = $this->db->query($sql);
              $query= $query->row();

                  $sql = "Insert into `x_student`
(student_id,iid,userid,name,birthday,sex,address,phone,email,password,father_name,mother_name,class_id,section_id,parent_id,roll,photo,locality,district,id_proof,proofid,bloodgroup,bday,caste,admission_no,religion,moles,admission_date,status,reason) values('$query->student_id','$query->iid','$query->userid','$query->name','$query->birthday','$query->sex','$query->address','$query->phone','$query->email','$query->password','$query->father_name','$query->mother_name','$query->class_id','$query->section_id','$query->parent_id','$query->roll','$query->photo','$query->locality','$query->district','$query->id_proof','$query->proofid','$query->bloodgroup','$query->bday','$query->caste','$query->admission_no','$query->religion','$query->moles','$query->admission_date','$query->status','$reason')";
                  $query = $this->db->query($sql);
                  //$query= $query->row();
                  //print_r($query);
                  //$sql1 = "Insert into `x_student`(reason) values('".$reason."')";
                 //$query1 = $this->db->query($sql1);
                  $sql2 = "DELETE FROM `student` WHERE student_id =".$value;
                  $query2 = $this->db->query($sql2);
                  

          }

          if($query && $query2){
                  
                  if(count($data1) > 1){
                  echo "<div class='alert alert-success'>
                  students are Terminated successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll numbers of students.</span>";
              }
              else{
                 echo "<div class='alert alert-success'>
                  student is Terminated successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll number of student.</span>";
              }

              }
            else{
              echo "<div class='alert alert-success'>
                  Something went wrong.
                </div>";
            } 


              //echo json_encode($data1);
        }

}

public function promote_stu(){
          
          $data1 = $this->input->post('check'); //this returns an array so use foreach to extract data
          $class = $this->input->post('pclass');
          $section = $this->input->post('section1');
          if($class ==  0)
            {
              echo '* Please select class';
            }


        else if($section ==  0)
            {
              echo '* Please select section';
            }

      else{
          $data1 = explode(' ',$data1);

          $staff = $this->session->userdata('staff_Org_id');

          foreach($data1 as $key => $value){
                  $sql = "select * from `same_class_students` WHERE student_id ='".$value."'and iid=".$staff;

                  $query = $this->db->query($sql);
                  $query= $query->row();

          }

          if($value == $query->student_id){
                    if(count($data1) > 1){
                    echo "* Already students are promoted to same class!";
                  }
                  else{
                    echo "* Already student is promoted to same class!";
                  }
                  }
            else{
          
            foreach($data1 as $key => $value){

                   //echo $value.' '."</br>";
               
                  $sql = "UPDATE `student` SET `class_id`='$class',`section_id`='$section' where student_id=".$value;

                  $query = $this->db->query($sql);

          } 

           if($query){

                if(count($data1) > 1){

                    echo "<div class='alert alert-success'>
                  students are promoted to another class successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll numbers of students.</span>";

                } else{
                  
                  echo "<div class='alert alert-success'>
                  student is promoted to another class successfully!
                </div>
                <span style='color:red;'>*Note: Please change roll number of student.</span>";

                }

              }
            else{
              echo "<div class='alert alert-success'>
                  Something went wrong.
                </div>";
            }


              //echo json_encode($data1);
          }
        }

}
        private function fetch_student_result($sclass,$section){
         // echo $admission_no;
            $query= "SELECT * FROM `student` WHERE  iid='".$this->session->userdata('staff_Org_id')."'  ";
            $condition="";
            
               $condition =" (`class_id` LIKE '%".$sclass."%' AND `section_id` LIKE '%".$section."%')";
           
           if(strlen($condition)!=0){
                $query =$query." AND ".$condition;
           }
        //  echo  $query;
           $query =  $this->db->query($query);
           return $query->result();
        }
        
        public function add(){
            $this->load->view('students/create');
        }
        
        public function edit($stud=""){
            $stud=trim($stud);
            if(strlen($stud)==0){
               redirect('students/add', 'refresh'); 
            }else{
                $s=$this->is_student($stud);
                if(!$s){
                  redirect('students/add', 'refresh'); 
                }else{
                    $data['student']=$s;
                    $this->load->view('students/edit',$data);
                }
            }
            
        }
        
        
        public function bulk_add(){
            $this->load->view('students/bulkcreate');
        }
        
        public function view_details($studid=""){
            if($studid==""){
                 $this->load->view('students/index');   
            }else{
               $student=  $this->fetch_student_details($studid);
              
               if(!$student){
                    $this->load->view('students/index');   
               } else{
                   $data['student']=$student;
                   $this->load->view('students/details',$data);   
               }
            }
            
        }
        
        public function View($class="",$section=""){
            $section=  urldecode($section);
            if( ($class=="") || ($section=="")){
                 $this->load->view('students/brief_report');
       
            }elseif(!is_numeric($class)){
                  $this->load->view('students/brief_report');
            }else{
                $section=  $this->check_class_section($class,$section);
                if(!$section){
                      $this->load->view('students/brief_report');
                }else{
                    $students=  $this->fetch_students($section->sid);
                    $data['section']=$section;
                    $data['students']=$students;
                    $this->load->view('students/view_students',$data);
                }
            }            
        }
        
        public function Send_sms($class="",$section=""){
            $section=  urldecode($section);
            if( ($class=="") || ($section=="")){
                 $this->load->view('students/brief_report');
            }elseif(!is_numeric($class)){
                  $this->load->view('students/brief_report');
            }else{
                $section=  $this->check_class_section($class,$section);
                if(!$section){
                      $this->load->view('students/brief_report');
                }else{
                    $students=  $this->fetch_students($section->sid);
                    $data['section']=$section;
                 $this->load->view('students/send_sms',$data);
                }
            }            
        }
        
        public function preview_sms(){
            $message= $this->input->post('message');
            $section= json_decode($this->input->post('section'));
            $students=  $this->fetch_students($section);
            foreach( $students as $value){
                $stud_message=  str_replace('#NAME#', $value->name, $message);
                $stud_message=  str_replace('#MOBILE#', $value->phone, $stud_message);
                $stud_message=  str_replace('#USERID#', $value->userid, $stud_message);
             ?>
                <tr>
                    <td><?php echo $value->name  ?></td>
                    <td><?php echo $stud_message ?></td>
                </tr>

             <?php
            }
            
        }
        
        
        public function send_msg(){
            $message= $this->input->post('message');
            $section= json_decode($this->input->post('section'));
            $students=  $this->fetch_students($section);
            $msg_count =0;
            $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>1,
                    'message' =>"Sending Student Alerts",
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
          $sms_details=  $this->studparent->fetch_sms_details();
          
            foreach( $students as $value){
                $stud_message=  str_replace('#NAME#', $value->name, $message);
                $stud_message=  str_replace('#MOBILE#', $value->phone, $stud_message);
                $stud_message=  str_replace('#USERID#', $value->userid, $stud_message);
               // $this->studparent->send_sms($stud_message,$value->phone);
                $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'username'=>$sms_details->username,
                    'password' =>$sms_details->password,
                    'senderid' =>$sms_details->senderid,
                    'message'  =>$stud_message,
                    'mobile' =>$value->phone,
                    'time' =>time(),
                    'status' =>1,
                    'alert_id' =>$aid,
                );
        $this->db->insert('msg_senthistory',$data);

                $msg_count++;
            }
          $this->session->set_userdata('Send_student_msg', 'Sucessfully sent message , Message Count :'.$msg_count);  
          ?><script>window.location.reload();</script><?php
            
        }
        
        
        private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
        
        public function print_students($class="",$section=""){
           $section=  urldecode($section);
            if( ($class=="") || ($section=="")){
                 $this->load->view('students/brief_report');
            }elseif(!is_numeric($class)){
                  $this->load->view('students/brief_report');
            }else{
                $section=  $this->check_class_section($class,$section);
                if(!$section){
                      $this->load->view('students/brief_report');
                }else{
                    $students=  $this->fetch_students($section->sid);
                    $institute =  $this->fetch_institute_details();
                    
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Attendance Brief Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
                    
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
</style>
</head>
<body>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="148" height="60" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />';
                           
 ?>                    <h3 style="text-align: center"><strong>Student List of <?php echo $section->class." , ".$section->section ?></strong></h3>
                                  
                                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
           
                                            <tr>
                                                <th>Roll</th>
                                                <th>Student Name</th>
                                                <th>Admission No</th>
                                                <th>Gender</th>
                                                <th>Mobile</th>
                                                <th>E-mail</th>
                                            </tr>
                                            <?php
                                               foreach($students as $value){
                                                   ?>
                                                           <tr>
                                            <td><?php echo $value->roll ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><?php echo $value->userid ?></td>
                                            <td><?php if($value->sex==1)
                                                        {
                                                            echo "Male";
                                                        }else{
                                                            echo "Female";
                                                        }
                                                        ?></td>
                                            <td><?php echo $value->phone ?></td>
                                            <td><?php echo $value->email ?></td>
                                         </tr>
                                                  <?php
                                               } 
                                            ?>
                                    </table>
                                    
                                    </div>
                                </body>
                                <script>
                                    window.print();
                                    </script>
                            </html>
                    <?php
                }
            }  
        }
        
        
        public function create(){
           
            if(strlen($this->input->post('submit'))==0){
                redirect('students/add','refresh');
            }
            $post=$this->operations->cleanInput($_POST);
                    
            $trans_cert =0;$tc_issue_date="";$tc_serial_no="";
           //Document Details Validations 
            $field = 'transfer_cert';
            $tc_issue_date="";
            if(isset($post[$field])){
                  $trans_cert =1;
                  //tc_issue_date 
                  $tc_issue_date="";
                    $field ="tc_issue_date";
                    if(strlen($post[$field])==0){
                         //   $this->form->setError($field,'* Provide TC Issue Date');
                    }else{
                        $str=explode("/",$post[$field]);
                        $tc_issue_date= mktime(0,0,0,$str[1],$str[0],$str[2]);
                        if($tc_issue_date >time()){
                            $this->form->setError($field,'* Select Valid Date Of TC Issue Date');
                        }
                    }
                  //tc_serial_no
                    $field ="tc_serial_no";
                    if(strlen($post[$field])==0){
                            $this->form->setError($field,'* Provide TC Serial No');
                    }else{
                        $tc_serial_no = $post[$field];
                    }
                    
            }
            
            $field = 'Bonafide_cert';$bonafide_Cert="";
            if(isset($post[$field])){
                $bonafide_Cert=1;
            }
            
            $field = 'caste_cert';$caste_Cert="";
            if(isset($post[$field])){
                $caste_Cert=1;
            }
            
            $field = 'income_cert';$income_cert="";
            if(isset($post[$field])){
                $income_cert=1;
            }
           
            
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
                 //     $this->form->setError($field,'* valid Admission No');
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
            
            
            $field ="admission_date";
            $admission_date="";
            if(strlen($post[$field])==0){
                 //   $this->form->setError($field,'* Provide D.O.A');
            }else{
                $str=explode("/",$post[$field]);
                $admission_date= mktime(0,0,0,$str[1],$str[0],$str[2]);
                if($admission_date >time()){
                    $this->form->setError($field,'*Invalid D.O.A');
                }
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
               //$this->form->setError($field,'* Provide Caste');
            }
            
            $field = 'state';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide state');
            }
            
            $field = 'district';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide district');
            }
            $field = 'Locality';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide Locality');
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
            
           $field = 'stbg';
           $bg="";
            if(strlen($post[$field]) ==  0)
            {
                //    $this->form->setError($field,'* Select Blood Group');
            }    else{
              $bg = $post[$field];  
            }
            
            //moles
            $field = 'moles';
            if(strlen($post[$field]) ==  0)
            {
                $this->form->setError($field,'*Please Enter Identification Moles');
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
            //  $this->form->setError($field,'* Select Id proff');
          
               if((strlen($post['proffid']) !=  0)){
                   //$this->form->setError($field,'* Select Id proff');
               }
            }else{
               if((strlen($post['proffid']) ==  0)){
                  // $this->form->setError('proffid','* enter id Proof');
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
                   // $this->form->setError($field,'* provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
               $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
               $bod_day = getdate($dob);
               $bod_day =$bod_day['mday']."/".$bod_day['mon'];
                if($dob >time()){
                    $this->form->setError($field,'* select a valid Date Of Birth');
                }
            }
            
            
            $field = 'stdphone';
		if(strlen($post[$field]) ==  0)
		{
			$this->form->setError($field,'* provide Mobile Number');
		}
		elseif( $this->validations->validate_mobile($post[$field])==0 )
		{
			$this->form->setError($field,'* enter valid Mobile Number  ');
                        
		}
            
          //stemail
          $field = 'stdemail';
		if(strlen($post[$field]) ==  0)
		{
		  	//$this->form->setError($field,'* provide E-mail');
		}
		elseif( $this->validations->validate_email($post[$field])==0 )
		{
			$this->form->setError($field,'* enter Valid E-mail id  ');
		}
            $field = 'prntphone';
		if(strlen($post[$field]) ==  0)                    
		{
                    $post[$field]=$post['stdphone'];
			$this->form->setError($field,'* provide Mobile Number');
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
          //   print_r($_SESSION);
            // exit;
             
             redirect('students/add', 'refresh'); 
         }else{
             $pass=$this->validations->generate_password();
             $file ="";
                  if($img_uplod==2){
                            $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."drv_".time().".".$ext; 
                            $config['upload_path']   = upload_path; 
                            $config['allowed_types'] = 'gif|jpg|png'; 
                            $this->load->library('upload', $config);
                            $this->upload->do_upload('stphoto');
                    }
                    if($img_uplod==1){
                        $file= $this->session->userdata('staff_Org_id')."_std_".time().".jpg"; 
                        $this->session->unset_userdata('driver_dummy_img');
                        copy(upload_path.'/temp/'.$this->session->userdata('student_dummy_img'),upload_path.$file);
                    }
              $parent_id="SELECT * from parent where iid= '".$this->session->userdata('staff_Org_id')."' AND phone= '".$this->input->post('stdphone')."' ";   
           $parent_id = $this->db->query($parent_id);
           if($parent_id->num_rows()>0){
               $parent_id=$parent_id->row();
               $parent=$parent_id->parent_id;
           }else{
               $data = array(
                   'iid' =>$this->session->userdata('staff_Org_id'),
                   'name' =>$post['stdfather'],
                   'email' =>$post['prntemail'],
                   'password' =>$pass,
                   'phone' =>$post['stdphone'],
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
   

              $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'name' => $post['stdname'],
                       'userid' => $userid,
                       'admission_no' => $post['userid'],
                       'birthday'=>$dob,
                       'sex' => $post['stdsex'],
                       'address'=>$post['stdaddress'],
                       'phone' => $post['stdphone'],
                       'email'=> $this->input->post('stdemail'),
                        'password'=>$pass,
                        'parent_id'=>  $parent,
                       'class_id' => $cls,
                       'section_id'=>$section,
                       'roll' => $post['stdroll'],
                       'photo'=>$file ,
                       'locality'=>$post['Locality'],
                        'district'=>$post['district'],
                        'father_name' =>$post['stdfather'],
                        'mother_name' =>$post['stdmother'],
                        'id_proof' =>$post['proffid'] ,
                        'proofid' =>$proof_id,
                        'bloodgroup' =>$bg,
                        'bday' =>$bod_day,
                        'caste' =>$post['caste'],
                        'religion'=>$post['religion'],
                        'moles' =>$post['moles'],
                        'admission_date'=>$admission_date
                    );
              
              $this->db->insert('student',$data);
              $std_id=$this->db->insert_id();
             
             $this->logs->insert_staff_log(5,'Created Student :'.$post['userid'],$std_id);
             $data = array(
                  'std_id' =>$std_id,
                  'iid' =>$this->session->userdata('staff_Org_id'),
                  'transfer' =>$trans_cert,
                  'bonafide' =>$bonafide_Cert,
                  'caste' =>$caste_Cert,
                  'income' =>$income_cert,
                 'tc_issue_date' =>$tc_issue_date,
                 'tc_serial_no' =>$tc_serial_no
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
               $this->update_admission_no($id);            
   
            $msgcontent="Dear Student,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                     . "url :http://ems.snetworkit.com/schooln/students \n username: ".$userid."\n Password : ".$pass;
             
            $msgcontent1="Dear Parent,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                     . "url : http://ems.snetworkit.com/schooln/parents \n username: ".$post['prntemail']."\n Password : ".$pass;

             $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['stdphone']."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

             $ret = file($url);

             $url1 = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['prntphone']."&from=School&message=".urlencode($msgcontent1);    //Store data into URL variable

                $ret1 = file($url1);    //Call Url variable by using file() function

            if($ret && $ret1){

             $this->studparent->send_sms($msgcontent,$post['stdphone']);
             $this->studparent->send_sms($msgcontent1,$post['prntphone']);
            }

            $this->session->set_userdata('student_add_Sucess', 'Sucessfuylly Created Student ');
        

        }
          
        redirect('students/add', 'refresh');
        }
        
        //edit_stud
        
        public function edit_stud(){
          
            $post=$this->operations->cleanInput($_POST);
         
           $studid=$post['studid'];
            $field = 'stdname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Provide Student Name');
            }
            
            $field = 'stdmother';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide Student Mother Name');
            }
            $field = 'stdfather';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide Student Father Name');
            }
            //stddob
            
            $field = 'stddob';
            if(strlen($post[$field]) ==  0)
            {
                  //  $this->form->setError($field,'* provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
               $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                if($dob >time()){
                    $this->form->setError($field,'* select a valid Date Of Birth');
                }
            }
            //admission_date
            $field = 'admission_date';
            $doa="";
            if(strlen($post[$field]) ==  0)
            {
                   // $this->form->setError($field,'* provide Date Of Admission');
            }else{
                $str=explode("/",$post[$field]);
               $doa= mktime(0,0,0,$str[1],$str[0],$str[2]);
                if($doa >time()){
                    $this->form->setError($field,'* Select a valid Date Of Admission');
                }
            }         
            
            $field = 'stbg';
            $bg="";
            if(strlen($post[$field]) ==  0)
            {
                 //   $this->form->setError($field,'* Please Select Blood Group');
            }else{
                $bg=$post[$field];
            }
            
            $field = 'caste';
            $caste="";
            if(strlen($post[$field]) ==  0)
            {
               // $this->form->setError($field,'* Please Select Caste');
            }else{
                $caste=$post[$field];
            }
            
            $field = 'stdsex';
            $sex="";
            if(strlen($post[$field]) ==  0)
            {
                $this->form->setError($field,'* Please Select Gender');
            }else{
                $sex=$post[$field];
            }
            $field = 'religion';
            $religion="";
            if(strlen($post[$field]) ==  0)
            {
                //$this->form->setError($field,'* Please Select Religion');
            }else{
                $religion=$post[$field];
            }
            //moles
            $field = 'moles';
            if(strlen($post[$field]) ==  0)
            {
              //  $this->form->setError($field,"* Provide Moles For Identification.");
            }
            
            
            $field = 'stdphone';
            if(strlen($post[$field]) ==  0){
                 //   $this->form->setError($field,'* provide Mobile Number');
            }elseif( $this->validations->validate_mobile($post[$field])==0 ){
                    $this->form->setError($field,'* enter valid Mobile Number  ');
            }
            
            $field = 'stdaddress';
            if(strlen($post[$field]) ==  0)
            {
               // $this->form->setError($field,"* Please Provide Address ");
            }
            //medium
            $field = 'medium';
            if(strlen($post[$field]) ==  0){
                //    $this->form->setError($field,'* provide Medium Of Study');
            }
            //userid
            $field = 'userid';
            if(strlen($post[$field]) ==  0){
                //    $this->form->setError($field,'* Please Provide Admission ');
            }elseif(!$this->check_admission_no($post[$field],$post['studid'])){
                 $this->form->setError($field,'* Admission No Already exist ');
            }
              
          //stemail
          $field = 'stdemail';
            if(strlen($post[$field]) ==  0){
                  //  $this->form->setError($field,'* provide E-mail');
            }elseif( $this->validations->validate_email($post[$field])==0 )
            {
                    $this->form->setError($field,'* enter Valid E-mail id  ');
            }
            
            $std_cls=$std_sec="";
            $field = 'stdclass';
            if(strlen($post[$field]) ==  0){
            $this->form->setError($field,'* Please Select Class');
            }else{
                $std_cls=explode("-",$post[$field]);
                $std_sec=$std_cls[1];
                $std_cls=$std_cls[0];
                    $field='stdroll';
                    if(strlen($post[$field]) ==  0)
                    {
                   //    $this->form->setError($field,'* Enter Roll No');
                    }elseif(!$this->studparent->checkstdroll($std_cls,$std_sec,$post[$field],$post['studid'])){
                     //   $this->form->setError($field,'* Roll No Already Assigned');
                    }
                }
          
            $proof_id="";
            $field = 'id_proff';
          
            if( strlen($post[$field]) ==  0 )
            {
            //  $this->form->setError($field,'* Select Id Proof');
          
               if((strlen($post['proffid']) !=  0)){
              //     $this->form->setError($field,'* Select Id proff');
               }
            }else{
               if((strlen($post['proffid']) ==  0)){
                   $this->form->setError('proffid','* Enter Id Proof');
               }else{
                   switch($post[$field]){
                        case 1 : $id =1;
                                if( (strlen($post['proffid'])!=12) || (!is_numeric($post['proffid'])) ){
                                   $this->form->setError('proffid','* Enter Valid Aadhar No');
                                  }else{
                                      $proof_id =$post['proffid'];
                                  }break;
                        case 2 : $id =2;
                                 if( (strlen($post['proffid'])!=8)  ){
                                   $this->form->setError('proffid','* Enter Passport No  ');
                                  }else{
                                      $proof_id =$post['proffid'];
                                  }
                                  break;
                        default :$this->form->setError($field,'* Select Valid Id Proff');                                  
                                   break;
                   }
               } 
            }
            
            $trans_cert =0;$tc_issue_date="";$tc_serial_no="";
           //Document Details Validations 
            $field = 'transfer_cert';
            if(isset($post[$field])){
                  $trans_cert =1;
                  //tc_issue_date                  
                    $field ="tc_issue_date";
                    if(strlen($post[$field])==0){
                            $this->form->setError($field,'* Provide TC Issue Date');
                    }else{
                        $str=explode("/",$post[$field]);
                        $tc_issue_date= mktime(0,0,0,$str[1],$str[0],$str[2]);
                        if($tc_issue_date >time()){
                            $this->form->setError($field,'* Select Valid Date Of TC Issue Date');
                        }
                    }
                  //tc_serial_no
                    $field ="tc_serial_no";
                    if(strlen($post[$field])==0){
                            $this->form->setError($field,'* Provide TC Serial No');
                    }else{
                        $tc_serial_no = $post[$field];
                    }
                    
            }
            
            $field = 'Bonafide_cert';$bonafide_Cert=0;
            if(isset($post[$field])){
                $bonafide_Cert=1;
            }
            
            $field = 'caste_cert';$caste_Cert=0;
            if(isset($post[$field])){
                $caste_Cert=1;
            }
            
            $field = 'income_cert';$income_cert=0;
            if(isset($post[$field])){
                $income_cert=1;
            }
           
            
            $field = 'state';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide state');
            }
            
            $field = 'district';
          
            if(strlen($post[$field]) ==  0)
            {
              // $this->form->setError($field,'* Provide district');
            }
            $field = 'Locality';
            if(strlen($post[$field]) ==  0)
            {
            //   $this->form->setError($field,'* Provide Locality');
            }
                
//           $field = 'stdparent';
//            if(strlen($post[$field]) ==  0)
//            {
//              // $this->form->setError($field,'* Select Parent');
//            }
            
               
            $field = 'stdimage';
            $image=1;
            if(strlen($_FILES[$field]['name']) >0){
	        $ext=explode(".",$_FILES['stdimage']['name']);
                $ext= strtolower($ext[sizeof($ext)-1 ]);
                $img_arr=array('jpg','jpeg','gif','png');
                if(!in_array($ext,$img_arr)){
                    $this->form->setError($field,'* Select a Image file');
                }
                  
            }else{
                $image=0;
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                    redirect('students/edit/'.$studid, 'refresh'); 
                }else{
                    $file="";
                    if($image==1){
                        $ext=explode(".",$_FILES['stdimage']['name']);
                        $ext=$ext[sizeof($ext)-1 ];
                        $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."_std_".time().".".$ext; 
                        $config['upload_path']   = '/var/www/html/schooln/assests_2/uploads/'; 
                        $config['allowed_types'] = 'gif|jpg|png'; 
                        $this->load->library('upload', $config);
                        $this->upload->do_upload('stdimage');
                    }
                    $dbay= getdate($dob);
                    //bloodgroup
                    $dbay = $dbay['mday']."/".$dbay["mon"];
                     $data = array(
                       'name' => $this->input->post("stdname"),
                       'father_name'=>$this->input->post("stdfather"),
                       'mother_name'=>$this->input->post("stdmother"),
                       'birthday'=>$dob,
                       'sex' => $sex,
                       'address'=>$this->input->post("stdaddress"),
                       'phone' => $this->input->post("stdphone"),
                       'email'=>  $this->input->post("stdemail"),
                       'class_id' => $std_cls,
                       'section_id'=>$std_sec,
                       'roll' => $post['stdroll'],
                       'locality'=>$this->input->post("Locality"),
                       'district'=>$this->input->post("district"),
                       'id_proof'=>$this->input->post("id_proff"),
                       'proofid'=>$proof_id,
                       'bloodgroup'=>$bg,
                       'caste'=>$caste,
                       'bloodgroup'=>$bg,
                       'admission_no'=>$this->input->post("userid"),
                       'religion' =>$religion,
                       'moles'=>  $this->input->post("moles"),
                       'admission_date'=>  $doa,
                      );
                     
                     if(strlen($file)>0){
                       $data['photo']=$file;  
                     }
                    
                    $this->db->where('student_id',$post['studid']);
                    $this->db->update('student', $data); 
                     $data = array(
                                'transfer' =>$trans_cert,
                                'bonafide' =>$bonafide_Cert,
                                'caste' =>$caste_Cert,
                                'income' =>$income_cert,
                               'tc_issue_date' =>$tc_issue_date,
                               'tc_serial_no' =>$tc_serial_no
                            );
                    
                    
                    $this->db->where('std_id',$post['studid']);
                     $this->db->update('stud_documents', $data); 
                    $this->logs->insert_staff_log(5,'Updated Student :'.$post['userid'],$studid);
                    $this->session->set_userdata('student_edit_Sucess', 'Updated Student records');
                }
      //         
                    redirect('students/view_details/'.$this->input->post("student_login_id"), 'refresh'); 
       
        }
        
        public function search(){
            if(isset($_GET['text']) ||isset($_GET['id']) ||isset($_GET['admission_no'])  ){                
                $txt="";
                $id="";$admission_no="";
                if(  (strlen( $_GET['text'] ) ==0) && (strlen($_GET['id']) ==0) && (strlen($_GET['admission_no']) ==0) ) {
                     $data['result']=FALSE;
                     $data['error']="provide keys for searching"; 
                }else{
                    if(strlen(trim($this->input->get('text')))!=0 ){
                      $txt =trim($_GET['text']); 
                    }
                    if(strlen(trim($this->input->get('id')))!=0 ){
                      $id =trim($_GET['id']); 
                    }
                    if(strlen(trim($this->input->get('admission_no')))!=0 ){
                     $admission_no =trim($_GET['admission_no']); 
                    }
                   // echo $txt ."<br/>".$id."<br/>".$admission_no;
                    $data['result']=  $this->fetch_search_result($txt,$id,$admission_no);
                    $data['error']=""; 
                }
            }else{
                $data['result']=FALSE;
                $data['error']="";    
            }
            $this->load->view('students/search',$data);
        }
        
        public function load_district(){
             
            $data =$this->db->query("SELECT id,district FROM `districts` where st_id = '".$this->input->post("state_id")."' ") ;
            $data = $data->result();
            ?>
            <option value="">SELECT DISTRICT</option> 
            <?php
                foreach ($data as $value) {
                    ?><option value="<?php echo  $value->id ?>" ><?php echo $value->district  ?></option><?php
                }
           
        }
        
        public function create_bulk(){
            
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
			$file_pointer = fopen($file['tmp_name'], "r");
			$file_read = fread($file_pointer, $file['size']);
			
			$newdata=$file_read;
			$ex=explode("\n",$newdata);
                       
                        if(strlen(trim($ex[sizeof($ex)-1]))==0){
                           array_pop($ex);
                        }
                        
                        if(trim($ex[0]) == "name,roll,birthday,gender,phone,email,admission,father,mother,address"){
                          array_shift($ex); 
                        }
                        
			$counter= 0;
			$name = '';
			$i = 0;
			$values="";
                        $id=$this->get_admission_no();
                        $code=$this->studparent->get_institute_code();
                        $dummy=array();
                        $login_url =base_url();
                        $login_url = str_replace("/office", "/students", $login_url);
			foreach($ex as $val)
			{
                            
                                $arr=explode(",",$val);
                                $arr = array_filter($arr);
                                $addr=$arr[9];
                                if(sizeof($arr)>10){
                                    $addr="";
                                    for($x=9;$x<=sizeof($arr)-1;$x++){
                                        $addr.=",".$arr[$x]; 
                                    }
                                }
                                                                
                                $str=explode("/",$arr[2]);
                                $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                                $phone=$arr['4'];
                                $sex=1;
                                if(strtolower(trim($arr['3']))!='male' ){
                                    $sex=2;   
                                }
                                if( $this->validations->validate_mobile($phone)==0 )
                                {
                                        $phone="";
                                }if(!$this->studparent->checkstdroll($post['bstdclass'],$post['bstdsection'],$arr['1'])){
                                    $arr['error']="Roll No already Assigned";  
                                    $dummy[]=$arr;
                                }else{
                                    if(preg_match("/^[a-zA-Z0-9]+$/", $arr['6']) != 1) {
                                        $arr['error']="Invalid Admission Number"; $dummy[]=$arr; 
                                      }elseif(!$this->check_admission_no($arr['6'])){
                                             $arr['error']="Admission Number Already Assigned"; $dummy[]=$arr; 
                                           }else{
                                            $counter++;
                                            $userid=$code.$this->make_5digit($id);
                                            $id++;
                                            $pass=$this->studparent->generate_pass();
                                           $data = array(
                                                  'iid' => $this->session->userdata('staff_Org_id'),
                                                  'name' => $arr['0'],
                                                  'userid'=>$userid,
                                                  'admission_no'=>$arr['6'],
                                                  'birthday'=>$dob,
                                                  'sex' => $sex,
                                                  'father_name' =>$arr['7'],
                                                  'mother_name' =>$arr['8'],
                                                  'address'=>$addr,
                                                  'phone' => $arr['4'],
                                                  'email'=>$arr[5],
                                                  'password'=>$pass,
                                                  'class_id' => $post['bstdclass'],
                                                  'section_id'=>$post['bstdsection'],
                                                  'roll' => $arr['1'],
                                                 );

                                          $this->db->insert('student',$data); 
                                         $this->logs->insert_staff_log(5,'Created Student : '.$arr['6'],  $this->db->insert_id());           
                                            $msgcontent="Dear Student,\n Your Account Created Sucessfully\n please check Your login credential\n"
                                    . "url : http://ems.snetworkit.com/schooln/students/ \n username: ".$userid."\n Password : ".$pass;

                                    $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$arr['4']."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

                                    $ret = file($url);

                                    if($ret){

                                           $this->studparent->send_sms($msgcontent,$arr['4']);

                                        }
                        
                                         }
 			}
                        }

                        $this->update_admission_no($id-1);$this->session->unset_userdata("student_count");
                        $this->session->unset_userdata("student_count");
                       $this->session->set_userdata('bulkstd_add', $counter.' Student Records Created Sucessfully ');
                    if(sizeof($dummy)>0){
                      $_SESSION['dummy_data']=$dummy;  
                    }
		}
             redirect('students/bulk_add', 'refresh'); 
            
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
        
        public function fetch_section(){
              $cls=  $this->input->post('class');
              $section=  $this->input->post('section');
              $credential = array('iid'=>$this->session->userdata('staff_Org_id') ,'class_id' =>$cls );
              $classes = $this->db->get_where('section', $credential)->result_array();	
                ?>
              <option value="">Select Section</option>
                <?php
              foreach($classes as $row){
                  ?>
                  <option value="<?php echo $row['sid'];?>" <?php
                  if($section==$row['sid']){
                      echo "selected";
                  }  ?> >
                  <?php echo $row['name'];?>
                  </option>
                  <?php
              }
          }
        
        public function fetch_section1(){
              $cls=  $this->input->post('class');
              $section=  $this->input->post('section');
              $credential = array('iid'=>$this->session->userdata('staff_Org_id') ,'class_id' =>$cls );
              $classes = $this->db->get_where('section', $credential)->result_array();	
                ?>
              <option value="">Select Section</option>
                <?php
              foreach($classes as $row){
                  ?>
                  <option value="<?php echo $row['sid'];?>" 
                          <?php  
                            if($section==$row['sid']){
                                echo 'selected="selected"';
                            }
                          ?> >
                  <?php echo $row['name'];?>
                  </option>
                  <?php
              }
          }
        
        private function fetch_class_id($sec){
             $query = $this->db->query("SELECT c.id as class FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND s.sid='".$sec."' ")->row();	
             return $query->class;
        }
          
        private function check_class_section($class,$section){
          $query=  $this->db->query("SELECT s.sid,s.name as section ,c.name as class , c.numeric_val , c.id as class_id  FROM `section` s  JOIN class c ON s.class_id =c.id WHERE ( c.id='".$class."' ) AND (s.name  LIKE  '%".$section."%') AND  (s.iid='".$this->session->userdata('staff_Org_id')."' )");
          if($query->num_rows()>0){
              $query=$query->row();
              return $query;
          }else{
              return FALSE;
          }
       }   
                                                             
        private function check_admission_no($userid,$stud=0){
            if($stud==0){
             $query=  $this->db->query("SELECT *  FROM student where admission_no='".$userid."'AND iid='".$this->session->userdata('staff_Org_id')."' ");
            }else{
                $query=  $this->db->query("SELECT *  FROM student where admission_no='".$userid."'AND student_id!='".$stud."'  AND  iid='".$this->session->userdata('staff_Org_id')."' ");
            }
            if($query->num_rows()>0){
                return FALSE;
            }else{
                return TRUE;
            }

       }
       
        private function fetch_students($section){
            $query=  $this->db->query("SELECT * FROM `student` WHERE section_id='".$section."' AND iid='".$this->session->userdata('staff_Org_id')."'");
            $query=$query->result();
            return $query;
        }
       
        private function fetch_student_details($studid){
           $query= $this->db->query("SELECT s.*,p.name as parent,p.email as prnt_email,p.password as prnt_password,p.phone as prnt_phone,p.parent_id as parentid,c.name as class,se.name as section FROM `student` s LEFT JOIN parent p ON s.parent_id=p.parent_id JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id=se.sid WHERE s.userid='".$studid."' AND s.iid='".$this->session->userdata('staff_Org_id')."'");
        
         if($query->num_rows()>0){
              $query=$query->row();
              
              return $query;
          }else{
              return FALSE;
          }
         
        }
        
        private function is_student($stud){
            $query=  $this->db->query("SELECT * FROM `student` where student_id='".$stud."' and  iid='".$this->session->userdata('staff_Org_id')."' ");
            if($query->num_rows()>0){
               $query=$query->row(); 
               return $query;
            }else{
                return FALSE;
            }
        }
        
        private function fetch_search_result($text,$id,$admission_no){
         // echo $admission_no;
            $query= "SELECT * FROM `student` WHERE  iid='".$this->session->userdata('staff_Org_id')."'  ";
            $condition="";
            if(strlen($text)!=0){
               $condition =" (`name` LIKE '%".$text."%' OR `address` LIKE '%".$text."%' OR `phone` LIKE '%".$text."%' OR `email` LIKE '%".$text."%' )";
           }
           if(strlen($id)!=0){
               if(strlen(trim($condition))!=0){
                   $condition .=" AND (`userid` LIKE '%".$id."%' )";
               }else{
                   $condition =" (`userid` LIKE '%".$id."%' )";
               }
               
           }
           if(strlen($admission_no)!=0){
               if(strlen(trim($condition))!=0){
                   $condition .=" AND (`admission_no` LIKE '%".$admission_no."%' )";
               }else{
                   $condition =" (`admission_no` LIKE '%".$admission_no."%' )";
               }
               
           }
           if(strlen($condition)!=0){
                $query =$query." AND ".$condition;
           }
        //  echo  $query;
           $query =  $this->db->query($query);
           return $query->result();
        }
        
        public function ajax_library(){
            $student=  $this->check_student($this->input->post('student'))
            ?>
            
                    <table  class="table table-bordered table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Issue ID</th>
                                <th>Book Name</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Fine</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php
                         $query=  $this->db->query("SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,lb.name as book FROM `lib_issues` li  JOIN lib_books lb ON li.bookid=lb.book_id   WHERE li.student_id='".$student->student_id ."' AND li.iid='".$this->session->userdata('parent_org_id')."' ");
                        ?>
                        <tbody>
                            <?php
                            if($query->num_rows()>0 ){
                               $i=1;
                             $query=$query->result();                          
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><?php  echo $value->trans_id;?></td>
                                <td><?php  echo $value->book;?></td>
                                <td><?php  echo date("d-m-Y", $value->issued_date);?></td>
                                <td><?php  
                                 if($value->return_date ==0){
                                    echo "--";                                    
                                  }else{
                                      echo date("d-m-Y",$value->return_date);
                                  }
                                ?></td>
                                
                                <td><?php  echo $value->fine;?></td>
                                <td><?php  
                                if($value->status==1){
                                                       echo "Not Returned";
                                                   }else{
                                                       echo "Returned";
                                                   }
                                ?></td>
                            </tr>
                                <?php
                             
                              } 
                            }else{
                                ?>
                            <tr><td colspan="7">No Records Found</td></tr>
                                <?php
                            }
                            
                            
                            ?>
                            
                        </tbody>
                    </table>
             <hr/>
             <h4><i class="fa fa-th-inr"></i>&nbsp;Library Fine List</h4>   
               
                    <table class="table table-bordered table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Issue id</th>
                                <th>Time</th>
                                <th>Description</th>
                                <th>Fee</th>
                                <th>Paid Amount</th> 
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $query="SELECT i.trans_id,f.fee,f.time,f.description,f.status,sum(p.amount) as paid FROM lib_fines f JOIN lib_issues i ON f.issue_id =i.issue_id JOIN lib_payments p ON p.fee_id=f.fine_id  WHERE i.student_id='".$student->student_id ."' AND i.iid='".$this->session->userdata('parent_org_id')."'";
                              $query=  $this->db->query($query);
                              if($query->num_rows()>0 ){
                                 $query=$query->result();
                                 $i=1;
                                 foreach($query as $value){
                                   ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value->trans_id ?></td>
                                        <td><?php echo $value->time ?></td>
                                        <td><?php echo $value->description ?></td>
                                        <td><?php echo $value->fee ?></td>
                                        <td><?php echo $value->paid ?></td>
                                        <td><?php echo ($value->fee - $value->paid) ?></td>
                                    </tr>
                                   <?php
                                 }
                              }else{
                                 ?>
                            <tr>
                                <td colspan="6">No Records Found</td>
                            </tr>
                                <?php
                              }
                              
                            ?>
                        </tbody>                        
                    </table>
                
            <?php
        }
        
        public function ajax_fee(){
            $student=  $this->check_student($this->input->post('student'));
           // print_r($student);
            $cls_fee = $this->db->query("SELECT f.fid,f.fee,c.cid,c.category ,(select sum(amount)  from  fee_accounts a where  f.category = a.category AND a.student_id= '".$student->student_id."' ) as paid ,(select sum(amount)  from  fee_concession fc where  f.category = fc.cat_id AND fc.std_id='".$student->student_id."' ) as concession FROM `fee_class` f JOIN fee_category c ON f.category=c.cid WHERE f.cls_id='".$student->class_id."' ");
            $cls_fee = $cls_fee->result();
            $total=$tconcession=$tpaid=$tbalance=0;
               ?>
             <div class="box nopadding">
             <table class="table table-striped table-bordered">
                 <thead>
                     <tr>
                         <td colspan="5"style="text-align: center; color: #386ee0; font-weight:  bold">Fee Structure</td>
                     </tr>
                     <tr>
                         <th>Category</th>
                         <th>Fee</th>
                         <th>Paid</th>
                         <th>Concession</th>
                         <th>Balance</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        if(sizeof($cls_fee)==0){
                          ?>
                     <tr><td colspan="5" style=" text-align: center;">** Fee Structure Not Yet Created</td></tr>
                           <?php  
                        }else{
                            foreach ($cls_fee as $value) {
                               $total+=$value->fee;
                                $paid=0;
                                if(strlen($value->paid)!=0){
                                   $paid= $value->paid;
                                }
                                $concession=0;
                                if(strlen($value->concession)!=0){
                                   $concession= $value->concession;
                                }
                                $tpaid+=$paid;
                                $tconcession+=$concession;
                        ?>
                     <tr>
                         <td><?php echo $value->category ?></td>
                         <td><?php echo $value->fee ?></td>
                         <td><?php echo $paid ?></td>
                         <td><?php echo $concession ?></td>
                         <td><?php echo ($value->fee -$concession) -$paid ?></td>
                         
                     </tr>
                            <?php
                            }
                            ?>
                     <tr>
                         <th>Total Summary</th>
                         <th><?php echo $total ?></th>
                         <th><?php echo $tpaid ?></th>
                         <th><?php echo $tconcession ?></th>
                         <th><?php echo $total-$tpaid-$tconcession ?></th>
                     </tr>
                                <?php
                        }
                     ?>
                 </tbody>
             </table>
                           
                 <hr/>
                 <div style=" max-height: 150px;  overflow-y: scroll">
                          <h3 style="text-align: center; color: #386ee0; font-weight:  bold ; padding: 0px; margin: 0px; font-size: 19px">Fee Payments</h3>
                           
                     <table class="table table-hover table-bordered table-nomargin" >
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Recipt No</th>
                                 <th>Time</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <?php
                        $query=  $this->db->query("SELECT * FROM `fee_accounts` WHERE student_id='".$student->student_id ."' AND iid='".$this->session->userdata('staff_Org_id')."'  ");
                         $query=$query->result();
                        ?>
                        <tbody>
                            <?php
                            $i=1;
                            $t=0;
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/accounts/printout/<?php  echo $value->recipt;?>"><?php  echo $value->recipt;?></a></td>
                                <td><?php  echo date('d-m-Y',$value->time);?></td>
                                <td><?php  echo $value->amount;?></td>
                            </tr>
                            <?php
                              }
                            ?>
                            
                        </tbody>
                    </table>
                 </div>
                 
                  </div>
           <?php
        }
        
        public function ajax_timetable(){
            $student=  $this->check_student($this->input->post('student'));
            $timetable=  $this->db->query("SELECT * FROM `timings` WHERE section ='".$student->section_id."' ");
            if($timetable->num_rows() >0){
               ?>
               
                    <table class="table table-hover table-nomargin" >
                        <thead>
                            <tr>
                                <th>Day/timings</th>
                                <?php
                                
                                
                                $timetable=$timetable->row();
                                $weekdays = unserialize (Week_days);
                                $start=$timetable->start;
                                $noofc=$timetable->classes;
                                $span=$timetable->span;
                                $start=  mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2));
                                
                                for($i=1;$i<=$timetable->classes;$i++){
                                    $end=$start+($span*60);
                                    ?>
                                   <th><?php echo date("H:i",$start)  ?> - <?php echo date("H:i",$end)  ?></th>
                                    <?php
                                    $start=$end;
                                 }
                                
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                               $query=$this->db->query("SELECT cr.*,c.subid,s.subject FROM class_routine cr LEFT JOIN course c ON cr.course_id =c.cid LEFT JOIN subjects s ON c.subid=s.sid  WHERE cr.tid='".$timetable->tid."' ORDER BY cr.day asc,cr.time_start ASC ");
                                
                               if($query->num_rows()>0){
                               
                               $query=$query->result();
                                $prev="";$ids="";
                                foreach ($query as $value) {
                                    $ids.=$value->class_routine_id.",";
                                  if($prev!=$value->day){
                                    if($prev!=""){
                                        ?>
                                         </tr>
                                     <?php
                                    }
                                      ?>
                                    <tr>
                                        <th><?php echo $weekdays[$value->day] ?></th>
                                        <td><?php 
                                               if( strlen($value->subject)==0){
                                                   echo "--";
                                               }else{
                                                   echo $value->subject;
                                               }?></td>
                                        
                                    <?php
                                  }else{
                                      ?>
                                        <td>
                                            <?php 
                                               if( strlen($value->subject)==0){
                                                   echo "--";
                                               }else{
                                                   echo $value->subject;
                                               }?>
                                        </td>
                                        <?php
                                  }
                                   $prev =$value->day;
                                }
                                
                                 if($prev!=""){
                                        ?>
                                         </tr>
                                     <?php
                                    }
                                    
                                $ids=substr($ids,0,strlen($ids)-1);
                               }else{
                                   ?>
                                         <tr >
                                             <td></td>
                                         </tr>
                                   <?php
                               } 
                            ?>
                        </tbody>
                    </table>
                
               <?php
            }

                    
        }
        
        public function ajax_profile(){
             $student=  $this->check_student($this->input->post('student'))
            ?>
<div class="col-sm-12">
                        <div class="box">
           <div class="box-title">
                        <h3><i class="fa fa-th-list"></i><?php echo $student->name." 's  Profile"; ?></h3>
                        <div class="actions">
                            <a class="btn" href="<?php echo base_url() ?>index.php/Students/edit/<?php  echo $student->student_id   ?>"  rel="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a>
                        </div>
                </div>
            
           <div class="box-content nopadding">
                 <div  class='form-horizontal form-bordered'>
                     
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Name</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->name ?></label>
                                </div>
                        </div> 
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Admission No</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->userid ?></label>
                                </div>
                        </div>
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Birthday</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo date('d-m-Y',$student->birthday) ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Gender</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php if($student->sex ==1){
                                        echo "Male";
                                    }else{
                                        echo "Female";
                                    }    ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Mobile No</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->phone ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Email</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->email ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Class-Section</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->class_name ." - " .$student->section_name ?></label>
                                </div>
                        </div>
                 </div>
            </div>  
                        </div>
</div>
            <?php
        }
        
        public function ajax_assignments(){
              $student=  $this->check_student($this->input->post('student'));
              $date=$this->input->post('date');
              $hide=  $this->input->post('hide');
            ?>
            
               <?php
                  if(strlen($hide)==0){
                      ?>
                  <div  class='form-horizontal form-bordered' style=" border-bottom:  1px solid #cccccc">

                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-3">Submission Date</label>
                                                    <div class="col-sm-6">
                                                        <input type="date" name="subdate" id="subdate" class="form-control datepick"/>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <button onclick="fetch_assignments();" class="btn btn-primary">Fetch Assignmnents</button>
                                                        <span style=" color: red" id="sub_date_error">

                                                        </span>
                                                    </div>
                                            </div> 

                                        </div>
                           <br/>       
                      <?php
                  }
               ?>
           
             
                    <table class="table table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Subject</th>
                                <th>Assignment</th>
                                <th>Issued On</th>
                                <th>Submission On</th>
                            </tr>
                        </thead>
                        <?php
                        if(strlen(trim($date))==0){
                      
                        $query= (" SELECT a.message,a.timestamp,a.submission,s.subject FROM assignments a JOIN course c ON a.courseid=c.cid  JOIN subjects s ON c.subid=s.sid WHERE c.secid='".$student->section_id ."' AND c.iid='".$this->session->userdata('parent_org_id')."'     ");
                        }else{
                         $date =explode("-",$date);
                         $date=  mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                         $query= (" SELECT a.message,a.timestamp,a.submission,s.subject FROM assignments a JOIN course c ON a.courseid=c.cid  JOIN subjects s ON c.subid=s.sid WHERE c.secid='".$student->section_id ."' AND c.iid='".$this->session->userdata('parent_org_id')."' AND a.submission = '".$date."'     ");
                        }
                    
                        $query=  $this->db->query($query);
                         
                        ?>
                        <tbody>
                            <?php
                            if($query->num_rows() >0){
                                $i=1;
                            $query=$query->result();
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><?php echo $value->subject ?></td>
                                <td><?php  echo $value->message;?></td>
                                <td><?php  echo date('d-m-Y',$value->timestamp);?></td>
                                <td><?php  echo date('d-m-Y ',$value->submission);?></td>
                            </tr>
                                <?php
                              
                              }
                            }else{
                                ?>
                            <tr><td colspan="5">No Records Found..</td></tr>
                               <?php
                            }
                            
                            
                            ?>
                           
                        </tbody>
                    </table>
                
            <?php
        }
        
        public function ajax_attendance(){
             $student=  $this->check_student($this->input->post('student'));            
            $now=time();    
            if( ( (strlen($this->input->post('month'))!=0 ) && (strlen($this->input->post('year'))!=0 ) )){
              
                  $now=   mktime(0,0,0,$this->input->post('month'),1,$this->input->post('year'));
             
            }
            $time=  getdate($now);
            $from=mktime(0,0,0,$time['mon'],1,$time['year']);
            $to=mktime(0,0,0,$time['mon']+1,1,$time['year']);
             $att_config=  $this->db->query("SELECT c.acid,c.time FROM attendance_config c JOIN attendance_settings s ON c.asid=s.aid WHERE s.section=1 ");
            if($att_config->num_rows()==0){
                
                
            }else{
                $att_config=$att_config->result();
                
                $query=$this->db->query("SELECT d.id,d.day,a.status,a.acid FROM attendance a JOIN attendance_date d ON a.date_id=d.id WHERE d.section='".$student->section_id."' AND ( d.day >= '".$from."' AND d.day < '".$to."' ) AND ( a.student ='".$student->student_id."') ORDER BY day ASC ");
                $query=$query->result();
               
                ?>
                <div class="col-sm-12">
                        <div class="box">
                            <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3><i class="fa fa-th-list"></i>View Attendance OF  <?php echo $time['month'] ." , " .$time['year']  ?> </h3> 
                                        <div class="tabs " style=" width: 30% ">
                                            <div class='form-horizontal ' >
                                                <div class="col-sm-12 nopadding" >

                                                    <div class="col-sm-5  ">
                                                        <div class="form-group">
                                                           <select class="select2-me" id="att_month" name="month"  style=" width: 90% "  >
                                                            <option value="" >Month</option>
                                                            <?php
                                                              for ($m=1; $m<=12; $m++) {
                                                                  ?> <option value="<?php echo $m ?>"  <?php 
                                                                    if($time['mon']==$m){
                                                                        echo "selected";
                                                                    }

                                                                  ?> ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                                                }
                                                                ?>
                                                           </select> 
                                                        </div>
                                                        </div>
                                                    <div class="col-sm-5  ">
                                                        <div class="form-group">
                                                           <select class="select2-me" id="att_year" name="year"  style=" width: 90% "  >
                                                            <option value="" >Year</option>
                                                            <?php
                                                                $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                                                $start=$start->row();
                                                                $start=$start->timestamp;
                                                                $start=getdate($start);
                                                                $start=$start['year'];
                                                                $now=getdate();
                                                                $now=$now['year'];
                                                                for($i=$start;$i<=$now;$i++){
                                                                  ?>
                                                            <option value="<?php echo $i; ?>"  <?php
                                                                    if($time['year']==$i){
                                                                        echo "selected";
                                                                    }

                                                                 ?> ><?php echo $i; ?></option>
                                                                  <?php
                                                                }

                                                            ?>
                                                           </select> 
                                                        </div>
                                                    </div>
                                                    <div class="form-actions  col-sm-2 nopadding ">
                                                        <button onclick="month_attendance();" class="btn">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                        </button>
                                                       </div>


                                            </div>
                                                <span id="fetch_att_error" style=" color: maroon">

                                                    </span>
                                            </div>
                                        </div>

                                </div>
                                <div class="box-content nopadding"> 
                                    <table class="table table-hover table-nomargin" >
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <?php
                                                foreach ($att_config as $value) {
                                                     ?>
                                                <th><?php echo $value->time ?></th>
                                                    <?php
                                                } 
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stud_attendance =array();
                                            $prev_day="";
                                                foreach($query as $value){
                                                    $stud_attendance[$value->day][$value->acid]=$value->status;
                                                   }


                                             foreach($stud_attendance as $key=>$value){
                                                 ?>
                                            <tr>
                                                <td><?php echo  date("d-m-Y",$key) ?></td>
                                                <?php
                                                  foreach ($att_config as $att) {
                                                      ?>
                                                <td>
                                                      <?php
                                                     if(isset($stud_attendance[$key][$att->acid])){
                                                         switch( $stud_attendance[$key][$att->acid]){
                                                             case 1: echo "Present"; break;
                                                             case 2: echo "Absent"; break;
                                                         }
                                                     }else{
                                                         echo "--";
                                                     }
                                                     ?>
                                                </td>
                                                    <?php
                                                  } 

                                                ?>

                                            </tr>
                                                 <?php
                                             }

                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                </div> 
                <?php
            } 
        }
        
        public function ajax_exam(){
             $student=  $this->check_student($this->input->post('student')); 
            ?>
            
                  <div  class='form-horizontal form-bordered' style=" border-bottom: 1px solid #cccccc">

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Exams</label>
                                        <div class="col-sm-6">
                                            <?php
                                                $query=  $this->db->query("SELECT ec.id as ecid,e.id as eid,e.exam,e.startdate,e.enddate  FROM `examination_cls` ec  JOIN examinations e ON e.id=ec.examid WHERE ( ec.status=1 AND e.status=1 ) AND sectionid= '".$student->section_id."' ");
                                                if($query->num_rows()>0){
                                                    $query=$query->result();
                                                    ?>               
                                                        <select class="select2-me" id="exam_r" name="exam" onchange=""   style=" width: 100% "  >
                                                            <option value="" >select Exam</option>
                                                            <?php
                                                                foreach($query as $val){
                                                                    ?><option value="<?php echo $val->eid.",".$val->ecid   ?>" ><?php echo $val->exam ?>( <?php echo date('d-m-Y',$val->startdate)." - ".date('d-m-Y',$val->enddate) ?> )</option><?php
                                                                }
                                                            ?>
                                                        </select> 
                                                        <span id="rexam_err" style=" color: red">

                                                        </span>

                                                   <?php
                                                }else{
                                                    ?>
                                                   <select   class="select2-me" id="exam_r" name="exam"  style=" width: 100%; display: none "  >

                                                   </select>
                                                   <span id="rexam_err" style=" color: red">
                                                       No Exams Scheduled
                                                    </span>
                                                    <?php                
                                                   }
                                            ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <button onclick="fetch_exam_schedule();" class="btn btn-primary btn-block">Fetch Schedule</button>
                                            
                                        </div>
                                </div> 
                         
                            </div>
                        
                    
                    <div id="exam_results_holder" class="box"  >
                        
                    </div>
                
            <?php            
        }
        
        public function fetch_exam_schedule(){
           $student=  $this->check_student($this->input->post('student')); 
            $section=$student->section_id;
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            ?><hr/>
               <table class="table table-bordered table-hover table-nomargin" >
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Timings</th>
                                <th>Time Span</th>
                                <th>Max Marks</th>
                                <th>Min Marks</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=  $this->db->query("SELECT s.subject,e.maxmarks,e.minmarks,e.examdate,e.starttime,e.endtime,e.timespan FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid  WHERE e.examid='".$examid."' AND e.ecid='".$ecid."'    ");
                             if($query->num_rows()>0){
                                 $query=$query->result();
                                 foreach ($query as $value){
                                     ?>
                            <tr>
                                <td><?php echo $value->subject  ?></td>
                                <td><?php echo date('d-m-Y',$value->examdate )  ?></td>
                                <td><?php echo date("H:i",$value->starttime) ." -- ".date("H:i",$value->endtime); ?></td>
                                <td><?php echo $value->timespan ?></td>
                                <td><?php echo $value->maxmarks  ?></td>
                                <td><?php echo $value->minmarks  ?></td>
                               
                            </tr>
                                    <?php
                                 }
                             }else{
                                 ?>
                                    <tr>
                                        <td colspan="5">No Records Found</td>
                                    </tr>
                                <?php
                             }
                            ?>
                        </tbody>
                    </table>
               
            <?php 
        }
        
        public function ajax_exam_results(){
             $student=  $this->check_student($this->input->post('student')); 
            ?>
                            <div  class='form-horizontal form-bordered'>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Exams</label>
                                        <div class="col-sm-6">
                                            <?php
                                                $query=  $this->db->query("SELECT ec.id as ecid,e.id as eid,e.exam,e.startdate,e.enddate  FROM `examination_cls` ec  JOIN examinations e ON e.id=ec.examid WHERE ( ec.status=1 AND e.status=1 ) AND sectionid= '".$student->section_id."' ");
                                                if($query->num_rows()>0){
                                                    $query=$query->result();
                                                    ?>               
                                                        <select class="select2-me" id="exam_r" name="exam" onchange=""   style=" width: 100% "  >
                                                            <option value="" >select Exam</option>
                                                            <?php
                                                                foreach($query as $val){
                                                                    ?><option value="<?php echo $val->eid.",".$val->ecid   ?>" ><?php echo $val->exam ?>( <?php echo date('d-m-Y',$val->startdate)." - ".date('d-m-Y',$val->enddate) ?> )</option><?php
                                                                }
                                                            ?>
                                                        </select> 
                                                        <span id="rexam_err" style=" color: red">

                                                        </span>

                                                   <?php
                                                }else{
                                                    ?>
                                                   <select   class="select2-me" id="exam_r" name="exam"  style=" width: 100%; display: none "  >

                                                   </select>
                                                   <span id="rexam_err" style=" color: red">
                                                       No Exams Scheduled
                                                    </span>
                                                    <?php                
                                                   }
                                            ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <button onclick="fetch_exam_results();" class="btn btn-primary">Fetch Results</button>
                                            
                                        </div>
                                </div> 

                            </div>
                    <div id="exam_results_holder" class="box"  >
                        
                    </div>
                
            <?php            
        }
        
        public function fetch_exam_results(){
            $student=  $this->check_student($this->input->post('student')); 
            $section=$student->section_id;
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            ?><hr/>
<!--            <a class='btn' style=" float: right"  onclick="send_sms_exam_results();" class="btn">Send SMS</a>-->
                    <table class="table table-bordered table-hover table-nomargin" >
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Max Marks</th>
                                <th>Min Marks</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=  $this->db->query("SELECT s.subject,e.maxmarks,e.minmarks,e.examdate,m.marks FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN marks m ON m.exam_id=e.id AND  m.student_id ='".$student->student_id."' WHERE e.examid='".$examid."' AND e.ecid='".$ecid."'    ");
                             if($query->num_rows()>0){
                                 $query=$query->result();
                                 foreach ($query as $value){
                                     ?>
                            <tr>
                                <td><?php echo $value->subject  ?></td>
                                <td><?php echo date("d-m-Y",$value->examdate);  ?></td>
                                <td><?php echo $value->maxmarks  ?></td>
                                <td><?php echo $value->minmarks  ?></td>
                                <td><?php if( strlen($value->marks)==0)
                                            {
                                              echo "--";
                                            }else{
                                               echo $value->marks; 
                                            }
                                    
                                    ?></td>
                            </tr>
                                    <?php
                                 }
                             }else{
                                 ?>
                                    <tr>
                                        <td colspan="5">No Records Found</td>
          
                                    
                                    
                                    
                                    </tr>
                                <?php
                             }
                            ?>
                        </tbody>
                    </table>
              
            <?php
        }
        
        public function ajax_send_exam_results(){
            $student=  $this->check_student($this->input->post('student')); 
            $phone=  $this->fetch_stud_parent_phoneno($this->input->post('student'));
            $section=$student->section_id;
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            $marks_msg="";
            $query=  $this->db->query("SELECT s.subject,e.maxmarks,e.minmarks,e.examdate,m.marks FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN marks m ON m.exam_id=e.id AND  m.student_id ='".$student->student_id."' WHERE e.examid='".$examid."' AND e.ecid='".$ecid."'    ");
            $exam=  $this->db->query("SELECT * FROM `examinations` WHERE id='".$examid."' ")->row();;
            
            if($query->num_rows()>0){
                $query=$query->result();
                foreach ($query as $value){
                    if( strlen($value->marks)==0)
                           {
                             $mark= "--";
                           }else{
                              $mark=  $value->marks; 
                           }
                    $marks_msg  = $marks_msg.$value->subject." : ".$mark ."\n";
                }
            }
           
            if(strlen($phone->std_phone)!=0){
                 $this->studparent->send_sms("Dear student Your Result of ".$exam->exam."is :"."\n ".$marks_msg,$phone->std_phone);
            }
            if(strlen($phone->prnt_ph)!=0){
               $this->studparent->send_sms("Dear parent, Your Ward's Result of ".$exam->exam."is :"."\n ".$marks_msg,$phone->prnt_ph);
           
            }
            ?><script>
                alert("Message Sent Sucessfully");
                </script>
                <?php
                            
        }
        
        private function check_student($student){
            
            $query = $this->db->query("SELECT s.*,st.name as section_name,c.name as class_name FROM `student` s JOIN section st ON s.section_id=st.sid JOIN class c ON st.class_id=c.id WHERE s.student_id='".$student."'  ");
            if ($query->num_rows() > 0) {
                $query = $query->row();
                return $query;
               }else{
               return FALSE;           
            }
        }
        
        private function fetch_stud_parent_phoneno($student){
           $query = $this->db->query("select  s.phone as std_phone , p.phone as prnt_ph from student s LEFT JOIN parent p on s.parent_id=p.parent_id  WHERE s.student_id='".$student."'  ");
           
           $query = $query->row();
           return $query;
        }
        
        public function brief_report(){
            $this->load->view('students/brief_report');
        }
        
        
        public function Upload_snap(){
         //   print_r($_FILES);
            //  $config['upload_path']   = 'C:/wamp/www/schooln/assests_2/uploads/';
              $file="tmp_student_".time().".jpg";
              move_uploaded_file($_FILES['webcam']['tmp_name'],upload_path.'temp/'.$file);
              $this->session->set_userdata('student_dummy_img', $file); 
          }
          
          public function load_class_sec(){
            $sclass =  $this->input->post("sclass");
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.medium='".$medium."'  ORDER BY c.id ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Class-section</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->id."-".$val->sid ?>" >
                    <?php echo $val->cls_name." -".$val->sec_name ?>
                </option>
             <?php
            }

        }

        public function load_sec(){
            $sclass =  $this->input->post("sclass");
            if(isset($sclass)){
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.id='".$sclass."'  ORDER BY c.id ";
            $query = $this->db->query($query);
            echo $sclass;
            ?>
                <option value="">Select section</option>
                <?php
            $query=$query->result();

            foreach($query as $val){
                ?>
                <option value="<?php echo $val->sid ?>" >
                    <?php echo $val->sec_name ?>
                </option>
             <?php
            }

        }
        else{?>
            <option value="">Select section</option><?php
        }
    }
        
        public function print_brief_report(){
             $institute =  $this->fetch_institute_details();
                     echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Attendance Brief Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
                    
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
</style>
</head>
<body>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="148" height="60" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />';
              ?><br/><br/>
                                      <h4 style=" text-align: center">Brief Student Report</h4>                 
                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                           <th>Class - Section</th>
                            <th style="   text-align: center">Boys</th>
                            <th style="    text-align: center" >Girls</th>
                            <th style="  text-align: center" >Total</th>
                    </tr>
                    <?php
                    $q="SELECT s.name as sec_name, s.sid ,c.id ,c.name as cls_name , (select count(*) from student  where section_id = s.sid AND sex =2  ) as female ,(select count(*) from student  where section_id = s.sid AND sex =1  ) as male   FROM section s JOIN class c ON s.class_id = c.id AND s.iid= '".$this->session->userdata('staff_Org_id')."' ";
        $q = $this->db->query($q);
        
                      if($q->num_rows()>0){
                          $q = $q->result();
                        $i=1;$total_boys=0;$total_girls=0;
                          foreach ($q as $value) {
                              ?> 
                            <tr>
                                <td><?php echo $value->cls_name ." - " .$value->sec_name ?></td> 
                                <td style=" color: #006699; text-align: center"><?php echo $value->male ?></td>
                                <td style=" color: #ff00cc; text-align: center"><?php echo $value->female ?></td>
                                <td style=" color: #ff9933; text-align: center"><?php echo ($value->female + $value->male ) ?></td>
                            </tr>
                              <?php
                              $total_boys =$total_boys+$value->male;
                              $total_girls =$total_girls+$value->female;
                          }
                          ?>
                            
                          <?php
                      }else{
                          ?>
                    <tr>
                        <td colspan="5" style=" text-align: center">No Records Found</td>
                    </tr>
                         <?php
                      }
                    ?>
            </table>
                                        
                                   
                </div><script>window.print();</script>
                                </body></html>
                            <?php
        }
        
        public function upddate_bday_field(){
            $q="SELECT * from student ";
            $q=$this->db->query($q);
            $q=$q->result();
            foreach ($q as $value) {
              $dob= getdate($value->birthday);
              $dob =$dob['mday']."/".$dob['mon'];
               echo "<br/>".$dob;
               $data=array(
                            'bday'=>$dob
                        );
                 $this->db->where('student_id',$value->student_id);
                    $this->db->update('student', $data); 
            }
        }
        
        public function delete_student(){
          $student=  $this->input->post("student_id");
          $query=  $this->db->query("DELETE from student where student_id='".$student."'");
          //Student_delete_sucess
          $this->session->set_userdata('Student_delete_sucess', 'Student Deleted Sucessfully ');  
          
          ?><script>window.location.reload();</script><?php
        }
        
        
}
