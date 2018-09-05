<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

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
        
        public function view_incharges(){
            $this->load->view("staff/view_incharges");
        }
        public function index()
        {
            redirect("staff/View_staff",'refresh');
             
        }

        public function Add_staff(){
             $this->load->view('staff/create');
            //views\staff
        }
        public function tmp(){
             $this->load->view('staff/tmp');
            //views\staff
        }

        public function bulk(){
               $this->load->view('staff/bulk_create');
        }
        
        public function bulk_create(){
            $file= $_FILES['bfile'];
	    $filename=$file['name'];
		if(strlen(trim($filename)) == 0)
		{
			$this->form->setError('bfile','<i class="fa fa-info-circle"></i> file is not selected');
		}
		else
		{
                        $end=explode('.',$filename);
			$end = strtolower(end($end));
                        
                        $type = array("csv", "txt");
                        
			if(!(in_array($end, $type)))
			{
				$this->form->setError('bfile','<i class="fa fa-info-circle"></i> file is supporrt only csv/txt format');
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

                    if(trim($ex[0]) == "name,gender,mobile,email,dob,doj,userlevel,qualification,salary"){
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
                                $str=explode("/",$arr[4]);
                                if(sizeof($str)!=3){
                                    $error++;
                                   $arr['error']="Invalid Date Of Birth";  
                                }else{
                                   $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                                }
                                $str=explode("/",$arr[5]);
                                if(sizeof($str)!=3){
                                    $error++;
                                   $arr['error']="Invalid Date Of Joining";  
                                }else{
                                  $doj= mktime(0,0,0,$str[1],$str[0],$str[2]);
                                }
                                $phone=$arr['2'];
                                $email = $arr['3'];
                                
                                
                                $level = 1;
                                switch(strtolower($arr[6])){
                                    case 'faculty' :  $level=1;break;
                                    case 'office' : $level=3;break;
                                    case 'librarian' : $level=5 ;break;
                                    default : $level=1;break;
                                }
                                $sex=1;
                                if(strtolower(trim($arr['1']))!='male' ){
                                    $sex=2;   
                                }
                                
                               if( $this->validations->validate_mobile($phone)==0 )
                                {
                                   $error++;
                                   $arr['error']="Invalid Mobile Number";  
                                   $dummy[]=$arr;
                                }else if(!$this->validations->check_teacher_mobile($phone)){
                                    $error++;
                                    $arr['error']="Mobile Number Already Registered"; 
                                    $dummy[]=$arr;
                                }
                                if( $this->validations->validate_email($email)==0 )
                                {
                                         $error++;
                                   $arr['error']="Invalid E-mail id";  
                                   $dummy[]=$arr;
                                }elseif( ! $this->validations->check_teacher_email($email)){
                                   $error++;
                                   $arr['error']="E-mail id Already Registered";  
                                   $dummy[]=$arr;
                                }   
                                
                                
                                if($error==0){
                                    $counter++;
                                       $pass=$this->validations->generate_password();
                                       
                                        $data = array(
                                           'iid' => $this->session->userdata('staff_Org_id'),
                                           'name' =>$arr[0],
                                           'dob' => $dob,
                                           'doj' => $doj,
                                           'sex' => $sex,
                                           'bloodg' => "0",
                                           'phone' => $phone,
                                           'email' => $email,
                                           'password' => $pass,
                                           'qualification' => $arr[7],
                                           'img' => " ",
                                           'status' => 1,
                                           'level' => $level,
                                           'timestamp' =>time(),
                                           'last_login' =>time(),
                                           'createdby'=>"st_".$this->session->userdata('staff_id')
                                           );
                                        //qualification,img
                                           //Transfering data to Model
                                    
                                           $this->db->insert('staff',$data); 
                                           $id= $this->db->insert_id();
                                           $this->logs->insert_staff_log(7,'Created Staff :'.$email,$id  );
                                            $data=array(
                                                'iid' =>  $this->session->userdata('staff_Org_id'),
                                                'staff_id' => $id,
                                                'amount' =>$arr[8],
                                                'assigned_by' =>$this->session->userdata('staff_id')
                                            );
                                            $this->db->insert('salary',$data);
                                            
                                            $this->logs->insert_staff_log(27,'Configured Salary',$this->db->insert_id());
                                           $msgcontent="Dear staff,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                                                       . "url :".  base_url()." \n username: ".$email."\n Password : ".$pass;
                                           $this->studparent->send_sms($msgcontent,$phone);

                                           
                                }
                                
			}
                        if(sizeof($dummy)>0){
                      $_SESSION['stdummy_data']=$dummy;  
                    }
                    if($counter!=0){
                         $this->session->set_userdata('bulkstf_add', $counter.'Staff  Records Created Sucessfully ');
                    }
                   
                        
                }
                
                redirect('staff/bulk', 'refresh'); 
                
        }
        

        public function View($section=""){
            if($section==""){
                redirect('/staff/View_staff', 'refresh');     exit;
            }else{
            $staff_level = unserialize (staff_level);
           
             $key = array_search($section, $staff_level);
           
            if(is_null($key)){
                redirect('/staff/View_staff', 'refresh');     exit;
            }
            $config = array();
            if($section==""){
                $config["base_url"] = base_url() ."index.php/". "staff/View/_/";
            }
//            $config["total_rows"] = $this->staff_count($key);          
//            $config["per_page"] = 10;
//            $config["uri_segment"] = 4;
//            $this->pagination->initialize($config);
//            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
             $data["results"] = $this->fetch_staff($key,$this->staff_count($key),1);
//            $data["links"] = $this->pagination->create_links($key);
//            $data['page']=$page;
//            $data['label_head']="List of ".$section." Users";
//            $data['level']=$section;
//            $data['per_page']=$config["per_page"];
            $this->load->view('staff\viewcat',$data);
            }
        }
        
        public function attendance(){
             $this->load->view('staff/attendance');
        }
        
        public function Monthly_attendance(){
             $this->load->view('staff/monthly_attendance');
        }
        
         private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
        
        public function print_all(){
                    $institute =  $this->fetch_institute_details();
                    $query=$this->db->query(" SELECT s.*,sa.amount FROM `staff` s JOIN salary sa ON sa.staff_id=s.id  WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND level >0 ORDER BY s.name  ");  
                    $query = $query->result();             
                    $blood_group = unserialize (blood_groups);$staff_level = unserialize (staff_level);
           echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Staff Details Print Out</title>
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
<hr color="#00306C" />     
<h2 style=" text-align: center; color:orange ">Staff Details</h2>'; 
                   ?> 
                                   <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                        
                                            <tr><th>S.No</th>
                                                <th>Name</th>
                                                <th>E-mail</th>
                                                <th>Phone</th>
                                                <th>Gender</th>
                                                <th>Role</th> 
                                                <th>B G</th>
                                                <th>Qualification</th>
                                            </tr>
                                        
                                            <?php
                                            $i=1;
                                               foreach($query as $value){
                                                   ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $value->name; ?></td>
                                                        <td><?php echo $value->email; ?></td>
                                                        <td><?php echo $value->phone; ?></td>
                                                        <td><?php switch( $value->sex){
                                    case 1 : echo "male";                                        break;
                                    case 2 : echo "female";                                        break;
                                    default : echo "--";                                        break;
                                                        } ?></td>
                                                        <td><?php echo $staff_level[$value->level];?></td>       
                                                        <td><?php 
                                                        if(!isset($blood_group[$value->bloodg])){
                                                           echo "--"; 
                                                        }else{
                                                        echo $blood_group[$value->bloodg];} ?></td>          
                                                        <td><?php echo $value->qualification; ?></td>
                                                        
                                                    </tr>
                                                  <?php
                                               }
                                            ?>
                                    </table>
                                    </div>
                                </body>
                            </html>
                    <?php
             }
        
        public function View_staff(){
            
            $query=$this->db->query(" SELECT s.*,sa.amount FROM `staff` s JOIN salary sa ON sa.staff_id=s.id  WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND level !=0");
            $data['results']= $query->result();

            $this->load->view('staff/view_staff',$data);

        }
        public function add(){

            $post=$_POST;
            //setError
            $field = 'stusername';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Staff Name');
            }
                                    
            
           //gender
           $field = 'gender';
           $gender=  $this->input->post('gender');
           if(strlen($gender)!=0){
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Gender ');
                }
                elseif( ($post[$field]!=1)&&($post[$field]!=2) )
                {
                        $this->form->setError($field,' <i class="fa fa-info-circle"></i> Please Select Gender ');
                }
           }else{
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select Gender ');
           }
          //stmobile      
           $field = 'stmobile';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Mobile Number');
                }
                elseif( $this->validations->validate_mobile($post[$field])==0 )
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Enter Valid Mobile Number  ');
                }elseif( ! $this->validations->check_teacher_mobile($post[$field])){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Mobile Number Already Used');
                    //check_teacher_mobile
                }
          //stemail
          $field = 'stemail';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide E-mail');
                }
                elseif( $this->validations->validate_email($post[$field])==0 )
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Enter Valid E-mail Id  ');
                }elseif( ! $this->validations->check_teacher_email($post[$field])){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> E-mail Already Used');
                    //check_teacher_email
                }                
         //stbg
                $field = 'Leaves';
                if(strlen($post[$field]) ==  0)
                {
                       $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter No Of Leaves  ');
                }elseif(!is_numeric($post[$field])){
                             $this->form->setError($field,'<i class="fa fa-info-circle"></i> Invalid No Of  Leaves  ');
                }
                
                $field = 'account';
                if(strlen($post[$field]) ==  0)
                {
                   //   $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Account No  ');
                }elseif(!is_numeric($post[$field])){
                             $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Valid Account No ');
                }
                $field = 'pfno';
                if(strlen(trim($post[$field])) ==  0)
                {
                    //    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter PF No ');
                }
                
                $field = 'aadhar';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Aadhar Card No  ');
                }elseif(!is_numeric($post[$field])){
                             $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Aadhar Card No ');
                }elseif(strlen($post[$field])!=12){
                     $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Valid Aadhar Card No ');
                }
                
          $field = 'stbg';
                if(strlen($post[$field]) ==  0)
                {
                   //     $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Blood Group ');
                }
         //stdob	
           $field = 'stdob';
           $dob="";$dob_day="";
                if(strlen($post[$field]) ==  0)
                {
                      $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Date Of Birth');
                }else{
                    $str=explode("/",$post[$field]);
                   $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                   $dob_ar= getdate($dob);
                   $dob_day =$dob_ar['mday']."/".$dob_ar['mon'];
                    if($dob >time()){
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select  Valid Date Of Birth');
                    }
                }
        $field = 'stdoj';$doj="";
                if(strlen($post[$field]) ==  0)
                {
                      //  $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Date Of Joining');
                }else{
                    $str=explode("/",$post[$field]);
                   $doj= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($doj >time()){
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select valid Date Of Joining');
                    }
                }
         //stlevel	
           $field = 'stlevel';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide User Level');
                } 
         //stqualification 
         $field = 'stqualification';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Qualification ');
                }
          $field = 'stsalary';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Salary ');
                }else if(!is_numeric($post[$field])){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Numeric Value ');
                }
                $field='stexperience';
                 if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Experience ');
                }else if(!is_numeric($post[$field])){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Numeric Value ');
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
                      //  $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select a profice pic');
                }else{
                    $img_uplod=2;
                    $ext=explode(".",$_FILES['stdimage']['name']);
                    $ext=$ext[sizeof($ext)-1 ];
                    $img_arr=array('jpg','jpeg','gif','png');
                    if(!in_array($ext,$img_arr)){
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select a Image file');
                    }
                } 
          } 
         if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/staff/Add_staff', 'refresh'); 
         }else{
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
                        $file= $this->session->userdata('staff_Org_id')."_stf_".time().".jpg"; 
                         copy('/var/www/html/schooln/assests_2/uploads/temp/'.$this->session->userdata('staff_dummy_img'),'/var/www/html/schooln/assests_2/uploads/'.$file);
                          $this->session->unset_userdata('staff_dummy_img');
                     
                   }   
             $pass=$this->validations->generate_password();
             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'name' => $post['stusername'],
                'dob' => $dob,
                'doj' => $doj,
                'sex' => $post['gender'],
                'bloodg' => $post['stbg'],
                'phone' => $post['stmobile'],
                'email' => $post['stemail'],
                'password' => $pass,
                 'qualification' => $post['stqualification'],
                'img' => $file,
                'status' => 1,
                'level' => $post['stlevel'],
                'timestamp' =>time(),
                 'last_login' =>time(),
                 'leaves'   =>$this->input->post('Leaves'),
                 'createdby'=>"st_".$this->session->userdata('staff_id'),
                 'bday' =>$dob_day,
                 'address' =>"",
                 'accounts'   =>$this->input->post('account'),
                 'experience' =>$this->input->post('stexperience')
                );
                $this->db->insert('staff',$data); 
                $id= $this->db->insert_id();
                 $data=array(
                     'iid' =>  $this->session->userdata('staff_Org_id'),
                     'staff_id' => $id,
                     'amount' =>$post['stsalary'],
                     'assigned_by' =>$this->session->userdata('staff_id')
                 );
                 $this->db->insert('salary',$data);
                 $this->logs->insert_staff_log(27,'Configured Salary',$this->db->insert_id());
                 $data= array(
                             'iid' => $this->session->userdata('staff_Org_id'),
                             'staff_id' => $id,
                             'doc_name' =>'Aadhar Card',
                             'doc_no' =>$post['aadhar'],
                             'validity' =>""
                             ); 
                $this->db->insert('staff_documents',$data); 
                $data= array(
                             'iid' => $this->session->userdata('staff_Org_id'),
                             'staff_id' => $id,
                             'doc_name' =>'pfno',
                             'doc_no' =>$post['pfno'],
                             'validity' =>""
                             ); 
                $this->db->insert('staff_documents',$data); 
                      
                $msgcontent="Dear staff,\n Your Account Created Sucessfully\n please check the url and login credential\n"
                            . "url :".  base_url()." \n username: ".$post['stemail']."\n Password : ".$pass;

                  $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$post['stmobile']."&from=School&message=".urlencode($msgcontent);    //Store data into URL variable

                $ret = file($url);    //Call Url variable by using file() function
                if($ret){
                $this->studparent->send_sms($msgcontent,$post['stmobile']);
                $this->logs->insert_staff_log(7,'Created Staff :'.$post['stusername'],$id  );
                $this->session->set_userdata('staff_add_Sucess', 'Sucessfully Created Staff'); 
                redirect('/staff/View_staff', 'refresh'); 
            }

            else{
              $this->session->set_userdata('staff_add_Sucess', 'Problem in creating of staff');
              redirect('/staff/View_staff', 'refresh');
            }
         }
         
              
        }
        
        public function view_staff_details($staffid=""){
            $staff=  $this->check_staff_id($staffid);
            if(!$staff){
               redirect("staff/View_staff",'refresh');
            }else{
                $data['staff_details']=$staff;
                $this->load->view("staff/view_staff",$data);
            }
        }
        
        public function edit($staffid=""){
            $staff=  $this->check_staff_id($staffid);
            if(!$staff){
               redirect("staff/View_staff",'refresh');
            }else{
                $data['staff_details']=$staff;
                $this->load->view("staff/edit",$data);
            }
        }
        
        public function update_profile(){
            
            $post=$this->operations->cleanInput($_POST);
            
            $field = 'sname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide  name');
            }
            
            //sphone
           $field = 'sphone'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Phone Number ');
            }elseif(!$this->validations->validate_mobile($post[$field])){
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Enter Valid Mobile number ');
            }
           
             //sphone
            $field = 'sgender'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide gender ');
            }
            
            $field = 'salary'; 
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide salary ');
            }else{
                if(!is_numeric($post[$field])){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Numeric Value');
                }
            }
            
            
            //$field = 'sbloodg'; 
            //if(strlen($post[$field]) ==  0)
            //{
               //$this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Blood Group ');
            //}
            //sdob
          
            $dob="";
            $field = 'sdob'; 
            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
               $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
               $dob_arr=  getdate($dob);
                $dob_day =$dob_arr['mday']."/".$dob_arr['mon'];

                if($dob >time()){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please select  valid Date Of Birth');
                }
            }
                $doj="";
            $field = 'sdoj'; 
            if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Date Of Joining');
                }else{
                    $str=explode("/",$post[$field]);
                   $doj= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($doj >time()){
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select  valid Date Of Joining');
                    }
                }
            
            
            //squalification
            $field ='squalification';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please provide Qualification');
            }
            $field ='email';
            
            if( $this->validations->validate_email($post[$field])==0 )
            {
                $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Provide Valid Email Id');
             }else{
                 $q=$this->db->query("SELECT * FROM `staff` WHERE  email='".$post[$field]."'  AND id!='".$this->input->post('staff_id')."'  ");
                 if($q->num_rows()>0){
                        $this->form->setError($field,'<i class="fa fa-info-circle"></i> Email Id Already Exists ');
                 }
                 
             }
            $image=0;
            if(strlen($_FILES['stdimage']['name'])!=0 ){
                $image=1;
                $field ='stdimage';
                $ext=explode(".",$_FILES['stdimage']['name']);
                $ext=$ext[sizeof($ext)-1 ];
                $img_arr=array('jpg','jpeg','gif','png');
               
                if(!in_array($ext,$img_arr)){
                    $this->form->setError($field,'<i class="fa fa-info-circle"></i> Please Select a Image file');
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
                        'email' =>$post['email'],
                        'qualification'=>$post['squalification'],
                        'dob' =>$dob,
                        'doj' =>$doj,
                        'bloodg' =>$post['sbloodg'],
                        'sex' =>$post['sgender'],
                        'bday' =>$dob_day
                    );
                   // print_r($data);exit;
                   if($image==1){
                       $ext=explode(".",$_FILES['stdimage']['name']);
                       $ext=$ext[sizeof($ext)-1 ];
                    //   echo $ext;
                       $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."_".time().".".$ext;
                       $config['upload_path']   = upload_path; 
                       $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
                       $this->load->library('upload', $config);
                       $this->upload->do_upload('stdimage');
                       $data['img'] =$file;
                    }

                    $this->db->where('id',$this->input->post('staff_id'));
                    $query = $this->db->update('staff',$data);
                    $data=array(
                        'amount' =>$post['salary']
                            );
                    $this->db->where('id',  $this->input->post('staff_id'));
                    if($query){
                  
                     $this->session->set_userdata('staff_details_update', 'Sucessfully Updated Profile');

                     redirect('staff/view_staff_details/'.$this->input->post('staff_id'),'refresh');
                   }else{

                    $this->session->set_userdata('staff_details_update', 'Something went wrong..');
                   }
                 
            }
            
            redirect('staff/edit/'.$this->input->post('staff_id'),'refresh');
            
        }        
        
        public function ajax_profile(){
             $staff_details=  $this->check_staff_id($this->input->post('staff'));
             
            ?>
              <br/><br/>         
             <table class="table table-hover table-nomargin table-striped">
                <tr>
                   <td>Full Name</td>  
                   <td><?php echo $staff_details->name; ?></td>
                </tr>
                <tr>
                   <td>E-mail</td>  
                   <td><?php echo $staff_details->email; ?></td>
                </tr>
                <tr>
                   <td>Phone No</td>  
                   <td><?php echo $staff_details->phone; ?></td>
                </tr>
                <tr>
                   <td>Qualification</td>  
                   <td><?php echo $staff_details->qualification; ?></td>
                </tr>
                <tr>
                   <td>Date Of Birth</td>  
                   <td><?php echo $staff_details->dob; ?></td>
                </tr>
                <tr>
                   <td>Date Of Joining</td>  
                   <td><?php echo $staff_details->doj; ?></td>
                </tr>
                <tr>
                   <td>Subjects </td>  
                   <td><?php 
                     if(strlen($staff_details->subjects)==0){
                         echo "--";
                     }else{
                         echo $staff_details->subjects;
                     }
                    ?></td>
                </tr>
                <tr>
                   <td>Created On</td>  
                   <td><?php echo date("d-m-y H:i",$staff_details->timestamp); ?></td>
                </tr>
                <tr>
                   <td>Last Login</td>  
                   <td><?php echo date("d-m-y H:i",$staff_details->last_login);  ?></td>
                </tr>
                <tr>
                   <td>Salary</td>  
                   <td><?php echo $staff_details->sal_amount; ?></td>
                </tr>
                
        </table>
            <?php
        }
        
        public function ajax_timetable(){
          $staff_details=  $this->check_staff_id($this->input->post('staff'));
          ?>
            <div class="box box-bordered box-color"  >
                
                    <div class="box-title">
                            <h3>
                                <i class="fa fa-th-list"></i>Time Table</h3>

                    </div>

                <div class="box-content nopadding" style=" max-height: 350px; overflow-y: auto ">
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Day</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Class - Section</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT t.time_start,t.time_end,t.day,s.name as section,c.name as cls_name,sub.subject FROM class_routine t JOIN course cr ON t.course_id=cr.cid JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid WHERE cr.tid='".$staff_details->id."' ORDER BY t.day ASC,  t.time_start ASC ");
                                      if($query->num_rows()>0){
                                            $query=$query->result();$i=1;
                                            foreach($query as $value){
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php
                                                             $Week_days = unserialize (Week_days);
                                                             echo $Week_days[$value->day];
                                                      ?></td>
                                                      <td><?php echo substr($value->time_start, 0, strlen($value->time_start)-2).":".substr($value->time_start, strlen($value->time_start)-2,strlen($value->time_start) ); ?></td>
                                                      <td><?php echo substr($value->time_end, 0, strlen($value->time_end)-2).":".substr($value->time_end, strlen($value->time_end)-2,strlen($value->time_end) ); ?></td>
                                                      <td><?php echo $value->cls_name ." - ".$value->section ?></td>
                                                      <td><?php echo $value->subject ?></td>
                                                  </tr>
                                             <?php
                                            }
                                      }else{
                                          ?>
                                            <tr>
                                                <td colspan="4">No Records Found</td>
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
        
        public function ajax_salary(){
            $staff_details=  $this->check_staff_id($this->input->post('staff'));
             ?>
            <div class="box box-bordered box-color"  >
                
                    <div class="box-title">
                            <h3>
                                <i class="fa fa-th-list"></i>Salary Report</h3>

                    </div>

                <div class="box-content nopadding" style=" max-height: 350px; overflow-y: auto ">
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Month</th>
                                        <th>Paid On</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT p.amount,m.month,m.paid_on FROM  salary_paid p JOIN salary_month m ON p.month_id=m.id  WHERE p.staff='".$staff_details->id."'");
                                      if($query->num_rows()>0){
                                            $query=$query->result();$i=1;
                                            foreach($query as $value){
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php 
                                                                 $month=getdate($value->month);
                                                                 echo $month['month'].",".$month['year'];
                                                            ?></td>
                                                      <td><?php
                                                          echo date('d-m-Y',$value->paid_on);
                                                         ?></td>
                                                      <td><?php echo $value->amount  ?></td>
                                                  </tr>
                                             <?php
                                            }
                                      }else{
                                          ?>
                                            <tr>
                                                <td colspan="4">No Records Found</td>
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
        
        public function ajax_course(){
            $staff_details=  $this->check_staff_id($this->input->post('staff'));
             ?>
            <div class="box box-bordered box-color"  >
                
                    <div class="box-title">
                            <h3>
                                <i class="fa fa-th-list"></i>Course List</h3>

                    </div>
                <div class="box-content nopadding" style=" max-height: 350px; overflow-y: auto ">
                       
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Class - section</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $query=$this->db->query("SELECT se.name as sec_name ,su.subject,cl.name as class_name FROM `course` c JOIN section se ON c.secid=se.sid JOIN subjects as su ON c.subid=su.sid  JOIN class cl ON se.class_id =cl.id WHERE tid='".$staff_details->id."'");
                              if($query->num_rows()>0){
                                    $query=$query->result();$i=1;
                                    foreach($query as $value){
                                        ?>
                                          <tr>
                                              <td><?php echo $i++; ?></td>
                                              <td><?php 
                                                       echo $value->class_name ." - " .$value->sec_name 
                                                    ?></td>
                                              <td><?php
                                                  echo $value->subject;
                                                 ?></td>
                                            
                                          </tr>
                                     <?php
                                    }
                              }else{
                                  ?>
                                    <tr>
                                        <td colspan="3">No Records Found</td>
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
        
        public function print_staff_details($staffid=""){
            $staff=  $this->check_staff_id($staffid);
            if(!$staff){
               redirect("staff/View_staff",'refresh');
            }else{
                $data['staff_details']=$staff;
                $this->load->view("staff/edit",$data);
            }
        }
        

        public function add_date(){
            $date=explode('/',$_POST['date']);
            $date=  mktime(0, 0, 0, $date[1], $date[0], $date[2]);
            if($date > time()){
               echo "Invalid time stamp"; 
               exit;
            }elseif(!$this->check_date($date)){
                $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'day' =>$date
                );
                $this->db->insert('staff_attendance_date',$data);
                $aid=$this->db->insert_id();
                $this->logs->insert_staff_log(16,'Created Attendance for '.date('d-m-Y',$date),$aid);
                $staff=$this->db->query("select * from staff where iid='".$this->session->userdata('staff_Org_id')."'");
                $staff=$staff->result();
                foreach ($staff as $value) {
                   $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'date_id' =>$aid,
                    'staff' =>$value->id,
                    'status' =>0
                    );
                    $this->db->insert('staff_attendance',$data);
                }
              ?><script>
                window.location.replace("<?php echo base_url() ?>index.php/staff/edit_attendance/<?php echo $aid ?>");
                 </script><?php
            }else{
               $date=$this->check_date($date);
               ?><script>
                window.location.replace("<?php echo base_url() ?>index.php/staff/edit_attendance/<?php echo $date->id ?>");
                 </script><?php
               
            }
        }
        
        public function view_attendance($aid=0){
            if($aid ==0){
                redirect('/staff/attendance', 'refresh'); exit;
            }else{
               $k=  $this->check_day_id($aid);
               if(!$k){
                    redirect('/staff/attendance', 'refresh'); exit;
               }else{
                   $data['day']=$k;
                    $this->load->view('staff/view_dailyattendance',$data);
               }
            }
            
        }
        
        public function edit_attendance($aid=0){
            if($aid ==0){
                redirect('/staff/attendance', 'refresh'); exit;
            }else{
               $k=  $this->check_day_id($aid);
               if(!$k){
                    redirect('/staff/attendance', 'refresh'); exit;
               }else{
                   $data['day']=$k;
                   $this->load->view('staff/edit_attendance',$data);
               }
            }
        }
        
        public function save_attendance(){
            $post=$this->operations->cleanInput($_POST);
           
            $ids=explode(',',$post['ids']);
            foreach ($ids as $id) {
                if(strlen($post['status_'.$id])==0){
                    $this->form->setError('status_'.$id,'<i class="fa fa-info-circle"></i> Please enter marks');
                }
            }
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('/staff/edit_attendance/'.$post['att_date'], 'refresh'); exit;
            }else{ 
                foreach ($ids as $id) {
                   $data=array(
                       'status' =>$post['status_'.$id]
                   );
                   $this->db->where('id', $id);
                   $this->db->update('staff_attendance',$data);    
                }
                 $this->session->set_userdata('attendance_update', 'Sucessfuylly Updated Attendance');
                  $this->logs->insert_staff_log(16,'Edited Attendance ',$post['att_date']);
               
            }
           redirect('/staff/view_attendance/'.$post['att_date'], 'refresh'); 
        }

        
        
        private function incharge_availability($section){
            $query=  $this->db->query("SELECT *  FROM `section` WHERE `section` = '".$section."' ");
            if($query->num_rows!=0){
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        private function staff_count($level){
            if($level==4){
                $level="3,4";
            }
            if($level==7){
                $level="6,7,8";
            }
            
        //  echo   $level = implode(explode(",",$level));
            $this->db->where_in('level', $level);
            $this->db->where('iid', $this->session->userdata('staff_Org_id')); 
            $this->db->from('staff');
            
            
            $count= $this->db->count_all_results();
            
            return $count;
      }
      
        private function fetch_staff($level,$limit, $start) {
            if($level==4){
                    $level="3";
                }
                if($level==7){
                    $level="6,7,8";
                }
            
            $level = implode(explode(",",$level));
            $this->db->where_in('level', $level);
            $this->db->where('iid', $this->session->userdata('staff_Org_id'));  
            $this->db->limit($limit, $start);
            $query = $this->db->get("staff");
            return $query->result();
       }
        
        private function check_date($time){
            $query=  $this->db->query("SELECT * FROM `staff_attendance_date` WHERE `day` ='".$time."' AND `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()==0){
                return FALSE;
            }else{
                $query=$query->row();
                return $query;
            }
            
        }
        
        private function check_day_id($id){
            $query=  $this->db->query("SELECT * FROM `staff_attendance_date` WHERE id='".$id."' AND iid='".$this->session->userdata('staff_Org_id')."'  ");
            if($query->num_rows()>0){
                $query=$query->row();
                return $query;
            }else{
                return FALSE;
            }
        }
        
        private function check_staff_id($staff){
           $query=  $this->db->query("SELECT  s.* , m.amount as sal_amount FROM `staff` s JOIN salary m ON m.staff_id = s.id WHERE s.id='".$staff."' AND s.iid='".$this->session->userdata('staff_Org_id')."' AND level < 6");  
          if($query->num_rows()>0){
              return $query->row();
          }else{
              return FALSE;
          }
          
        }
        
        public function change_status($id="",$status=""){
            if( (strlen($id)==0) || (strlen($status)==0)  ){
                redirect('staff/View_staff','refresh');
            }else{
                if( ($status=='activate') || ($status=='deactivate') ){
                     $q= $this->check_staff_id($id);
                      if(!$q){
                         redirect('staff/View_staff','refresh');  
                      }else{
                          $sts=1;$action=" Account Activated";
                          if($status=='deactivate'){
                             $sts=0; $action=" Account De-Activated";
                          }
                          $data= array('status'=>$sts);
                          $this->db->where('id', $q->id);
                          $this->db->update('staff',$data);
                          $this->session->set_userdata('staff_add_Sucess',$q->email." ".$action);
                      }
                }else{
                   redirect('staff/View_staff','refresh'); 
                }
            }
            
            redirect('staff/View_staff','refresh'); 
        }
        
        public function Upload_snap(){
              $file="tmp_staff_".time().".jpg";
              move_uploaded_file($_FILES['webcam']['tmp_name'],'/var/www/html/schooln/assests_2/uploads/temp/'.$file);
              $this->session->set_userdata('staff_dummy_img', $file); 
          }
        public function upddate_bday_field(){
            $q="SELECT * from staff ";
            $q=$this->db->query($q);
            $q=$q->result();
            foreach ($q as $value) {
              $dob =explode("/",$value->dob);
              if(sizeof($dob)==3){
                $dob= mktime(0,0,0,$dob[1],$dob[0],$dob[2]);
                $dob= getdate($dob);
                $dob =$dob['mday']."/".$dob['mon'];
              }else{
                  $dob= getdate($value->dob);
                $dob =$dob['mday']."/".$dob['mon'];
              }
              print_r($dob);
               echo "<br/>".$dob;
               $data=array(
                            'bday'=>$dob
                        );
                 $this->db->where('id',$value->id);
                    $this->db->update('staff', $data); 
            }
        }
        
        public function incharges(){
            $this->load->view('staff\incharges');
        }

        public function add_incharge(){
            $section=  $this->input->post('section');
            $staff= $this->input->post('staff');

                $data = array(
                'cls_tch_id' => $staff,
                );
                $this->db->where('sid',$section);
                $this->db->update('section', $data); 
                $this->session->set_userdata('incharge_add_Sucess', 'Sucessfully Created Incharge'); 
                 ?><script>location.reload();</script><?php
        }

        
        public function make_timestamps(){
           $staff =  $this->db->query("SELECT * from staff ");
           $staff =$staff->result();
           
           foreach ($staff as $value) {
               $dob=$value->dob;
               $dob= explode("/",$dob);
               $doj=$value->doj;
               $doj= explode("/",$doj);
               if(sizeof($dob)==3){
                $dob= mktime(0,0,0,$dob[1],$dob[0],$dob[2]);
                $data=array(
                            'dob'=>$dob
                    );
                $this->db->where('id',$value->id);
                $this->db->update('staff', $data); 
               }
               if(sizeof($doj)==3){
                $doj= mktime(0,0,0,$doj[1],$doj[0],$doj[2]);
                $data=array(
                            'doj'=>$doj
                    );
                $this->db->where('id',$value->id);
               $this->db->update('staff', $data); 
               }
           }
           echo ' DOne ...................';
           
        }
}
