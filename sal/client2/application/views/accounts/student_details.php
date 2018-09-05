<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$cls_fee = $this->db->query("SELECT f.fid,f.fee,c.cid,c.category ,(select sum(amount)  from  fee_accounts a where  f.category = a.category AND a.student_id= '".$student->student_id."' ) as paid ,(select sum(amount)  from  fee_concession fc where  f.category = fc.cat_id AND fc.std_id='".$student->student_id."' ) as concession FROM `fee_class` f JOIN fee_category c ON f.category=c.cid WHERE f.cls_id='".$student->cls_id."' ");
$cls_fee = $cls_fee->result();
$c_fee_arr= array();
$cat =""; $pays=0;$concs=0;$bals=0;$total=0;
$pay ="";$coner="";$bal="";$totals="";
       $query=  $this->db->query("SELECT s.student_id,s.phone,s.email,s.photo,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.student_id = '".$student->student_id."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");         $student=$query->row();
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

           $check="SELECT `transport`,`hostel` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->transport!=0){
               $trans ="SELECT st.* ,(SELECT sum(fee) FROM `transport_fee` WHERE student =st.stud_id ) as paid , t.fee FROM `stud_transport` st JOIN trip_route tr ON tr.trid= st.trip_route_id JOIN trips t ON tr.trip = t.trip_id where st.stud_id ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $cat.="'Transport Dept',";
                    $trans = $trans->row();
                    $paid=0;$concession=0;
                    if($trans->paid!=NULL){
                        $paid=$trans->paid;
                    }
                    $concession=$trans->fee - $trans->fee_amount;
                    $balance = $trans->fee_amount-$trans->paid;
                   $pay.="".$paid.",";
                   $coner.="".$concession.",";
                   $bal.="".$balance.",";
                    $totals.=  "".$trans->fee.",";
                    $pays+=$paid;
                    $concs+=$concession;
                    $bals+=$balance;
                    $total+=$trans->fee;
                    
                }
                
           }
           if($check->hostel!=0){
               $trans ="SELECT st.* ,(SELECT sum(fee) FROM `hostel_feepayment` WHERE student =st.student_id ) as paid , (SELECT fee from hostel_fee where class_id = '".$student->cls_id."' AND block_id = r.block_id ) as total FROM `hostel_students` st JOIN hostel_rooms r ON st.room_id=r.room_id  where st.student_id  ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $trans = $trans->row();
                    $cat.="'Hostel Dept',";
                   // $trans = $trans->row();
                   // print_r($trans);
                    $paid=0;$concession=0;
                    if($trans->paid!=NULL){
                        $paid=$trans->paid;
                    }
                    $concession=$trans->total - $trans->fee;
                    $balance = $trans->fee-$trans->paid;
                   $pay.="".$paid.",";
                   $coner.="".$concession.",";
                   $bal.="".$balance.",";
                    $totals.=  "".$trans->total.",";
                    $pays+=$paid;
                    $concs+=$concession;
                    $bals+=$balance;
                    $total+=$trans->total;
                 //   print_r($trans);
                    ?>
                      <tr>
                            <td>Hostel Dept</td> 
                            <td><?php echo $trans->total ?></td>
                            <td><?php echo $trans->paid ?></td>
                            <td><?php echo $trans->total - $trans->fee ?></td>
                            <td><?php echo $trans->fee-$trans->paid ?></td>
                        </tr>
                   <?php
                }
                
           }
 


