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
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Send SMS Alerts</a>
                        </li>
                    </ul>
            </div>
                        <?php
                if(strlen($this->session->userdata('transAlerts_send_sucess'))>0 ){
                    ?><br/>
                        <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('transAlerts_send_sucess'); ?>
                        </div>
                        <script>
                            $("#successMsg").fadeIn();
                            $("#successMsg").delay(2000).fadeOut();
                       </script>
                   <?php
                    $this->session->unset_userdata('transAlerts_send_sucess');
                }
            ?>
            <div class="box">
                <div class="col-sm-4">
                                        <h4 style=" margin: 0px; padding-top: 15px;  width: 80%; color: #66cc00   ">Trip Details :</h4><br/>
                                        <div class="box">
                                            <table class="table table-bordered table-striped" style="border: 1px solid #999999; padding: 0px; margin: 0px;  width: 100%" >
                                            <tr>
                                                <td>Route</td>
                                                <td><?php echo $trip->rname ?></td>
                                            </tr>
                                            <tr>
                                                <td>Trip</td>
                                                <td>Trip - <?php echo $trip->val ?></td>
                                            </tr>
                                            <tr>
                                                <td>Driver </td>
                                                <td><?php echo $trip->name ?></td>
                                            </tr>
                                            <tr>
                                                <td>Phone </td>
                                                <td><?php echo $trip->phone ?></td>
                                            </tr>
                                            <tr>
                                                <td>Fee Amount </td>
                                                <td><?php echo $trip->fee ?></td>
                                            </tr>
                                        </table> 
                                            
                                        </div>
                                          
                                        <h4 style=" clear: both; margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Route Details :</h4><br/>
                                        <?php
                                    $route_map =$this->db->query( "SELECT *, (select count(*) from stud_transport where trip_route_id= trid ) as stud FROM `trip_route` where trip = '".$trip->trip_id."' ");
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
                <div class="col-sm-8 nopadding">
                    <div class="box">
                        <div class="col-sm-8 nopadding">
                            <div class="box box-bordered box-color ">
                                <div class="box-title">
                                    <h3><i class="fa fa-comments-o "></i>&nbsp;Send SMS</h3>
                                </div>
                               <div class="box-content nopadding">
                                   <form class='form-horizontal form-bordered'  action="<?php echo base_url() ?>index.php/transport/preview_sms" method="post">
                                        <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Mobile No:</label>
                                                <div class="col-sm-10">
                                                    <textarea rows="5" class="form-control" name="mobile" id='mobile' placeholder="Select Stops To Load Contacts" disabled="" style=" resize: none"></textarea>
                                                    <span id="subname_err" style=" color: red">
                                                         <?php echo $this->form->error('exam') ?>   
                                                    </span>        
                                                </div>
                                        </div>
                                       <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Message:</label>
                                                <div class="col-sm-10">
                                                    <textarea name="message" rows="5" style=" resize: none" class="form-control" placeholder="Enter Message content here "  ><?php echo $this->form->value("message") ?></textarea>
                                                    <span id="subname_err" style=" color: red">
                                                         <?php echo $this->form->error('exam') ?>   
                                                    </span>        
                                                </div>
                                        </div>
                                       <div class="form-actions col-sm-offset-4 col-sm-4">
                                           <input type="hidden" name="trip_route_ids" id='trip_route_ids' value="<?php echo $this->form->value("trip_route_ids") ?>" />
                                           <input type="hidden" name='trip_id' value="<?php echo $trip->trip_id ?>" />
                                           <button type="submit" name="submit" class="btn btn-primary"> Preview <i class="fa fa-angle-double-right" aria-hidden="true"></i> </button>
                                       </div>
                                   </form>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 nopadding ">
                            <div class="box"style=" padding-left: 5px">
                                <div class="box box-bordered box-color ">
                                <div class="box-title">
                                    <h3><i class="fa fa-users "></i>&nbsp;contacts</h3>
                                </div>
                               <div class="box-content nopadding">
                                <table class=" table-bordered" style=" width: 100%;">
                                   <?php 
                                    foreach ($route_map as $value) {
                                      ?>
                                    <tr>
                                        <td style=" text-align: center">
                                            <input onclick="load_contacts(<?php echo $value->trid ?>);" type="checkbox" value="<?php echo $value->trid ?>" name="route_<?php echo $value->trid ?>" id="route_<?php echo $value->trid ?>" />
                                        </td>
                                        <td>
                                            &nbsp;    <?php echo $value->pickup_point ?> (<?php echo $value->stud ?>)
                                        </td>
                                    </tr>
                                       <?php                                                
                                    }
                                ?> 
                                </table>
                               </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
<script>
 function load_contacts(id){
    if($('#route_'+id).prop('checked')){
          //sms_grp_id
         ids = $('#trip_route_ids').val();   
         ids = ids+","+id;
         $('#trip_route_ids').val(ids);
        setState('mobile','<?php echo base_url() ?>index.php/transport/load_contacts','id='+$('#route_'+id).val()+'&mobile='+$('#mobile').html());             
      }else{
         ids = $('#trip_route_ids').val();   
        ids= ids.replace( id, " ") ;        
        $('#trip_route_ids').val(ids);
         setState('mobile','<?php echo base_url() ?>index.php/transport/remove_contacts','id='+$('#route_'+id).val()+'&mobile='+$('#mobile').html());             
     
     }
 }
</script>
<?php
$this->load->view('structure/footer');
?>