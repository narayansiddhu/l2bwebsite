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


<div class="row">
    <div class="col-sm-12">
        <div class="box">
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
        
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                             Attendance List of <?php echo  $section->class ?> ,<?php echo  $section->section ?>  ==>  <?php echo date('d-m-y',$data->day); ?>
                        </h3>
                    
                </div>
                <div class="box-content">
                     <form method="post" action="<?php echo base_url(); ?>index.php/teachers/save_attendance">
                        <input type="hidden" name="section" value="<?php echo   $section->sid ?>" />
                        <input type="hidden" name="att_date" value="<?php echo $data->id ?>" />
                        
                    <table class="table table-hover table-nomargin" style=" width: 100%">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Student</th>
                                <th>Roll No</th>
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
                                           <td><?php echo $i++; ?></td>
                                           <td><?php echo $value->name; ?></td>
                                           <td><?php echo $value->roll; ?></td>
                                           <td>
                                               <?php 
                                                if($value->status==1){
                                                        echo "present";
                                                        }else{
                                                            echo "absent";
                                                        }
                                               ?>
                                            </td>
                                     <?php
                                   }else{
                                     ?>
                                       <td>
                                       <?php 
                                                if($value->status==1){
                                                        echo "present";
                                                        }else{
                                                            echo "absent";
                                                        }
                                               ?></td>
                                   <?php
                                       }
                                      $proll=$value->roll; 
                                   }
                                   $ids=  substr($ids, 0, strlen($ids)-1);
                                   
                                   ?>
                        <input type="hidden" id="id_values" name="ids" value="<?php echo $ids ?>" />
                        </tbody>
                    </table>
                        
                     </form>
                </div>
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