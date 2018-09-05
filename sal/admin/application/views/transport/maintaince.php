<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$trans_reason= unserialize(trans_reason);
                                                    
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
                            <a href="">maintenance</a>
                        </li>
                    </ul>
            </div>
            
            <?php
            if(strlen($this->session->userdata('trans_maintaince_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('trans_maintaince_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('trans_maintaince_Sucess');
            }
            ?>
            <div class="box" style=" height: auto;" >
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered box-color" style=" padding-right:  2px;">
                        <div class="box-title">
                                <h3><i class="fa fa-money"></i>Add Maintenance</h3> 
                        </div>
                        <div class="box-content nopadding">
                            <?php
                               $vech="SELECT * FROM `vehicles` where iid='".$this->session->userdata('staff_Org_id')."' ";
                               $vech = $this->db->query($vech);  
                               $vech =$vech->result();
                            ?>
                            <div class='form-horizontal form-bordered'   >
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Vehicles</label>
                                        <div class="col-sm-9">
                                            <select onchange="load_balance();"  name="vehicle" id="vehicle" class='select2-me' style="width:100%;" >
                                                <option value="">Select A vehicles</option>
                                                 <?php
                                                  foreach($vech as $val){
                                                       ?>
                                                 <option value="<?php echo $val->vech_id ?>" >
                                                     <?php echo $val->vech_no ?>
                                                 </option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>  
                                            <span id='vehicle_err' style=" color: red">

                                            </span>  
                                        </div>
                                </div>
                                <div class="form-group">
                                   <label for="textfield" class="control-label col-sm-3">Type</label>
                                   <div class="col-sm-9">
                                     <Select class=" select2-me" id="type" style=" clear: both; width: 100%;" name="type">
                                                  <option value="">Select maintenance Type</option>
                                                  <?php
                                                    foreach ($trans_reason as $key => $value) {
                                                        ?><option value="<?php echo $key ?>"
                                                                <?php 
                                                                if($this->form->value('type')==$key){
                                                                    echo 'selected="';
                                                                }
                                                                ?>
                                                                ><?php echo $value ?></option><?php
                                                    }
                                                  ?>
                                              </Select> 
                                       <span id='type_err' style=" color: red">

                                            </span>
                                   </div>
                                </div>
                           
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="amount" id="amount" class="form-control"  placeholder="Enter Amount For Maintenance"  />
                                          <span id='amount_err' style=" color: red">

                                            </span>
                                  
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-3">Reason</label>
                                    <div class="col-sm-9">
                                        <textarea name="reason" placeholder="Enter Reason" id="reason" class="form-control" style=" resize: none"></textarea>
                                        <span id='reason_err' style=" color: red">

                                        </span>
                                    </div>
                                </div>
                                <div class="form-actions col-sm-offset-3 col-sm-9">
                                        <input type="button" name="submit" value="Add Maintenance" onclick="Add_Maintenance();" class="btn btn-primary btn-block" />
                                        <span id="errors" style=" color: red" ></span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <script>
                    function Add_Maintenance(){
                      $('#vehicle_err').html('');
                      $('#type_err').html('');
                      $('#reason_err').html('');
                      $('#amount_err').html('');
                      count=0;
                      vechile =$('#vehicle').val(); 
                      vechile=$.trim(vechile);
                          
                      type =$('#type').val(); 
                      type=$.trim(type);
                      
                      reason =$('#reason').val(); 
                      reason=$.trim(reason);
                      
                      amount =$('#amount').val();
                      amount=$.trim(amount); 
                      
                      if(vechile.length==0){
                          count++;
                         $('#vehicle_err').html('** Please select A vechile');
                      }
                       if(type.length==0){
                          count++;
                         $('#type_err').html('** Please select A Reason');
                      }
                       if(amount.length==0){
                          count++;
                         $('#amount_err').html('** Please Enter Amount');
                      }
                       if(reason.length==0){
                          count++;
                         $('#reason_err').html('** Please Enter Reason');
                      }
                      
                      if(count==0){
                       setState('errors','<?php echo base_url() ?>index.php/transport/add_maintainence','vechile='+vechile+'&type='+type+'&amount='+amount+'&reason='+reason);
 
                      }
                      
                    }
                </script>
                <div class="col-sm-8 nopadding">
                    <div class="box box-bordered box-color" style=" padding-left:  15px;">
                        <div class="box-title">
                                <h3><i class="fa fa-money"></i>Maintenance History</h3> 
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered datatable" style=" width: 100%; " > 
                                <thead >
                                    <tr>
                                        <th>S.no</th>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $maint=" SELECT m.*, v.vech_no FROM trans_maintaince m  JOIN vehicles v ON m.vech_id=v.vech_id where m.iid='".$this->session->userdata('staff_Org_id')."' ";
                                    $maint = $this->db->query($maint);
                                     $maint = $maint->result();$i=1;
                                     foreach( $maint as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $val->vech_no ?></td>
                                        <td><?php echo $trans_reason[$val->type] ?></td>
                                        <td><?php echo $val->amount ?></td>
                                        <td><?php echo $val->reason ?></td>
                                        <td><?php echo  date('d-m-y H:i',$val->timestamp);  ?></td>
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
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>