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
            
            <div class="box ">
                   
                  <div class="col-sm-1">
                      &nbsp;
                  </div>
                  
                    <div class="col-sm-10">
            
           <ul class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() ?>">Home</a>
                </li>
                <li class="active">
                    <a href="">Assignments</a>  
                </li>
            </ul>
               
                        
             <div class="box box-color box-bordered nopadding">
                            <div class="box-title">
                                <h3><i class="fa fa-files-o" aria-hidden="true"></i>View Assignments</h3>
                            </div>
                            <div class="box-content nopadding">
                                <div class='form-horizontal form-bordered' >
                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Student</label>
                                                <div class="col-sm-10">
                                                    <select class="select2-me" id="student" name="student"  style=" width: 100% "  >
                                                        <option value="" >Please Select Student</option>
                                                        <?php
                                                          $arr=$this->session->userdata('students_arr');
                                                          foreach ($arr as $value) {
                                                            ?>
                                                        <option value="<?php echo $value->student_id ?>" ><?php echo $value->name; ?></option>
                                                            <?php
                                                          }
                                                        ?>
                                                       </select> 
                                                    <span id="section_err" style=" color: red">

                                                    </span>  
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Submission Date</label>
                                                    <div class="col-sm-10">
                                                        <input type="text"  name="subdate" id="subdate" class="form-control  datepick"/>
                                                        <span id="subdate_err" style=" color: red">

                                                    </span> 
                                                    </div>
                                                    
                                            </div> 
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                <input type="button"  id="fetch"  onclick="fetch_timetable();" value="Fetch Assignments " class="btn btn-primary" />

                                            </div>
                                        </div>

                            </div>

                        </div>
                    
                
             <script>
                 function fetch_timetable(){
                     student=$('#student').val();
                     subdate=$('#subdate').val(); 
                     $('#subdate_err').html("");
                     $('#section_err').html("");
                     if(student.length==0){
                         $('#section_err').html("Please select Student");
                     }else{
                         if(subdate.length==0){
                              setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student='+student+'&hide=1');
                         }else{
                              subdate = subdate.split("/");
                             if(subdate.length!=3){
                                $('#subdate_err').html("Please Select date");
                            }
                            else{
                               
                                subdate=subdate[0] + "-" + subdate[1]+ "-" + subdate[2];
                                setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student='+student+'&date='+subdate+'&hide=1');
                            }
                         }
                       
                     }
                 }
             </script>
             <div id="result_holder" class="box box-color box-bordered nopadding"  >
         
                 </div>
                    </div>
                       
                    <div class="col-sm-1">
                      &nbsp;
                  </div>
            </div>
                
           </div>
    </div>   
</div>

<?php
$this->load->view('structure/footer');
?>
