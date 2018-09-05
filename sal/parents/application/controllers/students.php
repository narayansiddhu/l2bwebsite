<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
          //  $this->load->model('Barcode');
             $this->operations->is_login();
            /* cache control */
        }
        
        
        
        public function view_student($userid=""){
            
            if(strlen($userid)==0){
               redirect("Login/dashboard","refresh"); 
            }else{
                 $student="SELECT st.*, s.name as sec_name , c.name as cls_name FROM `student` st JOIN `section` s ON st.section_id=s.sid JOIN `class` c ON s.class_id = c.id  WHERE st.parent_id   ='".$this->session->userdata('parent_id')."'  AND st.iid ='".$this->session->userdata('parent_org_id')."' AND st.userid='".$userid."' ";
                 $student = $this->db->query($student);
                 if($student->num_rows()==1){
                     
                     $student=$student->row();
                     $data['student']=$student;
                    
                     $this->load->view("studentdetails",$data);  
                 }else{
                        redirect("Login/dashboard","refresh");
                 }
            }
        }
        public function index(){
            $this->load->view("home");
        }
        
        public function timetable(){
            $this->load->view("timetable");
        }
        
        public function Notifications(){
            $this->load->view("Notifications");
        }
        
        
        public function fees(){
            $this->load->view("fee_payments");
        }
        
        public function attendance(){
            $this->load->view("attendance");
        }
        
        public function assignments(){
            
             $this->load->view("assignments");
        }
        
        public function exam_schedule(){
            $this->load->view("examschedule");
        }
        
        public function exam_reports(){
            $this->load->view("exam_reports");
        }

        public function library(){
            $this->load->view("library_reports");
        }
        
        public function view($student=""){
           $student=trim($student); 
           if( (strlen($student)==0) || (!is_numeric($student)) ){
               redirect("",'refresh');
           }else{
              $s= $this->check_student($student);
              if(!$s){
                  redirect("",'refresh');
              }else{
                  $data['student']=$s;
                  $this->load->view("studentdetails",$data);   
              }
              
           }
        }
        
        public function student_profiles(){
            $this->load->view("students");   
        }
          
        public function ajax_library(){
            $student=  $this->check_student($this->input->post('student'))
            ?>
            

             <div class="box" style=" max-height: 350px; "  >
                    <h3 style=" text-align: center ; color:  #ff9900">Library  Issues</h3>   
                
                    <table  class="table table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Issue ID</th>
                                <th>Book Name</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                                <th>Fine</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php
                         $query=  $this->db->query("SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,lb.name as book FROM `lib_issues` li  JOIN lib_books lb ON li.bookid=lb.book_id   WHERE li.student_id='".$student->student_id ."' AND li.iid='".$this->session->userdata('parent_org_id')."' ");
                        ?>
                        <tbody>
                            <?php
                            if($query->num_rows()>0 ){
                               $i=1;
                             $query=$query->result();                          
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><?php  echo $value->trans_id;?></td>
                                <td><?php  echo $value->book;?></td>
                                <td><?php  echo date("d-m-Y", $value->issued_date);?></td>
                                <td><?php  
                                 if($value->return_date ==0){
                                    echo "--";                                    
                                  }else{
                                      echo date("d-m-Y",$value->return_date);
                                  }
                                ?></td>
                                
                                <td><?php  echo $value->fine;?></td>
                                <td><?php  
                                if($value->status==1){
                                                       echo "Not Returned";
                                                   }else{
                                                       echo "Returned";
                                                   }
                                ?></td>
                            </tr>
                                <?php
                             
                              } 
                            }else{
                                ?>
                            <tr><td colspan="7">No Records Found</td></tr>
                                <?php
                            }
                            
                            
                            ?>
                            
                        </tbody>
                    </table>
           <hr/>
                    <h3 style=" text-align: center ; color:  #ff9900">Library Fines</h3>   
                
                    <table class="table table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Issue id</th>
                                <th>Time</th>
                                <th>Description</th>
                                <th>Fee</th>
                                <th>Paid Amount</th> 
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $query="SELECT i.trans_id,f.fee,f.time,f.description,f.status,sum(p.amount) as paid FROM lib_fines f JOIN lib_issues i ON f.issue_id =i.issue_id JOIN lib_payments p ON p.fee_id=f.fine_id  WHERE i.student_id='".$student->student_id ."' AND i.iid='".$this->session->userdata('parent_org_id')."'";
                              $query=  $this->db->query($query);
                              if($query->num_rows()>0 ){
                                 $query=$query->result();
                                 $i=1;
                                 foreach($query as $value){
                                   ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value->trans_id ?></td>
                                        <td><?php echo $value->time ?></td>
                                        <td><?php echo $value->description ?></td>
                                        <td><?php echo $value->fee ?></td>
                                        <td><?php echo $value->paid ?></td>
                                        <td><?php echo ($value->fee - $value->paid) ?></td>
                                    </tr>
                                   <?php
                                 }
                              }else{
                                 ?>
                            <tr>
                                <td colspan="6">No Records Found</td>
                            </tr>
                                <?php
                              }
                              
                            ?>
                        </tbody>                        
                    </table>
                
            </div>
                       
            <?php
        }
        
        public function ajax_fee(){
            $student=  $this->check_student($this->input->post('student'))
            ?>
<div class="box">
    

<h3 style=" text-align: center; color: #ff9900">Fee Structure</h3>   
<table class="table table-bordered table-hover table-nomargin nopadding" style=" width: 100%; padding-right: 15px;">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Category</th>
                                <th>Total Fee</th>
                                <th>Concession</th>
                                <th>Paid</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <?php
                         $query=  $this->db->query("SELECT f.fid,f.fee,c.cid,c.category ,(select sum(amount)  from  fee_accounts a where  f.category = a.category AND a.student_id= '".$student->student_id."' ) as paid ,(select sum(amount)  from  fee_concession fc where  f.category = fc.cat_id AND fc.std_id='".$student->student_id."' ) as concession FROM `fee_class` f JOIN fee_category c ON f.category=c.cid WHERE f.cls_id='".$student->class_id."' ");
                         $query=$query->result();
                        ?>
                        <tbody>
                            <?php
                            $i=1;
                            $total=0;$totalc=0;$totalp=0;
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><?php  echo $value->category;?></td>
                                <td><?php  echo $value->fee;?></td>
                                <td><?php  
                                if(strlen($value->concession)>0){
                                    echo $value->concession;
                                }else{
                                    echo "0";
                                    $value->concession=0;
                                }
                                ;?></td>
                                <td><?php
                                if(strlen($value->paid)>0){
                                    echo $value->paid;
                                }else{
                                    echo "0";
                                    $value->paid=0;
                                }
                               ?></td>
                                <td><?php  echo $value->fee-($value->concession + $value->paid );?></td>
                            </tr>
                                <?php
                                $total+=$value->fee;
                                $totalc+=$value->concession;
                                $totalp+=$value->paid;
                              }
                            ?>
                            <tr>
                                <td colspan="2" ><span style=" float: right">Total Fee Amount</span></td>
                                <td><?php echo $total ?></td>
                                <td><?php echo $totalc ?></td>
                                <td><?php echo $totalp ?></td>
                                <td><?php echo $total -($totalp+$totalc) ?></td>
                            </tr>
                        </tbody>
                    </table>
                
            <hr/>
                      <h3 style=" text-align: center; color: #ff9900" >Fee Payment History</h3>   
                    <table class="table table-bordered table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Recipt No</th>
                                 <th>Time</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <?php
                         $query=  $this->db->query("SELECT * FROM `fee_accounts` WHERE student_id='".$student->student_id ."' AND iid='".$this->session->userdata('parent_org_id')."'  ");
                         $query=$query->result();
                        ?>
                        <tbody>
                            <?php
                            $i=1;
                            $t=0;
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><a target="_blank" href="<?php echo base_url() ?>index.php/students/printout/<?php  echo $value->recipt;?>"><?php  echo $value->recipt;?></a></td>
                                <td><?php  echo date('d-m-Y',$value->time);?></td>
                                <td><?php  echo $value->amount;?></td>
                            </tr>
                                <?php
                                $t+=$value->amount;
                              }
                            ?>
                        </tbody>
                    </table>
            </div>    
                        
           <?php
        }
         private function fetch_record_info($account_id){
            $query=  $this->db->query("SELECT s.student_id,s.photo,s.roll,f.account_id,s.name,s.userid,c.id as cls_id,cat.category,f.recipt,f.amount,f.time,c.name as clsname , se.name as section ,st.name as staff_name FROM fee_accounts f JOIN student s ON f.student_id=s.student_id JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id JOIN staff st ON st.id=f.staff_id JOIN fee_category cat ON f.category = cat.cid WHERE f.recipt='".$account_id."' AND f.iid='".$this->session->userdata("parent_org_id")."'");
            if($query->num_rows()>0){
                $account_query=array();
               $query= $query->result();
                foreach($query as $val){
                    $account_query['student']=array('student_id'=>$val->student_id,'cls_id'=>$val->cls_id,'name'=>$val->name,'userid'=>$val->userid,'roll'=>$val->roll,'photo'=>$val->photo,'cls_sec'=>$val->clsname." - ".$val->section,'paid_time'=>$val->time);
                    $account_query[$val->recipt][]=array('category'=>$val->category,'amount'=>$val->amount);
                }
               
               return  $account_query;
            }else{
                return FALSE;
            }
        }
      
        private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("parent_org_id")."' ");
              return  $query->row();
        }
        
