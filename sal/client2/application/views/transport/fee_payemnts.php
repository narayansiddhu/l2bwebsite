<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$route = $this->input->get('route');
///stud_fee_payee_Sucess
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
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Fee Payments</a>
                        </li>
                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('stud_fee_payee_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    Fee Payed Successfully , Invoice No : <?php echo $this->session->userdata('stud_fee_payee_Sucess'); ?>  , <a target="_blank" href="<?php echo base_url() ?>index.php/Transport/Invoice/<?php echo $this->session->userdata('stud_fee_payee_Sucess'); ?>">Click Here for invoice</a>  
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('stud_fee_payee_Sucess');
            }
            ?>
            <div class="box" style=" height: auto;" >
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered box-color" style=" padding-right:  2px;">
                        <div class="box-title">
                                <h3><i class="fa fa-money"></i>Transportation Fee Payments</h3> 
                        </div>
                        <div class="box-content nopadding">
                            <?php
                               $stud="SELECT s.student_id,st.stid,st.fee_amount ,s.student_id,s.name ,s.phone,s.userid FROM stud_transport st JOin student s On st.stud_id= s.student_id  where st.iid='".$this->session->userdata('staff_Org_id')."' ";
                               $stud = $this->db->query($stud);  
                               $stud =$stud->result();
                               //print_r($stud);
                            ?>
                            <div class='form-horizontal form-bordered'   >
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Student</label>
                                        <div class="col-sm-9">
                                            <select onchange="load_balance();"  name="student" id="student" class='select2-me' style="width:100%;" >
                                                <option value="">Select A student</option>
                                                 <?php
                                                  foreach($stud as $val){
                                                       ?>
                                                 <option value="<?php echo $val->student_id.",".$val->fee_amount.",".$val->student_id ?>" >
                                                     <?php echo $val->name ." - ".$val->userid ?>
                                                 </option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>  
                                            <span id='student_err' style=" color: red">

                                            </span>  
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Total Fee</label>
                                        <div class="col-sm-9">
                                            <label class="form-control" id="totall_fee_payee" >Select Student to Load fee</label>
                                            
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Paid</label>
                                        <div class="col-sm-9">
                                            <label class="form-control" id="fee_paid" >Select Student to Load Paid</label>
                                            
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Balance</label>
                                        <div class="col-sm-9">
                                            <label class="form-control" id="fee_balance" >Select Student to Load Balance</label>
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="payee" id="payee" placeholder="Enter Amount To Be Paid" class="form-control" id="fee_balance" />
                                            <span id='payee_err' style=" color: red">

                                            </span>  
                                        </div>
                                </div>
                                <div class="form-actions col-sm-offset-4 col-sm-4">
                                        <input type="button" name="submit" value="Pay Fee" onclick="Add_trans_fee();" class="btn btn-primary btn-block" />
                                        <span id="errors" style=" color: red" ></span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <script>
                function load_balance(){
                    //totall_fee_payee
                    $('#student_err').html(" ");
                    $('#totall_fee_payee').html(" ");
                    student =$('#student').val();
                    if(student.length==0){
                        $('#student_err').html("** please select student");
                    }else{
                        student = student.split(',');
                        $('#totall_fee_payee').html(student[1]);
                        setState('fee_balance','<?php echo base_url() ?>index.php/transport/load_balance','student='+student[0]+'&fee_amount='+student[1]);
 
                    }
                    
                }
                
                function Add_trans_fee(){
                    $('#student_err').html(" ");
                    $('#payee_err').html(" ");
                    student =$('#student').val();
                    payee = $('#payee').val();
                    count=0;
                    if(student.length==0){
                        count++;
                        $('#student_err').html("** please select student");
                    }
                    if(payee.length==0){
                        count++;
                        $('#payee_err').html("** Enter Amount To be Paid");
                    }
                    if(count==0){
                       student = student.split(','); 
                      // alert(student[0]);
                       setState('errors','<?php echo base_url() ?>index.php/transport/pay_fee','student='+student[2]+'&payee='+payee+'&total_paid='+$('#fee_paid').html()+'&fee_amount='+student[1]);
 
                    }
                 
                }
                </script>
                <div class="col-sm-8 nopadding">
                    <div class="box box-bordered box-color" style=" padding-left:  15px;">
                        <div class="box-title">
                                <h3><i class="fa fa-money"></i>Fee Payments History</h3> 
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered datatable" style=" width: 100%; " > 
                                <thead >
                                    <tr>
                                        <th>S.no</th>
                                        <th>Invoice</th>
                                        <th>Student</th>
                                        <th>User Id</th>
                                        <th>Amount</th>
                                        <th>Time Stamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $tfee=" SELECT tf.*,s.name , s.userid FROM `transport_fee` tf JOIN student s  On tf.student= s.student_id   WHERE tf.iid='".$this->session->userdata('staff_Org_id')."' ";
                                    $tfee = $this->db->query($tfee);
                                     $tfee = $tfee->result();$i=1;
                                     foreach( $tfee as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() ?>/index.php/Transport/Invoice/<?php echo $val->invoice_no ?>" ><?php echo $val->invoice_no ?></a></td>
                                        <td><?php echo $val->name ?></td>
                                        <td><?php echo $val->userid ?></td>
                                        <td><?php echo $val->fee ?></td>
                                        <td><?php echo date('d-m-y H:i',$val->timestamp);  ?></td>
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
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>