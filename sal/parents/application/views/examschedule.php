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
                    <a href="">Exam Schedule</a>  
                </li>
            </ul>
        
        
            
                <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-calendar" aria-hidden="true"></i>View Exam Schedule</h3>
                    </div>
                    <div class="box-content nopadding">
                        <div class='form-horizontal form-bordered' >
                                    <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select class="select2-me" id="student" name="student" onclick="fetch_exams();"   style=" width: 100% "  >
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
                                        <label for="textfield" class="control-label col-sm-2">Exam </label>
                                        <div class="col-sm-10" id='exam_holder' >
                                            <select class="select2-me" disabled="" id="exam" name="exam"  style=" width: 100% "  >
                                                <option value="" >Please Select Exam</option>
                                                
                                               </select> 
                                            <span id="exam_err" style=" color: red">

                                            </span>  
                                        </div>
                                    </div>
                                    <div class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="button" disabled=""  id="fetch"  onclick="fetch_timetable();" value="Fetch Exam Schedule" class="btn btn-primary" />

                                    </div>
                                </div>

                    </div>

                </div>
            
             <script>
                 function fetch_exams(){
                        $( "#student" ).prop( "disabled", true ); 
                        $( "#exam" ).prop( "disabled", true ); 
                        $( "#fetch" ).prop( "disabled", true ); 

                        $('#section_err').html("");
                        $('#exam_err').html("");
                     
                     
                     student=$('#student').val();
                     $('#exam_holder').html("");
                     
                     if(student.length==0){
                         $('#section_err').html("Please select Student");
                     }else{
                      setState('exam_holder','<?php echo base_url() ?>index.php/Students/ajax_exam','student='+student);
                     }  
                     $( "#student" ).prop( "disabled", false ); 
                     $( "#exam" ).prop( "disabled", false ); 
                     $( "#fetch" ).prop( "disabled", false );                     

                 }
                 
                 function fetch_timetable(){
                     $( "#student" ).prop( "disabled", true ); 
                     $( "#exam" ).prop( "disabled", true ); 
                     $( "#fetch" ).prop( "disabled", true );  
                     count=0;
                     $('#section_err').html("");
                     $('#exam_err').html("");
                     student=$('#student').val();
                     exam=$('#exam').val();
                     $('#section_err').html("");
                     if(student.length==0){
                         count++;
                         $('#section_err').html("Please select Student");
                     }
                     if(exam.length==0){
                         count++;
                         $('#exam_err').html("Please select Exam");
                     }
                     if(count==0){
                         setState('result_holder','<?php echo base_url() ?>index.php/Students/fetch_exam_schedule','student='+student+'&exam='+exam);
                     }
                     
                     $( "#student" ).prop( "disabled", false ); 
                     $( "#exam" ).prop( "disabled", false ); 
                     $( "#fetch" ).prop( "disabled", false ); 
                     
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
