<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
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
                    <a href="<?php echo base_url(); ?>index.php/accounts/">Accounts</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Search Accounts</a>
                </li>
            </ul>
    </div>
    
               
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3>Search Accounts </h3>
                            <div class="actions"> 
                                <a   class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/view_all"><i class="fa fa-eye"></i>&nbsp;View All</a>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered'  >
                                <div class="box">
                                    <div class="col-sm-6 nopadding ">
                                        <div class="form-group" style=" height: 55px;" >
                                            <label for="textfield" class="control-label col-sm-2">Student</label>
                                            <div class="col-sm-10" style=" background-color: #ffffff">
                                                <select name="student" id="student" class='select2-me' style="width:100%; " >
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
                                    <div class="col-sm-6 nopadding">
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
                                
                                
                            <div  class="form-actions col-sm-offset-4 col-sm-4">
                                 <input type="button" onclick="fetch_records();"  name="submit"  value="Search Account's" class="btn btn-primary btn-block" />
                                 <span id='errors' style=" color: red">

                                 </span>
                            </div>
                            </div>
                        </div>
                    </div>
    <br/>
       
  <div class="box box-bordered box-color" id="results_table">
                            
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
     </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>