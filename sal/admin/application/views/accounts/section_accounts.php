<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>		
<?php
 $trans ="SELECT st.* ,(SELECT sum(fee) FROM `transport_fee` WHERE student =st.stud_id ) as paid , t.fee FROM `stud_transport` st JOIN trip_route tr ON tr.trid= st.trip_route_id JOIN trips t ON tr.trip = t.trip_id JOIN student s ON st.stud_id=s.student_id where s.section_id ='".$section->sid."'   ";
$trans = $this->db->query($trans)->result();
$trans_port_fee_array= array();
$trans_total_fee =$trans_total_con=$trans_total_balance=$trans_total_paid=0;
foreach ($trans as $value) {
      $paid=$concession=$total=0;
      $total = $value->fee;
      $concession = $value->fee -$value->fee_amount;
      if(strlen(trim($value->paid))!=0){
          $paid = $value->paid;
      }
      $trans_total_fee+=$total;
      $trans_total_con+=$concession;
      $trans_total_paid+=$paid;
      $trans_total_balance+=($value->fee_amount-$paid);
      $trans_port_fee_array[$value->stud_id]= array('total'=>$total,"concession"=>$concession,"paid"=>$paid,"balance"=>$value->fee_amount-$paid);
}


 $hostel ="SELECT st.* ,(SELECT sum(fee) FROM `hostel_feepayment` WHERE student =st.student_id ) as paid , (SELECT fee from hostel_fee where class_id = '".$section->class_id."' AND block_id = r.block_id ) as total FROM `hostel_students` st JOIN hostel_rooms r ON st.room_id=r.room_id  JOIN student s ON st.student_id=s.student_id where s.section_id ='".$section->sid."'   ";
$hostel = $this->db->query($hostel)->result();
$hostel_port_fee_array= array();
$hostel_total_fee =$hostel_total_con=$hostel_total_balance=$hostel_total_paid=0;
foreach ($hostel as $value) {
      $paid=$concession=$total=0;
      $total = $value->total;
      $concession = $value->total -$value->fee;
      if(strlen(trim($value->paid))!=0){
          $paid = $value->paid;
      }
      $hostel_total_fee+=$total;
      $hostel_total_con+=$concession;
      $hostel_total_paid+=$paid;
      $hostel_total_balance+=($value->fee-$paid);
      $hostel_port_fee_array[$value->student_id]= array('total'=>$total,"concession"=>$concession,"paid"=>$paid,"balance"=>$value->fee-$paid);
}

