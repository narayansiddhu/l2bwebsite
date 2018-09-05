<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('examinations');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->operations->is_login();
            $this->load->model("validations");
            $check="SELECT `transport` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->transport==0){
                 $this->session->set_userdata('blocked_module', 'Transport Module'); 
                redirect("/Acessdenied/","refresh");
            }
            /* cache control */
        }
    
    public function index(){
        $this->load->view('transport/index');
    }
    
    public function drivers(){
        $this->load->view('transport/drivers');
    }
    
    public function new_driver(){
        $this->load->view('transport/createdrivers');
    }
    public function add_driver(){
       $post=$_POST;
        $field = 'stusername';

            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please Provide Driver Name');
            }
            
           $field = 'gender';
           
          //stmobile      
           $field = 'stmobile';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Mobile Number');
                }
                elseif( $this->validations->validate_mobile($post[$field])==0 )
                {
                        $this->form->setError($field,'* Please enter valid Mobile Number  ');
                }elseif( ! $this->validations->check_teacher_mobile($post[$field])){
                    $this->form->setError($field,'* Mobile Number Already Used ');
                    //check_teacher_mobile
                }
          //stemail
          $field = 'stemail';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide E-mail');
                }
                elseif( $this->validations->validate_email($post[$field])==0 )
                {
                        $this->form->setError($field,'* Please enter Valid E-mail id  ');
                }elseif( ! $this->validations->check_teacher_email($post[$field])){
                    $this->form->setError($field,'* E-mail Already Used');
                    //check_teacher_email
                }                
         
                
        if(isset($post['aadhar'])>0){
            $field = 'aadharcard';
            if(strlen($post[$field]) ==  0)
            {
                $this->form->setError($field,'* Please provide Aadharcard No');
            }else{
                if(strlen($post[$field])!=12)
                 {
                           $this->form->setError($field,'* Please provide Valid Aadharcard No');

                }
            }
         }
                
                
                
         //stdob	
           $field = 'Drivinglic';
            if(strlen($post[$field]) ==  0)
            {
                $this->form->setError($field,'* Please provide License No');
            }else{
                if(strlen($post[$field])!=16)
                 {
                           $this->form->setError($field,'* Please provide Valid License No');

                }
            }
           $field='dlcvalid';
                if(strlen($post[$field]) ==  0)
                {
                    $this->form->setError($field,'* Please provide Validity Date');
                }else{
                    $str=explode("/",$post[$field]);
                    $lic_exp= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($lic_exp <time()){
                        $this->form->setError($field,'* Provide Valid Validity Date');
                    }
                }
            $field = 'stdob';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Date Of Birth');
                }else{
                    $str=explode("/",$post[$field]);
                   $dob= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($dob >time()){
                        $this->form->setError($field,'* Please select a valid Date Of Birth');
                    }
                }
        $field = 'stdoj';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Date Of Joining');
                }else{
                    $str=explode("/",$post[$field]);
                   $doj= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($dob >time()){
                        $this->form->setError($field,'* Please select a valid Date Of Joining');
                    }
                }
         //stlevel	
           $field = 'stlevel';
          $post[$field]="0";
         //stqualification 
         $field = 'stqualification';
         $post[$field]="Driiver";
          $field = 'stsalary';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Salary ');
                }else if(!is_numeric($post[$field])){
                    $this->form->setError($field,'* Please Provide Numeric Value ');
                }
          $img_uplod=0;
          if($post['image_type']==1){
              if(strlen($this->session->userdata('driver_dummy_img'))!=0){
                  $img_uplod=1;
              }
          }else{
                $field = 'stdimage';
                if(strlen($_FILES[$field]['name']) ==  0)
                {
                      //  $this->form->setError($field,'* Please Select a profice pic');
                }else{
                    $img_uplod=2;
                    $ext=explode(".",$_FILES['stphoto']['name']);
                    $ext=$ext[sizeof($ext)-1 ];
                    $img_arr=array('jpg','jpeg','gif','png');
                    if(!in_array($ext,$img_arr)){
                        $this->form->setError($field,'* Please Select a Image file');
                    }
                } 
          } 
          
         if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/transport/new_driver', 'refresh'); 
         }else{
             $pass=$this->validations->generate_password();
                    $file="";
                    if($img_uplod==2){
                            $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."drv_".time().".".$ext; 
                            $config['upload_path']   = 'var/www/html/schooln/assests_2/uploads/'; 
                            //'var/www/html/schooln/assests_2/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png'; 
                            $this->load->library('upload', $config);
                            $this->upload->do_upload('stphoto');
                    }
                    if($img_uplod==1){
                        $file= $this->session->userdata('staff_Org_id')."_drv_".time().".jpg"; 
                        $this->session->unset_userdata('driver_dummy_img');
                        copy('/var/www/html/schooln/assests_2/uploads/temp/'.$this->session->userdata('driver_dummy_img'),'/var/www/html/schooln/assests_2/uploads/'.$file);
                    }
             
                    $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'name' =>$post['stusername'],
                       'dob' => $dob,
                       'doj' => $doj,
                       'sex' => 1,
                       'bloodg' => "0",
                       'phone' => $post['stmobile'],
                       'email' => $post['stemail'],
                       'password' => $pass,
                       'qualification' =>"Driver",
                       'img' => $file,
                       'status' => 1,
                       'level' => 0,
                       'timestamp' =>time(),
                       'last_login' =>time(),
                       'createdby'=>"st_".$this->session->userdata('staff_id')
                       );            
                    $this->db->insert('staff',$data); 
                    $id= $this->db->insert_id();
                     $data=array(
                            'iid' =>  $this->session->userdata('staff_Org_id'),
                            'staff_id' => $id,
                            'amount' =>$post['stsalary'],
                            'assigned_by' =>$this->session->userdata('staff_id')
                        );
                        $this->db->insert('salary',$data);
                 $this->logs->insert_staff_log(27,'Configured Salary',$this->db->insert_id());
                
                    $data= array(
                             'iid' => $this->session->userdata('staff_Org_id'),
                             'staff_id' => $id,
                             'doc_name' =>'driving License',
                             'doc_no' =>$post['Drivinglic'],
                             'validity' =>$lic_exp
                             ); 
                    $this->db->insert('staff_documents',$data); 
                    if(isset($post['aadhar'])){
                        $data= array(
                             'iid' => $this->session->userdata('staff_Org_id'),
                             'staff_id' => $id,
                             'doc_name' =>'AAdhar Card',
                             'doc_no' =>$post['aadharcard'],
                             'validity' =>""
                             ); 
                    $this->db->insert('staff_documents',$data);
                    }                    
            $this->logs->insert_staff_log(7,'Created Driver :'.$post['stusername'],$id  );
            $this->session->set_userdata('driver_add_Sucess', 'New Driver Created Sucessfully'); 
             redirect('/transport/drivers', 'refresh'); 
            
         }
    }
    
    public function vehicles(){
        $this->load->view('transport/vechiles');
    }
    
    public function add_vehicle(){
       
        $post=$_POST;
        $field = 'vechile_no';
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please Provide Vechile no');
        }
        
        $field = 'Manufacturer';
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please enter Manufacturer name');
        }
        
        $field = 'type';
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please Select Fuel type');
        }
        
        $c=0;
        //rcno
        $field ='rcno';
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please eneter registration No');
        }
        
        $field = 'rcdate';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Rc Expiry Date');
                }else{
                    $str=explode("/",$post[$field]);
                   $rcdate= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($rcdate <time()){
                        $this->form->setError($field,'* Please select a valid Expiry Date');
                    }
                }
        $field = 'insurancedate';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Insurance Expiry Date');
                }else{
                    $str=explode("/",$post[$field]);
                   $ins_Date= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($ins_Date <time()){
                        $this->form->setError($field,'* Please select a valid Expiry Date');
                    }
                }
                
        $field = 'pucdate';
                if(strlen($post[$field]) ==  0)
                {
                        $this->form->setError($field,'* Please provide Pollution Check Expiry Date');
                }else{
                    $str=explode("/",$post[$field]);
                   $pucdate= mktime(0,0,0,$str[1],$str[0],$str[2]);
                    if($pucdate <time()){
                        $this->form->setError($field,'* Please select a valid Expiry Date');
                    }
                }
         if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/transport/new_vehicles', 'refresh'); 
         }else{
                $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'vech_no' =>$post['vechile_no'],
                       'fuel' =>$post['type'],
                       'manufacture' =>$post['Manufacturer'],
                       'rc_no' => $post['rcno'],
                       'rc_date' => $rcdate,
                       'ins_no' => $post['insurance'],
                       'ins_date' => $ins_Date,
                       'puc_no' => $post['puc'],
                       'puc_date' => $pucdate,
                       );            
                    $this->db->insert('vehicles',$data); 
              $this->session->set_userdata('vechile_add_Sucess', 'New Vehicle Created Sucessfully'); 
             redirect('/transport/vehicles', 'refresh'); 
          
         }
                
                
        
    }
    
    public function new_vehicles(){
        $this->load->view('transport/createvehicles');
    }
    
    public function Upload_snap(){
      //  $config['upload_path']   = 'C:/wamp/www/schooln/assests_2/uploads/';
        $file="tmp_driver_".time().".jpg";
        move_uploaded_file($_FILES['webcam']['tmp_name'],'/var/www/html/schooln/assests_2/uploads/temp/'.$file);
        $this->session->set_userdata('driver_dummy_img', $file); 
    }
    
    public function preview_sms(){
        $post =$_POST;
        $field = 'message';
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please Enter Message content');
        }
        
        $trip_route_ids =  array_filter( explode(",",$post['trip_route_ids']));
        $mobile ="";
        foreach ($trip_route_ids as $value) {
            $q="SELECT s.phone from stud_transport st JOIN student s  ON st.stud_id =s.student_id where st.trip_route_id='".$value."' ";
            $q=$this->db->query($q)->result();
            foreach($q as $val){
               $mobile.=$val->phone.","; 
            }
        }
        
        if(strlen($mobile)==0){
              $this->form->setError("mobile",'* Please select Mobile Numbers');
        }
        if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/transport/send_sms/'.$this->input->post("trip_id"), 'refresh'); 
         }else{
             $invalid=0;
             $mobile = array_filter(explode(",", $mobile));
             $msg_array=array();
             foreach ($mobile as $value) {
                 if( !($this->validations->validate_mobile($value)==0) )
                {
                  $msg_array[]=array("mobile"=>$value,"message"=>  $this->input->post("message"));
                }else{
                    $invalid++;
                }
                
             }
             
             $post['invalid_no']=$invalid;
             $_SESSION['trans_msg_post']=$post;
             $_SESSION['trans_msg_prev']=$msg_array;
             redirect("/transport/preview","refresh");
         }
        
    }
    
    public function send(){
        $message_array =$_SESSION['trans_msg_prev'];
        $post_data =$_SESSION['trans_msg_post'];
            $time=time();
        $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>6,
                    'message' =>$post_data['message'],
                    'time' =>$time
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        $sms_details=$this->fetch_sms_details();$count=0;
        $this->logs->insert_staff_log(4,'Sending Alert',$aid);
        foreach ($message_array as $value) {
              $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$value['message'],
                        'mobile' =>$value['mobile'],
                        'time' => $time,
                        'status' =>0,
                        'alert_id' =>$aid,
                    );
            $count++;
          $this->db->insert('msg_senthistory',$data); 
        }
       $trip =$post_data["trip_id"];
        $this->session->set_userdata('transAlerts_send_sucess', "Sucessfully sent Alert , Total Message count :".$count);
             
        unset($_SESSION['trans_msg_prev']);
        unset($_SESSION['trans_msg_post']);
        redirect("transport/send_sms/".$trip,"refresh");
    }