$cat = substr($cat, 0, strlen($cat)-1);
$pay = substr($pay, 0, strlen($pay)-1);
$coner = substr($coner, 0, strlen($coner)-1);
$bal = substr($bal, 0, strlen($bal)-1);
$totals = substr($totals, 0, strlen($totals)-1);
?>
<script type="text/javascript" src="<?php echo assets_path ?>highcharts/js/jquery.min.js"></script>		
<script type="text/javascript">
$(function () {
Highcharts.chart('container', {
title: {
text: 'Fee Payment Summary Of <?php echo $student->name ?>'
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
name: 'Concession (<?php echo $concs ?>)',
y: <?php echo ($concs) ?>,
color: Highcharts.getOptions().colors[2] // John's color
}, {
name: 'Balance <?php echo $bals  ?>',
y: <?php echo ($bals) ?> ,
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
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/accounts/view">Accounts</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/accounts/view/<?php echo $student->cls_id  ?>/<?php echo $student->sid  ?>"><?php echo $student->clsname."-".$student->section ?></a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Student Accounts</a>
                        </li>
                    </ul>
            </div> 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div id="container" style="min-width: 310px; height: 400px;  margin-top: 20px;">
    
</div>
<div class="box">
    <div class="col-sm-4 nopadding">
        <?php
        $query=  $this->db->query("SELECT s.student_id,s.phone,s.email,s.photo,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.student_id = '".$student->student_id."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");         $student=$query->row();
        ?>
        <div class="box box-bordered">
                         <div class="box-title">
                             <h3 style=""><i class="fa fa-child"></i>Student Details</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div style=" text-align: center"><br/>
           <?php
              if(strlen($student->photo)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$student->photo)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $student->photo  ?>" alt="..." style=" width: 100px;; height: 100px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
                 }
              }
              ?>
           </div><hr/>
           <h3 style=" text-align: center; color:  #318EEE"><?php echo $student->name ?></h3>
           <table class=" table table-bordered nopadding" style=" width:100%">
               <tr>
                   <td><i class="fa fa-sitemap" aria-hidden="true"></i>&nbsp;<?php echo $student->clsname ." - " .$student->section ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;<?php echo $student->userid ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $student->phone ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo $student->email ?></td>
               </tr>
           </table>
                        </div>
                    </div>
    </div>
    <div class="col-sm-8 ">
        
        <div class="box box-bordered box-color ">
        <div class="box-title">
            <h3>Fee Payments  Summary</h3>
            <div class="actions">
                <a target="_blank" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/print_student_report/<?php echo $student->student_id ?>"><i class="fa fa-print"></i>&nbsp;Print</a>
            </div>
        </div>
        <div  class="box-content nopadding">
                <table class="table table-hover table-nomargin  table-bordered" style="width: 100%; overflow: scroll">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Fee</th>
                            <th>Paid</th>
                            <th>Concession</th>
                             <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        <?php
            if($check->transport!=0){
               $trans ="SELECT st.* ,(SELECT sum(fee) FROM `transport_fee` WHERE student =st.stud_id ) as paid , t.fee FROM `stud_transport` st JOIN trip_route tr ON tr.trid= st.trip_route_id JOIN trips t ON tr.trip = t.trip_id where st.stud_id ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $trans = $trans->row();
                    ?>
                        <tr>
                            <td>Transport Dept</td> 
                            <td><?php echo $trans->fee ?></td>
                            <td><?php echo $trans->paid ?></td>
                            <td><?php echo $trans->fee - $trans->fee_amount ?></td>
                            <td><?php echo $trans->fee_amount-$trans->paid ?></td>
                        </tr>
                    <?php
                }
                
           }
           if($check->hostel!=0){
               $trans ="SELECT st.* ,(SELECT sum(fee) FROM `hostel_feepayment` WHERE student =st.student_id ) as paid , (SELECT fee from hostel_fee where class_id = '".$student->cls_id."' AND block_id = r.block_id ) as total FROM `hostel_students` st JOIN hostel_rooms r ON st.room_id=r.room_id  where st.student_id  ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $trans = $trans->row();
                 //   print_r($trans);
                    ?>
                      <tr>
                            <td>Hostel Dept</td> 
                            <td><?php echo $trans->total ?></td>
                            <td><?php echo $trans->paid ?></td>
                            <td><?php echo $trans->total - $trans->fee ?></td>
                            <td><?php echo $trans->fee-$trans->paid ?></td>
                        </tr>
                   <?php
                }
                
           }
 
           
           ?>
                    </tbody>
                </table>
        </div>
    </div>
    </div>
</div>
<br style=" clear: both;"/><hr/>


<div class="box" style=" clear: both;">
    <div class="box box-bordered box-color">
        <div class="box-title">
                <h3><i class="fa fa-inr"></i>Fee Payments</h3>
        </div>
        <div class="box-content nopadding">
                <ul class="tabs tabs-inline tabs-top">
                        <li class='active'>
                                <a href="#first11" data-toggle='tab'>
                                    <i class="fa fa-inr"></i>&nbsp;Fees</a>
                        </li>
                        <li>
                                <a href="#second22" data-toggle='tab'>
                                        <i class="fa fa-bus"></i>&nbsp;Transport</a>
                        </li>
                        <li>
                                <a href="#thirds3322" data-toggle='tab'>
                                        <i class="fa fa-building"></i>&nbsp;Hostel</a>
                        </li>
                </ul><hr/>
                <div class="tab-content nopadding tab-content-inline tab-content-bottom">
                        <div class="tab-pane active" id="first11">
                             <table class="table datatable table-hover  table-nomargin  table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Paid On</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <td><a  target="_blank" href="<?php echo base_url(); ?>index.php/accounts/printout/<?php echo $value->recipt  ?>"><?php echo $value->recipt ?></a></td>
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

                                </tbody>                           
                            </table>
                        </div>
                        <div class="tab-pane" id="second22">
                                <?php  
                                if($check->transport!=0){
                                     $tfee=" SELECT * FROM `transport_fee` tf    WHERE tf.iid='".$this->session->userdata('staff_Org_id')."' AND tf.student ='".$student->student_id."' ";
                                    $tfee = $this->db->query($tfee);
                                     
                                     ?>
                                     <table class="table table-bordered datatable" style=" width: 100%; " > 
                                <thead >
                                    <tr>
                                        <th>S.no</th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Time Stamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $i=1;
                                     if($tfee->num_rows()>0){
                                    $tfee = $tfee->result();$i=1;
                                     foreach( $tfee as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() ?>/index.php/Transport/Invoice/<?php echo $val->invoice_no ?>" ><?php echo $val->invoice_no ?></a></td>
                                        <td><?php echo $val->fee ?></td>
                                        <td><?php echo date('d-m-y H:i',$val->timestamp);  ?></td>
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
                                </tbody>
                            </table>    
                                     <?php
                                     
                                }else{
                                    ?><h4>Transportation Module Not included In Your Package </h4><?php
                                }
                                ?>
                        </div>
                        <div class="tab-pane" id="thirds3322">
                        <?php  
                                if($check->hostel!=0){
                                     ?>
                            <table class="table table-bordered datatable" style=" width: 100%; " > 
                                <thead >
                                    <tr>
                                        <th>S.no</th>
                                        <th>Invoice No</th>
                                        <th>Amount</th>
                                        <th>Time </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $tfee=" SELECT tf.* FROM `hostel_feepayment` tf   WHERE tf.iid='".$this->session->userdata('staff_Org_id')."' AND tf.student = '".$student->student_id."' ";
                                    $tfee = $this->db->query($tfee);
                                     if($tfee->num_rows()>0){
                                         $tfee = $tfee->result();$i=1;
                                     
                                         foreach( $tfee as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() ?>/index.php/Hostel/Invoice/<?php echo $val->invoice_no ?>" ><?php echo $val->invoice_no ?></a></td>
                                        <td><?php echo $val->fee ?></td>
                                        <td><?php echo date('d-m-y H:i',$val->timestamp);  ?></td>
                                    </tr>
                                        <?php
                                     }
                                     }else{
                                         ?>
                                    <tr>
                                        <td colspan="4" style=" text-align: center; color:  red">** No Records Found...</td>
                                    </tr>
                                             <?php
                                     }
                                     
                                     ?>
                                </tbody>
                            </table>
                                     <?php
                                }else{
                                    ?><h4>Hostel Module Not included In Your Package </h4><?php
                                }
                                ?>        
                        </div>
                </div>
        </div>
</div>

</div>
    
                            
     </div>
    </div>
</div>
    

<?php

$this->load->view('structure/footer');

?>