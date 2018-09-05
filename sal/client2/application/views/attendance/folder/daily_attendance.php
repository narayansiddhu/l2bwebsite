<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$date= $this->input->get("date");
$slot= $this->input->get("slot");
?>
<div class="row">
    
    <div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Add Attendance </a>
                        </li>
                    </ul>
            </div> 
            <?php
                if(strlen($this->session->userdata('Attendance_add_sucess'))>0 ){
                    ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('Attendance_add_sucess'); ?>
                        </div>
                        <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(2000).fadeOut();
                        </script>
                   <?php
                    $this->session->unset_userdata('Attendance_add_sucess');
                }
            ?> 
                <?php
                if( sizeof($this->form->getErrorArray()) >0 ){
                    ?><br/>        
                        
                        <div id="successMsg1" class="alert alert-danger alert-dismissable">
                       
                         <?php 
                        
                            foreach( $this->form->getErrorArray() as $err){
                                echo $err."<br/>";
                            }
                         ?>
                        </div>
                        <script>
                             $("#successMsg1").fadeIn();
                             $("#successMsg1").delay(7000).fadeOut();
                        </script>
                        <?php
                }
                ?>
            
            
            <?php 
              if((strlen($date)==0)||(strlen($slot)==0)){
                  ?>
                                    
                    <div class="box box-color box-bordered " >
                        <div class="box-title">
                                <h3><i class="fa fa-calendar" aria-hidden="true"></i>Date & Slot</h3> 
                        </div>
                        <div class="box-content nopadding "  >
                            <form class='form-horizontal form-bordered' action="" method="post" enctype="multipart/form-data"  >
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Date</label>
                                    <div class="col-sm-10">
                                        <input onchange="check_date_slot();" type="text" id="date" name="date" placeholder="Select Date" class="form-control datepick" value="<?php echo $this->form->value('date') ?>">
                                        <span style=" color: red">
                                            <?php
                                                echo $this->form->error('date');
                                            ?>
                                        </span>
                                       </div>
                            </div>
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Attendance Slot</label>
                                    <div class="col-sm-10">
                                        <select onchange="check_date_slot();" onselect="check_date_slot();" id="slot" name="slot" class="select2-me" style=" width: 40%"> 
                                            <option value="">Select Slot</option>
                                            <option value="1">Morning</option>
                                            <option value="2">Afternoon</option>
                                        </select>
                                        <span id="slot_err" style=" color: red">
                                            <?php
                                                echo $this->form->error('slot');
                                            ?>
                                        </span>
                                       </div>
                            </div>
                             </form>
                        </div>
                    </div>
                
                 <?php
              }else{
                 $date = explode("/",$date);
                 $day = mktime(0, 0, 0, $date[1], $date[0],$date[2]);
                $query =$this->db->query("SELECT c.id as cls_id,s.sid,s.name as secname , c.name as cls_name FROM `section` s JOIN class c ON s.class_id = c.id WHERE s.iid ='".$this->session->userdata('staff_Org_id')."' ORDER BY c.id ");
                $query = $query->result();
                $cls_array =array();
                $section_array = array();
                $sections ="";
                foreach($query as $val){
                    if(array_key_exists($val->cls_id,$cls_array)){
                        $arr["section"][$val->sid]=$val->secname;
                        $cls_array[$val->cls_id]["section"][]=array('secid'=>$val->sid ,'secname'=>$val->secname  );
                    }else{
                    $arr = array();
                     $arr = array('cls_id' => $val->cls_id, 'cls_name'=>$val->cls_name ,'section'=>"" );

                    $arr["section"][]=array('secid'=>$val->sid ,'secname'=>$val->secname );
                    $cls_array[$val->cls_id]=$arr;
                    }
                    $sections .=$val->sid.",";
                    $section_array[$val->sid] = array("id"=>$val->sid ,'section'=>$val->secname);
                }
                $sections = substr($sections, 0, strlen($sections)-1);
                $day_att = array();
                $q =$this->db->query("SELECT id,section FROM `attendance_date` where section in (".$sections.") AND iid='".$this->session->userdata('staff_Org_id')."' AND day = '".$day."' AND slot='".$slot."' " );
                if($q->num_rows()>0){
                  $q = $q->result();
                  foreach($q as $val){
                      $day_att[$val->section] = array('ad_id'=>$val->id,'section'=>$val->section);
                  }
                }
                $sections = explode(",",$sections);
                foreach ($sections as $value) {
                    if(!array_key_exists($value,$day_att)){
                        $data=array(
                                'iid' =>$this->session->userdata('staff_Org_id'),
                                'section' =>$value,
                                'day' =>$day,
                                'slot' =>$slot
                            );
                            $this->db->insert('attendance_date',$data);
                            $aid=$this->db->insert_id();
                            $day_att[$value] = array('ad_id'=>$aid,'section'=>$value);
                    }
                }
               
                ?>
                        
                        
                            
                                      
                      
                        <div class="box "   >
                                    <div class="box-title">
                                        <h3 style=" color: blue"><i class="fa fa-check" aria-hidden="true"></i>School Attendance on <?php echo $this->input->get("date")  ?> , <?php  
                                            if($slot==1){
                                                echo "Morning Attendance slot";
                                            }else if($slot==2){
                                                echo "Afternoon Attendance slot";
                                            }
                                            ?> </h3> 
                                    </div>
                                    <div class="box-content nopadding " style=" min-height: 300px;"  > 
                                       
                                        <form class='form-horizontal form-bordered form-color' action="<?php echo base_url() ?>index.php/attendance/add_grp_attendance" method="post" enctype="multipart/form-data"  >  
                                        <br/>
                                            <input type="hidden" name="date" value="<?php  echo $this->input->get("date")?>" />
                                            <input type="hidden" name="slot" value="<?php  echo $this->input->get("slot")?>" />
                                            <input type="hidden" name="day_att_id" value='<?php echo json_encode($day_att) ?>' />
                                            <div class="tabs-container" style=" width: 20%; float: left; " >
                                                    <ul class="tabs tabs-inline tabs-left">
                                                        <?php
                                                        $i=1;
                                                             foreach ($cls_array as $value) {
                                                                 ?>
                                                                 <li <?php 
                                                                   if($i==1){
                                                                       echo 'class="active"';
                                                                   }
                                                                 ?>  >
                                                                    <a href="#<?php echo $value['cls_id'] ?>" data-toggle="tab"><?php echo $value['cls_name'] ?></a>
                                                                </li>
                                                                 <?php
                                                                 $i++;
                                                             }

                                                        ?>
                                                    </ul>

                                            </div>

                                            <div class="tab-content padding tab-content-inline nopadding"   >
                            <?php
                             $i=1;
                             foreach ($cls_array as $value) {
                                 ?>
                                <div 
                                     <?php
                                      if($i==1){
                                          echo 'class="tab-pane active nopadding "';
                                      }else{
                                          echo 'class="tab-pane  nopadding" ';
                                      }
                                     ?>
                                     id="<?php echo $value['cls_id'] ?>">


                                    <div class="box nopadding">
                                        <div class="box-title">

                                            <h3> <i class="fa fa-bars" ></i><?php  echo $value['cls_name'] ?> </h3>

                                            <div class="actions">
                                                <ul class="tabs">
                                                    <?php
                                                    $k=0;
                                                        foreach ($value['section'] as $val) {
                                                           ?>
                                                            <li <?php
                                                               if($k==0){
                                                                   echo 'class="active"';
                                                               }
                                                                ?>
                                                                ><a href="#sec_<?php echo $val['secid'] ?>" data-toggle="tab"><?php echo $val['secname'] ?></a>
                                                            </li>
                                                          <?php
                                                          $k++;
                                                        }                                            
                                                    ?>

                                                </ul>
                                            </div>

                                        </div>
                                        <div class="box-content nopadding" style=" height: 500px; overflow-y:  scroll" >
                                            <div class="tab-content nopadding">
                                                <?php
                                                $k=0;
                                                foreach ($value['section'] as $val) {
                                                   ?>

                                                    <div  <?php
                                                            if($k==0){
                                                                echo 'class="tab-pane active"';
                                                            }else{
                                                                echo 'class="tab-pane"';
                                                            }
                                                             ?>
                                                         id="sec_<?php echo $val['secid'] ?>">

                                                        <h3 style=" color: #7767e4; text-align: center">Section : <?php echo $val['secname'] ?></h3>
                                                        <?php
                                                           $absenties  = array();
                                                           $absents =$this->db->query("SELECT a.id,a.student,s.roll FROM `attendance` a JOIN student s ON s.student_id = a.student   WHERE a.date_id= '".$day_att[$val['secid']]['ad_id']."' AND acid = '".$slot."' AND a.iid= '".$this->session->userdata('staff_Org_id')."' AND s.section_id='".$val['secid']."' ");
                                                           $prev="";
                                                           if($absents->num_rows()>0){
                                                              $absents = $absents->result(); 
                                                              foreach($absents as $a){
                                                               $absenties[$a->student] = array("att_id" =>$a->id,"roll" =>$a->roll,'student_id'=>$a->student); 
                                                               $prev=$prev.$a->roll.",";
                                                              }
                                                              $prev = substr($prev, 0, strlen($prev));
                                                           }

                                                           $students =$this->db->query("SELECT * FROM `student` WHERE `section_id` = '".$val['secid']."' AND iid='".$this->session->userdata('staff_Org_id')."' ");

                                                           ?><hr style=" padding: none"/>
                                                        <div class="form-group" style=" background-color: white">
                                                            <label for="textfield"  class="control-label col-sm-2">Absenties</label>
                                                                <div class="col-sm-10">
                                                                    <input id="absenties_<?php echo $val['secid'] ?>" placeholder="Enter Rollno with , Separator " name="absenties_<?php echo $val['secid'] ?>" type="text" style=" width: 100%;  " value="<?php 
                                                                    if(strlen($this->form->value("absenties_".$val['secid']))){
                                                                        echo $this->form->value("absenties_".$val['secid']);
                                                                    }else{
                                                                    echo $prev; 
                                                                    }
                                                                    
                                                                    ?>"    class="form-control" />                                        
                                                                    <span style=" color: red">
                                                                            <?php
                                                                                echo $this->form->error("absenties_".$val['secid']);   
                                                                             ?>
                                                                        </span>        
                                                                </div>
                                                        </div>
                                                        <input name="Absent_prev_list_<?php echo $val['secid'] ?>" type="hidden"  value='<?php echo json_encode($absenties) ?>' />

                                                        <table class=" table datatable nopadding" >
                                                            <thead>
                                                                <tr>
                                                                    <th>Roll No</th>
                                                                    <th>Student Name</th>
                                                                    <th>Userid</th>
                                                                    <th>Status</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody> 
                                                                <?php 
                                                                 $stud_roll_list ="";
                                                                 $student_list = array();
                                                                   if($students->num_rows()>0){
                                                                     $students = $students->result();
                                                                     foreach($students as $stud){
                                                                       ?>
                                                                <tr>
                                                                    <td><?php echo $stud->roll ?></td>
                                                                    <td><?php echo $stud->name ?></td>
                                                                    <td><?php echo $stud->userid ?></td>
                                                                    <td id="td_status_<?php echo $stud->student_id  ?>"><?php 
                                                                       if(!array_key_exists($stud->student_id, $absenties)){
                                                                           ?>
                                                                        Present
                                                                            <?php
                                                                          }else{
                                                                              ?>Absent
                                                                            <?php 
                                                                          }

                                                                    ?></td>

                                                                    <td id="td_action_<?php echo $stud->student_id  ?>">
                                                                        <input type="checkbox" name="stud_<?php echo $stud->student_id ?>" id="stud_<?php echo $stud->student_id ?>" onclick="add_absent_list('<?php echo $val['secid'] ?>','<?php echo $stud->student_id ?>','<?php echo $stud->roll ?>');" value="" />
                                                                    </td>
                                                                </tr>
                                                                       <?php
                                                                       $stud_roll_list = $stud_roll_list.$stud->roll.",";
                                                                       $student_list[$stud->roll] = array('student_id'=>$stud->student_id ,'name'=>$stud->name,'roll'=>$stud->roll ,'userid' => $stud->userid);
                                                                     }
                                                                   }
                                                                   $stud_roll_list = substr($stud_roll_list,0,strlen($stud_roll_list)-1);
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        <input  type="hidden" name="student_roll_list_<?php echo $val['secid'] ?>" value='<?php echo $stud_roll_list ?>' />
                                                        <input  type="hidden" name="student_list_<?php echo $val['secid'] ?>" value='<?php echo json_encode($student_list) ?>' />
                                                    </div>

                                                  <?php
                                                  $k++;
                                                }                                            
                                                    ?>

                                            </div>
                                        </div>

                                        </div>


                                </div>
                                 <?php
                                 $i++;
                             }

                            ?>




                                    </div><br/><br/>
                                    <div class="row">
                                        <div class="col-sm-4">&nbsp;
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="submit" class="btn btn-block btn-primary" name="submit" value="submit Attendance" />
                                        </div>
                                        <div class="col-sm-4">&nbsp;
                                        </div>
                                    </div>
                                    </form>
                                     </div>

                              

                        </div>
                  
                            
                            
                           
                <?php
              }
            
            ?>
            
        
            
    </div>
