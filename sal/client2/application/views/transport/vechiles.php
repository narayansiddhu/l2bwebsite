<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium= unserialize(medium);
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
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Manage vehicles</a>
                        </li>
                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('vechile_add_Sucess'))>0 ){
                ?>       <br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('vechile_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('vechile_add_Sucess');
            }
            ?>
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Vehicles Details</h3> 
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/Transport/new_Vehicles" style=" background-color: white; color: #318ee0"  class="btn btn-primary "><i class="fa fa-plus"></i>&nbsp;Add Vehicles</a>
                                </div>
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Vechile no</th>
                                        <th>Fuel</th>
                                        <th>Manufacturer</th>
                                        <th>Rc Details</th>
                                        <th>Insurance Details</th>
                                        <th>Pollution Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $i=1;
                                    $vechile="SELECT * FROM `vehicles` where iid='".$this->session->userdata('staff_Org_id')."' ";
                                    $vechile = $this->db->query($vechile)->result();
                                    foreach ($vechile as $value) {
                                       ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><a href="<?php echo base_url() ?>indexx.php/transport/view_vechile/<?php echo $value->vech_id  ?>"><?php echo strtoupper($value->vech_no) ?></a></td>
                                        <td><?php echo $value->fuel ?></td>
                                        <td><?php echo $value->manufacture ?></td>
                                        <td><?php echo $value->rc_no ."<br/> ( ".date('d-m-y',$value->rc_date).")" ?></td>
                                        <td><?php echo $value->ins_no ."<br/> ( ".date('d-m-y',$value->	ins_date).")" ?></td>
                                        <td><?php echo $value->puc_no ."<br/> ( ".date('d-m-y',$value->puc_date).")" ?></td>
                                        
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