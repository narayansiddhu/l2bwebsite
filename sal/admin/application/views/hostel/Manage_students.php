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
                            <a href="">Manage Students</a>
                        </li>
                    </ul>
            </div>
           
                       <?php
            if(strlen($this->session->userdata('hostel_add_Sucess'))>0 ){
                ?>       <br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('hostel_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(5000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('hostel_add_Sucess');
            }
            ?>
            
                <div class="box box-color box-bordered nopadding">
                                <div class="box-title">
                                    <h3>  <i class="fa fa-building-o"></i>&nbsp; Manage Students</h3>
                                    <div class="actions">
                                        <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/Hostel/admit_student"><i class="fa fa-plus"></i>&nbsp;Admit Student</a>
                                    </div>
                                </div>
                            <div class="box-content nopadding">
                                <table class="table table-bordered datatable" style=" width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Student</th>
                                            <th>Admission No</th>
                                            <th>Block</th>
                                            <th>Room</th>
                                            <th>Fee Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $s="SELECT hs.hsid,hs.fee,hr.room , hb.block_name,s.name , s.admission_no,s.userid FROM `hostel_students` hs JOIN hostel_rooms  hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' ";
                                         $s = $this->db->query($s)->result();
                                         $i=1;
                                         foreach ($s as $value) {
                                              ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><?php echo $value->admission_no ?></td>
                                            <td><?php echo $value->block_name ?></td>
                                            <td><?php echo $value->room ?></td>
                                            <td><?php echo $value->fee ?></td>
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
