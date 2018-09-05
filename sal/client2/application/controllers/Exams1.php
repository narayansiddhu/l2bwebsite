<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exams1 extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('examinations');
            $this->load->model('form');
            $this->load->library("pagination");$this->operations->is_login();
            /* cache control */
            
            $check="SELECT `exams` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->exams==0){
                 $this->session->set_userdata('blocked_module', 'Library Module'); 
                redirect("/Acessdenied/","refresh");
            }
        }
    
    public function index(){
        $this->load->view('exams/view');
    }
    
    public function view(){
        $this->load->view('exams/view');
    }
    
    public function add(){
        $this->load->view('exams/add');
    }
    
    public function assign($eid){
       
        $exam=$this->examinations->getexam_data($eid);
        if(!$exam){
             redirect('exams/index','refresh');
        }else{
            $data['exam']=$exam;
             $this->load->view('exams/assign',$data);
        }
    }
    
    public function view_settings($eid=0){
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
                $data['exam']=$exam;
                if($exam->type==1){
                    $this->load->view('exams/view_settings',$data);
                }else if($exam->type==2) {
                     $this->load->view('exams/mcsettings',$data);
                }else if($exam->type==3) {
                     $this->load->view('exams/view_daily_exam_settings',$data);
                }else if($exam->type==4) {
                     $this->load->view('exams/view_formative',$data);
                }else if($exam->type==5) {
                     $this->load->view('exams/view_sum_settings',$data);
                }
              //  print_r($data); 
                 
            }
        } 
    }
    
    public function settings($eid=0){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
           
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
           //  print_r($exam);
                $data['exam']=$exam;
                if($exam->type==1){
                    $this->load->view('exams/settings',$data);
                }elseif($exam->type==2){
                    $this->load->view('exams/settings1',$data);
                }elseif($exam->type==3){
                     $this->load->view('exams/settings2',$data);
                }elseif($exam->type==4){
                    $this->load->view('exams/formative_Settings',$data);
                }elseif($exam->type==5){
                     $this->load->view('exams/summative_settings',$data);
                }
                 
            }
        }
    }
        
    public function create(){
        $post=$this->operations->cleanInput($_POST);
        $field = 'exam';
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide Exam Name');
        }elseif(!$this->examinations->checkexamname($post[$field])){
             $this->form->setError($field,'* Exam Name Already Exists');
        }
        
        $field = 'type';
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide Exam Type');
        }
        
        $field = 'strtdate';
        $str=0;
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please provide Starting date');
        }else{
            $str=explode("/",$post[$field]);
            $start= mktime(0,0,0,$str[1],$str[0],$str[2]);
            if($start <=time()){
                $this->form->setError($field,'* Please select a valid starting date');
            }
        }
        
        $field = 'enddate';
        $end=0;
            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
                $end= mktime(0,0,0,$str[1],$str[0],$str[2]);
                if($end <=time()){
                    $this->form->setError($field,'* Please select a valid ending date');
                }elseif($start!=0){
                   if($end<$start){
                       $this->form->setError($field,'* Please select a valid ending date'); 
                   }
                }
                
            }
            
       if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('exams/add', 'refresh');  
        }else{
            $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'exam' => $post['exam'],
                       'startdate' => $start,
                       'enddate'=>$end,
                       'status' =>1,
                       'type'   =>$post['type']
                    );
             $this->db->insert('examinations',$data);
             $examid=$this->db->insert_id(); 
             $this->logs->insert_staff_log(17,'Created Examination ' .$post['exam'],$examid);
             redirect('exams/assign/'.$examid, 'refresh');
        }       
        
    }
    
    public function add_daily_exams(){
        
    }
    
    public function halltickets($ecid){
        $institute =  $this->fetch_institute_details();
            
           echo  $html_header='<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hall Ticket</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:13px;}
.total {width:780px;margin-left:auto;padding:2px; margin-right: auto;}
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:0.9em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:110px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:1px 1px; vertical-align:text-top;font-size:12px; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:1px 0px;background-color:#E3E3E3;font-size:12px; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:110px;font-size:13px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:1px 1px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:1px 0px; }
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
<body>';
            $inst_html='<div class="total"  style="clear:both; min-height: 305px;">
<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'" width="148" height="77" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
'.str_replace("\n", "<br/>", $institute->address).'</p>
</div>
</td>
</tr>
</table>
<hr color="#00306C" style="clear:both;"/>';
            $medium= unserialize(medium);
            $query=  $this->db->query(" SELECT s.sid,s.name as section,c.medium,c.name as cls_name,ex.exam FROM examination_cls e JOIN section s ON e.sectionid=s.sid JOIN class c ON s.class_id=c.id JOIN examinations ex ON e.examid=ex.id WHERE e.id = '".$ecid."' ");
            $query=$query->row();
            $student_details=$this->db->query("SELECT name,userid,roll,photo FROM `student` WHERE section_id='".$query->sid."' ");
            $cls_sec=$query->cls_name." -" .$query->section;
            $e_name =$query->exam;
            $medium =$medium[$query->medium];
            $student_details=$student_details->result();
            $exam_schedule='<h3 style="text-align: center ; color:#0c4472"><strong>Exams Details</strong></h3><br/>'.'<table cellpadding="1" cellspacing="1" border="1" style=" color:#0c4472 ; ">';
            $query=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.minmarks,s.subject FROM exam e JOIN subjects s ON e.subid=s.sid WHERE e.ecid='".$ecid."' ORDER BY e.examdate asc");
            $query=$query->result();
            $i=1;
            $subjects_tr ='<tr><th>SUBJECTS</th>';
            $timmings_tr ='<tr><th>TIMINGS</th>';
            $date_tr='<tr><th>DATE</th>';
            foreach ($query as $value) {
        
                if($value->examdate ==0){
                    $date=   "--";
                }else{
                   $date=  date('d-m-Y',$value->examdate);
                }
                if($value->starttime ==0){
                         $timings= "__"." - "."__";
                     }else{
                     $timings=  date('H:i',$value->starttime)." - ".date('H:i',$value->endtime) ;
                     }
                
                    if($value->timespan ==0){
                         $span= "--";
                     }else{
                     $span= $value->timespan;
                     }
                $subjects_tr.='<th>'.strtoupper($value->subject).'</th>';
                $timmings_tr.='<td >'.$timings.'</td>';
                $date_tr.='<td>'.$date.'</td>';
                  
            }
            $subjects_tr.='</tr>';
            $timmings_tr.='</tr>';
            $date_tr.='</tr>';
            
            $exam_schedule='
<div style="clear:both"  class="subject">Hallticket Of&nbsp; <span style="color:red">'.$e_name.'</span><!--for Cement.--></div>
<table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
';  
            $exam_schedule.=$subjects_tr; 
            $exam_schedule.=$date_tr; $exam_schedule.=$timmings_tr; 
            
           $exam_schedule.='</table><div class="right">
<br />
<strong>Authorized Signatory</strong>
</div>
<br style="clear:both" />

<div style="clear:both;border-bottom:1px dashed #000000;padding-bottom:1px;margin-bottom:1px;" >&nbsp;</div>
<br style="clear:both" /><br style="clear:both" />
</div>'; $i=1;
            foreach($student_details as $student){
                    $std_image="dummy_user.png";
                      if((strlen($student->photo)!=0)){
                         if(!file_exists(assets_path."/uploads/".$student->photo)){
                           $std_image =$student->photo  ;
                         }            
                      }
                $stud_details=$inst_html;
                $stud_details.='<div style="border:2px solid #00306C;height:100px;">
                                    <div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="70" style="padding:5px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:73px;">&nbsp;</div>
                                    <div  style="float:left;padding:2px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$student->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$cls_sec.'</th>
                                    </tr>
                                    <tr>
                                            <td>Medium</td>
                                        <td>:</td>
                                        <th>'.$medium.'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$student->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>R.No</td>
                                        <td>:</td>
                                        <th>'.$student->roll.'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div><br/>
                                    ';
                 $stud_details.=$exam_schedule;
                 echo $stud_details;
            }
      
            ?>
<script>window.print();</script>
             <?php
           echo "</body></html>";
        }
    
    public function mchalltickets($ecid){
         $institute=  $this->fetch_institute_details();   
           echo  $html_header='<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hall Ticket</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:13px;}
.total {width:780px;margin-left:auto;padding:2px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:0.9em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:110px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:1px 1px; vertical-align:text-top;font-size:12px; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:1px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:110px;font-size:13px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:1px 1px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:1px 0px; }
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
<body>';
            $inst_html='<div class="total"  style="">
<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'" width="148" height="77" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
'.str_replace("\n", "<br/>", $institute->address).'</p>
</div>
</td>
</tr>
</table>
<hr color="#00306C" />';
            $medium= unserialize(medium);   
            $query=  $this->db->query("SELECT s.sid,s.name as section,c.name as cls_name,c.medium,ex.exam FROM examination_cls e JOIN section s ON e.sectionid=s.sid JOIN class c ON s.class_id=c.id JOIN examinations ex ON e.examid=ex.id WHERE e.id='".$ecid."' ");
            $query=$query->row();
            $medium=$medium[$query->medium];
            $student_details=$this->db->query("SELECT name,userid,roll,photo FROM `student` WHERE section_id='".$query->sid."' ");
            $cls_sec=$query->cls_name." -" .$query->section;
            $e_name=$query->exam;
            $student_details=$student_details->result();
            $query=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.questions,s.subject FROM mcexam e JOIN subjects s ON e.subid=s.sid WHERE e.ecid='".$ecid."' ORDER BY e.examdate asc");
            $query=$query->result();
            $i=1;
            $subjects_tr ='<tr><th>SUBJECTS</th>';
            $timmings_tr ='<tr><th>TIMINGS</th>';
            $date_tr='<tr><th>DATE</th>';
            foreach ($query as $value) {
                if($value->examdate ==0){
                    $date=   "--";
                }else{
                   $date=  date('d-m-Y',$value->examdate);
                }
                if($value->starttime ==0){
                         $timings= "__"." - "."__";
                     }else{
                     $timings=  date('H:i',$value->starttime)." - ".date('H:i',$value->endtime) ;
                     }
                
                    if($value->timespan ==0){
                         $span= "--";
                     }else{
                     $span= $value->timespan;
                     }
                $subjects_tr.='<th align="center">'.$value->subject.'</th>';
                $timmings_tr.='<td align="center">'.$timings.'</td>';
                $date_tr.='<td align="center">'.$date.'</td>';         
            }
            $subjects_tr.='</tr>';
            $timmings_tr.='</tr>';
            $date_tr.='</tr>';
            $exam_schedule='
<div style="clear:both"  class="subject">Hallticket Of&nbsp; <span style="color:red">'.$e_name.'</span><!--for Cement.--></div>
<table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
';  
            $exam_schedule.=$subjects_tr;  
            $exam_schedule.=$date_tr; 
            $exam_schedule.=$timmings_tr; 
            $exam_schedule.='</table>'; $i=1;
            $exam_schedule.='</table><div class="right">
<br />
<strong>Authorized Signatory</strong>
</div>
<br style="clear:both" />
</div>
<div style="border-bottom:1px dashed #000000;padding-bottom:1px;margin-bottom:1px;" class="total">&nbsp;</div>'; $i=1;
            foreach($student_details as $student){
                    $std_image="dummy_user.png";
                      if(!(strlen($student->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$student->photo)){
                           $std_image =$student->photo  ;
                         }            
                      }
                $stud_details=$inst_html;
                $stud_details.='<div style="border:2px solid #00306C;height:90px;">
                                    <div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="70" style="padding:5px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:73px;">&nbsp;</div>
                                    <div  style="float:left;padding:2px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$student->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$cls_sec.'</th>
                                    </tr>
                                    <tr>
                                            <td>Medium</td>
                                        <td>:</td>
                                        <th>'.$medium.'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$student->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>R.No</td>
                                        <td>:</td>
                                        <th>'.$student->roll.'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div>
                                    ';
                 $stud_details.=$exam_schedule;
                 echo $stud_details;
            }
             ?>
<script>window.print();
</script>
             <?php
            echo "</body></html>";
        }
        
    public function savesettings(){
       
       $post=$this->operations->cleanInput($_POST);
        $exam_timings=array();
        $exam_ids=explode(",",$post['course_ids']);
        foreach($exam_ids as $exam_id){
            if(isset($post['subject_'.$exam_id])){
                 $iner_count=0;
           $field="day_".$exam_id;
           if(strlen($post[$field])==0){
               
              $this->form->setError($field,'* select Exam date'); 
           }else{
                $examdate=explode('-',$post["day_".$exam_id]);                  
                $frm = $post["start_".$exam_id]; 
                $to  =$post["ending_".$exam_id]; 
                 $time_err_c=0;
                if(strlen($frm)==0){
                    $time_err_c++;
                    $this->form->setError("start_".$exam_id,'* select From Time'); 
                }
                if(strlen($to)==0){
                    $time_err_c++;
                    $this->form->setError("ending_".$exam_id,'* select End Time'); 
                }
                            
                if($time_err_c ==0){
                    $d=array();
                    $c_d=explode(',',$post['course_Details_'.$exam_id]);
                        $d['subject']=$c_d[1];
                        $d['date']=mktime(0,0,0,$examdate[1],$examdate[0],$examdate[2]);
                        $d['from']=  $this->validate_time($frm, $examdate);
                        $d['end']=$this->validate_time($to, $examdate);
                        $d['span']= ($d['end'] - $d['from'])/60;
                        if(!$this->check_slot($d['from'], $d['end'], $exam_timings)){
                           $this->form->setError('start_'.$exam_id,'* enter exam span');  
                        }else{
                            if($d['span']==0){
                                     $this->form->setError('ending_'.$exam_id,'* enter Valid End time');  
                            }else{
                            
                            $exam_timings[$exam_id]=$d;
                            }
                        }                
                }              
                
              }
          $field="max_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter max marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value max');
           }  
           $field="min_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter min marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value min');
           }
            }
            
           
        }
       
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
         }else{
             $c=0;
             $older_entry ="";
                          
              foreach($exam_ids as $exam_id){
                  if(isset($post['subject_'.$exam_id])){
                      if(isset($post['old_exam_id_'.$exam_id])){
                          $older_entry.=$post['old_exam_id_'.$exam_id].",";
                          $data = array(
                                    'examdate' => $exam_timings[$exam_id]['date'],
                                    'starttime' => $exam_timings[$exam_id]['from'],
                                    'endtime' => $exam_timings[$exam_id]['end'],
                                    'timespan' => $exam_timings[$exam_id]['span'],
                                    'maxmarks' =>  $post['max_'.$exam_id],
                                    'minmarks' =>  $post['min_'.$exam_id],
                                    );

                            $this->db->where('id',$post['old_exam_id_'.$exam_id]);
                            $this->db->update('exam', $data);
                      }else{
                            $c_d = explode(',', $post['course_Details_'.$exam_id]);
                            $data = array(
                              'iid' =>$this->session->userdata('staff_Org_id'),
                              'examid'=>$post['examid'],
                              'ecid'=>$post['ecid'],
                              'courseid' =>$c_d[0],
                              'subid' =>$c_d[1],
                              'examdate' => $exam_timings[$exam_id]['date'],
                              'starttime' => $exam_timings[$exam_id]['from'],
                              'endtime' => $exam_timings[$exam_id]['end'],
                              'timespan' => $exam_timings[$exam_id]['span'],
                              'maxmarks' =>  $post['max_'.$exam_id],
                              'minmarks' =>  $post['min_'.$exam_id],
                              );
                             $this->db->insert('exam', $data);  
                      }
                  }
                  
                
               $c++;
              //  $this->db->where('id', $exam_id);
              //  $this->db->update('exam', $data);  
             };
             
             $older_entry = substr($older_entry, 0,strlen($older_entry)-1);
             if($older_entry!=$post['old_ids']){
                 $or=explode(',',$post['old_ids']);
                 $older_entry;
                 $older_entry = explode(',',$older_entry);
                 $diff=(array_diff($or, $older_entry));
                 foreach ($diff as $value) {
                     $this->db->where('id',$value);
                     $this->db->delete('exam');
                 }
             }
           $this->session->set_userdata('Section_exam_Settings', 'Sucessfully updated settings');   
           redirect('exams/settings/'.$this->input->post('examid'), 'refresh'); 
          
         }
         redirect('exams/settings/'.$this->input->post('examid')."?section=".$this->input->post('section_details'), 'refresh'); 
    }
    
    private function check_slot($from,$to,$arr){
        
        foreach ($arr as $value) {
            
          if((($value['from'] <= $from) && ($from <= $value['end'])) ){
            
              return FALSE;
          }
          if((($value['from'] <= $to) && ($to <= $value['end'])) ){
          
              return FALSE;
          }
          
        }        
        return true;
    }
    
    public function configure(){
        
        $post=$this->operations->cleanInput($_POST);
        $examid=$post['exam_id'];
        $action=$post['action'];
        $sec=explode(',',$post['section_ids']);
        $counter=0;
        if($action=='insert'){
            foreach ($sec as $value) {
                if(isset($post['section_'.$value])){
                    $counter++;
                    $ecid=$this->examinations->add_exam($value,$examid); 
                   // $this->examinations->add_exam_subjects($value,$ecid,$examid);  
                }
            }
        }else{
            $previous=explode(',',$post['previous']);
            foreach ($sec as $value) {
                if(isset($post['section_'.$value])){
                    $counter++;
                    if(in_array($value, $previous)){
                       unset($previous[array_search($value, $previous)]);
                      // $ecid=$this->examinations->check_exams($value,$examid);  
                    }else{
                        $ecid=$this->examinations->add_exam($value,$examid); 
                     //$this->examinations->add_exam_subjects($value,$ecid,$examid);  
                    }
                    
                }
            }
            
            if(sizeof($previous)>0){
                foreach ($previous as $value) {
                   $this->examinations->deactive_exam($examid,$value);
                }
            }
            
        }
  
        if($counter==0){
             $this->session->set_userdata('class_exam_error', "Please select the section For the exam "); 
           redirect('exams/assign/'.$examid, 'refresh'); 
        }else{
            redirect('exams/settings/'.$examid, 'refresh');
        }
    }
    
    public function validate_time($ti , $examdate){
       $ti = explode(" ", $ti);
       $part1=$ti[0];
       $part2=$ti[1];
       $part1 = explode(":", $part1);
       $hours=$part1['0'];
       if($part2=="PM"){
           if($hours!=12){
              
                $hours= $hours+12;
                if($hours>=24){
                    $hours=$hours-24;
                }
           }
       }
     
        return mktime($hours,$part1[1],0,$examdate[1],$examdate[0],$examdate[2]);
    }
    
    public function check_results(){
       $this->load->view('exams/check_results');
    }
    
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
    public function load_sections(){
        $exam =$this->input->post("exam");
    }
    
    public function results($eid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
               $data['exam']=$exam;
               //summative_result
               if($exam->type==5){
                 $this->load->view('exams/summative_result',$data);
               }elseif($exam->type==4){
                 $this->load->view('exams/view_formative_result',$data);
               }elseif($exam->type==2){
                 $this->load->view('exams/mc_results',$data);
               }elseif($exam->type==1){
               $this->load->view('exams/results',$data);
               }
            }
        }
    }
    
    public function print_mc_result($eid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
               $data['institute']=$this->fetch_institute_details();
               $data['exam']=$exam;
               $this->load->view('exams/print_mc_result',$data);
                
            }
        }
    }
    
    public function print_schedule($eid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
              $institute =  $this->fetch_institute_details();
               $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
               $query=$query->result();
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Schedule </title>
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
<h3 style=" text-align: center; color:  #009999; font-size: 18px ">Schedule Of <?php echo $exam->exam ?></h3>
                            <div class="box" id="exam_schedule">
            
      <?php
        foreach ($query as  $value) {
            ?> <br/>  <h4 style=" text-align: center; color:  #66cc00">
                        <u><?php echo strtoupper($value->class." - ".$value->section) ?> </u>
                    </h4><br/>
                  <?php
                    $this->viewexam_Settings($value->id."-".$value->sid,$exam->startdate,$exam->enddate);
                  ?>
                
          <?php
        }
       ?>
                 </div>
                            
                        
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
    
    private function get_class_Section_detail($section){
           
           $query = $this->db->query("SELECT s.name as section ,c.name as class FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.sid='".$section."'");
           $query=$query->row();
           return $query;
        }
    
    public function viewexam_Settings($ecid,$from,$end){
            
            $ecid=  explode("-", $ecid);
           // echo "SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc";
            $query=  $this->db->query("SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc");
            
            $days =array();
            $t=$strt=$from;
            $end=$end;
            while($t<=$end){
              $days[]=date("d-m-y",$t);
              $t=strtotime('+1 day', $t);
            }
            
            $sectiondetails=  $this->get_class_Section_detail($ecid[1]);
            ?>
                                           
            <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <thead>
                                <tr>
                                    
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Timings</th>
                                    <th>Max Marks</th>
                                    <th>Pass Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                  $ids="";
                                if($query->num_rows()>0){
                                    $query=$query->result();
                                
                                    foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                          <?php echo date("d-m-y",$value->examdate); ?>
                                           </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <?php echo $starttime ?>
                                            --
                                            <?php
                                        if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->endtime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                             echo $starttime;  
                                          ?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('min_'.$value->id))>0 ){
                                                  echo $this->form->value('min_'.$value->id);
                                                }else{
                                                  echo $value->minmarks; 
                                                }?></td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="5" style=" text-align: center ; color: red">** Schedule Not Configured</td>
                                    </tr>
                                     <?php
                                }
                                
                                  
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            </tbody>
                            
                         </table> 
                        
            <?php
            
            
            
        }
    
    public function viewmcexam_Settings($ecid,$from,$end){
            
            $ecid=$ecid;
           // echo "SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc";
            $query=  $this->db->query("SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`questions` FROM `mcexam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc");
            
            $days =array();
            $t=$strt=$from;
            $end=$end;
            while($t<=$end){
              $days[]=date("d-m-y",$t);
              $t=strtotime('+1 day', $t);
            }
            
            $sectiondetails=  $this->get_class_Section_detail($ecid[1]);
            ?>
                                           
            <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                    
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Timings</th>
                                    <th>Max Marks</th>
                                    <th>Questions</th>
                                </tr>
                                <?php 
                                
                                  $ids="";
                                if($query->num_rows()>0){
                                    $query=$query->result();
                                
                                    foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                          <?php echo date("d-m-y",$value->examdate); ?>
                                           </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <?php echo $starttime ?>
                                            --
                                            <?php
                                        if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->endtime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                             echo $starttime;  
                                          ?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('min_'.$value->id))>0 ){
                                                  echo $this->form->value('min_'.$value->id);
                                                }else{
                                                  echo $value->questions; 
                                                }?></td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="5" style=" text-align: center ; color: red">** Schedule Not Configured</td>
                                    </tr>
                                     <?php
                                }
                                
                                  
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            
                         </table> 
                        
            <?php
            
            
            
        }
    
    public function upload_mcmarks_sheet(){
      
        $file= $_FILES['sheet'];
            $filename=$file['name'];
            if(strlen(trim($filename)) == 0)
            {
                $this->form->setError('sheet','* file is supporrt only csv/txt format');
            }else{
                $end=explode('.',$filename);
                $end = strtolower(end($end));

                $type = array("csv", "txt");

                if(!(in_array($end, $type)))
                {
                        $this->form->setError('sheet','* supports only csv/txt format');
                }
            }

            
            if($this->form->num_errors >0 )
            {
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect("exams/add_marks/".$this->input->post("exam_id")."?mcid=".$this->input->post("mcid")."&subject=".$this->input->post('examid'),'refresh');
            }
            else
            {
                $file_pointer = fopen($file['tmp_name'], "r");
                $file_read = fread($file_pointer, $file['size']);

                $newdata=$file_read;
                $ex=  array_filter(explode("\n",$newdata));
                if(!strpos(strtolower($ex[0]),"rollno,student")){
                    array_shift($ex); 
                }
                $arr = array();
                $rolls ="";
                foreach($ex as $val){
                   $val = explode(",",$val); 
                   $rolls.=$val[0].","; 
                   $arr[$val[0]]= $val;
                }
                //print_r($arr);
                $rolls = substr($rolls, 0, strlen($rolls)-1);
                $db_rolls="";
                $q=  $this->db->query("SELECT student_id,name,roll FROM `student` WHERE `section_id` = '".$this->input->post("section")."' ");
                $q= $q->result();
                $student_details =array();
                foreach($q as $val){
                   $student_details[$val->roll]= array("roll"=>$val->roll,'stud_id'=>$val->student_id,'name'=>$val->name); 
                   $db_rolls.=$val->roll.","; 
                }
                $db_rolls = substr($db_rolls, 0, strlen($db_rolls)-1);
                $rolls = array_filter( explode(",",$rolls));
                $failed_records=array();
                $invalid_records = array();
                $valid_Studid="";
                foreach($rolls as $r){
                    if(array_key_exists($r, $student_details)){
                      // print_r(  $arr[$r]);exit;
                        if( !( (is_numeric($arr[$r][2])) &&(is_numeric($arr[$r][3])) &&(is_numeric($arr[$r][4]))) ) {
                            if(!($arr[$r][4]> $this->input->post("maxmarks")) ){
                                $failed_records[$student_details[$r]['stud_id']]= array("details"=>$student_details[$r],'marks'=>$arr[$r][4],'error'=>"Greater Marks");
                            }else{
                                if( ( ($arr[$r][2]+$arr[$r][3])<= $this->input->post("questions")) ){
                                        $data = array(
                                         'iid' => $this->session->userdata('staff_Org_id'),
                                         'exam_id' => $this->input->post("examid"),
                                         'student_id' => $student_details[$r]['stud_id'] ,
                                         'marks' =>$arr[$r][4],
                                         'correct' =>$arr[$r][2],
                                         'wrong' =>$arr[$r][3],
                                        );
                 //                       print_r($data);
                                        $valid_Studid.=$r.",";
                                       $this->db->insert('mcmarks',$data);  

                                    }else{
                                        $failed_records[$student_details[$r]['stud_id']]= array("details"=>$student_details[$r],'marks'=>$arr[$r][4],'error'=>"Invalid Correct & wrogn Values");
                                     }
                            }
                        }else{
                           $failed_records[$student_details[$r]['stud_id']]= array("details"=>$student_details[$r],'marks'=>$arr[$r][4],'error'=>"Enter Numeric values");
                            
                        }
                            
                        }else{
                          $invalid_records[]= array("details"=>$arr[$r],"error"=>'invalid record entry');
                    }
                }
                $valid_Studid =  substr($valid_Studid, 0, strlen($valid_Studid)-1);
                $valid_Studid = array_filter(explode(",",$valid_Studid));
                $db_rolls = array_filter(explode(",", $db_rolls));

                $missing = array_diff($db_rolls, $valid_Studid);
                foreach( $missing as $val){
                  $failed_records[$student_details[$val]['stud_id']]= array("details"=>$student_details[$val],'marks'=>0,'error'=>"Not entered");
                }
                
                if( (sizeof($failed_records) >0 ) || (sizeof($invalid_records) >0 ) ){
                    $this->session->set_userdata("failed_records",$failed_records);
                    $this->session->set_userdata("dummy_records",$invalid_records);
                    $this->session->set_userdata("examid",  $this->input->post("examid"));
                    $this->session->set_userdata("maxmarks",  $this->input->post("maxmarks"));
                    redirect("exams/view_mc_marks/".$this->input->post("exam_id")."?mcid=".$this->input->post("mcid")."&subject=".$this->input->post('examid'),'refresh');
                }else{
                    $sec=$this->input->post("mcid");
                    $sec = explode(",",$sec);
                    $sec= $sec[1]."-".$sec[0];
                    redirect("exams/results/".$this->input->post("exam_id")."?section=".$sec,"refresh");
                }
                
                 
            }
    }
    
    public function submit_marks_failed_records(){
      
        $post=$_POST;
        $rolls = $this->input->post("rolls");
        $rolls= explode(",",$rolls);
        $rolls = array_filter($rolls);
        foreach ($rolls as $value) {
            $ic=0;
            $field= "correct_".$value;
            if(!is_numeric($this->input->post($field))){
                if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }
                
            }
            $field= "wrong_".$value;
            if(!is_numeric($this->input->post($field))){
               if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }    
            }
            $field= "marks_".$value;
            if(!is_numeric($this->input->post($field))){
               if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }
            }
            if($ic==0){
                if(  ($this->input->post("correct_".$value) + $this->input->post("wrong_".$value) ) <= $this->input->post("questions") ){
                    if( $this->input->post("marks_".$value) > $this->input->post("maxmarks")  ){
                        if($post["marks_".$value]!=-1){
                            $this->form->setError("marks_".$value,'Max Marks Excedded');
                        }
                    }
                }else{
                    $this->form->setError("correct_".$value,'Max Questions Excedded');
                    $this->form->setError("wrong_".$value,'Max Questions Excedded');
 
                }
            }
            
        }
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect("exams/view_mc_marks/".$this->input->post("exam_id")."?mcid=".$this->input->post("mcid")."&subject=".$this->input->post('examid'),'refresh');
        }else{
            $query ="select * from student where iid='".$this->session->userdata("staff_Org_id")."' AND  section_id='".$this->input->post('section')."' AND roll IN (".$this->input->post('rolls').") ";
            $query = $this->db->query($query);  
            $query = $query->result();
            $roll_id = array();
            foreach($query as $val){
               $roll_id[$val->roll]=$val->student_id; 
            }
            foreach ($rolls as $value) {
                $data = array(
                 'iid' => $this->session->userdata('staff_Org_id'),
                 'exam_id' => $this->input->post("examid"),
                 'student_id' => $roll_id[$value] ,
                 'marks' =>$post["marks_".$value],
                 'correct' =>$post["correct_".$value],
                 'wrong' =>$post["wrong_".$value],
                );
               $this->db->insert('mcmarks',$data);  

            }
            unset($_SESSION['failed_records']);
            redirect("exams/results/".$this->input->post('exam_id'),'refresh');
         }
    }
    
    public function view_mc_marks($eid){
        if(!isset($_SESSION['failed_records'])){
               redirect('exams/index','refresh'); 
        }
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $credential = array('id' =>$eid,'iid'=>$this->session->userdata('staff_Org_id'),'type'=>'2' );
            $query = $this->db->get_where('examinations', $credential);
            if ($query->num_rows() > 0) {
                $exam = $query->row();
                $data['exam']=$exam;
                $this->load->view("exams/add_mcmarks_dummy",$data);
            }else{
                redirect('exams/index','refresh');
            }
            
        }
        
    }
    
    public function edit_mc_marks($eid,$section,$mcid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
          $credential = array('id' =>$eid,'iid'=>$this->session->userdata('staff_Org_id'),'type'=>'2' );
            $query = $this->db->get_where('examinations', $credential);
            if ($query->num_rows() > 0) {
                $exam = $query->row();
                $data['exam']=$exam;
                $section = explode("-",$section);
                $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND ec.id='".$section[1]."' ");
                $section_query=$section_query->row(); 
                $mc_exam=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.questions,s.subject FROM mcexam e JOIN subjects s ON e.subid=s.sid where ecid='".$section_query->id."' AND id='".$mcid."' ")->row();
                $marks_list =$this->db->query("SELECT st.student_id,m.mark_id,st.name,st.roll ,m.marks,m.correct,m.wrong FROM `student` st LEFT JOIN mcmarks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section_query->sid."' AND m.exam_id='".$mc_exam->id."'  ")->result();
                if(sizeof($marks_list)==0){
                    redirect('exams/add_marks/'.$eid,'refresh');
                }
                $data['section']=$section_query;
                $data['mc_exam']=$mc_exam;
                $data['marks']=$marks_list;
                $this->load->view('exams/edit_mc_marks',$data);
            }else{
                redirect('exams/index','refresh');
            }  
        }
    }
    
    public function save_mc_marks(){
        
        $post=$_POST;
        $rolls = $this->input->post("student_id");
        $rolls= explode(",",$rolls);
        $rolls = array_filter($rolls);
        foreach ($rolls as $value) {
            $ic=0;
            $field= "correct_".$value;
            if(!is_numeric($this->input->post($field))){
                if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }
                
            }
            $field= "wrong_".$value;
            if(!is_numeric($this->input->post($field))){
               if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }    
            }
            $field= "marks_".$value;
            if(!is_numeric($this->input->post($field))){
               if(strtolower($this->input->post($field))!="a"){
                   $this->form->setError($field,'* Enter Numeric value');$ic++;
               }else{
                  $post[$field]=-1; 
               }
            }
            if($ic==0){
                if(  ($this->input->post("correct_".$value) + $this->input->post("wrong_".$value) ) <= $this->input->post("questions") ){
                    if( $this->input->post("marks_".$value) > $this->input->post("maxmarks")  ){
                        if($post["marks_".$value]!=-1){
                            $this->form->setError("marks_".$value,'Max Marks Excedded');
                        }
                    }
                }else{
                    $this->form->setError("correct_".$value,'Max Questions Excedded');
                    $this->form->setError("wrong_".$value,'Max Questions Excedded');
 
                }
            }
            
        }
       
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect("exams/view_mc_marks/".$this->input->post("exam")."/".$this->input->post("sec_val")."/".$this->input->post('mcid'),'refresh');
        }else{
              foreach ($rolls as $value) {
                  $data = array(
                        'marks' => $this->input->post("marks_".$value),
                        'correct' => $this->input->post("correct_".$value),
                        'wrong' => $this->input->post("wrong_".$value),
                    );
                $this->db->where('mark_id',$post['mark_id_'.$value]);
              $this->db->update('mcmarks', $data);
              }
          redirect("exams/results/".$this->input->post("exam")."?section=".$this->input->post("sec_val"),'refresh');
      
        }
    }
    
    public function add_marks($eid){
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $credential = array('id' =>$eid,'iid'=>$this->session->userdata('staff_Org_id'),'type'=>'2' );
            $query = $this->db->get_where('examinations', $credential);
            if ($query->num_rows() > 0) {
                $exam = $query->row();
                $data['exam']=$exam;
               // print_r($exam
               $this->load->view('exams/admin_add_marks',$data);
            }else{
                redirect('exams/index','refresh');
            }
            
        }
    }
    
    public function savemcsettings(){
        
       $post=$this->operations->cleanInput($_POST);
        $exam_timings=array();
        $exam_ids=explode(",",$post['course_ids']);
        foreach($exam_ids as $exam_id){
            if(isset($post['subject_'.$exam_id])){
                 $iner_count=0;
           $field="day_".$exam_id;
           if(strlen($post[$field])==0){
               
              $this->form->setError($field,'* select Exam date'); 
           }else{
                $examdate=explode('-',$post["day_".$exam_id]);                  
                $frm = $post["start_".$exam_id]; 
                $to  =$post["ending_".$exam_id]; 
                 $time_err_c=0;
                if(strlen($frm)==0){
                    $time_err_c++;
                    $this->form->setError("start_".$exam_id,'* select From Time'); 
                }
                if(strlen($to)==0){
                    $time_err_c++;
                    $this->form->setError("ending_".$exam_id,'* select End Time'); 
                }
                            
                if($time_err_c ==0){
                    $d=array();
                    $c_d=explode(',',$post['course_Details_'.$exam_id]);
                        $d['subject']=$c_d[1];
                        $d['date']=mktime(0,0,0,$examdate[1],$examdate[0],$examdate[2]);
                        $d['from']=  $this->validate_time($frm, $examdate);
                        $d['end']=$this->validate_time($to, $examdate);
                        $d['span']= ($d['end'] - $d['from'])/60;
                        if(!$this->check_slot($d['from'], $d['end'], $exam_timings)){
                           $this->form->setError('start_'.$exam_id,'* enter exam span');  
                        }else{
                            if($d['span']==0){
                                     $this->form->setError('ending_'.$exam_id,'* enter Valid End time');  
                            }else{
                            
                            $exam_timings[$exam_id]=$d;
                            }
                        }                
                }              
                
              }
          $field="max_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter max marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value max');
           }  
           $field="questions_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter min marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value min');
           }
            }
            
           
        }
       
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
         }else{
             $c=0;
             $older_entry ="";
                          
              foreach($exam_ids as $exam_id){
                  if(isset($post['subject_'.$exam_id])){
                      if(isset($post['old_exam_id_'.$exam_id])){
                          $older_entry.=$post['old_exam_id_'.$exam_id].",";
                          $data = array(
                                    'examdate' => $exam_timings[$exam_id]['date'],
                                    'starttime' => $exam_timings[$exam_id]['from'],
                                    'endtime' => $exam_timings[$exam_id]['end'],
                                    'timespan' => $exam_timings[$exam_id]['span'],
                                    'maxmarks' =>  $post['max_'.$exam_id],
                                    'questions' =>  $post['questions_'.$exam_id],
                                    );

                            $this->db->where('id',$post['old_exam_id_'.$exam_id]);
                            $this->db->update('mcexam', $data);
                      }else{
                            $c_d = explode(',', $post['course_Details_'.$exam_id]);
                            $data = array(
                              'iid' =>$this->session->userdata('staff_Org_id'),
                              'examid'=>$post['examid'],
                              'ecid'=>$post['ecid'],
                              'courseid' =>$c_d[0],
                              'subid' =>$c_d[1],
                              'examdate' => $exam_timings[$exam_id]['date'],
                              'starttime' => $exam_timings[$exam_id]['from'],
                              'endtime' => $exam_timings[$exam_id]['end'],
                              'timespan' => $exam_timings[$exam_id]['span'],
                              'maxmarks' =>  $post['max_'.$exam_id],
                              'questions' =>  $post['questions_'.$exam_id],
                              );
                             $this->db->insert('mcexam', $data);  
                      }
                  }
                  
                
               $c++;
              //  $this->db->where('id', $exam_id);
              //  $this->db->update('mcexam', $data);  
             };
             
             $older_entry = substr($older_entry, 0,strlen($older_entry)-1);
             if($older_entry!=$post['old_ids']){
                 $or=explode(',',$post['old_ids']);
                 $older_entry;
                 $older_entry = explode(',',$older_entry);
                 $diff=(array_diff($or, $older_entry));
                 foreach ($diff as $value) {
                     $this->db->where('id',$value);
                     $this->db->delete('mcexam');
                 }
             }
           $this->session->set_userdata('Section_exam_Settings', 'Sucessfully updated settings');   
           redirect('exams/settings/'.$this->input->post('examid'), 'refresh'); 
         }
          redirect('exams/settings/'.$this->input->post('examid')."?section=".$this->input->post('section_details'), 'refresh'); 
    }
    
    public function print_mcschedule($eid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
              $institute =  $this->fetch_institute_details();
               $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
               $query=$query->result();
               echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Examination Report</title>
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
            <h2 style=" text-align: center; color:  #006666 ">Schedule Of <?php echo $exam->exam ?></h2>
                           <br/>
                            <div class="box" id="exam_schedule">
            
      <?php
        foreach ($query as  $value) {
            ?>
                                <h3 style=" text-align: center ; color:  #006666">
                       <?php echo strtoupper($value->class." - ".$value->section) ?> 
                    </h3>
                         
                <div id='exam_scheddule_<?php echo $value->sid ?>' class="box-content nopadding"> 
                  <?php
                              $this->viewmcexam_Settings($value->id."-".$value->sid,$exam->startdate,$exam->enddate);
                  ?>
                </div>
                <br/>
           <hr/>
          <?php
        }
       ?>
                 </div>
                            
                        
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
    
    public function download_sheet(){
      $examid  = $this->input->get('examid');
      $ecid  = $this->input->get('ecid');
      $ecid = explode(",",$ecid);
      $section = $ecid[1];
      $ecid =$ecid[0];
     $mcid  = $this->input->get('subject');
      $query ="SELECT e.id,e.subid,s.subject, (select count(*) from mcmarks m where m.exam_id=e.id) as marks FROM mcexam e JOIN subjects s ON e.subid=s.sid where e.ecid= '".$ecid."' and e.examid= '".$examid."' and e.id= '".$mcid."' ";
      $query =$this->db->query($query); 
      if($query->num_rows()!=0){
            $query =$query->row();
            $csv_content ="RollNo,Student,correct,wrong,marks,\n";
            $students ="SELECT * from student where section_id = '".$section."' AND iid='".$this->session->userdata('staff_Org_id')."' ";
            $students = $this->db->query($students)->result();
            foreach ($students as $value) {
                $csv_content.=$value->roll.",".$value->name.",,,\n";
            }
            $this->load->helper('download');
            $file = $query->subject.'_markslist.csv';
           force_download($file, $csv_content);
                  
        }else{
            ?>
            <script>
            window.close();</script>
            <?php
        }
    }
    //download_formativesheet
    
    public function download_formativesheet(){
        
       $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$this->input->get("examid")."' AND ecid='".$this->input->get("ecid")."'  AND e.id='".$this->input->get("subject")."' ";
      $query =$this->db->query($query); 
      if($query->num_rows()!=0){
            $query =$query->row();
            $csv_content ="RollNo,Student,part-1,part-2,part-3,part-4,\n";
            $students ="SELECT * from student where section_id = '".$this->input->get("section")."' AND iid='".$this->session->userdata('staff_Org_id')."' ";
            $students = $this->db->query($students)->result();
            foreach ($students as $value) {
                $csv_content.=$value->roll.",".$value->name.",,,,\n";
            }
            $this->load->helper('download');
            $file = $this->input->get("subject_name").'_markslist.csv';
           force_download($file, $csv_content);
        }else{
            ?>
            <script>
            window.close();</script>
            <?php
        }
    }
   
    
    public function load_subjects(){
        $examid = $this->input->post('examid');
        $ecid  = $this->input->post('ecid');
        $ecid = explode(",",$ecid);
        $ecid = $ecid[0];
       $query ="SELECT e.id,e.subid,s.subject, (select count(*) from mcmarks m where m.exam_id=e.id) as marks FROM mcexam e JOIN subjects s ON e.subid=s.sid where e.ecid= '".$ecid."' and e.examid= '".$examid."'  ";
        $query =$this->db->query($query);
        if($query->num_rows()==0){
            ?>
            <script>
             $('#subject_err').html("** No Schedule Found ..");
            </script>
             <?php
        }else{
        $query =$query->result();
        foreach ($query as $value) {
            ?>
            <option value="<?php  echo $value->id.",".$value->marks ?>"><?php echo $value->subject ?>
            <?php
              if($value->marks ==0){
                  echo " (Marks Not entered)";
              }else{
                   echo " (Marks entered)";
              }
            ?>
            </option>
             <?php
        }
        
        }
        
       
    }
    
    public function print_result($eid,$section){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
            $t=$section ;
        if( strlen($section)!=0 ){
            $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
          if($section_query->num_rows()==0){
          redirect('exams/view_settings/'.$exam->id);
          }else{    
            $section_query =$section_query->row();
            $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
            $ecids = $this->db->query($ecids);
            $ecids = $ecids->row();
            }
        }else{
             redirect('exams/index','refresh');
        }
        
            $institute =  $this->fetch_institute_details();
        $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
        $query=$this->db->query($query); 
        $query=$query->result();

         if(sizeof($query)==0){
            ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
                <?php
        }else{
             echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Result</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">      
<link rel="stylesheet" href="http://demo1.snetwork.in/schooln/assests_2/css/bootstrap.min.css">
 <script src="http://demo1.snetwork.in/schooln/assests_2/js/jquery.min.js"></script>                           
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
            <br/>
            <h3 style="text-align: center"><strong>Results Of <?php echo $exam->exam ?> , <?php echo $section_query->section ." - ".$section_query->class ?></strong></h3>
            <hr color="#00306C" />
            <?php
            if(strlen($this->input->get("grading"))!=0){
         $query= "SELECT e.id,e.maxmarks,e.minmarks,s.subject,c.cid,sl.sl_id as sec_lang  FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
         $query=$this->db->query($query); 
         $query=$query->result();
         $grade_array=  unserialize(GPA_GRADING);
          $ranks=array();
          $stud_marks=array();
          $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
            
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
            }
            $overall_total=0;
            function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
             $overall_total=10*(sizeof($query));
            foreach ($query as $val)
            {
                $type=1;
                if(strlen($val->sec_lang)!=0){
                   $type=2; 
                }
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                $marks[$val->id]['max']=8;
                foreach($k as $p){
                    $gr="";$m="";
                    if($p->marks!=-1){
                        $p->marks=  number_format(($p->marks/$val->maxmarks)*100,2);
                        $p->marks=gpa_grade($p->marks, $grade_array[$type]['grading']);
                        $marks[$val->id][$p->student_id]= $p->marks;
                        $ranks[$val->id][$p->marks]=$p->marks;
                        
                        if($p->marks<$marks[$val->id]['max']){
                            $marks[$val->id]['max']=$p->marks;
                        }
                        $stud_marks[$p->student_id]['total']+=$grade_array[$type]['grading'][$p->marks]['Grade_points'];      
                    }else{
                       $marks[$val->id][$p->student_id]=$p->marks;    
                    }
                }
            }
            
            foreach ($ranks as $key => $value) {
                $value=array_unique($value);            
                krsort($value);
                $ranks[$key]=$value;
            }
             $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
            $totals=array();
            
            foreach($stud_marks as $k=>$value){
               $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
               $grade_graph_array[$stud_marks[$k]['grade']]++;
               $totals[$value['total']]=$value['total'];
            }
            krsort($totals);
            
            ?>
              
      <div class="box">
          <div class="box">
              <h4 style=" text-align: center; color:  #66cc00 ">Examination Settings</h4> <br/> 
                <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                   
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Total</th>
                            <th>cut-off</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php
                          foreach($query as $sub){
                              ?>
                        <tr>
                            <td><a rel="tooltip" title=""
                                   <?php
                                if(strlen($sub->sec_lang)==0){
                                  ?> data-original-title="Set <?php echo $sub->subject ?> As Second language " onclick="set_As_default('<?php echo $sub->cid ?>')" <?php
                                }
                                ?>
                                   ><?php echo $sub->subject ?>
                                <?php
                                if(strlen($sub->sec_lang)!=0){
                                    echo "(2nd Language)";
                                }
                                ?></a></td>
                            <td><?php echo $sub->maxmarks ?></td>
                            <td><?php echo $sub->minmarks ?></td>
                        </tr>   
                               <?php
                          }
                        ?>
                    </tbody>
                </table>
              
          </div>
          <hr color="#00306C" />
          <script>
          function set_As_default(subject){
               setState('section_names','<?php echo base_url() ?>index.php/Course/set_as_second_language','course='+subject+'&section=<?php echo $section  ?>');
          }
          </script>
          
          <div class="box">
              <h4 style=" text-align: center; color:  #66cc00 ">Exam Grading Scale</h4> <br/>   
             <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <thead>
                                            <tr style=" font-size: 15px">
                                             <th >First and Third Languages , Non-Languages</th>
                                             <th>Second Language</th>
                                             <th>Points</th>
                                             <th>Grade</th>
                                         </tr>
                                        </thead>
                                     <tbody>
                                        <?php
                                        $i=1;
                           while($i<=8){
                               ?>
                                         <tr>
                                             <td><?php echo $grade_array[1]['grading'][$i]['from'] ." - " .$grade_array[1]['grading'][$i]['to'] ?></td>
                                             <td><?php echo $grade_array[2]['grading'][$i]['from'] ." - " .$grade_array[2]['grading'][$i]['to'] ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['grade'] ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['Grade_points'] ?></td>
                                         </tr>    
                               <?php
                               $i++;
                           }
                                        ?>
                                     </tbody>
                                 </table>
     
                   
          </div>
          <div class="box">
                <?php
                    $g_Str="";
                        foreach ($grade_graph_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                           
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$grade_array[1]['grading'][$key]['grade']." ', y: ".$per." },";
                        }
                    ?>
                                      <script type="text/javascript">
      $(function () {
          Highcharts.chart('container', {
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Exam Report'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: true,
                          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
              },
              series: [{
                  name: 'Percentage : ',
                  colorByPoint: true,
                  data: [
                      <?php echo $g_Str ?>
                  ]
              }]
          });
      });
                      </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
                <div id="container" style="min-width: 350px; width: 100%; height:250px;  padding-top: 15px;"></div>

          </div>
      </div>
      
      <br style=" clear: both;"/>
      <hr/>
      <?php
//                           echo "<pre>";
//                       print_r($grade_array);
//                           echo "</pre>";
      ?>
               <?php

                    function find_pos($arr ,$val){
                        $i=1;
                        foreach ($arr as $value) {
                            if($value==$val){
                                return $i;
                            }else{
                                $i++;
                            }
                        }
                    }
                    ?>
                    <h4 style=" text-align: center; color:  #66cc00 ">&nbsp;Results</h4>
                              
                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                              
                        <thead>
                            <tr>
                                <th style=" text-align: center" >Student</th>
                                <?php
                                  foreach($query as $val){
                                    ?>
                                <th style=" text-align: center">
                                    <?php echo strtoupper($val->subject) ?>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                            <tr>
                                                <td>G</td>
                                                <td>P</td>
                                            </tr>
                                    </table>
                                </th>
                                    <?php   
                                  }
                                ?> 
                                <th style=" text-align: center" >Total</th>
                                <th style=" text-align: center" >Grade</th>
                                <th style=" text-align: center" >Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              foreach($stud_marks as $stud){
                                ?>
                            <tr>
                                <td style=" text-align: left"><?php echo $stud['name'] ?></td>
                                <?php
                                  foreach($query as $sub){
                                        $type=1;
                                        if(strlen($val->sec_lang)!=0){
                                           $type=2; 
                                        }
                                     ?>
                                <td><?php
                                  if(isset($marks[$sub->id][$stud['id']])){
                                      if($marks[$sub->id][$stud['id']]==-1){
                                      ?>
                                      <table class="table-bordered" style=" text-align: center; width: 100%; color:  green  ">
                                          <tr style="background-color: maroon; color:  white;">
                                            <td >A</td>
                                            <td >A</td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }else{
                                          
                                      ?>
                                      <table class="table-bordered nopadding" style=" text-align: center; width: 100%;   <?php
                                       if($marks[$sub->id][$stud['id']]<$sub->minmarks){
                                           echo "color: red; ";
                                       }
                                      ?>">
                                        <tr>
                                            <td  style="color: #009900; " ><?php
                                             echo $grade_array[$type]['grading'][$marks[$sub->id][$stud['id']]]['grade'];
                                            ?></td>
                                            <td style=" color: #ff9900; "><?php 
                                            echo $grade_array[$type]['grading'][$marks[$sub->id][$stud['id']]]['Grade_points'];
                                            ?></td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }
                                   
                                  }else{
                                      
                                      ?>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                        <tr>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    </table> 
                                      <?php
                                  }
                                ?></td>
                                  <?php
                                  }
                                ?>
                                <td style=" text-align: center; color: #003366"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo ($stud['total']/  sizeof($query));
                                }
                                 ?></td>
                                <td  style=" text-align: center; color:  orangered"><?php echo $grade_array[1]['grading'][$stud['grade']]['grade']; ?></td>
                                <td style=" text-align: center; color:  #006633"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo  find_pos($totals, $stud['total']);
                                }
                                
                                  ?></td>
                            </tr>
                                <?php
                              }
                            ?>
                        </tbody>
                    </table>
                   
                     <h5 style="color:red; text-align: right">G =>Grade P =>Grade Points</h5>
            <?php
            
      }else {
            
            $overall_total=0;
            $grade_array = array('O'=>0,'A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0,'F'=>0);
                                  $marks=array();
                            $ranks=array();
                            $stud_marks=array();
                            $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
                            foreach($s as $value){
                                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
                            }

                            foreach ($query as $val)
                            {
                                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                                $overall_total+=$val->maxmarks;
                                $marks[$val->id]['max']=0;
                                foreach($k as $p){
                                    if($marks[$val->id]['max']<$p->marks){
                                      $marks[$val->id]['max']=$p->marks;  
                                    }
                                    $marks[$val->id][$p->student_id]=$p->marks ;
                                     $ranks[$val->id][$p->marks]=$p->marks;
                                     $stud_marks[$p->student_id]['total']+=$p->marks;
                                }
                            }  

                            foreach ($ranks as $key => $value) {
                                $value=array_unique($value);            
                                krsort($value);
                                $ranks[$key]=$value;
                          }
                         function find_grade($total,$overall){
                             $per=($total/$overall)*100;
                             $per=number_format ($per,2);
                             $grade ="";
                             switch($per){
                                 case $per>90 : $grade="O"; break;
                                 case $per>80 : $grade="A"; break;
                                 case $per>70 : $grade="B"; break;
                                 case $per>60 : $grade="C"; break;
                                 case $per>50 : $grade="D"; break;
                                 case $per>40 : $grade="E"; break;
                                 default : $grade="F";break;
                             }
                             return $grade;
                         }
                          $totals=array();
                          foreach($stud_marks as $k=>$value){
                              $stud_marks[$k]['grade']=find_grade($value['total'],$overall_total);
                              $grade_array[$stud_marks[$k]['grade']]++;
                              $totals[$value['total']]=$value['total'];
                          }
                          krsort($totals);
                ?>
            <div class="col-sm-12">
                    <div class="col-sm-1">&nbsp;</div>
                <div class="col-sm-10 nopadding">
                    <h4 style=" text-align: center; color:  #66cc00 ">Exam Schedule</h4>
                              <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <thead>
                                      <tr>
                                          <th>Subject</th>
                                          <th>Total</th>
                                          <th>cut-off</th>
                                          <th>Max Secured</th>
                                      </tr>
                                  </thead> 
                                  <tbody>
                                      <?php
                                        foreach($query as $sub){
                                            ?>
                                      <tr>
                                          <th><?php echo $sub->subject ?></th>
                                          <td><?php echo $sub->maxmarks ?></td>
                                          <td><?php echo $sub->minmarks ?></td>
                                          <td><?php echo $marks[$sub->id]['max'] ?></td>
                                      </tr>   
                                             <?php
                                        }
                                      ?>
                                  </tbody>
                              </table>
                         
                </div>
                    <div class="col-sm-1">&nbsp;</div>
                    <div style=" clear: both; " class="col-sm-8 col-sm-offset-2">
                        <br/><br/>
                    <?php
                    $g_Str="";
                        foreach ($grade_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$key." Grade ', y: ".$per." },";
                        }
                    ?>
                                      <script type="text/javascript">
      $(function () {
          Highcharts.chart('container', {
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Exam Report'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: true,
                          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
              },
              series: [{
                  name: 'Percentage : ',
                  colorByPoint: true,
                  data: [
                      <?php echo $g_Str ?>
                  ]
              }]
          });
      });
                      </script>
                      <script src="https://code.highcharts.com/highcharts.js"></script>
      <script src="https://code.highcharts.com/modules/exporting.js"></script>

      <div id="container" style="min-width: 350px; width: 100%; height:250px; ">
          <span style=" color:  red; ">** please add marks for graphical representation</span>
      </div>

                </div>

            </div><br/>
            <hr/>
            <div style=" clear: both ; padding-top: 10px " class="  box  box-bordered nopadding">
                               <h4 style=" text-align: center; color:  #66cc00 ">&nbsp;Results</h4>
                              
                          <?php

                          function find_pos($arr ,$val){
                              $i=1;
                              foreach ($arr as $value) {
                                  if($value==$val){
                                      return $i;
                                  }else{
                                      $i++;
                                  }
                              }
                          }
                          ?>
                          <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                  <tr>
                                      <th style=" text-align: center" >Student</th>
                                      <?php
                                        foreach($query as $val){
                                          ?>
                                      <th style=" text-align: center"><?php echo $val->subject ?>
                                          <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                                  <tr>
                                                      <td>M</td>
                                                      <td>R</td>
                                                  </tr>
                                          </table>
                                      </th>
                                          <?php   
                                        }
                                      ?> 
                                      <th style=" text-align: center" >Total</th>
                                      <th style=" text-align: center" >Grade</th>
                                      <th style=" text-align: center" >Rank</th>

                                  </tr>
                              
                                  <?php

                                    foreach($stud_marks as $stud){

                                      ?>
                                  <tr>
                                      <th><?php echo $stud['name'] ?></th>
                                      <?php
                                        foreach($query as $sub){
                                           ;
                                           ?>
                                      <td><?php
                                        if(isset($marks[$sub->id][$stud['id']])){
                                            if($marks[$sub->id][$stud['id']]==-1){

                                            ?>
                                            <table class="table-bordered" style=" text-align: center; width: 100%; color:  green  ">
                                              <tr>
                                                  <td>A</td>
                                                  <td>A</td>
                                              </tr>
                                          </table>     
                                            <?php
                                            }else{

                                            ?>
                                            <table class="table-bordered" style=" text-align: center; width: 100%;  <?php
                                             if($marks[$sub->id][$stud['id']]<$sub->minmarks){
                                                 echo "color: red; ";
                                             }
                                            ?>">
                                              <tr>
                                                  <td><?php  echo $marks[$sub->id][$stud['id']];   ?></td>
                                                  <td><?php echo  find_pos($ranks[$sub->id], $marks[$sub->id][$stud['id']]); ?></td>
                                              </tr>
                                          </table>     
                                            <?php
                                            }

                                        }else{
                                            ?>
                                          <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                              <tr>
                                                  <td>--</td>
                                                  <td>--</td>
                                              </tr>
                                          </table> 
                                            <?php
                                        }
                                      ?></td>
                                        <?php
                                        }
                                      ?>
                                      <td style=" text-align: center; color: #003366"><?php 
                                      if($stud['total']==0){
                                           echo "--";
                                      }else{
                                          echo $stud['total'];
                                      }
                                       ?></td>
                                      <td  style=" text-align: center; color:  orangered"><?php echo $stud['grade']; ?></td>
                                      <td style=" text-align: center; color:  #006633"><?php 
                                      if($stud['total']==0){
                                           echo "--";
                                      }else{
                                          echo  find_pos($totals, $stud['total']);
                                      }

                                        ?></td>
                                  </tr>
                                      <?php
                                    }
                                  ?>
                             
                          </table>
                      </div>
            
                <h5 style=" text-align: center">M => Marks &nbsp;&nbsp;&nbsp; R => Subject-wise Rank </h5>
                <table class="table table-bordered" style=" width: 100%">
                        <tr  style=" text-align: center" >
                            <td >O � OUTSTANDING<br/>90 and above</td>
                            <td >A � EXCELLENT<br/>80% to &lt; 90%</td>
                            <td >B � GOOD<br/>70% to &lt; 80%</td>
                            <td >C � FAIR<br/>60% to &lt; 70%</td>
                            <td >D � SATISFACTORY<br/>50% to &lt; 60%</td>
                            <td >E � AVERAGE<br/>40% to &lt; 50%</td>
                            <td >F� FAIL<br/> &lt; 40%</td>
                       </tr>
               </table>
  
</div>
    </body>
</html>
            
            
            <?php
        }   
        
        
        
        
            }
            }
        }
    }
    
    
    public function add_results($eid,$section){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
                $t=$section ;
                if( strlen($section)!=0 ){
                    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                  if($section_query->num_rows()==0){
                  redirect('exams/view_settings/'.$exam->id);
                  }else{    
                    $section_query =$section_query->row();
                    $data['section']=$section_query;
                    $data['exam']=$exam;
                    $this->load->view('exams/add_marks',$data);
                  }
                }else{
                     redirect('exams/index','refresh');
                }
            }
        }
    }
    
    public function download_card($eid,$section,$student=""){
        $medium= unserialize(medium);
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
            $t=$section ;
        if( strlen($section)!=0 ){
            $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
          if($section_query->num_rows()==0){
          redirect('exams/view_settings/'.$exam->id);
          }else{    
            $section_query =$section_query->row();
            $ecids="SELECT ec.id,s.sid,s.name as section,c.medium,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
            $ecids = $this->db->query($ecids);
            $ecids = $ecids->row();
            }
        }else{
             redirect('exams/index','refresh');
        }
        $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
        $query=$this->db->query($query); 
        $query=$query->result();

         if(sizeof($query)==0){
            ?>
                 <h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
            <?php
            }else{
                                            $institute= $this->fetch_institute_details();
        $this->load->library('Pdf');
       echo $header='  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Progress Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
.tab{
	width:100%;
}
.tab td{
	text-align:center;
	width:16%;
	font-size:12px;
}
body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; clear:both; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px;	}
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

</style>
</head>
<body>';        
        $html_final='';
$institute_html='<div class="total"  style="clear:both; border:3px solid #000000;padding:20px; height: 1045px;">
<div>
<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'" width="148" height="77" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
'.str_replace("\n", "<br/>", $institute->address).'</p>
</div>
</td>
</tr>
</table>
<hr color="#00306C" />
<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
<h2>Progress Report</h2>
</div>
<div style="border:2px solid #00306C;height:160px;">
';


                $overall_total=0;
                $grade_array = array('O'=>0,'A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0,'F'=>0);
                                      $marks=array();
                                $ranks=array();
                                $stud_marks=array();
                                $att_month_query=array();
                                $att_query=$this->db->query("SELECT day,group_concat(id) as attd_id FROM `attendance_date` where section='".$section_query->sid."'  GROUP BY day ORDER BY day ASC");
                                $att_query=$att_query->result();
                                $prev_month="";
                                foreach($att_query as $at){
                                    $day  = getdate($at->day);
                                    $mont_yr=strtolower(substr($day['month'],0,3).",".$day['year']);
                                  //echo "<br/>".$at->attd_id;
                                    if(!isset($att_month_query[strtolower($mont_yr)])){
                                        $att_month_query[$mont_yr]=array("slots"=>$at->attd_id,"count"=>sizeof(array_filter( explode(",",$at->attd_id))));
                                    }else{
                                        $slots=$att_month_query[$mont_yr]['slots'];
                                         $slots.=",".$at->attd_id;
                                        $att_month_query[$mont_yr]['slots']=$slots;
                                        $att_month_query[$mont_yr]['count']=$att_month_query[$mont_yr]['count']+sizeof(array_filter( explode(",",$at->attd_id)));
                                    }
                                    $prev_month=$mont_yr;
                                }
                                
                                $s=$this->db->query("SELECT student_id as id,name,userid,photo,roll from student WHERE section_id ='".$section_query->sid."'  ")->result();;
                                foreach($s as $value){
                                    $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
                                    foreach ($att_month_query as $at=>$val) {
                                        $slots= implode(",",array_filter(explode(",",$val['slots'])));
                                        $count=$this->db->query( "SELECT count(*) as count FROM `attendance` where student='".$value->id."' AND date_id IN(".$slots.")")->row();
                                        $count =$count->count;
                                        $stud_marks[$value->id]['attendance'][$at]=$count;
                                    }
                                }
                                foreach ($query as $val)
                                {
                                    $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                                    $overall_total+=$val->maxmarks;
                                    $marks[$val->id]['max']=0;
                                    foreach($k as $p){
                                        if($marks[$val->id]['max']<$p->marks){
                                          $marks[$val->id]['max']=$p->marks;  
                                        }
                                        $marks[$val->id][$p->student_id]=$p->marks ;
                                         $ranks[$val->id][$p->marks]=$p->marks;
                                         $stud_marks[$p->student_id]['total']+=$p->marks;
                                    }
                                    
                                    
                                }  

                                foreach ($ranks as $key => $value) {
                                    $value=array_unique($value);            
                                    krsort($value);
                                    $ranks[$key]=$value;
                              }

                              function find_pos($arr ,$val){
                                $i=1;
                                foreach ($arr as $value) {
                                    if($value==$val){
                                        return $i;
                                    }else{
                                        $i++;
                                    }
                                }
                            }
                             function find_grade($total,$overall){
                                 $per=($total/$overall)*100;
                                 $per=number_format ($per,2);
                                 $grade ="";
                                 switch($per){
                                     case $per>90 : $grade="O"; break;
                                     case $per>80 : $grade="A"; break;
                                     case $per>70 : $grade="B"; break;
                                     case $per>60 : $grade="C"; break;
                                     case $per>50 : $grade="D"; break;
                                     case $per>40 : $grade="E"; break;
                                     default : $grade="F";break;
                                 }
                                 return $grade;
                             }
                              $totals=array();
                              foreach($stud_marks as $k=>$value){
                                  $stud_marks[$k]['grade']=find_grade($value['total'],$overall_total);
                                  $grade_array[$stud_marks[$k]['grade']]++;
                                  $totals[$value['total']]=$value['total'];
                              }
                              krsort($totals);
   
if(strlen($student)!=0){
    $student ="SELECT student_id as id,name,userid,photo,roll FROM student where student_id='".$student."' ";
    $student=$this->db->query($student);
    $s = $student->result();
    
}

                foreach($s as $stud){
                    $stud_html_str='';
                    $stud_html_str.=$institute_html;
                    $std_image="dummy_user.png";
                      if(!(strlen($stud->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$stud->photo)){
                           $std_image =$stud->photo  ;
                         }            
                      }

                    $stud_details='<div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="120" style="padding:10px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:138px;">&nbsp;</div>
                                    <div  style="float:left;padding:5px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$stud->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$ecids->class.'-'.$ecids->section.'</th>
                                    </tr>
                                    <tr>
                                            <td>Medium</td>
                                        <td>:</td>
                                        <th>'.$medium[$ecids->medium].'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$stud->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>R.No</td>
                                        <td>:</td>
                                        <th>'.$stud->roll.'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div>
                                    <br style="clear:both" />
                                    ';
                    $stud_html_str.=$stud_details;
                         $subjects_marks='<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
                                        <h2>Result of '.$exam->exam.'</h2>
                                        </div>

                                        <div>
                                        <table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                                <th width="13%">Subject</th>
                                            <th width="13%">Max&nbsp;Marks</th>
                                            <th width="13%">Cut-Off</th>
                                            <th width="13%">Marks&nbsp;Secured</th>
                                            <th width="13%">Highest&nbsp;Mark</th>
                                            <th width="13%">Rank</th>
                                            <th width="13%">Grade</th>
                                        </tr>
                                        ';
                    $sub_str="";$stud_total=0;
                    foreach($query as $sub){
                        
                        $maxmark=$marks[$sub->id]['max'];
                        if($marks[$sub->id]['max']==0){
                            $maxmark ="--";
                        }
                        $mark="--";
                        $grade="--";
                        $rank="--";
                        if(isset($marks[$sub->id][$stud->id])){
                            if($marks[$sub->id][$stud->id]==-1){
                                $mark="A";
                            }else{
                                $mark=$marks[$sub->id][$stud->id];
                                $stud_total+=$mark;
                                $grade=find_grade($mark,$maxmark);
                                $rank = find_pos($ranks[$sub->id],$mark);
                            }
                        }
                        $sub_str.='<tr>
                                    <th>'.strtoupper($sub->subject).'</th>
                                    <td>'.$sub->maxmarks.'</td>
                                    <td>'.$sub->minmarks.'</td>
                                    <td><h2>'.$mark.'</h2></td>
                                    <td>'.$maxmark.'</td>
                                    <td>'.$rank.'</td>
                                    <td>'.$grade.'</td>
                                </tr>
                                ';
                       }
                        $subjects_marks.=$sub_str.'</table>';
                        $stud_html_str.=$subjects_marks;
                        $result_summary='</div>
                                <br style="clear:both" />
                                <br />
                                <div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
                                <h2>Summary</h2>
                                </div>

                                <div>
                                <table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <th width="20%">Total&nbsp;Marks</th>
                                    <th width="20%">Grand&nbsp;Total</th>
                                    <th width="20%">Percentage</th>
                                    <th width="20%">Grade&nbsp;Secured</th>
                                    <th width="20%">Rank</th>
                                </tr>
                                <tr>
                                    <td><h2>'.$overall_total.'</h2></td>
                                    <td><h2>'.$stud_total.'</h2></td>
                                    <td><h2>'. number_format(($stud_total/$overall_total)*100, 2).'%</h2></td>
                                    <td><h2>'.find_grade($stud_total,$overall_total).'</h2></td>
                                    <td><h2>'.find_pos($totals, $stud_total).'</h2></td>
                                </tr>
                                </table>
                                </div>

                                <div style="clear:both;"></div>
                                <br />';
                        $mont_html="";
                        $mn_per_html="";
                        foreach($att_month_query as $att=>$per){
                            
                            $mont_html.="<th>".strtoupper($att)."</th>";
                            $mn_per_html.="<td>".( number_format(($stud_marks[$stud->id]['attendance'][$att]/$per['count'])*100,2) )."%</td>";
                        }
                        $attendance_html='
<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
<h2>Attendance Report</h2>
</div>

<div style=" text-align:center">
<table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>'.$mont_html.'
</tr>
<tr>'.$mn_per_html.'</tr>
</table>

</div>
';
                                                                 $signs='<div style="clear:both;"></div>
                                <br />
                                </div>
                                <br />
                                <br />
                                <br />
                                <br />
                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Student Sign</span>
                                <br />
                                <br />
                                </div>

                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Parent Sign</span>
                                <br />
                                <br />
                                </div>

                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Principal Sign</span>
                                <br />
                                <br />
                                </div>
                                <br />
                                <br />
                                <br />
                                <br />

                                <div style="clear:both;"></div>
                                <hr color="#00306C" />
                                <table class="tab">
                                <tr>
                                        <td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td>
                                </tr>
                                <tr>
                                    <td width="16%">90% and Above</td>
                                    <td width="16%">80% and < 90%</td>
                                        <td width="16%">70% and < 80%</td>
                                        <td width="16%">60% and < 70%</td>
                                    <td width="16%">50% and < 60%</td>
                                    <td width="16%">40% and < 50%</td>
                                </tr>
                                </table>

                                <div style="clear:both;"></div>
                                
                                </div>
                                ';
                                        
                                                                 $stud_html_str.=$result_summary.$attendance_html.$signs;
                                                                 echo $stud_html_str;
            }
                ?>
                 <script>window.print();</script>
                  <?php
                                     echo '</body>
</html>';
            }
        
            }
        }
        
    }
    
    public function download_gpacard($eid,$section,$student=""){
         $medium= unserialize(medium);
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
            $t=$section ;
        if( strlen($section)!=0 ){
            $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
          if($section_query->num_rows()==0){
          redirect('exams/view_settings/'.$exam->id);
          }else{    
            $section_query =$section_query->row();
            $ecids="SELECT ec.id,s.sid,s.name as section,c.medium,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
            $ecids = $this->db->query($ecids);
            $ecids = $ecids->row();
            }
        }else{
             redirect('exams/index','refresh');
        }
        $query= "SELECT e.id,e.maxmarks,e.minmarks,s.subject,c.cid,sl.sl_id as sec_lang  FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
         $query=$this->db->query($query); 
         $query=$query->result();
         if(sizeof($query)==0){
            ?>
                 <h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
            <?php
            }else{
             
         $grade_array=  unserialize(GPA_GRADING);
          $ranks=array();
          $stud_marks=array();
          $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
            
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
            }
            $overall_total=0;
            function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
             $overall_total=10*(sizeof($query));
            foreach ($query as $val)
            {
                $type=1;
                if(strlen($val->sec_lang)!=0){
                   $type=2; 
                }
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                $marks[$val->id]['max']=8;
                foreach($k as $p){
                    $gr="";$m="";
                    if($p->marks!=-1){
                        $p->marks=  number_format(($p->marks/$val->maxmarks)*100,2);
                        $p->marks=gpa_grade($p->marks, $grade_array[$type]['grading']);
                        $marks[$val->id][$p->student_id]= $p->marks;
                        $ranks[$val->id][$p->marks]=$p->marks;
                        
                        if($p->marks<$marks[$val->id]['max']){
                            $marks[$val->id]['max']=$p->marks;
                        }
                        $stud_marks[$p->student_id]['total']+=$grade_array[$type]['grading'][$p->marks]['Grade_points'];      
                    }else{
                       $marks[$val->id][$p->student_id]=$p->marks;    
                    }
                }
            }
            
            foreach ($ranks as $key => $value) {
                $value=array_unique($value);            
                krsort($value);
                $ranks[$key]=$value;
            }
             $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
            $totals=array();
            
            foreach($stud_marks as $k=>$value){
               $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
               $grade_graph_array[$stud_marks[$k]['grade']]++;
               $totals[$value['total']]=$value['total'];
            }
            krsort($totals);
            
            }
            }
        }
    }
   
    public function sub_brief_report($eid,$section,$subject){
       $medium= unserialize(medium);
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
                $t=$section ;
                if( strlen($section)!=0 ){
                    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                  if($section_query->num_rows()==0){
                  redirect('exams/view_settings/'.$exam->id);
                  }else{    
                    $section_query =$section_query->row();
                    $ecids="SELECT ec.id,s.sid,s.name as section,c.medium,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
                    $ecids = $this->db->query($ecids);
                    $ecids = $ecids->row();
                    }
                }else{
                     redirect('exams/index','refresh');
                }
                $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."' AND s.sid='".$subject."'  ";
                $query=$this->db->query($query); 
               if($query->num_rows()>0){
                  $subject =$query->result();                   
               }else{
                     redirect('exams/index','refresh');
                }
               $data['exam']=$exam;
               $data['examids']=$ecids;
               $data['subject']=$subject;echo "<pre>";
               print_r($data);
                
                
            }
        }
    }
    
    public function downnload_sample_sheet(){
      //  print_r($_POST);exit;
         $this->load->helper('download');
        $examid = $this->input->get("ecids");
         $examid = implode(",",array_filter( explode(",",$examid)));
         $section = $this->input->get("section");
            
        $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
        $section =$section->row();
        $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id IN (".$examid.")");
        $exam =$exam->result();

        $students= $this->db->query("SELECT * FROM student where section_id='".$section->sid."'");
        $students = $students->result();
        $csv_content="Roll,Name,";$i=0;
        foreach($exam as $val){
           $csv_content.=$val->subject.",";$i++;
        }
        $csv_content = substr($csv_content,0, strlen($csv_content)-1);
        $csv_content.="\n";
        foreach($students as $value){
           $csv_content.=$value->roll.",".$value->name.", ,\n";
        }
        $file = 'Marks_sheet.csv';
        force_download($file, $csv_content);
              
    }
    
    public function submit_marks(){
       // echo "<pre>";
        //print_r($_POST);exit;
        $file= $_FILES['file'];
        $filename=$file['name'];
        if(strlen(trim($filename)) == 0)
        {
            $this->form->setError('file','* file is supporrt only csv/txt format');
        }else{
            $end=explode('.',$filename);
            $end = strtolower(end($end));

            $type = array("csv", "txt");

            if(!(in_array($end, $type)))
            {
                    $this->form->setError('file','* supports only csv/txt format');
            }
        }
        if($this->form->num_errors >0 )
        {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect("exams/add_results".$this->input->post("exam")."/".$this->input->post("section")."?ecids=".$this->input->post('eid'),'refresh');
        }else{
            
            $subject= json_decode($this->input->post('exams_details'));
            $sub_array = array();
            foreach ($subject as $value) {
                $sub_array[trim(strtolower($value->subject))]= array('eid'=>$value->id,'subject'=>$value->subject,'max_marks'=>$value->maxmarks,'min_marks'=>$value->minmarks);
            }
            //print_r($sub_array);exit;
            $file_pointer = fopen($file['tmp_name'], "r");
            $file_read = fread($file_pointer, $file['size']);
            $sub_order=array();
            $newdata=$file_read;
            $ex=  array_filter(explode("\n",$newdata));
            $fst_line= explode(",",$ex[0] ); 
            $students= $this->db->query("SELECT * from student where iid='".$this->session->userdata('staff_Org_id')."' AND section_id = '".$this->input->post("section")."' ");
            $students = $students->result();
            $student_array=array();$db_rolls="";
            foreach ($students as $value) {
                $student_array[$value->roll] = array('stud_id'=>$value->student_id,'name'=>$value->name);
                $db_rolls.=$value->roll.",";
            }
           $no_of_sub=0;
            for($i=2;$i<sizeof($fst_line);$i++){
              $sub_array[strtolower(trim($fst_line[$i]))]['pos']=$i;
              $sub_order[$i]=strtolower(trim($fst_line[$i]));$no_of_sub++;
            }
            $dummy_record=array();
            $failed_records=array();$valid_Studid="";
            for($i=1;$i<sizeof($ex);$i++){
                $arr=trim($ex[$i]);
                $arr = explode(",",$arr);
                if(isset($student_array[$arr[0]])){
                    
                    if((sizeof($arr)-2)==$no_of_sub){
                        $valid_Studid.=$arr[0].",";
                        for($j=2;$j<sizeof($arr);$j++){
                            $mark="";
                            if(strtoupper($arr[$j])=='A'){
                               $mark = -1; 
                            }else{
                                if(!is_numeric($arr[$j])){
                                    $failed_records[$student_array[$arr[0]][stud_id]][$sub_array[$sub_order[$j]]['subject']]= array('Marks'=>$arr[$j],'reason'=>"Not a Numeric Value" );
                                }else{
                                     if($arr[$j]>$sub_array[$sub_order[$j]]['max_marks']){
                                            $failed_records[$student_array[$arr[0]]['stud_id']][$sub_array[$sub_order[$j]]['subject']]= array('Marks'=>$arr[$j],'reason'=>"Marks Greater Than max-marks " );
                                     }else{
                                       $mark = $arr[$j];   
                                     }
                                }
                            }
                            if(strlen($mark)!=0){
                                $data = array(
                                 'iid' => $this->session->userdata('staff_Org_id'),
                                 'exam_id' => $sub_array[$sub_order[$j]]['eid'],
                                 'student_id' => $student_array[$arr[0]]['stud_id'] ,
                                 'marks' =>$mark
                                );
                             $this->db->insert('marks',$data); 
                            }
                        
                        }
                    }
                }else{
                    $dummy_record[]=$ex;
                }
                
            }
            $valid_Studid = array_filter(explode(",",$valid_Studid));
            $db_rolls = array_filter(explode(",", $db_rolls));
            $missing = array_diff($db_rolls, $valid_Studid);
           if( (sizeof($missing)==0)&&(sizeof($failed_records)==0) ){
              redirect('exams/results/'.$this->input->post('exam').'?section='.$this->input->post('section'),'refresh'); 
           }
            foreach( $missing as $val){
                foreach($sub_array  as $key=>$value){
                     $failed_records[$student_array[$val]['stud_id']][$key]= array('Marks'=>"",'reason'=>"Value Not entered" );
                }
            }
          $_SESSION['failed_records'] = $failed_records;
          $_SESSION['exams_data']= $this->input->post("exams_details");
          $_SESSION['exam'] = $this->input->post("exam");$_SESSION['section'] = $this->input->post("section");
          $_SESSION['exams_data']= $this->input->post("exams_details");
          $_SESSION['ecids']= $this->input->post("eid");
          redirect('exams/retify_failed/'.$this->input->post('exam').'/'.$this->input->post('section'),'refresh'); 
        }
    }
    
    public function retify_failed($eid,$section){
       if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
                $t=$section ;
                if( strlen($section)!=0 ){
                    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                  if($section_query->num_rows()==0){
                       redirect('exams/view_settings/'.$exam->id);
                  }else{  
                      $section_query = $section_query->row();
                      $data['exam'] =$exam;
                      $data['section']=$section_query;
                      $this->load->view('exams/desc_dummy_marks',$data);
                  }
                }else{
                         redirect('exams/view_settings/'.$exam->id);
                }
            }
        }
    }
    
    public function submit_desc_dummymarks(){
        $post=$this->operations->cleanInput($_POST);
        $sub_array = array();
        $exams_details= $this->input->post('exams_details');
        $exams_details = json_decode($exams_details);
        foreach($exams_details as $value) {
            $sub_array[strtolower($value->id)]= array('eid'=>$value->id,'subject'=>$value->subject,'max_marks'=>$value->maxmarks,'min_marks'=>$value->minmarks);
        }
        $eids= array_filter(explode(",",$post['eid']));
        $stud_ids=array_filter(explode(",",$post['student_ids']));
        foreach($eids as $e){
            foreach ($stud_ids as $stud) {
                if(isset($post['marks_'.$e.'_'.$stud])){
                    if(strlen($post['marks_'.$e.'_'.$stud])==0){
                       $this->form->setError('marks_'.$e.'_'.$stud,'** Enter marks');
                    }else{
                        if(strtoupper($post['marks_'.$e.'_'.$stud])=="A"){
                            $post['marks_'.$e.'_'.$stud]=-1;
                        }else{
                            if(!is_numeric($post['marks_'.$e.'_'.$stud])){
                                $this->form->setError('marks_'.$e.'_'.$stud,'**Enter Numeric value');
                            }else{
                                if(abs($post['marks_'.$e.'_'.$stud])!=$post['marks_'.$e.'_'.$stud]){
                                       $this->form->setError('marks_'.$e.'_'.$stud,'**Enter Valid marks');
                                }else{
                                    if($post['marks_'.$e.'_'.$stud]>$sub_array[$e]['max_marks']){
                                              $this->form->setError('marks_'.$e.'_'.$stud,'**Enter Valid marks');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if($this->form->num_errors >0 )
        {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect("exams/retify_failed/".$this->input->post("exam")."/".$this->input->post("section"),'refresh');
        }else{
                                     
            foreach($eids as $e){
                foreach ($stud_ids as $stud) {
                    if(isset($post['marks_'.$e.'_'.$stud])){
                      $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'exam_id' => $e,
                        'student_id' => $stud ,
                        'marks' =>$post['marks_'.$e.'_'.$stud]
                       );
                        $this->db->insert('marks',$data); 
                    }
                }
            }
            
         redirect("exams/results/".$this->input->post("exam")."?section=".$this->input->post("section"),'refresh');
                                    
        }
        
        
    }
    
    public function edit_marks($examid=0,$section=0){
            if($examid==0 || $section==0 ){
                $this->load->view('teachers/viewexams');
            }else{
                $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class, (select count(*) from student where section_id=se.sid ) as students FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
                $section =$section->row();
                $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.examdate,e.starttime,e.endtime,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id='".$examid."'");
                $exam =$exam->row();
                $data['section']=$section;  
                $data['exam']=$exam;
                $this->load->view('exams/enter_marks',$data);
            }
        }
        
    
    public function save_marks(){
            $post=$this->operations->cleanInput($_POST);
            $stdids=$post['student_ids'];
            $stdids=  explode(',', $stdids);
            $maxmarks=$post['maxmarks'];
            $minmarks=$post['minmarks'];
            $examid=$post['examid'];
            foreach ($stdids as $id) {
                $field="marks_".$id;
                if(strlen(trim($post[$field]))==0){
                    $this->form->setError($field,'* Please enter marks');
                }elseif(!is_numeric($post[$field])){
                    if(strtolower($post[$field])!="a"){
                        $this->form->setError($field,'* Please Enter numeric value'); 
                    }else{
                        $post[$field]=-1;
                    }
                }elseif( $post[$field] > $maxmarks ){
                  $this->form->setError($field,'* enter valid marks'); 
                }
            }
          
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               redirect('/exams/edit_marks/'.$examid.'/'.$post['section'], 'refresh'); 
            
            }else{ 
               foreach ($stdids as $id) {
                   if($post["action_".$id]=='insert'){
                      $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'exam_id' => $examid,
                        'student_id' => $id,
                        'marks' =>$post["marks_".$id]
                       );
                     $this->db->insert('marks',$data); 
                   }else{
                        $data = array(
                        'marks' =>$post["marks_".$id]
                       );
                     $this->db->where('exam_id', $examid);
                     $this->db->where('student_id', $id);
                     $this->db->update('marks',$data);    
                  }
               }
                $this->session->set_userdata('marks_update_sucess', 'Sucessfuylly Updated Marks');
                redirect('/exams/edit_marks/'.$examid.'/'.$post['section'], 'refresh'); 
            
            }
            
            
        }
        
        public function Save_daily_exam_settings(){
            $post=$this->operations->cleanInput($_POST);
            $section_ids=$post['section_ids'];
            $section_ids= array_filter( explode(",",$section_ids));
            foreach ($section_ids as $value) {
                $field="day_".$value;
                if(strlen($post[$field])==0){
                    $this->form->setError($field,'**Please Select Date ');
                }else{
                   $d=explode("-",$post[$field]);
                   if(sizeof($d)!=3){
                        $this->form->setError($field,'**Please Select Date ');
                   }else{
                       $d= mktime(0,0, 0, $d[1],$d[0],$d[2]);
                       $post[$field]=$d;
                       $field="start_".$value;
                        $post[$field]=  $this->validate_time($post[$field], $post["day_".$value]);
                         $field="end_".$value;
                        $post[$field]=  $this->validate_time($post[$field], $post["day_".$value]);
                        if($post["start_".$value]>$post["end_".$value]){
                              $this->form->setError("start_".$value,'**Select Valid Time ');
                        }
                        if($post["end_".$value]<$post["start_".$value]){
                              $this->form->setError("end_".$value,'**Select Valid Time ');
                        }
                        $duration=$post["end_".$value]-$post["start_".$value];
                        $duration=$duration/60;
                        $field="duration_".$value;
                        $post[$field]=$duration;
                   }
                }
                
                $field="max_".$value;
                if(strlen($post[$field])==0){
                    $this->form->setError($field,'**Enter Max Marks ');
                }else{
                    if(!is_numeric($post[$field])){
                         $this->form->setError($field,'**Enter Numeric Value ');
                    }
                }
                
                $field="min_".$value;
                if(strlen($post[$field])==0){
                    $this->form->setError($field,'**Enter Min Marks ');
                }else{
                    if(!is_numeric($post[$field])){
                         $this->form->setError($field,'**Enter Numeric Value ');
                    }
                }
                
                if($post[$field]>$post["max_".$value]){
                     $this->form->setError($field,'**Enter Valid Min Marks ');
                }
                
                
            }
            if($this->form->num_errors >0 )
                {
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $this->form->getErrorArray();
                    redirect("exams/settings/".$this->input->post("exam"),'refresh');
                }else{
                foreach ($section_ids as $value) {
                                     $data = array(
                                         'iid' => $this->session->userdata('staff_Org_id'),
                                         'examid' => $this->input->post("exam"),
                                         'ecid' => $post['ecid_'.$value],
                                         'examdate' =>$post['day_'.$value],
                                         'starttime' =>$post['start_'.$value],
                                         'endtime' =>$post['end_'.$value],
                                         'timespan' =>$post['duration_'.$value],
                                         'maxmarks' =>$post['max_'.$value],
                                         'minmarks' =>$post['min_'.$value],
                                     );
                                     if(strlen($this->input->post("exam_id_".$value))==0){
                                         $this->db->insert("daily_exams",$data);
                                     }else{
                                         $this->db->where('id',$post['exam_id_'.$value]);
                                          $this->db->update("daily_exams",$data);
                                     }
                }
                   $this->session->set_userdata('exam_Settings', "Updated Exam Settings"); 
                   redirect("exams/view_settings/".$this->input->post("exam"),'refresh');                  

            }
            
            
        }
        
        public function edit_daily_exammarks($exam_id="",$dexam_id=""){
            if( (strlen($exam_id)!=0) && (strlen($dexam_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    if($exam->type!=3){
                        redirect('exams/index','refresh');
                    }
                    $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.sectionid ='".$dexam_id."' AND  ec.status=1 ");
                    $query=$query->row();
                    $data['exam']=$exam;
                    $data['activity']="edit";
                    $data['section_data'] =$query;
                    //view_grandtestmarks
                     $this->load->view('exams/view_grandtestmarks',$data);
                }
            }else{
                redirect("exams/","refresh");
            }
        }
        
        //print_daily_exammarks
    public function print_daily_exammarks($exam_id="",$dexam_id=""){
            if( (strlen($exam_id)!=0) && (strlen($dexam_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    if($exam->type!=3){
                        redirect('exams/index','refresh');
                    }
                    $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.sectionid ='".$dexam_id."' AND  ec.status=1 ");
                    $section_data=$query->row();
                    
                    $institute =  $this->fetch_institute_details();
             
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Result</title>
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
                 <h3 style=" text-align: center">Exam Result</h3>
                 <div class="box">
                <br/>
                <div style=" width: 50%; float: left">
                    <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>Exam Name</th>
                            <td><?php echo strtoupper($exam->exam) ?></td>
                        </tr>
                        <tr>
                            <th>Exam Type</th>
                            <td>Daily Test</td>
                        </tr>
                        <tr>
                            <th>Class-Section</th>
                            <td><?php echo $section_data->class." - ".$section_data->section ?></td>
                        </tr>
                    </table>
                </div>
                <div style=" width: 50%; float: left">
                    <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">  
                        <tr>
                            <th>Exam Date</th>
                            <td><?php echo date("d-m-Y",$section_data->examdate); ?></td>
                        </tr>
                        <tr>
                            <th>Exam Timmings</th>
                            <td><?php echo date("H:i",$section_data->starttime); ?> -- <?php echo date("H:i",$section_data->endtime); ?> </td>
                        </tr>
                        <tr>
                            <th>Maxmarks-Minmarks</th>
                            <td><?php echo $section_data->maxmarks." - ".$section_data->minmarks ?></td>
                        </tr>
                    </table>
                </div>
            </div> 
                 <br style=" clear: both"/><br style=" clear: both"/><br style=" clear: both"/>
                 <?php
                 $marks="SELECT m.*,s.name ,s.userid,s.roll,s.admission_no FROM student s LEFT JOIN `grandtest_marks` m  ON m.student_id=s.student_id WHERE m.exam_id='".$section_data->id."' ORDER BY s.roll ASC";
                $marks=$this->db->query($marks)->result();
                 ?>
                 <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">  
                               <thead>
                            <tr>
                                <th>Roll</th>
                                <th>Student Name</th>
                                <th>Userid</th>
                                <th>Admission No</th>
                                <th>Marks</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($marks as $value) {
                                     ?>
                                <tr>
                                    <td><?php echo $value->roll ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->userid ?></td>
                                    <td><?php echo $value->admission_no ?></td>
                                    <td><?php 
                                    if($value->marks==-1){
                                        echo "Absent";
                                    }else{
                                    echo $value->marks ;
                                    }
                                            ?></td>
                                </tr>    
                                     <?php                
                                    }
                                ?>
                            </tbody>
                        </table>
                 <script>
                 window.print();
                 </script>
</div>
</body>
</html>
 <?php
                    
                    
                }
            }else{
                redirect("exams/","refresh");
            }
        }
   
    public function print_daily_schedule($exam_id=""){
            if( (strlen($exam_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    if($exam->type!=3){
                        redirect('exams/index','refresh');
                    }else{
                          $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 ");
 $query=$query->result();
                         $institute =  $this->fetch_institute_details();
             
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Result</title>
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
<h3 style=" text-align: center"><?php echo ucfirst( $exam->exam) ?> Schedule</h3>
<br/><br/>
         <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">  
                                <tr>
                            <th>S.no</th>
                            <th>class-section</th>
                            <th>exam Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>max-marks</th>
                            <th>Min-Marks</th>
                         </tr>
                    
                    <?php
                    $i=1;
                    foreach ($query as $value) {
                        ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $value->class." - ".$value->section ?></td>
                        <td><?php
                          if(strlen($value->examdate)==0){
                              echo "--";
                          }else{
                              echo date("d-m-Y",$value->examdate);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->starttime)==0){
                              echo "--";
                          }else{
                              echo date("H:i",$value->starttime);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->endtime)==0){
                              echo "--";
                          }else{
                              echo date("H:i",$value->endtime);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->maxmarks)==0){
                              echo "--";
                          }else{
                              echo $value->maxmarks;
                          }
                        ?></td>
                        
                        <td><?php
                          if(strlen($value->minmarks)==0){
                              echo "--";
                          }else{
                              echo $value->minmarks;
                          }
                        ?></td>
                        
                    </tr>
                        <?php
                    }
                    
                    ?>
                    </table>
<script>
                 window.print();
                 </script>
</div>
</body>
</html>
                 <?php
                    }
                }
            }else{
                 redirect('exams/index','refresh');
            }
    }
    public function view_daily_exammarks($exam_id="",$dexam_id=""){
            if( (strlen($exam_id)!=0) && (strlen($dexam_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    if($exam->type!=3){
                        redirect('exams/index','refresh');
                    }
                    $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.sectionid ='".$dexam_id."' AND  ec.status=1 ");
                    $query=$query->row();
                    $data['exam']=$exam;$data['activity']="view";
                    $data['section_data'] =$query;
                    //view_grandtestmarks
                     $this->load->view('exams/view_grandtestmarks',$data);
                }
            }else{
                redirect("exams/","refresh");
            }
        }
   
    public function downnload_grandtest_sheet(){
      //  print_r($_POST);exit;
         $this->load->helper('download');
        $examid = $this->input->get("exam_id");
        $secid = $this->input->get("section_id");
             
        $section = $this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$examid."' AND ec.sectionid ='".$secid."' AND  ec.status=1 ");
        $section =$section->row();
        $students= $this->db->query("SELECT * FROM student where section_id='".$section->sid."' ORDER BY roll");
        $students = $students->result();
        $csv_content="Roll,Name,Marks";$i=0;
        $csv_content = substr($csv_content,0, strlen($csv_content)-1);
        $csv_content.="\n";
        foreach($students as $value){
           $csv_content.=$value->roll.",".$value->name.", ,\n";
        }
        $file = 'Marks_sheet.csv';
        force_download($file, $csv_content);
              
    }
        
    public function upload_daily_exammarks(){
//        echo "<pre>";
//              print_r($_POST);    // exit;                
     $file= $_FILES['sheet'];
            $filename=$file['name'];
            if(strlen(trim($filename)) == 0)
            {
                $this->form->setError('sheet','* file is supporrt only csv/txt format');
            }else{
                $end=explode('.',$filename);
                $end = strtolower(end($end));

                $type = array("csv", "txt");

                if(!(in_array($end, $type)))
                {
                        $this->form->setError('sheet','* supports only csv/txt format');
                }
            }

            
            if($this->form->num_errors >0 )
            {
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect("/exams/view_daily_exammarks/".$this->input->post("exam_id")."/".$this->input->post("mcid")."?action=upload_sheet",'refresh');
            }
            else
            {
                $file_pointer = fopen($file['tmp_name'], "r");
                $file_read = fread($file_pointer, $file['size']);
                $newdata=$file_read;
                $ex=  array_filter(explode("\n",$newdata));
                if(!strpos(strtolower($ex[0]),"rollno,student")){
                    array_shift($ex); 
                }
                $marks_list= array();
                foreach ($ex as $value) {
                    $value=  explode(",",$value);
                    $marks_list[$value[0]]= array("name"=>$value[1],"marks"=>trim($value[2]));
                }
                
                $students = $this->db->query("SELECT * from student where section_id='".$this->input->post("section")."' ");
                $students = $students->result();
                $students_array=array();
                foreach ($students as $value) {
                    $students_array[$value->student_id] = array("roll"=>$value->roll,"name"=>$value->name);
                }
                
                foreach ($students_array as $key => $value) {
                   if(isset($marks_list[$value["roll"]]) ){
                       $store=0;
                       if(is_numeric($marks_list[$value["roll"]]['marks'])){
                           if(($marks_list[$value["roll"]]['marks'])<=$this->input->post("maxmarks")){
                                $store=1 ;  
                           }
                       }elseif($marks_list[$value["roll"]]['marks']=="A"){
                           $marks_list[$value["roll"]]['marks']=-1;
                         $store=1;
                       }
                       if($store==1){
                            $data = array(
                                 'iid' => $this->session->userdata('staff_Org_id'),
                                 'exam_id' => $this->input->post("mcid") ,
                                 'student_id' => $key ,
                                 'marks' =>$marks_list[$value["roll"]]['marks']
                                );
                        //    print_r($data);
                            unset($students_array[$key]);
                            unset($marks_list[$value["roll"]]);
                         $this->db->insert('grandtest_marks',$data); 
                       }
                       
                   }                  
                }
                
                if(sizeof($marks_list)!=0){
                    $this->session->set_userdata('dummy_list', $marks_list);
                
                }
                if(sizeof($students_array)!=0){
                //    print_r($students_array);
                    $this->session->set_userdata('marks_invalid_list', $students_array);
                }
                 $this->session->set_userdata('mark_entered_Sucess', "Marks Updated Sucessfuly");
               redirect("/exams/view_daily_exammarks/".$this->input->post("exam_id")."/".$this->input->post("section"),'refresh');
           
            }
     
    }
    
    public function save_daily_exammarks(){
  
     $post=$_POST;
     $students_ids= $this->input->post("students_ids");
     $students_ids= array_filter(explode(",",$students_ids));
     
     foreach ($students_ids as $value) {
         $field="student_".$value;
         $post[$field]=trim($post[$field]);
         if(is_numeric($post[$field])){
            if(($post[$field])>$this->input->post("maxmarks")){
              $this->form->setError($field,'Invalid Marks Entered');  
            }
        }elseif(strtolower($post[$field])=="a"){
            $post[$field]=-1;         
        }else{
             $this->form->setError($field,'Enter Numeric Value'); 
        }
     }
     
     if($this->form->num_errors >0 )
    {
        $_SESSION['value_array'] = $_POST;
        $_SESSION['error_array'] = $this->form->getErrorArray();
        redirect("/exams/edit_daily_exammarks/".$this->input->post("exam_id")."/".$this->input->post("mcid")."?action=upload_sheet",'refresh');
    }
    else{
        foreach ($students_ids as $value) {
         $field="student_".$value;
              
              if(strlen($_POST['markid_'.$value])==0){
                  $data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                    'exam_id' => $this->input->post("mcid") ,
                    'student_id' => $value ,
                    'marks' =>$post[$field]
                   );
                $this->db->insert('grandtest_marks',$data); 
              }else{
                   $data = array(
                    'marks' =>$post[$field]
                   );
                $this->db->where('mark_id',$_POST['markid_'.$value]);
              $this->db->update('grandtest_marks',$data); 
              }
              
           $this->db->insert('grandtest_marks',$data);                        
        }
     
        $this->session->set_userdata('mark_entered_Sucess', "Marks Updated Sucessfuly");
        redirect("/exams/view_daily_exammarks/".$this->input->post("exam_id")."/".$this->input->post("mcid"),'refresh');
           

    }
     
        
        
    }
    
    public function formativeadd_marks($eid){
        if($eid ==0){
           redirect('exams/index','refresh'); 
        }else{
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
               $data['exam']=$exam;      
               if($exam->type!=4){
                   redirect('exams/index','refresh');
               }
               
               $t=$section = $this->input->get("section");
                $course_err ="";
                if( strlen($t)!=0 ){
                    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                  if($section_query->num_rows()==0){
                  redirect('exams/view_settings/'.$exam->id);
                  }else{    
                    $section_query =$section_query->row();
                    $data['section_array']=$section_query;
                    $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
                    $ecids = $this->db->query($ecids);
                    $ecids = $ecids->row();
                    $data['ecids']=$ecids;
                    $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."' AND e.id='".$this->input->get("subject")."'  ";
                    $query= $this->db->query($query)->row();
                    $data['exam_details']=$query;
                   // print_r($data);
                        $this->load->view('exams/add_formative_marks',$data);
             
                    }
                }else{
                    redirect('exams/index','refresh');
                }
                
            }
        }
    }
    
    public function submit_formativemarks(){
      $file= $_FILES['file'];
        $filename=$file['name'];
        if(strlen(trim($filename)) == 0)
        {
            $this->form->setError('file','* file is supporrt only csv/txt format');
        }else{
            $end=explode('.',$filename);
            $end = strtolower(end($end));

            $type = array("csv", "txt");

            if(!(in_array($end, $type)))
            {
                    $this->form->setError('file','* supports only csv/txt format');
            }
        }
        if($this->form->num_errors >0 )
        {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            //redirect("exams/add_results".$this->input->post("exam")."/".$this->input->post("section")."?ecids=".$this->input->post('eid'),'refresh');
        }else{
            $file_pointer = fopen($file['tmp_name'], "r");
            $file_read = fread($file_pointer, $file['size']);
            $newdata=$file_read;
            $ex=array_filter(explode("\n",$newdata));
            if(!strpos(strtolower($ex[0]),"rollno,student")){
                array_shift($ex); 
            }
            $marks_list= array();
            foreach ($ex as $value) {
                $value=  explode(",",$value);
                $marks_list[$value[0]]= array("name"=>$value[1],"marks1"=>trim($value[2]),"marks1"=>trim($value[2]),"marks2"=>trim($value[3]),"marks3"=>trim($value[4]),"marks4"=>trim($value[5]));
            }
            $students= "SELECT * from student where section_id='".$this->input->post("section")."'";
            $students= $this->db->query($students);
            $students=$students->result();
            $students_array=array();
            foreach ($students as $value) {
                $students_array[$value->student_id] = array("roll"=>$value->roll,"name"=>$value->name);
            }
                
            foreach ($students_array as $key => $value) {
                   if(isset($marks_list[$value["roll"]]) ){
                       $store=0;
                       if( (is_numeric($marks_list[$value["roll"]]['marks1']))&&(is_numeric($marks_list[$value["roll"]]['marks2']))&&(is_numeric($marks_list[$value["roll"]]['marks3']))&&(is_numeric($marks_list[$value["roll"]]['marks4'])) ){
                           if( ( ($marks_list[$value["roll"]]['marks1'])<=5) &&( ($marks_list[$value["roll"]]['marks2'])<=5)&&( ($marks_list[$value["roll"]]['marks3'])<=5)&&( ($marks_list[$value["roll"]]['marks4'])<=5) ){
                                $store=1 ;  
                           }
                       }else{
                           if($marks_list[$value["roll"]]['marks1']=="A"){
                                $marks_list[$value["roll"]]['marks1']=-1;
                                 $store=1;
                            }
                            if($marks_list[$value["roll"]]['marks2']=="A"){
                                $marks_list[$value["roll"]]['marks2']=-1;
                                 $store=1;
                            }
                            if($marks_list[$value["roll"]]['marks3']=="A"){
                                $marks_list[$value["roll"]]['marks3']=-1;
                                 $store=1;
                            }
                            if($marks_list[$value["roll"]]['marks4']=="A"){
                                $marks_list[$value["roll"]]['marks4']=-1;
                                 $store=1;
                            }
                       }
                       if($store==1){
                            $data = array(
                                 'iid' => $this->session->userdata('staff_Org_id'),
                                 'exam_id' => $this->input->post("eid") ,
                                 'student_id' => $key ,
                                 'part_1' =>$marks_list[$value["roll"]]['marks1'],
                                'part_2' =>$marks_list[$value["roll"]]['marks2'],
                                'part_3' =>$marks_list[$value["roll"]]['marks3'],
                                    'part_4' =>$marks_list[$value["roll"]]['marks4']
                                );
                         // print_r($data);
                            unset($students_array[$key]);
                            unset($marks_list[$value["roll"]]);
                        $this->db->insert('formative_marks',$data); 
                       }
                       
                   }                  
                }
                
                if(sizeof($marks_list)!=0){
                    $this->session->set_userdata('dummy_list', $marks_list);
                }
                if(sizeof($students_array)!=0){
                   $this->session->set_userdata('marks_invalid_list', $students_array);
                }
                 $this->session->set_userdata('mark_entered_Sucess', "Marks Updated Sucessfuly");
                 redirect("exams/results/".$this->input->post("exam")."?section=".$this->input->post("section"),"refresh");
        }
    }
    
    public function print_formative_marks($exam_id="",$section_id="",$subject=""){
            if( (strlen($exam_id)!=0) &&(strlen($section_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
$t=$section = $section_id;
 $grade_array=  unserialize(GPA_GRADING);
        
$course_err ="";
if( strlen($t)!=0 ){
    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
  if($section_query->num_rows()==0){
  redirect('exams/view_settings/'.$exam->id);
  }else{    
    $section_query =$section_query->row();
    $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
    $ecids = $this->db->query($ecids);
    $ecids = $ecids->row();
    }
}

if(strlen($subject)==0){
    
 $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject ,e.examdate, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
$query=$this->db->query($query); 
$query=$query->result();
$exam_ids="";
$subject_array=array();$overall_total=0;
foreach ($query as $value) {
    $exam_ids.=$value->id.",";
    $type=1;
    if(strlen($value->sec_lang)!=0){
         $type=2;
    }
     if(strlen(trim($value->maxmarks))!=0){
    $overall_total+=$value->maxmarks;
   }
    $subject_array[$value->id]= array("name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks,"max_Secured"=>0,"type"=>$type);
}
$exam_ids= substr($exam_ids, 0,  strlen($exam_ids)-1);
  if(sizeof($query)==0){
      ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
          <?php
  }else{
      $stud_marks= array();
      $s=$this->db->query("SELECT student_id as id,name,roll,userid from student WHERE section_id ='".$section_query->sid."'  ")->result();;
      foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0,'total_marks'=>0);
        }
        function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
             $overall_total=10*(sizeof($query));
            
    $marks="SELECT * from formative_marks where exam_id IN (".$exam_ids.") ";
 $marks= $this->db->query($marks)->result();
   $maarks_array=array();$ranks=array();
   foreach ($marks as $value) {
  $t=0;
        if($value->part_1==-1){
            $value->part_1="A";
        }else{
            $t+=$value->part_1;
        }
        if($value->part_2==-1){
            $value->part_2="A";
        }else{
            $t+=$value->part_2;
        }
        if($value->part_3==-1){
            $value->part_3="A";
        }else{
            $t+=$value->part_3;
        }
        if($value->part_4==-1){
            $value->part_4="A";
        }else{
            $t+=$value->part_4;
        }
 
        
        $value->marks =  number_format(($t/20)*100,2);
        $value->marks=gpa_grade($value->marks, $grade_array[$type]['grading']);
        $ranks[$value->exam_id][$value->marks]=$value->marks;

        if($value->marks< $subject_array[$value->exam_id]['max_Secured']){
            $subject_array[$value->exam_id]['max_Secured']=$value->marks;
        }
       $maarks_array[$value->exam_id][$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'total'=>$t,"Grade"=>$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['grade']);
        $stud_marks[$value->student_id]['total']+=$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['Grade_points'];              
    //total_marks
        $stud_marks[$value->student_id]['total_marks']+=$t;
        
        }
        
        
        foreach ($ranks as $key => $value) {
            $value=array_unique($value);            
            krsort($value);
            $ranks[$key]=$value;
        }
         $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
        $totals=array();

        foreach($stud_marks as $k=>$value){
           $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
           $grade_graph_array[$stud_marks[$k]['grade']]++;
           $totals[$value['total']]=$value['total'];
        }
        krsort($totals);
$institute =  $this->fetch_institute_details();

                          echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Result</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">      
<link rel="stylesheet" href="http://ems.snetwork.in/schooln/assests_2/css/bootstrap.min.css">
 <script src="http://ems.snetwork.in/schooln/assests_2/js/jquery.min.js"></script>                           
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:1000px;margin-left:auto;padding:2px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:0.3px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:0.3px solid #000; padding:2px 1px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:0.3px solid #000; padding:3px 0px;background-color:#E3E3E3; }

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
      <h3 style=" color:  #006666; text-align: center" >Exam Result Of <?php echo $exam->exam ?>&nbsp;&nbsp;&nbsp;<?php echo $section_query->section ." - ".$section_query->class  ?></h3>    
     
          <h4 style=" text-align: center; color:  #ff6600;  padding: 5px">  Exam Settings </h4>
             <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                          <th>Subject</th>
                          <th>Exam Date</th>
                          <th>Max Marks</th>
                          <th>Min Marks</th>
                      </tr>
                          <?php
                              foreach ($query as $value) {
                                  ?>
                      <tr>
                          <td><?php echo strtoupper($value->subject) ?></td>
                          <td><?php echo  date("d-m-Y", $value->examdate) ?></td>
                          <td><?php echo $value->maxmarks ?></td>
                          <td><?php echo $value->minmarks ?></td>
                      </tr>   
                                   <?php
                              }
                          ?>
                  </table>
                  
                 <h4 style=" text-align: center; color:  #ff6600; padding: 5px">Grading Scale</h4>
                               <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <thead>
                                            <tr style=" font-size: 15px">
                                                <th >First and Third Languages , Non-Languages</th>
                                             <th>Second Language</th>
                                             <th>Points</th>
                                             <th>Grade</th>
                                         </tr>
                                        </thead>
                                     <tbody>
                                        <?php
                                        $i=1;
                           while($i<=8){
                               ?>
                                         <tr>
                                             <td><?php echo $grade_array[1]['grading'][$i]['from'] ."% - " .$grade_array[1]['grading'][$i]['to']."%" ?></td>
                                             <td><?php echo $grade_array[2]['grading'][$i]['from'] ."% - " .$grade_array[2]['grading'][$i]['to']."%" ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['grade'] ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['Grade_points'] ?></td>
                                         </tr>    
                               <?php
                               $i++;
                           }
                                        ?>
                                     </tbody>
                                 </table>
        <?php
                    $g_Str="";
                        foreach ($grade_graph_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                           
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$grade_array[1]['grading'][$key]['grade']." ', y: ".$per." },";
                        }
                        function find_pos($arr ,$val){
                        $i=1;
                        foreach ($arr as $value) {
                            if($value==$val){
                                return $i;
                            }else{
                                $i++;
                            }
                        }
                    }
                    ?>
                                      <script type="text/javascript">
      $(function () {
          Highcharts.chart('container', {
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Exam Report'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: true,
                          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
              },
              series: [{
                  name: 'Percentage : ',
                  colorByPoint: true,
                  data: [
                      <?php echo $g_Str ?>
                  ]
              }]
          });
      });
                      </script>
<!--                <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
                <div id="container" style="min-width: 350px; width: 100%; height:250px; padding-left: 25px; padding-top: 15px;"></div>-->

                <hr/>
                <h3 style=" text-align: center; color:  #ff6600">Exam Result</h3>
                 
                
               <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <?php
                            foreach ($query as $value) {
                                ?>
                            <th style=" text-align: center">
                            <?php echo ucfirst(strtolower($value->subject)) ?>
                                <table class="table table-bordered" style=" width: 100%;" >
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>T</td>
                                <td>G</td>
                            </tr>
                        </table>
                        </th>
                                <?php
                            }
                            ?>
                            <th>Total Marks</th>
                            <th>Points / Grade/Rank</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($s as  $value) {
                                    ?>
                            <tr>
                                <td><?php echo $value->roll ?></td>
                                <td><?php echo $value->name ?></td>
                                <?php
                                foreach ($query as $sub){
                                    ?>
                                <td>
                                    <table class="table table-bordered" style=" width: 100%; border-right:0.2px solid #cccccc " >
                                        <?php 
                                         if(isset($maarks_array[$sub->id][$value->id])){
                                             ?>
                                        <tr>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part1'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part2'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part3'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part4'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['total'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['Grade'] ?></td>
                                        </tr>
                                             <?php
                                         }else{
                                             ?>
                                              <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>   
                                              <?php
                                         }
                                        ?>
                            
                        </table>
                                </td>
                                     <?php
                                }
                                ?>
                                <td><?php echo $stud_marks[$value->id]['total_marks'] ?></td>
                                <td><?php echo $grade_array[1]['grading'][$stud_marks[$value->id]['grade']]['Grade_points']; ?>/<?php echo $grade_array[1]['grading'][$stud_marks[$value->id]['grade']]['grade']; ?>/<?php echo  find_pos($totals, $stud_marks[$value->id]['total']); ?></td>
                            </tr>    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                <br/>
                <h4 style=" text-align: center;">Short Cut Description</h4>
                <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>1</th>
                        <th>:</th>
                        <td>Children's Participation Responses</td>
                        <th>2</th>
                        <th>:</th>
                        <td>Written Work</td>
                    </tr>
                     <tr>
                        <th>3</th>
                        <th>:</th>
                        <td>Project Work</td>
                        <th>4</th>
                        <td>:</td>
                        <td>Slip Test</td>
                    </tr>
                </table>
    <?php
}
                ?>
<script>window.print();</script>
             <?php
           echo "</body></html>";
  }else{
        $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject ,e.examdate, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  AND e.id='".$subject."' ";
        $query=$this->db->query($query); 
        $query=$query->result();
        $exam_ids="";
        foreach ($query as $value) {
    $exam_ids.=$value->id.",";
    $type=1;
    if(strlen($value->sec_lang)!=0){
         $type=2;
    }
    $subject_array[$value->id]= array("name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks,"max_Secured"=>0,"type"=>$type);
}
$exam_ids= substr($exam_ids, 0,  strlen($exam_ids)-1);
  if(sizeof($query)==0){
      ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
          <?php
  }else{
      $stud_marks= array();
      $s=$this->db->query("SELECT student_id as id,name,roll,userid from student WHERE section_id ='".$section_query->sid."'  ")->result();;
      foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0,'total_marks'=>0);
        }
        function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
        //     $overall_total=10*(sizeof($query));
            
    $marks="SELECT * from formative_marks where exam_id IN (".$exam_ids.") ";
 $marks= $this->db->query($marks)->result();
   $maarks_array=array();$ranks=array();
   foreach ($marks as $value) {
  $t=0;
        if($value->part_1==-1){
            $value->part_1="A";
        }else{
            $t+=$value->part_1;
        }
        if($value->part_2==-1){
            $value->part_2="A";
        }else{
            $t+=$value->part_2;
        }
        if($value->part_3==-1){
            $value->part_3="A";
        }else{
            $t+=$value->part_3;
        }
        if($value->part_4==-1){
            $value->part_4="A";
        }else{
            $t+=$value->part_4;
        }
 
        
        $value->marks =  number_format(($t/20)*100,2);
        $value->marks=gpa_grade($value->marks, $grade_array[$type]['grading']);
        $ranks[$value->exam_id][$value->marks]=$value->marks;

        if($value->marks< $subject_array[$value->exam_id]['max_Secured']){
            $subject_array[$value->exam_id]['max_Secured']=$value->marks;
        }
       $maarks_array[$value->exam_id][$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'total'=>$t,"Grade"=>$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['grade']);
        $stud_marks[$value->student_id]['total']+=$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['Grade_points'];              
    //total_marks
        $stud_marks[$value->student_id]['total_marks']+=$t;
        
        }
        
        
        foreach ($ranks as $key => $value) {
            $value=array_unique($value);            
            krsort($value);
            $ranks[$key]=$value;
        }
         $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
        $totals=array();

        foreach($stud_marks as $k=>$value){
           $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
           $grade_graph_array[$stud_marks[$k]['grade']]++;
           $totals[$value['total']]=$value['total'];
        }
        krsort($totals);
$institute =  $this->fetch_institute_details();
                          echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Exam Result</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">      
<link rel="stylesheet" href="http://demo1.snetwork.in/schooln/assests_2/css/bootstrap.min.css">
 <script src="http://demo1.snetwork.in/schooln/assests_2/js/jquery.min.js"></script>                           
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:1050px;margin-left:auto;padding:5px; margin-right: auto; }
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
      <h3 style=" color:  #006666; text-align: center" >Exam Result Of <?php echo $exam->exam ?><?php echo $section_query->section ." - ".$section_query->class  ?></h3>    
                          <?php
                    $g_Str="";
                        foreach ($grade_graph_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                           
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$grade_array[1]['grading'][$key]['grade']." ', y: ".$per." },";
                        }
                        function find_pos($arr ,$val){
                        $i=1;
                        foreach ($arr as $value) {
                            if($value==$val){
                                return $i;
                            }else{
                                $i++;
                            }
                        }
                    }
                    ?>
                                      <script type="text/javascript">
      $(function () {
          Highcharts.chart('container', {
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Exam Report'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: true,
                          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
              },
              series: [{
                  name: 'Percentage : ',
                  colorByPoint: true,
                  data: [
                      <?php echo $g_Str ?>
                  ]
              }]
          });
      });
                      </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
                <div id="container" style="min-width: 350px; width: 100%; height:250px; padding-left: 25px; padding-top: 15px;"></div>

                <hr/>
                <h3 style=" text-align: center; color:  #ff6600">Exam Result</h3>
                 
                
               <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <?php
                            foreach ($query as $value) {
                                ?>
                            <th style=" text-align: center">
                            <?php echo ucfirst(strtolower($value->subject)) ?>
                        <table class="table table-bordered" >
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>T</td>
                                <td>G</td>
                            </tr>
                        </table>
                        </th>
                                <?php
                            }
                            ?>
                        <th>Rank</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($s as  $value) {
                                    ?>
                            <tr>
                                <td><?php echo $value->roll ?></td>
                                <td><?php echo $value->name ?></td>
                                <?php
                                foreach ($query as $sub){
                                    ?>
                                <td>
                                    <table class="table table-bordered" style=" border-right: 1px solid #cccccc " >
                                        <?php 
                                         if(isset($maarks_array[$sub->id][$value->id])){
                                             ?>
                                        <tr>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part1'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part2'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part3'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part4'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['total'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['Grade'] ?></td>
                                        </tr>
                                             <?php
                                         }else{
                                             ?>
                                              <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>   
                                              <?php
                                         }
                                        ?>
                            
                        </table>
                                </td>
                                     <?php
                                }
                                ?>
                                <td><?php echo  find_pos($totals, $stud_marks[$value->id]['total']); ?></td>
                            </tr>    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                <br/>
                <h4 style=" text-align: center;">Short Cut Description</h4>
                <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <th>1</th>
                        <th>:</th>
                        <td>Children's Participation Responses</td>
                        <th>2</th>
                        <th>:</th>
                        <td>Written Work</td>
                    </tr>
                     <tr>
                        <th>3</th>
                        <th>:</th>
                        <td>Project Work</td>
                        <th>4</th>
                        <th>:</th>
                        <td>Slip Test</td>
                    </tr>
                </table>
               
<script>window.print();</script>
             <?php
           echo "</body></html>";
    
}
 
        
        
  }
                }
            }else{
                 redirect('exams/index','refresh');
            }
    }
    
    
    public function print_formative_cards($exam_id="",$section_id="",$student=""){
        if( (strlen($exam_id)!=0) &&(strlen($section_id)!=0) ){
            $exam=$this->examinations->getexam_data($exam_id);
        if(!$exam){
             redirect('exams/index','refresh');
        }else{
            $t=$section = $section_id;
             $grade_array=  unserialize(GPA_GRADING);
            $course_err ="";
            if( strlen($t)!=0 ){
                $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class ,c.medium FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
              if($section_query->num_rows()==0){
              redirect('exams/view_settings/'.$exam->id);
              }else{    
                $section_query =$section_query->row();
                $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
                $ecids = $this->db->query($ecids);
                $ecids = $ecids->row();
                }
                $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject ,e.examdate, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
                $query=$this->db->query($query); 
                $query=$query->result();
                $exam_ids="";
                $subject_array=array();$overall_total=0;
                foreach ($query as $value) {
                    $exam_ids.=$value->id.",";
                    $type=1;
                    if(strlen($value->sec_lang)!=0){
                         $type=2;
                    }
                    if(strlen(trim($value->maxmarks))!=0){
                        $overall_total+=$value->maxmarks;
                       }
                    $subject_array[$value->id]= array("name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks,"max_Secured"=>0,"type"=>$type);
                }
                $exam_ids= substr($exam_ids, 0,  strlen($exam_ids)-1);
                      $stud_marks= array();
      $s=$this->db->query("SELECT student_id as id,name,userid,photo,roll from student WHERE section_id ='".$section_query->sid."' ORDER BY roll asc ")->result();;
      foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0,'total_marks'=>0);
        }
        function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
          //   $overall_total=10*(sizeof($query));
            
    $marks="SELECT * from formative_marks where exam_id IN (".$exam_ids.") ";
 $marks= $this->db->query($marks)->result();
   $maarks_array=array();$ranks=array();
   foreach ($marks as $value) {
  $t=0;
        if($value->part_1==-1){
            $value->part_1="A";
        }else{
            $t+=$value->part_1;
        }
        if($value->part_2==-1){
            $value->part_2="A";
        }else{
            $t+=$value->part_2;
        }
        if($value->part_3==-1){
            $value->part_3="A";
        }else{
            $t+=$value->part_3;
        }
        if($value->part_4==-1){
            $value->part_4="A";
        }else{
            $t+=$value->part_4;
        }
 
        
        $value->marks =  number_format(($t/20)*100,2);
        $value->marks=gpa_grade($value->marks, $grade_array[$type]['grading']);
        $ranks[$value->exam_id][$value->marks]=$value->marks;

        if($value->marks< $subject_array[$value->exam_id]['max_Secured']){
            $subject_array[$value->exam_id]['max_Secured']=$value->marks;
        }
       $maarks_array[$value->exam_id][$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'total'=>$t,"Grade"=>$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['grade']);
        $stud_marks[$value->student_id]['total']+=$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['Grade_points'];              
    //total_marks
        $stud_marks[$value->student_id]['total_marks']+=$t;
        
        }
        
        
        foreach ($ranks as $key => $value) {
            $value=array_unique($value);            
            krsort($value);
            $ranks[$key]=$value;
        }
         $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
        $totals=array();

        foreach($stud_marks as $k=>$value){
           $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
           $grade_graph_array[$stud_marks[$k]['grade']]++;
           $totals[$value['total']]=$value['total'];
        }
        krsort($totals);
     //   echo $overall_total;exit;
        $institute =  $this->fetch_institute_details();
echo $header='  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Progress Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
.tab{
	width:100%;
}
.tab td{
	text-align:center;
	width:16%;
	font-size:12px;
}
body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; clear:both; }
.studet_card {width:780px;margin-left:auto;padding:5px; margin-right: auto; clear:both; min-height:580px; border-bottom : 1px solid black}
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px;	}
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

</style>
</head>
<body>
<div class="total"  style="clear:both; padding:20px; padding-top:0px; height: auto;">
';        
        $html_final='';
        $institute_html="";
$medium=  unserialize(medium);
                //$overall_total=0;
                                $att_month_query=array();
                                $att_query=$this->db->query("SELECT day,group_concat(id) as attd_id FROM `attendance_date` where section='".$section_query->sid."'  GROUP BY day ORDER BY day ASC");
                                $att_query=$att_query->result();
                                $prev_month="";
                                foreach($att_query as $at){
                                    $day  = getdate($at->day);
                                    $mont_yr=strtolower(substr($day['month'],0,3).",".$day['year']);
                                  //echo "<br/>".$at->attd_id;
                                    if(!isset($att_month_query[strtolower($mont_yr)])){
                                        $att_month_query[$mont_yr]=array("slots"=>$at->attd_id,"count"=>sizeof(array_filter( explode(",",$at->attd_id))));
                                    }else{
                                        $slots=$att_month_query[$mont_yr]['slots'];
                                         $slots.=",".$at->attd_id;
                                        $att_month_query[$mont_yr]['slots']=$slots;
                                        $att_month_query[$mont_yr]['count']=$att_month_query[$mont_yr]['count']+sizeof(array_filter( explode(",",$at->attd_id)));
                                    }
                                    $prev_month=$mont_yr;
                                }
                                
                                foreach($s as $value){
                                    foreach ($att_month_query as $at=>$val) {
                                        $slots= implode(",",array_filter(explode(",",$val['slots'])));
                                        $count=$this->db->query( "SELECT count(*) as count FROM `attendance` where student='".$value->id."' AND date_id IN(".$slots.")")->row();
                                        $count =$count->count;
                                        $stud_marks[$value->id]['attendance'][$at]=$count;
                                    }
                                }
                                
                if(strlen($student)!=0){
                    $student ="SELECT student_id as id,name,userid,photo,roll FROM student where student_id='".$student."' ";
                    $student=$this->db->query($student);
                    $s = $student->result();
                }
                foreach($s as $stud){
                    $stud_html_str='';
                    $stud_html_str.=$institute_html;
                    $stud_details='<div class="studet_card">
                                  <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="text-align:left" >Student Name : '.strtoupper($stud->name).'</td>
                                        <td  style="text-align:right" >Class : '.$section_query->class.'</td>
                                        <td  style="text-align:right" >Roll No : '.$stud->roll.'</td>
                                    </tr>
                                    </table>
                                    <br style="clear:both" />
                                    ';
                    $stud_html_str.=$stud_details;
                         $subjects_marks='<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
                                        <h2>Result of '.$exam->exam.'</h2>
                                        </div>
                                        <table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                                <th width="13%">Subject</th>
                                            <th width="13%">Children Participation Responses </th>
                                            <th width="13%">Written Work</th>
                                            <th width="13%">Project Work</th>
                                            <th width="13%">Slip Test</th>
                                            <th width="13%">Total</th>
                                            <th width="13%">Grade</th>
                                        </tr>
                                        ';
                         $t1=$t2=$t3=$t4=0;
                         foreach ($query as $value) {
                             $pr1=$pr2=$pr3=$pr4=$total=$gra="--";
                             if(isset($maarks_array[$value->id][$stud->id])){
                                 if($maarks_array[$value->id][$stud->id]['part1']==-1){
                                     $pr1="A";
                                }else{
                                    $pr1=$maarks_array[$value->id][$stud->id]['part1'];
                                    $t1+= $maarks_array[$value->id][$stud->id]['part1'];
                                 }
                                 
                                 if($maarks_array[$value->id][$stud->id]['part2']==-1){
                                    $pr2="A";
                                 }else{
                                    $t2+= $maarks_array[$value->id][$stud->id]['part2'];
                                     $pr2=$maarks_array[$value->id][$stud->id]['part2'];
                                 }
                                 
                                 if($maarks_array[$value->id][$stud->id]['part3']==-1){
                                      $pr3="A";
                                 }else{
                                    $t3+= $maarks_array[$value->id][$stud->id]['part3'];
                                    $pr3=$maarks_array[$value->id][$stud->id]['part3'];
                                 }
                                 if($maarks_array[$value->id][$stud->id]['part4']==-1){
                                      $pr4="A";
                                 }else{
                                    $t4+= $maarks_array[$value->id][$stud->id]['part4'];
                                    $pr4=$maarks_array[$value->id][$stud->id]['part4'];
                                 }
                                 
                                 if($maarks_array[$value->id][$stud->id]['total']==-1){
                                      $total="A";
                                 }else{
                                    $total=$maarks_array[$value->id][$stud->id]['total'];
                                 }
                                 if($maarks_array[$value->id][$stud->id]['Grade']==-1){
                                      $gra="Absent";
                                 }else{
                                    $gra=$maarks_array[$value->id][$stud->id]['Grade'];
                                 }
                                 
                               //  print_r($maarks_array[$value->id][$stud->id]);
                             }
                             $subjects_marks.='<tr>
                                             <td width="13%">'.strtoupper($value->subject).'('.$value->maxmarks.') </td>
                                            <td width="13%">'.$pr1.'</td>
                                            <td width="13%">'.$pr2.'</td>
                                            <td width="13%">'.$pr3.'</td>
                                            <td width="13%">'.$pr4.'</td>
                                            <td width="13%">'.$total.'</td>
                                            <td width="13%">'.$gra.'</td>
                                        </tr>';
                         }
                        // print_r($stud_marks[$stud->id]);
                         $subjects_marks.='<tr style="color:red">
                                             <th width="13%">Grand Total</th>
                                            <th width="13%">'.$t1.'</th>
                                            <th width="13%">'.$t2.'</th>
                                            <th width="13%">'.$t3.'</th>
                                            <th width="13%">'.$t4.'</th>
                                            <th colspan="2" width="26%">'.$stud_marks[$stud->id]['total_marks'].'</th>
                                        </tr>';
                         $t1 =  number_format(($t1/($overall_total/4))*100,2);
                         $t1=gpa_grade($t1, $grade_array["1"]['grading']);
                         $t2 =  number_format(($t2/($overall_total/4))*100,2);
                         $t2=gpa_grade($t2, $grade_array["1"]['grading']);
                         $t3 =  number_format(($t3/($overall_total/4))*100,2);
                         $t3=gpa_grade($t3, $grade_array["1"]['grading']);
                         $t4 =  number_format(($t4/($overall_total/4))*100,2);
                         $t4=gpa_grade($t4, $grade_array["1"]['grading']);
                         $gra= number_format(($stud_marks[$stud->id]['total_marks']/$overall_total)*100,2);
                         $gra=gpa_grade($gra, $grade_array["1"]['grading']);
                         //echo "<pre>";
                        // print_r($grade_array);
                        $subjects_marks.='<tr style="color:green">
                                             <th width="13%">Total Grade</td>
                                            <th width="13%">'.$grade_array[1]['grading'][$t1]['grade'].'</th>
                                            <th width="13%">'.$grade_array[1]['grading'][$t2]['grade'].'</th>
                                            <th width="13%">'.$grade_array[1]['grading'][$t3]['grade'].'</th>
                                            <th width="13%">'.$grade_array[1]['grading'][$t4]['grade'].'</th>
                                             <th colspan="2" width="26%">'.$grade_array["1"]['grading'][$gra]['grade'].'</td>
                                        </tr>';
                         $stud_html_str.=$subjects_marks."</table>";
                         $mont_html="";
                        $mn_per_html="";
                        foreach($att_month_query as $att=>$per){
                            
                            $mont_html.="<th>".strtoupper($att)."</th>";
                            $mn_per_html.="<td>".( number_format(($stud_marks[$stud->id]['attendance'][$att]/$per['count'])*100,2) )."%</td>";
                        }
                        $attendance_html='<br style="clear:both;" /><br style="clear:both;" />
<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
<h2>Attendance Report</h2>
</div>

<div style=" text-align:center">
<table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>'.$mont_html.'
</tr>
<tr>'.$mn_per_html.'</tr>
</table>

</div>
';
                                                                 $signs='<br style="clear:both;"/>
                                                                     <div style="clear:both;"></div>
                                
                                <div  style="width:33%;float:left;text-align:center">
                                <br/><br/>
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Teacher Sign</span>
                                </div>

                                <div  style="width:33%;float:left;text-align:center"><br/><br/>
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Parent Sign</span>
                                </div>

                                <div  style="width:33%;float:left;text-align:center"><br/><br/>
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Principal Sign</span>
                                </div>
                                ';
$stud_html_str.=$attendance_html.$signs."</div>";
                         echo $stud_html_str;
        
            }
            ?>
<script>window.print();</script>
             <?php
           echo "</body></html>";
               
            }
          }
    }else{
          redirect('exams/index','refresh');
    }
    
    }//end of function
    
    public function edit_formative_marks($exam_id="",$section_id="",$subject=""){
            if( (strlen($exam_id)!=0) &&(strlen($section_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    $t=$section = $section_id;
                    $course_err ="";
                    if( strlen($t)!=0 ){
                        $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                      if($section_query->num_rows()==0){
                      redirect('exams/view_settings/'.$exam->id);
                      }else{    
                        $section_query =$section_query->row();
                        $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
                        $ecids = $this->db->query($ecids);
                        $ecids = $ecids->row();
                        $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject ,e.examdate, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  AND e.id='".$subject."' ";
                        $query=$this->db->query($query); 
                        $query=$query->row();
                        $data['exam']=$exam;
                        $data['section']=$section_query;
                        $data['exam_details']=$query;
                        $this->load->view('exams/edit_formative_marks',$data);	
        
                        }
                    }else{
                         redirect('exams/view_settings/'.$exam->id);
                    }
                }
    }else{
         redirect('exams/view_settings/'.$exam->id);
    }
    
    }
    
    public function save_formative_marks(){
      $post=$_POST;
      $students_ids=  $this->input->post("students_ids");
      $students_ids= array_filter(explode(",", $students_ids));
      foreach ($students_ids as $value) {
          for($i=1;$i<=4;$i++){
            $field="marks_".$i."_".$value; 
          $post[$field]= trim($post[$field]);
          if(!is_numeric($post[$field])){
              $post[$field]= strtolower($post[$field]);
              if($post[$field]!="a"){
                    $this->form->setError($field,'* Invalid Marks1');
              }else{
                  $post[$field]=-1;
              }
          }elseif($post[$field]>5){
              $this->form->setError($field,'* Invalid Marks2');
          }  
          }
          
      }
      
      if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('exams/edit_formative_marks/'.$this->input->post("exam_ids").'/'.$this->input->post("section_id").'/'.$this->input->post("exam_details"), 'refresh');  
        }else{
              foreach ($students_ids as $value) {
              //markid_
                  $data= array(
                      'iid'=>$this->session->userdata('staff_Org_id'),
                      'student_id' =>$value,
                      'exam_id'=> $this->input->post("exam_details"),
                    'part_1'=>$post['marks_1_'.$value],
                    'part_2'=>$post['marks_2_'.$value],
                    'part_3'=>$post['marks_3_'.$value],
                    'part_4'=>$post['marks_4_'.$value]
                 );
                  if(strlen($this->input->post("markid_".$value))==0){
                    // $this->db->where('id',$post['old_exam_id_'.$exam_id]);
                     $this->db->insert('formative_marks', $data); 
                  }else{
                      $this->db->where('fmid',$this->input->post("markid_".$value));
                      $this->db->update('formative_marks', $data);
                  }
                  
              }
        }
          $this->session->set_userdata('marks_updated', 'Marks Updated Sucessfully '); 
               
      redirect('exams/results/'.$this->input->post("exam_ids").'?section='.$this->input->post("section_id"), 'refresh');  
       
    }
    
    public function send_formative_cards($exam_id="",$section_id=""){
            if( (strlen($exam_id)!=0) &&(strlen($section_id)!=0) ){
                $exam=$this->examinations->getexam_data($exam_id);
                if(!$exam){
                     redirect('exams/index','refresh');
                }else{
                    $t=$section = $section_id;
                    $course_err ="";
                    if( strlen($t)!=0 ){
                        $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
                      if($section_query->num_rows()==0){
                      redirect('exams/view_settings/'.$exam->id);
                      }else{    
                        $section_query =$section_query->row();
                        $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
                        $ecids = $this->db->query($ecids);
                        $ecids = $ecids->row();
                        $data['exam']=$exam;
                        $data['section']=$section_query;
                        //print_r($data);
                       // $data['exam_details']=$query;
                        $this->load->view('exams/send_formative_marks',$data);	
                        }
                    }else{
                         redirect('exams/view_settings/'.$exam->id);
                    }
                }
    }else{
         redirect('exams/view_settings/'.$exam->id);
    }
    
    }
    
    public function send_formative_results(){
        $examid=$_POST['examid'];
        $section=$_POST['section'];
        $array=$_SESSION['messge_content'];
        $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>2,
                    'message' =>"Sending Formative Results",
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        $this->logs->insert_staff_log(4,'Sending Alert',$aid);
$sms_details=  $this->fetch_sms_details();
        foreach ($array as $value) {
            $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$value['message'],
                        'mobile' =>$value['phone'],
                        'time' => time(),
                        'status' =>0,
                        'alert_id' =>$aid,
                    );
           
          $this->db->insert('msg_senthistory',$data); 
               
                  
        }
        $this->session->set_userdata('marks_updated', 'Result Suessfully Sent AS SMS'); 
          
        unset($_SESSION['messge_content']);
        ?><script>
        window.location.href = "<?php echo base_url() ?>index.php/exams/results/"+<?php echo $examid ?>+"?section=<?php echo $section ?>";
        </script>
            <?php
        
    }
    private function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        return $msg;
    }
    public function send_daily_test_results(){
      //  print_r($_POST);exit;
        $examid=$_POST['examid'];
        $section=$_POST['section'];
        $array=$_SESSION['messge_content'];
        $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>2,
                    'message' =>"Sending Daily Test Results",
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        $this->logs->insert_staff_log(4,'Sending Alert',$aid);
$sms_details=  $this->fetch_sms_details();
        foreach ($array as $value) {
            $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$value['message'],
                        'mobile' =>$value['phone'],
                        'time' => time(),
                        'status' =>0,
                        'alert_id' =>$aid,
                    );
           
          $this->db->insert('msg_senthistory',$data); 
               
                  
        }
        $this->session->set_userdata('marks_updated', 'Result Suessfully Sent AS SMS'); 
          
        unset($_SESSION['messge_content']);
        redirect("exams/view_daily_exammarks/".$examid."/".$section,'refresh');
        
    }
    public function send_daily_exammarks($exam_id="",$dexam_id=""){
        if( (strlen($exam_id)!=0) && (strlen($dexam_id)!=0) ){
            $exam=$this->examinations->getexam_data($exam_id);
            if(!$exam){
                 redirect('exams/index','refresh');
            }else{
                if($exam->type!=3){
                    redirect('exams/index','refresh');
                }
                $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.sectionid ='".$dexam_id."' AND  ec.status=1 ");
                $query=$query->row();
                $data['exam']=$exam;$data['activity']="view";
                $data['section_data'] =$query;
//                echo "<pre>";
//                print_r($data);
               $this->load->view('exams/send_daily_test_results',$data);
            }
        }else{
            redirect("exams/","refresh");
        }
    }
    
    public function savessettings(){
       
        
        $post=$this->operations->cleanInput($_POST);
        $exam_timings=array();
        $exam_ids=explode(",",$post['course_ids']);
        foreach($exam_ids as $exam_id){
            if(isset($post['subject_'.$exam_id])){
                 $iner_count=0;
           $field="day_".$exam_id;
           if(strlen($post[$field])==0){
               
              $this->form->setError($field,'* select Exam date'); 
           }else{
                $examdate=explode('-',$post["day_".$exam_id]);                  
                $frm = $post["start_".$exam_id]; 
                $to  =$post["ending_".$exam_id]; 
                 $time_err_c=0;
                if(strlen($frm)==0){
                    $time_err_c++;
                    $this->form->setError("start_".$exam_id,'* select From Time'); 
                }
                if(strlen($to)==0){
                    $time_err_c++;
                    $this->form->setError("ending_".$exam_id,'* select End Time'); 
                }
                            
                if($time_err_c ==0){
                    $d=array();
                    $c_d=explode(',',$post['course_Details_'.$exam_id]);
                        $d['subject']=$c_d[1];
                        $d['date']=mktime(0,0,0,$examdate[1],$examdate[0],$examdate[2]);
                        $d['from']=  $this->validate_time($frm, $examdate);
                        $d['end']=$this->validate_time($to, $examdate);
                        $d['span']= ($d['end'] - $d['from'])/60;
                        if(!$this->check_slot($d['from'], $d['end'], $exam_timings)){
                           $this->form->setError('start_'.$exam_id,'* enter exam span');  
                        }else{
                            if($d['span']==0){
                                     $this->form->setError('ending_'.$exam_id,'* enter Valid End time');  
                            }else{
                            
                            $exam_timings[$exam_id]=$d;
                            }
                        }                
                }              
                
              }
          $field="max_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter max marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value max');
           }  
           $field="min_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter min marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value min');
           }
            }
            
           
        }
       
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
         }else{
             $c=0;
             $older_entry ="";
                          
              foreach($exam_ids as $exam_id){
                  if(isset($post['subject_'.$exam_id])){
                      if(isset($post['old_exam_id_'.$exam_id])){
                          $older_entry.=$post['old_exam_id_'.$exam_id].",";
                          $data = array(
                                    'examdate' => $exam_timings[$exam_id]['date'],
                                    'starttime' => $exam_timings[$exam_id]['from'],
                                    'endtime' => $exam_timings[$exam_id]['end'],
                                    'timespan' => $exam_timings[$exam_id]['span'],
                                    'maxmarks' =>  $post['max_marks'],
                                    'minmarks' =>  $post['min_marks'],
                                    );
                          

                            $this->db->where('id',$post['old_exam_id_'.$exam_id]);
                            $this->db->update('exam', $data);
                      }else{
                            $c_d = explode(',', $post['course_Details_'.$exam_id]);
                            $data = array(
                              'iid' =>$this->session->userdata('staff_Org_id'),
                              'examid'=>$post['examid'],
                              'ecid'=>$post['ecid'],
                              'courseid' =>$c_d[0],
                              'subid' =>$c_d[1],
                              'examdate' => $exam_timings[$exam_id]['date'],
                              'starttime' => $exam_timings[$exam_id]['from'],
                              'endtime' => $exam_timings[$exam_id]['end'],
                              'timespan' => $exam_timings[$exam_id]['span'],
                              'maxmarks' =>  $post['max_marks'],
                              'minmarks' =>  $post['min_marks'],
                              );
                             $this->db->insert('exam', $data);  
                      }
                  }
                  
                
               $c++;
              //  $this->db->where('id', $exam_id);
              //  $this->db->update('exam', $data);  
             };
             
             $older_entry = substr($older_entry, 0,strlen($older_entry)-1);
             if($older_entry!=$post['old_ids']){
                 $or=explode(',',$post['old_ids']);
                 $older_entry;
                 $older_entry = explode(',',$older_entry);
                 $diff=(array_diff($or, $older_entry));
                 foreach ($diff as $value) {
                     $this->db->where('id',$value);
                     $this->db->delete('exam');
                 }
             }
           $this->session->set_userdata('Section_exam_Settings', 'Sucessfully updated settings');   
           redirect('exams/settings/'.$this->input->post('examid'), 'refresh'); 
          
         }
         redirect('exams/settings/'.$this->input->post('examid')."?section=".$this->input->post('section_details'), 'refresh'); 

        
    }
    
    
}
?>
