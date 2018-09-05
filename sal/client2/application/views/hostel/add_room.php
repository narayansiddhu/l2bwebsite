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
                            <a href="">Add Rooms</a>
                        </li>

                    </ul>
            </div>
                       
            
                <div class="box box-color box-bordered nopadding">
                                <div class="box-title">
                                    <h3>  <i class="fa fa-building-o"></i>&nbsp;Create Room</h3>
                                </div>
                            <div class="box-content nopadding">
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Hostel/save_room" method="post" enctype="multipart/form-data"  >
                            <div class="box"  style=" height: auto"  >
                            <div class="col-sm-4">
                                <div class="form-group1">
                                    <label for="textfield" class="control-label">Block /Building <span style=" float: right ; color: red">*</span></label>
                                    <select class="select2-me" name="block" style=" width: 100%" >
                                        <option value="">Please Select Block Name</option>
                                          <?php
                                 $blocks="SELECT * FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                                 $blocks = $this->db->query($blocks)->result();
                                 $i=1;
                                 foreach ($blocks as  $value) {
                                    ?>
                                        <option value="<?php echo $value->block_id ?>" 
                                                <?php
                                                if($this->form->value('block')==$value->block_id){
                                                    echo ' selected=""  ';
                                                }
                                                ?> ><?php echo $value->block_name ?></option>    
                                    <?php 
                                 }
                                ?>
                                    </select>
                                        <span style=" color: red">
                                                <?php
                                                   echo $this->form->error('block');   
                                                ?>
                                            </span>  
                                </div>
                                
                            </div>
                            <div class="col-sm-4 nopadding">
                                <div class="form-group1">
                                            <label for="textfield" class="control-label ">Room Name /No<span style=" float: right ; color: red">*</span></label>
                                                <div class="form-control" style=" border: none; padding: 0px">
                                                    <input type="text" name="room_name" value="<?php echo $this->form->value("room_name") ?>" class="form-control" />
                                                </div>
                                                <span style=" color: red">
                                                        <?php                                                          
                                                             echo $this->form->error('room_name');   
                                                           ?>
                                                    </span>  
                                    </div>
                            </div>
                            <div class="col-sm-4 ">
                                <div class="form-group1">
                                            <label for="textfield" class="control-label">Capacity<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" name="capacity"  placeholder="Please Enter Room Capacity " class="form-control" value="<?php echo $this->form->value("capacity") ?>" />
                                                <span style=" color: red">
                                                        <?php  echo $this->form->error('capacity'); ?>
                                                    </span> 
                                    </div> 
                                
                            </div>
                            </div>
                                    <div class="box" style=" clear: both">
                                        <br/>
                                        <div class="form-actions col-sm-offset-4 col-sm-4">
                                <input type="submit" name="submit" value="Create Room" class="btn btn-primary btn-block" />
                            </div>
                                    </div>
                                    
                                </form>
                    </div>       
                       
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
