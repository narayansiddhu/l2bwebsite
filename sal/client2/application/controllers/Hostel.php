<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hostel extends CI_Controller {

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
            $this->operations->is_login();$this->load->model("validations");
            $check="SELECT `hostel` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->hostel==0){
                 $this->session->set_userdata('blocked_module', 'Hostel Module'); 
                redirect("/Acessdenied/","refresh");
            }
            /* cache control */
        }
    
    public function index(){
        $this->load->view('hostel/index');
    }
   
    public function Rooms(){
         $this->load->view('hostel/rooms');
    }
    public function Blocks(){
         $this->load->view('hostel/blocks');
    }

    public function Search(){
        
         $this->load->view('hostel/search');
    }

    public function maintenance(){
       $this->load->view('hostel/maintaince');  
    }
    
    public function add_block(){
       
        $block= $this->input->post("block_name");
        if(strlen($block)==0){
           echo "** Please Enter Block Name";exit;
        }else{
            $q="SELECT * FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' AND block_name = '".$block."' ";
            $q =  $this->db->query($q);
            if($q->num_rows()>0){
                echo "** Block Name Already Exists";exit;
            }  else{
                $data= array(
                    'iid' =>$this->session->userdata("staff_Org_id"),
                    'block_name' =>$block
                );
                $this->db->insert("hostel_blocks",$data);
                $this->session->set_userdata( 'hostel_block_add_Sucess', 'Block : '.$block.' created Sucessfully'); 
                   ?><script>location.reload();</script><?php
            }
            
        }
    }
    
    public function add_room(){
         $this->load->view('hostel/add_room');
    }
    
    public function  save_room(){
        $block =  trim($this->input->post("block"));
        $room = trim($this->input->post("room_name"));        
        $capacity = trim($this->input->post("capacity"));
        if(strlen($block)==0){
             $this->form->setError('block','* Please Select Block ');
        }
        if(strlen($room)==0){
             $this->form->setError('room_name','* Please Enter Name ');
        }else{
             if(strlen($block)!=0){
              $q="SELECT * from hostel_rooms WHERE  block_id = '".$block."' AND room ='".$room."' ";  
              $q= $this->db->query($q);
              if($q->num_rows()>0){
                    $this->form->setError('room_name','* Room Name Already Exists ');
              }
            }
            
        }
        if(strlen($capacity)==0){
             $this->form->setError('capacity','* Please Enter Room Capacity');
        }else{
            if(!is_numeric($capacity)){
                $this->form->setError('capacity','* Please Enter  Numeric Value');
            }   
        }
         if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                  redirect("Hostel/add_room",'refresh');
         }else{
                $data=array(
                    'iid'=>$this->session->userdata("staff_Org_id"),
                    'block_id' =>$block,
                    'room' =>$room,
                    'capacity' =>$capacity
                );
                $this->db->insert('hostel_rooms',$data);
                 $this->session->set_userdata('room_add_Sucess', 'New Room Added  Sucessfully'); 
                 redirect("Hostel/Rooms",'refresh');
         }
       
    }
 
    public function Manage_students(){
        $this->load->view('hostel/Manage_students'); 
    }
    
    public function assign_fee_structure($block_id=""){
     $block_id =  trim($block_id);
        if(strlen($block_id)==0){
            redirect("Hostel/Fee_structure","refresh");
        }else{
            $block="SELECT * from hostel_blocks where block_id='".$block_id."' AND iid='".$this->session->userdata("staff_Org_id")."' ";
            $block = $this->db->query($block);
            if($block->num_rows()==1){
                 $block=$block->row();
                 $data['block'] =$block;
                 $this->load->view('hostel/fee_settings',$data); 
            }else{
                   redirect("Hostel/Fee_structure","refresh");
            }
        }
    }

    public function Fee_structure(){
           $this->load->view('hostel/Fee_structure'); 
    }
    
    public function admit_student(){
        $this->load->view('hostel/admit_student'); 
    }
    public function load_balance(){
       // print_r($_POST);
        $paid =" SELECT sum(`fee`)  as total FROM `hostel_feepayment` WHERE  student = '".$this->input->post("student")."'";
        $paid = $this->db->query($paid);
        $paid = $paid->row();
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
    
    public function add_fee(){
        $student =$this->input->post('student');
        $payee =$this->input->post('payee');
        $tpaid =$this->input->post('total');
        $fee_Amount =$this->input->post('fee_amount');
        $paid =" SELECT sum(`fee`)  as total FROM `hostel_feepayment` WHERE  student = '".$this->input->post("student")."'";
        $paid = $this->db->query($paid);
        $paid = $paid->row();
        $paid=  $paid->total;
        $bal =$fee_Amount-$paid;
        if(!is_numeric($payee)){
            echo "** Please enter Numeric value";exit;
        }
        if(!is_numeric($student)){
            echo "** Please Select Student";exit;
        }
        if($bal <= 0 || $payee > $bal){
              echo "** Amount Excedeed Balance Amount";exit;
        }
        
         $n=  $this->get_invoice_no()+1;
         $no=  $this->make_9digit($n);
         $inv=  $this->fetch_institute_code()."hst".$no;
                                    
        $data= array(
              'iid' =>$this->session->userdata('staff_Org_id'),
              'student' =>$student,
            'invoice_no'=>$inv,
            'added_by' =>$this->session->userdata('staff_id'),
              'fee' =>$payee,
              'timestamp' =>time()
          ); 
        $n++; 
         $this->update_invoice_no($n-1);
          $this->db->insert('hostel_feepayment',$data); 
          $this->session->set_userdata('stud_fee_payee_Sucess', $inv); 
        ?>
              <script>
                window.location.href = '<?php echo base_url() ?>index.php/Hostel/pay_fee';
              </script>
              <?php
    }

     public function Invoice($inv){
         $inv =  trim($inv);
         if(strlen($inv)==0){
             redirect("Hostel/pay_fee","refresh");
         }else{
             $inv= "SELECT f.*,st.name from hostel_feepayment f JOIN staff st ON f.added_by=st.id where invoice_no = '".$inv."'";
             $inv= $this->db->query($inv);
             if($inv->num_rows()==0){
                   redirect("Hostel/pay_fee","refresh");
             }else{
                 $inv =$inv->row();
                 //print_r($inv);
                   $student=  $this->db->query("SELECT s.student_id,s.photo,s.roll,s.name,s.userid,c.id as cls_id,c.name as clsname , se.name as section,hs.fee, ( SELECT SUM(fee) from hostel_feepayment WHERE student = s.student_id )  as paid FROM   student s JOIN hostel_students hs ON hs.student_id =s.student_id  JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id   WHERE s.student_id='".$inv->student."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
          $student=$student->row();
          //print_r($student);
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
<div class="subject">Invoice No: '.$inv->invoice_no.'<!--for Cement.--></div><br />';
                         $payment='<table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                ';
                         $f_cat_html='<tr><th style="width:50%"  >HOSTEL ANNUAL FEE</th><td style="width:50%" >'.$inv->fee.'</td></tr>';
                          
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
<div class="subject">Invoice No: '.$inv->invoice_no.'<!--for Cement.--></div><br />
'.$payment.'<br />

 <b>Total Fee In Words &nbsp;: '.$this->convert_number($inv->fee).' Rupees Only /- <br />
 Payment Type&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : Cash </b> <br />
 
 
 
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

    public function save_settings(){
       
        $post=$_POST;
        $cls_ids=$this->input->post("cls_ids");
        $cls_ids=  explode(",", $cls_ids);
        foreach ($cls_ids as $value) {
            $id="block_cls_fee_".$value;
            if(strlen($post[$id])==0){
                   $this->form->setError($id,'* Please Enter Fee Amount');
            }else{
                if(!is_numeric($post[$id])){
                     $this->form->setError($id,'* Enter Valid Amount..');
                }
            } 
        }
        
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                redirect("Hostel/assign_fee_structure/".$this->input->post("block"),'refresh');
         }else{
             foreach ($cls_ids as  $value) {
                 $id="block_cls_fee_".$value;
                 $data=array(
                    'iid'=>$this->session->userdata("staff_Org_id"),
                    'block_id' =>$this->input->post("block"),
                     'class_id' =>$value,
                     'fee' =>  $this->input->post($id)
                );
                 if(isset($post['old_b_fee_'.$value])){
                    $array = array('class_id' => $value, 'block_id' => $this->input->post("block"));
                       $this->db->where($array); 
                       $this->db->update('hostel_fee', $data); 
                          
                 }else{
                    $this->db->insert('hostel_fee',$data);
                 } 
             }
           $this->session->set_userdata('H_fee_update_Sucess', 'Sucessfully Updated Fee ..');   
           redirect('Hostel/Fee_structure', 'refresh'); 

                
         }
        
        
    }
    
    public function pay_fee(){
          $this->load->view('hostel/fee_payments'); 
    }
    public function load_fee_structure(){
         $block =  $this->input->post("block");   
         $fee=$this->db->query(" SELECT f.fee,c.name from hostel_fee f JOIN class c ON f.class_id=c.id where block_id='".$block."'   ");
          if($fee->num_rows()>0){
              $fee =$fee->result();
              ?>
                   <table class="table table-bordered table-striped" style=" width: 100%; ">
                       <tr>
                           <th>S.No</th>
                           <th>Class</th>
                           <th>Fee</th>
                       </tr>
                       <?php
                       $i=1;
                        foreach ($fee as $value) {
                           ?>
                       <tr>
                           <td><?php echo $i++ ?></td>
                           <td><?php echo $value->name ?></td>
                           <td><?php echo $value->fee ?></td>
                       </tr>
                           <?php
                        }
                       ?>
                   </table>         
              <?php
          }else {
              ?>
                   <br/><br/>
                   <h4 style=" text-align: center; color: red">Please Configure Fee Structure</h4>
                    <script>
                     $('#admit_form').html("** Please  Configure settings ,Refer fee structure in Hostel ")
                    </script>
              <?php
          }
    }
    
    public function load_rooms(){
         $block =  $this->input->post("block");   
         
            $rooms="SELECT h.* , (SELECT count(*) from hostel_students where room_id = h.room_id ) as filled FROM `hostel_rooms` h  WHERE iid='".$this->session->userdata("staff_Org_id")."' AND block_id='".$block."' " ;
            $rooms=$this->db->query($rooms)->result();
            foreach ($rooms as $value) {
              ?>
                      <option value="<?php echo $value->room_id  ?>,<?php echo ($value->capacity - $value->filled ) ?>" ><?php echo $value->room  ?> (<?php echo $value->filled  ?>/<?php echo $value->capacity  ?>)</option>
            <?php    
            }     
          
    }

    public function load_rooms1(){
         $block =  $this->input->post("block");   
         
            $rooms="SELECT h.* , (SELECT count(*) from hostel_students where room_id = h.room_id ) as filled FROM `hostel_rooms` h  WHERE iid='".$this->session->userdata("staff_Org_id")."' AND block_id='".$block."' " ;
            $rooms=$this->db->query($rooms)->result();
           ?> <option value="">Select Room</option>
           <?php
            foreach ($rooms as $value) {
              ?>

                      <option value="<?php echo $value->room_id  ?>" ><?php echo $value->room  ?></option>
            <?php    
            }     
          
    }

    public  function add_maintainence(){
        
        if(strlen($this->input->post('block'))==0){
            echo "** Please select Block";exit;
        }
        if(strlen($this->input->post('room'))==0){
            echo "** Please select Room";exit;
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
              'block_id' =>$this->input->post('block'),
              'room_id'  =>$this->input->post('room'),
              'amount' =>$this->input->post('amount'),
              'reason' =>$this->input->post('reason'),
              'timestamp' =>time()
              
              
          ); 
          $this->db->insert('hostel_maintenance',$data); 
          $this->session->set_userdata('hostel_maintaince_Sucess', 'New Maintenance Record Added Sucessfully ..'); 

        
        ?>
              <script>
                window.location.href = '<?php echo base_url() ?>index.php/hostel/maintenance';
              </script>
              <?php
        
        
    }
    
    public function  Maintaince_Category(){
         $this->load->view('hostel/Maintaince_category'); 
    }


    public function add_student(){

        $fee=$this->input->post("amount");
        if(!is_numeric($fee)){
            ?>
                      <script>
                        $('#amount_err').html("** Please select valid Amount");    
                      </script>
            <?php
        }else{
            $data=array(
                "iid" =>$this->session->userdata("staff_Org_id"),
                'student_id' => $this->input->post("student"),
                'room_id' =>$this->input->post("room"),
                'fee' =>$this->input->post("amount")
                );
            $this->db->insert("hostel_students",$data);
          $this->session->set_userdata('hostel_add_Sucess', 'New Student Admitted..'); 
          ?>
              <script>
                        window.location.href = '<?php echo base_url() ?>index.php/Hostel/Manage_students';
              </script>
          <?php
            
            
        }
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
            $query = $this->db->query("SELECT `last_id` FROM `other_invoice` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=1 ");
            if($query->num_rows()==0){
                $data=array(
                    "iid" =>$this->session->userdata('staff_Org_id'),
                    'start'=>1,
                    'type'=>1,
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
      $this->db->query("UPDATE `other_invoice` SET `last_id`='".$no."' WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND type=1   ");  
    }
    
function convert_number($number) {
		if (($number /*<*/ = 0) || ($number > 999999999)) {
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
}
?>