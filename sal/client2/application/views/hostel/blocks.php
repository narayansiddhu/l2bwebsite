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
                            <a href="<?php echo base_url(); ?>index.php/Hostel">Hostel</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Hostel/Rooms">Manage Rooms</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Manage Blocks</a>
                        </li>

                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('hostel_block_add_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('hostel_block_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('hostel_block_add_Sucess');
            }
            ?>
            <div class="box">
                <div class="col-sm-6 nopadding">
                    <div class="box box-color box-bordered nopadding">
                                <div class="box-title">
                                    <h3>  <i class="fa fa-building-o"></i>&nbsp;Create Block</h3>
                                </div>
                            <div class="box-content nopadding">
                                <div class="form-horizontal form-bordered">
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-3">Block / Building</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="block_name" name="block_name"  placeholder="Enter Block/Building Name" class="form-control" value=""   >
                                                 <span id="block_name_err" style=" color: red">
                                                        
                                                    </span>       
                                            </div>
                                    </div>
                                        <div class="form-actions col-sm-offset-3 col-sm-4">
                                        <input type="button" name="submit" value="Add Block" onclick="Add_block();" class="btn btn-primary btn-block" />
                                        
                                </div>
                            
                                </div>
                                <script>
                                    function Add_block(){
                                        block_name =$("#block_name").val();
                                       // alert(block_name);
                                         setState('block_name_err','<?php echo base_url() ?>index.php/Hostel/add_block','block_name='+block_name);
                                    }
                                </script>
                            </div>
                    </div>
                </div>
                <div class="col-sm-6 nopadding" >
                    <div class="box box-color box-bordered"  style=" margin-left: 10px">
                                <div class="box-title">
                                    <h3>  <i class="fa fa-building-o"></i>&nbsp;Blocks</h3>
                                </div>
                            <div class="box-content nopadding">
                        <table class="table table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Block</th>
                                    <th>Rooms</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $blocks="SELECT * FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                                 $blocks = $this->db->query($blocks)->result();
                                 $i=1;
                                 foreach ($blocks as  $value) {
                                    ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $value->block_name ?></td>
                                    <td>0</td>
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
</div>
<?php
$this->load->view('structure/footer');
?>
