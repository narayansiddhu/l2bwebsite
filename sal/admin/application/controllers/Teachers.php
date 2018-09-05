<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class teachers extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('validations');
            $this->load->model('studparent');
            $this->load->model('form');
            $this->load->library("pagination");$this->operations->is_login();
            /* cache control validations */
        }
        
	public function index()
	{         
               $this->load->view('home'); 
	}
        
        public function add_assignment(){
            
            $post=$this->operations->cleanInput($_POST);
            $field="course";
            if(strlen($post[$field])==0){
                 $this->form->setError($field,'* Please Select Course'); 
            }
            
            $field="assignment";
            $post[$field]=trim($post[$field]);
            if(strlen($post[$field])==0){
                 $this->form->setError($field,'* please enter Assignment Message'); 
            }
            
            $field="sub_date";
            $sub_date=0;
            $post[$field]=trim($post[$field]);
            if(strlen($post[$field])==0){
                 $this->form->setError($field,'* please Select Submission Date'); 
            }else{
                $d=explode("/",$post[$field]);
                
                if(sizeof($d)!=3){
                    $this->form->setError($field,'* select Submission date Properly'); 
                }else{
                   $sub_date=   $dob= mktime(0,0,0,$d[1],$d[0],$d[2]);
                   
                   if($sub_date < time() ){
                        $this->form->setError($field,'* select Submission date Properly');  
                   }
                   
                }
                
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{ 
                $data=array(
                    'tch_id' => $this->session->userdata('staff_id'),
                    'courseid' =>trim($post['course']),
                    'message' =>trim($post['assignment']),
                    'timestamp' =>time(),
                    'submission' =>$sub_date
                );
               $this->db->insert('assignments',$data);    
                $this->session->set_userdata('assignments_insert', 'Sucessfuylly Added Assignment');
            }
           redirect('/teachers/assignments', 'refresh'); 
         }
        
        public function assignments(){
            $this->load->view('teachers/assignments');
        }
        
        public function view_assignments(){
             $this->load->view('teachers/view_assignments');
        }
        
        public function view_exams(){
            $this->load->view('teachers/viewexams');
        }
        
        public function timetable(){
           $this->load->view('teachers/timetable');
        }
        
        public function self_attendance(){
             $this->load->view('teachers/selfattendancce');
        }
        
        public function salary(){
            $this->load->view('teachers/salary');
        }
        
        public function fetch_timetable(){
             $query=  $this->db->query("SELECT t.tid,s.sid,s.name as section,c.name as class,t.start,t.end,t.classes,t.span FROM `timings` t JOIN section s ON t.section=s.sid JOIN class c ON s.class_id=c.id  WHERE t.section='".$this->input->post('section')."' ");
            if($query->num_rows()>0){
                $timetable=$query->row();
                 ?>
               <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Time Table OF <?php echo $timetable->class ." , ".$timetable->section ?></h3> 
                        <div class="actions">
                            <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/timetable/edit/<?php echo $timetable->tid   ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a>  
                        </div>     
                </div>
                <div class="box-content nopadding"> 
                  
                    <table class="table table-hover table-nomargin" >
                        <thead>
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
                        </tbody>
                        
                        
                    </table>
                  
                </div>
            </div>
            
               <?php
            }else{
                ?>
                <div class="box" style=" text-align: center">
                    <br/><br/><br/>
                          <span style=" color: red">** Time Table Not Configured</span>
                    
                </div>
                      <?php
               // redirect("teachers/timetable",'refresh');
            }
            
            
        }
        
        public function exam_results(){
            $this->load->view("teachers/results");
        }
        
        public function monthly_attendance($section=""){
            if($section==0){
              redirect("teachers/",'refresh');
           }else{
               $t=  $this->fetch_section_details($section);
               if(!$t){
                  redirect("teachers/",'refresh');  
               }else{
               $data['section']=$t;
               $this->load->view("teachers/monthly_attendance",$data); 
               }
           }
        }
        
        public function download_monthly_attendance($section="",$month="",$year=""){
            $section=trim($section);
            $month=trim($month);
            $year=trim($year);
            if( (strlen($section)==0) || (strlen($month)==0) || (strlen($year)==0) ){
                 redirect("teachers/",'refresh');
            }else{
               $t=  $this->fetch_section_details($section);
               if(!$t){
                  redirect("teachers/",'refresh');  
               }else{
                 
                 $att_month =$month;
               $att_year =$year;
             
              $attendance_array = array();
               $q="SELECT group_concat(a.student) as students, ad.day, ad.slot FROM `attendance` a JOIN attendance_date ad ON a.date_id = ad.id  where ad.section = '".$section."' AND ( ad.day >='".mktime(0, 0, 0, $att_month, 1, $att_year)."' AND ad.day <'".mktime(0, 0, 0, ($att_month+1), 1, $att_year)."' ) GROUP BY ad.slot , ad.day ORDER BY ad.day ASC";
              $q=  $this->db->query($q);
              $q = $q->result();
              foreach($q as $value){
                  $students = explode(",",$value->students);
                  foreach($students as $stud){
                      $attendance_array[$value->day][$value->slot][$stud] = $stud;
                  }
                  
              }
              
              $stud = $this->db->query("SELECT * FROM student WHERE section_id = '".$section."' ");
              
              if($stud->num_rows()>0){
                  
              
              
              $stud = $stud->result();
              $k= $from =mktime(0, 0, 0, $att_month, 1, $att_year);
              $to =mktime(0, 0, 0, $att_month +1 , 1, $att_year);
              $k = getdate($k);
              
              
                $str ="Students,";
                foreach ($attendance_array as $this_day => $value) { 
                 $str.=date("d-m-y",$this_day).",,";   
                }
                $str.="\n ,";
               
                foreach ($attendance_array as $this_day => $value) { 
                   
                    foreach($value as $k=>$va){
                        $str.="Slot-".$k.",";  
                    }
                     
                }
                
                $str.="\n";
                 foreach($stud as $value ){
                    $str.= $value->name.",";
                        
                    foreach ($attendance_array as $this_day => $vr) { 
                         
                           foreach($vr as $k=>$va){
                               if(isset($attendance_array[$this_day][$k][$value->student_id])){
                                    $str.= "Absent".",";

                                 }else{
                                     $str.= "Present".",";
                                 }
                            }
                                                            
                      }
                    $str.="\n";         
                }
              }
             
               $this->load->helper('download');
                        $file = 'Monthly_attenance_report.csv';
                      force_download($file, $str);
              
               
                 

               
               }
               
            }
            
        }
        
        
        public function examination(){
              $this->load->view('teachers/examinations');
        }
        
        public function exam_schedule($examid=""){
            $examid=trim($examid);
            if(strlen($examid)==0 || (!is_numeric($examid))){
                redirect("teachers/view_exams",'refresh');
            }else{
              
                $t=$this->check_exam($examid);
                
                if(!$t){
                     redirect("teachers/view_exams",'refresh'); 
                }else{
                  $data=$t;
                  
                  $this->load->view('teachers/exam_schedule',$data);  
                }
            }
        }
        
        public function fetch_schedule(){
            ?>
             <div class="box box-color box-bordered nopadding">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                                <?php

                                 $query=  $this->db->query("SELECT ex.type,s.name as section,c.name as cls_name FROM examination_cls e JOIN section s ON e.sectionid=s.sid JOIN class c ON s.class_id=c.id JOIN examinations ex ON e.examid=ex.id WHERE e.id='".$this->input->post("ecid")."' ");
                                 $query=$query->row();
                                 ?>
                                Schedule of <?php echo $query->section." , " .$query->cls_name ?>
                        </h3>
                        <div class="actions">
                            <?php
                              if($this->input->post("type")==2){
                                 ?>
                                <a target="_blank" href="<?php echo base_url() ?>index.php/exams/mchalltickets/<?php echo $this->input->post('ecid') ?>" class="btn btn-mini "  rel="tooltip" title="" data-original-title="Download Hall tickets">
                                    <i class="fa fa-download" aria-hidden="true"></i> Hall Tickets
                                </a>
                                 <?php
                              }else{
                                  ?>
                            <a target="_blank" href="<?php echo base_url() ?>index.php/teachers/halltickets/<?php echo $this->input->post('ecid') ?>" class="btn btn-mini "  rel="tooltip" title="" data-original-title="Download Hall tickets">
                                        <i class="fa fa-download" aria-hidden="true"></i> Hall Tickets
                            </a>
                               <?php
                              }
                            ?>
                            
                       </div>
                </div>
                <div class="box-content nopadding">
                    <table   class="table table-hover table-nomargin table-bordered" >
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Subject</th>
                                <th>Exam date</th>
                                <th>Timings</th>
                                <th>Time Span</th>
                                <th>Max Marks</th>
                                <?php
                                if($this->input->post("type")==2){
                                    ?>
                                <th>Questions</th>
                                  <?php
                                }else{
                                     ?>
                                <th>Min Marks</th>
                                  <?php
                                }
                                ?>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.minmarks,s.subject FROM exam e JOIN subjects s ON e.subid=s.sid WHERE e.ecid='".$this->input->post('ecid')."' ORDER BY e.examdate asc");
                             if($this->input->post("type")==2){
                                $query=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.questions as minmarks,s.subject FROM mcexam e JOIN subjects s ON e.subid=s.sid WHERE e.ecid='".$this->input->post('ecid')."' ORDER BY e.examdate asc");
                                 
                             }
                             $query=$query->result();
                             $i=1;
                             foreach ($query as $value) {
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->subject ?></td>
                                <td><?php 
                                      if($value->examdate ==0){
                                          echo "--";
                                      }else{
                                      echo  date('d-m-Y',$value->examdate);
                                      }
                                      ?></td>
                                <td><?php
                                 if($value->starttime ==0){
                                          echo "__"."-"."__";
                                      }else{
                                      echo  date('H:i',$value->starttime)."-".date('H:i',$value->endtime) ;
                                      }
                                  ?></td>
                                <td><?php
                                     if($value->timespan ==0){
                                          echo "--";
                                      }else{
                                      echo $value->timespan;
                                      }
                                   ?></td>
                                <td><?php 
                                     if($value->maxmarks ==0){
                                          echo "--";
                                      }else{
                                      echo $value->maxmarks;
                                      }
                                
                               ?></td>
                                <td><?php 
                                     if($value->minmarks ==0){
                                          echo "--";
                                      }else{
                                      echo $value->minmarks;
                                      }
                                
                               ?></td>
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
        
        public function halltickets($ecid){
            ob_start();
            $this->load->library('Pdf');
            $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }

            $pdf->AddPage();
            
            $query=  $this->db->query("SELECT s.sid,s.name as section,c.name as cls_name FROM examination_cls e JOIN section s ON e.sectionid=s.sid JOIN class c ON s.class_id=c.id WHERE e.id='".$ecid."' ");
            $query=$query->row();
            $student_details=$this->db->query("SELECT name,userid,roll FROM `student` WHERE section_id='".$query->sid."' ");
            $cls_sec=$query->cls_name." -" .$query->section;
            $student_details=$student_details->result();
            $institute =  $this->fetch_institute_details();
            $header='<table cellpadding="1" cellspacing="1" style=" color:#0c4472 ; "  >
                                    <tr>
                                        <td colspan="2" align="left" >
                                            <img src="'. assets_path ."/uploads/".$institute->logo.'" width="150px" > 
                                          </td>
                                        <td colspan="3" align="right">
                                            <strong style=" color:#0c4472 ; float: right;font-size: 20px;" >'.$institute->name.'</strong>
                                            <br/> <strong style=" color:#0c4472 ; float: right;" >
                                                     '.str_replace("\n", "<br/>", $institute->address).'
                                             </strong> 
                                        </td>
                                    </tr>
                                </table>';
            $exam_schedule='<h3 style="text-align: center ; color:#0c4472"><strong>Exams Details</strong></h3><br/>'.'<table cellpadding="1" cellspacing="1" border="1" style=" color:#0c4472 ; ">';
//                           .'<tr>'
//                           . '<td><strong>Subject</strong></td>'
//                           . '<td><strong>Exam date</strong></td>'
//                           . '<td><strong>Timings</strong></td>'
//                           . '<td><strong>Time Span</strong></td>'
//                           . '</tr>';
            $query=$this->db->query("SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.minmarks,s.subject FROM exam e JOIN subjects s ON e.subid=s.sid WHERE e.ecid='".$ecid."' ORDER BY e.examdate asc");
            $query=$query->result();
            $i=1;
            $subjects_tr ='<tr>';
            $timmings_tr ='<tr>';
            $date_tr='<tr>';
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
                $subjects_tr.='<td align="center">'.$value->subject.'</td>';
                $timmings_tr.='<td align="center">'.$timings.'</td>';
                $date_tr.='<td align="center">'.$date.'</td>';
//              $exam_schedule.='<tr>'
//                           . '<td>'.$value->subject.'</td>'
//                           . '<td>'.$date.'</td>'
//                           . '<td>'.$timings.'</td>'
//                           . '<td>'.$span.' Min </td>'
//                           . '</tr>';   
                  
            }
            $subjects_tr.='</tr>';
            $timmings_tr.='</tr>';
            $date_tr.='</tr>';
            
            $exam_schedule.=$subjects_tr;  
            $exam_schedule.=$date_tr; 
            $exam_schedule.=$timmings_tr; 
            $exam_schedule.='</table>'; $i=1;
            foreach($student_details as $student){
                 $stud='<h3 style="text-align: center ; color:#0c4472"><strong>Student Details </strong></h3><br/>'.'<table cellpadding="1" cellspacing="1"  style=" color:#0c4472 ; ">'
                           .'<tr><td><strong>Name</strong></td>'.'<td>'.$student->name.'</td></tr>'
                           .'<tr><td><strong>Student ID</strong></td>'.'<td>'.$student->userid.'</td></tr>'
                           .'<tr><td><strong>Class-section</strong></td>'.'<td>'.$cls_sec.'</td></tr>'
                           .'<tr><td><strong>Roll No</strong></td>'.'<td>'.$student->roll.'</td></tr>'
                           .'</table>';
                 $html =$header.'<hr/><br/>'.$stud."<hr/>".$exam_schedule."<br/><hr/>";
                 $pdf->writeHTML($html, true, false, true, false, '');
                 if($i%2==0){
                    $pdf->AddPage(); 
                 }
                 $i++;
               //  $pdf->AddPage();
            }
            
              $pdf->Output("Accounts.pdf", 'I');
            
        }
        public function sample_hallticket(){
           
            $institute =  $this->fetch_institute_details();
            ?>
            <!DOCTYPE html>
                            <html lang="en">
                                <head>
                                    <title>Details OF Section</title>
                                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
                                    <link rel="stylesheet" href="<?php echo assets_path ?>/css/bootstrap.min.css">
                                     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                              <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                                </head>
                                <body style=" padding: 10px ; color:#0c4472 ;">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            &nbsp;
                                        </div>
                                        <div class="col-sm-10">
                                            <table cellpadding="1" cellspacing="1" style=" color:#0c4472 ; width: 100% "  >
                                                <tr>
                                                    <td colspan="2" align="left" >
                                                        <img src="<?php echo assets_path ?>/uploads/<?php echo $institute->logo ?>" width="150px" > 
                                                      </td>
                                                    <td colspan="3" align="right">
                                                        <strong style=" color:#0c4472 ; float: right;font-size: 20px;" >
                                                            <?php echo   $institute->name ?>
                                                        <br/> 
                                                              <?php  echo  str_replace("\n", "<br/>", $institute->address)  ?>
                                                         </strong> 
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-sm-1">
                                           &nbsp; 
                                        </div>
                                    </div>
                                </body>
                            </html>
            <?php
            
            
        }
        public function attendance($section=0){
           if($section==0){
               $this->load->view('home'); 
           }else{
               $data['section']=  $this->fetch_section_details($section);
               $this->load->view('teachers/view_attendance',$data); 
           }
        }
        
        public function add_date(){
            $date=explode('/',$_POST['date']);
            $date=  mktime(0, 0, 0, $date[1], $date[0], $date[2]);
            $section=$_POST['section'];
            $slot=$_POST['slot'];
            if($date > time()){
               echo "Invalid time stamp"; 
               exit;
            }elseif(!$this->check_date_slot($date,$section,$slot)){
                echo "Attendance Already Added.."; 
               exit;
            }else{
                $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'section' =>$section,
                    'day' =>$date,
                );
                $this->db->insert('attendance_date',$data);
                $aid=$this->db->insert_id();
                $config=$this->db->query("SELECT ac.* FROM `attendance_config` ac  JOIN attendance_settings a ON ac.asid=a.aid where a.section= '".$section."'");
                $config=$config->result();
                $acid=array();
                foreach ($config as $value) {
                   $acid[]=$value->acid; 
                }
                
               ?><script>window.location.replace("<?php echo base_url() ?>index.php/teachers/edit_attendance/<?php echo $section ?>/<?php echo $aid ?>");</script><?php
            }
        }
        
        public function save_attendance(){
            $post=$this->operations->cleanInput($_POST);
            $ids=explode(',',$post['ids']);
            foreach ($ids as $id) {
                if(strlen($post['status_'.$id])==0){
                    $this->form->setError('status_'.$id,'* Please enter marks'); 
                }
            }
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{ 
                foreach ($ids as $id) {
                   $data=array(
                       'status' =>$post['status_'.$id]
                   );
                   $this->db->where('id', $id);
                   $this->db->update('attendance',$data);    
                }
                 $this->session->set_userdata('attendance_update', 'Sucessfuylly Updated Attendance');
            }
           redirect('/teachers/view_attendance/'.$post['section'].'/'.$post['att_date'], 'refresh'); 
             
            
        }
        
        public function save_daily_attendance(){
            
           $date_Slot_ids = json_decode($this->input->post("date_Slot_ids"));
           $prev_attendance = array();
            $slots = array_filter(explode(",",$this->input->post("slots")));
            $student_ids = array_filter(explode(",",$this->input->post("id_values")));
            
            foreach($slots as $slot){
                //$prev_attendance[$slot] = json_decode($this->input->post("slot_".$slot));
                $arr =array();
                foreach(json_decode($this->input->post("slot_".$slot)) as $key=>$att){
                    $arr[$key] = array("ad_id" =>$att->ad_id,"roll"=>$att->roll);
                }
                 $prev_attendance[$slot] = $arr;
                foreach($student_ids as $stud){
                    if(strlen($this->input->post("status_".$slot."_".$stud)) ==  0)
                    {
                      $this->form->setError( "status_".$slot."_".$stud ,'* Please Provide Class Name');
                    }
                }
                
                
            }
           // print_r($prev_attendance);
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               
            }else{
                $delete_ids="";
                foreach($slots as $slot){
                    
                    foreach($student_ids as $stud){
                        
                        if($this->input->post("status_".$slot."_".$stud)==1){
                            if(array_key_exists($stud, $prev_attendance[$slot])){
                              $delete_ids.=$prev_attendance[$slot][$stud]['ad_id'].",";
                            }
                        }else{
                            
                            if(!array_key_exists($stud, $prev_attendance[$slot])){
                               $data=array(
                                    'iid' =>$this->session->userdata('staff_Org_id'),
                                    'date_id' =>$key,
                                    'acid' =>$date_Slot_ids[$slot],
                                    'student' =>$stud,
                                    'status' =>0,
                                    );
                                 $this->db->insert('attendance',$data);                            
                            }
                            
                        }   
                        
                        
                    }

                }
                  $delete_ids = substr($delete_ids, 0, strlen($delete_ids)-1);
                if(strlen($delete_ids)!=0){
                   $this->delete_attendance_records($delete_ids); 
                }
               
                $this->session->set_userdata('attendance_update', 'Sucessfully Updated Attendance');
            }
             redirect("teachers/edit_date_attendance/".$this->input->post("section")."/".date('d-m-y',$this->input->post("att_date")),"refresh");
            
        }
        
        public function view_attendance($section=0,$id){
          if( ($section==0) || ($id==0) ){
            $this->load->view('home');
          }elseif(!$this->check_att_date($id, $section)){
             $this->load->view('home'); 
          }else{
              $data['section']=  $this->fetch_section_details($section);
              $data['data']=  $this->get_attendance_date($id);
              $data['attendance']=  $this->get_attendance_details($id);
             
              $this->load->view('teachers/view_dailyattendance',$data); 
          }            
        }
        
        public function view_day_attendance($section=0,$date){
          if( ($section==0) || ($date==0) ){
            $this->load->view('home');
          }elseif(!$this->check_att_day($date, $section)){
             $this->load->view('home'); 
          }else{
              $data['section']=  $this->fetch_section_details($section);
              $data['data']=  $this->get_attendance_bydate($date,$section);
              $id ="";$date="";
              foreach($data['data'] as $d){
                 $id .= $d->id.","; 
                 $date =$d->day;
              }
              $data['date']= $date;
              $id = substr($id,0,strlen($id)-1);
              $data['attendance']=  $this->get_dayattendance_details($id);
//              echo "<pre>";
//              print_r($data);exit;
              $this->load->view('teachers/view_dailyattendance1',$data); 
          }            
        }
        
        public function edit_date_attendance($section=0,$date){
          if( ($section==0) || ($date==0) ){
            $this->load->view('home');
          }elseif(!$this->check_att_day($date, $section)){
             $this->load->view('home'); 
          }else{
              $data['section']=  $this->fetch_section_details($section);
              $data['data']=  $this->get_attendance_bydate($date,$section);
              $id ="";$date="";
              foreach($data['data'] as $d){
                 $id .= $d->id.","; 
                 $date =$d->day;
              }
              $data['date']= $date;
              $id = substr($id,0,strlen($id)-1);
              $data['attendance']=  $this->get_dayattendance_details($id);
//              echo "<pre>";
//              print_r($data);exit;
              $this->load->view('teachers/edit_attendance',$data); 
          }            
        }
        
        public function edit_attendance($section=0,$id){
          if( ($section==0) || ($id==0) ){
            $this->load->view('home');
          }elseif(!$this->check_att_date($id, $section)){
             $this->load->view('home'); 
          }else{
              $data['section']=  $this->fetch_section_details($section);
              $data['data']=  $this->get_attendance_date($id);
              $data['attendance']=  $this->get_attendance_details($id);
              $this->load->view('teachers/enter_attendance',$data); 
          }            
        }
        
        public function edit_marks($examid=0,$section=0){
            if($examid==0 || $section==0 ){
                $this->load->view('teachers/viewexams');
            }else{
                $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
                $section =$section->row();
                $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id='".$examid."'");
                $exam =$exam->row();
                $data['section']=$section;  
                $data['exam']=$exam;
                $this->load->view('teachers/enter_marks',$data);
            }
        }
        
        public function view_marks($examid=0,$section=0){
            if($examid==0 || $section==0 ){
                $this->load->view('teachers/viewexams');
            }else{
                $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
                $section =$section->row();
                $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id='".$examid."'");
                $exam =$exam->row();
                $data['section']=$section;  
                $data['exam']=$exam;
                $this->load->view('teachers/view_marks',$data);
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
                   $this->form->setError($field,'* Please Enter numeric value'); 
                }elseif( $post[$field] > $maxmarks ){
                  $this->form->setError($field,'* enter valid marks'); 
                }
            }
          
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
               redirect('/teachers/edit_marks/'.$examid.'/'.$post['section'], 'refresh'); 
            
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
                     $this->db->where('student_id', $id);
                     $this->db->update('marks',$data);    
                  }
                   
                   
               }
                $this->session->set_userdata('marks_update_sucess', 'Sucessfuylly Updated Marks');
                redirect("teachers/examination","refresh");
            }
            
            
        }
        
        public function exam_reports(){
           $this->load->view('teachers/progressreport');
        }
        
        public function fetch_exams(){
            $query=  $this->db->query("SELECT ec.id as ecid,e.id as eid,e.exam,e.startdate,e.enddate  FROM `examination_cls` ec  JOIN examinations e ON e.id=ec.examid WHERE ( ec.status=1 AND e.status=1 ) AND sectionid= '".$this->input->post('section')."' ");
            if($query->num_rows()>0){
                $query=$query->result();
            ?>
               
               <select class="select2-me" id="exam" name="exam" onchange=""   style=" width: 100% "  >
                <option value="" >Please select Exam</option>
                <?php
                    foreach($query as $val){
                        ?><option value="<?php echo $val->eid.",".$val->ecid   ?>" ><?php echo $val->exam ?>( <?php echo date('d-m-Y',$val->startdate)." - ".date('d-m-Y',$val->enddate) ?> )</option><?php
                    }
                ?>
                </select> 
                <span id="exam_err" style=" color: red">
                  
                </span>
               <script>
                $( "#fetch" ).prop( "disabled", false );  
               </script>
               <?php
            }else{
                ?>
               <select   class="select2-me" id="exam" name="exam"  style=" width: 100%; display: none "  >
                   
               </select>
                <span id="exam_err" style=" color: red">
                   No Exams Scheduled
                </span>
                <?php
                
            }
            
            
               
        }
        
        public function fetch_marks_report(){
            $section=$this->input->post('section');
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            $exam_details=  $this->exam_details($ecid);
           ?>
            <div id="results_holder" class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i><?php echo $exam_details->cls_name." , ".$exam_details->section_name  ."  --  " .$exam_details->exam ."   Results" ?></h3>
                        <div class="actions">
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/print_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>"   class="btn"  >
                            <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/print_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>&type=pdf"   class="btn"  >
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </a>
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/download_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>"   class="btn"  >
                            <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
<!--                            <a  id='send_sms_btn' onclick="sendsms('<?php echo $section ?>','<?php echo implode(',',$exam); ?>');"    class="btn"  >
                            <i class="fa fa-share" aria-hidden="true"></i></a>-->
                        </div>
                    </div>
                <div class="box-content nopadding" style=" height: 350px; overflow-y: auto"  >
                    <?php
                      $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  ";
                      $query=$this->db->query($query); 
                      $query=$query->result();
                      $stud_marks=array();
                      $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section."'  ")->result();;
                      foreach($s as $value){
                          $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
                      }

                      foreach ($query as $val)
                      {
                          $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                          foreach($k as $p){
                             $stud_marks[$p->student_id][$val->id]=$p->marks ;
                          }
                      }  

                    ?>
                    <table style=" overflow-y: auto; height: 10px; " border="1"  class="table table-hover table-nomargin table-bordered">
                        <thead >
                            <tr style="">
                                <th>Exam &#8594;<br/>&#8595;&nbsp;Students</th>
                                <?php
                                    foreach( $query as $exam){
                                        ?>
                                <th align="center" style="  text-align: center">
                                    <?php echo $exam->subject   ?>
                                    <br/>
                                    <?php echo $exam->maxmarks."  |  ".$exam->minmarks ?>
                                    </p>
                                </th>
                                      <?php
                                    }                                      
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                foreach($stud_marks as $stud){
                                   ?>
                                        <tr>
                                            <td><?php echo $stud['name']  ?></td>
                                            <?php
                                               foreach( $query as $e){
                                                   ?>
                                                    <td align="center">
                                                        <?php 
                                                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                                                          echo $stud_marks[$stud['id']][$e->id];  
                                                        } else{
                                                        echo "-" ;
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
            <?php
        }
        
        public function fetch_mcmarks_report(){
            $section=$this->input->post('section');
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            $exam_details=  $this->exam_details($ecid);
           ?>
            <div id="results_holder" class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i><?php echo $exam_details->cls_name." , ".$exam_details->section_name  ."  --  " .$exam_details->exam ."   Results" ?></h3>
                        <div class="actions">
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/print_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>"   class="btn"  >
                            <i class="fa fa-print" aria-hidden="true"></i>
                            </a>
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/print_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>&type=pdf"   class="btn"  >
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </a>
                            <a  target="_blank" href="<?php echo base_url(); ?>index.php/teachers/download_report?section=<?php echo $section ?>&exam=<?php echo implode(',',$exam); ?>"   class="btn"  >
                            <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
<!--                            <a  id='send_sms_btn' onclick="sendsms('<?php echo $section ?>','<?php echo implode(',',$exam); ?>');"    class="btn"  >
                            <i class="fa fa-share" aria-hidden="true"></i></a>-->
                        </div>
                    </div>
                <div class="box-content nopadding" style=" height: 350px; overflow-y: auto"  >
                    <?php
                      $query="SELECT e.id,e.maxmarks,e.questions,s.subject FROM `mcexam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  ";
                      $query=$this->db->query($query); 
                      $query=$query->result();
                      $stud_marks=array();
                      $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section."'  ")->result();;
                      foreach($s as $value){
                          $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
                      }

                      foreach ($query as $val)
                      {
                          $k=$this->db->query("SELECT st.student_id,m.marks,m.correct,m.wrong FROM `student` st LEFT JOIN mcmarks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                          foreach($k as $p){
                             $stud_marks[$p->student_id][$val->id]=array("marks"=>$p->marks,"correct"=>$p->correct,"wrong"=>$p->wrong); ;
                          }
                      }  

                    ?>
                    <table style=" overflow-y: auto; height: 10px; " border="1"  class="table table-hover table-nomargin table-bordered">
                        <thead >
                            <tr style="">
                                <th>Exam &#8594;<br/>&#8595;&nbsp;Students</th>
                                <?php
                                    foreach( $query as $exam){
                                        ?>
                                <th align="center" style="  text-align: center">
                                    <?php echo $exam->subject   ?>
                                    <table style="width: 100%">
                                        <tr>
                                            <th>c</th>
                                            <th>w</th>
                                            <th>L</th>
                                            <th>M</th>
                                        </tr>
                                    </table>
                                </th>
                                      <?php
                                    }                                      
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                foreach($stud_marks as $stud){
                                   ?>
                                        <tr>
                                            <td><?php echo $stud['name']  ?></td>
                                            <?php
                                               foreach( $query as $e){
                                                   ?>
                                                    <td align="center">
                                                        <table style="width: 100%">
                                                        <tr>
                                                            <td><?php 
                                                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                                                          echo $stud_marks[$stud['id']][$e->id]['correct'];  
                                                        } else{
                                                        echo "-" ;
                                                        }
                                                        ?></td>
                                                            <td><?php 
                                                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                                                          echo $stud_marks[$stud['id']][$e->id]['wrong'];  
                                                        } else{
                                                        echo "-" ;
                                                        }
                                                        ?></td>
                                                            <td>0
                                                            </td>
                                                            <td>
                                                        <?php 
                                                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                                                          echo $stud_marks[$stud['id']][$e->id]['marks'];  
                                                        } else{
                                                        echo "-" ;
                                                        }
                                                        ?>
                                                            </td>
                                                        </tr>
                                                        </table>
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
            <?php
        }
        
        
        public function attendance_Report(){
            $this->load->view('teachers/schoolattendance');
        }
        
        public function send_report(){
       
            $section=$this->input->post('section');
            $exam=explode(",",$this->input->post('exam'));
            
            $examid=$exam[0];
            $ecid=$exam[1];
            $edetails=$this->db->query("SELECT * FROM `examinations` WHERE id='".$examid."' ")->row();
            $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  ";
            $query=$this->db->query($query); 
            $query=$query->result();
            $stud_marks=array();

            $s=$this->db->query("SELECT s.student_id as id,s.name,s.phone as stdph,p.phone as prntph from student s LEFT JOIN parent p ON s.parent_id=p.parent_id WHERE s.section_id ='".$section."'  ")->result();;
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'stdph'=>$value->stdph,'prntph'=>$value->prntph);
            }

            foreach ($query as $val)
            {
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                foreach($k as $p){
                   $stud_marks[$p->student_id][$val->id]=$p->marks ;
                }
            }  
           
            $sms_details=$this->fetch_sms_details();
        
           $msgcount=0;
            foreach($stud_marks as $stud){
             
               $content="";
                foreach( $query as $e){
                      
                        // print_r($e);
                        
                         if( isset($stud_marks[$stud['id']][$e->id]) ){
                           $m= $stud_marks[$stud['id']][$e->id];  
                         } else{
                         $m= "-" ;
                         }
                     $content.= $e->subject ." : ".$m."\n";
                }
               $msg="Dear ".$stud['name']." , Your results For Exam :".$edetails->exam ." is as follows : \n" .$content ;
               $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'username'=>$sms_details->username,
                    'password' =>$sms_details->password,
                    'senderid' =>$sms_details->senderid,
                    'message'  =>$msg,
                    'mobile' =>$stud['stdph'],
                    'time' =>time(),
               'status' =>1
                );
               
                $this->db->insert('msg_senthistory',$data);
                $msgcount++;
                if( strlen($stud['prntph'])!=0 ){
                    $msg="Dear Parent \n Your Ward : ".$stud['name']." \n Your results For Exam :".$edetails->exam ." is as follows : \n" .$content ;
               
                    $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'username'=>$sms_details->username,
                    'password' =>$sms_details->password,
                    'senderid' =>$sms_details->senderid,
                    'message'  =>$msg,
                    'mobile' =>$stud['prntph'],
                    'time' =>time(),
               'status' =>1
                    );
                   
                    $this->db->insert('msg_senthistory',$data);
                    $msgcount++;
                }
                
            }
          ?>
               <Script>
                alert("Resuts Sent as a Message To students and parents ");
               </script>
          <?php

        }
        
        public function download_report(){
            $this->load->helper('download');
            $section=$this->input->get('section');
            $exam=explode(",",$this->input->get('exam'));
            
            $examid=$exam[0];
            $ecid=$exam[1];
           
                      $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  ";
                      $query=$this->db->query($query); 
                      $query=$query->result();
                      $stud_marks=array();
                    
                      $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section."'  ")->result();;
                      foreach($s as $value){
                          $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
                      }

                      foreach ($query as $val)
                      {
                          $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                          foreach($k as $p){
                             $stud_marks[$p->student_id][$val->id]=$p->marks ;
                          }
                      }  
                      $csv_content="";

            $csv_content.="students".",";

                foreach( $query as $exam){
            $csv_content.= $exam->subject.",";

                }    
                $csv_content.="\n";
          
            foreach($stud_marks as $stud){
                $csv_content.=$stud['name'].",";

                foreach( $query as $e){


                         if( isset($stud_marks[$stud['id']][$e->id]) ){
                           $m= $stud_marks[$stud['id']][$e->id];  
                         } else{
                         $m= "-" ;
                         }
                          $csv_content.=$m.",";


                }
                $csv_content.="\n";

            }
        
        $file = 'results.csv';
      force_download($file, $csv_content);
       
