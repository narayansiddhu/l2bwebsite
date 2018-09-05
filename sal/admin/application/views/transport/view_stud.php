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
                            <a href="">View Students</a>
                        </li>
                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('stu_route_add_Sucess'))>0 ){
                ?>       <br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('stu_route_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('stu_route_add_Sucess');
            }
            ?>
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Student Details</h3> 
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/Transport/assign_route" style=" background-color: white; color: #318ee0"  class="btn btn-primary "><i class="fa fa-plus"></i>&nbsp;Add Student</a>
                                </div>
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Student</th>
                                        <th>phone</th>
                                        <th>Route</th>
                                        <th>Trip</th>
                                        <th>Pick Up Point</th>
                                        <th>pick-up </th>
                                        <th>Drop</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $i=1;
                                    $stud="SELECT st.stid,st.fee_amount , t.val,s.student_id ,s.name ,s.phone,s.userid,tr.pickup_point , r.rname , tr.pick_up,tr.drop FROM stud_transport st JOin student s On st.stud_id= s.student_id JOIN trip_route tr ON tr.trid=st.trip_route_id JOIN trips t ON tr.trip=t.trip_id JOIN routes r On t.route_id = r.route_id where st.iid='".$this->session->userdata('staff_Org_id')."' ";
                                    $stud = $this->db->query($stud)->result();
                                    foreach ($stud as $value) {
                                       ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $value->name."<br/>( ".$value->userid.")" ?></td>
                                        <td><?php echo $value->phone ?></td>
                                        <td><?php echo $value->rname ?></td>
                                        <td>Trip - <?php echo $value->val ?></td>
                                        <td><?php echo $value->pickup_point ?></td>
                                        <td><?php echo $value->pick_up ?></td>
                                        <td><?php echo $value->drop ?></td>
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