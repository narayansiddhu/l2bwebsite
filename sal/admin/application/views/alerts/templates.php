<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script src="<?php echo assets_path ?>js/selector.js"></script>
<script src="<?php echo assets_path ?>js/unicode.js"></script>
<script src="<?php echo assets_path ?>js/parser.js"></script>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Alerts" >Alerts</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Alerts" >SMS Templates</a>
                        </li>
                    </ul>

            </div>
            <?php
                if(strlen($this->session->userdata('message_template_sucess'))>0 ){
                    ?><br/>
                        <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('message_template_sucess'); ?>
                        </div>
                        <script>
                            $("#successMsg").fadeIn();
                            $("#successMsg").delay(2000).fadeOut();
                       </script>
                   <?php
                    $this->session->unset_userdata('message_template_sucess');
                }
            ?><br/>
        
                       <div class="box">
                           <div  style=" float: left; width: 33%; height: 335px; border: 1px solid #cccccc; border-radius: 8px; margin:2px;">
                               <h4  style=" text-align: center" >Attendance Message Template</h4>
                               <div class="box" style=" padding: 8px;">
                                <?php
                                $att_msg="";
                                  $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='1' ";
                                  $msg_ids = $this->db->query($msg_ids);
                                  if($msg_ids->num_rows()!=0){
                                     $msg_ids=$msg_ids->row();
                                     $att_msg=$msg_ids->msg_content;
                                  }
                                ?>
                                   <span>Message :</span>
                                   <textarea style=" resize: none; clear: both;" rows="5" cols="5"  class="form-control" name="attendance_message" id="attendance_message"><?php echo $att_msg ?></textarea>
                                   <span style=" color: red;" id='att_temp_error'>&nbsp;</span><br/>
                                   <span style=" clear: both; ">Customization :</span>
                               <br/>
                               <button onclick="att_msg_add('name');" class="btn" style=" margin: 3px;" >Name</button>
                               <button onclick="att_msg_add('class_section');" class="btn" style=" margin: 3px;" >Class-section</button>
                               <button onclick="att_msg_add('date');" class="btn" style=" margin: 3px;" >Date</button>
                               <button onclick="att_msg_add('roll_no');" class="btn" style=" margin: 3px;" >Roll No</button>
                               <div class="box" style=" padding-top: 15px; text-align: center">
                                   <button onclick="save_attendace_template();" class="btn btn-primary" >Save Template</button>
                               </div>
                               </div>
                           </div>
                           <script>
                            function save_attendace_template(){
                              attendance_message = $("#attendance_message").val();
                              attendance_message=encodeURIComponent(attendance_message);
                              setState('att_temp_error','<?php echo base_url() ?>index.php/Alerts/save_template/','type=1&message='+attendance_message);
                            }
                            function att_msg_add(va_name){
                                va_name='<#'+va_name+'#>';
                                attendance_message = $("#attendance_message").val();
                                attendance_message=attendance_message+va_name;
                                $("#attendance_message").val(attendance_message);                                
                            }
                           </script>
                           <div  style="float: left; width: 33%; height: 335px; border: 1px solid #cccccc;border-radius: 8px;margin:2px;">
                               <h4  style=" text-align: center">Fee Payment Template</h4>
                               <div class="box" style=" padding: 8px;">
                                <?php
                                $att_msg="";
                                  $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='2' ";
                                  $msg_ids = $this->db->query($msg_ids);
                                  if($msg_ids->num_rows()!=0){
                                     $msg_ids=$msg_ids->row();
                                     $att_msg=$msg_ids->msg_content;
                                  }
                                ?>
                                   <span>Message :</span>
                                   <textarea style=" resize: none; clear: both;" rows="5" cols="5"  class="form-control" name="fee_message" id="fee_message"><?php echo $att_msg ?></textarea>
                                   <span style=" color: red;" id='fee_temp_error'>&nbsp;</span><br/>
                                   <span style=" clear: both; ">Customization :</span>
                               <br/>
                               <button onclick="fee_msg_add('name');" class="btn" style=" margin: 3px;" >Name</button>
                               <button onclick="fee_msg_add('class_section');" class="btn" style=" margin: 3px;" >Class-section</button>
                               <button onclick="fee_msg_add('date_time');" class="btn" style=" margin: 3px;" >Date&time</button>
                               <button onclick="fee_msg_add('roll_no');" class="btn" style=" margin: 3px;" >Roll No</button>
                               <button onclick="fee_msg_add('paid');" class="btn" style=" margin: 3px;" >Paid Amount</button>
                               <button onclick="fee_msg_add('total');" class="btn" style=" margin: 3px;" >Total</button>
                               <button onclick="fee_msg_add('balance');" class="btn" style=" margin: 3px;" >Balance</button>
                               <div class="box" style=" padding-top: 15px; text-align: center">
                                   <button onclick="save_fee_template();" class="btn btn-primary" >Save Template</button>
                               </div>
                               </div>
                           </div>
                           <script>
                            function save_fee_template(){
                              fee_message = $("#fee_message").val();
                              fee_message=encodeURIComponent(fee_message);
                              setState('fee_temp_error','<?php echo base_url() ?>index.php/Alerts/save_template/','type=2&message='+fee_message);
                            }
                            function fee_msg_add(va_name){
                                va_name='<#'+va_name+'#>';
                                fee_message = $("#fee_message").val();
                                fee_message=fee_message+va_name;
                                $("#fee_message").val(fee_message);                                
                            }
                           </script>
                           <div  style="float: left; width: 33%; height: 330px; border: 1px solid #cccccc ;border-radius: 8px; margin:2px;">
                               <h4  style=" text-align: center">Birthday Wishes Template</h4>
                               <?php
                                $att_msg="";
                                  $msg_ids="SELECT * from message_template where iid= '".$this->session->userdata('staff_Org_id')."' AND type='3' ";
                                  $msg_ids = $this->db->query($msg_ids);
                                  if($msg_ids->num_rows()!=0){
                                     $msg_ids=$msg_ids->row();
                                     $att_msg=$msg_ids->msg_content;
                                  }
                                ?>
                               <div class="box" style=" padding: 8px;">
                                   <span>Message :</span>
                                   <textarea style=" resize: none; clear: both;" rows="5" cols="5"  class="form-control" name="bday_message" id="bday_message"><?php echo $att_msg ?></textarea>
                                   <span style=" color: red;" id='bday_temp_error'>&nbsp;</span><br/>
                                   <span style=" clear: both; ">Customization :</span>
                               <br/>
                               <button onclick="bday_msg_Add('name');" class="btn" style=" margin: 3px;" >Name</button>
                               <div class="box" style=" padding-top: 35px; text-align: center">
                                   <button onclick="save_bday_template();" class="btn btn-primary" >Save Template</button>
                               </div>
                               </div>
                               
                                                          <script>
                            function save_bday_template(){
                              bday_message = $("#bday_message").val();
                              bday_message=encodeURIComponent(bday_message);
                              setState('fee_temp_error','<?php echo base_url() ?>index.php/Alerts/save_template/','type=3&message='+bday_message);
                            }
                            function bday_msg_Add(va_name){
                                va_name='<#'+va_name+'#>';
                                bday_message = $("#bday_message").val();
                                bday_message=bday_message+va_name;
                                $("#bday_message").val(bday_message);                                
                            }
                           </script>
                           </div>
                 
                       </div>
                       
        </div>
        
        
    </div>
</div>                       
<?php
$this->load->view('structure/footer');
?>