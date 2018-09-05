<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fee extends CI_Controller {

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
        
	public function index()
	{
               $this->load->view('fee/index');
        }
        
        public function view()
	{
               $this->load->view('fee/fees');          	   
	}
        
        public function edit($cls=""){
         $cls=trim($cls); 
         if( (strlen($cls)==0) ||(!is_numeric($cls)) ){
                $this->load->view('fee/fees'); 
          }  else{
            $c=  $this->check_cls($cls);  
            if(!$c){
                $this->load->view('fee/fees'); 
            }else{
                $data['cls']=$c;
                
                $fees=  $this->get_fee_details($cls);
                $data['fee']=$fees;
                $this->load->view('fee/edit_fee',$data);      
            }
          }
        }
        
        public function add_fee(){
            
            $post=$this->operations->cleanInput($_POST);
            $cls=$this->input->post('fclass_name');
            $catids=explode(',',$this->input->post('ids'));
            $zeros=0;
            if(!is_numeric($cls)){
                $this->form->setError('fclass_name','* Please Select Class'); 
            }
            elseif(!$this->check_cls_fee($cls)){
               $this->form->setError('fclass_name','* Class Already Exists'); 
            }
            
            foreach ($catids as $cat) {
               $field="fee_".$cat;
               if (strlen($post[$field])==0){
                   $this->form->setError($field,'* Please enter Fee'); 
               }elseif(!is_numeric( $post[$field] )){
                    $this->form->setError($field,'* Enter Numeric Value'); 
               }elseif($post[$field]==0){
                   $zeros++;
               }   
            }
            if($zeros== sizeof($catids)){
                  $this->form->setError('fclass_name','* Please Allocate Fee For Atleast 1 Category'); 
             }
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();redirect('/fee/add_new/?cls_id='.$cls, 'refresh'); 
            
            }else{ 
               foreach ($catids as $cat) {
                    $field="fee_".$cat;
                    $data = array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'cls_id' => $cls,
                        'category'=>$cat,
                        'fee' => $post[$field]                
                       );
                     $this->db->insert('fee_class',$data);
                     
                }
                $this->logs->insert_staff_log(18,'Created Fee structure ', $cls);
                $this->session->set_userdata('Fee_added_Sucess',"Sucessfully added Record");redirect('/accounts/view/', 'refresh'); 
            
            }
            
        }
        
        public function edit_fee(){
           // print_r($_POST);
            
            $post=$this->operations->cleanInput($_POST);
          
            $cls=$this->input->post('cls_name');
            $catids=explode(',',$this->input->post('ids'));
            if(!is_numeric($cls)){
                $this->form->setError('fclass_name','* Please Select Class'); 
            }
            
            foreach ($catids as $cat) {
               $field="fee_".$cat;
               if (strlen($post[$field])==0){
                   $this->form->setError($field,'* Please enter Fee'); 
               }elseif(!is_numeric( $post[$field] )){
                    $this->form->setError($field,'* Enter Numeric Value'); 
               }   
            }
            
            
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
            }else{ 
               foreach ($catids as $cat) {
                    $field="fee_".$cat;
                    if(strlen($post['fee_id_'.$cat])==0){
                     
                        $data = array(
                         'iid' => $this->session->userdata('staff_Org_id'),
                         'cls_id' => $cls,
                         'category'=>$cat,
                         'fee' => $post[$field]                
                        );
                       $this->db->insert('fee_class',$data);
                     }else{
                        $data = array(
                         'fee' => $post[$field]                
                        );
                        $this->db->where('fid',$post['fee_id_'.$cat]); 
                       $this->db->update('fee_class',$data); 
                     }
                  
                }
                 $this->logs->insert_staff_log(18,'Edited Fee structure ', $cls);
                
                $this->session->set_userdata('Fee_added_Sucess',"Sucessfully Updated Fee's");
                
            }
            redirect('accounts/view', 'refresh'); 
        }
        
        public function add_new(){
             $this->load->view('fee/add_fee');
        }
        
        public function category($catid=""){
           $catid=trim($catid);
           if(strlen($catid)==0){
                $this->load->view('fee/category');  
                 
            }else{
               $k=  $this->is_cat($catid); 
               if(!$k){
                 $this->load->view('fee/category');    
               }else{
                   $data['cat']=$k;
                   $this->load->view('fee/edit_cat',$data);  
               }
               
            }
          
        }
        
        public function edit_category(){
          $cat=trim($this->input->post('cat'));
          $catid=trim($this->input->post('catid'));
          if(strlen($cat)==0){
            echo "Please eneter category";  
          }elseif(!$this->check_cat($cat,$catid)){
               echo "category Already Exits";
          }else{
              $data=array(
                'category' => $cat
              );
              $this->db->where('cid',$catid);
              $this->db->update('fee_category',$data);
              $this->logs->insert_staff_log(19,'Edited fee category  '.$cat,$catid);
              $this->session->set_userdata('Feecat_added_Sucess',"Sucessfully Updated fee category");
              ?><script>window.location.replace("<?php echo base_url() ?>/index.php/fee/category");</script><?php
          }
        }
        
        public function add_category(){
          $cat=trim($this->input->post('cat'));
          if(strlen($cat)==0){
            echo "Please eneter category";  
          }elseif(!$this->check_cat($cat)){
               echo "category Already Exits";
          }else{
              $data=array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'category' => $cat
              );
              $this->db->insert('fee_category',$data);
              //Feecat_added_Sucess
              $this->session->set_userdata('Feecat_added_Sucess',"Sucessfully Add New Fee Category");
              $this->logs->insert_staff_log(19,'Created fee category :'.$cat,  $this->db->insert_id());
              ?><script>window.location.reload();</script><?php
          }
        }
        
        private function check_cat($cat,$catid=""){
            if(strlen($catid)==0){
               $query="SELECT * FROM `fee_category` WHERE `category`='".$cat."' AND  `iid`='".$this->session->userdata('staff_Org_id')."'";
            }else{
               $query="SELECT * FROM `fee_category` WHERE `category`='".$cat."' AND  `iid`='".$this->session->userdata('staff_Org_id')."' AND cid !='".$catid."' ";
            }
            $query=  $this->db->query($query);
            if($query->num_rows>0){
                return FALSE;
            }else{
                return TRUE;
            }
            
        }
        
        private function is_cat($catid){
            $query=  $this->db->query("SELECT * FROM `fee_category` WHERE `cid`='".$catid."' AND  `iid`='".$this->session->userdata('staff_Org_id')."'");
           
            if($query->num_rows()>0){
               $query=$query->row();
               return $query;
            }else{
                return FALSE;
            }
        }
        
        private function check_cls_fee($cls){
          $query=  $this->db->query("SELECT *  FROM `fee_class` WHERE `cls_id` = '".$cls."'");
          if($query->num_rows()>0){
           return FALSE;   
          }else{
              return TRUE;
          }
        }  
        
        private function check_cls($cls){
            $query=  $this->db->query("SELECT * FROM `class` WHERE id='".$cls."' AND iid='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()>0){
              $query=$query->row();
              return $query;
            }else{
                return FALSE;
            }
        }
        
        private function get_fee_details($cls){
            $query="SELECT f.fid ,c.cid as catid ,c.category,f.fee FROM fee_category c LEFT JOIN fee_class f ON c.cid = f.category  WHERE f.cls_id='".$cls."' AND  c.iid='".$this->session->userdata('staff_Org_id')."' ";
            $query=  $this->db->query($query);
            return $query->result();
        }
        
        public function manage_concessions(){
             $this->load->view('fee/manage_concession');
        }
        
        public function Add_concessions(){
             $this->load->view('fee/add_concession');
        }
        
        public function save_concession(){
            $post=$_POST;
            $ids= $this->input->post('category_ids');
            $ids = explode(',', $ids);
            $ids = array_filter($ids);
            
            if(strlen($this->input->post('reason'))==0 ){
                    $this->form->setError('reason','* Please enter Reason');
            }
            $counter=0;
            foreach($ids as $val){
               
                if(!is_numeric($this->input->post('fee_concession_'.$val))){
                    	$this->form->setError('fee_concession_'.$val,'* Please enter valid amount');
                }else{
                    $fees=explode(',', $this->input->post('total_fee_'.$val));
                    if(! ($this->input->post('fee_concession_'.$val) <= ( $fees[0]-$fees[1] ))){
                      $this->form->setError('fee_concession_'.$val,'* Enter valid Amount ');
                    }else{
                        if($this->input->post('fee_concession_'.$val)!=0){
                            $counter++;
                        }
                    }
                }
            }
            if($counter==0){
                      $this->form->setError('cat_reason','* Enter Amount for atleaast 1 category ');
            }
            
            if($this->form->num_errors >0 )
                {
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $this->form->getErrorArray();
                   redirect('/fee/Add_concessions', 'refresh'); 
            }else{
               
                $data= array(
                    'iid'=> $this->session->userdata('staff_Org_id') ,
                    'student' =>$this->input->post('student_id'),
                    'staff' =>$this->session->userdata('staff_id') ,
                    'reason' =>$this->input->post('reason'),
                    'time' =>time()
                );
                $this->db->insert('concessions',$data);
                 $cid=$this->db->insert_id();
                foreach($ids as $val){
                    if($this->input->post('fee_concession_'.$val)!=0) {
                      $data= array(
                                'iid'=> $this->session->userdata('staff_Org_id') ,
                                'std_id' =>$this->input->post('student_id'),
                                'cat_id' =>$val ,
                                'conc_id' =>$cid,
                                'amount' =>$this->input->post('fee_concession_'.$val)
                            ); 
                      $this->db->insert('fee_concession',$data);           
                    }
                }
                $this->session->set_userdata('Concession_adding_sucess',"Sucessfully Added Concession");
            }
              redirect('/fee/manage_concessions', 'refresh'); 
          
            
        }
        
        public function Load_fee_structure(){
            $c= $this->check_concession($this->input->post('student'));
            if($c){
                
            $student= $this->fetch_student_details($this->input->post('student'));
            $cls_details= $this->get_fee_details($student->cls_id);
            $paid= $this->fetch_paid_fee($this->input->post('student'));
           ?>
              <script>
                  $('#fee_title').html("Fee Concession For <?php echo $student->name ?>");
                  </script>
                  
                  <form action="<?php  echo base_url();?>index.php/Fee/save_concession" method="post">
                  <?php
                    if(sizeof($cls_details)==0){
                        ?>
                      <span style=" text-align: center; color: red">** No Fee Structure Found..</span>
                      <?php
                    }else{
                        ?>
                        <table class="table table-hover table-nomargin  table-bordered">
                            <tr>
                                <th style=" text-align: center; padding-top: 25px;">Reason</th><td colspan="3"><textarea name="reason" class="form-control" style=" resize: none;" rows="3"  cols="2" ><?php echo $this->form->value('reason');  ?></textarea>
                                    <span style=" color: red"><?php echo $this->form->error('reason');  ?></span>
                                    <span style=" color: red"><?php echo $this->form->error('cat_reason');  ?></span>
                                    
                                </td>
                            </tr>
                  
                      <tr>
                          <th>Categories</th>
                          <th>Total</th>
                          <th>Paid</th>
                          <th>Concession</th>
                      </tr>
               
                 
                      <?php
                        
                        $paid_Arr=array();
                        foreach ($paid as $val){
                            $paid_Arr[$val->category] =$val->total;
                        }
                        
                            $cat_id="";
                            foreach ($cls_details as $value) {
                                $cat_id.=$value->catid.",";
                            ?>
                            <tr>
                                <td><?php echo $value->category ?></td>
                                <td><?php 
                                $total=0;
                                if(strlen($value->fee)==0){
                                    echo "0";
                                } else{
                                    echo $value->fee;
                                    $total =$value->fee;
                                }
                                    ?></td>
                                <td><?php
                                $paid=0;
                                if(isset($paid_Arr[$value->catid])){
                                    $paid =$paid_Arr[$value->catid];
                                   echo $paid_Arr[$value->catid];
                                }else{
                                    echo "0";
                                }
                                    ?></td>
                                <td>
                                    <input type="hidden" name="total_fee_<?php echo $value->catid ?>" value="<?php echo $total.",".$paid  ?>" />
                                    <input type="text" name="fee_concession_<?php echo $value->catid  ?>" onkeyup="Get_total();" id="fee_concession_<?php echo $value->catid  ?>"  value="0"  class="form-control" />
                                   <script>
                                        document.getElementById('fee_concession_<?php echo $value->catid ?>').addEventListener('keydown', function(e) {
                                            var key   = e.keyCode ? e.keyCode : e.which;

                                            if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                                                 (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
                                                 (key >= 35 && key <= 40) ||
                                                 (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                                                 (key >= 96 && key <= 105)
                                               )) e.preventDefault();
                                        });
                                        </script>
                                    
                                    <span style=" color: red"><?php echo $this->form->error('fee_concession_'.$value->catid);  ?></span>
                                </td>
                            </tr>
                                <?php

                             }
                              $cat_id = substr($cat_id, 0, strlen($cat_id)-1);
                             ?>
                  <input type="hidden" name="category_ids"  id="category_ids" value="<?php echo $cat_id ?>" />
                  
                            <script>
                                function Get_total(){
                                  //  Total_fee=$('Total_fee').html();
                                    ids = $('#category_ids').val();
                                    //alert(ids);
                                    ids= ids.split(",");
                                    total=0;
                                    for (i=0; i<ids.length; i++)
                                    {
                                        total+=Number($('#fee_concession_'+ids[i]).val());
                                    }
                                    $('#Total_fee').html(total);
                                }
                            </script>
                  <input type="hidden" name="student_id" value="<?php echo  $student->student_id ?>" />
                  <input type="hidden" name="class" value="<?php echo  $student->sec_id ?>" />
                  
                  <tr>
                      <td colspan="4" style=" text-align: center" >
                          <input type="submit" style=" float: left" class="btn btn-primary" name="submit" value="Add Concession" />
                          <span style=" float: right">Total Amount : <span id="Total_fee">0</span></span>
                          
                      
                      </td>
                  </tr>
                            <?php
                        
                        
                      ?>
              </table>
                        <?php
                    }
                  ?>
                  
              
                      </form>
            <?php
            }else{
                ?><br/>
                <span style=" color: red; text-align: center; float: left ; padding-left: 15px ">Fee Concession Already Assigned </span>
                <a href="" style="float: right; padding-right: 15px  ">Click Here To Edit</a><br/><br/>
                <?php
            }
        }
        
        public function load_students(){
            $section=  $this->input->post("section");
            $student = $this->input->post('student');
            $query = " select * from student where iid='".$this->session->userdata('staff_Org_id')."' AND section_id = '".$section."' ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Student</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->student_id ?>" 
                        <?php
                          if($student==$val->student_id ){
                            echo 'selected=""';  
                          }
                        ?>
                         >
                    <?php echo $val->userid."-".$val->name ?>
                </option>
             <?php
            }

        }
        
        private function check_concession($student){
            $query="SELECT * FROM concessions where student='".$student."' ";
            $query=  $this->db->query($query);
            if($query->num_rows()>0){
               return FALSE; 
            }else{
                return TRUE;
            }
        }
        
        private function fetch_student_details($student){
            $q="SELECT s.name , s.student_id, s.userid,se.sid as sec_id ,se.name as sec_name,c.id as cls_id, c.name as cls_name from student s JOIN section se ON s.section_id=se.sid JOIN class c On se.class_id= c.id where s.student_id = '".$student."' ";
            $q = $this->db->query($q);
            $q = $q->row();
            return $q;
        }  
    
        private function fetch_paid_fee($student){
            $q="SELECT sum(amount) as total ,category FROM `fee_accounts` where  student_id ='".$student."' AND iid='".$this->session->userdata('staff_Org_id')."' GROUP BY category ";
            $q = $this->db->query($q);
            $q=$q->result();
            return $q;
        }
        
        public function view_concession($id=""){
            if(strlen($id)==0){
                 redirect('/fee/manage_concessions', 'refresh'); 
            }else{
                $concession ="SELECT c.cid,c.time,c.reason , s.name as staff_name , st.userid,st.roll , st.name as stud_name,  se.name as sec_name , cl.name as cls_name FROM concessions c JOIN staff s ON c.staff = s.id JOIN student st ON c.student = st.student_id JOIN section se ON se.sid=st.section_id JOIN class cl ON se.class_id=cl.id WHERE c.iid = '".$this->session->userdata('staff_Org_id')."' AND c.cid='".$id."' ";
                $concession = $this->db->query($concession);
                if($concession->num_rows()==0){
                   redirect('/fee/manage_concessions', 'refresh');   
                }else{
                    $concession=$concession->row();
                    $data['concession']=$concession;
                    $this->load->view('fee/view_concession',$data);
                }
            }
        }
        
        public function edit_concession($id=""){
            if(strlen($id)==0){
                 redirect('/fee/manage_concessions', 'refresh'); 
            }else{
                $concession ="SELECT c.cid,c.time,c.reason , s.name as staff_name ,st.student_id, st.userid,st.roll , st.name as stud_name,  se.name as sec_name , cl.name as cls_name ,cl.id as cls_id FROM concessions c JOIN staff s ON c.staff = s.id JOIN student st ON c.student = st.student_id JOIN section se ON se.sid=st.section_id JOIN class cl ON se.class_id=cl.id WHERE c.iid = '".$this->session->userdata('staff_Org_id')."' AND c.cid='".$id."' ";
                $concession = $this->db->query($concession);
                if($concession->num_rows()==0){
                   redirect('/fee/manage_concessions', 'refresh');   
                }else{
                    $concession=$concession->row();
                    $cls_details= $this->get_fee_details($concession->cls_id);
        
                    $data['concession']=$concession;
                    $data['cls_details']=$cls_details;
                    $data['paid_details']= $paid= $this->fetch_paid_fee($concession->student_id);
                    $this->load->view('fee/edit_concession',$data);
                }
            }
        }
        
        public function save_con_changes(){
            $post =$_POST;
            $ids= $this->input->post('cat_id');
            $ids = explode(',', $ids);
            $ids = array_filter($ids);
            
            if(strlen($this->input->post('reason'))==0 ){
                    $this->form->setError('reason','* Please enter Reason');
            }
            $counter=0;
            foreach($ids as $val){
               
                if(!is_numeric($this->input->post('concession_'.$val))){
                    	$this->form->setError('concession_'.$val,'* Please enter valid amount');
                }else{
                    $fees=explode(',', $this->input->post('total_'.$val));
                    if(! ($this->input->post('concession_'.$val) <= ( $fees[0]-$fees[1] ))){
                      $this->form->setError('concession_'.$val,'* Enter valid Amount ');
                    }
                }
            }
            
            if($this->form->num_errors >0 )
                {
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $this->form->getErrorArray();
                   redirect('/fee/edit_concession/'.$this->input->post('conc_id'), 'refresh'); 
            }else{
                foreach($ids as $val){
                    
                    if(isset($post['old_conession_'.$val])){
                      $data = array(
                                 'amount' =>$this->input->post('concession_'.$val)
                                );
                       $this->db->where('id', $post['old_conession_'.$val]);
                       $this->db->update('fee_concession',$data);
                    }else{
                       if($this->input->post('concession_'.$val)!=0){
                         $data= array(
                                'iid'=> $this->session->userdata('staff_Org_id') ,
                                'std_id' =>$this->input->post('student_id'),
                                'cat_id' =>$val ,
                                'conc_id' => $this->input->post('conc_id'),
                                'amount' =>$this->input->post('concession_'.$val)
                             ); 
                         $this->db->insert('fee_concession',$data);      
                       }
                        
                    }
                    
                    
                }
                $this->session->set_userdata('Concession_adding_sucess',"Concession Edited Sucessfully ..");
            }
             redirect('/fee/manage_concessions', 'refresh'); 
          
        }
        
        
            
         
        
        
}