?>
               <script>window.close();</script>
               <?php
        }
        
        public function pdf_report(){
            $section=$this->input->get('section');
            $exam=explode(",",$this->input->get('exam'));
            $type=$this->input->get('type');
            $examid=$exam[0];
            $ecid=$exam[1];
             $exam_details=  $this->exam_details($ecid);
            $institute =  $this->fetch_institute_details();
            $query=$this->db->query("SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  "); 
            $query=$query->result();
            $stud_marks=array();
            $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section."'  ")->result();
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
            }
            foreach ($query as $val)
            {
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                foreach($k as $p){
                   $stud_marks[$p->student_id][$val->id]=$p->marks ;
                }
            } 
           
            $this->load->library('Pdf');
            $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }

            $pdf->AddPage();
            $balance = '<table cellpadding="1" cellspacing="1" style=" color:#0c4472 ; "  >
                                    <tr>
                                        <td colspan="2" align="left" >
                                            <img src="'.  assets_path."/uploads/".$institute->logo.'" width="150px" >                                                </td>
                                        <td colspan="3" align="right">
                                            <strong style=" color:#0c4472 ; float: right;font-size: 20px;" >'.$institute->name.'</strong>
                                            <br/> <strong style=" color:#0c4472 ; float: right;" >
                                                     '.str_replace("\n", "<br/>", $institute->address).'
                                             </strong> 
                                        </td>
                                    </tr>
                                </table><br/>'.'<h3 style="text-align: center ; color:#0c4472"><strong>'. $exam_details->cls_name." , ".$exam_details->section_name  .'  --  ' .$exam_details->exam .'  Results </strong></h3><hr/> ';
            $balance='<table  border="1" style="text-align:center;  color:#0c4472 ;">
                                       <tr>
                                        <th><strong>Students</strong></th>';    
            foreach( $query as $exam){
                $balance.='<th><strong>'.$exam->subject .'</strong></th> ';
                } 
                $balance.='</tr>';

            foreach($stud_marks as $stud){
                $balance.=  '<tr>'.'<td>'.$stud['name'].'</td>';
                 foreach( $query as $e){
                    $mrk="-";
                    if( isset($stud_marks[$stud['id']][$e->id]) ){
                      $mrk= $stud_marks[$stud['id']][$e->id];  
                    } 
                    $balance.='<td>'.$mrk.'</td>';
                 }
                  $balance.=  '</tr>';

                 }
                           
                $balance.= '</table>';
                $pdf->writeHTML($balance, true, false, true, false, '');
              $pdf->Output("Accounts.pdf", 'D');
          }
        
        public function print_report(){
            ob_start();
            $section=$this->input->get('section');
            $exam=explode(",",$this->input->get('exam'));
            $type=$this->input->get('type');
            $examid=$exam[0];
            $ecid=$exam[1];
             $exam_details=  $this->exam_details($ecid);
            $institute =  $this->fetch_institute_details();
            $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$examid."' AND ecid='".$ecid."'  ";
            $query=$this->db->query($query); 
            $query=$query->result();
            $stud_marks=array();
            $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section."'  ")->result();
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
            }

            foreach ($query as $val)
            {
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                foreach($k as $p){
                   $stud_marks[$p->student_id][$val->id]=$p->marks ;
                }
            }  

            if($type=='pdf'){
                $this->load->library('Pdf');
                $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                        require_once(dirname(__FILE__).'/lang/eng.php');
                        $pdf->setLanguageArray($l);
                }

                $pdf->AddPage();
                $balance = '<table cellpadding="1" cellspacing="1" style=" color:#0c4472 ; "  >
                                        <tr>
                                            <td colspan="2" align="left" >
                                                <img src="'.assets_path .'/uploads/'.$institute->logo .'" width="150px" >                                                </td>
                                            <td colspan="3" align="right">
                                                <strong style=" color:#0c4472 ; float: right;font-size: 20px;" >'.$institute->name.'</strong>
                                                <br/> <strong style=" color:#0c4472 ; float: right;" >
                                                          '.str_replace("\n", "<br/>", $institute->address) .'
                                                 </strong> 
                                            </td>
                                        </tr>
                                    </table><br/>'.'<h3 style="text-align: center ; color:#0c4472"><strong>'. $exam_details->cls_name." , ".$exam_details->section_name  .'  --  ' .$exam_details->exam .'  Results </strong></h3><hr/> ';
                $balance.='<table  border="1" style="text-align:center;  color:#0c4472 ;">
                                       <tr>
                                        <th><strong>Students</strong></th>';    
                foreach( $query as $exam){
                    $balance.='<th><strong>'.$exam->subject .'</strong></th> ';
                    } 
                    $balance.='</tr>';
                 
                foreach($stud_marks as $stud){
                    $balance.=  '<tr><td>'.$stud['name'].'</td>';
                     foreach( $query as $e){
                        $mrk=" - ";
                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                          $mrk= $stud_marks[$stud['id']][$e->id];  
                        } 
                        $balance.='<td>'.$mrk.'</td>';
                     }
                      $balance.=  '</tr>';
                                            
                     }
                           
                $balance.=  '</table>';
                
                $pdf->writeHTML($balance, true, false, true, false, '');
              $pdf->Output("Results.pdf", 'D');
                ?>
               <script>
                   window.close();
                   </script>
                <?php
            }else
                {
                ?>
                             <!DOCTYPE html>
<html lang="en">
    <head>
        <title> Results </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
        <link rel="stylesheet" href="http://localhost:82/school/assests/css/bootstrap.min.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body style=" padding: 1% ; color:#0c4472 ;">
        <div class="container-fluid" id="content">
           
  <div class="row"  style="  ">
    <div class="col-sm-12 nopadding" style=" padding: 1% ;">
        <div class="col-sm-12">
            <div class="pull-left"><br/>
                <img src="<?php echo assets_path ?>/uploads/<?php echo $institute->logo ?>" style=" width: 100px">            
            </div>        
            <div class="pull-right">
                <h3><strong style=" color:#0c4472 ; float: right" ><?php echo $institute->name ?></strong></h3>
                <strong style=" color:#0c4472 ; float: right ;  text-align: right" ><br/> 
                 <?php 
                   echo str_replace("\n", "<br/>", $institute->address) ;
                 ?>
                </strong>
            </div>
         </div>
        <div style=" clear: both" class="col-sm-12">
            <br/>
            <h3 style="text-align: center"><strong><?php echo $exam_details->cls_name." , ".$exam_details->section_name  ."  --  " .$exam_details->exam ."   Results" ?></strong></h3>
            
            <div class="col-sm-12">
             <div id="results_holder" class="box box-color box-bordered nopadding">
                    
                <div class="box-content nopadding"   >
                    
                    <table style=" overflow-y: auto; height: 10px; " border="1"  class="table table-hover table-nomargin table-bordered">
                        <thead >
                            <tr style="">
                                <th>Exam &#8594;<br/>&#8595;&nbsp;Students</th>
                                <?php
                                    foreach( $query as $exam){
                                        ?>
                                <th align="center" style="  text-align: center">
                                    <?php echo $exam->subject   ?>
                                    <br/>
                                    <?php echo $exam->maxmarks."  |  ".$exam->minmarks ?>
                                    </p>
                                </th>
                                      <?php
                                    }                                      
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                foreach($stud_marks as $stud){
                                   ?>
                                        <tr>
                                            <td><?php echo $stud['name']  ?></td>
                                            <?php
                                               foreach( $query as $e){
                                                   ?>
                                                    <td align="center">
                                                        <?php 
                                                        if( isset($stud_marks[$stud['id']][$e->id]) ){
                                                          echo $stud_marks[$stud['id']][$e->id];  
                                                        } else{
                                                        echo "-" ;
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
    </div>
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
       
        private function fetch_section_details($section){
          
          $query=  $this->db->query("SELECT s.sid,s.name as section,c.name as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND s.sid='".$section."' AND s.cls_tch_id='".$this->session->userdata('staff_id')."'  ");
          if($query->num_rows()>0){
            return $query->row();
           }else{
               return FALSE;
           }
         
        }
        
        private function check_date_slot($time,$section,$slot){
            $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE `day` ='".$time."' AND slot='".$slot."' AND `section` ='".$section."' AND `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()>0){
                return FALSE;
            }else{
                return TRUE;
            }
            
        }
        
        private function check_att_date($id,$section){
           $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE `id` ='".$id."' AND `section` ='".$section."' AND `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()>0){
                return TRUE;
            }else{
                return FALSE;
            } 
            
        }
        
        private function check_att_day($date,$section){
            $date = explode("-",$date);
           $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE day='".  mktime(0, 0, 0, $date[1], $date[0], $date[2])."'  AND `section` ='".$section."' AND `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()>0){
                return TRUE;
            }else{
                return FALSE;
            } 
            
        }
        
        private function get_attendance_details($aid){
            echo "SELECT a.id,a.status,a.acid,s.name,s.roll FROM `attendance` a JOIN student s ON a.student=s.student_id WHERE `date_id`='".$aid."'";
           $query=  $this->db->query("SELECT a.id,a.status,a.acid,s.name,s.roll FROM `attendance` a JOIN student s ON a.student=s.student_id WHERE `date_id`='".$aid."'");
           $query=$query->result();
           return $query;
        }
        
        private function get_dayattendance_details($aid){
            $query=  $this->db->query("SELECT a.id,a.status,a.acid,a.student,s.name,s.roll FROM `attendance` a JOIN student s ON a.student=s.student_id WHERE `date_id` IN (".$aid.")");
           $query=$query->result();
           return $query;
        }
        
        private function get_attendance_date($aid){
           $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE id='".$aid."'");
           $query=$query->row();
           return $query; 
        }
        
        private function get_attendance_bydate($date,$section){
            $date = explode("-",$date);
           $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE day='".  mktime(0, 0, 0, $date[1], $date[0], $date[2])."' AND section = '".$section."'");
           $query=$query->result();
           return $query; 
        }
        
        private function check_exam($exam){
            $query=  $this->db->query("SELECT * FROM `examinations` WHERE id='".$exam."' AND iid='".$this->session->userdata("staff_Org_id")."'  ");
            if($query->num_rows()==0){
                return FALSE;
            }else{
                $query=$query->row();
                $data['exam']=$query;
                $query=$this->db->query("SELECT e.id,s.sid,s.name as section,c.name  as cls_name FROM `examination_cls` e JOIN section s ON e.sectionid=s.sid jOIN class c ON s.class_id=c.id WHERE examid='".$query->id."' AND status=1");
                $query=$query->result();
                $data['class_list']=$query;
                return $data;
            }
        }
        
        private function fetch_sms_details(){
            $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
            return $msg;
        }
        
        private function exam_details($ecid){
         $query=$this->db->query("SELECT e.exam,s.name as section_name ,c.name as cls_name FROM examination_cls ec  JOIN examinations e ON ec.examid=e.id JOIN section s ON s.sid=ec.sectionid JOIN class c On c.id=s.class_id WHERE ec.id ='".$ecid."'  " );
          return  $query->row();
         
        }
        
        private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
        
        public function get_sample_file($examid=0,$section=0){
             $this->load->helper('download');
            if($examid==0 || $section==0 ){
                $this->load->view('teachers/viewexams');
            }else{
                $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
                $section =$section->row();
                $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id='".$examid."'");
                $exam =$exam->row();
                
                $students= $this->db->query("SELECT * FROM student where section_id='".$section->sid."'");
                $students = $students->result();
                $csv_content="Roll,Name,".$exam->subject."\n";
                foreach($students as $value){
                   $csv_content.=$value->roll.",".$value->name.", ,\n";
                }
                
                $file = 'sample_marks_Sheet.csv';
                force_download($file, $csv_content);
                
                
            }
        }
        
        public function enter_marks($examid=0,$section=0){
            
            if($examid==0 || $section==0 ){
                $this->load->view('teachers/viewexams');
            }else{
                $section = $this->db->query("SELECT se.sid,se.name as section , c.name as class FROM `section` se JOIN class c ON se.class_id=c.id WHERE sid='".$section."'");
                $section =$section->row();
                $exam = $this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id='".$examid."'");
                $exam =$exam->row();
                $data['section']=$section;  
                $data['exam']=$exam;
                $this->load->view('teachers/add_marks',$data);
                
            }
        }
        
        public function submit_marks(){
            
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
                redirect("teachers/enter_marks/".$this->input->post("examid")."/".$this->input->post("section"),'refresh');
            }
            else
            {
                $file_pointer = fopen($file['tmp_name'], "r");
                $file_read = fread($file_pointer, $file['size']);

                $newdata=$file_read;
                $ex=  array_filter(explode("\n",$newdata));
                if(!strpos($ex[0],"Roll,Name")){
                    array_shift($ex); 
                }
                $arr = array();
                $rolls ="";
                foreach($ex as $val){
                   $val = explode(",",$val); 
                   $rolls.=$val[0].","; 
                   $arr[$val[0]]= $val;
                }
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

                            if($this->input->post("maxmarks") <= $arr[$r][2] ){
                                $data = array(
                                 'iid' => $this->session->userdata('staff_Org_id'),
                                 'exam_id' => $this->input->post("examid"),
                                 'student_id' => $student_details[$r]['stud_id'] ,
                                 'marks' =>$arr[$r][2]
                                );
                                $valid_Studid.=$r.",";
                               $this->db->insert('marks',$data); 
                            }else{
                             $failed_records[$student_details[$r]['stud_id']]= array("details"=>$student_details[$r],'marks'=>$arr[$r][2],'error'=>"Invalid marks");
                            }
                        }else{
                          $invalid_records[$student_details[$r]['stud_id']]= array("details"=>$arr[$r],"error"=>'invalid record entry');

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
                    $this->session->set_userdata("minmarks",  $this->input->post("minmarks"));
                  
                     redirect("teachers/enter_marks/".$this->input->post("examid")."/".$this->input->post("section"),'refresh');
                }else{
                    redirect("teachers/examination/","refresh");
                }
                
                 
            }
     

        }
        
        public function view_records(){
            $this->load->view("teachers/view_exam_records");
        }
        
        public function save_exam_reccords(){

            
            $examid=$this->input->post("examid");

            
            $exam_details = $this->db->query("SELECT ec.sectionid,e.maxmarks,e.minmarks FROM exam e  JOIN examination_cls ec ON e.ecid = ec.id where e.id='".$examid."' ");
            $exam_details = $exam_details->row();
            print_r($exam_details);
            $sectionid = $exam_details->sectionid;
            $maxmarks = $exam_details->maxmarks;
            $sectionid = $exam_details->sectionid;
            $ids = $this->input->post("ids");
            $ids = array_filter(explode(",",$ids));
            
            foreach($ids as $id){
               $field="stud_marks_".$id; 
               if(strlen(trim($this->input->post($field))) == 0)
                {
                    $this->form->setError($field,'* please enter marks');
                }elseif($this->input->post($field) > $maxmarks ){
                     $this->form->setError($field,'* enetered marks excedded max marks');
                }
              
            }
            
            if($this->form->num_errors >0 )
            {
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect("teachers/view_records",'refresh');
            }
            else
            {
                foreach($ids as $id){
                    $data = array(
                             'iid' => $this->session->userdata('staff_Org_id'),
                             'exam_id' => $examid,
                             'student_id' => $id ,
                             'marks' =>$this->input->post("stud_marks_".$id)
                            );
                     $this->db->insert('marks',$data);  
                }
                $this->session->unset_userdata('failed_records');
                $this->session->unset_userdata('dummy_records');
                $this->session->unset_userdata('examid');
                $this->session->unset_userdata('maxmarks');
                $this->session->unset_userdata('minmarks');
                $this->session->set_userdata('marks_update_sucess',"Sucessfully updated marks list");
                redirect("teachers/examination","refresh");
            }
            
        }
        
        
        public function view_section_attendance($section=0){
           if($section==0){
               $this->load->view('home'); 
           }else{
               $data['section']=  $this->fetch_section_details($section);
               $this->load->view('teachers/view_attendance',$data); 
           }
        }
        
        private function delete_attendance_records($ch_stud){
         $where = " id in (".$ch_stud.") ";
         $this->db->where($where);
         //$this->db->where_in('id', $data);
         $this->db->delete('attendance');
    }
        
}