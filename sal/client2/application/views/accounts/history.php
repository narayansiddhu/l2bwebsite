<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/accounts/">Accounts</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">View History</a>
                        </li>
                    </ul>

            </div> 
    
            <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3>Search Accounts</h3>
                            <div class="actions"> 
                                <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/view_all"><i class="fa fa-eye"></i>&nbsp;View All</a>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered'  >
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Student</label>
                                            <div class="col-sm-10">
                                                <select name="student" id="student" class='select2-me' style="width:100%;" >
                                                    <option value="">Select A Student</option>
                                                     <?php
                                                     $credential = array('iid' =>$this->session->userdata('staff_Org_id'));
                                                     $query = $this->db->get_where('student', $credential);
                                                       $query=$query->result();
                                                       foreach($query as $val){
                                                           ?>
                                                     <option value="<?php echo $val->student_id ?>" >
                                                         <?php echo $val->userid."-".$val->name ?>
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
                                            <label for="textfield" class="control-label col-sm-2">From</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="textfield" id="fromdate" placeholder="Please select From date"  class="form-control datepick">
                                                <span id='amount_err' style=" color: red">

                                                </span> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Recipt No</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="amount" id="recipt" placeholder="Enter Recipt Number" class="form-control"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">To</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="textfield" id="todate" placeholder="Please select To date"  class="form-control datepick">
                                                <span id='amount_err' style=" color: red">

                                                </span> 
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                 <input type="button" onclick="fetch_records();"  name="submit"  value="Search Account's" class="btn btn-primary" />
                                 <span id='errors' style=" color: red">

                                 </span>
                            </div>
                            </div>
                        </div>
                    </div>
            
            <div class="box box-bordered box-color" id="results_table">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i> Accounts History  </h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1105px;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Student</th>
                                    <th>Recipt</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $i=1;
                                 $query=$this->db->query("SELECT f.account_id,s.name,s.userid,f.recipt,f.amount,f.time,fc.category FROM fee_accounts f JOIN student s ON f.student_id=s.student_id  JOIN fee_category fc ON fc.cid=f.category  WHERE staff_id='".$this->session->userdata("staff_id")."' ORDER BY f.time DESC LIMIT 0, 30");
                                 $query=$query->result();
                                 foreach($query as $value){
                                     ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->recipt ?></td>
                                    <td><?php echo $value->category ?></td>                                    
                                    <td><?php echo $value->amount ?></td>
                                    <td><?php echo date("d-m-y h:i", $value->time) ?></td>
                                    <td>
                                        <a target="_blank" href="<?php echo base_url();   ?>index.php/accounts/printout/<?php echo $value->account_id ?>"><i class="fa fa-print" aria-hidden="true"></i></a></td>
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
    <script> 
        function fetch_records(){
            $('#errors').html("");
           var student=$('#student').val(); 
           var recipt=$('#recipt').val();
           var fromdate=$('#fromdate').val();
           var todate=$('#todate').val();
           setState('results_table','<?php echo base_url() ?>index.php/accounts/fetch_records','student='+student+'&recipt='+recipt+'&fromdate='+fromdate+'&todate='+todate);
        }
    </script>

<?php

$this->load->view('structure/footer');

?>