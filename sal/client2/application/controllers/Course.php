<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->model('logs');
            /* cache control */
            $this->operations->is_login();
                        
        }
        
	public function index()
	{
            $this->load->view("Course/index");
	}
        
        public function Academic_structure($cls_id){
            $cls= $this->check_cls($cls_id);
           if(!$cls){
              redirect('Course', 'refresh');
           }else{
              $data['class_data']=$cls;
              $this->load->view('Course/cls_structure',$data);	
           }
        }
        
        private function check_cls($clsid){
            $credential = array('id' =>$clsid,'iid'=>$this->session->userdata('staff_Org_id'));
            $query = $this->db->get_where('class', $credential);
            if ($query->num_rows() > 0) {
                $query=$query->row();
                return $query;
            }else{
                return FALSE;
            }
       }
       
       //view_subject_structure
       public function view_subject_structure($clsid="",$subject=""){
           if( (strlen($clsid)==0)|| (strlen($subject)==0) ){
               redirect("course/","refresh");
           }else{
             $cls= $this->check_cls($clsid);
                if(!$cls){
                   redirect('Course', 'refresh');
                }else{
                   $data['class_data']=$cls;
                   $course_Structure=
                           "SELECT c. * ,s.subject, (SELECT count(*) FROM `course_main_topic`  where csid=c.csid ) as chapters FROM `course_structure` c JOIN subjects s ON c.sub_id=s.sid  where  c.iid='".$this->session->userdata('staff_Org_id')."' AND c.class_id ='".$clsid."' AND c.sub_id='".$subject."' ";
                   $course_Structure = $this->db->query($course_Structure);
                   if($course_Structure->num_rows()==0){
                       redirect("Course/Academic_structure/".$clsid,"refresh");
                   }
                   $data['course']=$course_Structure->row();
                  $this->load->view('Course/view_sub_structure',$data);	
                }   
           }
       }
       
       public function add_chapter($clsid="",$subject=""){
           if( (strlen($clsid)==0)|| (strlen($subject)==0) ){
               redirect("course/","refresh");
           }else{
             $cls= $this->check_cls($clsid);
                if(!$cls){
                   redirect('Course', 'refresh');
                }else{
                   $data['class_data']=$cls;
                   $course_Structure=
                           "SELECT c. * ,s.subject, (SELECT count(*) FROM `course_main_topic`  where csid=c.csid ) as chapters FROM `course_structure` c JOIN subjects s ON c.sub_id=s.sid  where  c.iid='".$this->session->userdata('staff_Org_id')."' AND c.class_id ='".$clsid."' AND c.sub_id='".$subject."' ";
                   $course_Structure = $this->db->query($course_Structure);
                   if($course_Structure->num_rows()==0){
                       redirect("Course/Academic_structure/".$clsid,"refresh");
                   }
                   $data['course']=$course_Structure->row();
                  
                   $this->load->view('Course/add_structure',$data);	
                }   
           }
       }
       
       public function add_topic(){
           
           $post=$this->operations->cleanInput($_POST);
           //print_r($_POST);exit;
           $field="chapter";
           if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Please Enter Chapter Name');
            }else{
                $n= $this->check_chapter_name($this->input->post("csid"),trim($post[$field]));
                if($n!=0){
                    $this->form->setError($field,'* Chapter Name Already Exists..'); 
                }
            }
            
            $field = 'topics';
           $sec_err=0;
	   if(strlen($post[$field]) ==  0)
		{  
                   $sec_err=1;
		   $this->form->setError($field,'* Please Provide No of Sub-topics');
		}else if(!is_numeric($post[$field])){
                     $sec_err=1;
                    $this->form->setError($field,'* Please Provide Numeric Value');
                }elseif($post[$field]==0){
                    $sec_err=1;
                    $this->form->setError($field,'* Please Provide No of Sub-topics');
                }
                
                if($sec_err==0){
                    
                    for($i=1;$i<=$post['topics'];$i++){
                        $field = 'subtopic_'.$i;
                        if(strlen($post[$field]) ==  0)
                        {
                           $this->form->setError($field,'* Please Provide sub-topic');
                        }
                        //nfclasses_
                        $field = 'nfclasses_'.$i;
                        if(strlen($post[$field]) ==  0)
                        {
                           $this->form->setError($field,'* No Of Classes');
                        }
                    } 
                    
                }
                
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect('Course/add_chapter/'.$this->input->post("cls_id").'/'.$this->input->post("sub_id"), 'refresh'); 
         }else{
             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'name' => $post['chapter'],
                'csid' => $this->input->post("csid"),
               );
             $this->db->insert('course_main_topic',$data);
             $cls_id=$this->db->insert_id();
             for($i=1;$i<=$post['topics'];$i++){
                $field = 'subtopic_'.$i;
                $data = array(
                            'iid' => $this->session->userdata('staff_Org_id'),
                            'sub_topic' => $post[$field],
                            'tid' => $cls_id,
                            'classess' =>$post['nfclasses_'.$i]
                        );
                //ALTER TABLE  `course_main_sub_topic` ADD  `classess` INT( 7 ) NOT NULL ;
                $this->db->insert('course_main_sub_topic',$data);
             }
                 redirect('Course/view_subject_structure/'.$this->input->post("cls_id").'/'.$this->input->post("sub_id"), 'refresh'); 
        
         }
            
           
       }
       
       private function  check_chapter_name($csid,$topic,$tid=""){
           $query ="SELECT * from course_main_topic WHERE iid='".$this->session->userdata('staff_Org_id')."' AND name='".$topic."' ";
           if(strlen($tid)!=0){
               $query.=" AND tid !='".$tid."' "; 
           }
          // echo $query;
           $query=  $this->db->query($query);
           return $query->num_rows();
        }


       public function add_course_Structure(){
           $data= array(
               'iid' =>$this->session->userdata('staff_Org_id'),
               'class_id' =>$this->input->post("class"),
               'sub_id' =>$this->input->post("subject"),
           );
           $this->db->insert("course_structure",$data);     
           ?>  <script>
                   window.location.href="<?php echo base_url() ?>index.php/Course/add_chapter/<?php echo $this->input->post("class") ?>/<?php echo $this->input->post("subject") ?>";
               </script>
               <?php
       }
       
       public function add_sub_topic_textbox(){
           $topics=  $this->input->post('topics');
           if(!is_numeric($topics)){
                ?>
                <script> $('#noofsecerr').html(" Invalid Numeric Value");</script>
                <?php
           }else{
               ?>
                <script> 
                    
                      $('#submit_btn').prop('disabled', false);
                </script>
                <?php
               for($i=1;$i<=$topics;$i++){
                   ?>
                <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Sub-Topic - <?php echo $i; ?></label>
                        <div class="col-sm-10">
                            <input type="text" style=" width:80% ; float: left" id='Secname_<?php echo $i; ?>' name="subtopic_<?php echo $i; ?>"  placeholder="Enter Sub-Topic"   class="form-control" value="<?php  echo $this->form->value('subtopic_'.$i); ?>" >
                            <input type="text" style="margin-left: 5px;  width:18%; float: left" id='nfclasses_<?php echo $i; ?>' name="nfclasses_<?php echo $i; ?>"  placeholder="No Of Hours"   class="form-control" value="<?php  echo $this->form->value('subtopic_'.$i); ?>" >
                <script>
                                        document.getElementById('nfclasses_<?php echo $i ?>').addEventListener('keydown', function(e) {
                                            var key   = e.keyCode ? e.keyCode : e.which;

                                            if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                                                 (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
                                                 (key >= 35 && key <= 40) ||
                                                 (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                                                 (key >= 96 && key <= 105)
                                               )) e.preventDefault();
                                        });
                                        </script>
                            <span id='noofsecerr' style=" clear: both; color: red">
                                    <?php
                                        echo $this->form->error('Secname_'.$i);   
                                    ?>
                            </span>        
                        </div>
                </div>
                  <?php
               }
           }
           
       }
       
       public function view_Staff_course(){
           $this->load->view("Course/staff_view");
       }
       
       public function update_status(){
            if(strlen($this->input->post("start_date")==0)){
               ?>
                  <script>
                  $('#start_date_err').html("** Please Select Date");
                  </script> 
               <?php
               exit;
           }else{
               $date1= explode("/",  $this->input->post("cdate"));
               if(sizeof($date1)!=3){
                ?>
                  <script>
                  $('#start_date_err').html("** Invalid Date Format");
                  </script> 
               <?php   
               exit;
               }
               $date1 = mktime(0, 0, 0, $date1[1], $date1[0], $date1[2]);
               if($date1>time()){
                  ?>
                  <script>
                  $('#start_date_err').html("** Invalid Date Selected");
                  </script> 
               <?php   
               exit; 
               }
               
           }
           if(strlen($this->input->post("cdate")==0)){
               ?>
                  <script>
                  $('#cdate_err').html("** Please Select Date");
                  </script> 
               <?php
               exit;
           }else{
               $date= explode("/",  $this->input->post("cdate"));
               if(sizeof($date)!=3){
                ?>
                  <script>
                  $('#cdate_err').html("** Invalid Date Format");
                  </script> 
               <?php   
               exit;
               }
               $date = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
               if($date>time()){
                  ?>
                  <script>
                  $('#cdate_err').html("** Invalid Date Selected");
                  </script> 
               <?php   
               exit; 
               }
               
           }
           
           if(strlen($this->input->post("Remarks"))==0){
               ?>
                  <script>
                  $('#Remarks_err').html("** Please Enter Remarks");
                  </script> 
               <?php
               exit;
           }
           
           $data = array(
               'iid' =>$this->session->userdata("staff_Org_id"),
               'sec_id' =>$this->input->post("sec_id"),
               'staff_id'=>$this->session->userdata("staff_id"),
               'course_id'=>$this->input->post("course"),
               'timestamp'=>time(),
               'remarks'=>$this->input->post("Remarks"),
               'comp_date'=>$date,
               'start'=>$date1,
               't_id' =>$this->input->post("msid"),
           );
           $this->db->insert("course_topic_completion",$data);
           $this->session->set_userdata('course_update_Sucess', 'Status Updated Sucessfully');
       
           ?>
            <script>
             location.reload();   
           </script>       
            <?php
           
       }
       
       public function view_staff_subject($staff_id="",$sec_id="",$sub_id="",$csid=""){
           if( (strlen($staff_id)==0)|| (strlen($sec_id)==0) || (strlen($sub_id)==0)|| (strlen($csid)==0) ){
               redirect("course/view_Staff_course","refresh");
           }else{
                $query=$this->db->query("SELECT c.cid,se.name as sec_name,se.sid,su.sid as sub_id ,su.subject,cl.id as cls_id,cl.name as class_name ,(SELECT csid FROM `course_structure` where class_id= cl.id and sub_id =c.subid ) as csid  FROM `course` c JOIN section se ON c.secid=se.sid JOIN subjects as su ON c.subid=su.sid  JOIN class cl ON se.class_id =cl.id WHERE tid='".$this->session->userdata("staff_id")."' AND c.subid='".$sub_id."' AND se.sid='".$sec_id."' ");
                if($query->num_rows()!=1){
                    redirect("Course/view_Staff_course","refresh"); 
                }else{
                    $query=$query->row();
                    $data['course_structure']=$query;
                    $this->load->view("Course/staff_course_update",$data);
                }
                
           }
       }
       
       
       
       
}
?>