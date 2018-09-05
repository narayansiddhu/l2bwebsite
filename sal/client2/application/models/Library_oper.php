<?php  
   class library_oper extends CI_Model  
   {  
      function __construct()  
      {  
        parent::__construct();  
      }  
      
      public function books_count($search,$cat=0){
          $search=trim($search);  
          if($search=="_"){
              $search="";
          }
            if(strlen(trim($search))>0){
                $this->db->like('name', $search); 
                $this->db->or_like('author', $search); 
                $this->db->or_like('book_id', $search); 
            }
            if($cat!=0){
                $this->db->where('category', $cat);
            }
              $this->db->where('iid', $this->session->userdata('staff_Org_id'));
              $this->db->from('lib_books');
              $count= $this->db->count_all_results();
              return $count;   
      }
      
      public function request_book_count($search,$cat=0){
          $search=trim($search);  
          if($search=="_"){
              $search="";
          }
            if(strlen(trim($search))>0){
                $this->db->like('name', $search); 
                $this->db->or_like('author', $search); 
            }
            if($cat!=0){
                $this->db->where('category', $cat);
            }
              $this->db->where('iid', $this->session->userdata('staff_Org_id'));
              $this->db->from('lib_request');
              return $this->db->count_all_results();
               
      }
      
      public function fetch_request_books($search,$limit, $start,$cat=0) {
          
        $query="SELECT b.req_id,b.name,c.category,b.price,b.author,b.status FROM lib_request b JOIN book_category c on b.category=c.catid WHERE b.iid='".$this->session->userdata('staff_Org_id')."'";  
        if($cat!=0){
            $query.= "AND b.category='".$cat."' ";
        }
        if($search=="_"){
              $search="";
          }
        if(strlen($search)!=0){
          $query.= "AND ( (b.name LIKE '%".$search."%') OR (b.author LIKE '%".$search."%') )";  
         }
         $query.=" LIMIT ".$start." ,".$limit." ";
         $query=  $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
      
      public function fetch_books($search,$limit, $start,$cat=0) {
          
        $query="SELECT b.book_id,b.name,c.category,b.price,b.author,b.status FROM lib_books b JOIN book_category c on b.category=c.catid WHERE b.iid='".$this->session->userdata('staff_Org_id')."'";  
        if($cat!=0){
            $query.= "AND b.category='".$cat."' ";
        }
        if($search=="_"){
              $search="";
          }
        if(strlen($search)!=0){
          $query.= "AND ( (b.name LIKE '%".$search."%') OR (b.author LIKE '%".$search."%') OR (b.book_id LIKE '%".$search."%'))";  
         }
         $query.=" LIMIT ".($start*$limit)." ,".$limit." ";
        
         $query=  $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
      
      public function issue_count($search){
              $search=trim($search);  
             $query="SELECT li.issue_id,li.trans_id,li.issued_date,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id WHERE li.iid='".$this->session->userdata('staff_Org_id')."' ";
             if(strlen(trim($search))>0){
                $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
              }
              $query=  $this->db->query($query);
              return $query->num_rows();
             
      }
      
      public function fetch_issue($search,$limit, $start) {
        $search=trim($search);  
        if($search=='_'){
            $search="";
        }
        $query="SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid,c.name as class,se.name as section FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id JOIN class c ON s.class_id =c.id JOIN section se ON s.section_id=se.sid  WHERE li.iid='".$this->session->userdata('staff_Org_id')."'  ";
        if(strlen(trim($search))>0){
           $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
         }
        $query.= "ORDER BY li.issue_id DESC ";
        $query=  $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
     }
      
      public function Fee_count($search,$status){
              $search=trim($search);  
             $query="SELECT lf.fine_id,lf.fee,lf.time,lf.description,lf.status,li.issue_id,li.trans_id,lb.buid,lb.name as book_name,lb.price as book_price,li.student_id ,s.name as student,s.userid as admission FROM `lib_fines` lf JOIN lib_issues li ON lf.issue_id=li.issue_id join lib_books lb ON li.bookid =lb.book_id  JOIN student s ON li.student_id=s.student_id WHERE lf.iid='".$this->session->userdata('staff_Org_id')."' ";
             if(strlen($status)!=0){
                 $query.= "AND ( (lf.status LIKE  '%".$status."%') )";
             }
             if(strlen(trim($search))>0){
                $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
             }
              $query=  $this->db->query($query);
              return $query->num_rows();
             
      }
      
      public function Fee_count_issue($search,$limit, $start,$status) {
            $search=trim($search);  
            $query="SELECT lf.fine_id,lf.fee,lf.time,lf.description,lf.status,li.issue_id,li.trans_id,lb.buid,lb.name as book_name,lb.price as book_price,li.student_id ,s.name as student,s.userid as admission FROM `lib_fines` lf JOIN lib_issues li ON lf.issue_id=li.issue_id join lib_books lb ON li.bookid =lb.book_id  JOIN student s ON li.student_id=s.student_id WHERE lf.iid='".$this->session->userdata('staff_Org_id')."' ";
            if(strlen($status)!=0){
                $query.= "AND ( (lf.status LIKE  '%".$status."%') )";
            }else{
                 $query.= "AND ( (lf.status LIKE  '%1%') OR (lf.status LIKE  '%2%')  )";
            }
            
            if(strlen(trim($search))>0){
               $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
            }
        $query.= "ORDER BY li.issue_id DESC LIMIT ".$start." ,".$limit." ";
      
         $query=  $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
   }
   
   public function fee_payments($limit, $start){

            $query="SELECT lp.pay_id,lp.time,lp.amount, lf.fee , lf.description,li.trans_id,li.issue_id,s.student_id ,s.name as std_name,lb.book_id,lb.name FROM lib_payments lp JOIN lib_fines lf ON lp.fee_id = lf.fine_id JOIN lib_issues li ON lf.issue_id=li.issue_id JOIN student s ON li.student_id=s.student_id JOIN lib_books lb  ON li.bookid =lb.book_id WHERE lp.iid ='".$this->session->userdata('staff_Org_id')."' ";
            
        $query.= "ORDER BY lp.pay_id DESC LIMIT ".$start." ,".$limit." ";

         $query=  $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
       
       
   }
   
   public function fee_payments_count($search,$status){
              $search=trim($search);  
             $query="SELECT lp.pay_id,lp.time,lp.amount, lf.fee , lf.description,li.trans_id,li.issue_id,s.student_id ,s.name as std_name,lb.book_id,lb.name FROM lib_payments lp JOIN lib_fines lf ON lp.fee_id = lf.fine_id JOIN lib_issues li ON lf.issue_id=li.issue_id JOIN student s ON li.student_id=s.student_id JOIN lib_books lb  ON li.bookid =lb.book_id WHERE lp.iid ='".$this->session->userdata('staff_Org_id')."' ";
//              if(strlen($status)!=0){
//                 $query.= "AND ( (lf.status LIKE  '%".$status."%') )";
//             }
//             if(strlen(trim($search))>0){
//                $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
//             }
              $query=  $this->db->query($query);
              return $query->num_rows();
             
      }
   
   
      public function check_cat($catid){
          $query=  $this->db->query("SELECT * FROM `book_category` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND catid='".$catid."'");
          if($query->num_rows()>0){
             $query=$query->row();
             return $query;
          }else{
              return FALSE;
          }
            
      }
      
      public function check_catname($catname){
          $query=  $this->db->query("SELECT * FROM `book_category` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND category='".$catname."'");
          if($query->num_rows()>0){
             $query=$query->row();
             return $query;
          }else{
              return FALSE;
          }
            
      }
      
      public function check_book($bookid){
          $query=  $this->db->query("SELECT * FROM `lib_books` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND book_id='".$bookid."'");
          if($query->num_rows()>0){
             $query=$query->row();
             return $query;
          }else{
              return FALSE;
          }
      }
      
   }
   
?>

