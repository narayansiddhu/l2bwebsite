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
                    <a href="">Send Alert's</a>
                </li>
            </ul>
        </div>
    <?php
    if(strlen($this->session->userdata('Alerts_send_sucess'))>0 ){
        ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            
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
    
           <div class="row">
               
               <div class="col-sm-6">
                   <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Send SMS</h3> 
                        </div>
                        <div class="box-content nopadding ">  
                            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/send_alerts" method="post" enctype="multipart/form-data"  >
                                
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
                                    <input type="hidden" name="ids" id="sms_grp_id" />
                                    <span id="errors" style=" color: red">
                                                   <?php
                                                       echo $this->form->error('stdob');
                                                   ?>
                                    </span> <br/>
                                    
                                </div>
                            
                            </form>
                        </div>
                       
                   </div>
                   
                   
               </div>
               
               
               
               <div class="col-sm-6">
                   
                   <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Contacts</h3> 
                        </div>
                       <div class="box-content nopadding " style=" height: 400px; overflow-x:  no-display; overflow-y:scroll " > 
                           <div class="row" style="  width: 100%">
                                <div class="col-sm-1">
                                    &nbsp;
                                </div>
                                <div class="col-sm-8">
                                        <div class='form-horizontal form-bordered'  >
                                            <?php
                                              $ids="";
                                              echo $ids=$this->form->value('ids');
                                              $ids = explode(",",$ids);
                                              $ids = array_filter($ids);
                                              
                                              foreach($ids as $value){
                                                  echo $value;
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
                   
               </div>
               
           </div>
 
           
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
