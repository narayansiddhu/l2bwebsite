<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium= unserialize(medium);
$hfee= "SELECT * FROM `hostel_fee` WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
$hfee = $this->db->query($hfee)->result();
$q="SELECT c.id,c.name  FROM `class` c  WHERE c.iid='".$this->session->userdata('staff_Org_id')." '";
$q = $this->db->query($q);
$q= $q->result();
$cls_array = array();
foreach ($q as $value) {
       $cls_array[$value->id] = array('cls_name'=>$value->name);
}

foreach ($hfee as $value) {
      $cls_array[$value->class_id][$value->block_id] =$value->fee;
}

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
            <?php
                $blocks="SELECT * FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                $blocks = $this->db->query($blocks)->result();
                 if(sizeof($blocks)==0){
                      ?>
                        <br/><br/><br/>
                        <h3 style=" text-align: center; color: red">** Please Create Blocks/rooms to Create Fee Structure</h3>
                      <?php
                 } else{
                     ?>
                         <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3><i class="fa fa-inr"></i>Fee Structure</h3>
                </div>
                <div  class="box-content nopadding">
                    <table class="table table-bordered" style=" width: 100%">
                        <thead>
                            <tr>
                                <th>CLASS</th>
                                <?php
                                   foreach ($blocks as $value) {
                                       ?><th style=" text-align: center"><a href="<?php echo base_url() ?>index.php/Hostel/assign_fee_structure/<?php echo $value->block_id ?>"><i class="fa fa-pencil"></i></a><?php echo strtoupper($value->block_name) ?></th><?php   
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                     foreach ($cls_array as $key => $value) {
                          ?>
                            <tr>
                                <td><?php echo $value['cls_name'] ?></td>
                                <?php
                                   foreach ($blocks as $b) {
                                       ?>
                                <td style=" text-align: center"><?php 
                                if(isset($cls_array[$key][$b->block_id])){
                                    echo $cls_array[$key][$b->block_id];
                                }else{
                                    echo "--";
                                }
                                 ?></td>
                                    <?php   
                                    }
                                ?>
                            </tr> 
                          <?php
                     }
                            ?>
                        </tbody>
                    </table>
                   <?php
                   
                   ?>
                </div>
            </div>  
                    <?php
                 }           
            ?>
                         
            
        
        
        </div>
</div>
</div>
<?php
$this->load->view('structure/footer');
?>
