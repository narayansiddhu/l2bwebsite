<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script>
    function month_attendance(){
      year=$('#att_year').val();
      month=$('#att_month').val();
      $("#fetch_att_error").html("");
     if(year.length == 0 || month.length==0 ){
         $("#fetch_att_error").html("<br/> Please Select Month And Year");
     }else{
        
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_attendance','student=<?php echo $student->student_id; ?>&month='+month+'&year='+year);
     }
    
  }
  
  
  function fetch_exam_results(){
   // exam_results_holder
    exam=$('#exam_r').val();
   $("#rexam_err").html("");
    if(exam.length==0){
        //rexam_err
        $("#rexam_err").html("Please select Exam");
    }else{
        //fetch_exam_results
        setState('exam_results_holder','<?php echo base_url() ?>index.php/Students/fetch_exam_results','student=<?php echo $student->student_id; ?>&exam='+exam);
        
    }
  }
  //fetch_exam_results
  
  function fetch_exam_schedule(){
   // exam_results_holder
    exam=$('#exam_r').val();
   $("#rexam_err").html("");
    if(exam.length==0){
        //rexam_err
        $("#rexam_err").html("Please select Exam");
    }else{
        //fetch_exam_results
        setState('exam_results_holder','<?php echo base_url() ?>index.php/Students/fetch_exam_schedule','student=<?php echo $student->student_id; ?>&exam='+exam);
        
    }
  }
  
  function send_sms_exam_results(){
     exam=$('#exam_r').val();
   $("#rexam_err").html("");
    if(exam.length==0){
        //rexam_err
        $("#rexam_err").html("Please select Exam");
    }else{
        //fetch_exam_results
        setState('pavan_q','<?php echo base_url() ?>index.php/Students/ajax_send_exam_results','student=<?php echo $student->student_id; ?>&exam='+exam);
        
    }
  }
  
  
