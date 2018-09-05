<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
<?php
$class=$this->db->query("SELECT * FROM `timings` WHERE section='".$settings->sid."'");
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
    $class=$class->row();
    $start=$class->start;
    $start=  mktime(substr($start,0,strlen($start)-2), substr($start,strlen($start)-2));
    for($i=1;$i<=$class->classes;$i++){
        $end=$start+($class->span*60);
       $timings[]= date("H:i",$start);
        $start=$end;
     }
     $timings[]= date("H:i",$end);
     
?>

<?php
if(strlen($this->session->userdata('attendance_config'))>0){
 ?>
  <br/>
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('attendance_config'); ?>
        </div>  
 <?php
  $this->session->unset_userdata('attendance_config');
}

?>



            <div class="box box-bordered box-color">
                <div class="box-title">
                   <h3><i class="fa fa-th-list"></i>Configure  attendance Settings of <?php echo $settings->class ?> , <?php echo $settings->section ?> </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <form  class='form-horizontal form-bordered' method="post" action="<?php echo base_url() ?>index.php/attendance/save_config"  >
                       <?php
                       $i=1;
                       $ids="";
                       foreach ($configure as $value) {
                        $ids.= $value->acid.",";
                        ?>
                       <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Attendance -<?php echo $i++; ?></label>
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
                           <input type="submit" name="submit" value="Save Timetable " class="btn btn-primary" />
                       </div>
                       
                       
                    </form>
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