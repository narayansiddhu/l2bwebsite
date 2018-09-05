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
                <a href="<?php echo base_url(); ?>index.php/library/">Library</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/submit">Return Book</a>
            </li>
        </ul>
    </div>  
    
           <div class="box box-bordered box-color">
                    
                <div class="box-title">
                    <h3>Submit Book</h3>
                </div>
                <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered' > 
                                  <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select style="width: 100%" class="select2-me" id="student" name="student">
                                                <option value="">Select student</option>
                                                <?php
                                                  $query=$this->db->query("SELECT s.userid,s.student_id,s.userid,s.name,c.name as class,se.name as section  FROM student s JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id =se.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."'");
                                                  $query=$query->result();
                                                  foreach ($query as $value) {
                                                    ?>
                                                <option value="<?php echo $value->student_id ?>"><?php echo $value->userid."-".$value->name ."(".$value->class."-".$value->section.")" ?></option>
                                                        <?php  
                                                  }
                                                ?>
                                            </select>
                                            <span id="student_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                             
                                  <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Transaction id</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="transaction" id="transaction" placeholder="enter Transaction id" class="form-control" />
                                            <span id="transaction_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                                  <div  class="form-actions col-sm-offset-2 col-sm-10">
                                      <input type="button"  onclick="fetch();"  name="submit"  value="Fetch Transactions" class="btn btn-primary" />
                                       <span id="errors" style=" color: red">
                                                
                                       </span> 
                                  </div> 
                            </div>
                            
                            
                        </div> 
                    
          </div>
       
  <script>
    function fetch(){
       $('#errors').html('');
        
        student=$('#student').val();
        transaction=$('#transaction').val().trim();
        count=0;
        if(student.length>0){
           count++; 
        }
        if(transaction.length>0){
           count++; 
        }
        if(count==0){
            $('#errors').html('<br/>Please select any of the above');
        }else{
            $('#results').show();
            setState('results_holder','<?php echo base_url() ?>index.php/library/fetch_result','student='+student+'&transid='+transaction);
        }
    } 
  </script>  
   
        
  <div id="results" style="display: none" class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>Search Results</h3>
                        </div>
                        <div   class="box-content nopadding">
                            <table class="table table-hover table-nomargin">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Transaction id</th>
                                        <th>Student name</th>
                                        <th>Admission id </th>
                                        <th>Book </th>
                                        <th>Issued On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="results_holder" >
                                    
                                </tbody>
                            </table>
                        </div>
                   
           </div>
        
    
  </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>