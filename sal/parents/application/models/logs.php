<?php  
   class logs extends CI_Model  
   {  
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
      }  
      
      public function insert_staff_log($related,$message,$record=0){
          $data=array(
              'staff_id'  =>$this->session->userdata('staff_id'),
              'iid' =>$this->session->userdata('staff_Org_id'),
              'related' =>$related,
              'message' =>$message,
              'time' =>time(),
              'record_id' =>$record
          );
          $this->db->insert('staff_logs',$data);
      }
      
   }
 ?>