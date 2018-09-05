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
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        
                        <li>
                            <a href="">Exam Schedule</a>
                        </li>
                    </ul>

            </div>
        
        
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                                Examination  Schedule
                        </h3>
                        <div class="actions">
                                <a href="#" class="btn btn-mini content-refresh">
                                        <i class="fa fa-refresh"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-remove">
                                        <i class="fa fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-slideUp">
                                        <i class="fa fa-angle-down"></i>
                                </a>
                        </div>
                </div>
                <div class="box-content nopadding">
                        <div class="statistic-big">
                           <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                   
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Exam</th>
                                        <th>Type</th>
                                        <th>from date</th>
                                        <th>to date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php 

                            $query = $this->db->query("SELECT * FROM `examinations` WHERE `iid`='".$this->session->userdata('staff_Org_id')."' AND status=1  ");
                            if ($query->num_rows() > 0) {
                                $i=1;
                                foreach ($query->result() as $row) {
                                   ?>
                            <tr>
                                <td ><?php echo $i++; ?></td>
                                <td><?php echo $row->exam; ?></td>
                                <td><?php 
                                 switch($row->type){
                                    case 1 : echo "Descriptive";break;
                                    case 2 : echo "Multiple Choice";break;
                                    case 3 : echo "Online";break;
                                 }                            
                            ?></td>
                                <td><?php echo date('d-m-y',$row->startdate); ?></td>
                                <td><?php echo date('d-m-y',$row->enddate); ?></td>
                                <td><a  rel="tooltip" title="" data-original-title="View Exam Schedule" href="<?php echo base_url() ?>/index.php/teachers/exam_schedule/<?php echo $row->id ?>"><i class="fa fa-eye"></i></a></td>
                            </tr>
                                   <?php
                                }
                            }else{
                                ?>
                            <tr>
                                <td colspan="4">No Exam's Created</td>
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
        
</div>
 

<?php
$this->load->view('structure/footer');


//SELECT * FROM `exam` where courseid in (1,7,13)
?>