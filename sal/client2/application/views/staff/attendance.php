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
                            <a href="<?php echo base_url(); ?>index.php">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Staff Attendance</a>
                        </li>
                    </ul>

            </div> 
            
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Add Attendance </h3> 
                </div>
                <div class="box-content nopadding"> 
                  <div class='form-horizontal form-bordered' >
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Day</label>
                        <div class="col-sm-10">
                            <input type="text" onchange="add_attendance1()" id="new_date" placeholder="Place Select date" id="stdob" class="form-control datepick" value="<?php echo $this->form->value('stdob') ?>">
                            <span id="new_date_err" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                          ?>
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="button" id="add" onclick="add_attendance();" name="add" value="Add Attendane" class="btn btn-primary" />
                        <span id="errors" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                       ?>
                            </span> 
                    </div>
                  </div>
                </div>
            </div>
              
            <script>
                function add_attendance(){
                  var d=$('#new_date').val();   
                  if(d.length==0){
                     $('#new_date_err').html('* Please select date'); 
                  }else{
                    
                     $( "#add" ).prop( "disabled", true );
                     $('#errors').show(); 
                     setState('errors','<?php echo base_url() ?>index.php/staff/add_date','date='+d);
                   }
                  }

                  function add_attendance1(){
                  
                     $( "#add" ).prop( "disabled", false );
                     $('#errors').hide(); 
                   
                   }
                  
                
            </script>

    
            

         </div>
    </div>      
</div>
<?php
$this->load->view('structure/footer');
?>
