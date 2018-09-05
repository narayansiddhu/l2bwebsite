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
                            <a href="<?php echo base_url(); ?>index.php/timetable/view/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Hostel</a>
                        </li>

                    </ul>
            </div>
                       <br/>
            <div class="box">
                <div class="col-sm-9 nopadding">
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #ff3333">
                           <i class="fa fa-th fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: #ff3333  ">&nbsp;&nbsp;
                              <?php
                                   $query=$this->db->query("SELECT count(*) as total  FROM `hostel_blocks` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $query->total;
                              ?>
                            </strong><br/>
                            <p>Blocks</p>
                        </div>
                    </div>
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #006699">
                            <i class="fa fa-building-o fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color:  #006699">&nbsp;&nbsp;
                              <?php
                                   $query=$this->db->query("SELECT count(*) as total  FROM `hostel_rooms` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $query->total;
                              ?>
                            </strong><br/>
                            <p>Rooms</p>
                        </div>
                    </div>
                    
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: gray">
                            <i class="fa fa-bed fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: gray">&nbsp;&nbsp;
                              <?php
                                   $query=$this->db->query("SELECT sum(capacity) as total  FROM `hostel_rooms` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $cap= $query->total;
                              ?>
                            </strong><br/>
                            <p>Capacity</p>
                        </div>
                    </div>
                    
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #ff00cc">
                            <i class="fa fa-child fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: #ff00cc">&nbsp;&nbsp;
                              <?php
                                   $query=$this->db->query("SELECT count(*) as total  FROM `hostel_students` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $stud= $query->total;
                              ?>
                            </strong><br/>
                            <p>Students</p>
                        </div>
                    </div>
                    
                    
<hr/><br/><br/>
                    <div class="row " style=" height: auto; padding-top: 15px  ">
                        
                        <div class="col-sm-6 nopadding" style=" padding-top: 15px;">
                          <div id="container" style="margin-top: 10px; min-width : 300px; width: 90%; height: 250px; ">
                               <h4 style=" width: 100%; text-align: center; padding-top: 30px; color: red">** Add Records To View Graphical Representation</h4>
                          </div>  
                        </div>
                        <div class="col-sm-6 nopadding" style=" border-left: 1px solid #cccccc ; padding-top: 15px;">
                          <div id="container1" style="margin-top: 10px;  width: 400px; height: 250px;">
                             <h4 style=" width: 100%; text-align: center; padding-top: 30px; color: red">** Add Records To View Graphical Representation</h4>
                            
                            </div>  
                        </div>
                    </div>
                    
                </div>
                <div class="col-sm-3 nopadding">
                    <div class="col-sm-12 ">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Rooms" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-building fa-3x" aria-hidden="true"></i>&nbsp;ROOMS</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Manage_students" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-user fa-3x" aria-hidden="true"></i>&nbsp;Students</h4></a>
                    </div>
                    <div class="col-sm-12 "  style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Fee_structure" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-inr fa-3x" aria-hidden="true"></i>&nbsp;Fee Structure</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Hostel/maintenance" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-cogs fa-3x" aria-hidden="true"></i>&nbsp;maintenance</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Hostel/pay_fee" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-money fa-3x" aria-hidden="true"></i>&nbsp;Fee Payments</h4></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>