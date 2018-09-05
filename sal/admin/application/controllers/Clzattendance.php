<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clzattendance extends CI_Controller {

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
        }
    
    public function index(){
        $this->load->view('attendance/clz/index');
    }
    
    public function view(){
        $this->load->view('attendance/clz/view');
    }
    
    public function view_attendance($section=0){
           if($section==0){
              redirect("Clzattendance/view",'refresh'); 
           }else{
              $section= $data['section']=  $this->fetch_section_details($section);
              $att_settings =$this->db->query("SELECT * FROM `attendance_settings` where section ='".$section->sid."' ");
              if($att_settings->num_rows()==0){
                  redirect("Clzattendance/view",'refresh'); 
              }
              $att_settings=$data['att_settings'] =$att_settings->row();
                   $this->load->view('attendance/clz/view_attendance',$data);
           }
    }
    
    public function attendance($section=0,$id){
          if( ($section==0) || ($id==0) ){
            $this->load->view('home');
          }elseif(!$this->check_att_date($id, $section)){
             $this->load->view('home'); 
          }else{
              $data['section']=  $this->fetch_section_details($section);
              $data['data']=  $this->get_attendance_date($id);
              $data['attendance']=  $this->get_attendance_details($id);
              $this->load->view('attendance/clz/enter_attendance',$data); 
          }            
    }
        
    public function add_attendance(){
        $this->load->view('attendance/clz/add_attendance');
    }
    
    public function load_history(){
//        echo "<pre>";
//        print_r($_POST);
//        $settings =$this->db->query("SELECT * FROM attendance_config c join attendance_settings s ON c.asid=s.aid WHERE s.section='".$this->input->post("section")."' ");
//        $settings =$settings->result();
//        $settings_array()=array();
//        foreach ($settings as $value) {
//           $settings_array[$value->acid] = array("time" =>$value->time);
//        }
//        $day = getdate();
//        $from = mktime(0, 0,0, $day['mon'],1,$day['year']);
//        $to = mktime(0, 0,0, $day['mon']+1,1,$day['year']);
//        $q=$this->db->query("SELECT day,slot , (select count(*) from attendance WHERE date_id=id  ) as total from attendance_date WHERE day >='".$from."' AND day <'".$to."' ORDER BY day ASC ")->result();
//        $attendance_slots =array();
//        foreach ($q as $value){
//          //   $attendance_slots[$value->day][$value->slot]=
//        }                         
    }
    
    public function  submit_attendance(){
        $pre_data = json_decode($this->input->post("pre_data")); 
        //print_r($pre_data);
        $pre_attendance=array();
        $student_data=json_decode($this->input->post("student_data")); 
        $slots=  array_filter(explode(",",  $this->input->post("slots")));
        foreach($slots as $value){
            $pre_attendance[$value]=array("old_id"=>"");
        }
        foreach ($pre_data as $value) {
           $pre_attendance[$value->slot]["old_id"]=$value->id;
            $ids=$value->ids;
            $stds=$value->students;
            $rolls =$value->rolls;
             if(strlen($ids)!=0){
                 $ids=explode(",",$ids);
                 $stds=explode(",",$stds);
                 $rolls=explode(",",$rolls);
                 for($i=0;$i<sizeof($ids);$i++){
                    $pre_attendance[$value->slot]['attendance'][$rolls[$i]] = array('id'=>$ids[$i],'roll'=>$stds[$i]);
                 }
             } 
        }
        
        $std_array=array();
        foreach($student_data as $value){
            $std_array[$value->roll] =array("id"=>$value->student_id);
        }
       //$new_Att_arr= array();
        foreach($slots as $value){
            $rolls = $this->input->post("absenties_".$value);
            $rolls = array_filter(explode(",",$rolls));
            foreach($rolls as $r){
                if(isset($std_array[$r])){
                    if(!isset($pre_attendance[$value]['attendance'][$r])){
                             $pre_attendance[$value]['attendance'][$r] = array('std_id'=>$std_array[$r]["id"]);
                    }
                }else{
                    $err =$this->form->error("absenties_".$value);
                    $this->form->setError("absenties_".$value,$err.$r." ,");
                }
            }
        }
        
        
       //print_r($pre_attendance);exit;
        
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('Clzattendance/add_attendance?section='.$this->input->post("section").'&date='.$this->input->post("date").'&slots='.$this->input->post("slots"), 'refresh');  
        }else{
           $as_id="select * from attendance_settings where section= ".$this->input->post("section")." ";
           $as_id = $this->db->query($as_id)->row();
           
           $as_id=$as_id->aid;
           $date=explode("/",$this->input->post("date"));
           $date=mktime(0,0,0,$date[1],$date[0],$date[2]);
    
           foreach($slots as $value){
                $rolls = $this->input->post("absenties_".$value);
                $rolls = array_filter(explode(",",$rolls));
                $ad_id="";
                if(strlen($pre_attendance[$value]["old_id"])!=0){
                  $ad_id =$pre_attendance[$value]["old_id"];   
                }else{
                     $data= array(
                         'iid' => $this->session->userdata('staff_Org_id'),
                         'section' =>$this->input->post("section"),
                         'day' =>$date,
                         'slot' =>$value,
                     ); 
                    
                     $this->db->insert("attendance_date",$data);
                      $ad_id=$this->db->insert_id();

                }
               // print_r($pre_attendance[$value]['attendance']);
                
                foreach($rolls as $r){
                    
                    if(isset($pre_attendance[$value]['attendance'][$r]['id'])){
                         $data=array("status"=>0);
                         $this->db->where('id', $pre_attendance[$value]['attendance'][$r]['id']);
                         $this->db->update("attendance",$data);
                    }else{
                        //print_r($pre_attendance[$value]['attendance'][$r]);
                        $data=array(
                            'iid' =>$this->session->userdata('staff_Org_id'),
                            'date_id' =>$ad_id,
                            'acid' =>$value,
                            'student' =>$pre_attendance[$value]['attendance'][$r]['std_id'],
                            'status' =>0,
                            );
                       // print_r($data);
                         $this->db->insert('attendance',$data);
                    }
                    unset($pre_attendance[$value]['attendance'][$r]);
                    
                 }
                 
                 foreach ($pre_attendance[$value]['attendance'] as $del) {
                     
                     $this->db->where('id', $del['id']);
                    $this->db->delete("attendance");
                 }
               $this->session->set_userdata('attendance_add_Sucesss', 'Attendancce Updated Sucessfully');   
            
           }
           
        }
        redirect("Clzattendance/add_attendance","refresh");
        
    }

    public function add(){
        $date =$this->input->post("date");
        $section =$this->input->post("section");
        $slots=$this->input->post("slots");
        $slots = array_filter(explode(",",$slots));
        $date = explode("/",$date);   $err=0;
        if(sizeof($slots)==0){
            $err++;
            ?>
            <script>$('#slot_error').html("** Invalid Slots");</script>
                <?php
        }
        if(sizeof($date)!=3){
            $err++;
            ?>
                <script>$('#date_err').html("** Invalid Date");</script>
                    <?php
        }else{
           $d_time= mktime(0,0,0, $date[1], $date[0], $date[2]);
           if($d_time>time()){
               $err++;
               ?>
                <script>$('#date_err').html("** Invalid Date");</script>
               <?php
           }
        }
        
        if(!is_numeric($section)){
             $err++;
                 ?>
                <script>$('#section_err').html("** Select Section");</script>
               <?php
          
        }
        
        if($err==0){
            ?>
                <script>
                    window.location.href="<?php echo base_url() ?>index.php/Clzattendance/add_attendance?section=<?php echo $section ?>&date=<?php echo $this->input->post("date"); ?>&slots=<?php echo implode(",",$slots) ?>";
                </script>
               <?php
        }
        
        
        
    }
    
    
    public function reports(){
      $this->load->view('attendance/clz/reports');
    }
    
    public function load_slots(){
        $section =$this->input->post("section");
        $query=$this->db->query("SELECT * FROM attendance_config c JOIN attendance_settings s  ON c.asid=s.aid where s.section='".$section."' ");
        $query =$query->result();
        $slot_ids="";
        foreach ($query as $value) {
            ?>
                <input type="checkbox" id="slot_<?php echo $value->acid ?>" name="slot_<?php echo $value->acid ?>" value="<?php echo $value->acid ?>" />&nbsp;<?php echo substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2) ?>
            <?php
            $slot_ids.=$value->acid.",";
        }
        ?><span id="slot_ids" style=" display: none"><?php  echo  $slot_ids =  substr($slot_ids,0, strlen($slot_ids)-1); ?></span><?php
     
    }
    
    public function configure($asid=0){
        if($asid==0){
            $this->load->view('attendance/clz/view');
        }else{
            $con=  $this->get_details($asid);
            if(!$con){
                $this->load->view('attendance/clz/view');
            }else{
                $data['settings']=$con;
                $data['configure']=  $this->get_configure($asid);
                $this->load->view('attendance/clz/confgure',$data);
            }
            
            
        }
    }
    
    public function create(){
        $post=$_POST;
       
        $field="noof_times";
        if(strlen($post[$field]) ==  0)
        {
          echo "** Enter No Of Slots";exit;
        }elseif(!is_numeric(trim($post[$field]))){
           echo '* Enter Numeric Value';exit;
        }
        
        $data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                    'section' => $post['section'],
                    'no_of_times' =>$post['noof_times'],
                    );//
        $this->db->insert('attendance_settings',$data); 
        $tid=$this->db->insert_id();

        for($i=1;$i<=$post['noof_times'];$i++){
            $data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                    'asid' => $tid,
                    'time' =>0,
                    );//
            $this->db->insert('attendance_config',$data);  
        }
        ?>
           <script>
           window.location.href="<?php echo base_url() ?>index.php/Clzattendance/configure/<?php echo $tid ?>?action=edit";
           </script>
        <?php
    }
    
    public function save_config(){
        $post=$_POST;
        $ids=explode(',',$post['ids']);
        foreach ($ids as $id) {
           $field='timing_'.$id;
            if(strlen($post[$field]) ==  0)
            {
              $this->form->setError($field,'* Please Select timings');
            }
        }
        
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
              redirect('/Clzattendance/configure/'.$post['asid']."?action=edit", 'refresh'); 
        }else{ 
            foreach ($ids as $id) {
                
                $data = array(
                        'time' => implode(explode(":",$post['timing_'.$id]))
                        );
                
                $this->db->where('acid', $id);
                $this->db->update('attendance_config', $data); 
                
            }
            $this->session->set_userdata('attendance_config', 'Sucessfully Updated Attendance Settings');   
            
            
        }
         redirect('/Clzattendance/configure/'.$post['asid'], 'refresh'); 
    }
    
    private function get_configure($asid){
        $query=  $this->db->query("SELECT * FROM `attendance_config` WHERE `asid`='".$asid."'");
        if($query->num_rows()>0){
            return $query->result();
        }else{
            return FALSE;
        }
    }
    
    private function get_details($asid){
        $query=  $this->db->query("SELECT a.aid,a.no_of_times,s.sid,s.name as section ,c.name as class FROM `attendance_settings` a JOIN section s ON a.section=s.sid JOIN class c ON s.class_id=c.id WHERE a.aid='".$asid."' AND a.iid='".$this->session->userdata('staff_Org_id')."'");
        if($query->num_rows()>0){
            return $query->row();
        }else{
            return FALSE;
        }
    }
    
    private function fetch_section_details($section){
          $query=  $this->db->query("SELECT s.sid,s.name as section,c.name as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.sid='".$section."'");
          $query=$query->row();
          return $query;
        }
    
    private function check_section($section){
      $query=  $this->db->query("SELECT * FROM  `attendance_settings` WHERE  section='".$section."' AND iid='".$this->session->userdata('staff_Org_id')."' ");
       if ($query->num_rows() > 0) {
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
        
    private function get_attendance_details($aid){
           $query=  $this->db->query("SELECT a.id,a.status,a.acid,s.name,s.roll FROM `attendance` a JOIN student s ON a.student=s.student_id WHERE `date_id`='".$aid."'");
           $query=$query->result();
           return $query;
        }
        
    private function get_attendance_date($aid){
       $query=  $this->db->query("SELECT * FROM `attendance_date` WHERE id='".$aid."'");
       $query=$query->row();
       return $query; 
    }
    
    public function student_brief_print($student=""){
        if(strlen($student)==0){
            redirect("attendance/view","refresh");exit;
        }
                   $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
                   if($student->num_rows()==0){
                       redirect("clzattendance/reports","refresh");
                   }else{
                       $institute =  $this->fetch_institute_details();
                       $student = $student->row();
                       $std_image="dummy_user.png";
                      if(!(strlen($student->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$student->photo)){
                           $std_image =$student->photo ;
                         }            
                      }
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
<hr color="#00306C" />

<div style="border:2px solid #00306C;height:160px;">

<div style="float:left;width:50%">
	<img src="'.assets_path .'/uploads/'.$std_image.'" width="160" height="120" style="padding:10px;margin:10px;" />
</div>
<div class="verticalLine" style="float:left;height:138px;">&nbsp;</div>
<div  style="float:left;padding:3px;">
<table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$student->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$student->cls_name." - ".$student->sec_name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$student->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>Roll No</td>
                                        <td>:</td>
                                        <th>'.$student->roll.'</th>
                                    </tr>

                                    </table>
                                    
</div>
</div>

<br style="clear:both" />';
                    $att_set =$this->db->query( "select * from attendance_settings where section ='".$student->section_id."' ");
                if($att_set->num_rows()==0){
                           redirect("clzattendance/reports","refresh");
                }
                        $att_set =$att_set->row();

                        $att_config=$this->db->query("SELECT * from attendance_config where asid='".$att_set->aid."' ")->result();
                        $att_config_arr = array();
                        $att_ids="";
                        foreach ($att_config as $value) {
                            $att_ids.=$value->acid.",";
                         $att_config_arr[$value->acid]=$value->time;   
                        }
                        $att_ids = substr($att_ids,0,strlen($att_ids)-1);
                        $att=$this->db->query("SELECT d.* , (select count(*)  from attendance a where student='".$student->student_id."' AND a.date_id=d.id  ) as att_status FROM attendance_date d  where  slot IN (".$att_ids.")")->result(); 
                        $att_array=array();
                        $month_att_arr=array();
                        foreach ($att as $value) {
                            $day=  getdate($value->day);
                            $day =$day['month']."-".$day['year'];
                            if(!isset($month_att_arr[$day])){
                                   $month_att_arr[$day]=array("total"=>0,"present"=>0);
                             }
                             $month_att_arr[$day]['total']++;
                             if($value->att_status!=0){
                                $month_att_arr[$day]['present']++; 
                             }

                            $att_array[$day][$value->day][$value->slot]=$value->att_status;
                        }
                        $grph_names ="";
                        foreach($month_att_arr as $key=>$value){
                            $per =($value["present"]/$value["total"])*100;
                            $per =  number_format($per,2);
                            $grph_names.= "['".$key."',".$per."] ,";
                        }
                       $grph_names =  substr($grph_names, 0, strlen($grph_names)-1);
                       ?>
           <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		
                       <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '<br/><hr/><?php echo $student->name  ?> Attendance Report '
                                            },
                                            subtitle: {
                                                text: ''
                                            },
                                            xAxis: {
                                                type: 'category',
                                                labels: {
                                                    rotation: -45,
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: {
                                                    text: 'Attendance percentage'
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                pointFormat: 'Attendance in % : '
                                            },
                                            series: [{
                                                name: 'Attendance % :',
                                                data: [<?php echo $grph_names ?>
                                                ],
                                                dataLabels: {
                                                    enabled: true,
                                                    rotation: -90,
                                                    color: '#FFFFFF',
                                                    align: 'right',
                                                    format: '{point.y:.1f}', // one decimal
                                                    y: 10, // 10 pixels down from the top
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            }]
                                        });
                                    });
                                   </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <br/><br/><hr/>
                <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto">
                    
                </div>
                <div class="box" >           
                    <?php
                    $i=1;
                    foreach ($month_att_arr as $key=>$value) {
                           ?><hr/>
                          <div class="tab-pane "
                              >
                              <h3 style=" text-align: center"><?php 
                                echo "Attendance Report Of ".$key ;
                                ?></h3><br/>
                                <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th style=" text-align: center ">Date</th>
                                          <?php
                                              foreach ($att_config_arr as $tim) {
                                                  ?>
                                                <th style=" text-align: center "><?php   echo substr($tim,0,strlen($tim)-2).":".substr($tim,strlen($tim)-2) ?></th>
                                                  <?php 
                                               }
                                          ?>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                foreach ($att_array[$key] as $d=>$var) {
                                    ?>
                                      <tr>
                                          <th style=" text-align: center "><?php echo date("d-m-Y",$d) ?></th>
                                              <?php
                                              foreach ($att_config_arr as  $c=>$tim) {
                                                  ?>
                                                <td><?php 
                                                   if(isset($var[$c])){
                                                       if($var[$c]==0){
                                                           ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                          }else{
                                                              ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                          }
                                                   }else{
                                                       echo "--";
                                                   }  
                                                        ?></td>
                                                  <?php 
                                               }
                                          ?>
                                      </tr>
                                     <?php
                                }
                                      ?>
                                  </tbody>
                              </table><br/>
                              <h4 style=" text-align: center; color : #ff0000  ">Total Slots :<?php echo $value["total"] ?>&nbsp;Present Slots :<?php echo $value["present"] ?>&nbsp;Attendance Perentage :<?php echo number_format( ($value["present"]/$value["total"])*100,2 ) ?></h4>

                          </div><br/>
      <?php
      $i++;
                    }
                    ?>      
                    </div>
                        </div>
                
                    </div>
                    <script>
                            window.print();
                        
                    </script>
                </body>
            </html>
                
                <?php
                   }
    }
    
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
}
?>

