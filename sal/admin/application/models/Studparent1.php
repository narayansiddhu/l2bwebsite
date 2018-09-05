<?php  
   class studparent extends CI_Model  
   {  
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
      }  
      
      public function checkemail($email){
           $credential = array('email' =>$email,'iid'=>$this->session->userdata('staff_Org_id') );
                $query = $this->db->get_where('parent', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
      
      public function checkmobile($phone){
           $credential = array('phone' =>$phone ,'iid'=>$this->session->userdata('staff_Org_id') );
            $query = $this->db->get_where('parent', $credential);
            //echo $query->num_rows() ;
            if ($query->num_rows() > 0) {
                return FALSE;
            }else{
                return TRUE;
            }
      }
      
      public function generate_pass(){
          $rand=  rand(100001, 999999);
          return $rand;
      }
      
      public function parent_count($key){
            
           $this->db->where('iid', $this->session->userdata('staff_Org_id')); 
           $key=trim($key);
           if(strlen($key)>0 ){
              $this->db->like('name', $key); 
              $this->db->or_like('email', $key); 
              $this->db->or_like('address', $key); 
              $this->db->or_like('phone', $key); 
           }
            $this->db->from('parent');
            $count= $this->db->count_all_results();
            return $count;
      }
      
      public function fetch_parents($limit, $start,$key) {

          $query="SELECT * FROM `parent` where iid ='".$this->session->userdata('staff_Org_id')."'";  
          $query.=" LIMIT ".$start." , ".$limit." ";
         // echo $query;
           $query=  $this->db->query($query);
            if ($query->num_rows() > 0) {
                 return $query->result();
             }
        
         
       
      }
      
      public function student_count(){
            $this->db->where('iid', $this->session->userdata('staff_Org_id')); 
            $this->db->from('parent');
            $count= $this->db->count_all_results();
            return $count;
      }
      
      public function fetch_student($limit, $start) {
         
        $this->db->limit($limit, $start);
        $this->db->where('iid', $this->session->userdata('staff_Org_id')); 
        $query = $this->db->get("parent");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
      }
      
      public function send_sms($msg,$mobile){
          $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>4,
                    'message' =>"Sending Login credentials",
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
          $sms_details=  $this->fetch_sms_details();
          $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'username'=>$sms_details->username,
                    'password' =>$sms_details->password,
                    'senderid' =>$sms_details->senderid,
                    'message'  =>$msg,
                    'mobile' =>$mobile,
                    'time' =>time(),
                    'status' =>1,
                    'alert_id' =>$aid,
                );
        $this->db->insert('msg_senthistory',$data);

      }
      
      public function checkstdemail($email,$studid=""){
        return TRUE;
//        $studid=trim($studid);
//        $query="SELECT * from student where email='".$email."' AND iid='".$this->session->userdata('staff_Org_id')."'";
//         if(strlen($studid)>0){
//            $query.="AND student_id !='".$studid."' ";
//         } 
//          $query = $this->db->query($query);
//       
//        if ($query->num_rows() > 0) {
//            return FALSE;
//        }else{
//            return TRUE;
//        }
      }
      
      public function checkstdmobile($phone,$studid=""){
          return TRUE;
//          $studid=trim($studid);
//          $query="SELECT * from student where phone='".$phone."' AND iid='".$this->session->userdata('staff_Org_id')."'";
//          if(strlen($studid)>0){
//              $query.="AND student_id !='".$studid."' ";
//         } 
//        
//            $query = $this->db->query($query);
//           
//             if ($query->num_rows() > 0) {
//                return FALSE;
//            }else{
//                return TRUE;
//            }
      }
      
      public function checkstdroll($cls,$sec,$roll,$stud=""){
          if($stud!=""){
              $credential = array('class_id' =>$cls ,'iid'=>$this->session->userdata('staff_Org_id'),'section_id'=>$sec,'roll'=>$roll,'student_id !=' =>$stud );
          }else{
           $credential = array('class_id' =>$cls ,'iid'=>$this->session->userdata('staff_Org_id'),'section_id'=>$sec,'roll'=>$roll );
          } 
         
           $query = $this->db->get_where('student', $credential);
            //echo $query->num_rows() ;
            if ($query->num_rows() > 0) {
                return FALSE;
            }else{
                return TRUE;
            }
      }
      
      public function cls_section($cls,$section){
            $credential = array('class_id' =>$cls ,'iid'=>$this->session->userdata('staff_Org_id'),'sid'=>$section );
            $query = $this->db->get_where('section', $credential);
            //echo $query->num_rows() ;
            if ($query->num_rows() > 0) {
                return TRUE;
            }else{
                return FALSE;
            }
      }
      
      public function get_admission_no(){
        $query = $this->db->query("SELECT `last_id` FROM `admission` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
        $result=$query->row();
        return $result->last_id;            
      }
      
      public function update_admission_no($no){
        $this->db->query("UPDATE `admission` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");  
      }


      public function get_institute_code(){
        $query = $this->db->query("SELECT `code` FROM `institute_code` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
        $result=$query->row();
        return $result->code;   
      }
      
      public function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        
        return $msg;
    }
      
   }
?>