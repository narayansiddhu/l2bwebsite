<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
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
                    <a href="<?php echo base_url(); ?>index.php//Clzattendance/view/">View Settings</a>
                </li>
            </ul>

    </div> 
        <?php
          $att="select s.sid,s.name as sec_name ,c.name as cls_name , ats.*  from section s JOIN class c ON s.class_id=c.id LEFT JOIN attendance_settings ats ON ats.section = s.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."'";
          $att = $this->db->query($att)->result();
          ?>
    <div class="box box-bordered box-color">
        <div class="box-title">
                <h3><i class="fa fa-check"></i>Attendance Settings</h3> 
        </div>
        <div class="box-content nopadding"> 
             <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" >
                 <thead>
                     <tr>
                         <th>Class</th>
                         <th>Section</th>
                         <th>No Of Slots</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                       foreach($att as $val){
                          ?>
                     <tr>
                         <td><?php echo $val->cls_name ?></td>
                         <td><?php echo $val->sec_name ?></td>
                         <td><?php 
                                 if(strlen($val->no_of_times)!=0){
                                     echo $val->no_of_times;
                                 }else{
                                     echo "--";
                                 }
                                 ?>
                         </td>
                         <td><?php 
                                 if(strlen($val->no_of_times)!=0){
                                     ?>
                             <a href="<?php echo base_url();  ?>index.php/Clzattendance/configure/<?php echo $val->aid  ?>"  rel="tooltip" data-original-title="Change  Settings"  ><i class="fa fa-cogs" aria-hidden="true"></i></a>
                                    <a href="<?php echo base_url();  ?>index.php/Clzattendance/view_attendance/<?php echo $val->sid  ?>"  rel="tooltip" data-original-title="View Attendance list"  ><i class="fa fa-eye" aria-hidden="true"></i></a>
                               
                                     <?php
                                 }else{
                                    ?>
                                    <input style=" float: left; width: 80%;" type="text" name="slots_<?php echo $val->sid ?>" id="slots_<?php echo $val->sid ?>" placeholder="Enter No Of Slots" class="form-control"  />    
                                    <button onclick="create_settings(<?php echo $val->sid ?>);" class="btn btn-primary" style=" float: left; height: 34px; padding-top: 5px;" ><i class="fa fa-plus"></i></button>
                                    <span id="error_<?php echo $val->sid ?>" style=" color: red;" ></span>
                                          <?php
                                 }
                                 ?>
                         </td>
                     </tr>
                          <?php 
                       }
                     ?>
                 </tbody>
             </table>
        </div>
    </div>
            <script>
                function create_settings(val){
                    slots=$('#slots_'+val).val();
                    setState('error_'+val,'<?php echo base_url() ?>index.php/Clzattendance/create','section='+val+'&noof_times='+slots);
                   }
                </script>
            
            
        </div>
    </div>
</div>
   
<?php
$this->load->view('structure/footer');
?>
