<?php  
   class examinations extends CI_Model  
   {  
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
         $this->load->model('logs');
      }  
      
      public function checkexamname($name){
          $name=  strtolower($name);
           $credential = array('exam' =>$name,'iid'=>$this->session->userdata('staff_Org_id') );
                $query = $this->db->get_where('examinations', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
      
      public function check_availability($start,$end){
          
      }
      
      public function getexam_data($eid){
            $credential = array('id' =>$eid,'iid'=>$this->session->userdata('staff_Org_id') );
            $query = $this->db->get_where('examinations', $credential);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row;
            }else{
                return TRUE;
            }
      }
      
      public function add_exam($section,$examid){
          $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'examid' => $examid,
                       'sectionid' => $section,
                       'status' =>1
                    );
          $this->db->insert('examination_cls',$data);
           $id = $this->db->insert_id(); 
          $this->logs->insert_staff_log(17,'Created Examinations for '.$section,$examid);
          
          return $id;
          
      }
      
      public function add_exam_subjects($section,$ecid,$examid){
            $subjects=  $this->db->query("SELECT cid,subid  FROM `course` WHERE `secid` ='".$section."' ");
            $count=0;
            foreach($subjects->result() as $subject){
                $subject->subid;
                $data = array(
                   'iid' => $this->session->userdata('staff_Org_id'),
                   'examid' => $examid,
                   'ecid' => $ecid,
                   'subid' => $subject->subid,
                   'courseid' =>$subject->cid
                );
                
                $this->db->insert('exam',$data);
                $count++;
            }
        $this->logs->insert_staff_log(17,'Created '.$count.' for '.$section,$examid);
        
      }
      
      
      public function get_sectionids($ecid){
          $credential = array('examid' =>$ecid,'status'=>1,'iid'=>$this->session->userdata('staff_Org_id') );
          $query = $this->db->get_where('examination_cls', $credential);
          if($query->num_rows()>0){
            $secids="";
            foreach ($query->result() as $value) {
              $secids.=$value->sectionid.",";  
            }
            $secids=  substr($secids, 0,strlen($secids)-1);
            return $secids;
          }else{
              return 0;
          }
          
       }
     
      public function check_exams($section,$examid){
          $exam=  $this->db->query("SELECT * FROM `examination_cls` WHERE  `examid`='".$examid."' AND sectionid='".$section."' AND iid='".$this->session->userdata('staff_Org_id')."' ");
          $exam=$exam->row();
          $courseid=$this->db->query("SELECT group_concat(cid) as courseid FROM `course` where secid='".$section."'");
          $courseid=$courseid->row();
          $courseid=$courseid->courseid;
          $examcourseid=$this->db->query("SELECT group_concat(courseid) as courseid FROM  `exam` WHERE ecid='".$exam->id."' ");
          $examcourseid=$examcourseid->row();
          $examcourseid=$examcourseid->courseid;
          $result = array_diff(explode(',',$courseid), explode(',',$examcourseid));
          if(sizeof($result)>0){
              $count=0;
              foreach($result as $sub){
                  $data = array(
                   'iid' => $this->session->userdata('staff_Org_id'),
                   'examid' => $examid,
                   'ecid' => $exam->id,
                   'subid' => $this->return_subid($sub),
                   'courseid' =>$sub
                );
                $this->db->insert('exam',$data);
                $count++;
              }
               $this->logs->insert_staff_log(17,'Created '.$count.' for '.$section,$examid);
       
          }
      }
       
      public function deactive_exam($examid,$section){
         $data = array(
                   'status' =>0
                );
              $this->db->where('examid', $examid);
              $this->db->where('sectionid', $section);
          $this->db->update('examination_cls',$data);
           $this->logs->insert_staff_log(17,'Deleted Exam for '.$section,$examid);
      }
       
      private function  return_subid($course){
          $query=  $this->db->query("SELECT subid  FROM `course` where cid='".$course."'");
          $query=$query->row();
          return $query->subid;
      }
       
   }
 ?>