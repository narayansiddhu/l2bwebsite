<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$route_setting=0;
if( (isset($_GET['route']))&& (isset($_GET['trips'])) && (isset($_GET['stops'])) ){
   $route=$_GET['route'];
   $trips=$_GET['trips'];
   $stops=$_GET['stops'];
   $route_setting=1;
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
                        <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/transport/routes/">Routes</a>
                          <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/transport/routes/">Add Route</a>
                    </li>
                </ul>
            </div>
            
            <?php
              if($route_setting==1){
                  ?>
                    <div class="box ">
                            <div class="box-title">
                                    <h3><i class="fa fa-road"></i>Route Settings of <?php echo $route  ?></h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered'  action="<?php echo base_url() ?>index.php/transport/add_new_route" method="post">
                                    <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                                        <table class="table table-bordered table-striped" style="width: 100% ; margin: 0px">
                                            <thead >
                                                <tr >
                                                    <th style=" text-align: center">Pick Up Points</th>
                                                    <?php
                                                     $i=1;
                                                     for($i=1;$i<=$trips;$i++){
                                                         ?>
                                                          <th style=" text-align: center">Trip - <?php echo $i ?> (Pick Up Time)</th>
                                                         <?php
                                                     }
                                                    ?>
                                                </tr>
                                             
                                            </thead>
                                            <tbody>
                                                <?php
                                                  for($j=1;$j<=$stops;$j++){
                                                      ?>
                                                
                                                <tr>
                                                    <td>
                                                        <input placeholder="enter Stop Name" type="text" name="stop_<?php echo $j ?>" class="form-control"  value="<?php  echo $this->form->value('stop_'.$j); ?>"   />
                                                        <span  style=" color: red"><?php  echo $this->form->error('stop_'.$j); ?></span>
                                                    </td>
                                                    <?php
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <td>
                                                        <input placeholder="Trip - <?php echo $i ?> Pickup Time " type="time"  name="pick_up_<?php echo $j ?>_<?php echo $i ?>" class="form-control"  value="<?php  echo $this->form->value('pick_up_'.$j.'_'.$i); ?>"   />
                                                        <span  style=" color: red"><?php  echo $this->form->error('pick_up_'.$j.'_'.$i); ?></span>
                                                    </td>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                                     <?php
                                                  }
                                                ?>
                                                <tr>
                                                    <th style=" padding-top: 15px;">Pick-Up Trip Ending Time</th>
                                                    <?php
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <th>
                                                        <input placeholder="Trip - <?php echo $i ?> Pick Up ending time  " type="time"  name="pick_upending_<?php echo $i ?>" class="form-control"  value="<?php  echo $this->form->value('drop_'.$i); ?>"   />
                                                        <span  style=" color: red"><?php  echo $this->form->error('drop_'.$i); ?></span>
                                                    </th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                                
                                                <tr>
                                                    <th style=" padding-top: 15px;">Fee Amount</th>
                                                    <?php
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <th>
                                                        <input placeholder="Trip - <?php echo $i ?> Fee Amount " type="text"  name="fee_<?php echo $i ?>" class="form-control"  value="<?php  echo $this->form->value('drop_'.$i); ?>"   />
                                                        <span  style=" color: red"><?php  echo $this->form->error('fee_'.$i); ?></span>
                                                    </th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
       
                                                
                                                <tr>
                                                    <th style=" padding-top: 15px;">Vehicle</th>
                                                    <?php
                                                    $vechiles =$this->db->query ("SELECT * FROM `vehicles` where iid='".$this->session->userdata('staff_Org_id')."' ")->result();
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <th>
                                                        <select style=" width: 100%" class="select2-me" name="vechile_<?php echo $i; ?>" >
                                                            <option value="">Vehicle</option>
                                                            <?php 
                                                                foreach ($vechiles as $value) {
                                                                ?>
                                                         <option value="<?php echo $value->vech_id ?>"
                                                                                                                                  <?php
                                                                  if($this->form->value('vechile_'.$i)==$value->vech_id){
                                                                      echo "  SELECTED=''  ";
                                                                  }
                                                                 ?>
                                                                 
                                                                 ><?php echo $value->vech_no ?></option>
                                                                <?php                                                   
                                                                 }
                                                            ?>
                                                        </select>
                                                        <span  style=" color: red"><?php  echo $this->form->error('vechile_'.$i); ?></span>
                                                    </th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                                
                                                <tr>
                                                    <th style=" padding-top: 15px;">Driver</th>
                                                    <?php
                                                    $driver =$this->db->query ("SELECT * FROM `staff` where iid='".$this->session->userdata('staff_Org_id')."' AND level=0 ")->result();
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <th>
                                                        <select style=" width: 100%" class="select2-me" name="driver_<?php echo $i; ?>" >
                                                            <option value="">Driver</option>
                                                            <?php 
                                                                foreach ($driver as $value) {
                                                                ?>
                                                         <option value="<?php echo $value->id ?>" 
                                                                 <?php
                                                                  if($this->form->value('driver_'.$i)==$value->id){
                                                                      echo "  SELECTED=''  ";
                                                                  }
                                                                 ?>
                                                                 ><?php echo $value->name ?></option>
                                                                <?php                                                   
                                                                 }
                                                            ?>
                                                        </select>
                                                        <span  style=" color: red"><?php  echo $this->form->error('driver_'.$i); ?></span>
                                                    </th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                                
                                                <tr>
                                                    <th style=" padding-top: 15px;">Return Trip Starting Time</th>
                                                    <?php
                                                    $i=1;
                                                        for($i=1;$i<=$trips;$i++){
                                                            ?>
                                                    <th>
                                                        <input placeholder="Trip - <?php echo $i ?> Drop Start Time " type="time"  name="drop_<?php echo $i ?>" class="form-control"  value="<?php  echo $this->form->value('drop_'.$i); ?>"   />
                                                        <span  style=" color: red"><?php  echo $this->form->error('drop_'.$i); ?></span>
                                                    </th>
                                                            <?php
                                                        }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                                        <div class="form-actions col-sm-offset-4 col-sm-4">
                                            <input type="hidden" name="route_name" value="<?php echo $route ?>" />
                                            <input type="hidden" name="trips" value="<?php echo $trips ?>" />
                                            <input type="hidden" name="stops" value="<?php echo $stops ?>" />
                                            <input type="submit" name="submit" value="Create Route" onclick="upload_snapshot();" class="btn btn-primary btn-block" />
                                      </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                <?php
              }else{
                  ?>
            <div class="box ">
                    <div class="box-title">
                            <h3><i class="fa fa-road"></i>Create Route</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <div class='form-horizontal form-bordered'   >
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                           
                           <div class="box" style=" width: 100%; height: auto">
                               
                                <div class="col-sm-4 nopadding" >
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">Route Name<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" id='route_name' name="route_name" placeholder="Enter Route Name" class="form-control" value="<?php echo $this->form->value('route_name') ?>" > 
                                            <span id='route_name_err' style=" color: red">
                                                    <?php
                                                       echo $this->form->error('route_name');   
                                                    ?>
                                                </span>        
                                    </div>
                                </div>   
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">No Of Trips<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" id='trips' name="trips" placeholder="Enter No OF Trips" class="form-control" value="<?php echo $this->form->value('trips') ?>" > 
                                            <span id='trips_err' style=" color: red">
                                                    <?php
                                                        echo $this->form->error('trips')
                                                       ?>
                                                </span>  
                                    </div> 
                               </div>
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">No Of Stops<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" id='stops' name="stops" placeholder="Enter No Of Stops" class="form-control"  value="<?php echo $this->form->value('stops') ?>" > 
                                            <span id='stops_err' style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stops');
                                                       ?>
                                                </span>
                                    </div> 
                                    
                                </div>
                                <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                                    <div class="form-actions col-sm-offset-4 col-sm-4">
                                        <input type="button" name="submit" value="Create Route" onclick="add_route();" class="btn btn-primary btn-block" />
                                    </div>
                                </div>
                            
                                </div>
                            
                           </div>
                        </div>
                    </div>
                
                <script>
                  function add_route(){
                     count=0;
                     route=$('#route_name').val();
                     trips=$('#trips').val();
                     stops=$('#stops').val();
                     if(route.length==0){
                         count++;
                         $('#route_name_err').html("** Please enter Route Name")
                     }
                     if(trips.length==0){
                         count++;
                         $('#trips_err').html("** Please enter Trips");
                     }else{
                         if( !$.isNumeric(trips)){
                         count++;
                         $('#trips_err').html("** Enter Valid No Of Trips");
                         }
                     }
                     if(stops.length==0){
                         count++;
                         $('#stops_err').html("** Please enter No Of Stops")
                     }else{
                         if( !$.isNumeric(trips)){
                         count++;
                         $('#stops_err').html("** Enter Valid No Of stops");
                         }
                     }
                     
                     if(count==0){
                        window.location.href = '<?php echo base_url() ?>index.php/transport/add_routes?route='+route+'&trips='+trips+'&stops='+stops;
                     }
                     
                  }
                </script>
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