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
                            <a href="<?php echo base_url(); ?>index.php/transport/view/">Manage Drivers</a>
                        </li>

                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('driver_add_Sucess'))>0 ){
                ?>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('driver_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('driver_add_Sucess');
            }
            ?>
                       <br/>
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Drivers Details</h3> 
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/Transport/new_driver" style=" background-color: white; color: #318ee0"  class="btn btn-primary "><i class="fa fa-user-plus"></i>&nbsp;Add Driver</a>
                                </div>
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>E-mail</th>
                                        <th>Salary</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $drives="SELECT st.*,s.amount  FROM `staff` st  Left JOIN salary s On  st.id=s.staff_id WHERE st.level = 0  AND st.iid='".$this->session->userdata('staff_Org_id')."' ";
                                      $drives = $this->db->query($drives)->result();
                                      $i=1;
                                      foreach ($drives as $value) {
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->phone ?></td>
                                        <td><?php echo $value->email ?></td>
                                        <td><?php echo $value->amount ?></td>
                                        <td><i class="fa fa-eye"></i></td>
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