public function convert_number($number) {
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
		return strtoupper($res)." ONLY /- ";
	}
        public function printout($inv_no=""){
            $inv_no=trim($inv_no);
            if(strlen($inv_no)==0){
                redirect("Login/dashboard",'refresh');
            }else{
                $a=$this->fetch_record_info($inv_no);
                if(!$a){
                    redirect("Login/dashboard",'refresh');
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

<div style="border:2px solid #00306C;height:160px;">

<div style="float:left;width:50%">
	<img src="'.assets_path .'/uploads/'.$std_image.'" width="160" height="120" style="padding:10px;margin:10px;" />
</div>
<div class="verticalLine" style="float:left;height:138px;">&nbsp;</div>
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
<div class="subject">Invoice No: '.$inv_no.'<!--for Cement.--></div><br />
'.$payment.'<br />

 <b>Total Fee In Words &nbsp;: '.$this->convert_number($rammount).' <br />
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
<script>
window.print();
</script>
<div style="clear:both;"></div>
</div>
</div>
</body>
</html>';
                         
                }
                
            }
            
            ?>
           <?php
        }
        
        public function ajax_timetable(){
            $student=  $this->check_student($this->input->post('student'));
            $timetable=  $this->db->query("SELECT * FROM `timings` WHERE section ='".$student->section_id."' ");
            if($timetable->num_rows() >0){
               ?>
              
               <div class="box">
                   <h3 style=" text-align: center; color: #ff9900">Time Table</h3>   
                    <table class="table table-bordered table-hover table-nomargin" >
                        <thead>
                            <tr>
                                <th>Day/timings</th>
                               
                                            <?php
                                $timetable=$timetable->row();
                                $weekdays = unserialize (Week_days);
                                $start=$timetable->start;
                                $noofc=$timetable->classes;
                                $span=$timetable->span;
                                
                                $periods=$this->db->query("SELECT DISTINCT (time_start),time_end  FROM `class_routine` WHERE `tid` = '".$timetable->tid."'  ");
                                $periods =$periods->result();
                                $interval_arr =array();
                                $prev_end=0;
                                foreach($periods as $period){
                                     if( ($prev_end!=0) && ($prev_end !=$period->time_start) ){
                                         
                                         $interval_arr[] = array('period'=>'Break','start'=>$prev_end,'ending' =>$period->time_start); 
                                     }
                                    ?>
                                   <th><?php echo date("H:i",mktime(substr($period->time_start,0,strlen($period->time_start)-2), substr($period->time_start,strlen($period->time_start)-2)))  ?> - <?php echo date("H:i",mktime(substr($period->time_end,0,strlen($period->time_end)-2), substr($period->time_end,strlen($period->time_end)-2)))  ?></th>
                                    <?php
                                    $prev_end=$period->time_end;
                                }
                               
                                
                                
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                               $query=$this->db->query("SELECT cr.*,c.subid,s.subject FROM class_routine cr LEFT JOIN course c ON cr.course_id =c.cid LEFT JOIN subjects s ON c.subid=s.sid  WHERE cr.tid='".$timetable->tid."' ORDER BY cr.day asc,cr.time_start ASC ");
                                
                               if($query->num_rows()>0){
                               
                               $query=$query->result();
                                $prev="";$ids="";
                                foreach ($query as $value) {
                                    $ids.=$value->class_routine_id.",";
                                  if($prev!=$value->day){
                                    if($prev!=""){
                                        ?>
                                         </tr>
                                     <?php
                                    }
                                      ?>
                                    <tr>
                                        <th><?php echo $weekdays[$value->day] ?></th>
                                        <td><?php 
                                               if( strlen($value->subject)==0){
                                                   echo "--";
                                               }else{
                                                   echo $value->subject;
                                               }?></td>
                                        
                                    <?php
                                  }else{
                                      ?>
                                        <td>
                                            <?php 
                                               if( strlen($value->subject)==0){
                                                   echo "--";
                                               }else{
                                                   echo $value->subject;
                                               }?>
                                        </td>
                                        <?php
                                  }
                                   $prev =$value->day;
                                }
                                
                                 if($prev!=""){
                                        ?>
                                         </tr>
                                     <?php
                                    }
                                    
                                $ids=substr($ids,0,strlen($ids)-1);
                               }else{
                                   ?>
                                         <tr >
                                             <td></td>
                                         </tr>
                                   <?php
                               } 
                            ?>
                        </tbody>
                    </table>
              </div>
                        
               <?php
            }

                    
        }
        
        public function ajax_profile(){
             $student=  $this->check_student($this->input->post('student'))
            ?>

                        <div class="box">
           <div class="box-title">
                        <h3><i class="fa fa-th-list"></i><?php echo $student->name." 's  Profile"; ?></h3>
                </div>
            
           <div class="box-content nopadding">
                 <div  class='form-horizontal form-bordered'>
                     
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Name</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->name ?></label>
                                </div>
                        </div> 
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Admission No</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->userid ?></label>
                                </div>
                        </div>
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Birthday</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo date('d-m-Y',$student->birthday) ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Gender</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php if($student->sex ==1){
                                        echo "Male";
                                    }else{
                                        echo "Female";
                                    }    ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Mobile No</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->phone ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Email</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->email ?></label>
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Class-Section</label>
                                <div class="col-sm-9">
                                    <label class="form-control" ><?php echo $student->class_name ." - " .$student->section_name ?></label>
                                </div>
                        </div>
                 </div>
            </div>  
                        </div>

            <?php
        }
        
        public function ajax_assignments(){
              $student=  $this->check_student($this->input->post('student'));
              $date=$this->input->post('date');
             
              $hide=  $this->input->post('hide');
            ?>
<div class="box">
    
</div>
               <?php
                  if(strlen($hide)==0){
                      ?>
<h3 style=" text-align: center ; color:  #ff9900" >Fetch Assignments</h3>   
<div  class='form-horizontal form-bordered' style=" border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc">

                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-3">Submission Date</label>
                                                    <div class="col-sm-6">
                                                        <input type="date" name="subdate" id="subdate" class="form-control "  placeholder="dd/mm/yyyy" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <button onclick="fetch_assignments();" class="btn btn-primary">Fetch Assignmnents</button>
                                                        <span style=" color: red" id="sub_date_error">

                                                        </span>
                                                    </div>
                                            </div> 
                                        </div>
<hr/>
                      <?php
                  }
               ?>
                 <h3 style=" text-align: center ; color:  #ff9900">Assignments</h3>   
                    <table class="table table-bordered table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Subject</th>
                                <th>Assignment</th>
                                <th>Issued On</th>
                                <th>Submission On</th>
                            </tr>
                        </thead>
                        <?php
                        if(strlen(trim($date))==0){
                      
                        $query= (" SELECT a.message,a.timestamp,a.submission,s.subject FROM assignments a JOIN course c ON a.courseid=c.cid  JOIN subjects s ON c.subid=s.sid WHERE c.secid='".$student->section_id ."' AND c.iid='".$this->session->userdata('parent_org_id')."'     ");
                        }else{
                         if(strlen($hide)==0){
                           $date =explode("-",$date);
                      
                         $date=  mktime(0, 0, 0, $date[1], $date[2], $date[0]);  
                         }else{
                             $date =explode("-",$date);
                            
                            $date=  mktime(0, 0, 0, $date[1], $date[0], $date[2]); 
                         }
                         
                         
                         $query= (" SELECT a.message,a.timestamp,a.submission,s.subject FROM assignments a JOIN course c ON a.courseid=c.cid  JOIN subjects s ON c.subid=s.sid WHERE c.secid='".$student->section_id ."' AND c.iid='".$this->session->userdata('parent_org_id')."' AND a.submission = '".$date."'     ");
                        }
                      
                        $query=  $this->db->query($query);
                         
                        ?>
                        <tbody>
                            <?php
                            if($query->num_rows() >0){
                                $i=1;
                            $query=$query->result();
                              foreach($query as $value){
                                  ?>
                            <tr>
                                <td><?php  echo $i++;?></td>
                                <td><?php echo $value->subject ?></td>
                                <td><?php  echo $value->message;?></td>
                                <td><?php  echo date('d-m-Y',$value->timestamp);?></td>
                                <td><?php  echo date('d-m-Y ',$value->submission);?></td>
                            </tr>
                                <?php
                              
                              }
                            }else{
                                ?>
                            <tr><td colspan="5">No Records Found..</td></tr>
                               <?php
                            }
                            
                            
                            ?>
                           
                        </tbody>
                    </table>
                 
            </div>
                        
            <?php
        }
        
        public function courses(){
            $this->load->view("courses");
        }
        
        
        public function ajax_course(){
              $student=  $this->check_student($this->input->post('student'));
              ?>
             <div id="assignments_holder" class="box">
                 <h3 style=" text-align: center; color:  #ff9900">Course Structure</h3>   
                   <table  class="table  table-hover table-nomargin table-bordered" style="width: 100%;">
                               <thead>
                                          <th>S.no</th>
                                          <th>Subject</th>
                                          <th>Faculty Name</th>
                                </thead>
                               <tbody>
                                   <?php
                                     $query="SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$student->section_id."' ORDER BY sec.sid , s.sid ASC";
                                     $query=$this->db->query($query);
                                     $query=$query->result();$i=1;
                                     foreach($query as  $value){
                                       ?>
                                      <tr id="tr_<?php   echo $value->cid; ?>">
                                               <td id="id_<?php   echo $value->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                           <td style=" width: 15%" ><?php echo $value->subject; ?></td>
                                           <td  style=" width: 40%"><?php if($value->name !=NULL ){
                                                               echo $value->name;
                                                           }else{
                                                               echo "--";
                                                           }?></td>
                                           
                                       </tr>   
                                      <?php
                                     }
                                     ?>

                               </tbody>
                            </table>
            </div>
                        
            <?php
        }
        
        public function ajax_attendance(){
             $student=  $this->check_student($this->input->post('student'));     
           //  print_r($student);exit;
            $now=time();    
            if( ( (strlen($this->input->post('month'))!=0 ) && (strlen($this->input->post('year'))!=0 ) )){
              
                  $now=   mktime(0,0,0,$this->input->post('month'),1,$this->input->post('year'));
             
            }
            $time=  getdate($now);
            $from=mktime(0,0,0,$time['mon'],1,$time['year']);
            $to=mktime(0,0,0,$time['mon']+1,1,$time['year']);
            if($this->session->userdata("institute_att_type")==1){
                
            }else{
                echo "select * from attendance_settings where section ='".$student->section_id."' ";
                   $att_set =$this->db->query( "select * from attendance_settings where section ='".$student->section_id."' ");
                
                   if($att_set->num_rows()!=0){
                    $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student->student_id."' AND s.iid='".$this->session->userdata("parent_org_id")."' ");
                  if($student->num_rows()==0){
                       ?><br/><br/>
                <span style=" color: red; text-align: center">** Invalid Student Selected</span>
                       <?php
                   }else{
                        $student = $student->row();
                   
                        $att_set =$att_set->row();

                        $att_config=$this->db->query("SELECT * from attendance_config where asid='".$att_set->aid."' ")->result();
                        $att_config_arr = array();
                        $att_ids="";
                        foreach ($att_config as $value) {
                            $att_ids.=$value->acid.",";
                         $att_config_arr[$value->acid]=$value->time;   
                        }
                        $att_ids = substr($att_ids,0,strlen($att_ids)-1);
                        $att=$this->db->query("SELECT d.* , (select count(*)  from attendance a where student='".$student->student_id."' AND a.date_id=d.id  ) as att_status FROM attendance_date d  where  slot IN (".$att_ids.")")->result(); 
                        $att_array=array();
                        $month_att_arr=array();
                        foreach ($att as $value) {
                            $day=  getdate($value->day);
                            $day =$day['month']."-".$day['year'];
                            if(!isset($month_att_arr[$day])){
                                   $month_att_arr[$day]=array("total"=>0,"present"=>0);
                             }
                             $month_att_arr[$day]['total']++;
                             if($value->att_status!=0){
                                $month_att_arr[$day]['present']++; 
                             }

                            $att_array[$day][$value->day][$value->slot]=$value->att_status;
                        }
                        $grph_names ="";
                        foreach($month_att_arr as $key=>$value){
                            $per =($value["present"]/$value["total"])*100;
                            $per =  number_format($per,2);
                            $grph_names.= "['".$key."',".$per."] ,";
                        }
                       $grph_names =  substr($grph_names, 0, strlen($grph_names)-1);
                       ?>
                       <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '<br/><hr/><?php echo $student->name  ?> Attendance Report '
                                            },
                                            subtitle: {
                                                text: ''
                                            },
                                            xAxis: {
                                                type: 'category',
                                                labels: {
                                                    rotation: -45,
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: {
                                                    text: 'Attendance percentage'
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                pointFormat: 'Attendance in % : '
                                            },
                                            series: [{
                                                name: 'Attendance % :',
                                                data: [<?php echo $grph_names ?>
                                                ],
                                                dataLabels: {
                                                    enabled: true,
                                                    rotation: -90,
                                                    color: '#FFFFFF',
                                                    align: 'right',
                                                    format: '{point.y:.1f}', // one decimal
                                                    y: 10, // 10 pixels down from the top
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            }]
                                        });
                                    });
                                   </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <br/><br/><hr/>
                <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto">
                    
                </div>
                <div class="box" >
                    
                        <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-bars"></i>Attendance Brief History</h3>
                                                
                                </div>
                            <div class="box-content nopadding"  style=" min-height: 300px; max-height: 550px;" >
                                <div class="tabs-container" >
                                                <ul class="tabs tabs-inline tabs-left">
                                                    <?php
                                                      $i=1;
                                                      foreach ($month_att_arr as $key=>$value) {
                                                            ?>
                                                            <li <?php 
                                                                if($i==1){
                                                                    ?>class='active'<?php
                                                                }
                                                                ?>>
                                                                    <a href="#<?php echo $i  ?>" data-toggle='tab'>
                                                                    <?php echo $key ?>
                                                                    </a>
                                                                </li>
                                                               <?php
                                                                  $i++;
                                                           
                                                      }
                                                    ?>
                                                </ul>
                                </div>
                                    <div class="tab-content padding tab-content-inline nopadding" style=" max-height: 450px; overflow-y: scroll"  >
                                        <?php
                                        $i=1;
                                        foreach ($month_att_arr as $key=>$value) {
                                               ?>
                                              <div class="tab-pane <?php 
                                                  if($i==1){
                                                      ?>active<?php
                                                  }
                                                  ?>" id="<?php echo $i  ?>"
                                                  >
                                                  <h3 style=" text-align: center"><?php 
                                                    echo "Attendance Report Of ".$key ;
                                                    ?></h3>
                                                  <table class="table table-bordered table-striped" style=" text-align: center ">
                                                      <thead>
                                                          <tr>
                                                              <th style=" text-align: center ">Date</th>
                                                              <?php
                                                                  foreach ($att_config_arr as $tim) {
                                                                      ?>
                                                                    <th style=" text-align: center "><?php   echo substr($tim,0,strlen($tim)-2).":".substr($tim,strlen($tim)-2) ?></th>
                                                                      <?php 
                                                                   }
                                                              ?>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <?php
                                                    foreach ($att_array[$key] as $d=>$var) {
                                                        ?>
                                                          <tr>
                                                              <th style=" text-align: center "><?php echo date("d-m-Y",$d) ?></th>
                                                                  <?php
                                                                  foreach ($att_config_arr as  $c=>$tim) {
                                                                      ?>
                                                                    <td><?php 
                                                                       if(isset($var[$c])){
                                                                           if($var[$c]==0){
                                                                               ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                              }else{
                                                                                  ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                              }
                                                                       }else{
                                                                           echo "--";
                                                                       }  
                                                                            ?></td>
                                                                      <?php 
                                                                   }
                                                              ?>
                                                          </tr>
                                                         <?php
                                                    }
                                                          ?>
                                                      </tbody>
                                                  </table>
                                                  <h4 style=" text-align: center; color : #ff0000  ">Total Slots :<?php echo $value["total"] ?>&nbsp;Present Slots :<?php echo $value["present"] ?>&nbsp;Attendance Perentage :<?php echo number_format( ($value["present"]/$value["total"])*100,2 ) ?></h4>

                                              </div>
                          <?php
                          $i++;
                                        }
                                        ?>
                                    </div>
                            </div>
                        </div>
                   
                </div>
                        
                           
                       <?php   
                    }
                }else{
                    ?><br/><br/>
                <span style=" color: red; text-align: center">** Attendance Settings Not Yet Configured </span>
                    <?php
                }
            }
            
        }
        
        public function ajax_exam_results(){
             $student=  $this->check_student($this->input->post('student')); 
            ?>
            
                    <div class="box">
                              <h3 style=" text-align: center ; color:  #ff9900">Exam Results</h3>   
                              <div  class='form-horizontal form-bordered' style=" border-top: 1px solid #cccccc; border-bottom: 1px solid #cccccc">

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Exams</label>
                                        <div class="col-sm-6">
                                            <?php
                                                $query=  $this->db->query("SELECT ec.id as ecid,e.id as eid,e.exam,e.startdate,e.enddate  FROM `examination_cls` ec  JOIN examinations e ON e.id=ec.examid WHERE ( ec.status=1 AND e.status=1 ) AND sectionid= '".$student->section_id."' ");
                                                if($query->num_rows()>0){
                                                    $query=$query->result();
                                                    ?>               
                                                        <select class="select2-me" id="exam_r" name="exam" onchange=""   style=" width: 100% "  >
                                                            <option value="" >Please select Exam</option>
                                                            <?php
                                                                foreach($query as $val){
                                                                    ?><option value="<?php echo $val->eid.",".$val->ecid   ?>" ><?php echo $val->exam ?>( <?php echo date('d-m-Y',$val->startdate)." - ".date('d-m-Y',$val->enddate) ?> )</option><?php
                                                                }
                                                            ?>
                                                        </select> 
                                                        <span id="rexam_err" style=" color: red">

                                                        </span>

                                                   <?php
                                                }else{
                                                    ?>
                                                   <select   class="select2-me" id="exam_r" name="exam"  style=" width: 100%; display: none "  >

                                                   </select>
                                                   <span id="rexam_err" style=" color: red">
                                                       No Exams Scheduled
                                                    </span>
                                                    <?php                
                                                   }
                                            ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <button onclick="fetch_exam_results();" class="btn btn-primary">Fetch Results</button>
                                            
                                        </div>
                                </div> 

                            </div>
                        
                    
                    <div id="exam_results_holder" class="box"  >
                        
                    </div>
                    </div>
            <?php            
        }
        
        public function fetch_exam_results(){
            $student=  $this->check_student($this->input->post('student')); 
            $section=$student->section_id;
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            ?>
            
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>Results </h3>
                        
                    </div>
                <div class="box-content nopadding" style=" height: 350px; overflow-y: auto"  >
                    <table class="table table-hover table-nomargin" >
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Max Marks</th>
                                <th>Min Marks</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=  $this->db->query("SELECT s.subject,e.maxmarks,e.minmarks,e.examdate,m.marks FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN marks m ON m.exam_id=e.id AND  m.student_id ='".$student->student_id."' WHERE e.examid='".$examid."' AND e.ecid='".$ecid."'    ");
                             if($query->num_rows()>0){
                                 $total=0;
                                 $query=$query->result();
                                 foreach ($query as $value){
                                     ?>
                                    <tr>
                                <td><?php echo $value->subject  ?></td>
                                <td><?php echo date("d-m-Y",$value->examdate)  ?></td>
                                <td><?php echo $value->maxmarks  ?></td>
                                <td><?php echo $value->minmarks  ?></td>
                                <td><?php if( strlen($value->marks)==0)
                                            {
                                              echo "--";
                                            }else{
                                                $total=$total+$value->marks; 
                                               echo $value->marks; 
                                            }
                                    
                                    ?></td>
                            </tr>
                                    <?php
                                 }
                                 ?>
                            <tr>
                                <td colspan="4"><span style=" float: right; font-weight: bold">Total :</span> </td>
                                <td><span style=" float: left; font-weight: bold"><?php echo $total; ?></span></td>
                            </tr>
                                 <?php
                                 
                             }else{
                                 ?>
                                    <tr>
                                        <td colspan="5">No Records Found</td>
                                    </tr>
                                <?php
                             }
                            ?>
                        </tbody>
                    </table>
                </div>
           
            <?php
        }
       
        public function ajax_exam(){
             $student=  $this->check_student($this->input->post('student')); 
            
                $query=  $this->db->query("SELECT ec.id as ecid,e.id as eid,e.exam,e.startdate,e.enddate  FROM `examination_cls` ec  JOIN examinations e ON e.id=ec.examid WHERE ( ec.status=1 AND e.status=1 ) AND sectionid= '".$student->section_id."' ");
                if($query->num_rows()>0){
                    $query=$query->result();
                    ?>               
                        <select class="select2-me" id="exam" name="exam" onchange=""   style=" width: 100% "  >
                            <option value="" >Please select Exam</option>
                            <?php
                                foreach($query as $val){
                                    ?><option value="<?php echo $val->eid.",".$val->ecid   ?>" ><?php echo $val->exam ?>( <?php echo date('d-m-Y',$val->startdate)." - ".date('d-m-Y',$val->enddate) ?> )</option><?php
                                }
                            ?>
                        </select> 
                        <span id="exam_err" style=" color: red">

                        </span>
                        <script>
                            fetch    
                        </script>
                   <?php
                }else{
                    ?>
                   <select   class="select2-me" id="exam" name="exam"  style=" width: 100%; display: none "  >

                   </select>
                   <span id="exam_err" style=" color: red">
                       No Exams Scheduled
                    </span>
                    <?php                
                   }
                       
        }
        
        public function fetch_exam_schedule(){
            $student=  $this->check_student($this->input->post('student')); 
            $section=$student->section_id;
            $exam=explode(",",$this->input->post('exam'));
            $examid=$exam[0];
            $ecid=$exam[1];
            ?>
            <div id="results_holder" class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>Schedule</h3>
                    </div>
                <div class="box-content nopadding"   >
                    <table class="table table-bordered " >
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Timings</th>
                                <th>Time Span</th>
                                <th>Max Marks</th>
                                <th>Min Marks</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=  $this->db->query("SELECT s.subject,e.maxmarks,e.minmarks,e.examdate,e.starttime,e.endtime,e.timespan FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid  WHERE e.examid='".$examid."' AND e.ecid='".$ecid."'    ");
                             if($query->num_rows()>0){
                                 $query=$query->result();
                                 foreach ($query as $value){
                                     ?>
                            <tr>
                                <td><?php echo $value->subject  ?></td>
                                <td><?php echo date('d-m-Y',$value->examdate )  ?></td>
                                <td><?php echo date("H:i",$value->starttime) ." -- ".date("H:i",$value->endtime); ?></td>
                                <td><?php echo $value->timespan ?></td>
                                <td><?php echo $value->maxmarks  ?></td>
                                <td><?php echo $value->minmarks  ?></td>
                               
                            </tr>
                                    <?php
                                 }
                             }else{
                                 ?>
                                    <tr>
                                        <td colspan="5">No Records Found</td>
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
        
         private function check_student($student){
            
            $query = $this->db->query("SELECT s.*,st.name as section_name,c.name as class_name FROM `student` s JOIN section st ON s.section_id=st.sid JOIN class c ON st.class_id=c.id WHERE s.student_id='".$student."' AND parent_id='".$this->session->userdata('parent_id')."' ");

            if ($query->num_rows() > 0) {
                $query = $query->row();
                return $query;
               }else{
               return FALSE;           
            }
        }
        
        
}
