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
                            <a href="<?php echo base_url(); ?>index.php/transport/view/">Manage Routes</a>
                        </li>
                        <li>
                            <a href="">View Route</a>
                        </li>
                    </ul>
            </div>
            <?php
            $trips="SELECT t.* , v.vech_id ,v.vech_no , d.name , d.phone , d.email  from trips t JOIN vehicles v ON t.vechile_id = v.vech_id JOIN staff d ON t.driver=d.id where t.route_id ='".$route->route_id."' ";
            $trips = $this->db->query($trips)->result();
            ?>
                       <br/>
            <div class="box  box-bordered">
                <div class="box-title">
                        <h3>
                            <i class="fa fa-road"></i>Details Of <?php  echo strtoupper($route->rname) ?>
                        </h3>
                        <ul class="tabs">
                            <?php
                             $i=1;
                             foreach ($trips as $value) {
                                ?>
                                <li <?php
                                    if($i==1){
                                        echo 'class="active"';
                                    }
                                    ?>  >
                                        <a href="#<?php echo $value->trip_id ?>" data-toggle="tab">Trip - <?php echo $value->val ?></a>
                                </li>
                                <?php 
                                $i++;
                             }
                            ?>
                            
                        </ul>
                </div>
                <div class="box-content nopadding">
                        <div class="tab-content">
                            <?php
                             $i=1;
                             foreach ($trips as $value) {
                                ?>
                            <div class="tab-pane <?php
                                    if($i==1){
                                        echo 'active';
                                    }
                                    ?>" id="<?php echo $value->trip_id ?>">
                                 <div class="col-sm-12 nopadding">
                                    <div class="col-sm-4">
                                        <h4 style=" margin: 0px; padding-top: 15px;  width: 80%; color: #66cc00   ">Trip Details :</h4><br/>
                                        <div class="box">
                                            <div class="col-sm-9 nopadding">
                                            <table class="table table-bordered table-striped" style="border: 1px solid #999999; padding: 0px; margin: 0px;  width: 100%" >
                                            <tr>
                                                <td>Vehicle</td>
                                                <td><?php echo $value->vech_no ?></td>
                                            </tr>
                                            <tr>
                                                <td>Driver </td>
                                                <td><?php echo $value->name ?></td>
                                            </tr>
                                            <tr>
                                                <td>Phone </td>
                                                <td><?php echo $value->phone ?></td>
                                            </tr>
                                            <tr>
                                                <td>Fee Amount </td>
                                                <td><?php echo $value->fee ?></td>
                                            </tr>
                                        </table> 
                                            </div>
                                            <div class="col-sm-3" style="  padding-left: 5px">
                                                &nbsp;
                                                <div class="box" style=" padding-left: 5px">
                                                    <a  target="_blank" class="new_title" href="<?php echo base_url(); ?>index.php/transport/print_tripdetails/<?php echo $value->trip_id   ?>"  >
                                                        <i class="fa fa-print fa-2x"></i>
                                                        <p>Print</p>
                                                    </a>
                                                    <a  class="new_title" href="<?php echo base_url(); ?>index.php/transport/send_sms/<?php echo $value->trip_id   ?>"  >
                                                        <i class="fa fa-comments-o fa-2x"></i>
                                                        <p>SMS</p>
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                          
                                        <h4 style=" clear: both; margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Route Details :</h4><br/>
                                        <?php
                                            $route_map =$this->db->query( "SELECT * FROM `trip_route` where trip = '".$value->trip_id."' ");
                                            $route_map = $route_map->result();
                                            ?>
                                        <table class="table table-bordered table-striped" style="border: 1px solid #999999; padding: 0px; margin: 0px;  width: 100%" >
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Pick-up Point</th>
                                                    <th>Pick-Time</th>
                                                    <th>Drop-Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $j=1;
                                            foreach($route_map as $rou){
                                             ?>
                                                <tr>
                                                    <td><?php echo $j++ ?></td>
                                                    <td><?php echo $rou->pickup_point ?></td>
                                                    <td><?php echo $rou->pick_up ?></td>
                                                    <td><?php echo $rou->drop ?></td>
                                                </tr>   
                                              <?php
                                            }
                                                ?>
                                            </tbody>
                                        </table>
                                          <br/><br/>
                                    </div>
                                    <div class="col-sm-8">
                                        <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Student Details :</h4><br/>
                                        <table class="table table-bordered datatable table-striped" style="border: 1px solid #999999; padding: 0px; margin: 0px;  width: 100%" >
                                            <thead>
                                                <tr>
                                                    <th>Student</th>
                                                    <th>phone</th>
                                                    <th>Fee</th>
                                                    <th>Balance</th>
                                                    <th>Pick Up Point</th>
                                                    <th>pick-up </th>
                                                    <th>Drop</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                              $stud="SELECT st.stid,st.fee_amount , s.student_id ,s.name ,s.phone,s.userid,tr.pickup_point , r.rname , tr.pick_up,tr.drop, (select sum(fee) from transport_fee where student=s.student_id ) as paid FROM stud_transport st JOin student s On st.stud_id= s.student_id JOIN trip_route tr ON tr.trid=st.trip_route_id JOIN trips t ON tr.trip=t.trip_id JOIN routes r On t.route_id = r.route_id where st.iid='".$this->session->userdata('staff_Org_id')."' AND tr.trip='".$value->trip_id."' ";
                                                $stud = $this->db->query($stud)->result();
                                                foreach ($stud as $value) {
                                                   ?>
                                                    <tr>
                                                        <td><?php echo $value->name."<br/>( ".$value->userid.")" ?></td>
                                                        <td><?php echo $value->phone ?></td>
                                                        <td><?php echo $value->fee_amount ?></td>
                                                        <td><?php echo $value->fee_amount-$value->paid ?></td>
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
                                <?php 
                                $i++;
                             }
                            ?>
                        </div>
                </div>
        </div>          
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>