</div>

            
        
<script>
    function check_date_slot(){
        $('#slot_err').html("<br/>");
        date = $('#date').val();
        slot = $('#slot').val();
        if( (date.length==0)||(slot.length==0) ){
            $('#slot_err').html("<br/>Please select date and slot");
        }else{
             setState('slot_err','<?php echo base_url() ?>index.php/attendance/check_date_slot','date='+date+'&slot='+slot);
        }
    }
    
    function add_absent_list(section,student,roll){   
        if($('#stud_'+student).prop('checked')){
      
           //add roll no
          // alert("ADDDING roll no : "+roll);
           absent=$('#absenties_'+section).val();
           absent=absent+","+roll;
          
           $('#absenties_'+section).val(absent);
           $('#td_status_'+student).html('Absent');
            setState('absenties_'+section,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+absent+'&section='+section);
        }
        else {
                   //remove roll no
         //          alert("REmove roll no : "+roll);
           absent=$('#absenties_'+section).val();
           absent=absent+","+roll;
           $('#td_status_'+student).html('Present');
           setState('absenties_'+section,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+absent+'&section='+section+'&roll='+roll);
  
        }
      
//      absent=$('#absenties_'+section).val();
//      absent=absent+","+roll;
//      //$('#absenties_'+section).val(absent);
//      //td_status_
//      $('#td_status_'+student).html('Absent');
//     // $('#td_action_'+student).html('<a href="#" style="color: green;" onclick="remove_from_list('+section+','+student+','+roll+');" class="btn btn-default"  ><i class="fa fa-product-hunt" aria-hidden="true"></i></a>');
//      setState('absenties_'+section,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+absent+'&section='+section);
    }
    
    function remove_from_list(section,student,roll){
      absent=$('#absenties_'+section).val();
      absent=absent+","+roll;
      //$('#absenties_'+section).val(absent);
      $('#td_status_'+student).html('Present');
     // $('#td_action_'+student).html('<a href="#" style="color: red;" onclick="add_absent_list('+section+','+student+','+roll+');" class="btn btn-default"  ><i class="fa fa-font" aria-hidden="true"></i></a>');
      setState('absenties_'+section,'<?php echo base_url() ?>index.php/attendance/reassign_rollnos','absent='+absent+'&section='+section+'&roll='+roll);
    }
    
    function check(){
        alert("pghcgfc");
    }
    
    function blur(){
        alert("blur......");
    }
    
    
</script>

<div style=" width: 40%; height: 10px">
</div>

<?php
$this->load->view('structure/footer');
?>