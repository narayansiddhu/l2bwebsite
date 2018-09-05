<?php  
   class validations extends CI_Model  
   {  
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
         
         
      }  
      //we will use the select function  
      public function validate_mobile($mobile){
          $mobile = stripslashes($mobile);
        if(!is_numeric($mobile)  || strlen($mobile)!=10 || !preg_match("~^([7-8-9]{1}[0-9]{9})+$~", $mobile))
		return 0;
		else
		return 1; 
      }
      
      public function validate_email($mail){
          if (!filter_var($mail, FILTER_VALIDATE_EMAIL) === false) {
               return 1;
              } else {
                return 0;
              }
      }
      
      
      public function check_teacher_mobile($mobile){
          $credential = array('phone' =>$mobile ,'iid'=>$this->session->userdata('staff_Org_id'));
          $query = $this->db->get_where('staff', $credential);
          if($query->num_rows()>0 ){
              return FALSE;
          }  else{
              return TRUE;
          }
      }
      
      public function check_teacher_email($mail){
          $credential = array('email' =>$mail);
          $query = $this->db->get_where('staff', $credential);
          if($query->num_rows()>0 ){
              return FALSE;
          }  else{
              return TRUE;
          }
      }
      
      public function generate_password(){
          $password=  rand(100001, 999999);
          return $password;
      }
      
      
      
      
      
   }  
?> 