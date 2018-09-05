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
                   <a href="<?php echo base_url(); ?>index.php/timetable/view/">Time Table</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/">View Time-table</a>
                </li>
            </ul>

    </div> 
    
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Class Routines</h3> 
                        <div class="actions">
                            <a href="<?php echo base_url() ?>index.php/timetable/create" class="btn btn-primary"><i class="fa fa-plus-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Create New</a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                     <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" >
                         <thead>
                             <tr>
                                 <th>S.no</th>
                                 <th>Class</th>
                                 <th>Section</th>
                                 <th>From time</th>
                                 <th>To time</th>
                                 <th>No of classes</th>
                                 <th>Span</th>
                                 <th>Action</th>
                             </tr>
                         </thead>
                         <tbody>
                            <?php  
                                $timings=$this->db->query("SELECT t.tid,c.name as class,s.name as section,t.start,t.end,t.classes,t.span FROM `timings` t JOIN section s ON t.section=s.sid JOIN class c ON s.class_id=c.id WHERE t.iid='".$this->session->userdata('staff_Org_id')."'");
                               if ($timings->num_rows() > 0) {
                                  $timings=$timings->result();
                                  $i=1;
                                
                                     foreach ($timings as $value) {
                                     ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value->class ?></td>
                                        <td><?php echo $value->section ?></td>
                                        <td><?php echo substr($value->start, 0,strlen($value->start)-2) ." : ".substr($value->start,strlen($value->start)-2) ?></td>
                                        <td><?php echo substr($value->end, 0,strlen($value->end)-2) ." : ".substr($value->end,strlen($value->end)-2) ?></td>
                                        <td><?php echo $value->classes ?></td>
                                        <td><?php echo $value->span ?></td>
                                        <td><a href="<?php  echo base_url() ?>index.php/timetable/view/<?php echo $value->tid ?>" >View/Edit </a></td>
                                    </tr>
                                     <?php
                                  } 
                                  
                                  
                                  
                                }else{
                                    ?>
                             <tr>
                                 <td><?php echo "No Records To Display" ?></td>
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