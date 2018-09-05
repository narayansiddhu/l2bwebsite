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
                            <a href="">View Assignments</a>
                        </li>
                    </ul>

            </div>
        
       <div class="box box-color box-bordered">
            <div class="box-title">
                    <h3>
                            <i class="fa fa-bar-chart-o"></i>
                            Assignments  
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
               <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
               
                    <thead>
                        <tr>
                            <th >S.no</th>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Assignment</th>
                            <th>Assigned On</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query=$this->db->query("SELECT a.message,a.timestamp,a.submission,s.name  as section,c.name as cls_name,sub.subject FROM assignments a JOIN course cr ON a.courseid=cr.cid JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid WHERE a.tch_id='".$this->session->userdata('staff_id')."' ");
                        $query=$query->result();
                        $i=1;
                        foreach ($query as $value) {
                          ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->section."<br/>".$value->cls_name ?></td>
                                <td><?php echo $value->subject ?></td>
                                <td><?php echo $value->message ?></td>
                                <td><?php echo date("d-m-Y",$value->timestamp); ?></td>
                                <td><?php echo date("d-m-Y",$value->submission); ?></td>
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