<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenditure extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('operations');
        $this->load->model('form'); $this->load->model('logs');
        $this->load->library("pagination");
        /* cache control */
        $this->operations->is_login();
        $check="SELECT `expenses` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
        $check = $this->db->query($check)->row();
        if($check->expenses==0){
             $this->session->set_userdata('blocked_module', 'Expenditure Module'); 
            redirect("/Acessdenied/","refresh");
        }
        
    }

    public function index()
    {
      $query=  $this->db->query("select * from accounts where iid= '".$this->session->userdata('staff_Org_id')."' AND category ='2'");
      if(is_null($this->session->userdata('email')) && $this->router->fetch_class() == 'expenditure' && $this->session->userdata('iid') != $this->session->userdata('staff_Org_id') && $query->num_rows() > 0){
                 redirect("accounts/login/2",'refresh'); 

              }
              else{
                $this->load->view("expenditure/dash_board");
                //echo current_url();
              }
        	   
    }
    public function add_new()
    {
        $this->load->view("expenditure/index");	   
    }
    
    public function history()
    {
        $this->load->view("expenditure/history");	   
    }
    
    public function View()
    {
        $this->load->view("expenditure/view_all");	   
    }
    
    public function add(){
//       echo "<pre>";
//       print_r($_POST);
//       print_r($_FILES);
//       exit;
        $post=$this->operations->cleanInput($_POST);
        
        $field = 'category';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Select category ');
        }
        //mode
        $field = 'mode';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Select Mode ');
        }
        //refererer
        $field = 'refererer';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Reference Person Name ');
        }
        $field = 'reason';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Reason ');
        }
        
        $field = 'amount';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Amount ');
        }elseif(!is_numeric(trim($post[$field]))){
            $this->form->setError($field,'* Please Enter Numeric Value ');
        }
        
        $field = 'bils';
        $img_uplod=0;
        if(strlen($_FILES[$field]['name']) ==  0)
        {
              //  $this->form->setError($field,'* Please Select a profice pic');
        }else{
            $img_uplod=2;
            $ext=explode(".",$_FILES['bils']['name']);
            $ext=  strtolower($ext[sizeof($ext)-1 ]);
            $img_arr=array('jpg','jpeg','gif','png');
            if(!in_array($ext,$img_arr)){
                $this->form->setError($field,'* Please Select a Image file');
            }
        } 
        
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
          
        }else{
            $file ="";
            if($img_uplod==2){
                $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."_exp_".time().".".$ext; 
                $config['upload_path']   = upload_path; 
                //'var/www/html/schooln/assests_2/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
                $this->load->library('upload', $config);
                $this->upload->do_upload('bils');
            }
            
            $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'category' =>$post['category'],
                       'reason' => $post['reason'],
                       'ref_person' =>$post['refererer'],
                       'mode' => $post['mode'],
                       'amount' => $post['amount'],
                       'time'=>time(),
                       'staff_id' =>  $this->session->userdata("staff_id"),
                       'status' =>1,
                       'file' => $file
                    );
            $this->db->insert('expenditure',$data);
            $this->session->set_userdata('add_expenditure_sucess', "Sucessfully Added New Expenditure");
            $this->logs->insert_staff_log(17,"Added New Expenditure",$this->db->insert_id());
        }   
        redirect("expenditure/add_new","refresh");
    }
    
    public function save(){
       
        $post=$this->operations->cleanInput($_POST);
      //  print_r($post);exit;
        $field = 'category';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Select category ');
        }
        //mode
        $field = 'mode';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Select Mode ');
        }
        //refererer
        $field = 'refererer';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Reference Person Name ');
        }
        $field = 'reason';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Reason ');
        }
        
        $field = 'amount';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide Amount ');
        }elseif(!is_numeric(trim($post[$field]))){
            $this->form->setError($field,'* Please Enter Numeric Value ');
        }
        
        $field = 'bils';
        $img_uplod=0;
        if(strlen($_FILES[$field]['name']) ==  0)
        {
              //  $this->form->setError($field,'* Please Select a profice pic');
        }else{
            $img_uplod=2;
            $ext=explode(".",$_FILES['bils']['name']);
            $ext=  strtolower($ext[sizeof($ext)-1 ]);
            $img_arr=array('jpg','jpeg','gif','png');
            if(!in_array($ext,$img_arr)){
                $this->form->setError($field,'* Please Select a Image file');
            }
        } 
        
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
          
        }else{
        
            $file ="";
            if($img_uplod==2){
                $file=$config['file_name'] = $this->session->userdata('staff_Org_id')."_exp_".time().".".$ext; 
                $config['upload_path']   = upload_path; 
                //'var/www/html/schooln/assests_2/uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
                $this->load->library('upload', $config);
                $this->upload->do_upload('bils');
            }
            
            $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'category' =>$post['category'],
                       'reason' => $post['reason'],
                       'ref_person' =>$post['refererer'],
                       'mode' => $post['mode'],
                       'amount' => $post['amount'],
                       'time'=>time(),
                       'staff_id' =>  $this->session->userdata("staff_id"),
                       'status' =>1,
                    );
         if(strlen($file)!=0){
             $data['file']= $file;
         }
            $this->db->where('id',$post['id']);
            $this->db->update('expenditure',$data);
            $this->session->set_userdata('edit_expenditure_sucess', "Sucessfully Edited Expenditure");
           
        }   
        redirect("expenditure/history/","refresh");
    }
    
    public function approvals(){
         $this->load->view("expenditure/approvals");	   
    }
    
    
    public function category(){
       $this->load->view("expenditure/categories");	   
    }
    
    
    public function approve(){
        
        $data = array(
                'status' =>2,
                'approved_on' =>time(),
                'approved_by' =>  $this->session->userdata("staff_id")
             );
        $this->db->where('id',  $this->input->post('id'));
        $this->db->update('expenditure',$data);
        $this->session->set_userdata('approve_expenditure', "Approved Expenditure");
       ?>
<Script>location.reload();</script>
      <?php
    }
    
    public function reject(){
      //  print_r($_POST);
        $data = array(
                'status' =>0,
                'approved_on' =>time(),
                'approved_by' =>  $this->session->userdata("staff_id")
             );
        $this->db->where('id',$_POST['id']);
        $this->db->update('expenditure',$data);
        $this->session->set_userdata('approve_expenditure', "Rejected Expenditure");
        ?>
         <Script>location.reload();</script>
        <?php
    }

    public function approved_list(){
        $this->load->view("expenditure/approved");	 
    }
    public function rejected_list(){
        $this->load->view("expenditure/rejected_list");	 
    }
    
    public function save_category(){
       
       $post=$this->operations->cleanInput($_POST);
        $field = 'category';
        if(strlen(trim($post[$field])) ==  0)
        {
           $this->form->setError($field,'* Please Provide category  ');
        }elseif(!$this->check_exp_category($this->input->post('category'), $this->input->post('cat_id') )){
            $this->form->setError($field,'* Category Already Exists');
        }
        
        
        if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
          
        }else{
            $data = array(
                'iid' =>$this->session->userdata('staff_Org_id'),
                'name' =>  $this->input->post("category")
            );
            if(strlen($this->input->post('cat_id'))==0){
                $this->db->insert('expense_category',$data);
                $this->logs->insert_staff_log(30,'Created Expenditure category '.$this->input->post("category"),$this->db->insert_id());
                $this->session->set_userdata('expense_category_Sucess', 'New Expense Category Added Sucessfully');   
              
            }else{
                $this->db->where('cat_id',$this->input->post('cat_id'));
                $this->db->update('expense_category',$data);
                $this->logs->insert_staff_log(30,'Updated Expenditure category '.$this->input->post("category"),$this->db->insert_id());
                $this->session->set_userdata('expense_category_Sucess', 'Expense Category Updated Sucessfully');   
              
            }
            
        }
         redirect('expenditure/category', 'refresh'); 
    }
    
    

    public function edit($id=""){
        $id=trim($id);
       if(strlen($id)==0){
          redirect("expenditure/history"); 
       }else{
           $t=$this->can_edit($id);
          
           if(!$t){
               redirect("expenditure/history");  
           }else{
               $data['exp']=$t;
               $this->load->view("expenditure/edit",$data);	 
           }
       } 
    }

    private function can_print($id){
        $query=$this->db->query("SELECT  e.* , s.name as added_by , a.name as approved_by,c.name as cat_name FROM `expenditure` e left JOIN staff s ON e.staff_id = s.id left JOIN staff a ON e.approved_by=a.id JOIN expense_category c ON e.category=c.cat_id  WHERE e.id='".$id."' AND e.iid='".$this->session->userdata('staff_Org_id')."' AND e.status=2");
       if($query->num_rows()==0){
           return FALSE;
       }else{
           $query=$query->row();
           return $query;
       }        
    }
    
    private function can_edit($id){
        $query=  $this->db->query("SELECT * FROM `expenditure` WHERE id='".$id."' AND iid='".$this->session->userdata('staff_Org_id')."' AND status!=2");
       if($query->num_rows()==0){
           return FALSE;
       }else{
           $query=$query->row();
           return $query;
       }
        
    }
    
    private function check_exp_category($name,$id=0){
        $query ="SELECT *  FROM `expense_category` WHERE `name` = '".$name."' AND iid='".$this->session->userdata('staff_Org_id')."'";
        if($id!=0){
            $query.=" AND cat_id != '".$id."' ";
        }
        
        $query = $this->db->query($query);
        if($query->num_rows()==0){
           return TRUE;
       }else{
           return FALSE;
       }
        
        
    }
    
    public function  load_bill(){
     
     $file=$_POST['file'];
     if(strlen($file)==0){
                 ?>
         <h5 style=" text-align: center; color: red; ">** NO Bill Uploaded </h5> 
                <?php
              }else{
                  if(file_exists(upload_path.$file)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $file  ?>" alt="..." style=" width: 100%;; height: 400px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
                     <h5 style="text-align: center; color: red; ">** NO Bill Uploaded </h5> 
                    <?php
                 }
              }
             
    }
    
    public function print_voucher($id=""){
        $id=trim($id);
       if(strlen($id)==0){
          redirect("expenditure/history"); 
       }else{
           $t=$this->can_print($id);
         //  print_r($t);exit;
           if(!$t){
               redirect("expenditure/history");  
           }else{
              $mode ="";
              switch($t->mode){
                case 1: $mode=  "Cash"; break;
                case 2: $mode= "Cheque";break;
                case 3: $mode= "Card Payments";break;
                case 4: $mode= "Other payment Modes";break;
             }
             $status="";
             switch($t->status){
                case 1: $status=  "Not Approved"; break;
                case 2:$status= "Approved On ".date('d-m-y',$t->approved_on);
                       break;
                case 0: $status= "Rejected";break;
             }
              $institute= $this->fetch_institute_details();
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EXPENDITURE VOUCHER</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; min-height:400px; }
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
.tab_td2  td{ text-align:left;  padding:4px 2px; vertical-align:text-top; }
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
<h2 style=" color:teal; text-align:center ">EXPENDITURE VOUCHER</h2>
<table style="border:2px sold #00306C;" align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
  <td style="text-align:left" >Amount : '.$t->amount.'/-</td>
  <td style="text-align:right">Date & Time   : '.date("d-m-Y H:i",$t->time).'</td>
</tr>
<tr>
  <td style="text-align:left" >Mode Of Payment :'.$mode.'</td>
  <td style="text-align:right">Status :'.$status.'</td>
</tr>
<tr>
  <td style="text-align:left" >Reference Person  :'.$t->ref_person.'</td>
  <td style="text-align:right">Expenditure Category :'.strtoupper($t->cat_name).'</td>
</tr>
<tr>
  <td colspan="2" style="text-align:left;" ><u>Reason  :</u> <br/>'.$t->reason.'</td>
</tr>
</table>
<div style=" width:100%; clear:both; text-align:center; padding-top: 5px; color: #00306C; font-weight: bold; ">
<div style=" width:33%; float:left; border:1px solid black; height:50px;">
Added BY : <br/> '.strtoupper($t->added_by).'
</div>
<div style=" width:33%; float:left; border:1px solid black;  height:50px;">
Recivied By : <br/>
</div>
<div style=" width:33%; float:left; border:1px solid black;  height:50px;">
Approved BY : <br/> '.strtoupper($t->approved_by).'
</div>
</div>
<script>
window.print();
</script>
</div>
</div>
</body>
</html>';
           }
       } 
    }
    
     private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
    public function print_expenditure($type="self"){
        $institute= $this->fetch_institute_details();
              echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EXPENDITURE PRINT OUTS</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:850px;margin-left:auto;padding:5px; margin-right: auto; min-height:400px; }
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
.tab_td2  td{ text-align:left;  padding:4px 2px; vertical-align:text-top; }
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
';
              if($type=="self"){
                 ?>
                   <h3 style=" color:teal; text-align:center ">EXPENDITURES</h3>
                  <table style="border:2px sold #00306C;" align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>S.no</th>
                            <th>Reason</th>
                            <th>Reference Person</th>
                            <th>Mode payment</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th>Status</th>
                            
                        </tr>
                      <?php
                 $i=1;$total=0;
                             $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND staff_id='".$this->session->userdata('staff_id')."' ORDER BY time DESC ");
                             $query=$query->result();
                             foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->ref_person ?></td>
                                <td><?php
                                        switch($value->mode){
                                           case 1: echo  "Cash"; break;
                                           case 2: echo "Cheque";break;
                                           case 3: echo "Card Payments";break;
                                           case 4: echo "Other payment Modes";break;
                                        }
                                        ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2:echo "Approved On :".date('d-m-y',$value->approved_on);
                                                  break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                
                            </tr>
                              <?php
                             }
                  ?>
                  </table>
                  <?php 
              }else{
                  ?>
                   <h3 style=" color:teal; text-align:center ">EXPENDITURES</h3>
                   <table style="border:2px sold #00306C;" align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <th>S.no</th>
                            <th>Added By</th>
                            <th>Reason</th>
                            <th>Reference Person</th>
                            <th>Mode payment</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                      <?php
                 $i=1;$total=0;
                $query=$this->db->query("SELECT e.*,s.name as staff FROM `expenditure` e JOIN staff s ON e.staff_id=s.id  WHERE e.iid='".$this->session->userdata('staff_Org_id')."'  ORDER BY e.time DESC ");
                $query=$query->result();
                foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->staff ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->ref_person ?></td>
                                <td><?php
                                        switch($value->mode){
                                           case 1: echo  "Cash"; break;
                                           case 2: echo "Cheque";break;
                                           case 3: echo "Card Payments";break;
                                           case 4: echo "Other payment Modes";break;
                                        }
                                        ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2:echo "Approved On :".date('d-m-y',$value->approved_on);
                                                  break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                
                            </tr>
                              <?php
                             }
                  ?>
                  </table>   
                  <?php
              }
              
echo '<script>
window.print();
</script>
</div>
</div>
</body>
</html>';
    }
        
}


?>
