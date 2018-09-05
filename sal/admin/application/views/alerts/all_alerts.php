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
$pane=$this->form->value('pane');
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
                            <a href="">Send Alerts</a>
                        </li>
                    </ul>

            </div>
            <?php
                if(strlen($this->session->userdata('Alerts_send_sucess'))>0 ){
                    ?><br/>
                        <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('Alerts_send_sucess'); ?>
                        </div>
                        <script>
                            $("#successMsg").fadeIn();
                            $("#successMsg").delay(2000).fadeOut();
                       </script>
                   <?php
                    $this->session->unset_userdata('Alerts_send_sucess');
                }
            ?>
                       <div class="box">
                           <div class="col-sm-9 nopadding">
                               <div class="box box-bordered box-color ">
                <div class="box-title">
                        <h3><i class="fa fa-comments-o" aria-hidden="true"></i>SMS ALERTS</h3>
                        <ul class="tabs">
                                <li class="active">
                                        <a href="#t7" data-toggle="tab">Alerts</a>
                                </li>
                                <li>
                                        <a href="#t8" data-toggle="tab">Results</a>
                                </li>
                                <li>
                                        <a href="#t9" data-toggle="tab">Attendance</a>
                                </li>
                                <li>
                                        <a href="#t10" data-toggle="tab">Fee Alerts</a>
                                </li>
                        </ul>
                </div>
                <div class="box-content nopadding">
                        <div class="tab-content nopadding">
                            <div class="tab-pane <?php 
                                     if(($pane=='t7')||(strlen($pane)==0)){
                                         echo "active";
                                     }
                                   ?> nopadding"   id="t7">
                                <div class="col-sm-6 nopadding" style=" border-right: 1px solid #cccccc"  >                                                
                                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/send_alerts" method="post" enctype="multipart/form-data" style=" padding: 0px;" >

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Mobile Numbers</label>
                                            <div class="col-sm-10">
                                                <textarea disabled=""  class="form-control" name="mobile" id='mobile'  rows="4"  style=" resize: none" ></textarea>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('ids');    ?>
                                                </span>  

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Message</label>
                                            <div class="col-sm-10">
                                                <textarea name="message" maxlength="1500" id="textarea"  onkeypress="if (!isEng){ return change(this,event);} else {return 
            true;}"  rows="4" style="resize:none;" class="form-control"><?php echo $this->form->value('message') ?></textarea>
                                                  <span id="" style=" color: red">
                                                    <?php echo $this->form->error('message');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Unicode</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="unicode" value="2"
                                                       <?php  if(strlen($this->form->value('unicode'))!=0){
                                                           echo "checked";
                                                       } ?>
                                                       onclick="$('#language').toggle();" style=" width: 10%; float: left"   />&nbsp;
                                                <select onchange="changeLang(this.value)" id="language" <?php
                                                if(strlen($this->form->value('language')==0)){
                                                    echo 'style="display:none;"';
                                                }
                                                ?>  name="language"  class="input-small"> 
                                                <option  selected="selected"  value ="0">English</option>
                                                <option  value ="4">Bengali</option>
                                                <option value ="5">Gujarati</option>
                                                <option  value ="1">Hindi</option>
                                                <option  value ="6">Kannada</option>
                                                <option  value ="7">Malayalam</option>
                                                <option  value ="8">Punjabi</option>
                                                <option  value ="3">Tamil</option>
                                                <option  value ="2">Telugu</option>
                                            </select> 
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Schedule Time</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" value="2" name="schedule"  onclick="$('#sttime').toggle();"  style=" width: 10%; float: left" />&nbsp;
                                                <div id="sttime" style="display:none; width: 85%; float: left;">
                                                    <input type="text" name="date" value="<?php echo date("d/m/Y",time()); ?>" id="textfield" style=" width: 50%; float: left " class="form-control datepick"/>
                                                    <span style="width: 5%; float: left">&nbsp;&nbsp;</span>
                                                    <input type="text"  name="time" id="timepicker" style=" width: 25%; float: left" class="form-control timepick"/>
                                                </div>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('time');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit" id="add" onclick="send_alert();"  name="add" value="Send SMS Alert" class="btn btn-primary" />
                                            <input type="hidden" name="ids" id="sms_grp_id" value="<?php echo $this->form->value('ids'); ?>" />
                                            <span id="errors" style=" color: red">
                                                           <?php
                                                               echo $this->form->error('stdob');
                                                           ?>
                                            </span> <br/>

                                        </div>
                                       
                                        <input type="hidden" name="pane" value="t7"  />
                                    </form>
                                </div>

                                <div class="col-sm-6 nopadding " style=" height: 400px; overflow-x:  no-display; overflow-y:scroll;padding: 0px ">    
                                            <div class="row" style="  width: 100%">
                                                 <div class="col-sm-1">
                                                     &nbsp;
                                                 </div>
                                                 <div class="col-sm-8">
                                                         <div class='form-horizontal form-bordered'  >
                                                             <?php
                                                               $ids="";
                                                                $ids=$this->form->value('ids');
                                                               $ids = explode(",",$ids);
                                                               $ids = array_filter($ids);

                                                               foreach($ids as $value){
                                                                   ?>
                                                                 <script>setState('mobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id=<?php  echo $value ?>&mobile='+$('#mobile').html()); </script>
                                                                 <?php
                                                               }
                                                             ?>


                                                             <div class="checkbox">
                                                                 <label>
                                                                     <input onclick="fetch_contacts('staff')" <?php 
                                                                     if (in_array("staff", $ids))
                                                                         {
                                                                         echo 'checked="" ';
                                                                         }
                                                                     ?>id='staff' type="checkbox" name="checkbox"/>
                                                                     Staff (<?php echo ($this->session->userdata("tstaff_count")+$this->session->userdata("staff_count") )  ?>)
                                                                 </label>
                                                             </div>
                                                                 <div class="checkbox">
                                                                     <label>
                                                                         <input  <?php 
                                                                     if (in_array("parent", $ids))
                                                                         {
                                                                         echo 'checked="" ';
                                                                         }
                                                                     ?> onclick="fetch_contacts('parent')"   id='parent' type="checkbox" name="staff" /> 
                                                                         Parents (<?php echo $this->session->userdata("parent_count");   ?>)
                                                                     </label>
                                                                 </div> 
                                                              <?php
                                                                 $q= "SELECT s.sid,s.name as section,c.name as class_name ,( select count(*) from  student st WHERE st.section_id=s.sid)  as std_count FROM `section` s JOIN class c ON s.class_id=c.id  WHERE s.iid='".$this->session->userdata("staff_Org_id")."' ORDER BY c.name";
                                                                 $query=$this->db->query($q);
                                                                 $query=$query->result();
                                                                 foreach($query as $value){
                                                                      ?>
                                                                         <div class="checkbox">
                                                                             <label>
                                                                                 <input <?php 
                                                                     if (in_array('section_<?php echo $value->sid  ?>', $ids))
                                                                         {
                                                                         echo 'checked="" ';
                                                                         }
                                                                     ?> onclick="fetch_contacts('section_<?php echo $value->sid  ?>')" id='section_<?php echo $value->sid  ?>' type="checkbox" name="staff" /> 
                                                                                 <?php echo $value->class_name." - ".$value->section ?> (<?php echo $value->std_count   ?>)
                                                                             </label>
                                                                         </div> 
                                                                      <?php
                                                                 }
                                                              ?>


                                                         </div>
                                                 </div>

                                             </div>
                                </div>

                            </div>
                            
                                <div class="tab-pane <?php 
                                     if($pane=='t8'){
                                         echo "active";
                                     }
                                   ?> nopadding" id="t8">
                                    <?php
                                        $exams=$this->db->query("SELECT * FROM `examinations` where iid='".$this->session->userdata('staff_Org_id')."' AND status=1 ");
                                        $exams = $exams->result();
                                        ?>
                                    
                                        <div class="col-sm-6 nopadding">

                                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/result_msg" method="post" enctype="multipart/form-data"  >
                                                    <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">Exams</label>
                                                        <div class="col-sm-10">
                                                            <select onchange="load_sections();" class="select2-me" name="exam_list" id="exam_list" style=" width: 100%">
                                                                <option value="">Select Exam</option>
                                                                <?php
                                                              foreach ($exams as $value) {
                                                                  ?><option value="<?php echo $value->id ?>" ><?php echo $value->exam;  ?></option><?php 
                                                              }                                            
                                                            ?>
                                                            </select>
                                                            <span id="exam_err" style=" color: red">
                                                                 <?php echo $this->form->error('exam_list');    ?>
                                                            </span>  

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">Sections</label>
                                                        <div class="col-sm-10">
                                                            <div class="form-control" id="section_list" style="width: 100%; min-height: 80px; overflow-y: scroll">

                                                            </div>
                                                              <span id="" style=" color: red">
                                                                <?php echo $this->form->error('ids');    ?>
                                                            </span>  
                                                        </div>
                                                    </div>
                                                    
                                                    <input type="hidden" id="ids_list" name="ids"  />
                                                    <input type="hidden" name="pane" value="t8"  />
                                                    <div class="form-group" id="message_div" style=" display: none">
                                                        <label for="textfield" class="control-label col-sm-2">Message</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="message" maxlength="1500" id="message_text" rows="4" style="resize:none;" class="form-control"><?php echo $this->form->value('message') ?></textarea>
                                                              <span id="" style=" color: red">
                                                                <?php // echo $this->form->error('message');    ?>
                                                            </span>  
                                                        </div>
                                                    </div>

                                   <div class="form-actions col-sm-offset-2 col-sm-10">
                                       <input type="submit" id="add"   name="add" value="Send Results" class="btn btn-primary" />

                                       <span id="errors" style=" color: red">
                                                      <?php
                                                          echo $this->form->error('stdob');
                                                      ?>
                                       </span> <br/>

                                   </div>

                               </form>

                                         </div>
               
                                        <div class="col-sm-6 nopadding">

                                            <div class="form-horizontal form-bordered">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        Dear student13 ,<br/> Your results For Exam :unit 1 is as follows : <br/>
                                                        english : - 89 <br/>
                                                        telugu : - 95 <br/>
                                                        hindi : - 72  <br/>
                                                        science : -  98<br/>
                                                        computer : - 100 <br/>
                                                        Grand Total : 454/ 500
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            
                                <div class="tab-pane <?php 
                                     if($pane=='t9'){
                                         echo "active";
                                     }
                                   ?>" id="t9">
                                    <div class="col-sm-6 nopadding"  style=" border-right: 1px solid #cccccc" >                                                
                                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/send_attendance" method="post" enctype="multipart/form-data" style=" padding: 0px;" >

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Mobile Numbers</label>
                                            <div class="col-sm-10">
                                                <textarea disabled=""  class="form-control" name="attmobile" id='attmobile'  rows="4"  style=" resize: none" ></textarea>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('attmobile');    ?>
                                                </span>  

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Message</label>
                                            <div class="col-sm-10">
                                                <textarea name="attmessage" maxlength="1500" id="attmessage"  onkeypress="if (!isEng){ return change(this,event);} else {return 
            true;}"  rows="4" style="resize:none;" class="form-control"><?php echo $this->form->value('attmessage') ?></textarea>
                                                  <span id="" style=" color: red">
                                                    <?php echo $this->form->error('attmessage');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Unicode</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" id="attunicode" name="attunicode" value="2"
                                                       <?php  if(strlen($this->form->value('attunicode'))!=0){
                                                           echo "checked";
                                                       } ?>
                                                       onclick="$('#uni_block').toggle();$('#attlanguage').toggle();" style=" width: 10%; float: left"   />&nbsp;
                                                
                                                <div class="box" id="uni_block" style=" float: left;  display: none" >
                                            
                                                        <select onchange="changeLang(this.value)" id="attlanguage" <?php
                                                            if(strlen($this->form->value('language')==0)){
                                                                echo 'style="display:none;"';
                                                            }
                                                            ?>  name="attlanguage"  class="input-small"> 
                                                            <option  selected="selected"  value ="0">English</option>
                                                            <option  value ="4">Bengali</option>
                                                            <option value ="5">Gujarati</option>
                                                            <option  value ="1">Hindi</option>
                                                            <option  value ="6">Kannada</option>
                                                            <option  value ="7">Malayalam</option>
                                                            <option  value ="8">Punjabi</option>
                                                            <option  value ="3">Tamil</option>
                                                            <option  value ="2">Telugu</option>
                                                        </select> 

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Customize</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" name="attcustomize" value="2"
                                                       <?php  if(strlen($this->form->value('attcustomize'))!=0){
                                                           echo "checked";
                                                       } ?>
                                                       onclick="$('#att_cust_block').toggle();" style=" width: 10%; float: left"   />&nbsp;
                                                
                                                <div class="box" id="att_cust_block" style=" float: left;  display: none" >
                                            
                                                    <a href="#" onclick="customize_msg_content('name');" class="btn btn-mini">Name</a>&nbsp; 
                                                    <a href="#" onclick="customize_msg_content('roll');" class="btn btn-mini">Roll</a>&nbsp; 
                                                    <a href="#"  onclick="customize_msg_content('userid');" class="btn btn-mini">Userid</a>&nbsp;
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Schedule Time</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" value="2" name="attschedule"  onclick="$('#attsttime').toggle();"  style=" width: 10%; float: left" />&nbsp;
                                                <div id="attsttime" style="display:none; width: 85%; float: left;">
                                                    <input type="text" name="attdate" value="<?php echo date("d/m/Y",time()); ?>" id="textfield" style=" width: 50%; float: left " class="form-control datepick"/>
                                                    <span style="width: 5%; float: left">&nbsp;&nbsp;</span>
                                                    <input type="text"  name="atttime" id="timepicker" style=" width: 25%; float: left" class="form-control timepick"/>
                                                </div>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('atttime');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit"   name="submit" value="Send SMS Alert" class="btn btn-primary" />
                                            <input type="hidden" name="att_section_ids" id="att_section_ids" />
                                            <span id="errors" style=" color: red">
                                                           <?php
                                                               echo $this->form->error('stdob');
                                                           ?>
                                            </span> <br/>

                                        </div>
                                       
                                        <input type="hidden" name="pane" value="t9"  />
                                    </form>
                                </div>

                                <div class="col-sm-6 nopadding " style=" height: 400px; overflow-x:  no-display; overflow-y:scroll;padding: 0px ">    
                                            <div class="row" style="  width: 100%">
                                                 <div class="col-sm-1">
                                                     &nbsp;
                                                 </div>
                                                 <div class="col-sm-10">
                                                         <div class='form-horizontal form-bordered'  >
                                                             <?php
                                                             $date = getdate();
                                                             $sections="SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name , (select count(*) from  attendance_alerts aa  where   aa.section = s.sid  AND aa.day='".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."' ) as alert FROM `section` s JOIN class c ON s.class_id = c.id  where s.iid='".$this->session->userdata('staff_Org_id')."'  ";
                                                            // $sections = "SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name  FROM `section` s JOIN class c ON s.class_id = c.id where s.iid='".$this->session->userdata('staff_Org_id')."'";
                                                           
                                                             $sections = $this->db->query($sections);
                                                             $q="SELECT d.section , group_concat(d.id)as date_id , (SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id IN (group_concat(d.id))  ) as abs_count  FROM `attendance_date` d where d.day ='".mktime(0, 0, 0, $date['mon'], $date['mday'],  $date['year'])."'  GROUP BY d.section  ORDER BY d.section  ";
                                                             if($sections->num_rows()>0){
                                                                 $q = $this->db->query($q);
                                                                 if($q->num_rows()>0){
                                                                    $attendance_list = array();
                                                                    $q = $q->result();
                                                                    foreach($q as $value){
                                                                        $attendance_list[$value->section] = array("date_ids"=>$value->date_id,"count"=>$value->abs_count);
                                                                    }
                                                                    
                                                                    
                                                                    $sections = $sections->result();
                                                                    foreach($sections as $value){
                                                                       ?>
                                                                        <div class="checkbox">
                                                                             <label>
                                                                                 <input <?php 
                                                                     if (in_array('attsection_<?php echo $value->sid  ?>', $ids))
                                                                         {
                                                                         echo 'checked="" ';
                                                                         }
                                                                     ?> <?php 
                                                                                 if(isset($attendance_list[$value->sid])){
                                                                                  //   echo $attendance_list[$value->sid]['count'];
                                                                                     ?>
                                                                                      onclick="fetch_attendance_contacts('<?php echo $value->sid  ?>','<?php echo $attendance_list[$value->sid]['date_ids'] ?>')"
                                                                                     <?php
                                                                                 }                                                                                 
                                                                                 ?>
                                                                                      id='attsection_<?php echo $value->sid  ?>' type="checkbox" name="staff" /> 
                                                                                 <?php echo $value->cls_name." - ".$value->sec_name ?> (<?php 
                                                                                 if(isset($attendance_list[$value->sid])){
                                                                                     echo $attendance_list[$value->sid]['count'];
                                                                                 }                                                                                 
                                                                                 ?>)
                                                                             </label><?php
                                                                                if($value->alert >0){
                                                                                    ?>
                                                                            <span style=" color: red">**</span>
                                                                                   <?php
                                                                                }
                                                                             ?>
                                                                         </div>                                                                        
                                                                       <?php
                                                                    }
                                                                    
                                                                }else{
                                                                         ?>
                                                               <span style=" color: red">** Please Add Attendance To view Report's</span>
                                                                <?php
                                                                }
                                                             }else{
                                                                 ?>
                                                             <span style=" color: red;">No Class Structure Found..</span>
                                                                 <?php
                                                             }
                                                             
                                                             ?>
                                                         </div>
                                                 </div>

                                             </div>
                                </div>
                                </div>
                            
                                <div class="tab-pane <?php 
                                     if($pane=='t10'){
                                         echo "active";
                                     }
                                     ?> nopadding" id="t10">
                                    
                                    <div   style=" width: 50%; float: left; margin-right: 5px; border-right: 1px solid  #cccccc"  >                                                
                                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/fee_alerts_msg" method="post" enctype="multipart/form-data" style=" padding: 0px;" >

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Mobile Numbers</label>
                                            <div class="col-sm-10">
                                                <textarea disabled=""  class="form-control" name="fmobile" id='fmobile'  rows="4"  style=" resize: none" ></textarea>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('ids');    ?>
                                                </span>  

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Message</label>
                                            <div class="col-sm-10">
                                                <textarea name="fmessage" maxlength="1500" id="fmessage"  onkeypress="if (!isEng){ return change(this,event);} else {return 
            true;}"  rows="4" style="resize:none;" class="form-control"><?php echo $this->form->value('fmessage') ?></textarea>
                                                  <span id="" style=" color: red">
                                                    <?php echo $this->form->error('fmessage');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Unicode</label>
                                            <div class="col-sm-10">
                                                <input type="checkbox" id='funicode' name="funicode" value="2"
                                                       <?php  if(strlen($this->form->value('funicode'))!=0){
                                                           echo "checked";
                                                       } ?>
                                                       onclick="$('#flanguage').toggle();" style=" width: 10%; float: left"   />&nbsp;
                                                <select onchange="changeLang(this.value)" id="flanguage" <?php
                                                if(strlen($this->form->value('flanguage')==0)){
                                                    echo 'style="display:none;"';
                                                }
                                                ?>  name="language"  class="input-small"> 
                                                <option  selected="selected"  value ="0">English</option>
                                                <option  value ="4">Bengali</option>
                                                <option value ="5">Gujarati</option>
                                                <option  value ="1">Hindi</option>
                                                <option  value ="6">Kannada</option>
                                                <option  value ="7">Malayalam</option>
                                                <option  value ="8">Punjabi</option>
                                                <option  value ="3">Tamil</option>
                                                <option  value ="2">Telugu</option>
                                            </select> 
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Customize</label>
                                            <div class="col-sm-10">
                                                <div class="box" style=" width: 10%; float: left; text-align: center"  >
                                                
                                                <input type="checkbox" name="feecustomize" value="2"
                                                       <?php  if(strlen($this->form->value('feecustomize'))!=0){
                                                           echo "checked";
                                                           ?>
                                                <script>$('#fee_cust_block').toggle();</script>
                                                           <?php
                                                       } ?>
                                                       onclick="$('#fee_cust_block').toggle();"   />
                                                </div>
                                                <div class="box" id="fee_cust_block" style=" float: left; width: 90%;  padding: 0px;  display: none" >
                                                    <a href="#" onclick="fee_msg_customize('name');" style=" padding: 1px" class="btn btn-small">Name</a>&nbsp; 
                                                    <a href="#" onclick="fee_msg_customize('roll');"  style=" padding: 1px" class="btn btn-small">Roll</a>&nbsp; 
                                                    <a href="#"  onclick="fee_msg_customize('userid');" style=" padding: 1px" class="btn btn-small">Userid</a>&nbsp;
                                                    <a href="#" onclick="fee_msg_customize('class');" style=" padding: 1px" class="btn btn-small">class</a>&nbsp; 
                                                    <a href="#" onclick="fee_msg_customize('total');" style=" padding: 1px" class="btn btn-small">Total</a>&nbsp; 
                                                    <a href="#"  onclick="fee_msg_customize('paid');" style=" padding: 1px" class="btn btn-small">Paid</a>&nbsp;
                                                    <a href="#" onclick="fee_msg_customize('balance');" style=" padding: 1px" class="btn btn-small">Balance</a>&nbsp; 
                                                       
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                          function fee_msg_customize(name){
                                              attmessage ="";
                                                if($('#funicode').prop('checked')){
                                                     $('#flanguage').toggle();  
                                                     $("#funicode").prop("checked", false);
                                                     $('#fmessage').val("");//flanguage
                                                      attmessage="<#"+name+"#> ";            
                                                      $('#fmessage').val(attmessage);
                                                  }else{
                                                      attmessage = $('#fmessage').val();
                                                      attmessage =attmessage+"<#"+name+"#> ";
                                                      $('#fmessage').val(attmessage);
                                                }
      
                                          }
                                        </script>
                                        <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Schedule Time</label>
                                            <div class="col-sm-10" style=" background-color:  #ffffff; min-height: 54px;">
                                                <input type="checkbox" value="2" name="fschedule"  onclick="$('#feetime').toggle();"  style=" width: 10%; float: left" />&nbsp;
                                                <div id="feetime" style="display:none; width: 85%; float: left;">
                                                    <input type="text" name="fdate" value="<?php echo date("d/m/Y",time()); ?>" id="textfield" style=" width: 50%; float: left " class="form-control datepick"/>
                                                    <span style="width: 5%; float: left">&nbsp;&nbsp;</span>
                                                    <input type="text"  name="ftime" id="timepicker" style=" width: 25%; float: left" class="form-control timepick"/>
                                                </div>
                                                <span id="" style=" color: red">
                                                    <?php echo $this->form->error('ftime');    ?>
                                                </span>  
                                            </div>
                                        </div>

                                        <div class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit" id="add" onclick="send_alert();"  name="add" value="Send SMS Alert" class="btn btn-primary" />
                                            <input type="hidden" name="fids" id="fee_section_ids" value="<?php echo $this->form->value('fids'); ?>" />
                                            <span id="errors" style=" color: red">
                                                           <?php
                                                               echo $this->form->error('stdob');
                                                           ?>
                                            </span> <br/>

                                        </div>
                                       
                                        <input type="hidden" name="pane" value="t10"  />
                                        
                                    </form>
                                </div>

                                    <div style=" width: 48%; float: left; height: 430px; margin-left: 5px;  overflow-y:scroll;padding: 0px ">    
                                        <?php
                                          $fids =explode(",",$this->form->value('fids'));
                                          $fids = array_filter($fids);

                                           
                                        ?>
                                        <table class="table table-hover table-nomargin table-bordered"  style='width: 100%' >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="fee_select_all" name="select_all" onclick="fee_ids_all();" /></th>
                                                    <th>Class-Section </th>
                                                    <th>Total</th>
                                                    <th>Collected</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                            <?php
                                              $fees="select s.sid,c.id as cls_id,s.name as sec_name , c.name as cls_name ,(SELECT sum(fee) FROM `fee_class` fc where fc.cls_id=c.id ) as tif,( SELECT sum(amount) FROM `fee_accounts` fa JOIN student st ON fa.student_id=st.student_id WHERE st.section_id =s.sid  ) as total_collected , (select count(*) from student where section_id=s.sid ) as students from section s join class c on s.class_id=c.id  where s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.name DESC ";
                                              $fees = $this->db->query($fees);
                                              if($fees->num_rows()>0){
                                                  $fees =$fees->result();
                                                  $valid_ids ="";
                                                  foreach ($fees as $value) {
                                                           
                                                      if(in_array($value->sid, $fids)){
                                                       ?>
                                                        <script>setState('fmobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id=<?php echo $value->cls_id."_".$value->sid  ?>&mobile='+$('#mobile').html()); </script>
                                                        <?php
                                                      }
                                                      ?>
                                                      <tr >
                                                          <td><input onclick="fetch_fee_mobie('<?php echo $value->sid ?>')" <?php 
                                                           if(is_null($value->tif)){
                                                              echo 'disabled=""'; 
                                                           }else{
                                                               $valid_ids.=$value->sid.",";
                                                           }
                                                            if(in_array($value->sid, $fids)){
                                                               echo  'checked=""'; 
                                                            }
                                                        
                                                           ?>   type="checkbox" id="fee_clsid_<?php echo $value->sid ?>" name="fee_clsid_<?php echo $value->sid ?>" value="<?php echo $value->cls_id."_".$value->sid ?>" /></td>
                                                          <td><?php echo $value->cls_name ." - ".$value->sec_name  ?> (<?php echo $value->students ?>) </td>
                                                          <td style=" text-align: center"><?php echo $value->tif*$value->students ?></td>
                                                          <td style=" text-align: center"><?php 
                                                           if(is_null($value->total_collected)){
                                                               echo 0;
                                                           }else {
                                                           echo $value->total_collected ; } ?></td>
                                                          <td style='color: red'><?php echo ($value->tif*$value->students)-$value->total_collected ?></td>
                                                      </tr>
                                                    <?php
                                                  }

                                              }else{
                                                  ?>
                                                <tr>
                                                    <td colspan='5' style='color: red;  text-align: center' >** No Records Found..</td>
                                                </tr>
                                                  <?php
                                              }
                                            ?>
                                            </tbody>

                                        </table>
                                        <span id="fee_valid_ids" style="display: none" ><?php echo substr( $valid_ids,0,strlen($valid_ids)-1); ?></span>
                                        <script>
                                function fee_ids_all(){
                                    if($('#fee_select_all').prop('checked')){
                                        var ids =$('#fee_valid_ids').html();
                                        $('#fee_section_ids').val(ids);
                                        var ids_array = new Array();
                                        ids_array = ids.split(',');
                                        for(var x=0;x<ids_array.length;x++){
                                            $('#fee_clsid_'+ ids_array[x]).attr('checked', true);
                                            setState('fmobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id='+$('#fee_clsid_'+ ids_array[x]).val()+'&mobile='+$('#fmobile').html());             
   
                                        }
                                    }else{
                                        var ids =$('#fee_valid_ids').html();
                                        $('#fee_section_ids').val("");
                                        var ids_array = new Array();
                                        ids_array = ids.split(',');
                                        for(var x=0;x<ids_array.length;x++){
                                            $('#fee_clsid_'+ ids_array[x]).attr('checked', false);
                                            setState('fmobile','<?php echo base_url() ?>index.php/Alerts/remove_contacts','id='+$('#fee_clsid_'+ ids_array[x]).val()+'&mobile='+$('#fmobile').html());             
   
                                        }
                                    }
                                    
                                }
                                </script>
                                    </div>
                                    
                                    
                                    
                                </div>
                            
                            
                        </div>
                </div>
        </div>
                           </div>
                           
                           <div class="col-sm-3 nopadding" style=" margin-top: 20px;" >
                              <?php
                                $s=$this->db->query("SELECT * from messaging where iid='".$this->session->userdata('staff_Org_id')."'")->row();
                               // print_r($s);
                                $que=$this->db->query("SELECT status,count(*) as total FROM `msg_senthistory` where iid='".$this->session->userdata('staff_Org_id')."' GROUP BY status ")->result();
                                  $inqueue=0;
                                  $sent=0;$total=0;
                                  foreach($que as $v){
                                  $total+=$v->total;
                                      switch($v->status){
                                        case 0 : $inqueue+=$v->total;break;
                                        case 1 : $sent+=$v->total;break;
                                    }  
                                  }
                                  
                                ?>
                               <div class="col-sm-12 ">
                                   <h4 style=" text-align: center"><i class="fa fa-comments-o" aria-hidden="true"></i>&nbsp;SMS Details</h4>
                                   <table class="table table-bordered">
                                      <tr>
                                           <th>Senderid</th>
                                           <th><?php echo $s->senderid ?></th>
                                      </tr>
                                      <tr>
                                           <th>Balance</th>
                                           <th><?php 
                                       $url="http://smslogin.mobi/spanelv2/api.php?username=".$s->username."&password=".$s->password;
                                       $t= file($url);
                                       if(is_numeric($t['0'])){
                                           echo $t['0'];
                                       }else{
                                           echo "0";
                                       }
                                                   
                                                   ?></th>
                                      </tr> 
                                   </table>
                                       <table class="table table-bordered">
                               <tr style="  text-align: center">
                                         <td>Total<hr/><?php echo $total ?></td>
                                   
                                   <td>Sent<hr/><?php echo $sent ?></td>
                                         <td>In-Queue<hr/><?php echo $inqueue ?></td>
                                      </tr>
                                  
                                   </table>
                               </div>
                               <div class="col-sm-12 ">
                                    <a href="<?php echo base_url() ?>index.php/Alerts/history" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-history" aria-hidden="true"></i>&nbsp;SMS History</h4></a>
                                </div>
                               <div class="col-sm-12 " style=" margin-top: 8px;">
                                    <a href="<?php echo base_url() ?>index.php/Alerts/templates" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-sticky-note-o" aria-hidden="true"></i>&nbsp;SMS Templates</h4></a>
                                </div>
                           </div>
                       </div>
                       
           
 
        
        </div>
    </div>
