<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');


foreach ($attendance as $value) {
    if($value->acid==1){
      $slot1[$value->student] = array("ad_id"=>$value->id,"roll"=>$value->roll); 
    }elseif($value->acid==2){
      $slot2[$value->student] = array("ad_id"=>$value->id,"roll"=>$value->roll); 
    }
}

$att_date_id= array();


$student_array= $this->db->query("SELECT * from student where section_id='".$section->sid."'");
$student_array = $student_array->result();

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
                            Edit Attendance List of <?php echo  $section->class ?> ,<?php echo  $section->section ?>  On  <?php echo date('d-m-y',$date); ?>
                        </h3>
                    <div class="actions">
<!--                        <a onclick="select_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as present"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                       <a onclick="unselect_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as absent"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                       -->
                      
                    </div>
                </div>
                <div class="box-content nopadding">
                     <form method="post" action="<?php echo base_url(); ?>index.php/teachers/save_daily_attendance">
                         
                         
                        <input type="hidden" name="section" value="<?php echo   $section->sid ?>" />
                        <input type="hidden" name="att_date" value="<?php echo $date ?>" />
                    <table class="table table-hover table-nomargin  table-bordered"  style="width: 100%;">
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
                                  $arr;
                                    if($key->slot == 1){
                                        echo "Morning slot"; $arr = $slot1;
                                    }elseif($key->slot == 2){
                                        echo "Afternoon slot";$arr = $slot2;
                                    }
                                    
                                    
                                    ?>
                                </th>
                        <input type="hidden" name="slot_<?php echo $key->slot  ?>" value='<?php echo json_encode($arr) ?>' />
                                        <?php
                                        $slots.=$key->slot.",";
                                        $att_date_id[$key->slot] = $key->id;
                                    }
                                    $slots = array_filter(explode(",",$slots));
                                ?>                                
                            </tr>
                        </thead>
                        <input type="hidden" name="date_Slot_ids" value='<?php echo json_encode($att_date_id) ?>' />
                        <tbody>
                            <?php 
                            $i=1;
                            $proll="";
                            $stud_ids= "";
                            
                               foreach ($student_array as $value) {
                                   $stud_ids .= $value->student_id .",";
                                   ?>
                                        <tr>
                                            <td><?php echo  $value->roll ?></td>
                                             <td><?php echo $value->name ?></td>
                                             <?php
                                                foreach($slots as $s){
                                                    ?><td>
                                                        <?php
                                                        $arr;
                                                       
                                                        if($s == 1){
                                                        $arr = $slot1;
                                                       }elseif($s == 2){
                                                           $arr = $slot2;
                                                       }
                                                       
                                                       ?>
                                                       <select style=" width: 40% ; <?php  
                                                       if(strlen($this->form->error('bfile'))!=0){
                                                           ?> border-color: red;<?php
                                                       }?>" id="status_<?php echo $s ?>_<?php echo $value->student_id ?>" name="status_<?php echo $s ?>_<?php echo $value->student_id ?>" class="select2-me" > 
                                                           <option
                                                               <?php
                                                               if(strlen($this->form->value('status_'.$s.'_'.$value->student_id))>0){
                                                                   if($this->form->value('status_'.$s.'_'.$value->student_id)==1 ){
                                                                        echo "SELECTED";
                                                                    }
                                                               }else{

                                                                    if(! array_key_exists($value->student_id, $arr) ){
                                                                        echo "SELECTED";
                                                                    }  
                                                               }
                                                               ?> value="1">Present</option>
                                                            <option 
                                                                <?php
                                                                if(strlen($this->form->value('status_'.$s.'_'.$value->student_id))>0){
                                                                   if($this->form->value('status_'.$s.'_'.$value->student_id)==2 ){
                                                                        echo "SELECTED";
                                                                    }
                                                               }else{
                                                               if( array_key_exists($value->student_id, $arr) ){
                                                                   echo "SELECTED";
                                                               } 
                                                               }
                                                               ?>
                                                                value="2">Absent</option>
                                                        </select>
                                                       <?php
                                                       
                                                       ?>
                                                       
                                                 </td>
                                                          
                                                          <?php
                                                }                                      
                                             ?>                                
                                        </tr>
                                    <?php
                                }
                                   $stud_ids = substr($stud_ids, 0, strlen($stud_ids)-1);
                              
                                   ?>
                        
                        </tbody>
                        
                    </table>
                        
                        <br/><br/>  
                        <div class="col-sm-12"> 
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" id="id_values"  name="id_values" value="<?php echo $stud_ids ?>" class="btn btn-primary btn-block  " />  
                                <input type="hidden" id="slots"  name="slots" value="<?php echo    implode(",", $slots); ?>" class="btn btn-primary btn-block  " />  
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
      var slots=$('#slots').val(); 
      slots=slots.split(",");
      arr=ids.split(",");
      
      for(s in slots ){
           for(i in arr){
               $("#status_"+slots[s]+"_"+arr[i]).val("1");
            }  
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