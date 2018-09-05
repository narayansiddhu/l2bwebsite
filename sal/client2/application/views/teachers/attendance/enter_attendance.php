<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php 
$timings=$this->db->query("SELECT ac.* FROM `attendance_config` ac JOIN attendance_settings s ON ac.asid=s.aid WHERE s.section='".$section->sid."'");
$timings=$timings->result();
$t=array();
foreach ($timings as $value) {
  $t[]=$value->time;  
}

?>
<?php
if(strlen($this->session->userdata('attendance_update'))>0){
 ?>
  <br/>
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('attendance_update'); ?>
        </div>  
 <?php
  $this->session->unset_userdata('attendance_update');
}

?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                            Edit Attendance List of <?php echo  $section->class ?> ,<?php echo  $section->section ?>  On  <?php echo date('d-m-y',$data->day); ?>
                        </h3>
                    <div class="actions">
                        <a onclick="select_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as present"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                       <a onclick="unselect_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as absent"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                       
                      
                    </div>
                </div>
                <div class="box-content nopadding">
                     <form method="post" action="<?php echo base_url(); ?>index.php/teachers/save_attendance">
                        <input type="hidden" name="section" value="<?php echo   $section->sid ?>" />
                        <input type="hidden" name="att_date" value="<?php echo $data->id ?>" />
                    <table class="table table-hover table-nomargin  table-bordered"  style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student</th>
                                
                                <?php
                                foreach($t as $value){
                                    ?>
                                <th><?php echo substr($value,0,strlen($value)-2).":".substr($value,strlen($value)-2) ?></th>
                                  <?php 
                                }
                                
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i=1;$ids="";
                            $proll="";
                               foreach ($attendance as $value) {
                                  
                                  $ids.=$value->id.",";
                                   if($proll!=$value->roll){
                                        if($proll!=""){
                                          echo "</tr>"; 
                                        }
                                       ?>
                                        <tr>
                                           <td><?php echo $value->roll; ?></td>
                                           <td><?php echo $value->name; ?></td>
                                         
                                           <td>
                                               <select <?php  
                                              if(strlen($this->form->error('status_'.$value->id)) >0){
                                                ?>
                                                    style=" border-color: red;"
                                                   <?php
                                              }
                                           ?> id="status_<?php echo $value->id ?>" name="status_<?php echo $value->id ?>" >
                                                   <option value="">select</option>
                                                   <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==1) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==1){
                                                        echo "selected";
                                                        }
                                                   ?> value="1">Present</option>
                                                  <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==2) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==2){
                                                        echo "selected";
                                                        }
                                                   ?> value="2">Absent</option>
                                               </select></td>
                                     <?php
                                   }else{
                                     ?>
                                       <td><select   <?php  
                                              if(strlen($this->form->error('status_'.$value->id)) >0){
                                                ?>
                                                  style=" border-color: red;"
                                                   <?php
                                              }
                                              ?> id="status_<?php echo $value->id ?>" name="status_<?php echo $value->id ?>" >
                                                   <option value="">select</option>
                                                   <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==1) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==1){
                                                        echo "selected";
                                                        }
                                                   ?> value="1">Present</option>
                                                  <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==2) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==2){
                                                        echo "selected";
                                                        }
                                                   ?> value="2">Absent</option>
                                               </select></td>
                                   <?php
                                       }
                                      $proll=$value->roll; 
                                   }
                                   $ids=  substr($ids, 0, strlen($ids)-1);
                                   
                                   ?>
                        <input type="hidden" id="id_values" name="ids" value="<?php echo $ids ?>" />
                        </tbody>
                    </table>
                        
                        <br/><br/>  
                        <div class="col-sm-12"> 
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                <input type="submit"  name="submit" value="Save Attendance" class="btn btn-primary btn-block  " />  
                            </div>
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                           
                        </div>
                           <br/><br/>  
                        
                        
                     </form>
                </div>
        </div>
    </div>
</div>
<script>
  function select_all(){
      var ids=$('#id_values').val();
      arr=ids.split(",");
      for(i in arr){
         
         $("#status_"+arr[i]).val("1");
        
     }
  }
  function unselect_all(){
      var ids=$('#id_values').val();
      arr=ids.split(",");
      for(i in arr){
         $("#status_"+arr[i]).val("2");
        }
  } 
</script>
<?php
$this->load->view('structure/footer');
?>