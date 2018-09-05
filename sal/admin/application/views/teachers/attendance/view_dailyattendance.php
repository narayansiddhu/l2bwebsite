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
                             View Attendance List of <?php echo  $section->class ?> ,<?php echo  $section->section ?>  On  <?php echo date('d-m-y',$data->day); ?>
                        </h3>
                    <div class="actions">
                        <a href="<?php echo base_url(); ?>index.php/teachers/edit_attendance/<?php echo $section->sid ?>/<?php echo  $data->id  ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a>
                    </div>
                </div>
                <div class="box-content nopadding">
                        
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
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
                            $i=1;
                            $proll="";
                               foreach ($attendance as $value) {
                                  
                                 
                                   if($proll!=$value->roll){
                                        if($proll!=""){
                                          echo "</tr>"; 
                                        }
                                       ?>
                                        <tr>
                                           <td><?php echo $value->roll; ?></td>
                                           <td><?php echo $value->name; ?></td>
                                           <td>
                                               <?php
                                               switch($value->status){
                                                   case 0:echo "--";break;
                                                   case 1: echo "Present";break;
                                                   case 2: echo "Absent";break;
                                               }  
                                               
                                               ?>
                                               
                                           </td>
                                     <?php
                                   }else{
                                     ?>
                                       <td>
                                           <?php
                                               switch($value->status){
                                                   case 0:echo "--";break;
                                                   case 1: echo "Present";break;
                                                   case 2: echo "Absent";break;
                                               }  
                                            ?>
                                       </td>
                                   <?php
                                       }
                                      $proll=$value->roll; 
                                   }
                                   
                                   
                                   ?>
                        
                        </tbody>
                    </table>
                    
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