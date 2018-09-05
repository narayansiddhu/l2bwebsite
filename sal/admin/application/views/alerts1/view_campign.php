<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
<div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Alerts">View Alerts</a>
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Alerts">View campaign</a>
                </li>
                
            </ul>
        </div>
            <?php 
             $camp =$this->db->query("SELECT * from msg_senthistory where alert_id='".$alert_details->id."'  ");
             
            ?>
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Alert History </h3> 
                </div>
                <div class="box-content nopadding">
                    <div class='form-horizontal form-bordered' >
                        <div class="col-sm-6 nopadding" style="  border-right: 1px solid #999999">
                            <table class="table table-hover table-nomargin table-bordered">
                                <tr>
                                    <th>Regarding</th>
                                    <td><?php
                                            switch($alert_details->regarding){
                                                    case 1 :echo "Alerts";break;
                                                    case 2 :echo "Results";break;
                                                    case 3 :echo "Attendance";break;
                                                    case 4 :echo "Login Credentials";break;
                                                }
                                            ?></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td><?php echo $alert_details->message ?></td>
                                </tr>
                                <tr>
                                    <th>Posted On</th>
                                    <td><?php echo $alert_details->time ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6 nopadding">
                            <div class="col-sm-12" style=" border-bottom: 1px solid  #999999; text-align: center" ><br/>
                                <h4><strong>Total No Of Messages :<?php echo $camp->num_rows() ?></strong></h4>
                            </div><br/>
                            <div class="col-sm-6" style=" text-align: center; "   >
                                <h4>Sent : <span id='sent_msg'></span></h4>
                            </div>
                            <div class="col-sm-6" style=" text-align: center; color: red" >
                                <h4>In-queue : <span id='queue_msg'  ></span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Alert History </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th >S.no</th>
                                <th >Mobile</th>
                                <th >Message</th>
                                <th >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=1;$in_qu=0;$sent=0;
                                $camp=$camp->result();
                                foreach ($camp as $value) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value->mobile; ?></td>
                                        <td><?php echo str_replace("\n","<br/>",$value->message); ?></td>
                                        <td><?php 
                                                           switch($value->status){
                                                               case 0 : echo "in-queue" ;$in_qu++;
                                                                        break;
                                                               case 1 : echo "sent" ;$sent++;
                                                                        break;
                                                           }
                                              ?></td>

                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>  
                </div>
            </div>
            <script>
             $('#sent_msg').html("<?php echo $sent; ?>");
             $('#queue_msg').html("<?php echo $in_qu ?>")
            </script>
        </div>
    </div>   
</div>

<?php
$this->load->view('structure/footer');
?>


