<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class library extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('library_oper');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->operations->is_login();
            $this->operations->is_librian();
            /* cache control */
            
            $check="SELECT `library` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
        $check = $this->db->query($check)->row();
        if($check->library==0){
             $this->session->set_userdata('blocked_module', 'Library Module'); 
            redirect("/Acessdenied/","refresh");
        }
        }
        
	public function index()
	{
           if(is_null($this->session->userdata('staff_id'))){
             $this->load->view('login');
           }else{
               $this->load->view('library/index');
           }	   
	}
        
        public function brief_report(){
            $this->load->view('library/category_base');
        }
        
        
        public function category($cat=""){
            $cat=trim($cat);
            if(strlen($cat)==0){
                $cat=array();
                $data['error']="";
                if(isset($_GET['edit'])){
                 $q=  base64_decode($_GET['edit']);
                 $k= $this->library_oper->check_cat($q);
                  if(!$k){
                      $data['error']="Invaalid entry";
                  }else{

                     $cat['category']= $k->category;
                     $cat['catid']= $k->catid;
                  }
                }
                $data['cat']=$cat;
                $this->load->view('library/category',$data);
            }else{
                $cat= urldecode($cat);
                $k= $this->library_oper->check_catname($cat);
                if(!$k){
                    $cat=array();
                      $data['error']="";
                      $data['error']="Invaalid entry";
                      $data['cat']=array();
                      $this->load->view('library/category',$data);
                   }else{
                    $data['category']=$k;
                    $books=$this->db->query("SELECT * FROM `lib_books` where category ='".$k->catid."' ")->result();
                    $data['books']=$books;
                    $this->load->view('library/view_cat_books',$data);  
                  }
            }
            
        }
        
        public function issue(){
          $this->load->view('library/issue');
        }
        
        public function issue_list($search=""){
            if(isset($_GET['q'])){
              $search=trim($_GET['q']);  
            }else{
               $search="_"; 
            }
            $data["results"] = $this->fetch_issue($search);
            
            $this->load->view('library/issue_list',$data);
        }
        
        public function fetch_issue($search="") {
        $search=trim($search); 
        $query="SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid,c.name as class,se.name as section FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id JOIN class c ON s.class_id =c.id JOIN section se ON s.section_id=se.sid  WHERE li.iid='".$this->session->userdata('staff_Org_id')."'  ";
        if(strlen(trim($search))>0){
           $query.= "AND ( (li.trans_id LIKE  '%".$search."%') OR ( s.name LIKE  '%".$search."%' ) OR ( s.userid LIKE  '%".$search."%' ) OR ( lb.name LIKE  '%".$search."%' ) )";
         }
        $query.= "ORDER BY li.issue_id DESC ";
        $query=  $this->db->query($query)->result();
        return $query;
       // print_r($query);
     }
      
        
        public function return_list(){
            $search="";
            if(isset($_GET['q'])){
              $search=trim($_GET['q']);  
            }
            $config = array();
            $config["base_url"] = base_url() ."index.php/library/books";
            $config["total_rows"] = $this->library_oper->issue_count($search);
            $config["per_page"] = 25;
            $config["uri_segment"] = 3;
            
            $this->pagination->initialize($config);

            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["results"] = $this->library_oper->fetch_issue($search,$config["per_page"],$page);
            $data["links"] = $this->pagination->create_links();
            $data['page']=$page;
            $data['per_page']=$config["per_page"];
             $this->load->view('library/issue_list',$data);
        }
        
        public function issue_new(){
            
            $student=$this->input->post('student');
            $book=$this->input->post('book');
            $count=0;
            if($this->check_availability($book)!=1){
               $count++;
                echo "Book Unavailable";
            }
            
            if($this->check_availability($book)!=1){
                $count++;
                echo "Book Unavailable";
            }
//            if($this->check_user_book_count($student)>3){
//               $count++;
//               echo "Student Reached Max books"; 
//            }
            if($count==0){
                $n=  $this->get_transactionid()+1;
                $no=  $this->make_5digit($n);
                $trans=  $this->fetch_institute_code()."lbtr".$no;

                  $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'trans_id'=>$trans,
                    'bookid' =>$book,
                    'student_id'=>$student, 
                    'issued_date' =>time(),
                    'return_date'=>0,
                      'status'=>1
                    );
                  
                  $this->db->insert('lib_issues',$data);
                  $this->logs->insert_staff_log(22,'Issued New Book with transid: '.$trans,  $this->db->insert_id() );
                  $this->update_transactionid($n);$this->update_availabity($book, 2);
                  $this->session->set_userdata('book_issue_sucess', "Book Issued With Transactionid : ".$trans);
                  ?><script>location.reload();</script><?php
            }
            
            
        }
        
        public function books($page=""){
            $q="";
            $search ="";
            $cat ="";
            if(isset($_GET['q'])){
              $search=trim($_GET['q']);  
            }else{
                $search="";
            }
            if(isset($_GET['cat'])){
              $cat=trim($_GET['cat']);  
            }else{
                $cat="";
            }
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
         $query=  $this->db->query($query)->result();
        $data['results']=$query;
            $this->load->view('library/books',$data);
        }
        
        public function view_book($book_id=""){
           $book_id=trim($book_id); 
           if(strlen($book_id)==0){
               redirect('library/books', 'refresh');    
           }else{
               $k= $this->library_oper->check_book($book_id);
                if(!$k){
                     redirect('library/books', 'refresh');    
                }else{
                    $data['book']=$k;
                    $this->load->view('library/view_book_info',$data);
                } 
           }
        }
        
        public function edit_book($book_id=""){
           $book_id=trim($book_id); 
           if(strlen($book_id)==0){
               redirect('library/add_book', 'refresh');    
           }else{
               $k= $this->library_oper->check_book($book_id);
                if(!$k){
                     redirect('library/add_book', 'refresh');    
                }else{
                    $data['book']=$k;
                    $this->load->view('library/editbook',$data);
                } 
           }
        }
        
        public function books_new(){
          $this->load->view('library/addbooks');
        }
        
        public function editbook(){
           
            $post=$this->operations->cleanInput($_POST);
            
            //setError
            $field = 'title';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Title/Book Name');
            }
            
            $field = 'category';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Select category');
            }
            
            $field = 'amount';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Price');
            }elseif(!is_numeric($post[$field])){
                $this->form->setError($field,'* Invalid Book Price');
            }
            
            $field = 'author';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Author');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{
                $data = array(
                         'name' => $post['title'],
                         'category' => $post['category'],
                         'price' => $post['amount'],
                         'author' => $post['author'],
                       );
                $this->db->where('book_id', $this->input->post('bookid'));
                $this->db->update('lib_books',$data);      
                $this->logs->insert_staff_log(20,'Edited Book '.$post['title'],  $this->input->post('bookid') );
                $this->session->set_userdata('book_edit_Sucess', 'Sucessfully Updated Book Details'); 
            }
       redirect('library/edit_book/'.$this->input->post('bookid'), 'refresh');    
            	
       }
        
        public function add_book(){
           
            $post=$this->operations->cleanInput($_POST);
            //setError
            $field = 'title';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Title/Book Name');
            }
            
            $field = 'category';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Select category');
            }
            
            $field = 'amount';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Price');
            }elseif(!is_numeric($post[$field])){
                $this->form->setError($field,'* Invalid Book Price');
            }
            
            $field = 'author';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Author');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{
                $n=  $this->get_book_no()+1;
                $no=  $this->make_5digit($n);
                $inv=  $this->fetch_institute_code()."lbk".$no;
               
                $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'buid'=>$inv,
                        'name' => $post['title'],
                        'category' => $post['category'],
                        'price' => $post['amount'],
                        'author' => $post['author'],
                        'status'=>1                        
                        );
                $this->db->insert('lib_books',$data);      
                $this->logs->insert_staff_log(20,'Created A new Book ',  $this->db->insert_id() );
                
                $this->update_book_no($n);
                $this->session->set_userdata('book_add_Sucess', 'Book Created with id :'.$inv); 
            }
        redirect('library/books_new', 'refresh');    
            	
       }
        
        public function add_category(){
           $cat=  $this->input->post('category');
            $action=  $this->input->post('action');
            if($action=='add'){
                if(!$this->check_category($cat,0)){
                    echo "<br/>Category name already Exists";
                }else{
                    $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'category'=>$cat,
                    );
                   $this->db->insert('book_category',$data);
                   $this->session->set_userdata('book_category_sucess', "Sucessfully Addded Category");
                   $this->logs->insert_staff_log(21,'Created A  Book Category ',  $this->db->insert_id() );
                   ?><script>location.reload();</script><?php
                }
            }else if($action=='update'){
               if(!$this->check_category($cat,  $this->input->post('catid'))){
                    echo "<br/>Category name already Exists";
                }else{
                   $data=array(
                         'category'=>$cat,
                     );
                    $this->db->where('catid', $this->input->post('catid'));
                    $this->db->update('book_category', $data);
                    $this->logs->insert_staff_log(21,'Edited  Book Category '.$cat,  $this->input->post('catid'));
                    $this->session->set_userdata('book_category_sucess', "Category Updated Sucessfully");
                    ?><script>location.reload();</script><?php
                } 
            }
        }
        
        public function request(){
             $this->load->view('library/request_book');
        }
        
        public function view_request(){
            
            if(isset($_GET['q'])){
              $search=trim($_GET['q']);  
            }else{
                $search="_";
            }
            $r="SELECT b.req_id,b.name,c.category,b.price,b.author,b.status FROM lib_request b JOIN book_category c on b.category=c.catid WHERE b.iid='".$this->session->userdata('staff_Org_id')."'";  
            $r = $this->db->query($r)->result();
            $data["results"] = $r;
            $this->load->view('library/view_all_request',$data);
             
        }
        
        public function add_request(){
            
           $post=$this->operations->cleanInput($_POST);
            //setError
            $field = 'rtitle';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Provide Title/Book Name');
            }
            
            $field = 'rcategory';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Select category');
            }
            
            $field = 'ramount';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Price');
            }elseif(!is_numeric($post[$field])){
                $this->form->setError($field,'* Invalid Book Price');
            }
            
            $field = 'rauthor';
          
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Book Author');
            }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{
                
                $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'name' => $post['rtitle'],
                        'category' => $post['rcategory'],
                        'price' => $post['ramount'],
                        'author' => $post['rauthor'],
                        'status'=>1                        
                        );
                $this->db->insert('lib_request',$data);      
                $this->logs->insert_staff_log(26,'Requested For New Book ',  $this->db->insert_id() );
                $this->session->set_userdata('rbook_add_Sucess', "Sucessfully Requested For new Book"); 
            }
        redirect('library/request', 'refresh');    
            	 
        }
        
        public function search(){
            $this->load->view('library/search');  
        }
        
        public function view_issue($issueid=0){
            if($issueid==0){
                $this->load->view('library/submit');  
            }else{
                $issue=  $this->check_issueid($issueid);
                if(!$issue){
                    $this->load->view('library/submit');  
                }else{
                   $data['issue']=$issue;
                   //view_issue
                    $this->load->view('library/view_issue',$data);  
                }
            }
            
        }
        
        public function submit(){
          $this->load->view('library/submit');  
        }
        
        public function on_return(){
            $issueid=  $this->input->post('issueid');
            $fine=  $this->input->post('fine');
            $book=$this->input->post('book_id');
            if(!is_numeric($fine)){
                echo "Enter Numeric value";
            }else{
                $data=array(
                    'return_date'=>time(),
                    'status'=>2,
                    'fine'=>$fine
                );
               $this->db->where('issue_id', $issueid);
               $this->db->update('lib_issues', $data);
               $transid=  $this->get_trans_id($issueid);
               $this->logs->insert_staff_log(22,'Returned Book With Transid:'.$transid, $issueid);
                  
               if($fine>0){
                 $data=array(
                    'iid'=>$this->session->userdata('staff_Org_id'),
                    'issue_id'=>$issueid,
                    'fee'=>$fine,
                    'time'=>time(),
                    'description'=>'On Submittion',
                    'status'=>1
                   );
                 $this->db->insert('lib_fines',$data);  
                 $this->logs->insert_staff_log(23,'Imposed Fine for Transid:'.$transid, $this->db->insert_id());
               } 
               
               $this->update_availabity($book, 1);
               $this->session->set_userdata('issues_update', "Updated Transaction");
           
                ?><script>location.reload();</script><?php
            }
        }
        
        public function get_results(){
            
            $book=  $this->input->post('book');
            $section=  $this->input->post('section');
            $student=  $this->input->post('student');
            $transid= trim($this->input->post('search'));
            $query="SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid,c.name as class,se.name as section FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id JOIN class c ON s.class_id =c.id JOIN section se ON s.section_id=se.sid  WHERE li.iid='".$this->session->userdata('staff_Org_id')."'  ";
            
            if(strlen($student)>0){
                $query.= "AND s.student_id='".$student."'  ";
            }
            if(strlen($transid)>0){
                $query.= "AND li.trans_id LIKE  '%".$transid."%' ";
            }
            if(strlen($section)>0){
                $query.= "AND se.sid='".$section."'  ";
            }
            if(strlen($book)>0){
                $query.= "AND li.bookid =  '".$book."' ";
            }
            
            $query=  $this->db->query($query);
            if($query->num_rows()>0){
               $query=$query->result();$i=1;
               foreach ($query as $value) {
                  ?>
                   <tr>
                       <td> <?php echo $i++; ?> </td>
                       <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $value->issue_id ?>" ><?php echo $value->trans_id ?></a></td>
                       <td><?php echo $value->studname ?></td>
                       <td><?php echo $value->book ?></td>
                       <td><?php echo $value->class ?></td>
                       <td><?php echo $value->section ?></td>
                       <td><?php echo date('d-m-y H:i',$value->issued_date); ?></td>
                       <td><?php 
                              if($value->status==1){
                                  echo "Issued";
                              }else{
                                  echo "returned";
                              }?></td>
                       <td><?php 
                              if($value->status==1){
                                  echo "--";
                              }else{
                                  echo date('d-m-y H:i',$value->return_date);
                              }?></td>
                       <td><?php 
                              if($value->status==1){
                                  echo "--";
                              }else{
                                  echo $value->fine;
                              }?></td>
                   </tr>
                 <?php
                } 
            }else{
                ?>
                   <tr><td colspan="8" > No Records Found
                       </td>
                   </tr>    
                <?php
            }
        }

        public function fetch_result(){
            $student=  $this->input->post('student');
            $transid= trim($this->input->post('transid'));
            $query="SELECT li.issue_id,li.trans_id,li.issued_date,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id WHERE li.iid='".$this->session->userdata('staff_Org_id')."' AND li.status=1 ";
            if(strlen($student)>0){
                $query.= "AND s.student_id='".$student."'  ";
            }
            if(strlen($transid)>0){
                $query.= "AND li.trans_id LIKE  '%".$transid."%' ";
            }
            
            $query=  $this->db->query($query);
            if($query->num_rows()>0){
               $query=$query->result();$i=1;
               foreach ($query as $value) {
                  ?>
                   <tr>
                       <td> <?php echo $i++; ?> </td>
                       <td><?php echo $value->trans_id ?></td>
                       <td><?php echo $value->studname ?></td>
                       <td><?php echo $value->admissionid ?></td>
                       <td><?php echo $value->book ?></td>
                      <td><?php echo date('d-m-y H:i',$value->issued_date); ?></td>
                      <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $value->issue_id ?>" >Return</a></td>
                   </tr>
                 <?php
                } 
            }else{
                ?>
                   <tr><td colspan="8" > No Records Found
                       </td>
                   </tr>    
                <?php
            }
            
        }
        
        public function add_fine(){
           $data=array(
                    'iid'=>$this->session->userdata('staff_Org_id'),
                    'issue_id'=>  $this->input->post('issueid'),
                    'fee'=>$this->input->post('fine'),
                    'time'=>time(),
                    'description'=>$this->input->post('reason'),
                    'status'=>1
                   );
            $this->db->insert('lib_fines',$data);  
            $transid=  $this->get_trans_id($issueid);
            $this->logs->insert_staff_log(23,'Imposed Fine for Transid:'.$transid, $this->db->insert_id() );
            $this->session->set_userdata('issues_update', "Fee Added Sucessfully");
           
            ?><script>location.reload();</script><?php
            
            
        }
        
        public function view_all_payments(){
            $search=  "";
            if(isset($_GET['q'])){
              $search = trim($_GET['q']);
            }
            $status="";
            
            $config = array();
            $config["base_url"] = base_url() ."index.php/library/view_all_payments/";
            $url_part ="";
            if(strlen($search)!=0){
                $url_part ="?q=".$search."&"; 
            }
                
            $config["search_query"]=$url_part;
            $config["total_rows"] = $this->library_oper->fee_payments_count($search,$status);
            $config["per_page"] = 15;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["results"] = $this->library_oper->fee_payments($config["per_page"],$page);
            $data["links"] = $this->pagination->create_links();
            $data['page']=$page;
            
            $data['per_page']=$config["per_page"];
            
            $this->load->view('library/allfees',$data);
        
        }
        
        public function payments(){
            $search="";
            if(isset($_GET['q'])){
              $search=trim($_GET['q']);  
            }
            $status="";
            
            $config = array();
            $config["base_url"] = base_url() ."index.php/library/payments";
            $config["total_rows"] = $this->library_oper->Fee_count($search,$status);
            $config["per_page"] = 25;
            $config["uri_segment"] = 3;
            
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["results"] = $this->library_oper->Fee_count_issue($search,$config["per_page"],$page,$status);
            $data["links"] = $this->pagination->create_links();
            $data['page']=$page;
            $data['per_page']=$config["per_page"];
            $this->load->view('library/fees',$data);
        
        }
        
        public function pay($feeid=""){
            $feeid=trim($feeid);
            if(strlen($feeid)==0){
                redirect('library/payments', 'refresh');
            }else{
                $r=  $this->check_fee_id($feeid);
                if(!$r){
                   redirect('library/payments', 'refresh'); 
                }else{
                    $data['fee']=$r;
                    $data['book']= $this->check_issueid($r->issue_id);
                    $this->load->view('library/pay',$data);
                }
            }
            
        }
        
         private function make_9digit($id){
          if(strlen($id)>5){
              return $id;
          }
          $str="";
          $len=5-strlen($id);
          for($i=0;$i<$len;$i++){
             $str.="0";
          }
          $str.=$id;
          return $str;
        }
    
        
        public function make_payment(){
            $amount=  $this->input->post('amount');
            if(!is_numeric($amount)){
                echo "Please enter numeric value";
            }else{
                $n=  $this->get_invoice_no()+1;
         $no=  $this->make_9digit($n);
         $inv=  $this->fetch_institute_code()."libinv".$no;
         
              $data=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                     'fee_id'=>  $this->input->post('fine_id'),
                     'invoice'=>$inv,
                     'amount'=>$amount,
                     'time' =>time()
                );  
                $this->db->insert('lib_payments',$data);
                $n++; 
                $this->update_invoice_no($n-1); 
                $this->logs->insert_staff_log(23,'Payment Done for amount of :'.$amount, $this->db->insert_id() );           
                $this->session->set_userdata('lib_payments_sucess', "Payment Paid Sucessully");
                if( $amount < $this->input->post('max_fine')){
                  $status=2;
                }elseif($amount == $this->input->post('max_fine')){
                    $status=3;
                }
                $this->update_fine($this->input->post('fine_id'),$status);
                ?><script>location.reload();</script><?php
            }
        }
        
        private function check_category($cat,$id=0){
           if($id==0){
              $query=  $this->db->query("SELECT * FROM `book_category` WHERE `category`='".$cat."' AND iid='".$this->session->userdata('staff_Org_id')."'");
           }else{
                $query=  $this->db->query("SELECT * FROM `book_category` WHERE `category`='".$cat."' AND iid='".$this->session->userdata('staff_Org_id')."' AND catid!='".$id."'");
           }
           if($query->num_rows()>0){
               return FALSE;
           }else{
               return TRUE;
           }
        }
        
        private function get_book_no(){
            $query = $this->db->query("SELECT `last` FROM `book_id` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=1 ");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'start'=>1,
                    'last'=>1,
                    'type'=>1
                    );
                $this->db->insert('book_id',$data);
                return 0;
            }else{
                $result=$query->row();
                return $result->last;     
            }          
      }
      
        private function make_5digit($id){
          if(strlen($id)>5){
              return $id;
          }
          $str="";
          $len=5-strlen($id);
          for($i=0;$i<$len;$i++){
             $str.="0";
          }
          $str.=$id;
          return $str;
        }  
      
        private function update_book_no($no){
          $this->db->query("UPDATE `book_id` SET `last`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=1  ");  
        }
        
        private function get_transactionid(){
            $query = $this->db->query("SELECT `last` FROM `book_id` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=2 ");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'start'=>1,
                    'last'=>1,
                    'type'=>2
                    );
                $this->db->insert('book_id',$data);
                return 0;
            }else{
                $result=$query->row();
                return $result->last;     
            }          
      }
        
        private function update_transactionid($no){
          $this->db->query("UPDATE `book_id` SET `last`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=2  ");  
        }
        
        private function fetch_institute_code(){
        $query = $this->db->query("SELECT `code` FROM `institute_code` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
        $result=$query->row();
        return $result->code;   
      }
      
        private function get_invoice_no(){
                $query = $this->db->query("SELECT `last_id` FROM `other_invoice` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=3");
                if($query->num_rows()==0){
                    $data=array(
                        "iid" =>$this->session->userdata('staff_Org_id'),
                        'start'=>1,
                        'type'=>3,
                        'last_id'=>0
                        );
                    $this->db->insert('other_invoice',$data);
                    return 0;
                }else{
                    $result=$query->row();
                    return $result->last_id;     
                }          
          }
          private function fetch_institute_details(){
                 $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
                  return  $query->row();
            }
        private function update_invoice_no($no){
          $this->db->query("UPDATE `other_invoice` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=3  ");  
        }
    
      
        private function check_availability($book){
            $query=  $this->db->query("SELECT status FROM `lib_books` WHERE  book_id='".$book."' ");
            $query=$query->row();
            return $query->status;
        }
        
        private function update_availabity($book,$status){
            $data=array(
                    'status'=>$status,
                      );
                $this->db->where('book_id', $book);
             $this->db->update('lib_books', $data); 
        }
        
        private function check_user_book_count($student){
            //
            $query=  $this->db->query("SELECT count(*) as counter FROM `lib_issues` WHERE `student_id` ='".$student."'");
            $query=$query->row();
            return $query->counter;
            
        }
        
        private function check_issueid($id){
            $query=  $this->db->query("SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.status,li.fine,s.name as studname,s.userid as admissionid,lb.name as book,lb.book_id,lb.buid,lb.price,lb.author,c.name as class,se.name as section,bc.category FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id=se.sid JOIN book_category bc ON lb.category=bc.catid   WHERE li.`issue_id` ='".$id."' AND li.iid='".$this->session->userdata('staff_Org_id')."' ");
            if($query->num_rows()>0){
               $query=$query->row(); 
               return $query;
            }else{
                return FALSE;
            }
        }
       
        private function fetch_book_info($bookid){
            
            $query=  $this->db->query("SELECT lb.book_id,lb.buid,lb.name as book ,lb.price,lb.author,lb.status,lb.category FROM `lib_books` lb JOIN book_category c ON lb.category =c.catid WHERE lb.iid='".$this->session->userdata('')."' AND lb.book_id='".$bookid."'");
            
            
        }
        
        private function check_fee_id($feeid){
            $query=  $this->db->query("SELECT *  FROM `lib_fines` WHERE `fine_id` = '".$feeid."' AND iid='".$this->session->userdata('staff_Org_id')."' ");
            if($query->num_rows()>0){
               $query=$query->row(); 
               return $query;
            }else{
                return FALSE;
            }
        }
        
        private function update_fine($fineid,$status){
            $data=array(
                    'status'=>$status,
                      );
                $this->db->where('fine_id', $fineid);
             $this->db->update('lib_fines', $data); 
        }
       
        private function get_trans_id($issueid){
            $query=  $this->db->query("SELECT trans_id FROM `lib_issues` WHERE issue_id='".$issueid."'");
            $query=$query->row(); 
            return $query->trans_id;
        }
        
        
}

?>