$this->load->view('structure/nav');
$this->load->view('structure/body');
$con=$this->db->query("SELECT sum(amount)as amount FROM `fee_concession` c JOIN  student s on s.student_id=c.std_id WHERE   s.section_id='".$section->sid."' ");
$con = $con->row();
$con = $con->amount;
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
                            <a href="">Accounts Of <?php echo $section->class." , ".$section->section ?></a>
                        </li>
                    </ul>
            </div>
            <h3 style=" text-align: center; color:  #ff1340;  ">Accounts Of  <?php echo ucfirst(strtolower($section->class." , ".$section->section)); ?></h3>
            <hr/>
            <div class="row" style="max-height: 300px; ">
                <div class="col-sm-4" style=" clear: both;">
                    <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3><i class="fa fa-inr"></i>View Fee structure</h3> 
                        </div>
                        <div class="box-content nopadding" style=" max-height: 180px; overflow: scroll"> 
                            <table class="table table-bordered table-hover table-nomargin">
                                <thead>
                                    <tr>
                                        <th>
                                            S.no
                                        </th>
                                        <th>
                                           Fee Category 
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cat ="";
                                    $fees=0;
                                    $i=1;
                                  
                                         $q= $this->db->query("SELECT f.fid,f.category as cat,fc.category , f.fee FROM `fee_class` f JOIN fee_category fc on f.category = fc.cid where  f.cls_id= '".$section->class_id."' ");
                                         $fee_cat_amount= array();
                                         $q =$q->result();
                                         foreach($q as $val){
                                             $fee_cat_amount[$val->fid] =$val->fee;
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
                                    ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
                <?php
                $overall = 0;
                $concession="SELECT * FROM fee_concession fc JOIN student s ON fc.std_id=s.student_id WHERE s.iid= '".$this->session->userdata("staff_Org_id")."' AND  s.section_id = '".$section->sid."'";
                $concession=$this->db->query($concession)->result();
                $concession_array= array();
                foreach ($concession as $value) {
                   $concession_array[$value->std_id][$value->cat_id]=$value->amount; 
                }
                $stud=$this->db->query("SELECT s.* , (SELECT sum(amount) FROM fee_concession fc where fc.std_id=s.student_id ) as concession FROM `student` s    WHERE s.class_id ='".$section->class_id."' AND s.section_id ='".$section->sid."' AND s.iid = '".$this->session->userdata("staff_Org_id")."' ");
                $stud = $stud->result();
                $stud_deatils=array();
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
                $g_total=($section->students * $fees);
                $gbal=  ($section->students * $fees)-$overall;
                ?>
                <script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Fee Summary (<?php echo $g_total  ?>)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Paid (<?php echo $overall ?>)', <?php echo ($overall/$g_total)*100 ?>],['concession (<?php echo $con ?>) ', <?php echo ($con/$g_total)*100 ?>],
                {
                    name: 'Balance (<?php echo ($gbal-$con) ?>)',
                    y: <?php echo (($gbal-$con)/$g_total)*100 ?>,
                    sliced: true,
                    selected: true
                }
            ]
        }]
    });
});
		</script>
                <script src="<?php echo assets_path  ?>graphs/code//highcharts.js"></script>
                <script src="<?php echo assets_path  ?>graphs/code//highcharts-3d.js"></script>
                <script src="<?php echo assets_path  ?>graphs/code//exporting.js"></script>
                    <div class="col-sm-4 " style="  margin-top: 20px;">
                <div class="w3-content w3-section" style="max-width:500px">
                <div class="mySlides" id="container"  style="width:100% ; min-width : 350px; height: 250px; ">
                 <h4  style=" color: red; text-align: center">Please Add Student To View Graphs </h4>
                </div>
                
                                  
                </div>
                <script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 6000); // Change image every 2 seconds
}
</script>

            </div>
                
                <div class="col-sm-4 ">
                    <br/>
                    <table class="table ">
                        
                            <tr >
                                <th style="background-color: #ff9933; color: white">Individual Fee</th>
                                <th style="background-color: #ff9933; color: white"><?php echo $fees ?></th>
                            </tr> 
                            <tr >
                                <th style="background-color: #ff00cc; color: white">Overall Class Fee</th>
                                <th style="background-color: #ff00cc; color: white"><?php echo ($section->students * $fees);  ?></th>
                            </tr>
                            <tr>
                                <th style="background-color: #009999; color: white">Total Collected</th>
                                <th id="gross_coll" style="background-color: #009999; color: white"><?php echo $overall;  ?></th>
                            </tr>
                            <tr>
                                <th style="background-color: #99cc00 ; color: white">Concessions</th>
                                <th id="gross_concession" style="background-color: #99cc00; color: white"><?php echo $overall;  ?></th>
                            </tr>
                            <tr>
                                <th style="background-color: #006699; color: white">Balance</th>
                                 <th id="gross_bal" style="background-color: #006699; color: white"><?php echo $gbal;  ?></th>
                            </tr>
                    </table>
                </div>
            </div>
            <div class="box ">
                <div class="box-title" style=" border: 1px solid #cccccc">
                    <h3 style=" color:#012B72 "><i class="fa fa-inr"></i>&nbsp;Accounts Information</h3>
                    <ul class="tabs">
                            <li class="active">
                                    <a href="#t7" data-toggle="tab">Fee Payments</a>
                            </li>
                            <li>
                                    <a href="#t8" data-toggle="tab">Transport Fee</a>
                            </li>
                            <li>
                                    <a href="#t9" data-toggle="tab">Hostel Fee</a>
                            </li>
                    </ul>
            </div>
                <div class="box-content nopadding" style=" border: 1px solid #cccccc">
                    <div class="tab-content nopadding">
                            <div class="tab-pane nopadding active" id="t7">
                                <div class="box ">
                                    <div class="box-content nopadding">
                                            <ul class="tabs tabs-inline tabs-top">
                                                    <li class='active'>
                                                            <a href="#first11" data-toggle='tab'>Paid Sheet</a>
                                                    </li>
                                                    <li>
                                                            <a href="#second22" data-toggle='tab'>Balance Sheet</a>
                                                    </li>

                                            </ul>
                                            <div class="tab-content nopadding tab-content-inline tab-content-bottom">
                                                    <div class="tab-pane nopadding active" id="first11">
                                                        <a  style="color: white ; background-color : #012B72; float: right" class="btn btn-primary" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_report/<?php echo $section->class_id."/".$section->sid ?>">
                                                            <i class="fa fa-print" aria-hidden="true"></i> &nbsp;Print
                                                        </a>
                                                        <h4 style=" color:#012B72; text-align: center ">Paid List</h4><br/>
                                                        <table class="table datatable table-bordered table-hover table-nomargin">
                                                            <thead >
                                                                <tr >
                                                                    <th>Roll No</th>
                                                                    <th>Name</th>
                                                                    <?php
                                                                       foreach($q as $val){
                                                                           ?>
                                                                    <th><?php echo ucfirst( strtolower( $val->category)); ?></th>
                                                                         <?php
                                                                       }
                                                                    ?>
                                                                <th>Concession</th>
                                                                <th>Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody >
                                                                <?php
                                                                $total_con=0;
                                                                    foreach($stud as $s){
                             $stud_deatils[$s->student_id]= array("name"=>$s->name,"userid"=>$s->userid,"admission_no"=>$s->admission_no);                                           
                                                                        $indiv_paid=0;
                                                                     ?> 
                                                                <tr>
                                                                    <td><a rel="tooltip" title="" data-original-title="Fee Payment Print out "  target="_blank" href="<?php echo base_url() ?>index.php/accounts/print_student_report/<?php echo $s->student_id ?>"><?php echo $s->roll ?></a></td>
                                                                    <td><a rel="tooltip" title="" data-original-title="View Fee Payments " href="<?php echo base_url() ?>index.php/accounts/student_fee_details/<?php echo $s->student_id ?>"><?php echo $s->name ?></a></td>
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


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <div class="tab-pane nopadding " id="second22">
                                                    <a  style="color: white ; background-color : #012B72; float: right" class="btn btn-primary" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_report/<?php echo $section->class_id."/".$section->sid ?>/balance">
                                                        <i class="fa fa-print" aria-hidden="true"></i> &nbsp;Print Balance Sheet
                                                    </a>
                                                    <h4 style=" color:#012B72; text-align: center ">Balance List</h4><br/>
                                                    <br/>
                                                    <table class="table datatable table-bordered table-hover table-nomargin" style=" width: 100%">
                                                            <thead >
                                                                <tr >
                                                                    <th>Roll No</th>
                                                                    <th>Name</th>
                                                                    <?php
                                                                       foreach($q as $val){
                                                                           ?>
                                                                    <th><?php echo ucfirst( strtolower( $val->category)); ?></th>
                                                                         <?php
                                                                       }
                                                                    ?>
                                                                 
                                                                </tr>
                                                            </thead>
                                                            <tbody >
                                                                <?php
                                                                $total_con=0;
                                                                    foreach($stud as $s){
                                                                        $indiv_paid=0;
                                                                     ?> 
                                                                <tr>
                                                                    <td><a rel="tooltip" title="" data-original-title="Fee Payment Print out "  target="_blank" href="<?php echo base_url() ?>index.php/accounts/print_student_report/<?php echo $s->student_id ?>"><?php echo $s->roll ?></a></td>
                                                                    <td><a rel="tooltip" title="" data-original-title="View Fee Payments " href="<?php echo base_url() ?>index.php/accounts/student_fee_details/<?php echo $s->student_id ?>"><?php echo $s->name ?></a></td>
                                                                     <?php
                                                                     foreach($cat as $c){
                                                                         ?>
                                                                    <td>
                                                                    <?php 

                                                                       if(isset($accounts[$s->student_id][$c]) ){
                                                                           $con_std=0;
                                                                           if(isset($concession_array[$s->student_id][$c])){
                                                                             $con_std=  $concession_array[$s->student_id][$c];
                                                                           }
                                                                           echo $fee_cat_amount[$c]-$accounts[$s->student_id][$c]-$con_std;
                                                                       }else{
                                                                           echo $fee_cat_amount[$c];
                                                                       }
                                                                    ?>
                                                                    </td>
                                                                       <?php
                                                                     }
                                                                     ?>
                                                                    
                                                                    
                                                                </tr>
                                                                     <?php
                                                                    }
                                                                 ?>  
                                                                
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                    </div>
                                </div>                            
                            </div>
                            <div class="tab-pane" id="t8">
                                <div class="box">
                                    <div class="box-content nopadding">
                                        <a  style="color: white ; background-color : #012B72; float: right" class="btn btn-primary" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/transport_report/<?php echo $section->class_id."/".$section->sid ?>">
                                            <i class="fa fa-print" aria-hidden="true"></i> &nbsp;Print
                                        </a>
                                        <h4 style=" color:#012B72; text-align: center ">Transport Department Fee List</h4><br/>
                                        <table class="table  table-bordered table-hover table-nomargin" style=" width: 100%" >
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Student</th>
                                            <th>Total</th>
                                            <th>Concession</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                        <?php
                                        $i=1;
                                            foreach ($trans_port_fee_array as $key => $value) {
                                               ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><a rel="tooltip" title="" data-original-title="View Fee Payments " href="<?php echo base_url() ?>index.php/accounts/student_fee_details/<?php echo $key ?>" target="_blank"><?php echo $stud_deatils[$key]['name'] ?></a></td>
                                            <td><?php echo $value['total'] ?></td>
                                            <td><?php echo $value['concession'] ?></td>
                                            <td><?php echo $value['paid'] ?></td>
                                            <td><?php echo $value['balance'] ?></td>
                                        </tr>
                                               <?php                                                              
                                            }
                                        ?>
                                    </thead>
                                </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="t9">
                                <div class="box">
                                    <div class="box-content nopadding">
                                        <a  style="color: white ; background-color : #012B72; float: right" class="btn btn-primary" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/hostel_report/<?php echo $section->class_id."/".$section->sid ?>">
                                            <i class="fa fa-print" aria-hidden="true"></i> &nbsp;Print
                                        </a>
                                        <h4 style=" color:#012B72; text-align: center ">Hostel Department Fee List</h4><br/>
                                        
                                        <table class="table  table-bordered table-hover table-nomargin" style=" width: 100%" >
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Student</th>
                                            <th>Total</th>
                                            <th>Concession</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                        </tr>
                                        <?php
                                        $i=1;
                                            foreach ($hostel_port_fee_array as $key => $value) {
                                               ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><a rel="tooltip" title="" data-original-title="View Fee Payments " href="<?php echo base_url() ?>index.php/accounts/student_fee_details/<?php echo $key ?>" target="_blank"><?php echo $stud_deatils[$key]['name'] ?></a></td>
                                            <td><?php echo $value['total'] ?></td>
                                            <td><?php echo $value['concession'] ?></td>
                                            <td><?php echo $value['paid'] ?></td>
                                            <td><?php echo $value['balance'] ?></td>
                                        </tr>
                                               <?php                                                              
                                            }
                                        ?>
                                    </thead>
                                </table>
                                    </div>
                                </div>
                            </div>
                    </div>
            </div>
                
              </div>                        
      </div>
    </div> 
</div>

<script>
$('#gross_concession').html("<?php echo $total_con ?>");
$('#gross_bal').html("<?php echo $gbal-$total_con ?>");
</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<?php
$this->load->view('structure/footer');
?>
