<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$slot1 = array();
$slot2 = array();

foreach ($attendance as $value) {
    if($value->acid==1){
      $slot1[$value->student] = array("ad_id"=>$value->id,"roll"=>$value->roll); 
    }elseif($value->acid==2){
      $slot2[$value->student] = array("ad_id"=>$value->id,"roll"=>$value->roll); 
    }
}

$student_array= $this->db->query("SELECT * from student where section_id='".$section->sid."'");
$student_array = $student_array->result();
?>

<?php
if(strlen($this->session->userdata('attendance_update'))>0){
 ?>
  <br/>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
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
                             View Attendance List of <?php echo  $section->class ?> ,<?php echo  $section->section ?>  On  <?php echo date('d-m-y',$date); ?>
                        </h3>
                    <div class="actions">
                        <a href="<?php echo base_url(); ?>index.php/teachers/edit_date_attendance/<?php echo $section->sid ?>/<?php  echo date('d-m-y',$date);   ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a>
                    </div>
                </div>
                <div class="box-content nopadding">
                        
                    <table class="table table-hover table-nomargin datatable table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student</th>
                                <?php
                                $slots ="";
                                    foreach($data as $t=>$key ){
                                        ?>
                                <th>
                                    <?php 
                                   // print_r($key);
                                    if($key->slot == 1){
                                        echo "Morning slot";
                                    }elseif($key->slot == 2){
                                        echo "Afternoon slot";
                                    }
                                    ?>
                                </th>
                                        <?php
                                        $slots.=$key->slot.",";
                                    }
                                    $slots = array_filter(explode(",",$slots));
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i=1;
                            $proll="";
                            
                            
                               foreach ($student_array as $value) {
                                   ?>
                                        <tr>
                                            <td><?php echo $value->roll ?></td>
                                             <td><?php echo $value->name ?></td>
                                             <?php
                                                foreach($slots as $s){
                                                    ?><td><?php
                                                   if($s == 1){
                                                     if(array_key_exists($value->student_id, $slot1)){
                                                         echo "Absent";
                                                     }else{
                                                         echo "Present";
                                                     }
                                                       }elseif($s == 2){
                                                    if(array_key_exists($value->student_id, $slot2)){
                                                         echo "Absent";
                                                     }else{
                                                         echo "Present";
                                                     }
                                                    } 
                                                      ?></td><?php
                                                }                                      
                                             ?>                                
                                        </tr>
                                    <?php
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