private function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        return $msg;
    }
public function discard_sms(){
    $trip =$_SESSION['trans_msg_post']["trip_id"];
    unset($_SESSION['trans_msg_prev']);
    unset($_SESSION['trans_msg_post']);
    redirect("transport/send_sms/".$trip,"refresh");
}
    public function preview(){
        if(isset($_SESSION['trans_msg_prev'])){
            $this->load->view("transport/message_preview");
        }else{
            redirect("transport/routes","refresh");
        }
    }
    public function add_routes(){
           $this->load->view('transport/add_route');  
    }
    
    public function add_new_route(){
        //echo "<pre>";print_r($_POST);exit;
        $post=$_POST;
        $stops=$this->input->post("stops");
        $trips = $this->input->post("trips");
        $trip_array=array();
        for($i=1;$i<=$trips;$i++) {
            
                    $field="drop_".$i;
                    if(strlen($post[$field]) ==  0)
                    {
                        $this->form->setError($field,'* Enter Return Time');
                    }else{
                        $tym= explode(":",$post[$field]);
                        if(sizeof($tym)!=2){
                            $this->form->setError($field,'* Enter Valid Return Time');
                        }else{
                            $trip_array[$i]['return_tym']=$post[$field];
                        }
                    }
                    
                    $field="fee_".$i;
                    if(strlen($post[$field]) ==  0)
                    {
                        $this->form->setError($field,'* Enter Fee Amount');
                    }else{
                       if(!is_numeric($post[$field])){
                               $this->form->setError($field,'* Enter Valid Fee Amount');
                       }else{
                            $trip_array[$i]['fee']=$post[$field];
                        }
                    }
                    $field="vechile_".$i;
                    if(strlen($post[$field]) ==  0)
                    {
                        $this->form->setError($field,'* Select Vechicle');
                    }else{
                        $trip_array[$i]['vehicle']=$post[$field];
                        
                    }
                    $field="driver_".$i;
                    if(strlen($post[$field]) ==  0)
                    {
                        $this->form->setError($field,'* Select Driver');
                    }else{
                        $trip_array[$i]['driver']=$post[$field];
                        
                    }
                    
                    $field="pick_upending_".$i;
                    if(strlen($post[$field]) ==  0)
                    {
                        $this->form->setError($field,'* Enter Pick Up ending Time');
                    }else{
                        $tym= explode(":",$post[$field]);
                        if(sizeof($tym)!=2){
                            $this->form->setError($field,'* Enter Valid Ending Time');
                        }else{
                            $trip_array[$i]['pick_up_ending']=$post[$field];
                        }
                    }
            for($j=1;$j<=$stops;$j++){
                $field = 'stop_'.$j;
            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Enter Pick Up Point');
            }
                $trip_array[$i]['pickups'][$j]['stop']=$post['stop_'.$i];
                
                $field="pick_up_".$j."_".$i;
                if(strlen($post[$field]) ==  0)
                {
                    $this->form->setError($field,'* Enter Pick Up Time');
                }else{
                    $tym= explode(":",$post[$field]);
                    if(sizeof($tym)!=2){
                        $this->form->setError($field,'* Enter Valid Pick Up Time');
                    }else{
                        $trip_array[$i]['pickups'][$j]['time']=$post[$field];
                    }

                }
                
            }
        }
        
        if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
             redirect('/transport/add_routes?route='.$this->input->post('route_name').'&trips='.$this->input->post('trips').'&stops='.$this->input->post('stops'), 'refresh'); 
         }else{
            
             $data= array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'rname' =>$post['route_name'],
                'trips' =>$post['trips'],
                'stops' =>$post['stops'],
                'status' =>1
             );
            $this->db->insert('routes',$data); 
            $rid= $this->db->insert_id();
            
            for($i=1;$i<=$trips;$i++){
                $tr=$trip_array[$i]['pickups'];
                $data = array(
                    'iid' => $this->session->userdata('staff_Org_id'),
                        'route_id' =>$rid,
                        'val' =>$i,
                        'vechile_id' =>$trip_array[$i]['vehicle'],
                        'fee' =>$trip_array[$i]['fee'],
                        'driver' =>$trip_array[$i]['driver'],
                        'status'=>1
                );
                $this->db->insert('trips',$data); 
                $tripid= $this->db->insert_id();
                $max_tym = explode(":",$trip_array[$i]['pick_up_ending']);
                $end_tym = explode(":",$trip_array[$i]['return_tym']);
                $max_mk= mktime($max_tym[0], $max_tym[1]); 
                $return_mk= mktime($end_tym[0], $end_tym[1]);
                foreach ($tr as $key => $value) {
                  $ptym = explode(":",$value['time']);
                  $ptym_mk= mktime($ptym[0], $ptym[1]); 
                  //echo "<br/>diff :".($max_mk-$ptym_mk);
                  $ret=date("H:i",$return_mk+($max_mk-$ptym_mk));
                   $data= array(
                        'iid' => $this->session->userdata('staff_Org_id'),
                        'pickup_point' =>$value['stop'],
                        'pick_up' =>$value['time'],
                        'drop' =>$ret,
                        'trip' =>$tripid
                     );
                    $this->db->insert('trip_route',$data); 
                    
                }
            }
             $this->session->set_userdata('route_add_Sucess', 'New Vehicle Created Sucessfully'); 
             
            redirect('/transport/routes', 'refresh'); 
         
            
         }
        
    }
    
    public function viewroutes($route_id){
       $route=  $this->check_route($route_id);
         if(!$route){
            redirect("transport/routes",'refresh');
         }else{
             $data['route']=$route;
             $this->load->view("transport/view_route",$data);
         }
    }
    
    private function check_student($std_id){
         $query =" select *  from stud_transport where iid='".$this->session->userdata('staff_Org_id')."'  AND stud_id = '".$std_id."' ";
        $query = $this->db->query($query);
        if($query->num_rows()==0){
            return TRUE;
        } else{
            return FALSE;
        }
    }
    
    private function check_route($route_id){
          $query=  $this->db->query("SELECT  * FROM `routes`  WHERE route_id='".$route_id."' AND iid='".$this->session->userdata('staff_Org_id')."'");  
          if($query->num_rows()>0){
              return $query->row();
          }else{
              return FALSE;
          }
         
    }
    
    public function load_trips(){
            $route=  $this->input->post("route");
            $query = " select * from trips  where iid='".$this->session->userdata('staff_Org_id')."' AND route_id = '".$route."' ";
            $query = $this->db->query($query);
              $query=$query->result();
              ?>
                <option value="">Select A Trip</option>
                <?php
                $i=1;
              foreach($query as $val){
                  ?>
            <option value="<?php echo $val->trip_id.",".$val->fee ?>" >
                Trip - <?php echo $i++ ?>
            </option>
               <?php
              }
        }
        
    public function load_pickups(){
            $trips=  $this->input->post("trip");
            $query = " select * from trip_route where iid='".$this->session->userdata('staff_Org_id')."' AND trip = '".$trips."' ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Pick-Up Point</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->trid.",".$val->pick_up ?>" >
                    <?php echo $val->pickup_point." ( ".$val->pick_up.")" ?>
                </option>
             <?php
            }

        }
    
    public function  add_stud_record (){
        $student =$this->input->post("student");
        //pickups
         $pickups =$this->input->post("pickups");
       $fee=$this->input->post("fee");
       $count=0;
       if(strlen($student)==0){
           $count++;
           echo "Please select Student";exit;
       }else{
           $v= $this->check_student($student);
           if(!$v){
               $count++;
               echo "* Student Already Assigned a route";exit;
           }
       }
       if(strlen($pickups)==0){
          $count++;
          echo "Please select Pick -up Point";exit; 
       }
       if(strlen($this->input->post('fee_payee'))==0){
         $count++;
          echo "Please select Trip";exit;   
       }
       if(strlen($fee)==0){
          $count++;
          echo "Please Enter Fee Amount To Paid";exit; 
       }
       
       if($count==0){
          $data= array(
              'iid' =>$this->session->userdata('staff_Org_id'),
              'stud_id' =>$student,
              'trip_route_id' =>$pickups,
              'fee_amount' =>$fee,
              'timestamp' =>time()
          ); 
          
          $this->db->insert('stud_transport',$data); 
          $this->session->set_userdata('stu_route_add_Sucess', 'Student Added To The Route Sucessfully..'); 
          ?>
              <script>
                        window.location.href = '<?php echo base_url() ?>index.php/transport/stud_details';
              </script>
          <?php
       }
       
    }
    
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
        
    public function send_sms($trip=""){
        if($trip==""){
            redirect("transport/routes","refresh"); 
        }else{
             $query = " select t.trip_id, r.rname , t.val,t.fee,s.name ,s.phone from trips t JOIN routes r ON r.route_id=t.route_id JOIN staff s ON t.driver=s.id  where t.iid='".$this->session->userdata('staff_Org_id')."' AND trip_id = '".$trip."' ";
             $query = $this->db->query($query)->row();
             //send_sms
             $data['trip'] =$query;
             $this->load->view("transport/send_sms",$data);
        }   
    }
    
    public function load_contacts(){
        //echo "<pre>";
       // print_r($_POST);
        $q="SELECT s.phone from stud_transport st JOIN student s  ON st.stud_id =s.student_id where st.trip_route_id='".$this->input->post("id")."' ";
        $q=$this->db->query($q)->result();
        $mobile=$this->input->post("mobile");
        $mobile=trim($this->input->post("mobile"));
        $mobile = str_replace(" ","", $mobile);
        $mobile.="\n";
        foreach($q as $val){
           $mobile.=$val->phone."\n"; 
        }
       
        $mobile = array_filter(explode("\n",$mobile));
        $mobile = implode("\n",$mobile);
        echo $mobile;
    }    
    
    public function remove_contacts(){
        $q="SELECT s.phone from stud_transport st JOIN student s  ON st.stud_id =s.student_id where st.trip_route_id='".$this->input->post("id")."' ";
        $q=$this->db->query($q)->result();
        $mobile=trim($this->input->post("mobile"));
        $mobile = str_replace(" ","", $mobile);
        $t="";
        foreach($q as $val){
           $t.=$val->phone."\n"; 
        }
       
        $t=  array_filter(explode("\n",$t));
        $mobile =explode("\n",$mobile);
        $mobile=array_filter(array_diff($mobile,$t));
        $mobile =implode("\n",$mobile);
        echo $mobile;
        
    }
    
    public function  print_tripdetails($trip=""){
        if($trip==""){
            redirect("transport/routes","refresh"); 
        }else{
             $query = " select t.trip_id, r.rname , t.val,t.fee,s.name ,s.phone from trips t JOIN routes r ON r.route_id=t.route_id JOIN staff s ON t.driver=s.id  where t.iid='".$this->session->userdata('staff_Org_id')."' AND trip_id = '".$trip."' ";
            $query = $this->db->query($query)->row();
           //echo "<pre>";
           // print_r($query);
             $institute=  $this->fetch_institute_details();
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trip Details</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
                    
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
</style>
</head>
<body>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="148" height="60" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />';
               ?>
              <h2 style="text-align: center; color:  #009933"><strong><?php echo strtoupper($query->rname." -- Trip - ".$query->val) ?> </strong></h2>
              <br/>
              <h4>
                  <span style=" float: left">Driver :&nbsp;<?php echo strtoupper($query->name) ?></span><span style=" float: right">Contact No :&nbsp;<?php echo $query->phone ?></span>
              </h4>
              <hr style=" clear: both; "/>
              <h3 style=" text-align: center;padding-top: 10px; color:  #009933">Route Map</h3>
              <?php
                                            $route_map =$this->db->query( "SELECT * FROM `trip_route` where trip = '".$query->trip_id."' ");
                                            $route_map = $route_map->result();
                                            ?>
                                        <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                        
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Pick-up Point</th>
                                                    <th>Pick-Time</th>
                                                    <th>Drop-Time</th>
                                                </tr>
                                                <?php
                                                $j=1;
                                            foreach($route_map as $rou){
                                             ?>
                                                <tr>
                                                    <td><?php echo $j++ ?></td>
                                                    <td><?php echo $rou->pickup_point ?></td>
                                                    <td><?php echo $rou->pick_up ?></td>
                                                    <td><?php echo $rou->drop ?></td>
                                                </tr>   
                                              <?php
                                            }
                                                ?>
                                        </table>
              <br/>
              <h3 style=" text-align: center;padding-top: 10px; color:  #009933">Student Details</h3>
              <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                                                                          <tr>
                                                    <th>S.No</th>
                                                    <th>Student</th>
                                                    <th>phone</th>
                                                    <th>Pick Up Point</th>
                                                    <th>pick-up </th>
                                                    <th>Drop</th>
                                                </tr>
                                                <?php
                                             $stud="SELECT st.stid,st.fee_amount , s.student_id ,s.name ,s.phone,s.userid,tr.pickup_point , r.rname , tr.pick_up,tr.drop FROM stud_transport st JOin student s On st.stud_id= s.student_id JOIN trip_route tr ON tr.trid=st.trip_route_id JOIN trips t ON tr.trip=t.trip_id JOIN routes r On t.route_id = r.route_id where st.iid='".$this->session->userdata('staff_Org_id')."' AND tr.trip='".$query->trip_id."' ";
                                                $stud = $this->db->query($stud)->result();
                                                $i=1;
                                                if(sizeof($stud)==0){
                                                    ?>
                                                <tr>
                                                    <td colspan="6" style=" text-align: center; color: red">** No Student Records Found..</td>
                                                </tr>
                                                     <?php
                                                }
                                                foreach ($stud as $value) {
                                                    
                                                   ?>
                                                    <tr>
                                                        <td><?php echo $i++ ?></td>
                                                        <td><?php echo $value->name."<br/>( ".$value->userid.")" ?></td>
                                                        <td><?php echo $value->phone ?></td>
                                                        <td><?php echo $value->pickup_point ?></td>
                                                        <td><?php echo $value->pick_up ?></td>
                                                        <td><?php echo $value->drop ?></td>
                                                    </tr>
                                                    <?php
                                                }

                                                ?>
                                        </table> 
               </div>
                                       <script>
                                         window.print();
                                    </script>
                                </body>
                            </html>
               <?php
            
            
        }
    }
    
    public function pay_fee(){
        $student =$this->input->post('student');
        $payee =$this->input->post('payee');
        $tpaid =$this->input->post('total');
        $fee_Amount =$this->input->post('fee_amount');
        $bal =$fee_Amount-$tpaid;
        if(!is_numeric($payee)){
            echo "** Please enter Numeric value";exit;
        }
        if(!is_numeric($student)){
            echo "** Please Select Student";exit;
        }
        if($payee >$bal){
              echo "** Amount Excedeed Balance Amount";exit;
        }
        $n=  $this->get_invoice_no()+1;
         $no=  $this->make_9digit($n);
         $inv=  $this->fetch_institute_code()."tnsp".$no;
         
        
        $data= array(
              'iid' =>$this->session->userdata('staff_Org_id'),
              'student' =>$student,
              'fee' =>$payee,
              'invoice_no'=>$inv,
               'added_by' =>$this->session->userdata('staff_id'),
              'timestamp' =>time()
          ); 
        
          $this->db->insert('transport_fee',$data); 
        $n++; 
         $this->update_invoice_no($n-1);
        
          $this->session->set_userdata('stud_fee_payee_Sucess', $inv); 
        ?>
              <script>
                window.location.href = '<?php echo base_url() ?>index.php/transport/fee_payment';
              </script>
              <?php
    }
    
    public function load_balance(){
       // print_r($_POST);
        $paid =" SELECT sum(`fee`)  as total FROM `transport_fee` WHERE  student = '".$this->input->post("student")."'";
        $paid = $this->db->query($paid);
        $paid = $paid->row();
       // print_r($paid);exit;
        if(strlen($paid->total)!=0){
          $paid=  $paid->total;
        }else{
            $paid=0;
        }
        
        echo $this->input->post('fee_amount')-$paid;
        ?>
              <script>
                  $('#fee_paid').html('<?php echo $paid ?>');
              </script>
        <?php
    }

    public function maintenance(){
       $this->load->view('transport/maintaince');  
    }

    public function fee_payment(){
       $this->load->view('transport/fee_payemnts');  
    }
    
    public  function add_maintainence(){
        
        if(strlen($this->input->post('vechile'))==0){
            echo "** Please select Vechile";exit;
        }
        if(strlen($this->input->post('type'))==0){
            echo "** Please select Type Of Maintaince";exit;
        }
        if(strlen($this->input->post('amount'))==0){
            echo "** Enter Amount";exit;
        }elseif(!is_numeric($this->input->post('amount'))){
             echo "** Enter Numeric Value ";exit;
        }
        
        if(strlen($this->input->post('reason'))==0){
            echo "** Enter Reason For Maintaince";exit;
        }

        $data= array(
              'iid' =>$this->session->userdata('staff_Org_id'),
              '	vech_id' =>$this->input->post('vechile'),
              'reason' =>$this->input->post('reason'),
              'timestamp' =>time(),
              'amount' =>$this->input->post('amount'),
              'type'  =>$this->input->post('type')
          ); 
          $this->db->insert('trans_maintaince',$data); 
          $this->session->set_userdata('trans_maintaince_Sucess', 'New Maintenance Record Added Sucessfully ..'); 
        ?>
              <script>
                window.location.href = '<?php echo base_url() ?>index.php/transport/maintenance';
              </script>
              <?php
        
        
    }


    public function search(){
            $this->load->view('transport/search');  
    }
    
    public function stud_details(){
        $this->load->view('transport/view_stud');  
    }
    public function routes(){
           $this->load->view('transport/routes');  
    }
    
    public function assign_route(){
         $this->load->view('transport/assign_route');     
    }
    private function make_9digit($id){
          if(strlen($id)>9){
              return $id;
          }
          $str="";
          $len=9-strlen($id);
          for($i=0;$i<$len;$i++){
             $str.="0";
          }
          $str.=$id;
          return $str;
        }
        
    private function fetch_institute_code(){
        $query = $this->db->query("SELECT `code` FROM `institute_code` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
        $result=$query->row();
        return $result->code;   
      }
    
    private function get_invoice_no(){
            $query = $this->db->query("SELECT `last_id` FROM `other_invoice` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=2 ");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'start'=>1,
                    'type'=>2,
                    'last_id'=>0
                    );
                $this->db->insert('other_invoice',$data);
                return 0;
            }else{
                $result=$query->row();
                return $result->last_id;     
            }          
      }
      
    private function update_invoice_no($no){
      $this->db->query("UPDATE `other_invoice` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=2   ");  
    }
    