</script>
<?php
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
                    <a href="<?php echo base_url(); ?>index.php/students/view/">View Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/students/view/<?php echo $student->class_id ?>/<?php echo $student->section ?>"><?php echo $student->class." - ".$student->section ?></a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                
                <li>
                    <a href="">Students Details</a>
                </li>
              </ul>  
        </div>
   <br/>

   <div class="box">
       <div class="col-sm-3 nopadding" style=" margin-top: 21px; padding-top: 10px; border:2px solid #318EEE">
           <div style=" text-align: center"><br/>
           <?php
              if(strlen($student->photo)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
              }else{
                  //echo file_exists(upload_path.$student->photo);
                  if(file_exists(upload_path.$student->photo)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $student->photo  ?>" alt="..." style=" width: 100px;; height: 100px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
                 }
              }
              ?>
           </div><hr/>
           <h3 style=" text-align: center; color:  #318EEE"><u><?php echo $student->name ?></u></h3>
           <table class=" table table-bordered nopadding" style=" width:100%">
               <tr>
                   <td><i class="fa fa-sitemap" aria-hidden="true"></i>&nbsp;<?php echo $student->class ." - " .$student->section ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;<?php echo $student->admission_no ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<?php echo date('d-m-Y',$student->birthday) ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $student->phone ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo $student->email ?></td>
               </tr>
               <tr>
                   <td style=" text-align: center"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Login Details</td>
               </tr>
               <tr>
                   <td><i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;<?php echo $student->userid ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-key" aria-hidden="true"></i>&nbsp;<?php echo $student->password ?></td>
               </tr>
               
           </table>
            
       </div>
       <div class="col-sm-9"  style=" padding-top: 0px;">
           <div class="box   box-bordered box-color " style=" margin: 0px">
                <div class="box-title">
                    <h3><i class="fa fa-user"></i> &nbsp;&nbsp;
                                <?php echo $student->name; ?> Details
                        </h3>
                    <div class="actions">
                        <a class="btn " href="" rel="tooltip" title="" data-original-title="View Profile"  >
                               <i class="fa fa-user"></i> 
                        </a>

                        <a class="btn " onclick="fee_payments();" rel="tooltip" title="" data-original-title="View Fee Payments"   >
                            <i class="fa fa-money" aria-hidden="true"></i> 
                        </a>

                        <a class="btn " onclick="exams();" rel="tooltip" title="" data-original-title="View Exam Schedule">
                            <i class="fa fa-pencil-square-o "></i>
                        </a>
                        <a class="btn "  onclick="results();"  rel="tooltip"  data-original-title="Results" >
                                 <i class="fa fa-adn" aria-hidden="true"></i>
                        </a>

                        <a class="btn " onclick="assignments();" rel="tooltip" title="" data-original-title="Assignment's">
                             <i class="fa fa-files-o" aria-hidden="true"></i> 
                        </a>
                         <?php 
                            //Clzattendance/reports?stdcls_name=1&student=1
                      //   echo $this->session->userdata("institute_att_type");
                         if($this->session->userdata("institute_att_type")==1){
                            ?>
                        <a class="btn "  href="<?php echo base_url() ?>index.php/attendance/view?stdcls_name=<?php echo $student->section_id ?>&student=<?php echo $student->student_id ?>" target="_blank" rel="tooltip"  data-original-title="View Attendance" >
                               <i class="fa fa-check" aria-hidden="true"></i>
                        </a>
                            <?php 
                         }else{
                            ?>
                        <a class="btn "  href="<?php echo base_url() ?>index.php/Clzattendance/reports?stdcls_name=<?php echo $student->section_id ?>&student=<?php echo $student->student_id ?>" target="_blank" rel="tooltip"  data-original-title="View Attendance" >
                               <i class="fa fa-check" aria-hidden="true"></i>
                        </a>
                            <?php 
                         }
                         ?>
                        

                        <a class="btn " onclick="library();" rel="tooltip"  data-original-title="Library Books" >
                                <i class="fa fa-book" aria-hidden="true"></i> 
                        </a>

                        

                        <a class="btn " onclick="timetable();"  rel="tooltip"  data-original-title="Time Table" >
                                       <i class="fa fa-calendar" aria-hidden="true"></i> 
                        </a>
                        <a class="btn "  href="<?php echo base_url(); ?>index.php/Students/edit/<?php echo $student->student_id ?>" rel="tooltip" title="" data-original-title="Edit Student Profile"  >
                              <i class="fa fa-pencil-square" aria-hidden="true"></i>
                        </a>
                    </div>
                        
                    
                </div>
               <div   style=" max-height: 750px;" class="box-content nopadding">
                   <div  id="result_holder" class='form-horizontal form-bordered'>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->name ?></span> 
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Father Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->father_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mother Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold">  <?php echo $student->mother_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Admission No</label>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->admission_no  ?></span>
                                    </div>
 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Class-Section</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->class ." - " .$student->section ?></span>
                                    </div>
                                 </div>
                                <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Roll No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->roll ?></span>
                                    </div> 
                                 </div>
                                 <?php // print_r($student);
                                  $blood_group = unserialize(blood_groups); 
                                  $Caste_system=  unserialize(Caste_system);
                                 ?>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Birthday</label>
                                           <span class="form-control" style=" font-weight:  bold"><?php echo date('d-m-Y',$student->birthday) ?>
                                           </span> 
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mobile No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->phone ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($blood_group[$student->bloodgroup])){
                                                echo $blood_group[$student->bloodgroup];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Gender</label>
                                               <span class="form-control" style=" font-weight:  bold"><?php if($student->sex ==1){
                                                    echo "Male";
                                                }else{
                                                    echo "Female";
                                                    }    ?></span>
                                            
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Email</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->email ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Caste</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($Caste_system[$student->caste])){
                                                echo $Caste_system[$student->caste];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Address</label>
                                            <textarea  disabled="" style=' height: 90px '  class="form-control" style=" resize: none; font-weight:  bold"><?php echo $student->address ?></textarea>
                                     </div>
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Locality</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->locality ?></span>
                                    </div>
                                 </div>
                       <br style=" clear: both"/>
                       <hr style=" clear: both"/>
                       <h4  style=" color:  teal" >Parent Login Credentials</h4>
                       <div class="col-sm-4">
                           <div class="form-group1">
                                    <label for="textfield" class="control-label">Name</label>
                                   <span class="form-control" style=" font-weight:  bold"><?php echo $student->parent ?>
                                   </span> 
                            </div> 
                       </div>
                       <div class="col-sm-4">
                           <div class="form-group1">
                                    <label for="textfield" class="control-label">E-mail/Phone</label>
                                   <span class="form-control" style=" font-weight:  bold"><?php echo $student->prnt_email ." / ".$student->prnt_phone ?>
                                   </span> 
                            </div> 
                       </div>
                       <div class="col-sm-4">
                           <div class="form-group1">
                                    <label for="textfield" class="control-label">Password</label>
                                   <span class="form-control" style=" font-weight:  bold"><?php echo $student->prnt_password ?>
                                   </span> 
                            </div> 
                       </div>
                       <br style=" clear: both"/><br/>
                             </div>
               </div>
           </div>
       </div>
   </div>
                                         
   <span id='pavan_q'></span>
<script>
  
  function profile(){
     
     setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_profile','student=<?php echo $student->student_id; ?>');
  }
  
  function fee_payments(){
//      $('#result_header').html('<i class="fa fa-money"></i> Fee Payments');
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_fee','student=<?php echo $student->student_id; ?>');
      
  }
  
  function library(){
//       $('#result_header').html('<i class="fa fa-book"></i> Library');
       //ajax_library
       setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_library','student=<?php echo $student->student_id; ?>');
  
  }
  
  function timetable(){
//      $('#result_header').html('<i class="fa fa-calendar"></i> Time Table');
      //ajax_timetable
       setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_timetable','student=<?php echo $student->student_id; ?>');
  
  }
  
  function assignments(){
    //  $('#result_header').html('<i class="fa fa-files-o"></i> Assignments');
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student=<?php echo $student->student_id; ?>');
  }
  
  function attendance(){
     setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_attendance','student=<?php echo $student->student_id; ?>');
  }
  
  function exams(){
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_exam','student=<?php echo $student->student_id; ?>');           
    }
  
  
  function results(){
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_exam_results','student=<?php echo $student->student_id; ?>');           
  }
  
  function fetch_assignments(){
     subdate=$('#subdate').val(); 
     $("#sub_date_error").html("");
     if(subdate.length==0){
         $("#sub_date_error").html("<br/>Please select date");
     }else{
         //assignments_holder
         setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student=<?php echo $student->student_id; ?>&date='+subdate); 
     }
  }
  
  
</script>
        </div>
    </div>
</div>
<?php 
$this->load->view('structure/footer');
?>