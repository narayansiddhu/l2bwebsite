<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class timetable extends CI_Controller {

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
        }
    
    public function index(){
        redirect("timetable/view" ,'refresh');
    }
    
    public function section($section=0){
        $section = trim($section);
        if($section!=0){
            
        }else{
           redirect('timetable/view', 'refresh');
        }
    }
    
    public function create(){
             $this->load->view('timetable/create');  
        
    }
    
    public function delete($tid=0){
       if($tid!=0){
            $timetable=  $this->fetch_details($tid);
            if(!$timetable){
                redirect('timetable/view', 'refresh'); 
            }else{
              $this->db->where('tid',$timetable->tid);
              $this->db->delete('timings');//class_routine
              $this->db->where('tid',$timetable->tid);
              $this->db->delete('class_routine');
              $this->session->set_userdata('delete_time_table', 'Sucessfully Deleted Time Table '); 
              redirect('timetable/view', 'refresh'); 
            }
       }else{
           $this->load->view('timetable/viewall');
       }
    }
    public function step2(){
       $q= base_url()."index.php/timetable/create_step2/".$this->input->post('stdsection'). "/".$this->input->post('periods'); 
        ?>
<script>
    window.location.href = "<?php echo $q ?>";
</script>
          <?php
    }
    
    public function create_step2($section,$periods){
       
        if( (strlen($section)!=0) &&(strlen($periods)!=0) ) {
             $post=array();
                $post['stdsection'] =  $section;
                $post['periods'] =  $periods;
                $data['post']= $post;
                $this->load->view('timetable/create_step2',$data);   
           
        }else{
              redirect('timetable/view', 'refresh'); 
        }
               
    }
    
    public function view($tid=0){
       if($tid!=0){
            $data['timetable']=  $this->fetch_details($tid);
            
            if(!$data['timetable']){
                redirect('timetable/view', 'refresh'); 
            }else{
               $this->load->view('timetable/view',$data);    
            }
       }else{
           $this->load->view('timetable/viewall');
       }
    }
    
    public function edit($tid=0){
       if($tid!=0){
           $t=$this->fetch_details($tid);
           if(!$t){
               redirect("timetable/view",'refresh');
           }
           $data['timetable']=  $t;
           $this->load->view('timetable/settings',$data);
       }else{
           $this->load->view('timetable/view');
       }
    }
    
    public function pdf_print($tid=0){
       if($tid!=0){
           $t=$this->fetch_details($tid);
           if(!$t){
               redirect("timetable/view",'refresh');
           }
           $institute=  $this->fetch_institute_details();
          
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
                    <br/><br/>
                    <u>
                        <h3 style="text-align: center ; color:#0c4472"><strong>Time Table Of <?php echo $t->class ." - ".$t->section ?></strong></h3><hr/>

                    </u>
                        <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">

                                        <tr>
                                            <th>Day/timings</th>
                                            <?php
                                            $weekdays = unserialize (Week_days);
                                            $start=$t->start;
                                            $noofc=$t->classes;
                                            $span=$t->span;

                                            $periods=$this->db->query("SELECT DISTINCT (time_start),time_end  FROM `class_routine` WHERE `tid` = '".$t->tid."'  ");
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
                                           $query=$this->db->query("SELECT cr.*,c.subid,s.subject FROM class_routine cr LEFT JOIN course c ON cr.course_id =c.cid LEFT JOIN subjects s ON c.subid=s.sid  WHERE cr.tid='".$t->tid."' ORDER BY cr.day asc,cr.time_start ASC ");

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
                   </div>
                <script>
                    window.print();
                    </script>
            </body>
            </html>
           <?php
       }else{
           $this->load->view('timetable/view');
       }
    }

    public function save_settings(){
       $post=$this->operations->cleanInput($_POST);
       
       $ids=$post['ids'];
       $ids=explode(',',$ids);
       foreach ($ids as $id) {
         if(strlen($post['cr_subject_'.$id]) ==0 ){
            $this->form->setError('cr_subject_'.$id,'*please select');  
         } else{
             $timings=explode(",",$post['day_timings_sub_'.$id]);
             $query="SELECT * FROM class_routine cr JOIN course c ON cr.course_id=c.cid  WHERE  c.tid='".$post['Staff_'.$id]."' AND ( (cr.time_start > '".$timings[1]."'  AND cr.time_start <'".$timings[2]."' ) || (cr.time_start = '".$timings[1]."'  AND cr.time_end ='".$timings[2]."' )    ||(cr.time_end > '".$timings[1]."'  AND cr.time_end <'".$timings[2]."') ) AND cr.day='".$timings[0]."' AND cr.class_routine_id != '".$id."'";
             $query =  $this->db->query($query);
             if($query->num_rows() > 0){
                 $this->form->setError('cr_subject_'.$id,'**Faculty Unavailable');  
             }
         } 
       }
       
       if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                 redirect('/timetable/edit/'.$post['tid'], 'refresh');   
        }else{ 
           foreach ($ids as $id) {
            $data = array(
               'course_id' => $post['cr_subject_'.$id]
            );
              
            $this->db->where('class_routine_id', $id);
            $this->db->update('class_routine', $data);
           } 
           $this->logs->insert_staff_log(25,'Edited Class Routine',$post['tid'] );
            redirect('/timetable/view/'.$post['tid'], 'refresh');   
        }
     
       
    }
    
    public function save(){
        
        
        $post=$this->operations->cleanInput($_POST);
        $timmings= array();
        
        
        $field="stdsection";
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please select section');
        }elseif(!$this->check_section(trim($post[$field]))){
            $this->form->setError($field,'* Time Table Already Assigned');
        }
        
        
        $field='periods';
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide No of classes');
        }elseif(!is_numeric($post[$field])){
            $this->form->setError($field,'* Provide Numeric Value');
        }else{
            for($i=1;$i<=$post[$field];$i++){
                
                $from ="from_".$i;
                $to ="to_".$i;
            // echo "from :".$post[$from]."  to : ".$post[$to];
                if(strlen($post[$from])==0){
                    $this->form->setError($from,'* Please provide from time');
                }else if(strlen($post[$to])==0){
                    $this->form->setError($to,'* Please provide to time');
                }elseif($post[$from]==$post[$to]){
                    $this->form->setError($to,'* from and to time must be different');
                    $this->form->setError($from,'* from and to time must be different');
                }else{
                    
                    $from1 = $this->send_time($post[$from]);
                    $to1 = $this->send_time($post[$to]);
                     if($from1 >$to1){
                        $this->form->setError($to,'* Please provide valid to time');
                    }else if(!$this->check_time_availability($timmings,$from1,$to1)){
                    //   echo  $this->check_time_availability($timmings,$from1,$to1); 
                        $this->form->setError($to,'* Invalid time schedule');
                    }else{
                        $arr['start']=$from1;
                        $arr['end']=$to1;
                        $timmings[]=$arr;
                    }
                    
                }
            }
        }
        
        
        
        $days="";
        for($i=1;$i<=7;$i++){
            $field='week_day_'.$i;
            if(array_key_exists($field,$post)){
                $days.=$i.",";
            }
        }
        if(strlen($days)==0){
            $this->form->setError('days','* please Select days');
        }else{
            $days=  substr($days, 0, strlen($days)-1);
        }
        
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
              
                redirect('timetable/create_step2/'.$post['stdsection'].'/'.$post['periods'], 'refresh'); 
        }else{ 
            
            $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'section' => $post['stdsection'],
                        'start' => 0,
                        'end' =>0,
                        'classes' => $post['periods'],
                        'span' =>0
                       );
            
            
            $this->db->insert('timings',$data); 
            
            $tid=$this->db->insert_id();
            $days=explode(',',$days);
            $this->logs->insert_staff_log(25,'Created Time table For '.$this->get_section_name($post['stdsection']), $tid );
            $start ="";
            $end="";
            $i=1;
            foreach($timmings as $value){
                if($i==1){
                  $start  =$value['start'];
                }
                $end=$value['end'];
            }
            $data = array(
                        'start' => $start,
                        'end' =>$end,
                       );
            $this->db->where('tid', $tid);
            $this->db->update('timings', $data);
            foreach($days as $day){
                
                foreach($timmings as $value){
                   $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'tid' => $tid,
                        'sec_id' => $post['stdsection'],
                        'course_id' => 0,
                        'time_start' => $value['start'],
                        'time_end' =>$value['end'],
                        'day' => $day,
                        );
               
                  $this->db->insert('class_routine',$data); 
                }
                
            }
            $this->logs->insert_staff_log(25,'Created Class Routine :'.$this->get_section_name($post['stdsection']), $tid );
          
         redirect('timetable/edit/'.$tid, 'refresh'); 
            
        }
        
         
        
    }
    
    private function send_time($t){
        $t= explode(" ",$t);
        if( strtolower($t[1])=='pm'){
            $t=$t['0'];
            $t=explode(":",$t);
            if($t['0']!=12){
                $t['0']=$t['0']+12;
                if($t['0']>=24){
                    $t['0']=$t['0']-24;
                }
            }
            return $t['0']."".$t['1'];
        }else{
            return implode("",  explode(":", $t['0']));
        }
    }
    
    public function modify_timings(){
       
        $schedule=  json_decode($this->input->post('schedule'));
        $changed =array();
        $period=$this->input->post('period');
        $action=$this->input->post('action');
        $span=$this->input->post('span');
        
        if($action=='add'){
            if($period == (sizeof($schedule)-1) ){
                echo "<br/>Sorry it is  Last Period";exit;
            }else{
               
                for($i=$period+1;$i<=(sizeof($schedule)-1);$i++){
                   $start=$schedule[$i]->start;
                   $new_start =implode('',explode(":",date('H:i',mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2)) + ($span *60))) );
                   $start=$schedule[$i]->ending;
                   $new_end =implode('',explode(":",date('H:i',mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2)) + ($span *60) )) );
                   $changed[] = array('start'=>$schedule[$i]->start ,'ending' =>$schedule[$i]->ending ,'new_start'=>$new_start , 'new_ending' =>$new_end  ); 
                }
               
            }
        }else{
            if($period == (sizeof($schedule)-1) ){
                 echo "<br/>Sorry it is  Last Period";exit;
            }else{
                $start=$schedule[$period+1]->period;
                if($start == 'Break'){
                   for($i=$period+2;$i<=(sizeof($schedule)-1);$i++){
                       $start=$schedule[$i]->start;
                       $new_start =implode('',explode(":",date('H:i',mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2)) - ($span *60))) );
                       $start=$schedule[$i]->ending;
                       $new_end =implode('',explode(":",date('H:i',mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2)) - ($span *60) )) );
                       $changed[] = array('start'=>$schedule[$i]->start ,'ending' =>$schedule[$i]->ending ,'new_start'=>$new_start , 'new_ending' =>$new_end  ); 
                    }
                    
                }else{
                    echo "<br/>No Interval Found ";exit;
                }
            }
        }
        
        foreach($changed as $value){
         
            $this->db->query("UPDATE class_routine SET   time_start = '".$value['new_start']."' , time_end = '".$value['new_ending']."'  WHERE tid ='".$this->input->post('tid')."' AND  time_start ='".$value['start']."' AND time_end = '".$value['ending']."' " );
        }
        ?>
        <script>
            location.reload();
        </script>
       <?php
        
       
    }
    
    private function check_section($section){
        $credential = array('section' =>$section);
        $query = $this->db->get_where('timings', $credential); 
        if ($query->num_rows() > 0) {
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function edit_timings($tid=0){
       if($tid!=0){
           $t=$this->fetch_details($tid);
           
           if(!$t){
                redirect("timetable/view",'refresh');
           }
           
           $data['timetable']=  $t;
           $this->load->view('timetable/edit_timings',$data);
       }else{
           $this->load->view('timetable/view');
       }
    }
    
    private function fetch_details($tid){
        $query=  $this->db->query("SELECT t.tid,s.sid,s.name as section,c.name as class,c.medium ,t.start,t.end,t.classes,t.span FROM `timings` t JOIN section s ON t.section=s.sid JOIN class c ON s.class_id=c.id  WHERE t.tid='".$tid."' ");
        if($query->num_rows()>0){
            $query=$query->row();
           return $query;
        }else{
            return FALSE;
        }
        
    }
    
    public function add_period_timings(){
         //periods
        $periods =$this->input->post("periods");
        
         for($i=1;$i<=$periods;$i++){
                   ?>
                <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Section _ <?php echo $i; ?></label>
                        <div class="col-sm-10">
                            
                                <div class="col-sm-5">
                                    <select class="select2-me">
                                         <?php
                                            for($i=0;$i<24;$i++){
                                               ?>
                                        <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                               <?php
                                            }
                                         ?>
                                    </select>
                                    <select class="select2-me">
                                         <?php
                                            for($i=0;$i<60;$i++){
                                               ?>
                                        <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                               <?php
                                            }
                                         ?>
                                    </select>
                                </div>
                                 
                            <div class="col-sm-2">
                                
                            </div>
                                
                                <div class="col-sm-5">
                                    <select class="select2-me">
                                         <?php
                                            for($i=0;$i<24;$i++){
                                               ?>
                                        <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                               <?php
                                            }
                                         ?>
                                    </select>
                                    <select class="select2-me">
                                         <?php
                                            for($i=0;$i<60;$i++){
                                               ?>
                                        <option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                                               <?php
                                            }
                                         ?>
                                    </select>
                                </div>
                            
                            
                        </div>
                </div>
                  <?php
               }
        
        
    }
    
    private function get_section_name($sid){
        $query=  $this->db->query("SELECT group_concat(s.name,'-',c.name) as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.sid='".$sid."' ");
        $query=$query->row();
    
        return $query->class;
    }
    
    private function get_section_name1($sid){
        $query=  $this->db->query("SELECT c.name as cls_name , s.name as section, sid FROM section s JOIN class c ON s.class_id=c.id WHERE s.sid='".$sid."' ");
        $query=$query->row();
    
        return $query;
    }
    
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
    public function fetch_timetable(){
         $section = $this->input->post("section");
         $section = $this->get_section_name1($section);
         $t ="SELECT * FROM `timings` where  section ='".$section->sid."'";
         $t=  $this->db->query($t);
         if($t->num_rows()>0){
             $t=$t->row();
         }else{
             ?>
                <div class="box-title">
                        <h3>View Timetable of <?php echo $section->cls_name ." , ".$section->section  ?></h3> 
                </div>
                <div  style=" text-align: center" class="box-content nopadding">
                    <br/>
                    <strong>TIMETABLE NOT YET CONFIGURED</strong>
                    <br/>
                    
                </div>
             <?php
             
         }
         
    }
    
    public function check_time_availability($timmings , $from ,$to){
        $from =$from+1;
        $to= $to-1;
        
        foreach($timmings as $value){
          

           if(  (($from >$value['start']) &&  ($from <$value['end']) ) ){
               echo $from."beyond limit" ;
               return false;
           }
           
           if(  (($to >$value['start']) &&  ($to <$value['end']) ) ){
               echo $to."beyond limit" ;
               return false;
           }
           
           
        }
        return true;
        
        
    }
    
    
    
}