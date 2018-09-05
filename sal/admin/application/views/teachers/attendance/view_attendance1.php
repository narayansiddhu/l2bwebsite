<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
          <br/>
       <div class="box nopadding">
            <div class="box-title nopadding">                                    
                 <h3>
                     <i class="fa fa-list" aria-hidden="true"></i>
                       Attendance of <?php echo $section->class ?> , <?php echo $section->section ?>
                 </h3>
                <div class="actions">
                    <a style="color: maroon"  href="<?php echo base_url(); ?>index.php/teachers/monthly_attendance/<?php echo $section->sid ?>" >&nbsp;&nbsp;Monthly Report&nbsp;&nbsp;</a>
                </div>
            </div>
       </div>
  
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Add Attendance </h3> 
                </div>
                <div class="box-content nopadding"> 
                  <div class='form-horizontal form-bordered' >
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Day</label>
                        <div class="col-sm-10">
                            <input type="text" id="new_date" placeholder="Place Select date"  class="form-control datepick" >
                            <span id="new_date_err" style=" color: red">
                                      
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Attendance Slot</label>
                            <div class="col-sm-10">
                                <select  id="slot" name="slot" class="select2-me" style=" width: 40%"> 
                                    <option value="">Select Slot</option>
                                    <option value="1">Morning</option>
                                    <option value="2">Afternoon</option>
                                </select>
                                <span id="slot_err" style=" color: red">
                                   
                                </span>
                               </div>
                    </div>
               
                    
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="button" id="add" onclick="check_date_slot();" name="add" value="Add Attendane" class="btn btn-primary" />
                        <span id="errors" style=" color: red">
                                       
                            </span> 
                    </div>
                  </div>
                </div>
            </div>
             

<script>
    
    function check_date_slot(){
       //new_date , slot
       d= $('#new_date').val();
       slot = $('#slot').val();
       $('#new_date_err').html("");
       $('#slot_err').html("");
        if(d.length==0){
           $('#new_date_err').html("Please select date");
        }else if(slot.length==0){
             $('#slot_err').html("Please select slot");
        }else{
            setState('errors','<?php echo base_url() ?>index.php/teachers/add_date','date='+d+'&slot='+slot+'&section=<?php echo $section->sid ?>'); 
        }
       
    }
    
    function add_attendance(){
      var d=$('#new_date').val();   
      if(d.length==0){
         $('#new_date_err').html('* Please select date'); 
      }else{
        $( "#add" ).prop( "disabled", true ); 
         setState('errors','<?php echo base_url() ?>index.php/teachers/add_date','date='+d+'&section=<?php echo $section->sid ?>');
      }
    }
</script>
<?php
$now=time();    
if( ( isset($_GET['month']) && isset($_GET['year']) )){
  if( ( is_numeric($_GET['month']) && is_numeric($_GET['year']) ) ){
      $now=   mktime(0,0,0,$_GET['month'],1,$_GET['year']);
  }
}
$time=  getdate($now);
$from=mktime(0,0,0,$time['mon'],1,$time['year']);
$to=mktime(0,0,0,$time['mon']+1,1,$time['year']);
$query=$this->db->query("SELECT day,GROUP_CONCAT(slot SEPARATOR ', ') as slots  FROM `attendance_date` WHERE section='".$section->sid."' AND ( day >= '".$from."' AND day < '".$to."' ) GROUP BY day ORDER BY day DESC   ");
$query=$query->result();

?>
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Attendance OF  <?php echo $time['month'] ." , " .$time['year']  ?> </h3> 
                        <div class="actions" >
                            <form class='form-horizontal ' >
                                <div class="col-sm-12 nopadding" >
                                    
                                    <div class="col-sm-5  ">
                                        <div class="form-group">
                                           <select class="select2-me" id="month" name="month"  style=" width: 90% "  >
                                            <option value="" >Month</option>
                                            <?php
                                              for ($m=1; $m<=12; $m++) {
                                                  ?> <option value="<?php echo $m ?>"  <?php 
                                                    if($time['mon']==$m){
                                                        echo "selected";
                                                    }
                                                  
                                                  ?> ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                                }
                                                ?>
                                           </select> 
                                        </div>
                                        </div>
                                    <div class="col-sm-5  ">
                                        <div class="form-group">
                                           <select class="select2-me" id="year" name="year"  style=" width: 90% "  >
                                            <option value="" >Year</option>
                                            <?php
                                                $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                                $start=$start->row();
                                                $start=$start->timestamp;
                                                $start=getdate($start);
                                                $start=$start['year'];
                                                $now=getdate();
                                                $now=$now['year'];
                                                for($i=$start;$i<=$now;$i++){
                                                  ?>
                                                   <option value="<?php echo $i; ?>"  <?php
                                                    if($time['year']==$i){
                                                        echo "selected";
                                                    }
                                                  
                                                 ?> ><?php echo $i; ?>
                                                   </option>
                                                  <?php
                                                }

                                            ?>
                                           </select> 
                                        </div>
                                    </div>
                                    <div class="form-actions  col-sm-2 nopadding ">
                                       <button class="btn">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                       </div>
                            </div>
                            </form>
                           
                        </div>
                        
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Day</th>
                                 <th>Attendance Slots</th>
                                <th>action's</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             
                             $i=1;
                             foreach($query as $details){
                                 ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo date('d-m-y',$details->day); ?></td>
                                    <td><?php 
                                             $slots=explode(",",$details->slots);
                                             $t="";
                                             foreach($slots as $var){
                                                 if($var == 1){
                                                     $t=$t."Morning slot".",";
                                                 }elseif($var == 2){
                                                     $t=$t."Afternoon slot".",";
                                                 }
                                             }
                                              $t= substr($t,0,strlen($t)-1);
                                              echo $t;
                                            ?></td>
                                    <td>
                                        <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/view_day_attendance/<?php echo $section->sid ?>/<?php echo date('d-m-y',$details->day) ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/edit_attendance/<?php echo $section->sid ?>/<?php echo date('d-m-y',$details->day) ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    </td>
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
           
         
</div>
<?php
$this->load->view('structure/footer');
?>
