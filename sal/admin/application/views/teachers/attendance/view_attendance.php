<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
   <div class="col-sm-12 nopadding"> 
       <div class="box nopadding">
       <div class="box-title nopadding">                                    
            <h3>
                <i class="fa fa-list" aria-hidden="true"></i>
                  Attendance of <?php echo $section->class ?> , <?php echo $section->section ?>
            </h3>
           <div class="actions">
               <span class="label label-danger"   >
                   <a class="btn" style=" color: white" href="<?php echo base_url(); ?>index.php/teachers/monthly_attendance/<?php echo $section->sid ?>" >&nbsp;&nbsp;Monthly Report&nbsp;&nbsp;</a>
               </span>
           </div>
       </div>
       </div>
   </div>
    <div class="col-sm-12">
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Add Attendance </h3> 
                </div>
                <div class="box-content nopadding"> 
                  <div class='form-horizontal form-bordered' >
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Day</label>
                        <div class="col-sm-10">
                            <input type="text" id="new_date" placeholder="Place Select date" id="stdob" class="form-control datepick" value="<?php echo $this->form->value('stdob') ?>">
                            <span id="new_date_err" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                          ?>
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="button" id="add" onclick="add_attendance();" name="add" value="Add Attendane" class="btn btn-primary" />
                        <span id="errors" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                       ?>
                            </span> 
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>       

<script>
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
$query=$this->db->query("SELECT * FROM `attendance_date` WHERE section='".$section->sid."' AND ( day >= '".$from."' AND day < '".$to."' ) ORDER BY day DESC ");
$query=$query->result();

?>
    <div class="col-sm-12">
        <div class="box">
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
                                                  
                                                 ?> ><?php echo $i; ?></option>
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
                                    <td>
                                        <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/view_attendance/<?php echo $section->sid ?>/<?php echo $details->id ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/edit_attendance/<?php echo $section->sid ?>/<?php echo $details->id ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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
