<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class attendance extends CI_Controller {

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
        $this->load->view('attendance/index');
    }
    
    public function view(){
        $this->load->view('attendance/view');
    }
    
    public function view_attendance($section=0){
           if($section==0){
               $this->load->view('home'); 
           }else{
               $data['section']=  $this->fetch_section_details($section);
               $this->load->view('attendance/view_attendance',$data); 
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
              $this->load->view('attendance/enter_attendance',$data); 
          }            
    }
        
    public function add(){
        $this->load->view('attendance/new');
    }
    
    public function configure($asid=0){
        if($asid==0){
            $this->load->view('attendance/view');
        }else{
            $con=  $this->get_details($asid);
            if(!$con){
                $this->load->view('attendance/view');
            }else{
                $data['settings']=$con;
                $data['configure']=  $this->get_configure($asid);
                $this->load->view('attendance/confgure',$data);
            }
            
            
        }
    }
    
    public function create(){
        $post=$this->operations->cleanInput($_POST);
        $field="section";
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide Section');
        }elseif(!$this->check_section($post[$field])){
            $this->form->setError($field,'* Already Assigned for this Section');
        }
        
        $field="noof_times";
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide No of times');
        }elseif(!is_numeric(trim($post[$field]))){
            $this->form->setError($field,'* Enter Numeric Value');
        }
        
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('/attendance/add', 'refresh'); 
        }else{ 
            $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'section' => $post['section'],
                        'no_of_times' =>$post['noof_times'],
                        );
            $this->db->insert('attendance_settings',$data); 
              $tid=$this->db->insert_id();
            $this->logs->insert_staff_log(9,'Created Attendance Settings',$tid);
           
            for($i=1;$i<=$post['noof_times'];$i++){
                $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'asid' => $tid,
                        'time' =>0,
                        );
                $this->db->insert('attendance_config',$data);  
                $this->logs->insert_staff_log(9,'Configuring Attendance Settings',$tid);
            }
            redirect('/attendance/configure/'.$tid, 'refresh'); 
        }
        
    }
    
    public function save_config(){
        $post=$this->operations->cleanInput($_POST);
        
              
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
         redirect('/attendance/configure/'.$post['asid'], 'refresh'); 
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
    public function load_students(){
            $section=  $this->input->post("section");
            $query = " select * from student where iid='".$this->session->userdata('staff_Org_id')."' AND section_id = '".$section."' ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Student</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->student_id ?>" >
                    <?php echo $val->userid."-".$val->name ?>
                </option>
             <?php
            }

        }
    
    public function add_attendance(){
         $this->load->view('attendance/daily_attendance');
    }
    
    public function pavan(){
       $query=  $this->db->query("SELECT * FROM  section ");
       $query =$query->result();
       echo "<pre>";
       foreach ($query as $value) {
           $data = array(
            'iid' => $value->iid,
            'section' => $value->sid,
            'no_of_times' =>2,
            );
          $this->db->insert('attendance_settings',$data); 
           $tid=$this->db->insert_id();
           
           
            for($i=1;$i<=2;$i++){
                $data = array(
                        'iid' => $value->iid,
                        'asid' => $tid,
                        'time' =>0,
                        );
                $this->db->insert('attendance_config',$data);  
                 }
       }
       
    }
    
    public function check_date_slot(){
       
       $date=$this->input->post("date");
       $slot=$this->input->post("slot");
       
       $date = explode("/",$date);
       if(sizeof($date)!=3){
           echo "please select valid date";exit;
       }else{
           $day= mktime(0, 0, 0, $date[1], $date[0], $date[2]);
           if($day <= time()){
              ?>
<Script>
     window.location.replace("<?php echo base_url() ?>index.php/attendance/add_attendance?date=<?php echo $this->input->post("date") ?>&slot=<?php echo $this->input->post("slot") ?>#");
</script>
              
              <?php
           }else{
               echo "Invalid Date";exit;
           }
           
       }
    }
    
    public function reassign_rollnos(){
       $roll = $this->input->post("roll");
       $absent = $this->input->post("absent");
       
       if(strlen($roll)!=0){
           $absent= explode(',',$absent);
           $absent= array_filter($absent);
           $roll =explode(',',$roll);
           $roll= array_filter($roll);
           $absent= array_filter(array_diff($absent, $roll));
       }else{
           $absent= explode(',',$absent);
           $absent= array_filter($absent);
       }
        $absent = implode(",",$absent);       
       ?>
         <script>
         //section
         $('#absenties_<?php echo $this->input->post('section'); ?>').val('<?php echo $absent ?>');
         </script>
       <?php
    }
    
    public function add_grp_attendance(){
   
       $post=$this->operations->cleanInput($_POST);
       if(strlen($this->input->post("send_sms"))>0){
           if(strlen($post['alert_message'])==0){
              $this->form->setError("alert_message","* Please enter message content ");  
           }
       }
       
        $day_att_id = json_decode($this->input->post("day_att_id"));
        $section_dat_info=json_decode($this->input->post("section_dat_info"));
        $sec_data=array();
        foreach ($section_dat_info as $key => $value) {
           $sec_data[$key]['cls_name']=$value->cls_name; 
           $sec_data[$key]['section']=$value->section; 
        }
        foreach ($day_att_id as $key => $value) {
           $absenties_list=  array_filter(explode(",",$this->input->post("absenties_".$key)));
           $student_roll_list = explode(",",$this->input->post("student_roll_list_".$key));
         
           if(sizeof(array_diff($absenties_list,$student_roll_list))>0){
               $this->form->setError("absenties_".$key,"* ". $sec_data[$key]['cls_name']."".$sec_data[$key]['section']."  Invalid Roll No's:  ".implode(",",array_diff($absenties_list,$student_roll_list)));
            }
        }
     
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $post;
            $_SESSION['error_array'] = $this->form->getErrorArray();
           
           redirect('/attendance/add_attendance?date='.$this->input->post('date').'&slot='.$this->input->post('slot')."#", 'refresh'); 
        }else{ 
            $date=$this->input->post("date");
            $slot=$this->input->post("slot");
            
            $date = explode("/",$date);
            $day= mktime(0, 0, 0, $date[1], $date[0], $date[2]);
            $send_sms=0;
            if(strlen($this->input->post("send_sms"))>0){
                $send_sms=1;$time=time();
                $sms_details = $this->fetch_sms_details();
                    $data1=array(
                                'iid' =>$this->session->userdata('staff_Org_id'),
                                'staff_id' =>$this->session->userdata('staff_id'),
                                'regarding'=>3,
                                'message' =>"Attendance Alerts",
                                'time' =>$time
                               );
                    $this->db->insert('alerts',$data1);
                    $aid=$this->db->insert_id();
                    $this->logs->insert_staff_log(4,'Sending Alert',$aid);
            }
            
            foreach ($day_att_id as $key => $value) {
               
                $absenties_list=  array_filter(explode(",",$this->input->post("absenties_".$key)));
                $Absent_prev_list =json_decode($this->input->post("Absent_prev_list_".$key));
                $student_roll_list = $this->input->post("student_roll_list_".$key);
                $student_list =  json_decode($this->input->post("student_list_".$key));
                $stud_array=array();
                $prev_absent_list ="";
                foreach($student_list as $st){
                   $stud_array[$st->roll] = array('roll'=>$st->roll,'name'=>$st->name,'student_id'=>$st->student_id,"phone"=>$st->phone);
                }
                $prev_Att_list=array();
                foreach($Absent_prev_list as $st){
                   $prev_Att_list[$st->att_id] = array('att_id'=>$st->att_id,'roll'=>$st->roll);
                   $prev_absent_list = $prev_absent_list.$st->roll.",";
                }
                $prev_absent_list = substr($prev_absent_list,0,strlen($prev_absent_list)-1);
                $prev_absent_list = array_filter(explode(",",$prev_absent_list));
                if( sizeof(array_diff($prev_absent_list, $absenties_list)) >0 ){
                    $ch=array_diff($prev_absent_list, $absenties_list);
                    $ch_stud="";
                    foreach($ch as $ch_val){
                        $s = $stud_array[$ch_val];
                       $ch_stud =$ch_stud.$s['student_id'].",";
                    }
                    $ch_stud = substr($ch_stud,0,strlen($ch_stud)-1);
                    $this->delete_attendance_records($ch_stud,$value->ad_id,$slot);
                }else{
                    $prev_ab="";
                    foreach ($Absent_prev_list as $ab_value) {
                      
                        $prev_ab =$prev_ab.$ab_value->roll.",";
                     }
                    
                    
                    $prev_ab = substr($prev_ab,0,strlen($prev_ab)-1);
                    $prev_ab = explode(",",$prev_ab);
                    $new_ab = array_diff($absenties_list,$prev_ab);
                    
                     foreach($new_ab as $n){
                      
                                    
                         $data=array(
                            'iid' =>$this->session->userdata('staff_Org_id'),
                            'date_id' =>$value->ad_id,
                            'acid' =>$slot,
                            'student' =>$stud_array[$n]['student_id'],
                            'status' =>0,
                            );

                         $this->db->insert('attendance',$data);
                         
                         if($send_sms==1){
                               $data=array(
                                            'iid' =>$this->session->userdata('staff_Org_id'),
                                            'username'=>$sms_details->username,
                                            'password' =>$sms_details->password,
                                            'senderid' =>$sms_details->senderid,
                                            'message'  =>$post['alert_message'],
                                            'mobile' =>$stud_array[$n]['phone'],
                                            'time' => $time,
                                            'status' =>0,
                                            'alert_id' =>$aid,
                                        );
                              $this->db->insert('msg_senthistory',$data);  
                         }
                     }
                   
                   
                }
             }  
             $this->session->set_userdata('Attendance_add_sucess', 'Sucessfully Added Attendance'); 
           redirect('/attendance/add_attendance', 'refresh'); 
        }
        
        
    }
    
    private function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        return $msg;
    }
    
    public function attendance_report($section=""){
        $section =trim($section);
        if(strlen($section)==0){
            $this->load->view('attendance/attendance_report');
        }else{
            $section =  $this->check_section1($section);
            if(!$section){
              $this->load->view('attendance/attendance_report');
           }else{
              $data['section_data']=$section;
             // print_r($data);
              $this->load->view('attendance/section_attendance_report',$data);
           }
        }
       
    }
    
    private function check_section1($section){
            $query=  $this->db->query("SELECT s.sid,s.name as section,c.id as class_id,c.name as class FROM `section` s  JOIN class c ON s.class_id=c.id WHERE sid='".$section."'");
            if($query->num_rows()==0){
                return FALSE;
            }else{
               $query=$query->row(); 
               return $query;
            }
            
            
        }
    
    public function load_att_report(){
              
              $att_month =  $this->input->post("att_month");
              $att_year =  $this->input->post("att_year");
              $section =  $this->input->post("section");
//              $att_month =10;
//              $att_year =2016;
//              $section =1;
              $attendance_array = array();
              $q="SELECT a.student, ad.day, ad.slot FROM `attendance` a JOIN attendance_date ad ON a.date_id = ad.id  where ad.section = '".$section."' AND ( ad.day >='".mktime(0, 0, 0, $att_month, 1, $att_year)."' AND ad.day <'".mktime(0, 0, 0, ($att_month+1), 1, $att_year)."' ) ORDER BY ad.day ASC";
              $q=  $this->db->query($q);
              $q = $q->result();
              foreach($q as $value){
                  $attendance_array[$value->day][$value->slot][$value->student] = $value->student;
              }
              
              $stud = $this->db->query("SELECT * FROM student WHERE section_id = '".$section."' ");
              $stud = $stud->result();
              $from =mktime(0, 0, 0, $att_month, 1, $att_year);
              $to =mktime(0, 0, 0, $att_month +1 , 1, $att_year);
              ?>
               

                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Attendance Report</h3> 
                    </div>

                    <div class="box-content nopadding"> 
                         <div class='form-horizontal form-bordered' style=" overflow: auto; width:100% "   >
                             <table class="table table-hover table-nomargin table-bordered">
                                 <thead>
                                     <tr>
                                         <th>Students</th>
                                         <?php
                                         $this_day=$from;
                                           while($this_day<$to){
                                               ?>
                                                <th colspan="2">
                                                    <table>
                                                        <tr>
                                                            <th colspan="2" style=" text-align: center"><?php echo date("d-m-y",$this_day); ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Slot-1</th>
                                                            <th>Slot-2</th>
                                                        </tr>
                                                    </table>
                                                </th>
                                               <?php
                                               $this_day =$this_day+86400;
                                           }
                                         ?>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php
                                       foreach($stud as $value ){
                                           ?>
                                            <tr>
                                                <td><?php echo $value->name ?></td>
                                                <?php
                                                      $this_day=$from;
                                                        while($this_day<$to){
                                                          ?>
                                                            <td><?php
                                                              if(isset($attendance_array[$this_day][1][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td> 
                                                            <td><?php
                                                              if(isset($attendance_array[$this_day][2][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td>        
                                                          <?php
                                                          $this_day =$this_day+86400;
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
    
        
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
        
    public function fetch_monthly_attendance(){
        ?>
            	<?php
            $cls_name= $this->input->post("cls_name");
            $from = mktime(0, 0, 0, $this->input->post("att_month"), 1, $this->input->post("att_year"));
            $to = mktime(0, 0, 0, $this->input->post("att_month")+1, 1, $this->input->post("att_year"));
            $students=0;
            if($cls_name==0){
                $q="SELECT d.day ,(SELECT count(distinct(student)) from attendance where date_id in (group_concat(d.id))) as count FROM `attendance_date` d where  ( (d.day >='".$from."') AND (d.day <'".$to."')  )  GROUP BY d.day ORDER BY d.day";  
                $students =$this->db->query("SELECT count(*) as total from student where iid= '".$this->session->userdata('staff_Org_id')."' ");
                $students = $students->row();$students =$students->total;
                $q=  $this->db->query($q);
                
                $q1 =$q->result();$i=1;$avg=0;
                $grph_values="";
                  foreach($q1 as $value){
                      //['Shanghai', 23.7]
                      if($value->count ==0){
                          $avg=100;
                      }else{
                          $avg= round((($students-$value->count)/$students)*100 ) ;
                      }
                      $grph_values.="['".date("d-m-y", $value->day)."(".$value->count.")', ".$avg."],";
                  }
$from1= getdate($from);
                
                ?><br/><br/><hr/>
		<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Attendance Report <?php echo $from1['month']." , ".$from1['year'] ?> '
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
                text: 'Attendance Percentage'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
        },
        series: [{
            name: 'Population',
            data: [
                <?php echo $grph_values ?>
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

<div id="container" style="min-width: 310px; height: 400px; width: 100%; margin: 0 auto"></div>

                 <div class="box box-bordered box-color"  >
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Monthly Attendance Report Of <?php echo $from['month']." , ".$from['year'] ?>  </h3> 
                            <div class="actions" style=" color: white" >
                                <strong> Total Students :  <?php echo $students ?></strong>
                            </div>
                    </div>
                    <div class="box-content nopadding" style=" height: 270px; overflow: scroll;"> 
                        <table class="table table-hover table-nomargin table-bordered" >
                               <thead>
                                   <tr>
                                       <th>S.no</th>
                                       <th>Date</th>
                                       <th>Morning</th>
                                       <th>Afternoon</th>
                                   </tr>
                               </thead>
                               <tbody >
                                   <?php
                                    $q="SELECT d.day ,d.slot,(SELECT count(distinct(student)) from attendance where date_id in (group_concat(d.id))) as count FROM `attendance_date` d where  ( (d.day >='".$from."') AND (d.day <'".$to."')  )  GROUP BY d.day,d.slot ORDER BY d.day";  
                                    $q = $this->db->query($q);
                                      if($q->num_rows()==0){
                                          ?>
                                   <tr>
                                       <td colspan="4" style=" text-align: center ; color: red"> ** No Attendance Records Found.
                                       </td>
                                   </tr>
                                          <?php
                                      }else{
                                          $q =$q->result();$i=1;$avg=0;
                                          $month_att= array();
                                            foreach($q as $value){
                                                $month_att[$value->day][$value->slot]=$value->count;
                                            }
                                            
                                            foreach ($month_att as $key=>$value) {
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php echo date("d-m-y", $key); ?></td>
                                                      <td><?php 
                                                             if(isset($value[1])){
                                                                 echo $students-$value[1];
                                                                 if($value[1]==0){
                                                                   echo "<br/>(100%)";  
                                                                 }else{
                                                                  echo "<br/>(". number_format((($students-$value[1])/$students)*100,2).")";   
                                                                 }
                                                             }else{
                                                               echo "--"; 
                                                             }
                                                              ?></td>
                                                      <td><?php 
                                                             if(isset($value[2])){
                                                                 echo $students-$value[2];
                                                                 if($value[2]==0){
                                                                   echo "<br/>(100%)";  
                                                                 }else{
                                                                  echo "<br/>(". number_format((($students-$value[2])/$students)*100,2).")";   
                                                                 }
                                                             }else{
                                                               echo "--"; 
                                                             }
                                                              ?></td>
                                                      
                                                  </tr>
                                                <?php
                                            }
                                          
                                      }
                                   ?>
                               </tbody>
                        </table>
                    </div>
                 </div>
                <?php
            }
             else{
               $q="SELECT d.day ,(SELECT count(distinct(student)) from attendance where date_id in (group_concat(d.id))) as count FROM `attendance_date` d where section ='".$cls_name."' AND  ( (d.day >='".$from."') AND (d.day <'".$to."')  )  GROUP BY d.day";  
                $section =$this->db->query("SELECT s.sid,s.name as sec_name ,c.name as cls_name ,(select count(*) from student where section_id= s.sid ) as students FROM `section` s JOIN class c ON s.class_id =c.id WHERE s.sid= '".$cls_name."'  ");
                $section = $section->row();
                $students =$section->students;
                $q=  $this->db->query($q);
                
                                $q1 =$q->result();$i=1;$avg=0;
                $grph_values="";
                  foreach($q1 as $value){
                      //['Shanghai', 23.7]
                      if($value->count ==0){
                          $avg=100;
                      }else{
                          $avg= round((($students-$value->count)/$students)*100 ) ;
                      }
                      $grph_values.="['".date("d-m-y", $value->day)."(".$value->count.")', ".$avg."],";
                  }
$from1= getdate($from);
                
                ?><br/><br/><hr/>
		<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Attendance Of <?php echo $section->cls_name." - ".$section->sec_name ?> , <?php echo $from1['month']." - ".$from1['year'] ?> '
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
                text: 'Attendance Percentage'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
        },
        series: [{
            name: 'Population',
            data: [
                <?php echo $grph_values ?>
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

<div id="container" style="min-width: 310px; height: 400px; width: 100%; margin: 0 auto"></div>
<?php
              
               $from1= getdate($from);
                ?>
                 <div class="box box-bordered box-color"  >
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Monthly Attendance Report Of <?php echo $section->cls_name." - ".$section->sec_name ?> , <?php echo $from1['month']." , ".$from1['year'] ?>  </h3> 
                            <div class="actions" style=" color: white" >
                                <a class="btn btn-default" ><strong> Total Students :  <?php echo $students ?></strong></a> &nbsp;&nbsp;
                                <a class="btn btn-default" href="<?php echo base_url() ?>/index.php/attendance/attendance_report/<?php echo $section->sid ?>">Brief Report</a>
                            </div>
                    </div>
                    <div class="box-content nopadding" style=" height: 270px; overflow: scroll;"> 
                        <table class="table table-hover table-nomargin table-bordered" >
                               <thead>
                                   <tr>
                                       <th>S.no</th>
                                       <th>Date</th>
                                       <th>Morning</th>
                                       <th>Afternoon</th>
                                   </tr>
                               </thead>
                               
                               <tbody >
                                   <?php
                                       $q="SELECT d.day,d.slot ,(SELECT count(distinct(student)) from attendance where date_id in (group_concat(d.id))) as count FROM `attendance_date` d where section ='".$cls_name."' AND  ( (d.day >='".$from."') AND (d.day <'".$to."')  )  GROUP BY d.day,d.slot";  
                 $q=  $this->db->query($q);
               $q1 =$q->result();$i=1;$avg=0;
                                      if($q->num_rows()==0){
                                          ?>
                                   <tr>
                                       <td colspan="4" style=" text-align: center ; color: red"> ** No Attendance Records Found.
                                       </td>
                                   </tr>
                                          <?php
                                      }else{
                                          $q =$q->result();$i=1;$avg=0;
                                          $month_att= array();
                                            foreach($q as $value){
                                                $month_att[$value->day][$value->slot]=$value->count;
                                            }
                                            
                                            foreach ($month_att as $key=>$value) {
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php echo date("d-m-y", $key); ?></td>
                                                      <td><?php 
                                                             if(isset($value[1])){
                                                                 echo $students-$value[1];
                                                                 if($value[1]==0){
                                                                   echo "<br/>(100%)";  
                                                                 }else{
                                                                  echo "<br/>(". number_format((($students-$value[1])/$students)*100,2).")";   
                                                                 }
                                                             }else{
                                                               echo "--"; 
                                                             }
                                                              ?></td>
                                                      <td><?php 
                                                             if(isset($value[2])){
                                                                 echo $students-$value[2];
                                                                 if($value[2]==0){
                                                                   echo "<br/>(100%)";  
                                                                 }else{
                                                                  echo "<br/>(". number_format((($students-$value[2])/$students)*100,2).")";   
                                                                 }
                                                             }else{
                                                               echo "--"; 
                                                             }
                                                              ?></td>
                                                      
                                                  </tr>
                                                <?php
                                            }
                                          
                                      }
                                   ?>
                               </tbody>
                        </table>
                    </div>
                 </div>
                <?php      
           }
            
        }
      
    public function att_print_out($date=""){
        if(strlen($date)==0){
            redirect("attendance/view","refresh");
        }else{
            $d= explode("-",$date);
            $ti=mktime(0, 0, 0, $d['1'], $d['0'], $d['2']);            
            $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' ";
            $sections = $this->db->query($sections);
            $q =("SELECT d.section ,d.day,d.slot,(SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id =d.id  ) as abs_count FROM `attendance_date` d  where d.day ='".$ti."'  GROUP BY d.section  ORDER BY d.section ");
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
<hr color="#00306C" />     
<h4 style=" text-align: center; ">Attendance Report Of '. $date.'</h4>'; 
                   ?> 
                        
         <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <th>Class - section</th>
                                    <th>Total Students</th>
                                    <th>Morning</th>
                                    <th>Afternoon</th>
                                </tr>
                         <?php
                          if($sections->num_rows()>0){
                    $q = $this->db->query("SELECT d.section ,d.day,d.slot,(SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id =d.id  ) as abs_count FROM `attendance_date` d  where d.day ='".$ti."'  GROUP BY d.section,d.slot  ORDER BY d.section ");
                    if($q->num_rows()>0){
                       $attendance_list = array();
                       $q = $q->result();
                       foreach($q as $value){
                           $attendance_list[$value->section][ $value->slot ] = array("count"=>$value->abs_count);
                       }
                        $i=1;$total_str=$total_abs=0;
                       $sections = $sections->result();
                       foreach($sections as $value){
                           
                           if($value->total!=0){
                                ?>
                                <tr>
                                    <td><?php echo $value->cls_name." - ".$value->sec_name ?></td>
                                    <td><?php echo $value->total ;
                                     $total_str =$total_str+$value->total ;
                                      ?></td>
                                    <td style=" text-align: center">
                                        <?php 
                                         if(isset($attendance_list[$value->sid][1])){
                                             echo ($value->total-$attendance_list[$value->sid][1]["count"]);
                                             $per= (($value->total-$attendance_list[$value->sid][1]["count"])/$value->total)*100;
                                             echo "<br/>(".number_format($per,2).")";
                                         }else{
                                             echo "--";
                                         }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                         if(isset($attendance_list[$value->sid][2])){
                                             echo ($value->total-$attendance_list[$value->sid][2]["count"]);
                                             $per= (($value->total-$attendance_list[$value->sid][2]["count"])/$value->total)*100;
                                             echo "<br/>(".number_format($per,2).")";
                                         }else{
                                             echo "--";
                                         }
                                        ?>
                                    </td>
                                </tr>
                                                                                                   
                          <?php
                           }
                         
                       }
                   }else{
                                ?>
                                <tr>
                                    <td colspan="5"><span style=" color: red">** Please Add Attendance To view Report's</span></td>
                                </tr>
                       <?php
                       }
                }else{
                    ?>
                        <tr>
                            <td colspan="5"> <span style=" color: red;">No Class Structure Found..</span></td>
                        </tr>
               
                    <?php
                }   
                         ?>
                        </table> 
<span style="color:  red; float: right">** Display No Of Presenties Only... And In Braces  Attendance Percentage </span>
                    </div>
                    <script>
                     window.print();
                    </script>
                </body>
            </html>
            <?php
        }
    }
    public function student_att_print_out($date=""){
        if(strlen($date)==0){
            redirect("attendance/view","refresh");
        }else{
            $d= explode("-",$date);
            $ti=mktime(0, 0, 0, $d['1'], $d['0'], $d['2']);            
            $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' ";
            $sections = $this->db->query($sections);
            $q="SELECT st.student_id,st.name,st.userid,s.name as sec_name,c.name as cls_name ,d.slot FROM `attendance` a JOIN `attendance_date` d ON a.date_id =d.id JOIN student st ON a.student=st.student_id JOIN section s ON d.section= s.sid JOIN class c ON s.class_id=c.id where d.day ='".$ti."'  ORDER BY c.name ,st.roll";
            $q = $this->db->query($q)->result();
            $stud_arr=array();
            foreach($q as $val){
                $stud_arr[$val->student_id]= array("name"=>$val->name,"userid"=>$val->userid,"sec_name"=>$val->sec_name,"cls_name"=>$val->cls_name);
                $stud_arr[$val->student_id][$val->slot]=2;
            }
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
<hr color="#00306C" />

            <h4 style=" text-align: center; ">Absenties Report Of '.$date.'</h4>';
                        ?>
                        <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <th>Adm No</th>
                                    <th>Student</th>
                                    <th>Class-section</th>
                                    <th>Morning</th>
                                    <th>Afternoon</th>
                                </tr>
                                <?php
                                    foreach ($stud_arr as $key=>$value) {
                                        ?>
                                <tr>
                                    <td><?php  echo $value['userid'] ?></td>
                                    <td><?php  echo $value['name'] ?></td>
                                    <td><?php  echo $value['cls_name']." - ".$value['sec_name'] ?></td>
                                    <td style=" text-align: center;"><?php  if(isset($stud_arr[$key][1])) {
                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                    }else{
                                             echo '<i class="fa fa-times" aria-hidden="true"></i>';
                                   
                                    }
                                        ?></td>
                                    <td style=" text-align: center;"><?php  if(isset($stud_arr[$key][2])) {
                                        echo '<i class="fa fa-check" aria-hidden="true"></i>';
                                    }else{
                                             echo '<i class="fa fa-times" aria-hidden="true"></i>';
                                   
                                    }
                                        ?></td>
                                    
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
        
    public function fetch_day_attendance(){
            $date =$this->input->post("att_date");
            $d= explode("/",$date);
            $ti=mktime(0, 0, 0, $d['1'], $d['0'], $d['2']);            
            $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' ";

             $sections = $this->db->query($sections);
             $q="SELECT d.section , group_concat(d.id)as date_id , (SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id IN (group_concat(d.id))  ) as abs_count  FROM `attendance_date` d where d.day ='".$ti."'  GROUP BY d.section  ORDER BY d.section  ";
            ?>
         <div class="box box-bordered box-color"  >
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>View Attendance Report Of <?php echo $date ?>  </h3> 
                    <div class="actions"> 
                        <a class="btn " target="_blank" rel="tooltip" title="" data-original-title="Print Report" href=" <?php echo base_url(); ?>index.php/attendance/att_print_out/<?php echo str_replace("/", "-", $date) ?>" ><i class="fa fa-print"></i></a>
                        <a class="btn " target="_blank" rel="tooltip" title="" data-original-title="Print Report" href=" <?php echo base_url(); ?>index.php/attendance/student_att_print_out/<?php echo str_replace("/", "-", $date) ?>/student_list" ><i class="fa fa-users"></i></a>
                  
                    </div>
                    </div>
                    <div class="box-content nopadding" style=" height: 270px; overflow: scroll;"> 
                     <table class="table table-hover table-nomargin table-bordered" >
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Class - section</th>
                                    <th>Total Students</th>
                                    <th>absentees</th>
                                    <th>Attendance per</th>
                                </tr>
                            </thead>
                            <tbody >
                                
                                           
                         <?php
                          if($sections->num_rows()>0){
                    $q = $this->db->query($q);
                    if($q->num_rows()>0){
                       $attendance_list = array();
                       $q = $q->result();
                       foreach($q as $value){
                           $attendance_list[$value->section] = array("date_ids"=>$value->date_id,"count"=>$value->abs_count);
                       }
                        
                        $i=1;$total_str=$total_abs=0;
                       $sections = $sections->result();
                       foreach($sections as $value){
                          ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value->cls_name." - ".$value->sec_name ?></td>
                                    <td><?php echo $value->total ;
                                     $total_str =$total_str+$value->total ;
                                      ?></td>
                                    <td><?php 
                                            if(isset($attendance_list[$value->sid])){
                                                echo $attendance_list[$value->sid]['count'];
                                                $total_abs =$total_abs+$attendance_list[$value->sid]['count'] ;
                                            }                                                                                 
                                            ?>
                                    </td>
                                    <td><?php 
                                            if(isset($attendance_list[$value->sid])){
                                                if($attendance_list[$value->sid]['count'] ==0){
                                                    echo "100%";
                                                }else{
                                                echo 100-(round(($attendance_list[$value->sid]['count'] /$value->total)*100)); echo "%";
                                                }
                                            }                                                                                 
                                        ?>
                                    </td>
                                </tr>
                                                                                                   
                          <?php
                       }
                       ?>
                                <tr>
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo $total_str ?></td>
                                    <td><?php echo $total_abs ?></td>
                                    <td><?php 
                                    if($total_abs==0){
                                       echo "100"; 
                                    }else{
                                    echo 100-round(((($total_abs/$total_str))*100)) ;
                                    }
                                    echo "%";
                                        ?></td>
                                </tr>
                          <?php
                        
                   }else{
                                ?>
                                <tr>
                                    <td colspan="5"><span style=" color: red">** Please Add Attendance To view Report's</span></td>
                                </tr>
                       <?php
                       }
                }else{
                    ?>
                        <tr>
                            <td colspan="5"> <span style=" color: red;">No Class Structure Found..</span></td>
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
     
    public function student_brief_print($student=""){
        if(strlen($student)==0){
            redirect("attendance/view","refresh");exit;
        }
        
                   $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
                   if($student->num_rows()==0){
                       redirect("attendance/view","refresh");
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

<br style="clear:both" />
<div class="subject">'. $student->name  .' Attendance Brief History<!--for Cement.--></div><br />';
                        $dates_array=array();
                        $dates = $this->db->query( " SELECT * FROM attendance_date WHERE iid='".$this->session->userdata("staff_Org_id")."' and section='".$student->section_id."' ");
                        $a="";
                        if($dates->num_rows()>0){
                            $dates= $dates->result();
                            $st_att=array();
                            $std_att=$this->db->query("SELECT DISTINCT(date_id) from attendance WHERE  student='".$student->student_id."' ");
                            $std_att=$std_att->result();
                            foreach ($std_att as $value) {
                                $st_att[$value->date_id]=1;
                            }
                            $monthly_att=array();
                            foreach ($dates as $value) {
                                if(isset($dates_array[$value->id])){
                                    $dates_array[$value->id][$value->slot]=1;
                               }else{
                                    $dates_array[$value->id] = array('day'=>$value->day);
                                    $dates_array[$value->id][$value->slot]=$value->slot;
                                }
                                $d= getdate($value->day);
                                if(isset($monthly_att[$d['year']][$d['mon']])){
                                     $monthly_att[$d['year']][$d['mon']]['total']++;
                                }else{
                                    $monthly_att[$d['year']][$d['mon']] = array('month'=>$d['month'],'total'=>1,'absent'=>0);
                                }
                                $monthly_att[$d['year']][$d['mon']]['att'][$value->day][$value->slot]=$value->id;
                                if(isset($st_att[$value->id])){
                                    $a.=$value->id.",";
                                    $monthly_att[$d['year']][$d['mon']]['absent']++;
                                }
                                
                            }

                            $grph_names="";
                            foreach ($monthly_att as $key=>$value) {
                                foreach ($value as $k=>$val) {
                                  $pr=( ($val['total']-$val['absent'])/$val['total'])*100;
                                  $grph_names.="[ '".$val['month']."<br/>(".$key.")' ,".$pr."],";
                                }
                            }
                            ?>
                              <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		
                              <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '  '
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
                <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto">
                    
                </div>
                <div class="box" style="clear: both" >
                                       
                                        <?php
                                                      $i=1;
                                                      foreach ($monthly_att as $key=>$value) {
                                                            foreach ($value as $k=>$val) {
                                                                $pr=( ($val['total']-$val['absent'])/$val['total'])*100;
                                                              $grph_names.="[ '".$val['month']."<br/>(".$key.")' ,".$pr."],";
                                                              ?>
                                                            <hr/>
                                                                <h3 style=" text-align: center"><?php 
                                                                  echo "Attendance Report Of ".$val['month']."-".$key ;
                                                                  ?></h3>
                                                               <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                                                       <tr style=" text-align: center">
                                                                            <th style=" text-align: center">Date</th>
                                                                            <th style=" text-align: center">Morning</th>
                                                                            <th style=" text-align: center">Afternoon</th>
                                                                        </tr>
                                                                        <?php
                                                                          $total=0;$present=0;
                                                                            foreach ($val['att'] as $kr=>$vark) {
                                                            //                    print_r($vark);
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php  echo date("d-m-y",$kr) ?></td>
                                                                                    <td><?php 
                                                                                        if(isset($vark[1])){
                                                                                            $total++;
                                                                                            if(isset($st_att[$vark[1]])){
                                                                                                $present++;
                                                                                                ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                                            }else{
                                                                                                ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                                            }
                                                                                        } else{
                                                                                            echo "--";
                                                                                        }
                                                                                    ?></td>
                                                                                    <td><?php 
                                                                                        if(isset($vark[2])){
                                                                                            $total++;
                                                                                            if(isset($st_att[$vark[2]])){
                                                                                                $present++;
                                                                                                ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                                            }else{
                                                                                                ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                                            }
                                                                                        } else{
                                                                                            echo "--";
                                                                                        }
                                                                                    ?></td>
                                                                                </tr>
                                                                               <?php
                                                                            }
                                                                        ?>
                                                                </table>
                                                                <h4 style=" margin: 0px; text-align: center; padding-top: 8px; ">Total : <?php echo $total ?> Present : <?php echo $present ?> Absent : <?php echo $total-$present ?></h4>
                                                           
                                                               <?php
                                                                  $i++;
                                                            }
                                                      }
                                                    ?>        
                                        
                               
                    </div>
                
                
				
                            <?php
                        }else{
                          ?><br/><br/>
                <span style=" color: red">** Invalid Student Selected</span>
                       <?php  
                            
                        }
                    }
                    
                    ?>
                        </div>
                    </div>
                    <script>
                    window.print();
                    </script>
                </body>
            </html>
                
                <?php
             
    }
   
    private function delete_attendance_records($ch_stud,$date_id,$slot){
         $where = "date_id='".$date_id."' AND acid='".$slot."' AND id in (".$ch_stud.") ";
         $this->db->where($where);
         //$this->db->where_in('id', $data);
         $this->db->delete('attendance');
    }
    
    
}
?>

