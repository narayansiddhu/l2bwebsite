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
                    <a href="">Course </a>  
                </li>
            </ul>
               
                        
             <div class="box box-color box-bordered nopadding">
                            <div class="box-title">
                                <h3><i class="fa fa-files-o" aria-hidden="true"></i>View Course </h3>
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
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                <input type="button"  id="fetch"  onclick="fetch_course();" value="Fetch Course  " class="btn btn-primary" />

                                            </div>
                                        </div>

                            </div>

                        </div>
                    
                
             <script>
                 function fetch_course(){
                     student=$('#student').val();
                     $('#section_err').html("");
                     if(student.length==0){
                         $('#section_err').html("Please select Student");
                     }else{
                        setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_course','student='+student);
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
