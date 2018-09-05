<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <?php $this->load->view('attendance/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php//attendance/">Attendance</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php//attendance/view/">View Settings</a>
                </li>
            </ul>

    </div> 
    
    
    
    <div class="col-sm-12">
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Attendance Settings</h3> 
                        <div class="actions">
                            <a class="btn btn-primary" href="<?php echo base_url();  ?>index.php/attendance/add"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Create New</a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                     <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" >
                         <thead>
                             <tr>
                                 <th>S.no</th>
                                 <th>Class</th>
                                 <th>Section</th>
                                 <th>No of Times</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <tbody>
                            <?php  
                            $timings=$this->db->query("SELECT ats.aid,ats.no_of_times,s.sid , s.name as section,c.name as class FROM `attendance_settings` as ats JOIN section s ON ats.section=s.sid JOIN class c ON s.class_id=c.id WHERE ats.iid='".$this->session->userdata('staff_Org_id')."'");
                             if( $timings->num_rows()==0){
                                 ?>
                             <tr><td colspan="5">No Records To display</td></tr>
                              <?php
                             }else{
                                 $timings=$timings->result();
                                 $i=1;
                                 foreach ($timings as $s) {
                                     ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $s->class ?></td>
                                        <td><?php echo $s->section ?></td>
                                        <td><?php echo $s->no_of_times ?></td>
                                        <td><a href="<?php echo base_url();  ?>index.php/attendance/configure/<?php echo $s->aid  ?>"  rel="tooltip" data-original-title="Change  Settings"  ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            <a href="<?php echo base_url();  ?>index.php/attendance/view_attendance/<?php echo $s->sid  ?>"  rel="tooltip" data-original-title="View Attendance list"  ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                  <?php
                                 }
                                 
                                 ?>
                             
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