</div>
<script>
function fetch_fee_mobie(id){

  if($('#fee_clsid_'+id).prop('checked')){
          //sms_grp_id
         ids = $('#fee_section_ids').val();   
         ids = ids+","+id;
         $('#fee_section_ids').val(ids);
        setState('fmobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id='+$('#fee_clsid_'+id).val()+'&mobile='+$('#fmobile').html());             
      }else{
         ids = $('#fee_section_ids').val();   
        ids= ids.replace( id, " ") ;        
        $('#fee_section_ids').val(ids);
         setState('fmobile','<?php echo base_url() ?>index.php/Alerts/remove_contacts','id='+$('#fee_clsid_'+id).val()+'&mobile='+$('#fmobile').html());             
     
     }

 
}
</script>
<script>
    function fetch_attendance_contacts(id,att_ids){
      //  alert(id);alert(att_ids);
      if($('#attsection_'+id).prop('checked')){
          //sms_grp_id
         ids = $('#att_section_ids').val();   
         ids = ids+","+id;
         $('#att_section_ids').val(ids);
        setState('attmobile','<?php echo base_url() ?>index.php/Alerts/load_attendance_mobile','att_ids='+att_ids+'&mobile='+$('#attmobile').html()+'&action=add');             
      }else{
         ids = $('#att_section_ids').val();   
        ids= ids.replace( id, " ") ;        
        $('#att_section_ids').val(ids);
        setState('attmobile','<?php echo base_url() ?>index.php/Alerts/load_attendance_mobile','att_ids='+att_ids+'&mobile='+$('#attmobile').html()+'&action=remove');             
      
     }
   }
   
   function customize_msg_content(name){
      attmessage ="";
      if($('#attunicode').prop('checked')){
           $('#uni_block').toggle();$('#attlanguage').toggle();  
           $("#attunicode").prop("checked", false);
           $('#attmessage').val("");
            attmessage="<#"+name+"#> ";            
            $('#attmessage').val(attmessage);
        }else{
            attmessage = $('#attmessage').val();
            attmessage =attmessage+"<#"+name+"#> ";
            $('#attmessage').val(attmessage);
      }
      
    
   }
   
</script>

<script>
   
   function load_sections(){
       exam =$('#exam_list').val();
       $('#exam_err').html("");
       $('#section_list').html("");
       if(exam.length ==0){
           $('#exam_err').html("Please select exam");
       }else{
           setState('section_list','<?php echo base_url() ?>index.php/Alerts/load_sections','exam='+exam);             
       }
   }
   
   function add_message(key){
       message_text = $('#message_text').val();
       message_text = message_text+'#'+key+'#';
       $('#message_text').val(message_text);        
   }

   function fetch_contacts(id){
     if($('#'+id).prop('checked')){
          //sms_grp_id
         ids = $('#sms_grp_id').val();   
         ids = ids+","+id;
         $('#sms_grp_id').val(ids);
         setState('mobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id='+id+'&mobile='+$('#mobile').html());            
     }else{
         ids = $('#sms_grp_id').val();   
        ids= ids.replace( id, " ") ;
        
        $('#sms_grp_id').val(ids);
        setState('mobile','<?php echo base_url() ?>index.php/Alerts/remove_contacts','id='+id+'&mobile='+$('#mobile').html());            
        
     }
   }    
   
</script>

<?php
$this->load->view('structure/footer');
?>
