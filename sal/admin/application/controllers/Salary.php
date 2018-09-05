<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends CI_Controller {

        function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->load->model("validations");$this->operations->is_login();
        }
        
        public function index(){
            
        }
        
        public function add()
        {
             $this->load->view('salary/index');
        }
        
        public  function View(){
            $this->load->view('salary/view_salary');
        }

        public function edit(){
            ?>
                <div class="col-sm-12">
                    <input type="text" id="new_amount_<?php  echo $this->input->post('sid')  ?>" class="col-sm-8"  value="<?php echo $this->input->post('amount') ?>" />  
                    &nbsp;&nbsp;<button class="btn " onclick="save_salary('<?php  echo $this->input->post('sid')  ?>')" ><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                    <span style="color:red" id="new_amount_error_<?php  echo $this->input->post('sid')  ?>"></span>
                </div>
            <?php
        }
        
        public function save_sal(){
            $sid=  $this->input->post('sid');
            $amount=  $this->input->post('amount');
            if(!is_numeric($amount)){
                echo "Please enter valid amount";
            }else{
                $data=array(
                    'amount' =>$amount,
                    'assigned_by' =>$this->session->userdata('staff_id')
                );
                $this->db->where('id', $sid);
                $this->db->update('salary',$data);
                //staff_name
                $this->logs->insert_staff_log(27,'Re-configured Salary',$sid);
                ?>
                 <script>
                      location.reload();
                     
//                  $('#td_amount_<?php echo $sid ?>').html('<?php echo $amount  ?>');
//                  $('#td_assign_<?php echo $sid ?>').html('<?php echo $this->session->userdata('staff_name')  ?>');
//                  $('#td_action_<?php echo $sid ?>').html('<button onclick="edit_salary('<?php echo $sid ?>','<?php echo $amount ?>');" class="btn btn-mini"><i class="fa fa fa-pencil-square-o"></i></button>');
                 </script>
               <?php
            }
        }

        public function add_salary(){
            $post=$this->operations->cleanInput($_POST);
            //setError
            $field = 'staff';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please Select Staff');
            }else if(!$this->check_sal($post[$field])){
                 $this->form->setError($field,'* Salary Already Configured');
            }
            
             $field = 'amount';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please Enter Amount');
            }elseif(!is_numeric($post[$field])){
                 $this->form->setError($field,'* Please Enter Numeric value');
            }
       
            if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
              redirect('salary/add','refresh');
            }else{
                 $data=array(
                     'iid' =>  $this->session->userdata('staff_Org_id'),
                     'staff_id' =>$post['staff'],
                     'amount' =>$post['amount'],
                     'assigned_by' =>$this->session->userdata('staff_id')
                 );
                 $this->db->insert('salary',$data);
                 $this->logs->insert_staff_log(27,'Configured Salary',$this->db->insert_id());
                 $this->session->set_userdata('staff_Salary_add',"New Salary Added Sucessfully ");
                 redirect('salary/View','refresh');
            }
           
            
        }
        
        public function view_monthly($id=0){
            if($id==0){
                redirect('salary/pay_salary','refresh');
            }else{
               $query=  $this->db->query("SELECT m.id,m.month,m.paid_on,s.name FROM `salary_month` m JOIN staff s ON m.paid_by=s.id WHERE m.id='".$id."'  ");
                if($query->num_rows()!=1){
                    redirect('salary/pay_salary','refresh');   
                }else{
                    $data['month_sal']=$query->row();
                    $query=  $this->db->query("SELECT p.amount,s.name,s.email FROM `salary_paid` p JOIN staff s ON p.staff=s.id WHERE p.month_id='".$id."' ");
                     $data['records']=$query->result();
                     $this->load->view('salary/view_monthly',$data);
                }
                
            }
            
            
        }
        
        
        public function pay_salary(){
            $query=  $this->db->query("select * from accounts where iid= '".$this->session->userdata('staff_Org_id')."' AND category ='1'");
            if(is_null($this->session->userdata('email')) && $this->router->fetch_class() == 'salary' && $this->session->userdata('iid') != $this->session->userdata('staff_Org_id') && $query->num_rows() > 0){
                 redirect("accounts/login/1",'refresh'); 

              }
              else{
                 $this->load->view('salary/paymonth');
                //echo current_url();
              }
                
               //$this->load->view('accounts/login');

              //$this->load->view('salary/paymonth');
            //SELECT s.name,s.email,SUM(CASE WHEN a.status = 0 THEN 1 END) AS not_done,SUM(CASE WHEN a.status = 1 THEN 1 END) AS present,SUM(CASE WHEN a.status = 2 THEN 1 END) AS absent FROM staff_attendance a JOIN staff s ON a.staff=s.id  JOIN staff_attendance_date d ON a.date_id=d.id  GROUP BY staff
        }
        
        public function pay($month=0,$year=0){
            if( ( ($month==0) || ($year==0) )|| ( (!is_numeric($month)) || (!is_numeric($year))  ) ||(strlen($year)!=4) ){
               redirect('salary/pay_salary','refresh'); 
            }else{
                if( ($month<1) || ($month >12) ){
                      redirect('salary/pay_salary','refresh');           
                }else{
                    $time=mktime(0,0,0,$month,1,$year);
                    $query=  $this->db->query("SELECT * FROM `salary_month` WHERE month='".$time."'  ");
                    if($query->num_rows()!=0){
                        redirect('salary/pay_salary','refresh');   
                    }
                    $query="SELECT s.id,s.name,s.email,SUM(CASE WHEN a.status = 0 THEN 1 END) AS not_done,SUM(CASE WHEN a.status = 1 THEN 1 END) AS present,SUM(CASE WHEN a.status = 2 THEN 1 END) AS absent,sal.amount FROM staff_attendance a JOIN staff s ON a.staff=s.id JOIN staff_attendance_date d ON a.date_id=d.id LEFT JOIN salary sal ON s.id=sal.staff_id  WHERE a.iid='".$this->session->userdata("staff_Org_id")."' AND  ( d.day >='".$time."' AND d.day < '".mktime(0,0,0,$month+1,1,$year)."'  ) GROUP BY staff";
                    $query=$this->db->query($query);
                    
                    $data['month']=$time;
                    $data['result']=$query->result();
                    $this->load->view('salary/pay',$data);
                }
            }
        }
        
        public function pay_sal(){
            
            $post=$this->operations->cleanInput($_POST);
            $month=$post['month'];
            $month=  getdate($month);
            $ids=  explode(',', $post['ids']);
            foreach ($ids as $id){
                $field="amount_".$id;
                if(strlen($post[$field]) ==  0)
                {
                   $this->form->setError($field,'* Please Provide Section');
                }elseif(!is_numeric($post[$field])){
                    $this->form->setError($field,'* Please enter Numeric value');
                }
            }
            if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
                redirect('/attendance/add', 'refresh'); 
            }else{ 
               $data=array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'month' => $post['month'],
                        'paid_on' =>time(),
                        'paid_by' =>$this->session->userdata('staff_id'),
                        );
                $this->db->insert('salary_month',$data);
                $mid= $this->db->insert_id();
                foreach ($ids as $id){
                    $field="amount_".$id;//$post[$field]
                    $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'month_id' =>$mid,
                        'staff' =>$id,
                        'amount' =>$post[$field]
                    );
                    $this->db->insert('salary_paid',$data);
                }
                $this->logs->insert_staff_log(28,'Paid Monthly Salary of '.$month['month'].','.$month['year'],$mid);
                $this->session->set_userdata('salary_pay',"Sucessfully Paid Salary To Staff");
            }
         
          redirect("salary/pay/".$month['mon']."/".$month['year'],'refresh');
        }
        
        public function check_month_salary(){
            $time=mktime(0,0,0,$this->input->post('month'),1,$this->input->post('year'));
            if($time > time()){
                ?>
                 <script>
                   $('#errors').html("<br/>Already Paid for the month");
                 </script>
                 <?php
                 exit;
            }
            
            $query=  $this->db->query("SELECT * FROM `salary_month` WHERE month='".$time."'  ");
            if($query->num_rows()==0){
                 $t= base_url()."index.php/salary/pay/".$this->input->post('month')."/".$this->input->post('year');
                ?>
                 <script>
                   window.location.replace("<?php echo $t ?>")   ; 
                 </script>
                 <?php
            }else{
                ?>
                 <script>
                   $('#errors').html("<br/>Already Paid for the month");
                 </script>
                 <?php
            }
        }
        
        private function  check_sal($staff){
           
           $query=  $this->db->query("SELECT * FROM `salary` WHERE staff_id='".$staff."'  ");   
           
           if($query->num_rows()==0){
               
                return TRUE;
            }else{
              
                return FALSE;
            }
        }
        
        
}
?>