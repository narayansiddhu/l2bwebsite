<?php  
   class operations extends CI_Model  
   {  
      
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
      }  
      
      //Login check for the users 
      public function validate_user($username,$password){
          
            $credential = array('email' =>$username, 'password' => $password);
            $query = $this->db->get_where('parent', $credential);

            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->session->set_userdata('parent_id', $row->parent_id);
                $this->session->set_userdata('parent_org_id', $row->iid);
                $this->session->set_userdata('parent_name', $row->name);
                $this->session->set_userdata('parent_email', $row->email);
                $this->fetch_students();
                return TRUE;
               }else{
               return FALSE;           
            }
        }
            
      //Login_user
      public function is_login(){
         if(strlen($this->session->userdata('parent_id'))==0 ){
             redirect('Login', 'refresh'); 
         }
      }
      
      public function fetch_students(){
            $credential = array('parent_id' =>$this->session->userdata('parent_id'));
           
            $query = $this->db->get_where('student', $credential);
            $query=$query->result();
            $this->session->set_userdata('students_arr', $query);  
      }
      
      public  function cleanInput($post = array())
	{
		   foreach($post as $k => $v){
				$post[$k] = trim(htmlspecialchars($v));
			 }
			 return $post;
	}
      public function validate_mobile($mobile){
          $mobile = stripslashes($mobile);
        if(!is_numeric($mobile)  || strlen($mobile)!=10 || !preg_match("~^([7-8-9]{1}[0-9]{9})+$~", $mobile))
		return 0;
		else
		return 1; 
      }
      
        
   }
?>
