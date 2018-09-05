<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class academics extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
        }
        public function view_subject_course($subj=""){
            if(strlen($subj)==0){
                redirect('academics/subjects', 'refresh');
            }else{
                $sub="SELECT * from subjects where sid='".$subj."' AND iid='".$this->session->userdata('staff_Org_id')."' ";
                $sub = $this->db->query($sub);
                if($sub->num_rows()==0){
                    redirect('academics/subjects', 'refresh');
                }else{
                    $sub=$sub->row();
                    $data['subject']=$sub;
                        $this->load->view('academics/view_subject_course',$data);	
        
                }
            }
        }
	public function index()
	{
           redirect('academics/class_list', 'refresh');
	}
        
        public function class_list(){
            $this->load->view('academics/cls_list');	
        }
        
        public function create_cls(){
            $this->load->view('academics/create');
        }
        
        public function edit_class($cls=""){
           $cls= $this->check_cls($cls);
           if(!$cls){
              redirect('academics/class_list', 'refresh');
           }else{
              $data['class_data']=$cls;
              $this->load->view('academics/edit_class',$data);	
           }
        }
        
        public function sections($cls=""){
            
           $cls= $this->check_cls($cls);
           if(!$cls){
              redirect('academics/class_list', 'refresh');
           }else{
              $data['class_data']=$cls;
              $this->load->view('academics/createsection',$data);	
           }
            
        }
        
        public function edit_section($section=""){
            $section= $this->check_section($section);
           if(!$section){
              redirect('academics/sections', 'refresh');
           }else{
              $data['section_data']=$section;
              $this->load->view('academics/edit_section',$data);	
           }
        }
        
        public function view_section($section=""){
           $section= $this->check_section($section);
           if(!$section){
              redirect('academics/sections', 'refresh');
           }else{
              $data['section_data']=$section;
              $this->load->view('academics/view_section',$data);	
           } 
        }
        
        public function Cls_structure(){
            $data['results']=  $this->operations->Fetch_cls_structure();
            //classstructure
            $this->load->view('academics/classstructure',$data);
        }
        
        public function subjects(){
           $this->load->view('academics/subjects');  
        }
        
        public function edit_subject($subid){
          $this->db->from('subjects');
          $this->db->where('sid ', $subid);
          $this->db->where('iid', $this->session->userdata('staff_Org_id'));
          $q = $this->db->get();  
          if($q->num_rows()>0){
              $q=$q->row();
              $data['subject']=$q;
              $this->load->view('academics/editsubject',$data);  
          }else{
              redirect('academics/','refresh');
          }
          
        }
        
        public function Assign_incharge($section=""){
            $data['section']="";
            if( ($section!="") ||(!is_numeric($section)) ){
                $sec=$this->check_section($section);
                if(!$sec){
                    redirect('academics/Cls_structure','refresh');
                }else{
                    $data['section']=$sec;
                    $this->load->view('academics/incharge',$data);
                }
            }else{
                redirect('academics/Cls_structure','refresh');
            }
        }

        public function add_incharge(){
            $section=  $this->input->post('section');
            $staff= $this->input->post('staff');
            
            $data = array(
            'cls_tch_id' => $staff,
            );
            $this->db->where('sid', $section);
            $this->db->update('section', $data); 
            $section=  $this->check_section($section);
            $this->logs->insert_staff_log(13,'Added Incharge for section'.$section['section'],$section['sid']);
            $this->session->set_userdata('incharge_add_Sucess', 'Sucessfully Created Incharge'); 
            ?><script>location.reload();</script><?php
        }

        public function assign_sub(){
           $this->load->view('academics/assignsub');  
        }
       
        public function view_course($cls_id){
            $r=$this->operations->course_structure($cls_id);
           
           if(is_array($r)){
              $data['course']=$r;
             $data['class']=$cls_id;
             $this->load->view('academics/coursestructure',$data); 
           } 
           
        }
        
        public function course($section){
            $section=  $this->check_section($section);
            if(!$section){
              redirect('academics/sections', 'refresh');
           }else{
              $data['section_data']=$section;
              $this->load->view('academics/course',$data);	
           }
        }
        
        public function load_sections(){
            $cls=$_POST['cls_id'];
            $credential = array('iid' =>$this->session->userdata('staff_Org_id'),'class_id' =>$cls);
            $query = $this->db->get_where('section', $credential);
              $query=$query->result();
              $i=1;
              $ids="";
              foreach($query as $val){
                  ?>
                  <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="section_<?php echo $val->sid; ?>" value="<?php echo $val->sid; ?>" />
                        <?php echo $val->name ?>
                        </label>
                    </div>
                </div>
                 <?php
                 $ids.=",".$val->sid;                               
              }
              ?>
<span id="section_ids" hidden="" ><?php  echo substr($ids,1);?></span><br/>
<span id="section_list_err" style=" color: red"></span>
        <?php
        
        }
        
        public function edit_Cls(){
           
            
            $post=$this->operations->cleanInput($_POST);
            
            $clsid=$post['classid'];
            //setError
            
            $field = 'medium';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide medium of Study');
            }
            
           $field = 'clsname';
          
		if(strlen($post[$field]) ==  0)
		{
		   $this->form->setError($field,'* Please Provide Class Name');
		}
          if( (strlen($post[$field])!=  0) &&(strlen($post['medium'])!=  0) ){
              if(!$this->operations->check_cls_name( trim($post[$field]) , $clsid,trim($post['medium']) ) ){
                 $this->form->setError($field,'* Class Name Already Exists ');
              }
           } 	    
	  
             if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('academics/edit_class/'.$clsid, 'refresh'); exit;
         }else{
             $data = array(
                'name' => $post['clsname'],
                 'medium' =>$post['medium']
               );
              $this->db->where('id',$clsid);
             $this->db->update('class',$data);
             $this->logs->insert_staff_log(11,'Edited class '.$post['clsname'],$clsid);
              $this->session->set_userdata('class_add_Sucess', 'Updated Class Details'); 
              redirect('academics/class_list', 'refresh');
         }
        
         
         
        
        }
        
        public function Add_class(){

            if(!isset($_POST['submit'])){
                 redirect('academics/create_cls', 'refresh'); 
            }
             
            $post=$this->operations->cleanInput($_POST);
            //setError
            $field = 'medium';
          
		if(strlen($post[$field]) ==  0)
		{
		   $this->form->setError($field,'* Please Provide Medium Of Study');
		}
                $field = 'clsname';
          
		if(strlen($post[$field]) ==  0)
		{
		   $this->form->setError($field,'* Please Provide Class Name');
		}
           if( (strlen($post[$field])!=  0) &&(strlen($post['medium'])!=  0) ){
              if(!$this->operations->check_cls_name( trim($post[$field]) , "",trim($post['medium']) ) ){
                 $this->form->setError($field,'* Class Name Already Exists ');
              }
           } 	
           $field = 'noofsec';
           $sec_err=0;
	   if(strlen($post[$field]) ==  0)
		{  
                   $sec_err=1;
		   $this->form->setError($field,'* Please Provide No of Section');
		}else if(!is_numeric($post[$field])){
                     $sec_err=1;
                    $this->form->setError($field,'* Please Provide Numeric Value');
                }elseif($post[$field]==0){
                    $sec_err=1;
                    $this->form->setError($field,'* Please Provide No of Sections');
                }
                
                if($sec_err==0){
                    
                    for($i=1;$i<=$post['noofsec'];$i++){
                        $field = 'Secname_'.$i;
                        if(strlen($post[$field]) ==  0)
                        {
                           $this->form->setError($field,'* Please Provide Section Name');
                        }
                    } 
                    
                }
                
             if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('academics/create_cls', 'refresh'); 
         }else{
             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'name' => $post['clsname'],
                'numeric_val' => 1,
                 'medium' =>$post['medium']
               );
            
             $this->db->insert('class',$data);
             $cls_id=$this->db->insert_id(); 
             $this->logs->insert_staff_log(11,'Created Class  ' .$post['clsname'],$cls_id);
             for($i=1;$i<=$post['noofsec'];$i++){
                    $sec_data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                    'name' => $post['Secname_'.$i],
                    'class_id' => $cls_id,
                    'cls_tch_id'=>0
                   ); 

                   $this->db->insert('section',$sec_data);
                   $this->logs->insert_staff_log(12,'Created Section:'.$sec_data['name']." for ".$post['clsname'],$this->db->insert_id());
             }
             $medium = unserialize (medium);
             $this->session->set_userdata('class_add_Sucess', 'Class : '.$post['clsname'].'('.$medium[$post['medium']].') Created  Sucessfully '); 
         }
        
         redirect('academics/class_list', 'refresh'); 
        }
        
        public function print_section_info($section=""){
            $section= $this->check_section($section);
           if(!$section){
              redirect('academics/class_list', 'refresh');
           }else{
              $data['section_data']=$section;
              $students =  $this->fetch_students($section->sid);
              $institute=  $this->fetch_institute_details();
              $course = $this->fetch_course($section->sid);              
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
               ?>
                                    <h3 style="text-align: center"><strong>Section Info Of <?php echo $section->class." , ".$section->section ?></strong></h3>
                                    <br/><br/>
                                    <h4 style=" text-align: center; color:  teal">Course Structure</h4><br/>
                                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th>S.no</th>
                                                <th>Subject</th>
                                                <th>Faculty Name</th>
                                            </tr>
                                            <?php
                                            $i=1;
                                               foreach($course as  $value){
                                                    ?>
                                                   <tr >
                                                            <td ><?php echo $i++; ?></td>
                                                        <td  ><?php echo $value->subject; ?></td>
                                                        <td  ><?php if($value->name !=NULL ){
                                                                            echo $value->name;
                                                                        }else{
                                                                            echo "--";
                                                                        }?></td>

                                                    </tr>   
                                                   <?php
                                                  }
                                            ?>
                                    </table>
                                    
                                    <?php
                                    $timetable = $this->db->query("SELECT * FROM `timings`  WHERE section ='".$section->sid."' ");
        if($timetable->num_rows()==0){
            ?>
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h4 style=" text-align: center">Timetable</h4>
                </div>
                <div class="box-content nopadding" style=" text-align: center">
                    <strong>Time Table Not Yet Configured</strong>
                </div>
            </div>
           <?php
        }else{
            $timetable=$timetable->row();
        $q=$this->db->query("SELECT c.cid,s.subject  FROM `course` c JOIN subjects s ON c.subid=s.sid  WHERE `secid` = '".$section->sid."'");
        $q=$q->result();
        $course=array();
        foreach ($q as $key => $value) {
          $course[$value->cid] =$value->subject; 
        }
        
        ?>
                               <br/><hr/>     <h4 style=" text-align: center; color:  teal">Timetable</h4><br/><br/>
                 
                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
    
                            <tr>
                                <th>Day/timings</th>
                                <?php
                                $weekdays = unserialize (Week_days);
                                $start=$timetable->start;
                                $noofc=$timetable->classes;
                                $span=$timetable->span;
                                
                                $periods=$this->db->query("SELECT DISTINCT (time_start),time_end  FROM `class_routine` WHERE `tid` = '".$timetable->tid."'  ");
                                $periods =$periods->result();
                                $interval_arr =array();
                                $prev_end=0;
                                foreach($periods as $period){
                                     if( ($prev_end!=0) && ($prev_end !=$period->time_start) ){
                                         
                                         $interval_arr[] = array('period'=>'Break','start'=>$prev_end,'ending' =>$period->time_start); 
                                     }
                                    ?>
                                   <th><?php echo date("H:i",mktime(substr($period->time_start,0,strlen($period->time_start)-2), substr($period->time_start,strlen($period->time_start)-2)))  ?> - <?php echo date("H:i",mktime(substr($period->time_end,0,strlen($period->time_end)-2), substr($period->time_end,strlen($period->time_end)-2)))  ?></th>
                                    <?php
                                    $prev_end=$period->time_end;
                                }
                               
                                
                                ?>
                            </tr>
                       
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
                            <tr>
                                <th>Intervals </th>
                                <th colspan="<?php echo ($noofc) ?>" style=" color: red" >
                                   <?php
                                   $str= "";
                      foreach ($interval_arr as $value) {
                          
                          $str .= date("H:i",mktime(substr($value['start'],0,strlen($value['start'])-2), substr($value['start'],strlen($value['start'])-2))) . " - " .date("H:i",mktime(substr($value['ending'],0,strlen($value['ending'])-2), substr($value['ending'],strlen($value['ending'])-2))) ." ,";
                      }
                      echo $str=substr($str,0,  strlen($str)-1);
                                   ?>
                                </th>
                            </tr>
                    </table>
                 
       
       <?php
        }
        ?><br/><hr/>
                                       <h4 style=" text-align: center; color:  teal">Students List</h4><br/><br/>
                                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th>Roll</th>
                                                <th>Student Name</th>
                                                <th>Userid</th>
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
                                       <script>
                                         window.print();
                                    </script>
                                </body>
                            </html>
               <?php
           }
        }
        
        public function Add_section(){
           $post=$this->operations->cleanInput($_POST);
           $field = 'class_name';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Select Class ');
            }
           
            $field = 'Sectionname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Section Name ');
            }else{
              if(strlen($post['class_name'])!=0){
                 if(!$this->operations->check_section_name($post['class_name'],trim($post[$field]))){
                    //check_cls_val
                    $this->form->setError($field,'* Class Name Already Exists');
                } 
              }
                
            }
            
            $field = 'class_teacher';
          
            if(strlen($post[$field]) !=  0)
            {
              if(strlen($post['class_name'])!=0){
                 if(!$this->operations->check_section_name($post['class_name'],trim($post[$field]))){
                    //check_cls_val
                    $this->form->setError($field,'* Teacher Already Assignied to other Teacher');
                } 
              }
                
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               redirect('academics/sections/'.$post['class_name'], 'refresh'); 
         }else{
             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'name' => $post['Sectionname'],
                'class_id' => $post['class_name'],
                'cls_tch_id'=>$post['class_teacher']
               );
              $this->db->insert('section',$data);
              $this->logs->insert_staff_log(12,'Created Section  '.$post['Sectionname'],$this->db->insert_id());
              $this->session->set_userdata('class_add_Sucess', 'New Section Created ');   
               redirect('academics/class_list', 'refresh'); 
         }
           
            
        }
        
         public function editsection(){
             
           $post=$this->operations->cleanInput($_POST);
            
           
            $secid=$post['sectionid'];
            $field = 'Sectionname';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Section Name ');
            }else{
              if(strlen($post['class_name'])!=0){
                 if(!$this->operations->check_section_name($post['class_name'],trim($post[$field]),$secid)){
                    //check_cls_val
                    $this->form->setError($field,'* Section Name Already Exists');
                } 
              }
                
            }
            
            $field = 'class_teacher';
          
            if(strlen($post[$field]) ==  0)
            {
             // $this->form->setError($field,'* Please Select Class Incharge ');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
              
         }else{
             $data = array(
                'name' => $post['Sectionname'],
                'cls_tch_id'=>$post['class_teacher']
               );
              $this->db->where('sid',$secid);
              $this->db->update('section',$data);
              $this->logs->insert_staff_log(12,'Edited Section  '.$post['Sectionname'],$secid);
              $this->session->set_userdata('Section_add_Sucess', 'Sucessfully edited section details '); 
              redirect('academics/view_section/'.$secid, 'refresh'); 
          
         }
       
          redirect('academics/edit_section/'.$secid, 'refresh'); 
            
        }
        
        public function add_subject(){
            $subjects=  trim($this->input->post('subject'));
            $subjects = str_replace("\n", ",", $subjects);
           
            $error =0;
            $subjects = explode(",",$subjects);
            $subjects = array_filter($subjects);
            foreach($subjects as $subject){
                if(strlen($subject)<0){
                    $error++;
                        echo "** Please enter subject name";exit;
                    }elseif(!$this->operations->check_subject($subject)){
                            $error++;
                            echo "** ".$subject."  Already Exits";exit;
                    }
            }
            
            if($error==0){
                foreach($subjects as $subject){
                    $data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                    'subject' => $subject
                   );
                 $this->db->insert('subjects',$data);
                 $this->logs->insert_staff_log(14,'Add Subject '.$subject,$this->db->insert_id());

                 
                }
                  $this->session->set_userdata( 'subject_add_Sucess', 'Subject :'.$subject.' created Sucessfully'); 
                   ?><script>location.reload();</script><?php
            }
            
        }
        
        public function edit_sub(){
            //check_edit_subject
            $subject=  $this->input->post('subject');
            $subid=  $this->input->post('subid');
            if(strlen($subject)<0){
                $this->form->setError('subject','* Please enter subject name ');
                
            }elseif(!$this->operations->check_edit_subject($subject,$subid)){
                    $this->form->setError('subject','** Subject Already Exits ');
                   
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               redirect("academics/edit_subject/".$subid,'refresh');
         }else{
            
                $data=array(
                    'subject'=>trim($subject),
                );
                $this->db->where('sid',$subid);
                $this->db->update('subjects',$data);
                $this->logs->insert_staff_log(14,'Edited Subject '.$subject,$subid);
                $this->session->set_userdata('subject_add_Sucess', 'Subject Details updated Sucessfully'); 
                redirect("academics/subjects",'refresh');
              }
         
        }
        
        public function add_course(){
            $error=FALSE;
            $post=$this->operations->cleanInput($_POST);
            $field = 'section';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<br/>* Please Select Section ');
            }
            
            $field = 'subject_name';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<br/>* Please Select Subject ');
            }
            
            $field = 'sub_teacher';
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'<br/>* Please Select Teacher ');
            }
           if($this->form->num_errors >0 ){
               $error=TRUE;
               
           } else{
               if(!$this->operations->check_course($post['subject_name'],$post['section'])){
                  $error=TRUE;
                   $this->form->setError('subject_name','<br/>* Subject Already Assigned.');
                }
           }
         if($error){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
             }else{
             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'subid' => $post['subject_name'],
                'secid' => $post['section'],
                 'tid'=>$post['sub_teacher']
               );
              $this->db->insert('course',$data);
             $this->logs->insert_staff_log(15,'Added Subject To the Course',$this->db->insert_id());
             $this->session->set_userdata('course_add_Sucess', 'Subject Added to course sucessfully');   
         }
          redirect('academics/assign_sub', 'refresh'); 
               
        }
       
        private function check_section($section){
            $query=  $this->db->query("SELECT s.sid,s.name as section,c.id as class_id,c.name as class,c.medium ,st.name as staff_name,st.id as staff_id FROM `section` s LEFT JOIN staff st  on s.cls_tch_id=st.id JOIN class c ON s.class_id=c.id WHERE sid='".$section."'");
            if($query->num_rows()==0){
                return FALSE;
            }else{
               $query=$query->row(); 
               return $query;
            }
            
            
        }
        
       private function check_cls($clsid){
            $credential = array('id' =>$clsid,'iid'=>$this->session->userdata('staff_Org_id'));
            $query = $this->db->get_where('class', $credential);
            if ($query->num_rows() > 0) {
                $query=$query->row();
                return $query;
            }else{
                return FALSE;
            }
       }
        
       public function add_section_names(){
           $noofsec=  $this->input->post('noofsec');
           if(!is_numeric($noofsec)){
                ?>
                <script> $('#noofsecerr').html(" Invalid Numeric Value");</script>
                <?php
           }else{
               ?>
                <script> $('#noofsecerr').html(" ");</script>
                <?php
               for($i=1;$i<=$noofsec;$i++){
                   ?>
                <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Section - <?php echo $i; ?></label>
                        <div class="col-sm-10">
                            <input type="text" style=" width: 100%" id='Secname_<?php echo $i; ?>' name="Secname_<?php echo $i; ?>"  placeholder="name"   class="form-control" value="<?php  echo $this->form->value('Secname_'.$i); ?>" >
                            
                            <span id='noofsecerr' style=" clear: both; color: red">
                                    <?php
                                        echo $this->form->error('Secname_'.$i);   
                                    ?>
                            </span>        
                        </div>
                </div>
                  <?php
               }
           }
           
       }
       
       private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
       
        private function fetch_students($section){
            $query=  $this->db->query("SELECT * FROM `student` WHERE section_id='".$section."' AND iid='".$this->session->userdata('staff_Org_id')."'");
            $query=$query->result();
            return $query;
        }
        
        private function fetch_course($section){
            $query=  $this->db->query( "SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$section."' AND sec.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY sec.sid , s.sid ASC ");
            $query=$query->result();
            return $query;
        }
       
        public function assign_faculty(){            
            $this->load->view('academics/assign_faculty');  
        }
        
        public function add_faculty(){
            
            $post=$this->operations->cleanInput($_POST);
            $course_details =$_POST['course_details'];
            $course_details = json_decode($course_details);
          
            $subjects_all = $this->input->post("subjects_all");
            $subjects_all = array_filter(explode(",",$subjects_all));
            foreach($subjects_all as  $val){
                if(strlen($this->input->post("subject_".$val))!=0){
                    if(strlen($this->input->post("sub_staff_".$val))==0){
                        $this->form->setError("sub_staff_".$val,'* Please select staff ');
                    }else{
                        if($this->input->post("sub_staff_".$val)==0){
                           $this->form->setError("sub_staff_".$val,'* Please select staff '); 
                        }
                    }
                }
            }
            
             if($this->form->num_errors >0){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('academics/assign_faculty?section='.$this->input->post("section_val"), 'refresh'); 
             }else{
                 
                 foreach($subjects_all as  $val){
                    
                    if(strlen($this->input->post("subject_".$val))!=0){
                        if(sizeof($course_details)==0){
                            $data = array(
                                        'iid' => $this->session->userdata('staff_Org_id'),
                                        'subid' => $val,
                                        'secid' => $this->input->post("section_val"),
                                         'tid'=>$this->input->post("sub_staff_".$val)
                                       );
                            $this->db->insert('course',$data);
                            $this->logs->insert_staff_log(15,'Added Subject To the Course',$this->db->insert_id());
                            $this->session->set_userdata('course_alter_sucess', 'Updated Course Structure');  
                        }else{
                            if(isset($course_details->$val)){

                               $data = array(
                                         'tid'=>$this->input->post("sub_staff_".$val)
                                       );
                                $this->db->where('cid', $course_details->$val->course_id);
                                $this->db->update('course',$data);  
                                $this->logs->insert_staff_log(15,'Updated Faculty for course',$course_details->$val->course_id);
                            }else{
                                $data = array(
                                            'iid' => $this->session->userdata('staff_Org_id'),
                                            'subid' => $val,
                                            'secid' => $this->input->post("section_val"),
                                             'tid'=>$this->input->post("sub_staff_".$val)
                                           );
                                $this->db->insert('course',$data);
                                $this->logs->insert_staff_log(15,'Added Subject To the Course',$this->db->insert_id());
                                

                            }
                        }
                        $this->session->set_userdata('course_alter_sucess', 'Updated Course Structure');  
                        
                    }
                }
              
                 redirect('academics/subjects', 'refresh');   
         }
            
            
            
            
        }
        
}
