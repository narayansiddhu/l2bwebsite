<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->model('logs');
            $this->load->model('barcode');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
            
            $check="SELECT `fees` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->fees==0){
                 $this->session->set_userdata('blocked_module', 'Fees Module'); 
                redirect("/Acessdenied/","refresh");
            }
            
        }
        
	public function index()
	{
           if(is_null($this->session->userdata('staff_id'))){
             $this->load->view('login');
           }else{
               redirect('accounts/view','refresh');
           }	   
	}

    public function settings()
    {
            $this->load->view('accounts/setting');   
    }
    public function login()
    {
            $this->load->view('accounts/login');   
    }

    public function logout()
    {

            $this->session->unset_userdata('email'); 
            $this->session->unset_userdata('iid');
            
          if($this->session->userdata('staff_level') == 8){

            redirect("accounts/dashboard",'refresh');
          }
          else{
            redirect("dashboard",'refresh');
          }
    }

    public function otp()
    {
            $this->load->view('accounts/otp');
      
    }

    public function reset_password(){

      if($this->session->userdata('gm') == ''){

        redirect('accounts/dashboard','refresh');
      }else{

        $this->load->view('accounts/reset');
      }
    }

    public function reset(){

        $post=$_POST;
            //setError

        if(strlen($post['password']) ==  0)
            {

                    $this->session->set_userdata('pass_add_error', '* Please Enter Password');

                    redirect('/accounts/reset_password', 'refresh');
            } 

             $field = 'cnfrmpass';

             if(strlen($post[$field]) ==  0)
            {

                    $this->session->set_userdata('pass_add_error', '* Please Enter Confirm Password');

                    redirect('/accounts/reset_password', 'refresh');
            }
             if($post['password'] != $post['cnfrmpass']){
                    

                       $this->session->set_userdata('pass_add_error', '* Please Enter Same Password');

                    redirect('/accounts/reset_password', 'refresh');
            }

           

           
        

        elseif($this->session->userdata('otp') == 1 || $this->session->userdata('otp')== 2 || $this->session->userdata('otp')== 3){

            

            if($this->session->userdata('otp') == 1){

                $password = $post['password'];

                $otp = $this->session->userdata('otp');

                $id = $this->session->userdata('staff_Org_id');

             $sql= "UPDATE `accounts` SET `password`= '$password' WHERE `category`= '$otp' AND iid = '$id'";
              
              $query = $this->db->query($sql);

                    //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));
                $this->session->unset_userdata('gm');
                    

                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }
            elseif($this->session->userdata('otp') == 2){

                $password = $post['password'];

                $otp = $this->session->userdata('otp');

                $id = $this->session->userdata('staff_Org_id');

             $sql= "UPDATE `accounts` SET `password`= '$password' WHERE `category`= '$otp' AND iid = '$id'";
              
              $query = $this->db->query($sql);

               //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));

                $this->session->unset_userdata('gm');

                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }
            elseif($this->session->userdata('otp') == 3){

                $password = $post['password'];

                $otp = $this->session->userdata('otp');

                $id = $this->session->userdata('staff_Org_id');

             $sql= "UPDATE `accounts` SET `password`= '$password' WHERE `category`= '$otp' AND iid = '$id'";
              
              $query = $this->db->query($sql);

                //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));
                $this->session->unset_userdata('gm');

                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }

              redirect('/accounts/dashboard', 'refresh');

                //$msgcontent="Dear staff,\n Your Account Created Sucessfully\n please check the url and login credential\n" . "url :".  base_url()." \n username: admin@snetwork.in \n\n Password :" .$this->input->post['pass'];
               // $this->studparent->send_sms($msgcontent,$post['stmobile']);
                //$this->logs->insert_staff_log(7,'Created Staff :'.$post['stusername'],$id  );
                //$this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                // redirect('/accounts/settings', 'refresh'); 
        
    }

    }

    public function send(){

      if(isset($_POST['checks'])){

        $this->session->set_userdata('checks', $_POST['checks']);
      }

      $sql ="SELECT * FROM `accounts` WHERE iid=".$this->session->userdata('staff_Org_id');

            $query = $this->db->query($sql);
            $query = $query->row();
                  

          if(!isset($_POST['checks']))
            {

                    $this->session->set_userdata('pass_add_error1', '* Please select a radio button!');

                    redirect('/accounts/settings', 'refresh');
            }

         else if($query == ''){  
                $this->session->set_userdata('pass_add_error1', '* Password not yet created to reset password!');

                   redirect('/accounts/settings', 'refresh');
          }


        else if(isset($_POST['checks']) == 1 || isset($_POST['checks'])== 2 || isset($_POST['checks'])== 3){


       
        $rand =rand(100000,999999);
        $this->session->set_userdata('gm', $rand);
        $this->session->set_tempdata('otp', $rand, 300);
        $msg = "Hai, This is an OTP to reset your password is " .$rand;    //Message Here

        $query="select * from staff where iid=".$this->session->userdata('staff_Org_id');

        $query = $this->db->query($query);

          $query= $query->row();

          $mobile = $query->phone;

        //$url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$mobile."&from=School&message=".urlencode($msg);    //Store data into URL variable

        //$ret = file($url);    //Call Url variable by using file() function

        //if($ret){
        $time= time();
            $check = $_POST['checks'];

            $query="INSERT INTO `accounts_otp`(`mobile`, `OTP`, `category`, `timestamp`) VALUES ('$mobile','$rand', '$check', $time)";
            

            $query = $this->db->query($query);
            if($query){

              $this->session->set_userdata('Success','OTP Sent Sucessfully!');
              
            $this->load->view('accounts/otp');

        }
        //} 
    }

    }

    public function send1(){

          if(isset($_POST['resend'])){

            $last = $this->db->order_by('id',"desc")
                      ->limit(1)
                      ->get('accounts_otp')
                      ->row();

            //print_r($last);

                  $sql ="DELETE FROM `accounts_otp` WHERE id = ".$last->id;

                   $query = $this->db->query($sql);

                    if($this->session->userdata('checks') == 1 || $this->session->userdata('checks') == 2 ||$this->session->userdata('checks') == 3){
                      
                      $rand =rand(100000,999999);
        $this->session->set_tempdata('otp', $rand, 300);
        $msg = "Hai, This is an OTP to reset your password is " .$rand;    //Message Here

        $query="select * from staff where iid=".$this->session->userdata('staff_Org_id');

        $query = $this->db->query($query);

          $query= $query->row();

          $mobile = $query->phone;

        $url = "http://smslogin.mobi/spanelv2/api.php?username=lsateesh&password=lsateesh&to=".$mobile."&from=School&message=".urlencode($msg);    //Store data into URL variable

        $ret = file($url);    //Call Url variable by using file() function

        if($ret){

        $time= time();
            $check = $this->session->userdata('checks');

            $query="INSERT INTO `accounts_otp`(`mobile`, `OTP`, `category`, `timestamp`) VALUES ('$mobile','$rand', '$check', $time)";
            

            

            $query = $this->db->query($query);
            if($query){
              
            //$this->load->view('accounts/otp');

              $this->session->set_userdata('Success','OTP Sent Sucessfully!');

            redirect('accounts/otp','refresh');

            

        }
        }
                    }
                  }
    }

     public function validate_otp(){



         if((isset($_POST['otp']))){

          $otp=$_POST['otp'];

          if($this->session->tempdata('otp') == ''){
              $otp=$_POST['otp'];

            $query="DELETE FROM `accounts_otp` WHERE OTP =".$otp;
            $query = $this->db->query($query);
        }
                
                $otp_status = $this->operations->validate_otp($otp);
                if($otp_status){
                   $response['otp_status'] = "Success";
                }else{
                      $response['otp_status'] = "invalid";
                }
                if ($otp_status == 'Success') {
                    $response['redirect_url'] = base_url()."/index.php/accounts/reset_password";
                }
                //Replying ajax request with validation response
                echo json_encode($response);
                       
           }else{
               //redirect("accounts/settings",'refresh');
           }   //$ret stores the msg-id

    }

    public function login_check(){
            
           if((isset($_POST['username']) && isset($_POST['password'])  )){
                $id = $_POST['id'];
                $username=$_POST['username'];
                $password=$_POST['password'];
                $login_status = $this->operations->validated_user($id, $username, $password);
                if($login_status){
                   $response['login_status'] = "Success ";
                }else{
                      $response['login_status'] = "invalid";
                }
                if ($login_status == 'Success' && $id == '1') {
                    $response['redirect_url'] = base_url()."/index.php/salary/pay_salary";
                }
                else if ($login_status == 'Success' && $id == '2') {
                    $response['redirect_url'] = base_url()."/index.php/expenditure/";
                }
                else if ($login_status == 'Success' && $id == '3') {
                    $response['redirect_url'] = base_url()."/index.php/accounts/view";
                }
                //Replying ajax request with validation response
                echo json_encode($response);
                       // echo $login_status;
           }else{
               redirect("Login/",'refresh');
           }
        }

     public function settings_add(){
                                    
            
            $post=$_POST;
            //setError

            if(!isset($post['checks']) == 1 && !isset($post['checks1'])== 2 && !isset($post['checks2'])== 3){

                  $this->session->set_userdata('pass_add_error', '* Please select any checkbox!');

                    redirect('/accounts/settings', 'refresh');

            }

            if(strlen($post['password']) ==  0)
            {

                    $this->session->set_userdata('pass_add_error', '* Please Enter Password');

                    redirect('/accounts/settings', 'refresh');
            }

             $field = 'cnfrmpass';

             if(strlen($post[$field]) ==  0)
            {

                    $this->session->set_userdata('pass_add_error', '* Please Enter Confirm Password');

                    redirect('/accounts/settings', 'refresh');
            }
           if($post['password'] != $post['cnfrmpass']){
                    

                       $this->session->set_userdata('pass_add_error', '* Please Enter Same Password');

                    redirect('/accounts/settings', 'refresh');
            }

        else if(isset($post['checks']) == 1 || isset($post['checks1'])== 2 || isset($post['checks2'])== 3){

            $query=  $this->db->query("SELECT * from accounts where (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks']."') OR (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks1']."') OR (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks2']."')");
            if ($query->num_rows() > 0) {

                    

                     $this->session->set_userdata('pass_add_error', '* Password is already created.');

                    redirect('/accounts/settings', 'refresh');
            }else{

            if(isset($post['checks']) == 1){

             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks'],
                );
                $this->db->insert('accounts',$data);

                    //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));

                    

                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }
            elseif(isset($post['checks1']) == 2){

             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks1'],
                );
                $this->db->insert('accounts',$data);

               //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));


                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }
            elseif(isset($post['checks2']) == 3){

             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks2'],
                );
                $this->db->insert('accounts',$data);

                //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));
                  
                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh'); 
            }

                      
                //$msgcontent="Dear staff,\n Your Account Created Sucessfully\n please check the url and login credential\n" . "url :".  base_url()." \n username: admin@snetwork.in \n\n Password :" .$this->input->post['pass'];
               // $this->studparent->send_sms($msgcontent,$post['stmobile']);
                //$this->logs->insert_staff_log(7,'Created Staff :'.$post['stusername'],$id  );
                //$this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                // redirect('/accounts/settings', 'refresh'); 
        }
    }

     else if(isset($post['checks']) == 1 && isset($post['checks1'])== 2 && isset($post['checks2'])== 3){

            $query=  $this->db->query("SELECT * from accounts where (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks']."') OR (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks1']."') OR (iid = '".$this->session->userdata('staff_Org_id')."' AND category = '".$post['checks2']."')");
            if ($query->num_rows() > 0) {

                      $this->session->set_userdata('pass_add_error', '* Password is already created.');

                    redirect('/accounts/settings', 'refresh');
            }else{

             $data = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks'],
                );
              $data1 = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks1'],
                );
              $data2 = array(
                'iid' => $this->session->userdata('staff_Org_id'),
                'email' => 'admin@snetwork.in',
                'password' => $post['password'],
                'status' => '1',
                'category' => $post['checks2'],
                );
                $this->db->insert('accounts',$data);
                $this->db->insert('accounts',$data1);
                $this->db->insert('accounts',$data2);

                  //$this->session->set_userdata('iid', $this->session->userdata('staff_Org_id'));   
                      
                //$msgcontent="Dear staff,\n Your Account Created Sucessfully\n please check the url and login credential\n" . "url :".  base_url()." \n username: admin@snetwork.in \n\n Password :" .$this->input->post['pass'];
               // $this->studparent->send_sms($msgcontent,$post['stmobile']);
                //$this->logs->insert_staff_log(7,'Created Staff :'.$post['stusername'],$id  );
                $this->session->set_userdata('pass_add_Sucess', 'Sucessfully Created a Password'); 
                 redirect('/accounts/dashboard', 'refresh');
        } 
        }


        }

         
              
        
        
        public function view($class=0,$section=0){
          $query=  $this->db->query("select * from accounts where iid= '".$this->session->userdata('staff_Org_id')."' AND category ='3'");
            if(is_null($this->session->userdata('email')) && $this->router->fetch_class() == 'accounts' && $this->session->userdata('iid') != $this->session->userdata('staff_Org_id') && $query->num_rows() > 0){
                 redirect("accounts/login/3",'refresh'); 

              }
              else{
                if($class==0 || $section==0){
                 $this->load->view('accounts/view');
            }else{
                $sec=  $this->check_section($class, $section);
                if(!$sec){
                    $this->load->view('accounts/view'); 
                }else{
                    $data['section']=$sec;
                
                   $this->load->view('accounts/section_accounts',$data); 
                }
            }
              }
            
        }
        
        public function history(){
           $this->load->view('accounts/history'); 
        }
        
        public function printout($inv_no=""){
            $inv_no=trim($inv_no);
            if(strlen($inv_no)==0){
                redirect("accounts/add_record",'refresh');
            }else{
                $a=$this->fetch_record_info($inv_no);
                if(!$a){
                    redirect("accounts/add_record",'refresh');
                }else{
                   $total=  $this->db->query("SELECT sum(fc.fee) as total FROM fee_class fc JOIN student s ON fc.cls_id = s.class_id WHERE s.student_id='".$a['student']['student_id']."'  ");
                   $total=$total->row();
                   $total=$total->total;
                   $cons= $this->db->query("SELECT sum(amount) as total FROM `fee_accounts` WHERE student_id='".$a['student']['student_id']."'  ");
                   $cons=$cons->row();
                   $cons=$cons->total;
                   
                   $bal=  $this->db->query("SELECT sum(amount) as total FROM `fee_concession` WHERE std_id='".$a['student']['student_id']."'  ");
                   $bal=$bal->row();
                   $bal=$bal->total;
                   $institute=$this->fetch_institute_details();
                  $std_image="dummy_user.png";
                      if(!(strlen($a['student']['photo'])!=0)){
                         if(file_exists(assets_path."/uploads/".$a['student']['photo'])){
                           $std_image =$a['student']['photo']  ;
                         }            
                      }
                     $student='<div style="border:2px solid #00306C;height:90px;">
                                    <div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="70" style="padding:5px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:73px;">&nbsp;</div>
                                    <div  style="float:left;padding:2px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$a['student']['name'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$a['student']['cls_sec'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['userid'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Roll No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['roll'].'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div>
                                    <br style="clear:both" />
                                    ';
                        $inv_time_html='<br style="clear:both" />
<div class="subject">Invoice No: '.$inv_no.'<!--for Cement.--></div><br />';
                         $payment='<table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                ';
                         $rammount=0;
                         $f_cat_html="<tr>";
                         $payment_html="<tr>";
                         foreach ($a[$inv_no]  as $r){
                             $f_cat_html.='<th>'. strtoupper($r['category']).'</th>';
                              $payment_html.='<td>'.$r['amount'].'</td>';
                          $rammount+=$r['amount'];
                         }
                         $f_cat_html.='<th>TOTAL AMOUNT</th></tr>';
                         $payment_html.='<th >'.$rammount.'</th></tr>';
                         
                         $payment.=$f_cat_html.$payment_html."</table>";
                         
                         $total_bal='<br style="clear:both" /><br/><div >
                            <h2  style=" font-size :15px">
                        <span style="text-align:left; font-size :15px">&nbsp;In Words:&nbsp;'. $this->convert_number($rammount) .' Only /-</span>
                        </h2>
                        <h2  style=" font-size :15px">
                        <span style="text-align:left; font-size :15px">&nbsp;TOTAL FEE:&nbsp;'. date("d-m-y , H:i", $a['student']['paid_time']).'</span>
                        <span style=" float:right;">BALANCE FEE :&nbsp;<span style=" color: blue ">'.$inv_no.'</span>&nbsp;&nbsp;</span>
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
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="158" height="95" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />

<div style="border:2px solid #00306C;height:110px;">

<div style="float:left;width:40%">
	<img src="'.assets_path .'/uploads/'.$std_image.'" width="160" height="90" style="padding:5px;margin:5px;" />
</div>
<div class="verticalLine" style="float:left;height:85px;">&nbsp;</div>
<div  style="float:left;padding:3px;">
<table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$a['student']['name'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$a['student']['cls_sec'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['admission_no'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Roll No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['roll'].'</th>
                                    </tr>

                                    </table>
                                    
</div>
</div>

<br style="clear:both" />
<div class="subject">Invoice No: '.$inv_no.'<!--for Cement.--></div><br />
'.$payment.'<br />

 <b>Total Fee In Words &nbsp;: '.$this->convert_number($rammount).' Only /- <br />
 Payment Type&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : Cash </b> <br />
 
 
 
<div class="left">
<p><b>Total Amount</b> : '.$total.' /-</p>
</div>

<div class="right">
<p><b>Total Balance</b> : '.($total-($cons+$bal)).' /-</p>
</div>


<div style="clear:both;"></div>


<div class="left">
<img width="300px" height="60px"  src="'. base_url()."/index.php/barcode/barcode/".$inv_no.'" />
</div>
<div style="float:left; padding-top:10px ; padding-left:40px">
	Issue Date  : '.date("d-m-y , H:i", $a['student']['paid_time']).'
</div>


<div class="right">	
<p><b>Authorized Signatory</b></p>
</div>

<div style="clear:both;"></div>
</div>
</div>
<hr/>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="158" height="95" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />

<div style="border:2px solid #00306C;height:110px;">

<div style="float:left;width:40%">
	<img src="'.assets_path .'/uploads/'.$std_image.'" width="160" height="90" style="padding:5px;margin:5px;" />
</div>
<div class="verticalLine" style="float:left;height:85px;">&nbsp;</div>
<div  style="float:left;padding:3px;">
<table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$a['student']['name'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$a['student']['cls_sec'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['admission_no'].'</th>
                                    </tr>
                                    <tr>
                                            <td>Roll No</td>
                                        <td>:</td>
                                        <th>'.$a['student']['roll'].'</th>
                                    </tr>

                                    </table>
                                    
</div>
</div>
<br style="clear:both" />
<div class="subject"><span style="float:left;">Office Copy </span><span style="float: right;">Invoice No: '.$inv_no.'</span></div><br />
'.$payment.'<br />
<div style=" float:left; width:33.3%" >
<p><b>Total Fee In Words &nbsp;:</b> : '.$this->convert_number($rammount).' /-</p>
<p><b>Payment Type</b> : Cash Mode </p>
</div>
<div style=" float:left; text-align: center; width:33.3%" >
<p>&nbsp;</p>
<p style="text-align: center;"><b>Issue Date  :</b> '.date("d-m-y , H:i", $a['student']['paid_time']).'</p>
</div>
<div style=" float:left;text-align: right; width:33.3%" >
<p style="text-align: right;"><b>Total Amount</b> : '.$total.' /-</p>
<p style="text-align: right;" ><b>Total Balance</b> : '.($total-($cons+$bal)).' /-</p>
</div>


<div style="clear:both;"></div>
<div  style="float:left; text-align: left; width:25%; padding-top:10px ; ">
<p style="text-align: left;" ><b>Reciever Signature</b></p>
</div>
<div style="float:left; text-align: center;  width:50%;" >
<img width="300px" height="60px"  src="'. base_url()."/index.php/barcode/barcode/".$inv_no.'" />
	
</div>
<div style="float: right; text-align: right; width:25%;">	
<p style="text-align: right;"><b>Authorized Signature</b></p>
</div>

<div style="clear:both;"></div>
<script>
window.print();
</script>
</div>
</div>
</body>
</html>';
                         
                }
                
            }
            
            ?>
           <?php
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
        
        public function view_all(){
           $this->load->view('accounts/history_all'); 
        }
        
        public function Balance($class=0,$section=0){
            if($class==0 || $section==0){
                 $this->load->view('accounts/view');
            }else{
                $sec=  $this->check_section($class, $section);
                if(!$sec){
                    $this->load->view('accounts/view'); 
                }else{
                    $data['section']=$sec;
                    $data['balance']=  $this->fetch_account_balance($section);
                    $this->load->view('accounts/balance',$data); 
                }
            }
        }
        
        public function  bal_checker(){
            $count=0;
            if(!is_numeric($_POST['balance'])){
                echo 0;
            }elseif(!is_numeric($_POST['amount'])){
                 echo 0;
            }else{
                if($_POST['amount']>$_POST['balance']){
                   echo 0; 
                }else{
                    echo 1;
                }
            }
            
        }


        public function print_report($class=0,$section=0,$type=""){
            
             $this->load->library('Pdf');
            if($class==0 || $section==0){
                 $this->load->view('accounts/view');
            }else{
                $section=  $this->check_section($class, $section);
                if(!$section){
                    $this->load->view('accounts/view'); 
                }else
                    {
                    $institute=  $this->fetch_institute_details();
                echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Accounts Brief Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
<link rel="stylesheet" href="'.assets_path.'/js/jquery.min.js">                    
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
<hr color="#00306C" />     
<h4 style=" text-align: center;color: orange "><strong>Accounts Report of '.$section->class." , ".$section->section .'</strong></h4>'; 
                   ?> 
          
                <div style=" clear: both" class="col-sm-12">
                    <?php
                    if($type=="balance"){
                      $accounts= $this->fetch_account_balance($section->sid);
                      
                      ?>               
                        
                        <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">

                                <tr>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Userid</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                </tr>
                                <?php
                                  foreach($accounts as $value){
                                    ?>
                                <tr>
                                    <td><?php echo $value->roll ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->userid ?></td>
                                    <td><?php 
                                            if( strlen($value->total)==0){
                                                   echo 0;
                                            }else{
                                               echo $value->total;
                                            }
                                            ?></td>
                                    <td><?php echo ($section->fee-$value->total) ?></td>
                                </tr>
                                   <?php
                                  }
                                ?>
                        </table>
                             
                    <?php
                        }
                    else{
                        $accounts=  $this->fetch_account_records($section->sid);
                       ?>               
                            <br/>
                        <?php
                        $cat ="";
                        $fees=0;
                        $i=1;

                             $q= $this->db->query("SELECT f.fid,f.category as cat,fc.category , f.fee FROM `fee_class` f JOIN fee_category fc on f.category = fc.cid where  f.cls_id= '".$section->class_id."' ");
                             $q =$q->result();
                             foreach($q as $val){
                               $cat.=$val->cat.",";
                               $fees =$fees+$val->fee;
                             }
                             $cat= substr($cat,0, strlen($cat)-1);
                             $cat = array_filter(explode(",",$cat));
                        ?>
                      
                <?php
                $overall = 0;
                  $stud=$this->db->query("SELECT s.* , (SELECT sum(amount) FROM fee_concession fc where fc.std_id=s.student_id ) as concession FROM `student` s    WHERE s.class_id ='".$section->class_id."' AND s.section_id ='".$section->sid."' AND s.iid = '".$this->session->userdata("staff_Org_id")."' ");
                 $stud = $stud->result();
                $fee=$this->db->query("SELECT fa.* FROM `fee_accounts` fa JOIN student s ON fa.student_id = s.student_id JOIN fee_category fc ON fa.category = fc.cid WHERE s.section_id='".$section->sid."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
                $fee = $fee->result();
            
                $accounts=array();
                foreach ($fee as $value) {
                    if(isset($accounts[$value->student_id][$value->category]) ){
                         $accounts[$value->student_id][$value->category]=$accounts[$value->student_id][$value->category]+$value->amount;
                    }else{
                         $accounts[$value->student_id][$value->category]=$value->amount;
                    }
                   $overall+=$value->amount;
                }
               $total = array();
               $gbal=  ($section->students * $fees)-$overall;
              
               ?>
                
            
                        <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">                
                            <tr >
                                <th>Roll No</th>
                                <th>Name</th>
                                <?php
                                   foreach($q as $val){
                                       ?>
                                     <th><?php echo $val->category."<br/>(".$val->fee.")"; ?></th>
                                     <?php
                                   }
                                ?>
                            <th>Concession</th>
                            <th>Balance</th>
                            </tr>
                        
                            <?php
                            $total_con=0;
                                foreach($stud as $s){
                                    $indiv_paid=0;
                                 ?> 
                            <tr>
                                <td><?php echo $s->roll ?></td>
                                <td><?php echo $s->name ?></td>
                                 <?php
                                 foreach($cat as $c){
                                     ?>
                                <td>
                                <?php 
                                   
                                   if(isset($accounts[$s->student_id][$c]) ){
                                       echo $accounts[$s->student_id][$c];
                                       $indiv_paid=$indiv_paid+$accounts[$s->student_id][$c];
                                      if(isset($total[$c])) {
                                          $total[$c]=$total[$c]+$accounts[$s->student_id][$c];
                                      }else{
                                          $total[$c]=$accounts[$s->student_id][$c];
                                      }
                                   }else{
                                       echo "--";
                                   }
                                ?>
                                </td>
                                   <?php
                                 }
                                 ?>
                                <td><?php 
                                    $con=0;
                                    if(strlen($s->concession)>0){
                                    echo $con=$s->concession;
                                    $total_con =$total_con+$s->concession;
                                    } else{
                                        echo "--";
                                    }
                                ?></td>
                                <td>
                                    <?php echo $fees-($indiv_paid +$con ) ?>
                                </td>
                            </tr>
                                 <?php
                                }
                             ?>  
                            <tr>
                                <td colspan="2" style=" text-align: center "><strong>Total</strong></td>
                                <?php
                                 foreach($cat as $c){
                                     ?>
                                <td><?php
                                if(isset($total[$c])) {
                                         echo $total[$c];
                                      }else{
                                         echo "-";
                                      }
                                ?>
                                </td>
                                <?php
                                 }
                                 ?>
                                <td><?php echo $total_con ?></td>
                                <td><?php echo $gbal-$total_con ?></td>
                            </tr>


                       
                    </table><br>
<hr/>
                    <h4 style=" text-align:center; color: orange" >Fee payment Summary</h4>
                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">                           <tr>
                    <th>Individual</th>
                    <th>Over All</th>
                    <th>Collected</th>
                    <th>Concessions</th>
                    <th>Balance</th>
                </tr>
                <tr>
                    <td><?php echo $fees ?></td>
                    <td><?php echo ($section->students * $fees);  ?></td>
                    <td><?php echo $overall;  ?></td>
                    <td><span id='gross_concession'><?php echo $total_con ?></span></td>
                    <td><span id='gross_bal'><?php echo $gbal-$total_con ?></span></td>
                </tr>
            </table>
                        </div>
                </div>
                      
                 </div>
                
            </div>                                
                        <?php
                         }
                    ?>
                                        </div>
                                </div>
                                     <script>
                                window.print();
                                </script>
                                </body>
                            </html>
                     <?php
                    
                }
            }
        }
        
        public function download_report($class=0,$section=0,$type=""){
            $this->load->helper('download');
          if($class==0 || $section==0){
              //   $this->load->view('accounts/view');
            }else{
                $section=  $this->check_section($class, $section);
                if(!$section){
                 //   $this->load->view('accounts/view'); 
                }else
                    {
                          $institute=  $this->fetch_institute_details();
                                
                            if($type=="balance"){
                              $accounts= $this->fetch_account_balance($section->sid);
                               $txt ="Roll,Student Name,Userid,Paid,Balance\n";
                              
                                  foreach($accounts as $value){
                                      $txt.=  $value->roll .",". $value->name .",". $value->userid .",";
                                    
                                            if( strlen($value->total)==0){
                                              $txt.='0'.",";
                                            }else{
                                                $txt.=$value->total.",";
                                               
                                            }
                                       $txt.=$section->fee-$value->total."\n" ;
                                       
                                  }
                               $file = 'results.csv';
                                force_download($file, $txt);
                            }
                            else {

                                $txt ="Roll,Student Name,Userid,Payment Details \n";
                                $q= $this->db->query("SELECT f.fid,f.category as cat,fc.category , f.fee FROM `fee_class` f JOIN fee_category fc on f.category = fc.cid where  f.cls_id= '".$section->class_id."' ");
                                         $q =$q->result();
                                         foreach($q as $val){
                                             $txt.=$val->category.",";
                                            ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $val->category; ?></td>
                                        <td><?php echo $val->fee; ?></td>
                                    </tr>
                                           <?php
                                           $cat.=$val->cat.",";
                                           $fees =$fees+$val->fee;
                                         }
                                         $cat= substr($cat,0, strlen($cat)-1);
                                         $cat = array_filter(explode(",",$cat));
                                
                                $overall = 0;
                                
                                $stud= $this->db->query("SELECT * FROM `student` WHERE `class_id` ='".$section->class_id."' AND `section_id` ='".$section->sid."' AND iid = '".$this->session->userdata("staff_Org_id")."' ");
                                $stud = $stud->result();
                                $fee=$this->db->query("SELECT fa.* FROM `fee_accounts` fa JOIN student s ON fa.student_id = s.student_id JOIN fee_category fc ON fa.category = fc.cid WHERE s.section_id='".$section->sid."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
                                $fee = $fee->result();

                                $accounts=array();
                                foreach ($fee as $value) {
                                    if(isset($accounts[$value->student_id][$value->category]) ){
                                         $accounts[$value->student_id][$value->category]=$accounts[$value->student_id][$value->category]+$value->amount;
                                    }else{
                                         $accounts[$value->student_id][$value->category]=$value->amount;
                                    }
                                   $overall+=$value->amount;
                                }
                               $total = array();
                               $gbal=  ($section->students * $fees)-$overall;
                                
                                $file = 'results.csv';
                                force_download($file, $txt);
                        }
                
                
                    }
            }
            
        }
        
        public function pdf_report($class=0,$section=0,$type=""){
           
             $this->load->library('Pdf');
            $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
            }
           
            $pdf->AddPage();
            
          if($class==0 || $section==0){
              //   $this->load->view('accounts/view');
            }else{
                $section=  $this->check_section($class, $section);
                if(!$section){
                 //   $this->load->view('accounts/view'); 
                }else
                    {
                          $institute=  $this->fetch_institute_details();
                     $html = '<div class="col-sm-12">
                                        <div class="pull-left"><br/>
                                            <img src="<?php echo assets_path ?>/uploads/<?php echo $institute->logo ?>" style=" width: 100px">            
                                        </div>        
                                        <div class="pull-right">
                                            <h3><strong style=" color:#0c4472 ; float: right" ><?php echo $institute->name ?></strong></h3>
                                            <strong style=" color:#0c4472 ; float: right ;  text-align: right" ><br/> 
                                             <?php 
                                               echo str_replace("\n", "<br/>", $institute->address) ;
                                             ?>
                                            </strong>
                                        </div>
                                     </div><br/>'.'<h3 style="text-align: center ; color:#0c4472"><strong>Accounts Report of '. $section->class." , ".$section->section .'</strong></h3><hr/> ';
                           if($type=="balance"){
                              $accounts= $this->fetch_account_balance($section->sid);
                              
                              $balance='<table  border="1" style="text-align:center;  color:#0c4472 ;">
                                       <tr>
                                        <th><strong>Roll</strong></th>
                                        <th><strong>Student</strong></th>
                                        <th><strong>Userid</strong></th>
                                        <th><strong>Paid</strong></th>
                                        <th><strong>Balance</strong></th>
                                    </tr>';
                              
                                  foreach($accounts as $value){
                                      if( strlen($value->total)==0){
                                                 $paid=0;
                                            }else{
                                                $paid=$value->total;
                                               
                                            }
                                      $balance.='<tr>'
                                              . '<td>'.$value->roll.'</td>'
                                              . '<td>'.$value->name.'</td>'
                                              . '<td>'.$value->userid.'</td>'
                                              . '<td>'.$paid.'</td>'
                                              . '<td>'.($section->fee-$value->total).'</td>'
                                              . '</tr>';
                                      
                                  }$balance.="</table>";
                                  
                            }
                            else {

                                $accounts=  $this->fetch_account_records($section->sid);
                                $balance='<table  border="1" style="text-align:center;  color:#0c4472 ;">
                                       <tr>
                                        <th><strong>Roll</strong></th>
                                        <th><strong>Student Details</strong></th>
                                        <th colspan="2"><strong>Balance</strong></th>
                                    </tr>';
                                
                                $i=1;
                                $previd="";
                                $total=0;$gtotal=0;$gbalance=0;
                                 foreach($accounts as $value){


                                     if($previd!=$value->student_id){
                                         $gtotal+=$total;
                                         $prev_total=$total;
                                         $total=0;
                                            if($previd!=""){
                                                $balance.='<tr>'
                                                     . '<td>Total Paid</td>'
                                                     . '<td>'.$prev_total.'</td>'
                                                     . '</tr>';
                                                $balance.='<tr>'
                                                     . '<td>Balance</td>'
                                                     . '<td>'.($section->fee-$prev_total).'</td>'
                                                     . '</tr>'. '</table></td></tr>';
                                            }
                                            $balance.='<tr>'
                                              . '<td>'.$value->roll.'</td>'
                                              . '<td>'.$value->name.'<br/>'.$value->userid.'</td>'
                                              . '<td colspan="2"><table >';
                                            
                                            if($value->account_id!=0){
                                             $balance.='<tr>'
                                                     . '<td>'.$value->recipt.'</td>'
                                                     . '<td>'.$value->amount.'</td>'
                                                     . '</tr>';
                                             }
                                                                       
                                     }else{
                                         $balance.='<tr>'
                                                     . '<td>'.$value->recipt.'</td>'
                                                     . '<td>'.$value->amount.'</td>'
                                                     . '</tr>';
                                     }
                                     $total=$total+$value->amount;
                                     
                                     $previd=$value->student_id;
                                 }
                                 if($previd!=""){

                                     $balance.='<tr>'
                                                     . '<td>Total Paid</td>'
                                                     . '<td>'.$prev_total.'</td>'
                                                     . '</tr>';
                                                $balance.='<tr>'
                                                     . '<td>Balance</td>'
                                                     . '<td>'.($section->fee-$prev_total).'</td>'
                                                     . '</tr>'. '</table></td></tr>';
                                         }
                                     $balance.="</table>";
                                
                        }
                         $html.=$balance;

$pdf->writeHTML($html, true, false, true, false, '');
                                   
                                     
                                    $pdf->Output("Accounts.pdf", 'D');
                
                    }
            }
        }
        
        public function add_record(){
          $this->load->view('accounts/add_record');
        }
        
        public function add(){

            $catids =$this->input->post("catids");      
            $catids =  array_filter(explode(",",$catids));
            $counter =0;$ids="";
            foreach ($catids as $value) {
               if(strlen($this->input->post("pay_cat_".$value))!=0){
                  $counter++;
                  if(!is_numeric($this->input->post("pay_cat_".$value))){
                          $this->form->setError("pay_cat_".$value,'* Enter Numeric Value ');
                  }else{
                      if($this->input->post("pay_cat_".$value)>$this->input->post("cat_bal_".$value)){
                                  $this->form->setError("pay_cat_".$value,'* Entered Amount Greater Than Balance ');
                      }else{
                         $ids.=$value.","; 
                      }
                  }
               }   
            }
            $status=1;
            if(strlen($this->input->post("payment_type"))==0){
                           $this->form->setError("pay_cat_".$value,'* Select Payment Type ');
            }else{
                if($this->input->post("payment_type")!=1){
                    $status=2;
                    if(strlen($this->input->post("ref_no"))==0){
                           $this->form->setError("pay_cat_".$value,'* Enter Reference No ');
                    }
                }
            }
            
            if($counter==0){
                        $this->form->setError("error",'* Enter Any Fee Category ');
            }
            
             if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect('accounts/add_record?student='.$this->input->post("userid"), 'refresh'); exit;
                }else{
                    $p_type=$this->input->post("payment_type");
                    $catids =  array_filter(explode(",",$ids));
                    $n=  $this->get_admission_no()+1;
                    $no=  $this->make_9digit($n);$t=time();
                    $inv=  $this->fetch_institute_code()."inv".$no;
                    $ref_no=0;
                     if($p_type!=1){
                         $data = array(
                                    'iid' =>$this->session->userdata('staff_Org_id'),
                                    'ref_no' =>$this->input->post("ref_no")
                                  );
                         $this->db->insert('fee_pay_reference',$data);
                         $ref_no=$this->db->insert_id();
                     }
                     $total_amount=0;
                    foreach ($catids as $value) {
                        $data=array(
                            'iid' =>$this->session->userdata('staff_Org_id'),
                            'student_id'=>  $this->input->post("student_id"),
                            'recipt'=>$inv,
                            'category'=>$value,
                            'amount'=>$this->input->post("pay_cat_".$value),
                            'time'=>$t,
                            'staff_id' =>$this->session->userdata('staff_id'),
                            'pay_type' =>$p_type,
                            'status' =>$status,
                            'ref_id' =>$ref_no
                        ); 
                        $total_amount+=$this->input->post("pay_cat_".$value);
                        $this->db->insert('fee_accounts',$data);
                        $id=$this->db->insert_id();
                        $this->logs->insert_staff_log(8,'Added Fee record ',$id);
                        }
                        $message ="Dear parent ,
                        your Ward  <#name#> , 
                        Paid an amount of : <#paid#> With Invoice No : <#invoice#>
                        On : <#date_time#> and 
                        balance Left is : <#balance#> ";
                        $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='2' ";
                        $msg_ids = $this->db->query($msg_ids);
                        if($msg_ids->num_rows()!=0){
                            $msg_ids =$msg_ids->row();
                            $message= $msg_ids->msg_content;
                        } 
                        $student =$this->db->query("SELECT s.student_id,s.phone,s.email,s.photo,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.student_id = '".$this->input->post("student_id")."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
                        $student =$student->row();
                        $fee="SELECT sum(fee) as total  FROM `fee_class` where cls_id=".$student->cls_id." ";
                        $fee=$this->db->query($fee)->row();
                        $fee=$fee->total;
                        $concession="SELECT sum(amount) as total  FROM `fee_concession` where std_id=".$student->student_id." ";
                        $concession=$this->db->query($concession)->row();
                        $concession=$concession->total;
                        //fee_accounts
                        $paid="SELECT sum(amount) as total  FROM `fee_accounts` where student_id=".$student->student_id." ";
                        $paid=$this->db->query($paid)->row();
                        $paid=$paid->total;
                        //print_r($student);
                        $message = str_replace("<#name#>", $student->name, $message);
                        $message = str_replace("<#paid#>", $total_amount, $message);
                        $message = str_replace("<#invoice#>", $inv, $message);
                        $message = str_replace("<#date_time#>", date("d-m-Y H:i", time()), $message);
                        $message = str_replace("<#balance#>", $inv, $message);
                        $message = str_replace("<#roll_no#>", $student->roll, $message);
                        $message = str_replace("<#paid#>", $paid, $message);
                        $message = str_replace("<#total#>", $fee, $message);
                        $message = str_replace("<#balance#>", ($fee-$concession)-$paid, $message);
                        //<#roll_no#>
                        $message = str_replace("<#class_section#>", $student->clsname." - ".$student->section, $message);
                        
                        $data1=array(
                                'iid' =>$this->session->userdata('staff_Org_id'),
                                'staff_id' =>$this->session->userdata('staff_id'),
                                'regarding'=>5,
                                'message' =>"Fee payement Alerts ",
                                'time' =>time()
                               );
                    $this->db->insert('alerts',$data1);
                    $aid=$this->db->insert_id();
                    $sms_details=  $this->fetch_sms_details();
                    $this->logs->insert_staff_log(4,'Sending Alert',$aid);
                    $data=array(
                        'iid' =>$this->session->userdata('staff_Org_id'),
                        'username'=>$sms_details->username,
                        'password' =>$sms_details->password,
                        'senderid' =>$sms_details->senderid,
                        'message'  =>$message,
                        'mobile' =>$student->phone,
                        'time' => time(),
                        'status' =>0,
                        'alert_id' =>$aid,
                    );
          $this->db->insert('msg_senthistory',$data); 
                     $this->update_admission_no($n);
                 $this->session->set_userdata('add_record_sucess', $inv.",".$id);
              redirect('accounts/add_record', 'refresh'); exit;
                
               }
        }
        
        private function fetch_sms_details(){
        $msg=$this->db->query("SELECT * FROM `messaging` where iid='".$this->session->userdata('staff_Org_id')."' ")->row();
        return $msg;
    }
        
        public function send_sms_Alert(){
            $data1=array(
                    'iid' =>$this->session->userdata('staff_Org_id'),
                    'staff_id' =>$this->session->userdata('staff_id'),
                    'regarding'=>$reg,
                    'message' =>$al_msg,
                    'time' =>time()
                   );
        $this->db->insert('alerts',$data1);
        $aid=$this->db->insert_id();
        }
        
        public function bulk(){
            
            $file= $_FILES['bfile'];
            $filename=$file['name'];
		if(strlen(trim($filename)) == 0)
		{
			$this->form->setError('bfile','* file is not selected');
		}
		else
		{
                       $end=explode('.',$filename);
			$end = strtolower(end($end));
                        
                        $type = array("csv", "txt");
                        
			if(!(in_array($end, $type)))
			{
				$this->form->setError('bfile','* file is supporrt only csv/txt format');
			}
		}
		
		if($this->form->num_errors >0 )
                {
                    $_SESSION['value_array'] = $_POST;
                    $_SESSION['error_array'] = $this->form->getErrorArray();
                   
                }
		else
		{
                    $file_pointer = fopen($file['tmp_name'], "r");
                    $file_read = fread($file_pointer, $file['size']);

                    $newdata=$file_read;
                    $ex=explode("\n",$newdata);
                    if(strlen(trim($ex[sizeof($ex)-1]))==0){
                       array_pop($ex);
                    }

                    if(trim($ex[0]) == "admission,fee"){
                       array_shift($ex); 
                    }
                     $n=  $this->get_admission_no()+1;
                    $failed=array();
                    $count=0;
                    foreach ($ex as $value) {
                        $value=  explode(',',$value);
                        $bal= $this->fetch_balance1($value[0]);
                        if($value[1]<$bal){
                            $no=  $this->make_9digit($n);
                            $inv=  $this->fetch_institute_code()."inv".$no;
                          $count++;
                          $data=array(
                                'iid' =>$this->session->userdata('staff_Org_id'),
                                'student_id'=> $this->fetch_student_id($value[0]),
                                'recipt'=>$inv,
                                'amount'=>$value[1],
                                'time'=>time()
                            );
                          $this->db->insert('fee_accounts',$data);  
                          $this->logs->insert_staff_log(8,'Added Fee record ',$this->db->insert_id());
                        }  else{
                           $failed[]=$value; 
                        }
                       $n++; 
                    }
                   $this->update_admission_no($n-1);
                   $this->session->set_userdata('add_record_sucess', $count." Record added Sucessfully");
                   if(sizeof($failed)>0){
                    $this->session->set_userdata('failed_records', $failed);
                   }
                }
               
         redirect('accounts/add_record', 'refresh'); 
        }
        
        public function search_record(){

            $this->load->view('accounts/search');

        }
        
        public function fetch_records(){
            $student =  $this->input->post('student');
            $recipt =  $this->input->post('recipt');
            $fromdate =  $this->input->post('fromdate');
            $todate =  $this->input->post('todate');
            if( (strlen($student)==0) &&(strlen($recipt)==0)&&(strlen($fromdate)==0)&&(strlen($todate)==0) ){
                ?>
                        <script>
                           $('#herrors').html("<br/>Please select Any of these above");
                        </script>
                        <?php
                  exit;
            }else{
                if(strlen($fromdate)!=0){
                   $fromdate =explode('/',$fromdate);
                   $fromdate= mktime(0,0,0,$fromdate[1],$fromdate[0],$fromdate[2]);
                }else{
                    $i="SELECT timestamp FROM `institutes` where id='".$this->session->userdata("staff_Org_id")."' ";
                  $i=  $this->db->query($i)->row();
                  $i=$i->timestamp;
                    $fromdate=  $i;
                }
                if(strlen($todate)!=0){
                    $todate =explode('/',$todate);
                    $todate= mktime(0,0,0,$todate[1],$todate[0],$todate[2]);
                    if($fromdate>$todate){
                        ?>
                        <script>
                           $('#errors').html("<br/>Please Select Valid Date Range");
                        </script>
                        <?php
                        exit;
                    }
                }else{
                    $todate=time();
                }
             
                $query ="SELECT s.student_id,s.name,s.userid,fa.account_id,fa.recipt,sum(fa.amount) as amount ,fa.time FROM fee_accounts fa JOIN student s ON fa.student_id=s.student_id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ";
                if(strlen($student)!=0){
                   $query.=" AND s.student_id='".$student."' "; 
                }
                if(strlen($recipt)!=0){
                     $query.=" AND fa.recipt LIKE '%".$recipt."%'  "; 
                }
                if(strlen($fromdate)!=0){
                     $query.=" AND fa.time >='".$fromdate."' "; 
                }
                if(strlen($todate)!=0){
                     $query.=" AND fa.time <='".$todate."' "; 
                }
              $query.= " GROUP BY fa.recipt ";
                $query=  $this->db->query($query);
                $query=$query->result();
                ?>
                <script>
                           $('#results').show();
                </script>
                <div class="box">
                        <div class="box-title">
                            <h3>Results</h3>
                            <div class="actions">
                                <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/Accounts/Print_brief_report/?from=<?php echo date("d-m-y", $fromdate) ?>&to=<?php echo date("d-m-y", $todate) ?>&student=<?php echo $student ?>&receipt=<?php echo $recipt ?>" target="_blank"><i class="fa fa-print"></i>&nbsp;Print</a> 
                            </div>
                        </div>
                        <div  class="box-content nopadding" style=" max-height: 300px ; overflow-y: scroll">
                <table class="table table-hover table-nomargin dataTable table-bordered">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Student</th>
                        <th>Receipt No</th>
                        <th>Amount</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody  >
                    <?php
                        $i=1;
                foreach($query as $value){
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><a target="_blank" href="<?php echo base_url() ?>index.php/accounts/student_fee_details/<?php echo $value->student_id ?>"><?php echo $value->name ?></a></td>
                    <td><a target="_blank" href="<?php echo base_url()  ?>index.php/accounts/printout/<?php echo $value->recipt ?>"><?php echo $value->recipt ?></a></td>
                    <td><?php echo $value->amount ?></td>
                    <td><?php echo date('d-m-Y',$value->time) ?></td>
                </tr>
                   <?php
                }
                ?>
                </tbody>
                </table>
                        </div>
                </div>
                <?php
                
            }
            
        }
        
        private function check_section($cls,$section){
           $query=$this->db->query("SELECT s.sid,c.id as class_id,s.name as section,c.name as class,(SELECT SUM(`fee`) FROM `fee_class` where cls_id= c.id) AS fee, (SELECT count(*) FROM `student` where section_id= s.sid) AS students  FROM `section` s JOIN  class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND s.sid='".$section."' AND s.class_id='".$cls."'  ORDER BY c.numeric_val DESC ,s.sid ASC");
           if($query->num_rows()>0){
               $query=$query->row(); 
               return $query;
           }else{
               return FALSE;
           }
          
                     
        }
        
        private function fetch_account_records($section){
       //    echo "SELECT fa.account_id,fa.amount,fa.recipt,fa.time,s.student_id,s.userid,s.name,s.roll FROM  student s  LEFT JOIN fee_accounts fa ON  fa.student_id=s.student_id  WHERE s.section_id='".$section."' ORDER BY fa.time DESC,s.student_id ASC ";
            $query=  $this->db->query("SELECT fa.account_id,fa.amount,fa.recipt,fa.time,s.student_id,s.userid,s.name,s.roll FROM  student s  LEFT JOIN fee_accounts fa ON  fa.student_id=s.student_id  WHERE s.section_id='".$section."' ORDER BY fa.time DESC,s.student_id ASC ");
            $query=$query->result();
            return $query;
        }
        
        private function fetch_account_balance($section){
            $query=  $this->db->query("SELECT s.student_id,s.name,s.roll,s.userid,(select sum(fa.amount) from fee_accounts fa where fa.student_id=s.student_id  ) as total FROM `student` s   where s.section_id='".$section."' ORDER BY  s.student_id ASC ");
            $query=$query->result();
            return $query; 
            
        }
         
        private function get_admission_no(){
            $query = $this->db->query("SELECT `last_id` FROM `invoice` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'start'=>1,
                    'last_id'=>0
                    );
                $this->db->insert('invoice',$data);
                return 0;
            }else{
                $result=$query->row();
                return $result->last_id;     
            }          
      }
      
        private function update_admission_no($no){
          $this->db->query("UPDATE `invoice` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");  
        }
        
        private function fetch_balance($student){
            $query=  $this->db->query("SELECT sum(fee) as fee FROM `fee_class` f JOIN student s ON f.cls_id=s.class_id WHERE s.student_id='".$student."'");
            $query=$query->row();
            $max=$query->fee;
            $min=  $this->db->query("SELECT sum(amount) as total FROM fee_accounts WHERE student_id ='".$student."'");
            $min=$min->row();
            $min=$min->total;
            return ($max-$min);
        }
        
        private function fetch_student_id($admission){
           $query=  $this->db->query("SELECT * FROM student  WHERE userid='".$admission."'");
            $query=$query->row();
            return $query->student_id;
             
        }
        
        private function fetch_balance1($userid){
            $query=  $this->db->query("SELECT sum(fee) as fee FROM `fee_class` f JOIN student s ON f.cls_id=s.class_id WHERE s.userid='".$userid."'");
            $query=$query->row();
            $max=$query->fee;
            $min=  $this->db->query("SELECT sum(f.amount) as total FROM fee_accounts f JOIN student s ON f.student_id=s.student_id WHERE s.userid ='".$userid."'");
            $min=$min->row();
            $min=$min->total;
            return ($max-$min);
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
      
        private function fetch_record_info($account_id){
            $query=  $this->db->query("SELECT s.student_id,s.photo,s.roll,f.account_id,s.name,s.userid,s.admission_no,c.id as cls_id,cat.category,f.recipt,f.amount,f.time,c.name as clsname , se.name as section ,st.name as staff_name FROM fee_accounts f JOIN student s ON f.student_id=s.student_id JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id JOIN staff st ON st.id=f.staff_id JOIN fee_category cat ON f.category = cat.cid WHERE f.recipt='".$account_id."' AND f.iid='".$this->session->userdata("staff_Org_id")."'");
            if($query->num_rows()>0){
                $account_query=array();
               $query= $query->result();
                foreach($query as $val){
                    $account_query['student']=array('student_id'=>$val->student_id,'cls_id'=>$val->cls_id,'name'=>$val->name,'userid'=>$val->userid,'roll'=>$val->roll,'photo'=>$val->photo,'cls_sec'=>$val->clsname." - ".$val->section,'paid_time'=>$val->time,'admission_no'=>$val->admission_no);
                    $account_query[$val->recipt][]=array('category'=>$val->category,'amount'=>$val->amount);
                }
               
               return  $account_query;
            }else{
                return FALSE;
            }
        }
      
        private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
        
        public function fetch_cat_balance(){
            $stud = array_filter(explode(",",  $this->input->post("stud")));
            $category =trim($this->input->post("category"));
            $clsid= $stud[1];
            $stud = $stud[0];
            $q=$this->db->query("SELECT * FROM `fee_class`  where cls_id='".$clsid."' and iid='".$this->session->userdata("staff_Org_id")."' AND category = '".$category."' ");
            $amount =0;
            if($q->num_rows()>0){
               $q = $q->row(); 
               $amount = $q->fee;
            }
            $amount;
            $q=$this->db->query("SELECT sum(amount) as total FROM `fee_accounts` WHERE `student_id` = '".$stud."' AND `category` =  '".$category."' AND  iid='".$this->session->userdata("staff_Org_id")."'  ");
            $paid =0;
            if($q->num_rows()>0){
               $q = $q->row(); 
               $paid = $q->total;
            }
            $q=$this->db->query("SELECT sum(amount) as total FROM `fee_concession` WHERE `std_id` = '".$stud."' AND `cat_id` =  '".$category."' AND  iid='".$this->session->userdata("staff_Org_id")."'  ");
            if($q->num_rows()>0){
               $q = $q->row(); 
               $paid = $paid+ $q->total;
            }
            if($paid>=$amount){
                echo  "0";
            }else{
                echo $amount-$paid;
            }
            
        }
        
        public function Dashboard(){
            //echo current_url();
            $this->load->view('accounts/dashboard'); 
        }
        
        public function load_sections(){
            $cls=  $this->input->post("cls");
            $query = " select * from section where iid='".$this->session->userdata('staff_Org_id')."' AND class_id = '".$cls."' ";
            $query = $this->db->query($query);
              $query=$query->result();
              ?>
                <option value="">Select A Section</option>
                <?php
              foreach($query as $val){
                  ?>
            <option value="<?php echo $val->sid ?>" >
                <?php echo $val->name ?>
            </option>
               <?php
              }
        }
        
        public function load_students(){
            $section=  $this->input->post("section");
            $query = " select * from student where iid='".$this->session->userdata('staff_Org_id')."' AND section_id = '".$section."' ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Student</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->student_id.",".$val->userid ?>" >
                    <?php echo $val->userid."-".$val->name ?>
                </option>
             <?php
            }

        }
        
        
        function student_fee_details($id=""){
            if(strlen($id)==0){
              redirect('accounts/view','refresh');  
            }   else{
                $query=  $this->db->query("SELECT s.student_id,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.student_id = '".$id."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
               if($query->num_rows()>0){
                   $query =$query->row();
                   $data['student']=$query;
                   $this->load->view('accounts/student_details',$data);
                }else{
                    redirect('accounts/view','refresh');  
                }
            }
        }
		
		function print_student_report($id=""){
            if(strlen($id)==0){
              redirect('accounts/view','refresh');  
            }   else{
				$institute=$this->fetch_institute_details();
                $query=  $this->db->query("SELECT s.student_id,s.photo,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.student_id = '".$id."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
               if($query->num_rows()>0){
                   $query =$query->row();
                   $student=$query;
                   $cls_fee = $this->db->query("SELECT f.fid,f.fee,c.cid,c.category ,(select sum(amount)  from  fee_accounts a where  f.category = a.category AND a.student_id= '".$student->student_id."' ) as paid ,(select sum(amount)  from  fee_concession fc where  f.category = fc.cat_id AND fc.std_id='".$student->student_id."' ) as concession FROM `fee_class` f JOIN fee_category c ON f.category=c.cid WHERE f.cls_id='".$student->cls_id."' ");
$cls_fee = $cls_fee->result();
$c_fee_arr= array();
$cat =""; $pays=0;$concs=0;$bals=0;$total=0;
$pay ="";$coner="";$bal="";$totals="";
foreach ($cls_fee as $value) {
    $cat.="'".$value->category."',";
    $paid=0;$concession=0;
    if($value->paid!=NULL){
        $paid=$value->paid;
    }
    if($value->concession!=NULL){
        $concession=$value->concession;
    }
    $balance = $value->fee-($paid + $concession);
   $c_fee_arr[$value->fid] = array('cat_id'=>$value->cid,'category'=>$value->category,'fee'=>$value->fee,'paid'=>$paid,'concession'=>$concession,'balance'=>$balance);
   $pay.="".$paid.",";
   $coner.="".$concession.",";
   $bal.="".$balance.",";
    $totals.=  "".$value->fee.",";
    $pays+=$paid;
    $concs+=$concession;
    $bals+=$balance;$total+=$value->fee;
  }
$cat = substr($cat, 0, strlen($cat)-1);
$pay = substr($pay, 0, strlen($pay)-1);
$coner = substr($coner, 0, strlen($coner)-1);
$bal = substr($bal, 0, strlen($bal)-1);
$totals = substr($totals, 0, strlen($totals)-1);

       echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fee payment Brief Report</title>
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
<hr color="#00306C" />     
<h3 style=" text-align: center; color:orange ">Fee  Report Of '.$student->name.'</h3>'; 
$std_image="dummy_user.png";
                      if(!(strlen($student->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$student->photo)){
                           $std_image =$student->photo ;
                         }            
                      } 
echo  '<div style="border:2px solid #00306C;height:120px;">
                                    <div style="float:left;width:50%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="70" style="padding:5px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:101px;">&nbsp;</div>
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
                                        <th>'.$student->clsname." - ".$student->section.'</th>
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
									<script src="https://code.highcharts.com/highcharts.js"></script>
									<script src="https://code.highcharts.com/modules/exporting.js"></script>
									<div id="container" style="min-width: 310px; height: 400px;  margin-top: 20px;">
										
									</div>
                                    ';?>
<script type="text/javascript" src="<?php echo assets_path ?>highcharts/js/jquery.min.js"></script>		
<script type="text/javascript">
$(function () {
Highcharts.chart('container', {
title: {
text: 'Fee Payment (Total : <?php echo $total ?>)'
},
xAxis: {
categories: [<?php echo $cat ?>]
},
labels: {
items: [{
html: 'Total Fee Summary',
style: {
    left: '50px',
    top: '18px',
    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
}
}]
},
series: [{
type: 'column',
name: 'Total',
data: [<?php echo $totals ?>]
},{
type: 'column',
name: 'Paid',
data: [<?php echo $pay ?>]
}, {
type: 'column',
name: 'Concession',
data: [<?php echo $coner ?>]
},  {
type: 'column',
name: 'Balance',
data: [<?php echo $bal ?>]
},  {
type: 'pie',
name: 'Value : ',
data: [{
name: 'paid (<?php echo $pays ?>)',
y: <?php echo ($pays) ?>,
color: Highcharts.getOptions().colors[1] // Jane's color
}, {
name: 'Concession (<?php echo $concs ;?>)',
y: <?php echo $concs ?>,
color: Highcharts.getOptions().colors[2] // John's color
},{
name: 'Balance <?php echo $bals ; ?>',
y:  <?php echo $bals ; ?> ,
color: Highcharts.getOptions().colors[3] // Joe's color
}],
center: [100, 80],
size: 100,
showInLegend: false,
dataLabels: {
enabled: false
}
}]
});
});


</script>
                  <h4 style=" text-align:center; color: #0F4184 ">Fee  summary </h4>
                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                         <tr>
                            <th>Category</th>
                            <th>Fee</th>
                            <th>Paid</th>
                            <th>Concession</th>
                             <th>Balance</th>
                        </tr>
                    
                        <?php
                        foreach ($c_fee_arr as $value) {
                            ?>
                        <tr>
                            <td><?php  echo $value['category']?></td>
                            <td><?php  echo $value['fee']?></td>
                            <td><?php  echo $value['paid']?></td>
                            <td><?php  echo $value['concession']?></td>
                            <td><?php  echo $value['balance']?></td>
                        </tr>
                           <?php
                        }
                        ?>
                </table>
				<br/><hr/>
                <h4 style=" text-align:center; color: #0F4184 ">Fee Payment History </h4>
				<table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                           <tr>
                            <th>S.no</th>
                            <th>Invoice No</th>
                            <th>Amount</th>
                            <th>Paid On</th>
                        </tr>
                        <?php
                           $q="SELECT f.account_id,f.recipt,sum(f.amount)as amount,f.time,c.category FROM fee_accounts f JOIN fee_category c ON f.category =c.cid  where f.student_id='".$student->student_id."'  AND f.iid='".$this->session->userdata('staff_Org_id')."' GROUP BY f.recipt ";
                           $q =$this->db->query($q);
                          if($q->num_rows()>0){


                          $q = $q->result();
                          $i=1;
                          foreach($q as $value){
                               ?> 
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $value->recipt ?></td>
                            <td><?php echo $value->amount ?></td>
                            <td><?php echo date('d-m-y H:i',$value->time) ?></td>

                        </tr>
                               <?php
                           }
                          }else{
                              ?>
                        <tr>
                            <td colspan="4" style=" text-align: center; color: red">** No Fee Details found ..</td>
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

				  }else{
                    redirect('accounts/view','refresh');  
                }
            }
        }
		
        public function approvals(){
            $this->load->view("accounts/approvals");  
            
        }
        
        public function change_pay_status($rid,$status){
            $data = array(
                'status' => $status,
              );
              $this->db->where('ref_id',$rid);
             $this->db->update('fee_accounts',$data);
             ?>
                <script>
                window.location.href="<?php echo base_url() ?>index.php/accounts/approvals";
                </script>
             <?php
        }
        
        public function payment_approved(){
           $this->load->view("accounts/payment_approved");  
             
        }
        public function payment_rejected(){
            $this->load->view("accounts/payment_rejected");  
            
        }
        
    public function transport_report($class=0,$section=0){
        if($class==0 || $section==0){
             $this->load->view('accounts/view');
        }else{
            $sec=  $this->check_section($class, $section);
            if(!$sec){
                $this->load->view('accounts/view'); 
            }else{
                    $institute=  $this->fetch_institute_details();
                echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transport Department Fee payments</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
<link rel="stylesheet" href="'.assets_path.'/js/jquery.min.js">                    
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
<hr color="#00306C" />     
<h3 style=" text-align: center;color: orange "><strong>Hostel Department Fee Payment Report of '.$sec->class." , ".$sec->section .'</strong></h3>'; 
              $trans ="SELECT st.* ,s.userid,s.name,s.admission_no,(SELECT sum(fee) FROM `transport_fee` WHERE student =st.stud_id ) as paid , t.fee FROM `stud_transport` st JOIN trip_route tr ON tr.trid= st.trip_route_id JOIN trips t ON tr.trip = t.trip_id JOIN student s ON st.stud_id=s.student_id where s.section_id ='".$sec->sid."'   ";
              $trans = $this->db->query($trans)->result();
                ?>
<hr color="#ccc" /> 
                <div style=" clear: both" class="col-sm-12">
                    <table align="center"  class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                        <tr>
                            <th>S.No</th>
                            <th>Student</th>
                            <th>Admission No</th>
                            <th>Total</th>
                            <th>Concession</th>
                            <th>Paid</th>
                            <th>Balance</th>
                        </tr>
                        <?php
                        $i=1;
                          foreach($trans as $value){
                                $paid=$concession=$total=0;
                                $total = $value->fee;
                                $concession = $value->fee -$value->fee_amount;
                                if(strlen(trim($value->paid))!=0){
                                    $paid = $value->paid;
                                }
                              ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $value->name ?></td>
                            <td><?php echo $value->admission_no ?></td>
                            <td><?php echo $total ?></td>
                            <td><?php echo $concession ?></td>
                            <td><?php echo $paid ?></td>
                            <td><?php echo $total-$paid-$concession ?></td>
                        </tr>   
                               <?php
                          }
                        ?>
                    </table>
                </div>
                </div>
                </body>
                </html>
               <?php
                 
            }
        }
    }
    
    public function hostel_report($class=0,$section=0){
        if($class==0 || $section==0){
             $this->load->view('accounts/view');
        }else{
            $sec=  $this->check_section($class, $section);
            if(!$sec){
                $this->load->view('accounts/view'); 
            }else{
                    $institute=  $this->fetch_institute_details();
                echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hostel Department Fee Payments Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
<link rel="stylesheet" href="'.assets_path.'/js/jquery.min.js">                    
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
<hr color="#00306C" />     
<h3 style=" text-align: center;color: orange "><strong>Transport Department Fee Payment Report of '.$sec->class." , ".$sec->section .'</strong></h3>'; 
              $hostel ="SELECT st.* ,s.name,s.userid,s.admission_no,(SELECT sum(fee) FROM `hostel_feepayment` WHERE student =st.student_id ) as paid , (SELECT fee from hostel_fee where class_id = '".$sec->class_id."' AND block_id = r.block_id ) as total FROM `hostel_students` st JOIN hostel_rooms r ON st.room_id=r.room_id  JOIN student s ON st.student_id=s.student_id where s.section_id ='".$sec->sid."'   ";
              $hostel = $this->db->query($hostel)->result();
   ?><hr color="#ccc" />  
                <div style=" clear: both" class="col-sm-12">
                    <table align="center"  class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                        <tr>
                            <th>S.No</th>
                            <th>Student</th>
                            <th>Admission No</th>
                            <th>Total</th>
                            <th>Concession</th>
                            <th>Paid</th>
                            <th>Balance</th>
                        </tr>
                        <?php
                        $i=1;
                          foreach($hostel as $value){
                                $paid=$concession=$total=0;
                                $total = $value->total;
                                $concession = $value->total -$value->fee;
                                if(strlen(trim($value->paid))!=0){
                                    $paid = $value->paid;
                                }
                              ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $value->name ?></td>
                            <td><?php echo $value->admission_no ?></td>
                            <td><?php echo $total ?></td>
                            <td><?php echo $concession ?></td>
                            <td><?php echo $paid ?></td>
                            <td><?php echo $total-$paid-$concession ?></td>
                        </tr>   
                               <?php
                          }
                        ?>
                    </table>
                </div>
                </div>
                </body>
                </html>
               <?php
                 
            }
        }
    }
    
    public function print_details($detail="",$type=""){
        $t="";
               $d= getdate();
              // print_r($d);
               $institute = $this->fetch_institute_details();
              $from=  mktime(0, 0, 0, $d['mon'], $d['mday'], $d['year']);
              $to=mktime(0, 0, 0, $d['mon'], $d['mday']+1, $d['year']);
             
                if((strlen($this->input->get("from"))!=0) ){
                    $from =  explode("/",$this->input->get("from"));
                    $from=  mktime(0, 0, 0, $from[1], $from[0], $from[2]);
                   
                }
                $t = " ( ".date("d-m-y",$from)." )";
                if(strlen($this->input->get("to"))!=0){
                    $to =  explode("/",$this->input->get("to"));
                    $to=  mktime(0, 0, 0, $to[1], $to[0], $to[2]);
                   
                     $t = " (  ".date("d-m-y",$from) ." -- ".date("d-m-y",$to)." )";
                }
         echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title> Accounts  Report'.$t.'</title>
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
                        <tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="158" height="95" /></td>
                        <td align="center"><!--<img src="#" width="125" height="92" />--></td>
                        <td align="right">
                        <div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                                                '.str_replace("\n", "<br/>", $institute->address).'</p>
                                                </div>
                        </td>
                        </tr>
                        </table>
                        <hr color="#00306C" />';
         if( ( $detail=="income") || ( $detail=="all") ){
             
            if($type==""){
               $income="SELECT c.category, (select sum(amount) from fee_accounts where (time >=".$from." AND  time <".$to."  ) AND category= c.cid  ) as total FROM `fee_category` c WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                $income =$this->db->query($income)->result();
             //   print_r($income);
                $travel_income ="SELECT sum(fee) as total FROM `transport_fee` where  (timestamp >=".$from." AND  timestamp <".$to."  )  AND iid='".$this->session->userdata("staff_Org_id")."' ";      
                $travel_income =$this->db->query($travel_income)->row();
                $travel_income=$travel_income->total;
                if( strlen($travel_income)==0){
                   $travel_income =0;
                }
                $t_income=0;
                $t_income+=$travel_income;
                $lib=$this->db->query("SELECT sum(amount) as total from lib_payments where iid='".$this->session->userdata("staff_Org_id")."' AND (time >=".$from." AND  time <".$to."  ) ")->row();
               $lib =$lib->total;
               if( strlen($lib)==0){
                  $lib =0;
               }
               
               ?>
                   <h4 style="text-align: center; color:  #ff6600">INCOME <?php echo $t ?> </h4>
                   <hr/>
                <table align="center" class="tab_td1" border="0" width="60%" cellpadding="0" cellspacing="0">                    
                        <tr>
                            <th>Category</th>
                            <th>Amount</th>
                        </tr>
                        <?php
                        foreach ($income as $value) {
                                  if( strlen($value->total)==0){
                                      $value->total=0;
                                  }
                                ?>
                        <tr>
                            <th><?php echo $value->category ?></th>
                            <td><?php echo $value->total ?></td>
                        </tr>
                              <?php
                                $t_income+=$value->total;
                              }
                              ?>
                        
                        <?php
                        $travelcheck="SELECT `transport` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
                        $travelcheck = $this->db->query($travelcheck)->row();
                        if($travelcheck->transport!=0){
                            ?>
                        <tr>
                            <th>Transport Dept</th>
                            <td><?php echo $travel_income ?></td>
                        </tr>
                            <?php
                        }
                        
                        ?>
                        
                        <tr>
                            <th>Library </th>
                            <td><?php echo $lib ?></td>
                        </tr>
                        <tr style=" color: #ff6600">
                            <th>Total Income</th>
                             <th><?php echo $t_income ?></th>
                        </tr>    
                    </table>  

                <?php
            }else{
                $total_income=0;
                
                $income="SELECT f.account_id,s.student_id,s.name,s.admission_no,s.userid,f.recipt,sum(f.amount) as amount,f.time FROM fee_accounts f JOIN student s ON f.student_id=s.student_id  where (f.time >=".$from." AND f.time <".$to."  ) AND  f.iid='".$this->session->userdata("staff_Org_id")."' GROUP BY f.recipt  ORDER BY f.time DESC ";
                $income =$this->db->query($income)->result();
             //   print_r($income);
                $travel_income ="SELECT sum(fee) as total FROM `transport_fee` where  (timestamp >=".$from." AND  timestamp <".$to."  )  AND iid='".$this->session->userdata("staff_Org_id")."' ";      
                $travel_income =$this->db->query($travel_income)->row();
                $travel_income=$travel_income->total;
                if( strlen($travel_income)==0){
                   $travel_income =0;
                }
                $t_income=0;
                $t_income+=$travel_income;
                $lib=$this->db->query("SELECT sum(amount) as total from lib_payments where iid='".$this->session->userdata("staff_Org_id")."' AND (time >=".$from." AND  time <".$to."  ) ")->row();
               $lib =$lib->total;
               if( strlen($lib)==0){
                  $lib =0;
               }
              // print_r($income);
               ?>
                   <h3 style="text-align: center; color:  #ff6600">INCOME <?php echo $t ?> </h3>
                   <hr/>
                   <h4 style=" color:  #006633">Fee payments</h4>
                   <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                        <tr>
                            <th>Student</th>
                            <th>Admission No</th>
                            <th>Recipt</th>
                            <th>Amount</th>
                            <th>Time</th>
                         </tr>
                         <?php
                          $total=0;
                            foreach ($income as  $value) {
                                ?>
                            <tr>
                                <td><?php echo $value->name ?></td>
                                <td><?php echo $value->admission_no ?></td>
                                <td><?php echo $value->recipt ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y h:i", $value->time) ?></td>
                            </tr>
                               <?php
                               $total+= $value->amount;
                            }
                         
                         ?>
                            <tr>
                                <th colspan="3" style=" text-align: right ; color:  red;">Total Amount&nbsp;&nbsp;</th>
                                <th colspan="2" style=" text-align: left;color:  red;" >&nbsp;&nbsp;<?php echo $total ?></th>
                            </tr>
                   </table>
               <?php
                $travelcheck="SELECT * FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
                $travelcheck = $this->db->query($travelcheck)->row(); $ttotal=0; $htotal=0;  $ltotal=0;
                      
                if($travelcheck->transport!=0){
                      $tfee=" SELECT tf.*,s.name , s.userid,s.admission_no FROM `transport_fee` tf JOIN student s  On tf.student= s.student_id   WHERE (tf.timestamp >=".$from." AND  tf.timestamp <".$to."  )  AND tf.iid='".$this->session->userdata('staff_Org_id')."'   ";
                      $tfee = $this->db->query($tfee)->result();
                       
                    ?>
                   <hr/>
                <h4 style=" color:  #006633">Transport-Dept Fee payments</h4>
                   <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                        <tr>
                             <th>Student</th>
                            <th>User Id</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Time Stamp</th>
                        </tr>
                        <?php
                        foreach( $tfee as $val){
                            ?>
                       <tr>
                            <td><?php echo $val->name ?></td>
                           <td><?php echo $val->admission_no ?></td> <td><?php echo $val->invoice_no ?></td>
                          
                           <td><?php echo $val->fee ?></td>
                           <td><?php echo date('d-m-y H:i',$val->timestamp);  ?></td>
                       </tr>
                           <?php
                        $ttotal+=$val->fee;
                           
                        }
                        
                        ?>
                       <tr>
                                <th colspan="3" style=" text-align: right ; color:  red;">Total Amount&nbsp;&nbsp;</th>
                                <th colspan="2" style=" text-align: left;color:  red;" >&nbsp;&nbsp;<?php echo $ttotal ?></th>
                            </tr>
                   </table>
                    <?php
                }
                if($travelcheck->hostel!=0){
                ?>
                 <hr/>
                <h4 style=" color:  #006633">Hostel-Dept Fee payments</h4>
                
                <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                                     <tr>
                                         <th>Student</th>
                                        <th>Admission No</th>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Time </th>
                                    </tr>
                                    <?php
                                     $tfee=" SELECT tf.*,s.name , s.userid FROM `hostel_feepayment` tf JOIN student s  On tf.student= s.student_id   WHERE tf.iid='".$this->session->userdata('staff_Org_id')."' AND (tf.timestamp >=".$from." AND  tf.timestamp <".$to."  ) ";
                                    $tfee = $this->db->query($tfee);
                                     $tfee = $tfee->result();$i=1;
                                     foreach( $tfee as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $val->name ?></td>
                                        <td><?php echo $val->userid ?></td>   
                                        <td><?php echo $val->invoice_no ?></td>
                                       
                                        <td><?php echo $val->fee ?></td>
                                        <td><?php echo date('d-m-y H:i',$val->timestamp);  ?></td>
                                    </tr>
                                        <?php
                                        $htotal+=$val->fee;
                                     }
                                     ?>
                                <tr>
                                <th colspan="3" style=" text-align: right ; color:  red;">Total Amount&nbsp;&nbsp;</th>
                                <th colspan="2" style=" text-align: left;color:  red;" >&nbsp;&nbsp;<?php echo $htotal ?></th>
                            </tr>
                            </table>    
                    
                <?php
                }
                
                 $lib=$this->db->query("SELECT p.invoice,p.time,p.amount,s.name,s.userid ,s.admission_no FROM `lib_payments` p JOIN lib_fines f ON p.fee_id = f.fine_id JOIn lib_issues i ON f.issue_id = i.issue_id JOIN student s ON s.student_id =i.student_id WHERE  p.iid='".$this->session->userdata("staff_Org_id")."' AND (p.time >=".$from." AND  p.time <".$to."  ) ");
                  $lib =$lib->result();
                  ?>
                 
                 <hr/>
                <h4 style=" color:  #006633">Library Fee payments</h4>
                
                <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                                     <tr>
                                         <th>Student</th>
                                        <th>Admission No</th>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Time </th>
                                    </tr>
                                    <?php
                                  
                                      foreach( $lib as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $val->name ?></td>
                                        <td><?php echo $val->userid ?></td>   
                                        <td><?php echo $val->invoice ?></td>
                                       
                                        <td><?php echo $val->amount ?></td>
                                        <td><?php echo date('d-m-y H:i',$val->time);  ?></td>
                                    </tr>
                                        <?php
                                        $ltotal+=$val->amount;
                                     }
                                     ?>
                                <tr>
                                <th colspan="3" style=" text-align: right ; color:  red;">Total Amount&nbsp;&nbsp;</th>
                                <th colspan="2" style=" text-align: left;color:  red;" >&nbsp;&nbsp;<?php echo $ltotal ?></th>
                            </tr>
                            </table>    
                  <br/><hr/>       
                  <h4 style=" text-align: center">Total Income Summary</h4><br/>  
                <table align="center"  class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                    <tr>
                        <th>Fee Collection</th>
                        <th>Transport Dept</th>
                        <th>Hostel Dept</th>
                        <th>Library</th>
                        <th>Total Income</th>
                    </tr>
                    <tr style=" color:  red"> 
                        <td><?php echo $total; ?></td>
                        <td><?php echo $ttotal; ?></td>
                        <td><?php echo $htotal; ?></td>
                        <td><?php echo $ltotal; ?></td>
                        <td style=" color:  #990033"><?php echo $total+$ttotal+$htotal+$ltotal; ?></td>
                    </tr>
                </table>
                 <?php
                        
                 
                 
            }
            ?>
                </div>
</body>
</html>
                <?php
            
         }
        if( ( $detail=="expenses") || ( $detail=="all") ){
          
          if(strlen($type)!=0){
              ?><br/><br/>
                <h4 style="text-align: center; color:  #ff6600">Expenditure Report <?php echo $t ?> </h4>
                                   <hr/>
                   <h4 style=" color:  #006633">Staff Expenditure List :</h4>
              <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                              <tr>
                                <th>Staff</th>
                                <th>Reason</th>
                                <th>Time</th>
                                <th>Amount</th>
                                
                            </tr>
                            <?php
                             $i=1;
                             $query=$this->db->query("SELECT e.*,s.name as staff FROM expenditure e JOIN staff s ON e.staff_id=s.id WHERE e.status =2  and e.iid='".$this->session->userdata("staff_Org_id")."'  ORDER BY e.time DESC ");
                             $query=$query->result();
                             $t_expenditure=0;
                             foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $value->staff ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo date("d-m-y H:i",$value->time); ?></td>
                                <td><?php echo $value->amount ?></td>
                                
                            </tr>
                              <?php
                              $t_expenditure+=$value->amount ;
                             }
                            ?>
                            <tr>
                                <th colspan="3" style=" text-align: right; color: red">Total Amount&nbsp;&nbsp;&nbsp;</th>
                                <th style=" text-align: left; color: red">&nbsp;&nbsp;&nbsp;<?php echo $t_expenditure ?></th>
                            </tr>
                    </table>    
              <?php     
              $travel_main="SELECT m.*, v.vech_no FROM trans_maintaince m  JOIN vehicles v ON m.vech_id=v.vech_id  where (m.timestamp >=".$from." AND  m.timestamp <".$to."  ) AND m.iid='".$this->session->userdata("staff_Org_id")."'";
               $travel_main= $this->db->query($travel_main)->result();
              $trans_reason= unserialize(trans_reason);
             $tr_expenditure=0;
              ?><br style=" clear: both"/><hr/>
                   <h4 style=" color:  #006633">Transport Maintenance Expenditure</h4>
              <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                 <tr>
                    <th>Vehicle</th>
                    <th>Reason</th>
                    <th>Time</th>
                    <th>Amount</th>
                </tr>
                <?php
                foreach( $travel_main as $val){
                    ?>
               <tr>
                  
                   <td><?php echo $val->vech_no ?>(<?php echo $trans_reason[$val->type] ?>)</td>
                   <td><?php echo $val->reason ?></td>
                   <td><?php echo  date('d-m-y H:i',$val->timestamp);  ?></td>
                   <td><?php echo $val->amount ?></td>
                   
               </tr>
                   <?php
                   $tr_expenditure+=$val->amount;
                }
                ?>
               <tr>
                   <th colspan="3" style=" text-align: right; color: red">Total Amount&nbsp;&nbsp;&nbsp;</th>
                   <th style=" text-align: left; color: red" >&nbsp;&nbsp;&nbsp;<?php echo $tr_expenditure ?></th>
               </tr>
              </table>
              <?php
             $q="SELECT m.month,m.paid_on, (select sum(amount) from salary_paid where month_id=m.id ) as total  FROM `salary_month` m  where ( m.paid_on >=".$from." AND  m.paid_on <".$to." ) AND m.iid= '".$this->session->userdata("staff_Org_id")."'"; 
             $q=$this->db->query($q)->result();
             $salary=0;
             ?>
             <br style=" clear: both"/><hr/>
                   <h4 style=" color:  #006633">Salary Paid Report</h4>
              <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                  <tr>
                      <th>Month-Year</th>
                      <th>Paid On</th>
                      <th>Amount</th>
                  </tr>
                  <?php
                               foreach ($q as $value) {
                                   $time =  getdate($value->month);
                                   ?>
                  <tr>
                      <td><?php echo $time['month'] ." - ".$time['year']  ?></td>
                      <td><?php echo date("d-m-Y",$value->paid_on) ?></td>
                      <td><?php echo $value->total ?></td>
                  </tr>
                                   <?php
                                   $salary+=$value->total;
                               }
                  ?>
                  <tr>
                   <th colspan="2" style=" text-align: right; color: red">Total Amount&nbsp;&nbsp;&nbsp;</th>
                   <th style=" text-align: left; color: red" >&nbsp;&nbsp;&nbsp;<?php echo $salary ?></th>
               </tr>
              </table>    
                 <br/><hr/>       
                  <h4 style=" text-align: center">Total Expenditure Summary</h4><br/>  
                <table align="center"  class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                    <tr>
                        <th>Expenditure</th>
                        <th>Transport Maintaince</th>
                        <th>Salary</th>
                        <th>Total Expenses</th>
                    </tr>
                    <tr style=" color:  red"> 
                        <td><?php echo $t_expenditure; ?></td>
                        <td><?php echo $tr_expenditure; ?></td>
                        <td><?php echo $salary; ?></td>
                        <td style=" color:  #990033"><?php echo $salary+$tr_expenditure+$t_expenditure; ?></td>
                    </tr>
                </table>
             <?php
             
          }else{
            $expenses="SELECT c.name ,  (select sum(amount) from expenditure where (time >=".$from." AND  time <".$to."  ) AND status = 1 AND category=c.cat_id ) as total FROM `expense_category` c WHERE c.iid='".$this->session->userdata("staff_Org_id")."'";
            $expenses =$this->db->query($expenses)->result();
            $travel_main="SELECT sum(amount) as total FROM `trans_maintaince` where (timestamp >=".$from." AND  timestamp <".$to."  ) AND iid='".$this->session->userdata("staff_Org_id")."'";
            $travel_main =$this->db->query($travel_main)->row();
            $travel_main = $travel_main->total;
           if( strlen($travel_main)==0){
                      $travel_main=0;
           }

            $t_expenses=0;
           $t_expenses+=$travel_main;
            ?><br/><br/>
                  <h4 style="text-align: center; color:  #ff6600">Expenditure Report <?php echo $t ?> </h4>
                                   <hr/>
                
           <table align="center"  class="tab_td1" border="0" width="60%" cellpadding="0" cellspacing="0">                    
                    <tr>
                   <th style=" text-align: center">Category</th>
                   <th style=" text-align: center">Amount</th>
               </tr>
               <?php 
                     foreach ($expenses as $value) {
                         if( strlen($value->total)==0){
                             $value->total=0;
                         }
                       ?>
               <tr>
                   <td><?php echo $value->name ?></td>
                   <td><?php echo $value->total ?></td>
               </tr>
                     <?php
                       $t_expenses+=$value->total;
                     }
                ?>
               
               <tr>
                   <td>Transport Maintaince</td>
                   <td><?php echo $travel_main ?></td>

               </tr>
                 
               <tr style=" color: #009933">
                   <td>Total Expenditure</td>
                    <td><?php echo $t_expenses ?></td>
               </tr>    
                     <?php
               ?>
           </table>
         <?php
          }
            
     }
      ?>
</div>
</body>
</html>                
             <?php
    }
    
    public function Print_brief_report(){
        $from =$_REQUEST['from'];
        $from= explode("-", $from);
        $from =  mktime(0, 0, 0, $from[1], $from[0], $from[2]);
        $to =$_REQUEST['to'];
        $to= explode("-", $to);
        $to =  mktime(0, 0, 0, $to[1], $to[0], $to[2]);
        $student =  $this->input->get("student");
        $receipt =  $this->input->get("receipt");
        $query ="SELECT s.student_id,s.name,s.userid,s.admission_no,fa.account_id,fa.recipt,sum(fa.amount) as amount ,fa.time, se.name as sec_name , c.name as class_name FROM fee_accounts fa JOIN student s ON fa.student_id=s.student_id JOIN section se ON s.section_id=se.sid JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ";
                if(strlen($student)!=0){
                   $query.=" AND s.student_id='".$student."' "; 
                }
                if(strlen($receipt)!=0){
                     $query.=" AND fa.recipt LIKE '%".$recipt."%'  "; 
                }
                if(strlen($from)!=0){
                     $query.=" AND fa.time >='".$from."' "; 
                }
                if(strlen($to)!=0){
                     $query.=" AND fa.time <='".$to."' "; 
                }
              $query.= " GROUP BY fa.recipt ";
               $query=  $this->db->query($query);
                $query=$query->result();
        //   print_r($query);  
         $institute=  $this->fetch_institute_details();
           echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title> Accounts  Report</title>
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
                        <tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="158" height="95" /></td>
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
<h2 style=" color:  cornflowerblue; "><u>Account Report : </u></h2><br/>
               <table align="center"  class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">                    
                    <tr>
                        <th style=" text-align: center">Name</th>
                        <th style=" text-align: center">Admission No</th>
                        <th style=" text-align: center">Class-Section</th>
                        <th style=" text-align: center">Receipt NO</th>
                        <th style=" text-align: center">Amount</th>
                        <th style=" text-align: center">Time</th>
                    </tr>
                    <?php 
                    $total=0;
                               foreach ($query as $value) {
                                   ?>
                    <tr>
                        <td><?php echo $value->name ?></td>
                        <td><?php echo $value->admission_no ?></td>
                        <td><?php echo $value->class_name." - ".$value->sec_name ?></td>
                        <td><?php echo $value->recipt ?></td>
                        <td><?php echo $value->amount ?></td>
                         <td><?php echo date("d-m-Y H:i",$value->time) ?></td>
                    </tr>    
                                   <?php 
                          $total+=$value->amount;
                                   }
                    
                    ?>
                    <tr>
                        <th style=" color: red; font-size: 18px;" colspan="6">Total Amount : <?php echo $total ?></th>
                    </tr>
               </table>
<script>
window.print();
</script>
</div>
</body>
</html>
               <?php
         
    }
    
}


?>
