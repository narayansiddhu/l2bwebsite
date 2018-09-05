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
                            <a href="<?php echo base_url(); ?>index.php/hostel/">Hostel</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">maintenance</a>
                        </li>
                    </ul>
            </div>
            
            <?php
            if(strlen($this->session->userdata('hostel_maintaince_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('hostel_maintaince_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('hostel_maintaince_Sucess');
            }
            ?>
            <div class="box" style=" height: auto;" >
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered box-color" style=" padding-right:  2px;">
                        <div class="box-title">
                                <h3><i class="fa fa-money"></i>Add Maintenance</h3> 
                        </div>
                        <div class="box-content nopadding">
                            
                            <div class='form-horizontal form-bordered'   >
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-3">Block</label>
                                        <div class="col-sm-9">
                                            <select onchange="load_rooms();"  name="block" id="block" class='select2-me' style="width:100%;" >
                                                                                 <option value="">Select Block</option>
                                                                                  <?php
                                                                                  $query = " select * from hostel_blocks where iid='".$this->session->userdata('staff_Org_id')."' ";
                                                                                  $query = $this->db->query($query);
                                                                                    $query=$query->result();
                                                                                    foreach($query as $val){
                                                                                        ?>
                                                                                  <option value="<?php echo $val->block_id ?>" >
                                                                                      <?php echo $val->block_name ?>
                                                                                  </option>
                                                                                     <?php
                                                                                    }
                                                                                  ?>
                                                                             </select> 
                                            <span id='block_err' style=" color: red">

                                            </span>  
                                        </div>
                                </div>
                                <div class="form-group">
                                   <label for="textfield" class="control-label col-sm-3">Room</label>
                                   <div class="col-sm-9">
                                     <select    name="room" id="room" class='select2-me' style="width:100%;" >
                                                                         <option value="">Select Room</option>
                                                                     </select>  
                                       <span id='room_err' style=" color: red">

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

                    function load_rooms(){
                      $('#block_err').html(' ');//cat_err
                         $('#room').prop('disabled', true);
                        block = $('#block').val();  //category    
                        if(block.length==0){
                           $('#block_err').html(' ** please select Block');//cat_err
                         //cls_err
                        }else{
                            setState('room','<?php echo base_url() ?>index.php/Hostel/load_rooms1','block='+block);
                            //setState('Fee_structure','<?php echo base_url() ?>index.php/Hostel/load_fee_structure','block='+block);
                            $('#room').prop('disabled', false); 
                         }
                    }

                    function Add_Maintenance(){
                      $('#block_err').html('');
                      $('#room_err').html('');
                      $('#reason_err').html('');
                      $('#amount_err').html('');
                      count=0;
                      block =$('#block').val(); 
                      block=$.trim(block);
                          
                      room =$('#room').val(); 
                      room=$.trim(room);
                      
                      reason =$('#reason').val(); 
                      reason=$.trim(reason);
                      
                      amount =$('#amount').val();
                      amount=$.trim(amount); 
                      
                      if(block.length==0){
                          count++;
                         $('#block_err').html('** Please select A Block');
                      }
                       if(room.length==0){
                          count++;
                         $('#room_err').html('** Please select A Room');
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
                       setState('errors','<?php echo base_url() ?>index.php/hostel/add_maintainence','block='+block+'&room='+room+'&amount='+amount+'&reason='+reason);
 
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
                                        <th>Block</th>
                                        <th>Room</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                     $maint=" SELECT m.*, h.block_name, r.room  FROM hostel_maintenance m  JOIN hostel_blocks h ON m.block_id=h.block_id JOIN hostel_rooms r ON m.room_id=r.room_id where m.iid='".$this->session->userdata('staff_Org_id')."' ";
                                    $maint = $this->db->query($maint);
                                     $maint = $maint->result();$i=1;
                                     foreach( $maint as $val){
                                         ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $val->block_name ?></td>
                                        <td><?php echo $val->room ?></td>
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