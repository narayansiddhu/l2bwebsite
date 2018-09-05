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
                        <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/transport/routes/">Routes</a>
                    </li>
                </ul>
            </div>
            
            <?php
            if(strlen($this->session->userdata('route_add_Sucess'))>0 ){
                ?>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('route_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('route_add_Sucess');
            }
            ?>
            <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-road"></i>Routes</h3> 
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/Transport/add_routes" style=" background-color: white; color: #318ee0"  class="btn btn-primary "><i class="fa fa-plus"></i>&nbsp;Add Route</a>
                                </div>
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>No Of Trips</th>
                                        <th>Pick Up Points</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $routes="SELECT * from routes  WHERE iid='".$this->session->userdata('staff_Org_id')."'  ";
                                      $routes = $this->db->query($routes)->result();
                                      $i=1;
                                      foreach ($routes as $value) {
                                         ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><?php echo $value->rname ?></td>
                                            <td><?php echo $value->trips ?></td>
                                            <td><?php echo $value->stops ?></td>
                                            <td><a href="<?php echo base_url() ?>index.php/Transport/viewroutes/<?php echo $value->route_id ?>"><i class="fa fa-eye"></i></a></td>
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