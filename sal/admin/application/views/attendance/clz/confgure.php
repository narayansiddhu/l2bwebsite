<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$edit=$this->input->get("action");
if(strtolower($edit)=="edit"){
    $edit=1;
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
                            <a href="">View Settings</a>
                        </li>
                    </ul>

            </div> 
<?php
$class=$this->db->query("SELECT distinct(time_start),time_end FROM `class_routine` where sec_id='".$settings->sid."' order by time_start ASC");
if($class->num_rows()==0){
        ?>
    <br/><br/>
      <div class="alert alert-warning alert-dismissable">
        <strong>Warning!</strong>
           Class Timings Not Set Please Configure Time Table
      </div>
        <?php
    }else{
    $timings=array();
    $class=$class->result();
    foreach($class as $val){
        $start =$val->time_start;
        $end=$val->time_end;
        $start =substr($start, 0,strlen($start)-2).":".substr($start, strlen($start)-2,strlen($start));
             $end =substr($end, 0,strlen($end)-2).":".substr($end, strlen($end)-2,strlen($end));
        $timings[]=$start;
        $timings[]=$end;
    }
    $timings=array_unique($timings);
?>

<?php
if(strlen($this->session->userdata('attendance_config'))>0){
 ?>
  <br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
         <?php echo $this->session->userdata('attendance_config'); ?>
        </div>
    <script>
        $("#successMsg").fadeIn();
        $("#successMsg").delay(3000).fadeOut();
    </script>  
 <?php
  $this->session->unset_userdata('attendance_config');
}
 $section ="SELECT s.sid,s.name as secname ,c.name as clsname , st.id,st.name ,st.email,st.phone, (select count(*) from student where section_id=s.sid) as students FROM section s JOIN class c ON s.class_id=c.id LEFT JOIN staff st On s.cls_tch_id = st.id  where s.sid='".$settings->sid."' ";
$section = $this->db->query($section);
$section =$section->row();

?>
  <div class="box">
      <div class="col-sm-4 nopadding">
          <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3><i class="fa fa-info-circle"></i>&nbsp;Section Info</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Class</td>
                            <td><?php  echo $section->clsname ?></td>
                        </tr>
                        <tr>
                            <td>Class</td>
                            <td><?php  echo $section->secname ?></td>
                        </tr>
                        <tr>
                            <td>Incharge</td>
                            <td><?php  
                            if(strlen($section->name)==0){
                                ?><span style=" color: red">** Not Assigned</span><?php
                            }
                            else{
                               echo  $section->name;
                            }
                            ?></td>
                        </tr>
                        <tr>
                            <td>Students</td>
                            <td><?php echo $section->students ?></td>
                        </tr>
                        <tr>
                            <td>Attendance Slots</td>
                            <td><?php  echo $settings->no_of_times ?></td>
                        </tr>
                    </table>
                </div>
          </div>
      </div>
      <div class="col-sm-8 nopadding">
          <?php
           
           if($edit==1){
               ?>
               <div class="box box-bordered box-color" style=" padding-left: 10px">
                <div class="box-title">
                   <h3><i class="fa fa-check"></i>Configure  attendance </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <form  class='form-horizontal form-bordered' method="post" action="<?php echo base_url() ?>index.php/Clzattendance/save_config"  >
                       <?php
                       $i=1;
                       $ids="";
                       foreach ($configure as $value) {
                        $ids.= $value->acid.",";
                        ?>
                       <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Slot -<?php echo $i++; ?></label>
                                    <div class="col-sm-10">
                                        <select class="select2-me" name="timing_<?php echo $value->acid ?>" style=" width: 100%"  >
                                            <option value="">Select A time</option>
                                        <?php 
                                            foreach ($timings as $t) {
                                              if(strlen($this->form->value("timing_".$value->acid))!=0 ){
                                                 $ti= $this->form->value("timing_".$value->acid);
                                              }else{
                                                 $ti= substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2);
                                            
                                                  if(strlen(substr($value->time,0,strlen($value->time)-2))==1){
                                                    $ti='0'.$ti;  
                                                  }
                                              }
                                            ?>
                                            <option value="<?php echo $t ?>" <?php
                                            if($ti==$t){
                                                echo "selected";
                                            }?> ><?php echo $t ?></option>
                                            <?php 
                                            }
                                        ?>
                                        </select>
                                        <span  style=" color: red">
                                            <?php
                                               echo $this->form->error("timing_".$value->acid);   
                                            ?>
                                        </span>        
                                    </div>
                        </div>  
                               
                          <?php 
                          
                       }
                       $ids=substr($ids,0,strlen($ids)-1);
                       ?>
                       <div class="form-actions col-sm-offset-2 col-sm-10">
                           <input type="hidden" name="asid" value="<?php echo $settings->aid ?>" />
                           <input type="hidden" name="ids" value="<?php echo $ids ?>" />
                           <input type="submit" name="submit" value="Save Attendance Settings " class="btn btn-primary" />
                       </div>
                       
                       
                    </form>
                </div>
            </div>    
               <?php
           }else{
               ?>
                 <div class="box box-bordered box-color" style=" padding-left: 10px">
                <div class="box-title">
                   <h3><i class="fa fa-check"></i>Attendance Slots</h3> 
                   <div class="actions">
                       <a style="background-color: white; color: rgb(54, 142, 224);" href="<?php echo base_url() ?>index.php/Clzattendance/configure/<?php echo $settings->aid ?>?action=edit"  class="btn btn-primary">
                           <i class="fa fa-pencil-square" aria-hidden="true"></i>&nbsp;Edit
                        </a>
                   </div>
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-bordered" style=" text-align: center" >
                                            <?php 
                     $i=1;
                    foreach($configure as $value){
                       ?>
                    <td>
                        Slot - <?php echo $i++ ?>
                        <hr/>
                        <?php
                          $ti= substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2);
                          echo $ti; 
                        ?>
                    </td>
                        <?php
                    }
                    
                    ?>
                    
                    </table>

                </div>
            </div> 
               <?php
           }
          ?> 
          
      </div>
  </div>
            
        
<?php
}
?>
  </div>
     </div>       
</div>
<?php
$this->load->view('structure/footer');
?>