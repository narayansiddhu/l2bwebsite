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
            $this->load->model('logs');
           $credential = array('email' =>$username, 'password' => $password,'status' =>'1');
                $query = $this->db->get_where('staff', $credential);
                if ($query->num_rows() > 0) {
                    
                    $row = $query->row();
                    $query="SELECT *  FROM `institutes` WHERE id='".$row->iid."' ";
                    $query = $this->db->query($query);
                    $query= $query->row();
                    $this->session->set_userdata('institute_att_type', $query->type);
                    
                    $this->session->set_userdata('staff_id', $row->id);
                    $this->session->set_userdata('staff_name', $row->name);
                    $this->session->set_userdata('staff_user', $row->email);
                    $this->session->set_userdata('staff_level', $row->level);
                    $this->session->set_userdata('staff_Org_id', $row->iid);
                    $this->logs->insert_staff_log(1,'logged in '.$_SERVER['REMOTE_ADDR']);
                    $this->logs->update_last_login();
                    return TRUE;
                   }else{
                   return FALSE;           
                }
        }
      
      public function update_last_login($id){
          $data = array(
                    'last_login' => time()
                    );
                    $id=trim($this->input->post('id'));
                    $this->db->where('id', $id);
                    $this->db->update('staff', $data);         
      }
      
      public function section_details($id){
         
         $query=  $this->db->query("SELECT GROUP_CONCAT(secid,"-",subid ) as course FROM `course` where tid='".$id."'");
         $query=$query->row();
         $this->session->set_userdata('staff_course_details', $row->course);
      }
      
      //Login_user
      public function is_login(){
         if(strlen($this->session->userdata('staff_id'))==0 ){
             redirect('Login', 'refresh'); 
         }
      }
      
      public function is_librian(){
        if($this->session->userdata('staff_level')!=5){
          redirect('Login', 'refresh'); 
        }
      }
      
      //function for visualizing Staff details 
      public function staff_count(){
            $this->db->where('iid', $this->session->userdata('staff_Org_id')); 
            $this->db->where('level <', 6); 
            $this->db->from('staff');
            $count= $this->db->count_all_results();
            return $count;
      }
      
      public function fetch_staff($limit, $start) {
        $this->db->where('iid', $this->session->userdata('staff_Org_id'));  
        $this->db->limit($limit, $start);
        $this->db->where('level <', 6); 
        $query = $this->db->get("staff");
        $this->db->join('salary', 'salary.staff_id = staff.id');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
       }
      
      public function check_cls_name($name,$clsid="",$medium=""){
          if($clsid!=""){
              $credential = array('name' =>$name ,'id !=' =>$clsid ,'iid'=>$this->session->userdata('staff_Org_id') );
          }else{
                $credential = array('name' =>$name ,'iid'=>$this->session->userdata('staff_Org_id') );
          }
         if($medium!=""){
              $credential['medium']=$medium;
          }
                $query = $this->db->get_where('class', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
      
      public function check_cls_val($val,$clsid=""){
            if($clsid!=""){
              $credential = array('numeric_val' =>$val ,'id !=' =>$clsid ,'iid'=>$this->session->userdata('staff_Org_id') );
          }else{
                $credential = array('numeric_val' =>$val,'iid'=>$this->session->userdata('staff_Org_id'));
          }
               
                $query = $this->db->get_where('class', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
      
      public  function cleanInput($post = array())
	{
		   foreach($post as $k => $v){
				$post[$k] = trim(htmlspecialchars($v));
			 }
			 return $post;
	}
    
      public function check_section_name($cls,$sec,$secid=""){
                $sec=  strtolower($sec);
                if($secid!=""){
                    $credential = array('class_id' =>$cls ,'name'=>$sec,'sid !='=>$secid);
                }else{
                    $credential = array('class_id' =>$cls ,'name'=>$sec);
                }
                
                $query = $this->db->get_where('section', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      } 
      
      public function check_teacher_avail($cls,$teacher){
                $sec=  strtolower($sec);
                $credential = array('class_id' =>$cls ,'cls_tch_id'=>$teacher);
                $query = $this->db->get_where('section', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
    
      public function Fetch_cls_structure(){
       $query = $this->db->query("SELECT s.sid,s.name,s.cls_tch_id,c.name as cls_name,c.numeric_val,st.name as staff_name FROM `section` s Join class c ON s.class_id=c.id LEFT JOIN staff st ON s.cls_tch_id=st.id  WHERE c.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC");
       if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
      
      public function check_subject($name){
          $credential = array('subject' =>$name ,'iid'=>$this->session->userdata('staff_Org_id') );
                $query = $this->db->get_where('subjects', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
      
       public function check_edit_subject($name,$id){
        $credential = array('subject' =>$name,'sid != '=>$id,'iid'=>$this->session->userdata('staff_Org_id') );
                $query = $this->db->get_where('subjects', $credential);
                //echo $query->num_rows() ;
                if ($query->num_rows() > 0) {
                    return FALSE;
                }else{
                    return TRUE;
                }
      }
       
      public function check_course($sub,$section){
            $credential = array('subid' =>$sub ,'secid' =>$section );
            $query = $this->db->get_where('course', $credential);
            
            if ($query->num_rows() > 0) {
                return FALSE;
            }else{
                return TRUE;
            }   
      }
    
      public function fee_check($cls,$cat){
          $credential = array('cls_id' =>$cls,'category' =>$cat);
            $query = $this->db->get_where('fee_class', $credential);
            if ($query->num_rows() > 0) {
                return FALSE;
            }else{
                return TRUE;
            } 
      }
      
      public function course_structure($class){          
            if($this->check_cls_val($class)){
               return "false";
            }
            $cls_id=  $this->get_class_id($class);
            $query = $this->db->query("SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.class_id='".$cls_id."' ORDER BY sec.sid , s.sid ASC");
            return $query->result();
            
      }
      
      public function get_class_id($cls){
            $credential = array('numeric_val' =>$cls,'iid'=>$this->session->userdata('staff_Org_id'));
            $query = $this->db->get_where('class', $credential);
            //echo $query->num_rows() ;
            if ($query->num_rows() > 0) {
                $row = $query->row();
                return $row->id;
            }else{
                return TRUE;
            }
      }
      
      public function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url)
        {
            $pagination = '';
            if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
                $pagination .= '<ul class="pagination">';

                $right_links    = $current_page + 3; 
                $previous       = $current_page - 3; //previous link 
                $next           = $current_page + 1; //next link
                $first_link     = true; //boolean var to decide our first link

                if($current_page > 1){
                    $previous_link = ($previous==0)?1:$previous;
                    $pagination .= '<li class="first"><a href="'.$page_url.'?page=1" title="First">&laquo;</a></li>'; //first link
                    $pagination .= '<li><a href="'.$page_url.'?page='.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
                        for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                            if($i > 0){
                                $pagination .= '<li><a href="'.$page_url.'?page='.$i.'">'.$i.'</a></li>';
                            }
                        }   
                    $first_link = false; //set first link to false
                }

                if($first_link){ //if current active page is first link
                    $pagination .= '<li class="first active">'.$current_page.'</li>';
                }elseif($current_page == $total_pages){ //if it's the last active link
                    $pagination .= '<li class="last active">'.$current_page.'</li>';
                }else{ //regular current link
                    $pagination .= '<li class="active">'.$current_page.'</li>';
                }

                for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                    if($i<=$total_pages){
                        $pagination .= '<li><a href="'.$page_url.'?page='.$i.'">'.$i.'</a></li>';
                    }
                }
                if($current_page < $total_pages){ 
                        $next_link = ($i > $total_pages)? $total_pages : $i;
                        $pagination .= '<li><a href="'.$page_url.'?page='.$next_link.'" >&gt;</a></li>'; //next link
                        $pagination .= '<li class="last"><a href="'.$page_url.'?page='.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
                }

                $pagination .= '</ul>'; 
            }
            return $pagination; //return pagination links
        }
      
     public function paginate1 ($base_url, $query_str, $total_pages, $current_page, $paginate_limit)
        {
            // Array to store page link list
            $page_array = array ();
            // Show dots flag - where to show dots?
            $dotshow = true;
            // walk through the list of pages
            for ( $i = 1; $i <= $total_pages; $i ++ )
            {
               // If first or last page or the page number falls 
               // within the pagination limit
               // generate the links for these pages
               if ($i == 1 || $i == $total_pages || 
                     ($i >= $current_page - $paginate_limit && 
                     $i <= $current_page + $paginate_limit) )
               {
                  // reset the show dots flag
                  $dotshow = true;
                  // If it's the current page, leave out the link
                  // otherwise set a URL field also
                  if ($i != $current_page)
                      $page_array[$i]['url'] = $base_url . "?" . $query_str .
                                                 "=" . $i;
                  $page_array[$i]['text'] = strval ($i);
               }
               // If ellipses dots are to be displayed
               // (page navigation skipped)
               else if ($dotshow == true)
               {
                   // set it to false, so that more than one 
                   // set of ellipses is not displayed
                   $dotshow = false;
                   $page_array[$i]['text'] = "...";
               }
            }
            // return the navigation array
            return $page_array;
        }
        
   }
?>