function convert_number($number) {
		if (($number < 0) || ($number > 999999999)) {
			throw new Exception("Number is out of range");
		}
		$Gn = floor($number / 1000000);
		/* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);
		/* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);
		/* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);
		/* Tens (deca) */
		$n = $number % 10;
		/* Ones */
		$res = "";
		if ($Gn) {
			$res .= $this->convert_number($Gn) .  "Million";
		}
		if ($kn) {
			$res .= (empty($res) ? "" : " ") .$this->convert_number($kn) . " Thousand";
		}
		if ($Hn) {
			$res .= (empty($res) ? "" : " ") .$this->convert_number($Hn) . " Hundred";
		}
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " and ";
			}
			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n];
			} else {
				$res .= $tens[$Dn];
				if ($n) {
					$res .= "-" . $ones[$n];
				}
			}
		}
		if (empty($res)) {
			$res = "zero";
		}
		return $res;
	}
        
        public function Invoice($inv){
         $inv =  trim($inv);
         if(strlen($inv)==0){
             redirect("Transport/fee_payment","refresh");
         }else{
             $inv= "SELECT f.*,st.name from transport_fee f JOIN staff st ON f.added_by=st.id where invoice_no = '".$inv."'";
             $inv= $this->db->query($inv);
             if($inv->num_rows()==0){
                   redirect("Hostel/pay_fee","refresh");
             }else{
                 $inv =$inv->row();
              //   print_r($inv);
                 
                   $student=  $this->db->query("SELECT s.student_id,s.photo,s.roll,s.name,s.userid,c.id as cls_id,c.name as clsname , se.name as section,hs.fee_amount as fee, ( SELECT SUM(fee) from transport_fee WHERE student = s.student_id )  as paid FROM   student s JOIN stud_transport hs ON hs.stud_id =s.student_id  JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id   WHERE s.student_id='".$inv->student."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
          $student=$student->row();
         // print_r($student);
               $institute=$this->fetch_institute_details();
                  $std_image="dummy_user.png";
                      if(!(strlen($student->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$student->photo)){
                           $std_image =$student->photo  ;
                         }            
                      }
                     $std_html='<div style="border:2px solid #00306C;height:115px;">
                                    <div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="70" style="padding:5px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:95px;">&nbsp;</div>
                                    <div  style="float:left;padding:2px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$student->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$student->clsname.' - '.$student->section.'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$student->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>Roll No</td>
                                        <td>:</td>
                                        <th>'.$student->roll.'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div>
                                    <br style="clear:both" />
                                    ';
                        $inv_time_html='<br style="clear:both" />
<div class="subject">Transport Department Invoice No: '.$inv->invoice_no.'<!--for Cement.--></div><br />';
                         $payment='<table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                ';
                         $f_cat_html='<tr><th style="width:50%"  >TRANSPORTATION FEE</th><td style="width:50%" >'.$inv->fee.'</td></tr>';
                          
                         $payment.=$f_cat_html."</table>";
                         $total_bal='<br style="clear:both" /><br/><div >
                            <h2  style=" font-size :15px">
                        <span style="text-align:left; font-size :15px">&nbsp;In Words:&nbsp;'. $this->convert_number($inv->fee) .' Only /-</span>
                        </h2>
                        <h2  style=" font-size :15px">
                        <span style="text-align:left; font-size :15px">&nbsp;TOTAL FEE:&nbsp;'.$student->fee.'</span>
                        <span style=" float:right;">BALANCE FEE :&nbsp;<span style=" color: blue ">'.$student->fee-$student->paid.'</span>&nbsp;&nbsp;</span>
                        </h2>
                         </div>';
                         echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fee Recipt</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />


<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
</style>
</head>
<body>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="148" height="60" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />'.$std_html.'
<div class="subject">Transport Department Invoice No: '.$inv->invoice_no.'<!--for Cement.--></div><br />
'.$payment.'<br />
<br/>
 <b>Total Fee In Words &nbsp;: '.$this->convert_number($inv->fee).' Rupees Only /-</b> <br />
 
 
 
<div class="left">
<p><b>Total Amount</b> : '.$student->fee.' /-</p>
</div>
<div class="right">
<p><b>Total Balance</b> : '.($student->fee-($student->paid)).' /-</p>
</div>
<div style="clear:both;"></div>
<div class="left">
<img width="300px" height="60px"  src="'. base_url()."/index.php/barcode/barcode/".$inv->invoice_no.'" />
</div>
<div style="float:left; padding-top:10px ; padding-left:40px">
	Issue Date  : '.date("d-m-y , H:i",$inv->timestamp).'
</div>
<div class="right" style="text-align:center ">	
<p>'.$inv->name.'<br/><b>Authorized Person</b></p>
</div>

<div style="clear:both;"></div>
</div>
</div>
</body>
</html>';    
             }
         }
     }    

        
}
?>