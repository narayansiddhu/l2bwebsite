<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$section =$this->input->get("section");
$date =$this->input->get("date");
$slots =$this->input->get("slots");
$add=1;
if((strlen($section)>0) &&(strlen($date)>0) ){
    $date=explode("/",$date);
    $d_time=mktime(0,0,0,$date[1],$date[0],$date[2]);
    //date("d-m-y H:I",$d_time);
    $add=0;
    $section ="SELECT s.sid,s.name as secname ,c.name as clsname , st.id,st.name ,st.email,st.phone, (select count(*) from student where section_id=s.sid) as students FROM section s JOIN class c ON s.class_id=c.id LEFT JOIN staff st On s.cls_tch_id = st.id  where s.sid='".$section."' ";
    $section = $this->db->query($section);
    $section =$section->row();
    $att_config="SELECT c.* FROM attendance_config c JOIN attendance_settings s ON c.asid=s.aid WHERE s.section= '".$section->sid."' AND c.acid IN (".$slots.") ";
    $att_config =$this->db->query($att_config)->result();
    $acid ="";
    $slot_roll=array();
    foreach ($att_config as $value) {
        $acid.=$value->acid.",";
        $slot_roll[$value->acid]['rolls']="";
    }
    $pr_att ="SELECT d.* , group_concat(a.id) as ids , group_concat(a.student) as students,group_concat(s.roll) as rolls  FROM `attendance_date` d  JOIN  attendance a ON (a.date_id=d.id AND a.acid=d.slot ) JOIN student s ON a.student= s.student_id  WHERE slot IN  (".$slots.")   AND day ='".$d_time."' GROUP BY d.id ";
    $pr_att =$this->db->query($pr_att)->result();
    $std_attendance=array();
    foreach ($pr_att as $value) {
        $slot_roll[$value->slot]["Att_id"]=$value->id;
      $ids=$value->ids;
      $stds=$value->students;
      $rolls =$value->rolls;
       if(strlen($ids)==0){
           $std_attendance[$value->slot]=0;
       }else{
           $ids=explode(",",$ids);
           $stds=explode(",",$stds);
           $rolls=explode(",",$rolls);
           for($i=0;$i<sizeof($ids);$i++){
              $slot_roll[$value->slot]['rolls'].=$rolls[$i].",";
              $std_attendance[$value->slot][$stds[$i]] =$ids[$i];
           }
       }  
    }    
}
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
                    <a href="<?php echo base_url(); ?>index.php/Clzattendance">Attendance</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Add Attendance</a>
                </li>
            </ul>

    </div> 
                    <?php
        if(strlen($this->session->userdata('attendance_add_Sucesss'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('attendance_add_Sucesss'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
                $("#successMsg").delay(2000).fadeOut();
            </script>
           <?php
            $this->session->unset_userdata('attendance_add_Sucesss');
        }
        ?>
          <?php
             if($add==1){
                 ?>
            <div class="box ">
                <div class="box-title">
                    <h3 style="color: #66cc00 "><i class="fa fa-check"></i>Add Attendance</h3> 
                </div>
                <div class="box-content nopadding">    
                    <br/>
                        <div class="box"  style=" height: auto"  >
                            <div class="col-sm-4">
                                <div class="form-group1">
                                    <label for="textfield" class="control-label">Attendance Date<span style=" float: right ; color: red">*</span></label>
                                        <input type="text" id="att_date" name="att_date" placeholder="Select Attendance Date" class="form-control datepick" value="<?php echo $this->form->value('stusername') ?>" > 
                                        <span  id="date_err" style=" color: red">
                                                
                                            </span>  
                                </div>
                                
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                    <?php
                                        $att="select s.sid,s.name as sec_name ,c.name as cls_name , ats.*  from section s JOIN class c ON s.class_id=c.id LEFT JOIN attendance_settings ats ON ats.section = s.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."'";
                                        $att = $this->db->query($att)->result();
                                    ?>  
                                    <label for="textfield" class="control-label ">Class-Section<span style=" float: right ; color: red">*</span></label>
                                    <select onchange="load_slots();" class="select2-me" name="section" id="section"  style=" width: 100%" >
                            <option value="">Select A Section</option>
                            <?php
                            foreach ($att as  $value) {
                                if($value->no_of_times>0){
                               ?>
                            <option value="<?php echo $value->sid ?>"><?php echo $value->cls_name." - ".$value->sec_name ?></option>
                              <?php
                              }
                            }
                            ?>
                        </select>
          
                                                
                        <span id="section_err" style="color:red"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                            <label for="textfield" class="control-label">Slots<span style=" float: right ; color: red">*</span></label>
                                            <div id="slots_holder">
                                                <span id="slot_ids"></span>                                    
                                            </div>
                                                <span id="slot_error" style=" color: red">
                                                       
                                                    </span> 
                                    </div> 
                                
                            </div>
                            
                            <div class="form-actions col-sm-offset-4 col-sm-4" style=" clear: both">
                                 <input onclick="Add_attendance();" type="button" name="submit" value="Add Attendance" class="btn btn-primary btn-block" />
                                 <span id="error" style=" color: red"></span>
                            </div>
                        </div>
            
        </div>
    </div>
                   
            <script>
                
                function load_slots(){
                    $('#section_err').html("");
                    section =$('#section').val();
                    if(section.length==0){
                      $('#section_err').html("** Select Class-Section To Add Attendance");
                   }else{
                    
                       setState('slots_holder','<?php echo base_url() ?>index.php/Clzattendance/load_slots','section='+section);
                       setState('attendance_holder','<?php echo base_url() ?>index.php/Clzattendance/load_history','section='+section);
     
                   }
                }
                
                function Add_attendance(){
                    err_count=0;
                    $('#section_err').html("");
                    $('#date_err').html("");
                    checked_slots="";
                   section =$('#section').val();
                   slot_ids=$("#slot_ids").html();
                   att_date = $('#att_date').val();
                   if(section.length==0){
                      err_count++; 
                      $('#section_err').html("Select Section");
                   }
                   if(att_date.length==0){
                      err_count++; 
                      $('#date_err').html("Select Attendance Date");
                   }
                   if(slot_ids.length==0){
                      $('#slot_error').html("Select A section");
                   }else{
                       checked_slots="";
                      slot_ids = slot_ids.split(",");
                      for (i = 0; i < slot_ids.length; i++) { 
                            if($('#slot_'+slot_ids[i]).prop("checked")){
                                checked_slots=checked_slots+slot_ids[i]+",";
                                        //  alert('slot_'+slot_ids[i]+" is checked ");
                            }
                      }
                      if(checked_slots.length==0){
                          err_count++;
                              $('#slot_error').html("** please select attendance slots");
                      }
                  }
                   
                   if(err_count==0){
                       //slots
                      setState('error','<?php echo base_url() ?>index.php/Clzattendance/add','date='+att_date+'&section='+section+'&slots='+checked_slots);
                     }
                }
            </script>  
         
            <div class="box " id="attendance_holder" >
                <div class="box-title">
                    <h3 style="color: #66cc00 "><i class="fa fa-check"></i>Attendance History</h3> 
                </div>
                 
			
            </div>
                 <?php
             }else{
                 
           $acid = substr($acid, 0,strlen($acid)-1);
 //print_r($std_attendance);
                 ?>
            <div class="box">
            
                <div class="box box-bordered box-color">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-info-circle"></i>&nbsp;Section Info
                        </h3>
                    
                </div>
                    <div class="box-content nopadding">
                        <table class="table table-bordered" style="text-align: center">
                    <tr>
                        <td>Class-section<hr/>
                            <?php  echo $section->clsname." - ". $section->secname ?>
                        </td>
                        <td>Incharge<hr/>
                            <?php  
                            if(strlen($section->name)==0){
                                ?><span style=" color: red">--</span><?php
                            }
                            else{
                               echo  $section->name;
                            }
                            ?>
                        </td>
                        <td>Students<hr/>
                            <?php echo $section->students ?>
                        </td>
                        <td>Attendance Slots<hr/>
                            <?php 
                            $str_slot="";
                              foreach($att_config as $value){
                               $str_slot.= substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2) ."&nbsp;&nbsp;," ;
                              }
                              echo substr($str_slot,0,strlen($str_slot)-1);
                            ?>
                        </td>
                    </tr>
                </table>
                    </div>
                </div><?php

                ?>
                <div class="box box-bordered box-color">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-check"></i>&nbsp;Add Attendance
                        </h3>
                        <div class="actions">
                            <span style=" color: #ffff00">** Please Enter Or Check  Absenties Roll Only</span>
                        </div>
                    </div>
                    <div class="box-content nopadding">
                        <form  class='form-horizontal form-bordered' method="post" action="<?php echo base_url() ?>index.php/Clzattendance/submit_attendance"  >
                            <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                            <input type="hidden" name="pre_data" value='<?php echo json_encode($pr_att) ?>' />
                            <input type="hidden" name="date" value="<?php echo $this->input->get("date") ?>" />
                            <input type="hidden" name="slots" value='<?php echo $this->input->get("slots") ?>' />
                            
                            <?php 
                          $i=1;$acid ="";
                            foreach ($att_config as $value) {
                                $acid.=$value->acid.",";
                              ?>
                            <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Slot - <?php echo $i++; ?>  (<?php echo substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2) ?>) </label>
                        <div class="col-sm-10">
                            <input type="text" name="absenties_<?php echo $value->acid ?>" id="absenties_<?php echo $value->acid ?>" value="<?php print_r( $slot_roll[$value->acid]['rolls']) ?>" class="form-control" placeholder="Enter Absenties Roll No's"  />
                        </div>
                            </div>
                              <?php                              
                            }
                         ?>
                            <div class="box" style=" max-height: 400px; overflow-y: scroll">
                                <table class="table table-bordered  datatable table-striped" >
                                <thead>
                                    <tr>
                                        <th>Roll No</th>
                                        <th>Name</th>
                                        <?php
                                        $i=1;
                                        foreach ($att_config as $value) {
                                            ?>
                                        <th style=" text-align: center">Slot - <?php echo $i++ ?></th>
                                             <?php
                                        }
                                          ?>
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php
                                      $students= $this->db->query("SELECT student_id,roll,name ,phone from student where section_id='".$section->sid."'");
                                      $students =$students->result();
                                      $stud="";
                                      foreach ($students as $value) {
                                          $stud.=$value->student_id.",";
                                         ?>
                                    <tr>
                                        <td><?php echo $value->roll ?></td>
                                        <td><?php echo $value->name ?></td>
                                        <?php
                                        $i=1;
                                        foreach ($att_config as $con) {
                                            ?>
                                        <td style=" text-align: center">
                                            <input  value="<?php echo $value->roll ?>" type="checkbox" name="stud_<?php echo $con->acid ?>_<?php echo $value->student_id ?>" id="stud_<?php echo $con->acid ?>_<?php echo $value->student_id ?>" 
                                                    <?php 
                                                       if(isset($std_attendance[$con->acid][$value->student_id])){
                                                           echo " checked ";
                                                       }
                                                    ?>
                                                    
                                                    onchange="load_absenties(<?php echo $value->student_id ?>,<?php echo $con->acid ?>);"/>
                                        </td>
                                             <?php
                                        }
                                          ?>
                                    </tr>
                                         <?php 
                                      }
                                      
                                    ?>
                                <input type="hidden" name="student_ids" value="<?php echo $stud ?>" />
                                <input type="hidden" name="student_data" value="<?php echo json_encode($students) ?>" />
                                 </tbody>
                                 <input type="hidden" name="student_data" value='<?php echo json_encode($students) ?>' />
                            </table>
                            </div>
                            <hr/>
                            <div class="box">
                                        <div class="col-sm-4">&nbsp;
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="submit" class="btn btn-block btn-primary" name="submit" value="submit Attendance" />
                                        </div>
                                        <div class="col-sm-4">&nbsp;
                                        </div>
                                    </div><br/>
                        </form><br/>
                    </div>
                </div>
                
            </div>
            <script>
               function load_absenties(std_id,slot_id){
                    roll=$('#stud_'+slot_id+"_"+std_id).val();
                    if($('#stud_'+slot_id+"_"+std_id).prop("checked"))
                    {
                       prev=$('#absenties_'+slot_id).val(); 
                       prev=prev+","+roll;
                       $('#absenties_'+slot_id).val(prev); 
                       setState('absenties_'+slot_id,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+prev+'&section='+slot_id);
                    }
                    else{
                        prev=$('#absenties_'+slot_id).val(); 
                    setState('absenties_'+slot_id,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+prev+'&section='+slot_id+'&roll='+roll);
                   }
               }
            </script>
                 <?php
             }
           
          ?>
            
            
            
        </div>
    </div>
</div>
   
<?php
$this->load->view('structure/footer');
?>
