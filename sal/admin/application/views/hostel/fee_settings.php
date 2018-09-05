<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$hfee= "SELECT * FROM `hostel_fee` WHERE iid='".$this->session->userdata("staff_Org_id")."'  AND block_id='".$block->block_id."' ";
$hfee = $this->db->query($hfee)->result();
$q="SELECT c.id,c.name  FROM `class` c  WHERE c.iid='".$this->session->userdata('staff_Org_id')." '";
$q = $this->db->query($q);
$q= $q->result();
$cls_array = array();
$cls_ids="";
foreach ($q as $value) {
       $cls_array[$value->id] = array('cls_name'=>$value->name);
       $cls_ids.=$value->id.",";
}
$cls_ids =  substr($cls_ids, 0,  strlen($cls_ids)-1);
foreach ($hfee as $value) {
      $cls_array[$value->class_id][$value->block_id] = array("hfid"=>$value->hfid,'amount'=>$value->fee);
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
            
                <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3><i class="fa fa-inr"></i>Fee Structure of <?php echo $block->block_name ?></h3>
                </div>
                <div  class="box-content nopadding">
                    <form action="<?php  echo base_url() ?>index.php/Hostel/save_settings/"  method="post" >
                     <table class="table table-bordered" style=" width: 100%">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Fee Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                     foreach ($cls_array as $key => $value) {
                          ?>
                            <tr>
                                <td><?php echo $value['cls_name'] ?></td>
                                <td><input type="text" name="block_cls_fee_<?php  echo $key ?>" class="form-control" placeholder="Please Enter Fee Amount" 
                                           value="<?php 
                                             if(strlen($this->form->value("block_cls_fee_".$key))>0){
                                                 echo $this->form->value("block_cls_fee_".$key);
                                             }else{
                                                if(isset($cls_array[$key][$block->block_id])){
                                                    echo $cls_array[$key][$block->block_id]['amount'];
                                                }   
                                             }
                                           ?>"
                                           
                                           />
                                <?php
                                if(isset($cls_array[$key][$block->block_id])){
                                  ?>
                                    <input type="hidden" name="old_b_fee_<?php echo $key ?>" value="<?php
                                    echo $cls_array[$key][$block->block_id]['hfid'];?>" />   
                                   <?php
                                } 
                                ?>
                                    <span style=" color: red">
                                        <?php  echo $this->form->error("block_cls_fee_".$key);  ?>
                                    </span>
                                </td>
                            </tr> 
                          <?php
                     }
                            ?>
                        <input type="hidden" name="block" value="<?php echo $block->block_id ?>" />
                        <input type="hidden" name="cls_ids" value="<?php echo $cls_ids ?>" />
                        <tr>
                                <td colspan="2" style=" text-align: center" > 
                                    <button type="submit" name="submit" class="btn btn-primary" >Submit Fee Structure</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                       
                    </form> 
                    
                </div>
            </div>  
                  
        </div>
</div>
</div>
<?php
$this->load->view('structure/footer');
?>
