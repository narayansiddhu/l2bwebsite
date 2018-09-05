<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style>
    .display_tiles{
     width: 24%; 
     margin: 3px; 
     height: 65px;float: left; border : 1px solid #cccccc;
    }
    .display_tiles .left_part{
        float: left; text-align: center;
        width: 25%; height: 100%;
    }
    .display_tiles .left_part i{
        padding-top: 10px;
        color: white;
    }
    .display_tiles .rigth_part{
        float: left;padding-top: 5px;
        width: 75%;height: 100%;padding-left: 5px;
    }
    .display_tiles .rigth_part p{
        color:  #666666;
    }
    </style>
<?php
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
                            <a href="">Manage Rooms</a>
                        </li>

                    </ul>
            </div>
                <?php
            if(strlen($this->session->userdata('room_add_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('room_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('room_add_Sucess');
            }
            ?>
        
                       <br/>
            <div class="box">
                <div class="pull-right">
                    <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/Hostel/Blocks"><i class="fa fa-plus"></i>&nbsp;Add Blocks</a>
                    <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/Hostel/add_room"><i class="fa fa-plus"></i>&nbsp;Add Rooms</a>
                </div><br style=" clear: both"/>
                <hr style=" clear: both"/>
                <?php
                $blocks="SELECT * FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                $blocks = $this->db->query($blocks)->result();
                if(sizeof($blocks)==0){
                    ?>
                      <br/><br/><br/>
                      <h4 style=" text-align: center; color: red">** Please Create Blocks And Rooms </h4>
                    <?php
                }
                foreach ($blocks as  $value) {
                  $rooms="SELECT h.* , (SELECT count(*) from hostel_students where room_id = h.room_id ) as filled FROM `hostel_rooms` h  WHERE iid='".$this->session->userdata("staff_Org_id")."' AND block_id='".$value->block_id."' " ;
                  $rooms = $this->db->query($rooms)->result();
                  $capacity=0;$students=0;
                    ?>
                      <h4 style=" clear: both; border-bottom: 1px solid #cccccc; color:  #ff9900"><?php echo strtoupper($value->block_name) ?><div style=" text-align: right; float: right">Rooms:<?php echo sizeof($rooms); ?>&nbsp;Capacity :<span id="Capacity_<?php echo $value->block_id ?>" ></span>&nbsp; Students :<span id="students_<?php echo $value->block_id ?>" ></span></div></h4>
                   <?php
                       foreach ($rooms as $room) {
                           //ff6666,00cc99,ffcc33
                           $bg="";
                            if($room->filled==0){
                              $bg ="#ff6666"; 
                           }else if($room->filled==$room->capacity){
                              $bg ="#00cc99"; 
                           }else{
                               $bg ="#ffcc33"; 
                           }
                         ?>
                <div class="col-sm-2"  style=" margin:2px; border-radius:5px; height: 100px; text-align: center; background-color: <?php echo $bg ?>; color: white">
                    <h4 style=" text-align: center" ><i class="fa fa-bed" aria-hidden="true"></i>&nbsp;&nbsp;Room : <?php echo $room->room ?></h4>
                    <strong>Total : <?php echo $room->capacity ?> </strong><br/>
                    <strong>Filled : <?php echo $room->filled ?></strong><br/>
                    <strong>Available : <?php echo $room->capacity-$room->filled ?></strong><br/>
                </div>    
                         <?php
                         $capacity+=$room->capacity;
                         $students+=$room->filled;
                         ?>
                          <script>
                           $('#Capacity_<?php echo $value->block_id ?>').html("<?php echo $capacity ?>");
                           $('#students_<?php echo $value->block_id ?>').html("<?php echo $students ?>");
                          </script>    
                             
                         <?php
                       }
                       
                   
                }
        ?>                                
        
            </div>
                       
                       
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
