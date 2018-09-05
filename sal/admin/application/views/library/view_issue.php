<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box"> <br/>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>index.php/library/">Library</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/library/issue_list">Issue List</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="#">View Transaction Details</a>
            </li>
        </ul>
    </div>  
    <?php  
        if(strlen($this->session->userdata('issues_update'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('issues_update'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
           
            $this->session->unset_userdata('issues_update');
        }
    ?>
   
            <div class="row">
                <div class="col-sm-6">
                    <div class="box box-bordered box-color">
                            <div class="box-title" >
                                <h3 ><i class="fa fa-exchange" aria-hidden="true"></i>Transaction Details</h3>
                            </div>
                            <div class='box-content nopadding' > 
                                
                                <div class="form-horizontal form-bordered ">
                                    
                                
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Trans Id:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->trans_id ?>" />
                                        </div>
                                    </div>
                                    <?php
                                    if($issue->status==2){
                                      ?>
                                       
                                          <div class="form-group">
                                              <label for="textfield"  class="control-label col-sm-2">Return date</label>
                                              <div class="col-sm-10">
                                                  <input type="text" class="form-control" disabled="" value="<?php echo date("d-m-y h:i",$issue->return_date); ?>" />
                                              </div>
                                          </div>

                                     
                                      <?php
                                    }

                                  ?>
                                
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Issued On</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo date("d-m-y H:i",$issue->issued_date) ?>" />
                                        </div>
                                    </div>
                                    <?php
                                    if($issue->status==2){
                                      ?>
                                       
                                     
                                          <div class="form-group">
                                              <label for="textfield"  class="control-label col-sm-2">Fee</label>
                                              <div class="col-sm-10">
                                                  <input type="text" class="form-control" disabled="" value="<?php echo $issue->fine ?>" />
                                              </div>
                                          </div>
                                     
                                      <?php
                                    }

                                  ?>
                                </div> 
                            
                            </div>
                        </div>
                    <div class="box box-bordered box-color">
                            <div class="box-title" >
                                <h3><i class="fa fa-book"></i>&nbsp;&nbsp;Book Details</h3>
                            </div>
                            <div class='box-content nopadding' > 
                                <div class=" form-horizontal form-bordered ">
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Title</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->book ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Author</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->author ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Category</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->category ?>" />
                                        </div>
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Book Id </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->buid ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">price</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" disabled="" value="<?php echo $issue->price ?>" />
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    
                </div>
                <div class="col-sm-6">
                    <div class="box box-bordered box-color">
                            <div class="box-title" >
                                <h3 ><i class="fa fa-user"></i>&nbsp;&nbsp;Student Details</h3>
                            </div>
                            <div class='box-content nopadding' > 
                                <div class="form-horizontal form-bordered ">
                                        <div class="form-group">
                                            <label for="textfield"  class="control-label col-sm-2">Student</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" disabled="" value="<?php echo $issue->studname ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="textfield"  class="control-label col-sm-2">Class - section</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" disabled="" value="<?php echo $issue->class ." - ".$issue->section ?>" />
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="textfield"  class="control-label col-sm-2">Admission no</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" disabled="" value="<?php echo $issue->admissionid ?>" />
                                            </div>
                                        </div>
                                        
                                    
                                </div>
                            </div> 
                        </div> 
                          
                    <?php
                              if($issue->status==1){
                                  ?>
                               <div class="box box-bordered box-color">
                                    <div class="box-title" >
                                        <h3><i class="fa fa-retweet" aria-hidden="true"></i>Return Book</h3>
                                    </div>
                                    <div class='box-content nopadding' >
                                        <div class=' form-horizontal form-bordered' > 
                                            
                                            
                                                <div class="form-group">
                                                    <label for="textfield"  class="control-label col-sm-2">No Of days</label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $datediff=time()-$issue->issued_date;

                                                        ?>

                                                        <input type="text" class="form-control" disabled="" value="<?php echo  ceil($datediff/(60*60*24)); ?>" />
                                                    </div>
                                                </div>
                                               <div class="form-group">
                                                    <label for="textfield"  class="control-label col-sm-2">Fee</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="fine"  value="0" placeholder="Please enter fine if any else enter 0" />
                                                    </div>
                                                </div>
                                            
                                        </div>
                                        <div  style=" text-align: center" class="form-groups col-sm-offset-4 col-sm-4">
                                               <input type="button" id="return_book"  onclick="submit_book();"  name="submit"  value="Return Book" class="btn btn-primary btn-block" />
                                                   <span id="errors" style=" color: red">

                                                   </span> 
                                        </div> 
                                       <br/><br/> 
                                    </div>
                               </div>
                                        
                                 <?php
                              }else{
                                ?>
                                <div class="box box-bordered box-color">
                                    <div class="box-title" >
                                        <h3><i class="fa fa-money" aria-hidden="true"></i>Fee Charged </h3>
                                        <div class="actions">
                                            <button style=" background-color: white; color: blue" onclick="show_fine_form();" class="btn"><i class="fa fa-plus-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Add Fine</button>
                                        </div>
                                    </div>
                                    <div class='box-content nopadding' > 
                                       
                                           
                                                <div  id="fine_form_holder" style="display: none;" class='form-horizontal form-bordered'>
                                                   
                                                       <div class="form-group">
                                                           <label for="textfield"  class="control-label col-sm-2">Fee</label>
                                                           <div class="col-sm-10">
                                                               <input type="text" class="form-control" id="form_fine"  value="" placeholder="Please enter fine " />
                                                           <span id='form_fine_err' style=" color: red">

                                                            </span>
                                                           </div>
                                                       </div>
                                                   
                                                       <div class="form-group">
                                                           <label for="textfield"  class="control-label col-sm-2">Reason</label>
                                                           <div class="col-sm-10">
                                                               <input type="text" class="form-control" id="form_reason"  value="" placeholder="Please enter Reason" />
                                                               <span id='form_reason_err' style=" color: red">

                                                               </span>
                                                           </div>
                                                       </div>
                                                   
                                                    
                                                        <div class="form-group">                 
                                                            <br/>
                                                            <button onclick="Add_fee();" class="btn btn-primary">Add Fine</button>
                                                            <span id='form_errors' style=" color: red"></span>
                                                       </div>
                                                   
                                                </div>
                                                <table  class="table table-hover table-nomargin">
                                                           <thead>
                                                               <tr>
                                                                   <th>S.no</th>
                                                                   <th>Amount Charged</th>
                                                                   <th>description</th>
                                                                   <th>time</th>
                                                                   <th>Actions</th>
                                                               </tr>
                                                           </thead>
                                                           <tbody>
                                                               <?php  
                                                                $q=$this->db->query("SELECT * FROM lib_fines WHERE `issue_id`='".$issue->issue_id."'");
                                                                if($q->num_rows())
                                                                {
                                                                    $q=$q->result();$i=1;
                                                                   foreach($q as $value){
                                                                       
                                                                       ?>
                                                                        <tr>
                                                                            <td><?php echo $i++; ?></td>
                                                                            <td><?php echo $value->fee ?></td>
                                                                            <td><?php echo $value->description ?></td>
                                                                            <td><?php echo date('d-m-y',$value->time); ?></td>
                                                                            <td><a href="<?php echo base_url() ?>/index.php/library/pay/<?php echo $value->fine_id ?>">Pay</a></td>
                                                                        </tr>
                                                                       <?php
                                                                     }
                                                                }else{
                                                                    ?><tr><td colspan="4">No Records Found..</td></tr><?php
                                                                }
                                                                ?>
                                                           </tbody>
                                                       </table>
                                
                                    </div>
                                </div>
                                <?php
                              }
                            ?>
                        
                </div>
            </div>
            
            
            
            
                        
               
                        
                            
                            
          
            
    </div>   
</div>
</div>
<script>
  function submit_book(){
      document.getElementById("return_book").disabled = true;
      fine=$('#fine').val().trim();
      count=0;
      if(fine.length==0){
           count++;
           $('#errors').html('Please Enter Amount');
       }
       if( isNaN(fine) ){
          count++;
           $('#errors').html('Enter Numeric Value');
       }else{
           if(fine % 1 != 0){
            count++;
           $('#errors').html('Decimal Not Allowed');    
           }
       }
       if(count==0){
           setState('errors','<?php echo base_url() ?>index.php/library/on_return','issueid=<?php echo $issue->issue_id ?>&fine='+fine+'&book_id=<?php echo $issue->book_id; ?>');
       }
      
      document.getElementById("return_book").disabled = false;
  }
  
  function Add_fee(){
      $('#form_fine_err').html('');
      $('#form_reason_err').html('');
      fine=$('#form_fine').val().trim();
      reason=$('#form_reason').val().trim();
      count=0;
      if(fine.length==0){
           count++;
           $('#form_fine_err').html('Please Enter Amount');
       }
       if( isNaN(fine) ){
          count++;
           $('#form_fine_err').html('Enter Numeric Value');
       }else{
           if(fine % 1 != 0){
            count++;
           $('#form_fine_err').html('Decimal Not Allowed');    
           }
       } 
       if(reason.length==0){
           count++;
           $('#form_reason_err').html('Please reason');
       }
       
       if(count==0){
          setState('form_errors','<?php echo base_url() ?>index.php/library/add_fine','issueid=<?php echo $issue->issue_id ?>&fine='+fine+'&reason='+reason+'&book_id=<?php echo $issue->book_id; ?>');
       }
      
      
  }
  
  function show_fine_form(){
    $('#fine_form_holder').show();
  }
  
  
</script>
    
<?php
$this->load->view('structure/footer');
?>