<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alerts extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->library("pagination");$this->operations->is_login();
            /* cache control */
    }
    
    function index(){
         $this->load->view("alerts/all_alerts");
    }
    
    public function send_alerts(){
        
        $ids=  $this->input->post('ids');
        $ids=  explode(",",$ids);
        $ids= array_filter($ids);
        $message= trim($this->input->post('message'));
        $schedule = $this->input->post("schedule");
        $unicode =  $this->input->post("unicode");
        $field = 'ids';
          
        if(sizeof($ids) ==  0)
        {
           $this->form->setError($field,'* Please select Contacts');
        }else{
            $mobile="";
            foreach ($ids as $value) {
              $mobile .=",".$this->get_contacts($value);  
            }
           $mobile=explode(",",$mobile);
           $mobile= array_filter($mobile);
           
           if(sizeof($mobile)==0){
               $this->form->setError($field,'* No Mobile numbers Found ');
           }
           
           
        }
       
        $field = 'message';
          
        if(strlen($message) ==  0)
        {
           $this->form->setError($field,'* Please Enter Message Content');
        }
        $timer =0;
        if(strlen($schedule)!=0){
            $date =$this->input->post("date");
            $time =$this->input->post("time");
            $date=explode("/",$date);
            
            if((sizeof($date))==3){
                $time = explode(" ",$time);
                if(sizeof($time)!=2){
                     $this->form->setError('time','* Please select a valid time1 ');  
                }else{
                    if($time[1]=="PM"){
                        $time = explode(":",$time[0]);
                        $time[0]=$time[0]+"12";
                        $time[1]=$time[1];
                    }else{
                        $time = explode(":",$time[0]);
                    }

        $timer= mktime($time[0], $time[1],0,$date[1],$date[0],$date[2] );
                   if($timer<=time()){
                     $this->form->setError('time','* Please select a valid time 2'); 
                   } 
                }
            }else{
                 $this->form->setError('time','* Please select a Valid Date 3'); 
            }
            
        } 
        if($this->form->num_errors >0 ){
           $_SESSION['value_array'] = $_POST;
           $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('Alerts/', 'refresh'); exit;
         }else{
             $count=0;
             $status=1;
             
             if(strlen($unicode)!=0){
                 $status=2;
             }
             
//            $sms_details=  $this->fetch_sms_details();
//            $data=array(
//                       'iid' =>$this->session->userdata('staff_Org_id'),
//                        'staff_id' =>$this->session->userdata('staff_id'),
//                       'for'=>implode(",",$ids),
//                       'message' =>$message,
//                       'time' =>time()
//                   );
//            $this->db->insert('alerts',$data);
//            $aid=$this->db->insert_id();
//            $this->logs->insert_staff_log(4,'Sending Alert',$aid);
            $data['values']=$_POST;
            $data['schedule']=0;
            $message_arr=array();
            
            foreach ($mobile as $value) {
               
                $message_arr[]=array(
                    'msg_content'  =>$message,
                    'mobile' =>$value,
                );
            }
            $data['message'] =$message_arr;
            $data['msg_type']="Alerts";
            if($timer!=0){
                $data['schedule']=1;
                $data['schedule_time']=$timer;
            }else{
                           $data['schedule_time']=time();
            }
            $_SESSION['sms_data']=$data;
            redirect("alerts/preview","refresh");
        } 
        
    }
    
    public function preview(){
        if(!isset($_SESSION['sms_data'])){
            redirect("alerts/","refresh");
        }else{
            $data =$_SESSION['sms_data'];
            $this->load->view("alerts/preview",$data);
        }
            
    }
    
    public function history(){
         $this->load->view("alerts/history");
    }
    
    public function sent_history(){
        $config = array();
        $config["base_url"] = base_url() ."index.php/Alerts/sent_history";
        if(strlen($this->session->userdata('Alerts_send_sucess'))>0 ){
            
        }
        $config["total_rows"] = $this->sms_record_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->fetch_sms_history($config["per_page"],$page);
        $data["links"] = $this->pagination->create_links();
        $this->load->view("alerts/view_sms_history", $data);
    }
    
    public function promotion(){
        //
        $this->load->view("alerts/promotion");
    }
    
    
    private function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        return $msg;
    }
    
    private function send_sms_students($username,$password,$senderid,$message,$aid){
       $query=$this->db->query("SELECT phone from student WHERE iid='".$this->session->userdata('staff_Org_id')."' AND LENGTH (phone)=10 ");
       $query=$query->result();
       $count=0;
       foreach($query as $value){
           $data=array(
               'iid' =>$this->session->userdata('staff_Org_id'),
               'username'=>$username,
               'password' =>$password,
               'senderid' =>$senderid,
               'message'  =>$message,
               'mobile' =>$value->phone,
               'time' =>time(),
               'status' =>1
           );
           $count++;
           $this->db->insert('msg_senthistory',$data);
       }
       $this->logs->insert_staff_log(4,'Sent '.$count.' Messages ',$aid); 
       return $count;
    }     
    
    private function send_sms_staff($username,$password,$senderid,$message,$aid){
       $query=$this->db->query("SELECT phone from staff WHERE iid='".$this->session->userdata('staff_Org_id')."' AND LENGTH (phone)=10 ");
       $query=$query->result();
       $count=0;
       foreach($query as $value){
           $data=array(
               'iid' =>$this->session->userdata('staff_Org_id'),
               'username'=>$username,
               'password' =>$password,
               'senderid' =>$senderid,
               'message'  =>$message,
               'mobile' =>$value->phone,
               'time' =>time(),
               'status' =>1
           );
           $count++;
           $this->db->insert('msg_senthistory',$data);
       }
        $this->logs->insert_staff_log(4,'Sent '.$count.' Messages ',$aid);
         return $count;
    }
    
    private function send_sms_parents($username,$password,$senderid,$message,$aid){
       $query=$this->db->query("SELECT phone from parent WHERE iid='".$this->session->userdata('staff_Org_id')."' AND LENGTH (phone)=10 ");
       $query=$query->result();
       $count=0;
      
       foreach($query as $value){
           $data=array(
               'iid' =>$this->session->userdata('staff_Org_id'),
               'username'=>$username,
               'password' =>$password,
               'senderid' =>$senderid,
               'message'  =>$message,
               'mobile' =>$value->phone,
               'time' =>time(),
               'status' =>1
           );
           $this->db->insert('msg_senthistory',$data);
           $count++;
       }
      $this->logs->insert_staff_log(4,'Sent '.$count.' Messages ',$aid);
      return $count;
    }
        
    private function fetch_sms_history($limit,$start){
        echo $start =($start/10);
       $this->db->where('iid', $this->session->userdata('staff_Org_id'));  
        $this->db->limit($limit, $start);
        $query = $this->db->get("msg_senthistory");
        return $query->result();
    }
    
    private function sms_record_count(){
        $this->db->where('iid' ,'1');
        $t=$this->db->get("msg_senthistory");
        $this->session->userdata("sms_record_count",$t->num_rows());
        return $t->num_rows();
    }
    
    private  function get_contacts($id){
        $t ="";
        if($id=="staff"){
            $q="select * from staff where iid='".$this->session->userdata("staff_Org_id")."'";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone.",";
            }
        }else if($id=="parent"){
            $q="select * from parent where iid='".$this->session->userdata("staff_Org_id")."'";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone.",";
            }
        }else{
            $id = explode("_",$id);
            
            $q="select * from student where iid='".$this->session->userdata("staff_Org_id")."' AND section_id ='".$id[1]."' ";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone.",";
            }
        }
        return $t;
    }
    
    
    public function load_contacts(){
        $id=  $this->input->post("id");
        $mobile =  $this->input->post("mobile");
        $t ="";
        if($id=="staff"){
            $q="select * from staff where iid='".$this->session->userdata("staff_Org_id")."' AND level <= 5 ";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone."\n";
            }
        }else if($id=="parent"){
            $q="select * from parent where iid='".$this->session->userdata("staff_Org_id")."'";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone."\n";
            }
        }else{
            $id = explode("_",$id);
            
            $q="select * from student where iid='".$this->session->userdata("staff_Org_id")."' AND section_id ='".$id[1]."' ";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone."\n";
            }
        }
        $mobile .="\n".$t;
        $mobile = array_filter(explode("\n",$mobile));
        $mobile = implode("\n",$mobile);
        echo $mobile;
    }
    
    public function remove_contacts(){
        $id=  $this->input->post("id");
        $mobile =  $this->input->post("mobile");
        
        $t= "";
        if($id=="staff"){
            $q="select * from staff where iid='".$this->session->userdata("staff_Org_id")."'";
            $query=$this->db->query($q);
            $query=$query->result();
            
            foreach($query as $value){
               $t=$t."".$value->phone."\n";
            }
        }else if($id=="parent"){
            $q="select * from parent where iid='".$this->session->userdata("staff_Org_id")."'";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone."\n";
            }
        }else{
            $id = explode("_",$id);
            
            $q="select * from student where iid='".$this->session->userdata("staff_Org_id")."' AND section_id ='".$id[1]."' ";
            $query=$this->db->query($q);
            $query=$query->result();

            foreach($query as $value){
                $t=$t."".$value->phone."\n";
            }
        }
        $t= explode("\n",$t);
        $mobile =explode("\n",$mobile);
        $mobile=array_filter(array_diff($mobile,$t));
        $mobile =implode("\n",$mobile);
        echo $mobile;
        
    }
   
    public function send_results(){
        $this->load->view("alerts/send_results");
    }
    
    public function load_sections(){
      $id=  $this->input->post("exam"); 
      $q=  $this->db->query("SELECT ec.id,ec.sectionid,s.name as section , c.name as cls_name FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where examid='".$id."' ");
      if($q->num_rows()==0){
       echo "No Results found.";  exit;
      }else{
         $q=$q->result(); 
         $ids="";
         foreach($q as $value){
             $ids.=$value->sectionid.",";
             ?>
            <input checked="" type="checkbox" name="section_<?php echo $value->sectionid ?>"  value="<?php echo $value->id ?>"  /> <?php echo $value->cls_name ." - ".$value->section  ?><br/>
            <?php
         }
         $ids = substr($ids,0,strlen($ids)-1);
         ?>
            <script>$('#ids_list').val('<?php echo $ids ?>');</script>
        <?php
         
      }
      
      
    }
    
    public function  result_msg(){

        
        if(strlen($this->input->post('exam_list'))==0){
               $this->form->setError('exam_list','* please select exam ');
        } else{
        
            $ids =$this->input->post("ids");
            $ids= array_filter(explode(",",$ids));
                if(sizeof($ids)==0){
                    $this->form->setError('ids','* No section records found.. ');
                }else{
                    $s =0;
                    $selected="";
                    foreach($ids as $id){
                        if(strlen($this->input->post('section_'.$id))!=0){
                           $s++; 
                           //SELECT * FROM `exam` where examid=1 and ecid=1
                           if($this->check_configured($this->input->post('exam_list'),$this->input->post('section_'.$id))){
                                 $selected =$selected. $id .",";
                           }else{
                               $this->form->setError('ids','* Exam settings not configured'); 
                           }
                         
                        }
                    }
                    if($s==0){
                       $this->form->setError('ids','* Please select sections  '); 
                    }else if(!$this->check_exam_classids(implode(',',$ids),$this->input->post('exam_list'))){
                        $this->form->setError('ids','* Invalid sections  '); 
                    }
                    
                }
            
        }
        $cust =0;
        if(strlen($this->input->post('customize'))!=0){
             $cust=1;  
            if(strlen($this->input->post('message'))==0){
              $this->form->setError('ids','* Please enter message content '); 
            }
            
        }
        
        
        if($this->form->num_errors >0 ){
           $_SESSION['value_array'] = $_POST;
           $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect('Alerts/', 'refresh'); exit;
        }else{
             $count=0;
             $eid=$this->input->post('exam_list');
             $selected = array_filter( explode(",",$selected));
             $message=array();
             $_SESSION['result_Arr']=$message;
             foreach ($selected as $id) {
                  $this->send_report($id, $eid.",".$this->input->post('section_'.$id));
               }
            $data['values']=$_POST;
            $data['schedule']=0;
            $data['message'] =$_SESSION['result_Arr'];
            unset($_SESSION['result_Arr']);
            $data['msg_type']="Results";
            $data['schedule_time']=time();
            $_SESSION['sms_data']=$data;
            redirect("alerts/preview","refresh");
         }
        
    }
    
    private function check_exam_classids($ids,$examid){
       $q=  $this->db->query(" SELECT * FROM `examination_cls` where id in (".$ids.") AND examid='".$examid."'  "); 
       if(sizeof(array_filter(explode(",",$ids)))== $q->num_rows() ){
           return TRUE;
       }else{
           return FALSE;
       }
       
    }
   
    private function check_configured($exam,$ecid){
        $q=  $this->db->query("SELECT * FROM `exam` where examid='".$exam."' and ecid='".$ecid."' ");
        if($q->num_rows()>0){
             return TRUE;
        }else{
            return FALSE;
        }
    }
    
    private function  get_total_marks($exam,$ecid){
        $q=  $this->db->query("SELECT sum(maxmarks)as total FROM `exam` where examid='".$exam."' and ecid='".$ecid."' ");
       $q = $q->row();
        return $q->total;
    }

    public function send_report($section,$exam){
       $msg_array= $_SESSION['result_Arr'] ;
            $exam=explode(",",$exam);
            
            $examid=$exam[0];
            $ecid=$exam[1];
            $grand_total = $this->get_total_marks($examid,$ecid);
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
               $total=0;
                foreach( $query as $e){
                      
                        // print_r($e);
                        
                         if( isset($stud_marks[$stud['id']][$e->id]) ){
                           $m= $stud_marks[$stud['id']][$e->id];  
                           $total =$total+$m;
                         } else{
                         $m= "-" ;
                         }
                     $content.= $e->subject ." : ".$m."\n";
                }
                
                $content.=" Grand Total : ".$total." / ".$grand_total;
                    $msg="Dear ".$stud['name']." , Your results For Exam :".$edetails->exam ." is as follows : \n" .$content ;
                    $data=array('msg_content'  =>$msg,'mobile' =>$stud['stdph']);
                    $msg_array[]=$data;
                    if( strlen($stud['prntph'])!=0 ){
                         $msg="Dear Parent \n Your Ward : ".$stud['name']." \n Your results For Exam :".$edetails->exam ." is as follows : \n" .$content ;

                         $data=array(
                         'msg_content'  =>$msg,
                         'mobile' =>$stud['prntph'],
                         
                         );
                         $msg_array[]=$data;
                     }
                
            }
            $_SESSION['result_Arr'] =$msg_array;
         
        }
       
    public function attendance_report(){
        //send_attendance_reports
         $this->load->view("alerts/send_attendance_reports");
    }
    
    public function send_attendance_report(){
         
        $count =0;
        $section_list = array_filter(explode("," ,$this->input->post('section_list')));
        foreach ($section_list as $value) {
            if(strlen($this->input->post('secction_'.$value."_1"))!=0 ){
                $count++;
            }
        
            if(strlen($this->input->post('secction_'.$value."_2"))!=0 ){
                $count++;
            }
        }
        if($count==0){
           $_SESSION['value_array'] = $_POST;
           $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('Alerts/', 'refresh'); exit;
         }else{
             $msgcount=0;
              $attendance_list =  json_decode($this->input->post('attendance_list'));
              $date = getdate();
              $sms_details=$this->fetch_sms_details();
              foreach ($section_list as $value) {
                  $indiv_total1=0;
                  $indiv_total2=0;
                  $student_att = array();      
                  if(strlen($this->input->post('secction_'.$value."_1"))!=0 ){
                       $q=" SELECT s.student_id,s.phone as s_phone , s.name as s_name , p.name as p_name, p.phone as p_phone, d.slot  FROM attendance  a JOIN attendance_date d ON  a.date_id = d.id JOIN student s on a.student = s.student_id LEFT JOIN parent p ON p.parent_id = s.parent_id WHERE d.day='".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."' AND d.section ='".$value."' AND d.slot=1 ";
                       $q =  $this->db->query($q);
                       $q = $q->result();
                                        
                       foreach($q as $val){
                           $student_att[$val->student_id]['details']= array('s_name'=>$val->s_name,'s_phone'=>$val->s_phone,'p_name'=>$val->p_name,'p_phone'=>$val->p_phone);
                           $student_att[$val->student_id][$val->slot]="1";
                       }
                   } 
                
                   if(strlen($this->input->post('secction_'.$value."_2"))!=0 ){
                       $q=" SELECT s.student_id,s.phone as s_phone , s.name as s_name , p.name as p_name, p.phone as p_phone, d.slot  FROM attendance  a JOIN attendance_date d ON  a.date_id = d.id JOIN student s on a.student = s.student_id LEFT JOIN parent p ON p.parent_id = s.parent_id WHERE d.day='".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."' AND d.section ='".$value."' AND d.slot=2 ";
                       $q =  $this->db->query($q);
                       $q = $q->result();
                                           
                       foreach($q as $val){
                           $student_att[$val->student_id]['details']= array('s_name'=>$val->s_name,'s_phone'=>$val->s_phone,'p_name'=>$val->p_name,'p_phone'=>$val->p_phone);
                           $student_att[$val->student_id][$val->slot]="1";
                       }
                   } 
                   
                   foreach ($student_att as $key => $att) {
                       $message ="";
                      
                        if(isset($student_att[$key][1])&&isset($student_att[$key][2])){
                        $indiv_total1++;
                            $message= "  absent for both the sessions today ie.".date("d-m-y",time())." ";
                        } else{
                           
                            if(isset($student_att[$key][1])&&(!isset($student_att[$key][2]))){
                                $indiv_total1++;
                                $message= " absent for morning session today ie.".date("d-m-y",time())." ";
                            }else{
                                if(isset($student_att[$key][2])&&(!isset($student_att[$key][1]))){
                                   $indiv_total2++;
                                    $message= "  absent for Afternoon  session today ie.".date("d-m-y",time())." ";
                                   }
                            }
                        }
                        
                        if(strlen($student_att[$key]['details']['s_phone'])!=0){
                             $msg="Dear  ".$student_att[$key]['details']['s_name']." you are ".$message;
                            
                             $data=array(
                                
                                    'iid' =>$this->session->userdata('staff_Org_id'),
                                    'username'=>$sms_details->username,
                                    'password' =>$sms_details->password,
                                    'senderid' =>$sms_details->senderid,
                                    'message'  =>$msg,
                                    'mobile' =>$student_att[$key]['details']['s_phone'],
                                    'time' =>time(),
                                    'status' =>1
                             );
                           $this->db->insert('msg_senthistory',$data);
                            $msgcount++;
                        }
                        
                        if(strlen($student_att[$key]['details']['p_phone'])!=0){
                            $msg="Dear parent your Ward ".$student_att[$key]['details']['s_name']." is  ".$message;
                            
                            $data=array(
                                    'iid' =>$this->session->userdata('staff_Org_id'),
                                    'username'=>$sms_details->username,
                                    'password' =>$sms_details->password,
                                    'senderid' =>$sms_details->senderid,
                                    'message'  =>$msg,
                                    'mobile' =>$student_att[$key]['details']['p_phone'],
                                    'time' =>time(),
                                    'status' =>1
                             );
                           $this->db->insert('msg_senthistory',$data);
                            $msgcount++;
                        }
                        
                        
                        
                   }
                   //$indiv_total1 ,$indiv_total2
                   $da=  getdate();
                   $data=array(
                            'day' => mktime(0, 0, 0, $da['mon'], $da['mday'],  $da['year']),
                            'section'=>$value,
                            'slot' =>1,
                            'time' =>  time(),
                            
                     );
                   $this->db->insert('attendance_alerts',$data);
                   $data=array(
                            'day' => mktime(0, 0, 0, $da['mon'], $da['mday'],  $da['year']),
                            'section'=>$value,
                            'slot' =>2,
                            'time' =>  time(),
                            
                     );
                   $this->db->insert('attendance_alerts',$data);
                            
              }
              
                $this->session->set_userdata('Alerts_send_sucess', "Sucessfully sent Attendance Report , Total Message count :".$msgcount);
       
         } 
             
     
       redirect('Alerts/', 'refresh'); exit;
                
    }
    
    public function load_attendance_mobile(){
       $mobile = $this->input->post("mobile");
       $action=$this->input->post("action");
       $new_mobile="";
       $q="SELECT distinct(a.student) , s.phone  FROM `attendance` a join student s ON a.student= s.student_id   where date_id in (".$this->input->post("att_ids").") ";
       $q= $this->db->query($q);
       if($q->num_rows()>0){
         $q = $q->result();
         foreach($q as $value){
             $new_mobile=$new_mobile.",".$value->phone;
         }
       }
       $new_mobile=  implode(",",array_filter(explode(",",$new_mobile)));
       if($action=="add"){
          $mobile = implode(",",array_filter(explode("\n",$mobile))).",".$new_mobile;
          $mobile = str_replace(",", "\n",$mobile);
          echo $mobile;
       }else{
          $mobile =array_filter(explode("\n",$mobile));
          $new_mobile= array_filter(explode(",",$new_mobile));
          $mobile = implode(",", array_diff($mobile, $new_mobile));
          $mobile = str_replace(",", "\n",$mobile);
          echo $mobile;
       }
    }
    
    public function send_attendance(){
              
        $ids=  $this->input->post('att_section_ids');
        $ids=  array_filter(explode(",",$ids));
       // $ids= array_filter($ids);
        $message= trim($this->input->post('attmessage'));
        $schedule = $this->input->post("attschedule");
        $unicode =  $this->input->post("unicode");
        $customize = $this->input->post("attcustomize");
        $field = 'attmobile';
          
        if(sizeof($ids) ==  0)
        {
           $this->form->setError($field,'* Please select Contacts');
        }else{
            $mobile="";
            foreach ($ids as $value) {
              $mobile .=",".$this->absent_date_mobile($value);  
            }
           $mobile=explode(",",$mobile);
           $mobile= array_filter($mobile);
           
           if(sizeof($mobile)==0){
               $this->form->setError($field,'* No Mobile numbers Found ');
           }
           
           
        }
       
        $field = 'attmessage';
          
        if(strlen($message) ==  0)
        {
           $this->form->setError($field,'* Please Enter Message Content');
        }
        $timer =0;
        if(strlen($schedule)!=0){
            $date =$this->input->post("date");
            $time =$this->input->post("time");
            $date=explode("/",$date);
            if(sizeof($date)!=3){
                $time = explode(" ",$time);
                if(sizeof($time)!=2){
                     $this->form->setError('time','* Please select a valid time ');  
                }else{
                    if($time[1]=="PM"){
                        $time = explode(":",$time[0]);
                        $time[0]=$time[0]+"12";
                        $time[1]=$time[1];
                    }else{
                        $time = explode(":",$time[0]);
                    }

                   $timer= mktime($time[0], $time[1],0,$date[1],$date[0],$date[2] );
                   if($timer<=time()){
                      $this->form->setError('time','* Please select a valid time '); 
                   } 
                }
            }else{
                 $this->form->setError('time','* Please select a Valid Date'); 
            }
            
        }
            
        if($this->form->num_errors >0 ){
           $_SESSION['value_array'] = $_POST;
           $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('Alerts/', 'refresh'); exit;
         }else{
             $count=0;
             $status=1;
             if($timer==0){
                 $timer=time();
             }
             if(strlen($unicode)!=0){
                 $status=2;
             }
             
            $message_arr=array();

             if(strlen($customize)==0){
                foreach ($mobile as $value) {
                    $temp_arr=array(
                        'msg_content'  =>$message,
                        'mobile' =>$value,
                    );
                   $message_arr[]= $temp_arr;
                }    
             }else{
                 foreach ($ids as $id) {
                    $stud_data=  $this->return_student_info($id); 
                    
                    foreach($stud_data as $stud){
                        $n_msg = str_replace("<#name#>", $stud['name'], $message );
                        $n_msg = str_replace("<#roll#>", $stud['roll'], $n_msg );
                        $n_msg = str_replace("<#userid#>", $stud['userid'], $n_msg );
                        $temp_arr=array(
                            'msg_content'  =>$n_msg,
                            'mobile' =>$stud['phone'],
                        );
                        //phone
                        $message_arr[]= $temp_arr;
                    }
                    
                  }    
             }
             
             $data['values']=$_POST;
            $data['schedule']=0;
            

            $data['message'] =$message_arr;
            $data['msg_type']="Attendance";
            $data['schedule_time']=time();
            
            $_SESSION['sms_data']=$data;
            redirect("alerts/preview","refresh");
             
         }
        
    }
    
    public function absent_date_mobile($section){
       $new_mobile="";
       $date = getdate();
       $q="SELECT distinct(a.student) , s.phone,s.name,s.userid,s.roll  FROM `attendance` a join attendance_date ad ON a.date_id = ad.id  join student s ON a.student= s.student_id where ad.section ='".$section."' AND ad.day= '".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."' ";
       $q= $this->db->query($q);
       if($q->num_rows()>0){
         $q = $q->result();
         foreach($q as $value){
             $new_mobile=$new_mobile.",".$value->phone;
         }
       }
       return $new_mobile;
    }
    
    public function return_student_info($section){
       $student_details= array();
       $date = getdate();
       $q="SELECT distinct(a.student) , s.phone,s.name,s.userid,s.roll  FROM `attendance` a join attendance_date ad ON a.date_id = ad.id  join student s ON a.student= s.student_id where ad.section ='".$section."' AND ad.day= '".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."' ";
       $q= $this->db->query($q);
       if($q->num_rows()>0){
         $q = $q->result();
         foreach($q as $value){
             $student_details[$value->student] = array('name'=>$value->name,'roll'=>$value->roll,'userid'=>$value->userid,'phone'=>$value->phone);
         }
       }
       return $student_details;
    }
 
    public function back(){
        $data =$_SESSION['sms_data'];
        $_SESSION['value_array'] = $data['values'];
        unset($_SESSION['sms_data']);
        ?><script>window.location.reload();</script><?php
    }
    
    public function send_sms(){
        $data =$_SESSION['sms_data'];
        $time=$data['schedule_time'];
        $values=$data['values'];
        $message=$data['message'];$count=0;
        $sms_details=  $this->fetch_sms_details();
        $al_msg="";$reg=0;
        switch($data['msg_type']){
            case  "Alerts" : $al_msg="Sending Alerts";$reg=1;
                            break;
            case  "Results" : $al_msg="Sending Results";$reg=2;
                            break;
            case  "Attendance" : $al_msg="Sending Attendance Alert";$reg=3;
                            break;
            case  "Fee Alerts" : $al_msg="Sending Fee Alert";$reg=5;
                            break;
        }
        $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>$reg,
                    'message' =>$al_msg,
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        $this->logs->insert_staff_log(4,'Sending Alert',$aid);

        foreach ($message as $value) {
            $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$value['msg_content'],
                        'mobile' =>$value['mobile'],
                        'time' => $time,
                        'status' =>0,
                        'alert_id' =>$aid,
                    );
            $count++;
          $this->db->insert('msg_senthistory',$data); 
        }
       
        $this->session->set_userdata('Alerts_send_sucess', "Sucessfully sent Alert , Total Message count :".$count);
             
        unset($_SESSION['sms_data']);
        ?><script>window.location.reload();</script><?php
    }
    
    public function view_campign($id=""){
        if(strlen(trim($id))==0){
            redirect("Alerts/history","refresh");
        }else{
            $q=  $this->db->query("SELECT * FROM `alerts` where id='".$id."' ");
            if($q->num_rows()>0){
                   $data['alert_details']=$q->row();
                   $this->load->view("alerts/view_campign",$data);
            }else{
                 redirect("Alerts/history","refresh");
            }
        }
    }

    public function fee_alerts_msg(){
      //  print_r($_POST);
       $ids=  $this->input->post('fids');
        $ids=  array_filter(explode(",",$ids));
       // $ids= array_filter($ids);
        $message= trim($this->input->post('fmessage'));
        $schedule = $this->input->post("fschedule");
        $unicode =  $this->input->post("funicode");
        $customize = $this->input->post("feecustomize");
        $field = 'attmobile';
          
        if(sizeof($ids) ==  0)
        {
           $this->form->setError($field,'* Please select Contacts');
        }
        
        $field = 'attmessage';
          
        if(strlen($message) ==  0)
        {
           $this->form->setError($field,'* Please Enter Message Content');
        }
        $timer =0;
        if(strlen($schedule)!=0){
            $date =$this->input->post("fdate");
            $time =$this->input->post("ftime");
            $date=explode("/",$date);
            if(sizeof($date)!=3){
                $time = explode(" ",$time);
                if(sizeof($time)!=2){
                     $this->form->setError('time','* Please select a valid time ');  
                }else{
                    if($time[1]=="PM"){
                        $time = explode(":",$time[0]);
                        $time[0]=$time[0]+"12";
                        $time[1]=$time[1];
                    }else{
                        $time = explode(":",$time[0]);
                    }

                   $timer= mktime($time[0], $time[1],0,$date[1],$date[0],$date[2] );
                   if($timer<=time()){
                      $this->form->setError('ftime','* Please select a valid time '); 
                   } 
                }
            }else{
                 $this->form->setError('ftime','* Please select a Valid Date'); 
            }
            
        }
            
        if($this->form->num_errors >0 ){
           $_SESSION['value_array'] = $_POST;
           $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('Alerts/', 'refresh'); exit;
         }else{
            
             $count=0;
             $status=1;
             if($timer==0){
                 $timer=time();
             }
             if(strlen($unicode)!=0){
                 $status=2;
             }
             
            $message_arr=array();

             if(strlen($customize)==0){
                 foreach ($ids as $id) {
                    $stud_data=  $this->fetch_account_balance($id); 
                    foreach($stud_data['stud_details'] as $stud){
                         $temp_arr=array(
                        'msg_content'  =>$message,
                        'mobile' =>$stud->phone,
                    );
                   $message_arr[]= $temp_arr;
                    }
                   
                 }
                  
             }else{
                 foreach ($ids as $id) {
                    $stud_data=  $this->fetch_account_balance($id); 
                    $cls_details =$stud_data['cls_details'];
                    $cls_name =$cls_details->cls_name."-".$cls_details->sec_name;
                    $total= $cls_details->total;
                    foreach($stud_data['stud_details'] as $stud){
                        $n_msg = str_replace("<#name#>", $stud->name, $message );
                        $n_msg = str_replace("<#roll#>", $stud->roll, $n_msg );
                        $n_msg = str_replace("<#userid#>", $stud->userid, $n_msg );
                        $n_msg = str_replace("<#class#>", $cls_name, $n_msg );
                        $n_msg = str_replace("<#total#>", $total, $n_msg );
                        $n_msg = str_replace("<#paid#>", $stud->total, $n_msg );
                        $n_msg = str_replace("<#balance#>",($total-$stud->total), $n_msg );
                        
                        $temp_arr=array(
                            'msg_content'  =>$n_msg,
                            'mobile' =>$stud->phone,
                        );
                        $message_arr[]= $temp_arr;
                    }
                  }    
             }
             
             $data['values']=$_POST;
            $data['schedule']=0;
            

            $data['message'] =$message_arr;
            $data['msg_type']="Fee Alerts";
            $data['schedule_time']=time();         
            $_SESSION['sms_data']=$data;
            redirect("alerts/preview","refresh");
             
         }
                 
                
    }
    
    private function fetch_account_balance($section){
        $class_details =$this->db->query("SELECT s.sid , s.name as sec_name ,c.name as cls_name ,(SELECT sum(fee)   FROM `fee_class` where cls_id=c.id) as total  FROM section s JOIN class c on s.class_id=c.id where s.sid='".$section."' ");
        $class_details = $class_details->row();
        $query=  $this->db->query("SELECT s.student_id,s.phone,s.name,s.roll,s.userid,(select sum(fa.amount) from fee_accounts fa where fa.student_id=s.student_id  ) as total FROM `student` s   where s.section_id='".$section."' ORDER BY  s.student_id ASC ");
        $query=$query->result();
        $stud_data['cls_details']=$class_details;
        $stud_data['stud_details']=$query;
        return $stud_data; 
            
    }
            
    public function templates(){
          $this->load->view("alerts/templates");  
    }  
    
    public function save_template(){
        $message = $this->input->post("message");
        if(strlen($message)==0){
            echo "** Please Add Message Content ..";exit;
        }else{
            $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='".$this->input->post("type")."' ";
            $msg_ids = $this->db->query($msg_ids);
            if($msg_ids->num_rows()==0){
                $data= array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'type' =>$this->input->post("type"),
                    'msg_content' =>$message                             
                );
                $this->db->insert('message_template',$data);
            }else{
                $msg_ids =$msg_ids->row();
                $data= array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'type' =>$this->input->post("type"),
                    'msg_content' =>$message                             
                );
                $this->db->where('msg_tmp_id', $msg_ids->msg_tmp_id);
                $this->db->where('type',$this->input->post("type"));
                $this->db->update('message_template', $data); 
            }
        }
        $this->session->set_userdata('message_template_sucess', 'Template Updated Sucessfully ..'); 
        ?><script>location.reload();</script><?php
    }
   
    public function load_template($type){
        $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='".$type."' ";
        $msg_ids = $this->db->query($msg_ids);
        if($msg_ids->num_rows()==0){
            switch ($type) {
                case 1 : echo  "Dear parent , 
                         Your ward <#name#> is absent today ie . ".date("d-m-Y"); break;
                case 2 : echo "Dear parent ,
                        your Ward  <#name#> , 
                        Paid an amount of : <#paid#>
                        On : <#date_time#> and 
                        balance Left is : <#balance#> "; break;
                case 3 : echo "Dear <#name#> ,
Wish u a Many Many Happy Returns of  the day  ,
Have a wonderful birthday! "; break;
            }
        }else{
           $msg_ids =$msg_ids->row();
           echo $msg_ids->msg_content;
        }    
        
        
    }
    
    public function  send_bday_alerts(){
       $message =  $this->input->post("message");
        $date = getdate();
        if(strlen($message)==0){
            echo "** please Enter Message Content ";exit;
        }
        $date=$date['mday']."/".$date['mon'];
       $stud="SELECT * from student where bday='".$date."' AND iid='".$this->session->userdata("staff_Org_id")."'";
       $stud=$this->db->query($stud);
       $stud=$stud->result();
       $staff="SELECT * from staff  where bday='".$date."' AND iid='".$this->session->userdata("staff_Org_id")."'";
       $staff=$this->db->query($staff);
       $staff=$staff->result();
       $birthdays= array();
       foreach($stud as $val){
               $birthdays[]=array("name"=>$val->name,'phone'=>$val->phone,"role"=>"student");
       }
       foreach($staff as $val){
               $birthdays[]=array("name"=>$val->name,'phone'=>$val->phone,"role"=>"staff");
       }
        if(sizeof($birthdays)==0){
           echo "** No people Found Celebrating Birthdays Today  ";exit;
        }
        $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>1,
                    'message' =>"Birthday Wishes Alert",
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        $sms_details =  $this->fetch_sms_details();
        $this->logs->insert_staff_log(4,'Sending Birthday Alerts',$aid);
        foreach ($birthdays as $value) {
               $mesg= str_replace("<#name#>",$value['name'],$message) ;
               $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$mesg,
                        'mobile' =>$value['phone'],
                        'time' =>time(),
                        'status' =>1,
                        'alert_id' =>$aid,
                        'schedule'=>0
                );
               $this->db->insert('msg_senthistory',$data);
        }
       //  $this->session->set_userdata('birthday_msg_alert', 'Birthday Wishes Sent Sucessfully......'); 
        ?><script>
            $('#send_b_alerts').hide();
        $('#modal_body').html("<h2 class='text-center' style='color:green;'><i class='fa fa-check-circle'></i>Birthday Alerts Sent Sucessfully </h2>");
        </script><?php
        
    }
   
    
}