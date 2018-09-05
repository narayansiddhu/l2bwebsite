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
                    <a href="">View Logs</a>
                </li>
            </ul>
    </div>   
    
               
    <div class="box box-bordered box-color nopadding">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>View Logs</h3> 
                </div>
                <div class="box-content nopadding">   
                    <?php
                         $cat=  unserialize(log_cat);
                        // echo "<pre>";
                        // print_r($cat);
                         
                        ?>
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width:100%;">
                        <thead>
                            <tr>
                               <th>Related</th>
                                <th>Activity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                               $query=$this->db->query("SELECT * FROM `staff_logs`   WHERE iid='".$this->session->userdata('staff_Org_id')."' AND staff_id='".$this->session->userdata('staff_id')."' ORDER BY time DESC ");
                               $query=$query->result();
                               foreach ($query as $value) {
                                   ?>
                            <tr>
                                <td><?php echo $cat[$value->related] ?></td>
                                <td><?php echo $value->message ?></td>
                                <td><?php echo date("d-m-y H:i",$value->time); ?></td>
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
