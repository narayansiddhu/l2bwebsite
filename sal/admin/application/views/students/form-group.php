<?php
$this->load->view('structure/header');
?>
<style type="text/css">
    .form-group1{
        height: 65px;
        max-height: 70px;
    }
    .form-group2{
        height: 130px;
        max-height: 140px;
    }
    .fip{
        height: 100%;
    }
</style>
<?php
$this->load->view('structure/js');
?>
<script src="<?php echo assets_path ?>js/webcamjs-master/webcam.min.js" ></script>
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
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/students">Students</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Create Student</a>
                    </li>
                </ul>
            </div>
                <?php
            if(strlen($this->session->userdata('student_add_Sucess'))>0 ){
                ?>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('student_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('student_add_Sucess');
            }
            ?>
    
                    <div class="box ">
                            <div class="box-title">
                                <h3><i class="fa fa-child"></i>Create Student</h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/students/create" method="post" enctype="multipart/form-data"  >
                                    
                                    <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Personal Details :</h4>
                                    <div class="box"  style=" height: auto"  >
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                                <label for="field-1" class="control-label">Name <span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" name="stdname" data-validate="required" data-message-required="value_required" value="<?php echo $this->form->value('stdname') ?>" placeholder=" enter Student name" maxlength="50" />
                                            <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('stdname');
                                                   ?>
                                            </span> 
                                            </div>
                                            <div class="form-group1" >
                                            <label for="field-2" class="control-label">D.O.B<span style=" float: right ; color: red">*</span></label>                        
						        <input type="text" name="stddob" placeholder="Select Date Of Birth" id="stddob" class="form-control datepick" value="<?php echo $this->form->value('stddob') ?>">
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('stddob');
                                                               ?>
                                                        </span>     
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label ">Father<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" maxlength="50" name="stdfather" placeholder="Enter Student Father Name" value="<?php echo $this->form->value('stdfather');  ?>" >
                                            <span style=" color: red">
                                                <?php echo $this->form->error('stdfather'); ?>
                                            </span>
                                            </div>
                                            <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label " style="clear : both" >Gender<span style="color: red">*</span></label>

                                                <select class="select2-me" style=" width: 100%" name="stdsex" class="form-control">
                                                        <option value="">select</option>
                                                        <option <?php if($this->form->value('stdsex') ==1 ){ echo "selected";
                                                             }?> value="1">male</option>
                                                       <option <?php if($this->form->value('stdsex') ==2 ){ echo "selected";
                                                             }?>  value="2">female</option>
                                                    </select>
                                                  <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdsex');
                                                           ?>
                                                  </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                            <label for="field-2" class="control-label">Mother<span style=" float: right ; color: red">*</span></label>
                        
                                                    <input type="text" maxlength="50" class="form-control" name="stdmother" placeholder="Enter Student Mother Name" value="<?php echo $this->form->value('stdmother');  ?>" >
                                                        <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdmother');
                                                        ?>
                                                    </span>
                                            </div>
                                            
                                            <div class="box">
                                                <div class="col-sm-6 nopadding">
                                                    
                                            <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group<span style=" float: right ; color: red">*</span></label>
                                           
                                                 <select name="stbg" id="stbg" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                        <option value="" >Select Blood Group</option> 
                                                    <?php                                       
                                                    $blood_group = unserialize (blood_groups);
                                                   foreach ($blood_group as $key=>$value) {
                                                       ?>
                                                        <option  value="<?php echo $key ?>" <?php
                                                      if($this->form->value('stbg')== $key){
                                                          echo "selected";
                                                      }

                                                     ?>   ><?php echo $value ?></option>
                                                      <?php
                                                   }

                                                  ?>
                                                   </select>
                                                   <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('stbg')
                                                              ?>
                                                       </span>        
                                            
                                       </div> 
                                                </div>
                                                <div class="col-sm-6  ">
                                                    
                                            <div class="form-group1">
                                            <label for="textfield" class="control-label">Caste<span style=" float: right ; color: red">*</span></label>
                                           
                                                 <select name="caste" id="caste" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                        <option value="" >Select Caste</option> 
                                                    <?php                                       
                                                    $Caste_system = unserialize (Caste_system);
                                                   foreach ($Caste_system as $key=>$value) {
                                                       ?>
                                                        <option  value="<?php echo $key ?>" <?php
                                                      if($this->form->value('caste')== $key){
                                                          echo "selected";
                                                      }

                                                     ?>   ><?php echo $value ?></option>
                                                      <?php
                                                   }

                                                  ?>
                                                   </select>
                                                   <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('caste')
                                                              ?>
                                                       </span>        
                                            
                                       </div> 
                                                </div>
                                            </div>
				        </div>
                                    </div><br/><br/>
                                    <div class="box"  style=" height: auto"  ><br/>
                                        <h4 style=" margin: 0px; margin-top: 35px;  width: 100%; color: #66cc00   ">Communication Details :</h4>
                                    
                                        <div class="col-sm-4">
                                            <div class="form-group1">
						<label for="field-2" class="control-label">Mobile No<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" class="form-control" name="stdphone" value="<?php echo $this->form->value('stdphone') ?>" placeholder="enter  Mobile No " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('stdphone');
                                                               ?>
                                                      </span>
					    </div>
                                            <div class="form-group1">
						<label for="field-2" class="control-label">Alternate Mobile</label>
                                                <input type="text" class="form-control" name="prntphone" value="<?php echo $this->form->value('prntphone') ?>" placeholder="enter Alternate Mobile No " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('prntphone');
                                                               ?>
                                                      </span>
					    </div>
                                            
                                            <div class="form-group1">
                                                        <label for="field-2" class="control-label">State<span style=" float: right ; color: red">*</span></label>
                                                        <select style="width: 100%" class="select2-me" name="state"  data-validate="required" id="class_id" 
                                                                    data-message-required="value_required"
                                                                            onchange="return get_district_list(this.value)">
                                                            <option value="">select state </option>
                                                           <?php 
                                                               $st = $this->db->query(" SELECT * FROM `state` ")->result();	

                                                                foreach($st as $row){
                                                                        ?>
                                                                    <option value="<?php echo $row->state_id ?>" <?php if($this->form->value('stdclass')==$row->state_id){
                                                                            echo "selected";
                                                                        }?>  >
                                                                    <?php echo $row->name;?>
                                                                    </option>
                                                                     <?php
                                                                }
                                                              ?>
                                                        </select>
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('state');
                                                               ?>
                                                        </span> 
                                                    
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
                                                <label for="field-1" class="control-label">Student E-mail</label>
                                                <input type="text" class="form-control" name="stdemail" value="<?php echo $this->form->value('stdemail') ?>" placeholder="enter Student e-mail" >
                                                    <span style=" color: red">
                                                        <?php echo $this->form->error('stdemail'); ?>
                                                  </span>

                                            </div>
                                            <div class="form-group1">
                                                <label for="field-1" class="control-label">Parent E-mail</label>
                                                <input type="text" class="form-control" name="prntemail" value="<?php echo $this->form->value('prntemail') ?>" placeholder="enter Parent e-mail" >
                                                    <span style=" color: red">
                                                        <?php echo $this->form->error('prntemail'); ?>
                                                  </span>
                                            </div>
                                            <div class="form-group1">
                                                    <label for="field-2" class="control-label">District<span style=" float: right ; color: red">*</span></label>
                                                    <select name="district" style="width: 100%" class="select2-me"  id="district_selector_holder">
                                                        <option value="">select_State_first</option>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('district');
                                                        ?>
                                                    </span> 
                                                </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group2">
                                                    <label for="field-2" class="control-label">Address<span style=" float: right ; color: red">*</span></label>
                                                    <textarea class="form-control" name="stdaddress" value="" placeholder="Enter Student Address" style=" width: 100%; height: 100px; resize: none" ><?php echo $this->form->value('stdaddress') ?></textarea>
                                                        <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdaddress');
                                                                   ?>
                                                          </span>
                                            </div>
                                            <div class="form-group1" >
						<label for="field-2" class="control-label">Locality<span style=" float: right ; color: red">*</span></label>
                        
							<input type="text" class="form-control" name="Locality" value="<?php echo $this->form->value('Locality') ?>" placeholder="enter Town or Mandal or Area name " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('Locality');
                                                               ?>
                                                      </span>
					    </div>
                                            
				        </div>
                                        
                                    </div>
                                    <div class="box">
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Academic Details :</h4>
                                    <div class="box"  style=" height: auto ; padding-left: 15px;"  >
                                        <div class="col-sm-6 nopadding">
                                            <div class="form-group1">
                                                    <label for="textfield" class="control-label">Medium Of Study<span style=" float: right ; color: red">*</span></label>
                                                    <select  onchange="load_classes();" name="medium" id="medium" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                             <option value="" >Select Medium</option> 
                                                         <?php                                       
                                                         $medium = unserialize (medium);
                                                        foreach ($medium as $key=>$value) {
                                                            ?>
                                                             <option  value="<?php echo $key ?>" <?php
                                                           if($this->form->value('medium')== $key){
                                                               echo "selected";
                                                           }

                                                          ?>   ><?php echo $value ?></option>
                                                           <?php
                                                        }

                                                       ?>
                                                        </select>
                                                        <span id="medium_err" style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('medium')
                                                                   ?>
                                                            </span>       
                                            </div> 
                                            
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Admission No<span style=" float: right ; color: red">*</span></label>
                                                        <input type="text" name="userid" class="form-control" placeholder="Please Enter Admission No" value="<?php echo $this->form->value('userid');  ?>" />
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('userid');
                                                            ?>
                                                        </span> 
					    </div>
                                            
                                            
                                        </div>
                                     <div class="col-sm-6">
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Class-section<span style=" float: right ; color: red">*</span></label>
                                                <select style="width: 100%" class="select2-me" id="stdclass" name="stdclass" >
                                                            <option value="" >Class - Section</option>
                                                             <?php
                                                             $medium =  $this->form->value("medium");
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.medium='".$medium."'  ORDER BY c.id ";
            $query = $this->db->query($query);
            ?>
                <option value="">Select A Class-section</option>
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->id."-".$val->sid ?>" <?php
                if($this->form->value('stdclass')==($val->id."-".$val->sid) ){
                    echo ' SELECTED="" ' ;
                }
                ?> >
                    <?php echo $val->cls_name." -".$val->sec_name ?>
                </option>
             <?php
            }
                                                             ?>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdclass');
                                                           ?>
                                                    </span>
					    </div>
                                         <div class="form-group1">
						<label for="field-2" class="control-label">Roll<span style=" float: right ; color: red">*</span></label>
                                                   <input type="text" class="form-control" name="stdroll" placeholder="Enter Student Roll No" value="<?php echo $this->form->value('stdroll');  ?>" >
                                                        <span style=" color: red">
                                                        <?php    echo $this->form->error('stdroll');  ?>
                                                    </span>
					</div>
                                        </div>   
                                    </div> 
                                           <script>
                                             function load_classes(){
                                                 medium =$('#medium').val();
                                                 $('#medium_err').html("");
                                                 if(medium.length==0){
                                                      $('#medium_err').html("** Please Select Medium Of Study");
                                                  }else{
                                                      //load_class_sec
                                                    setState('stdclass','<?php echo base_url() ?>index.php/students/load_class_sec','medium='+medium);
                                                  }
                                             }
                                           </script> 
                                              <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Document Details :</h4>
                                    <div class="box"  style=" height: auto; padding-left: 15px;"  >
                                        <div class="col-sm-12 nopadding" style=" clear: both">
                                           <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Id Proof<span style=" float: right ; color: red">*</span></label><br/>
                                                <div class="box">
                                                    <div  style=" width:50%; float: left;  margin-right: 10px">
                                                        <select class="select2-me" name="id_proff" style=" width:100%" >
                                                        <option value="" >Select Id Proof</option>
                                                        <option <?php
                                                           if($this->form->value('id_proff')==1){
                                                               echo " selected='' ";
                                                           }
                                                           ?>  value="1" >Aadhar card</option>
                                                        <option <?php
                                                           if($this->form->value('id_proff')==2){
                                                               echo " selected='' ";
                                                           }
                                                           ?> value="2" >Passport</option>
                                                    </select> 
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('id_proff');
                                                        ?>
                                                    </span>
                                                    </div>
                                                    <div  style="width:46%; float: left">
                                                        <input type="text" class="form-control" maxlength="50" name="proffid" placeholder="Enter Proof id No" value="<?php echo $this->form->value('proffid');  ?>" >
                                                        <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('proffid');
                                                        ?>
                                                    </span>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="col-sm-6 nopadding" style=" clear: both">
                                            <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Transfer Certificate</label><br/>
                                                <input type="radio" value="1" name="transfer_cert" <?php 
                                                  if( ($this->form->value('transfer_cert')==1) ){
                                                      echo 'checked=""';
                                                  }
                                                ?>  /> Submitted&nbsp;&nbsp;<input type="radio" <?php 
                                                  if($this->form->value('transfer_cert')==2){
                                                      echo 'checked=""';
                                                  }
                                                ?>  value="2" name="transfer_cert" /> Not-Submitted
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('transfer_cert')
                                                           ?>
                                                    </span>       
                                            </div> 
                                            
                                            <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Caste Certificate</label><br/>
                                                <input type="radio" value="1" name="caste_cert" <?php 
                                                   if( ($this->form->value('caste_cert')==1)){
                                                      echo 'checked=""';
                                                  }
                                                ?>  /> Submitted&nbsp;&nbsp;<input type="radio" <?php 
                                                  if($this->form->value('caste_cert')==2){
                                                      echo 'checked=""';
                                                  }
                                                ?>  value="2" name="caste_cert" /> Not-Submitted
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('caste_cert')
                                                           ?>
                                                    </span>       
                                            </div> 
                                            
                                            
                                        </div>
                                     <div class="col-sm-6">
                                         
                                            <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Bonafide Certificate</label><br/>
                                                <input type="radio" value="1" name="Bonafide" <?php 
                                                  if( ($this->form->value('Bonafide')==1) ){
                                                      echo 'checked=""';
                                                  }
                                                ?>  /> Submitted&nbsp;&nbsp;<input type="radio" <?php 
                                                  if($this->form->value('Bonafide')==2){
                                                      echo 'checked=""';
                                                  }
                                                ?>  value="2" name="Bonafide" /> Not-Submitted
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('Bonafide')
                                                           ?>
                                                    </span>       
                                            </div> 
                                            
                                            <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Income Certificate</label><br/>
                                                <input type="radio" value="1" name="income_cert" <?php 
                                                  if( ($this->form->value('income_cert')==1) ){
                                                      echo 'checked=""';
                                                  }
                                                ?>  /> Submitted&nbsp;&nbsp;<input type="radio" <?php 
                                                  if($this->form->value('income_cert')==2){
                                                      echo 'checked=""';
                                                  }
                                                ?>  value="2" name="income_cert" /> Not-Submitted
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('income_cert')
                                                           ?>
                                                    </span>       
                                            </div> 
                                         
                                        </div>   
                                    </div>
                                            
                                        </div>
                                        
                                        
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Student Image :</h4>
                                            <div class="box"  style=" height: auto"  >
                                                <label for="textfield" class="control-label ">Image</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" onclick="show_snap();" name="image_type" id="snap_chck" value="1"  <?php
                                    if(strlen($this->form->value("image_type"))==0){
                                        echo "checked=''";
                                    }else{
                                        if($this->form->value("image_type")==1){
                                            echo "checked=''";
                                        }
                                    }
                                    ?> /> Cam Snap &nbsp;<input type="checkbox"  onchange="uploads();" name="image_type" id="upload" value="2" <?php 
                                    if($this->form->value("image_type")==2){
                                            echo "checked=''";
                                        }
                                    ?> />Upload Image
                                    <div class="form-group2"  id="snap_shot"  >
                                                       <div style=" width: 100%; height: auto">
                                                            <div id="my_camera" style=" height: 100px; float: left;">    
                                                            </div>
                                                           <button class="btn btn-primary"  onClick="take_snapshot();" style=" color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 80px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-camera fa-3x" aria-hidden="true"></i></button>
                                                            
                                                            <div id="results" style=" height: 130px; float: left;">    
                                                                <?php
                                                                if($this->form->value("image_type")==1){
                                                                    if(strlen($this->session->userdata('student_dummy_img')) >0){
                                                                        ?>
                                                                <img style=" width: 280px; height: 280px;" src="<?php echo assets_path ?>uploads/temp/<?php echo $this->session->userdata('driver_dummy_img')   ?>" />
                                                                      <?php 
                                                                    }
                                                                 
                                                                }
                                                                ?>
                                                            </div>
                                                       </div>
                                                    
                                                        <script language="JavaScript">
                                                                Webcam.set({
                                                                        width: 280,
                                                                        height:280,
                                                                        image_format: 'jpeg',
                                                                        jpeg_quality: 99
                                                                });
                                                                Webcam.attach( '#my_camera' );
                                                        </script>
                                                        
                                                        <script language="JavaScript">
                                                                    function take_snapshot() {
                                                                            // take snapshot and get image data
                                                                            Webcam.snap( function(data_uri) {
                                                                                    document.getElementById('results').innerHTML = 
                                                                                            '<img src="'+data_uri+'"/>';
                                                                                  Webcam.upload( data_uri, '<?php echo base_url() ?>index.php/students/Upload_snap', function(code, text) {
                                                                                    });
                                                                                   
                                                                            } );	
                                                                            
                                                                    }
                                          
                                                            </script>
                                                    
                                    </div> 
                                    <div class="form-group2" id='upload_pic' style=" display: none"  >
                                                        <div class="col-sm-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 250px; height: 200px;"></div>
                                                                    <div>
                                                                            <span class="btn btn-default btn-file">
                                                                                    <span class="fileinput-new">Select image</span>
                                                                            <span class="fileinput-exists">Change</span>
                                                                            <input type="file" name="stdimage">
                                                                            </span>
                                                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                    </div>
                                                            </div>
                                                    </div>
                                                            <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdimage');
                                                                   ?>
                                                          </span>
                                                    </div>
                                    <script>
                                      function show_snap(){
                                           if($('#snap_chck').prop('checked')){
                                                $('#upload').attr('checked', false); 
                                                $('#snap_shot').show();
                                                $('#upload_pic').hide();
                                          }else{
                                             $('#upload').attr('checked', true); 
                                            $('#snap_shot').hide();
                                            $('#upload_pic').show(); 
                                          }
                                      }
                                      
                                      function uploads(){
                                           if($('#upload').prop('checked')){
                                                $('#snap_chck').attr('checked',false); 
                                                $('#snap_shot').hide();
                                               $('#upload_pic').show(); 
                                          }else{
                                             $('#snap_chck').attr('checked',true); 
                                             $('#snap_shot').show();
                                             $('#upload_pic').hide();
                                          }
                                            
                                      }
                                      
                                    </script>
                                            </div> 
                                        </div>
                                    </div>
                                    
                                    <h4 style=" clear: both; margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Other Facilities :</h4>
                                    <div class="box"  style=" height: auto"  >
                                        <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Transportation</label>&nbsp;&nbsp; <input type="checkbox" name="trans_use" value="1" <?php  if(strlen($this->form->value('trans_use'))!=0){
                                                           echo "checked";
                                                       } ?>
                                                       onclick="$('#transportation_block').toggle();"  />
                                        <div class="box" id='transportation_block'  
                                             <?php  if(strlen($this->form->value('trans_use'))!=0){
                                                        //   echo "checked";
                                                       }else{
                                                         echo '  style="display: none" ';
                                                       } 
                                                       
                                                       ?>
                                                >
                                            
                                            <div  class="col-sm-3">
                                                 <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Bus Routes<span style=" float: right ; color: red">*</span></label>
                                                 <select  class="select2-me" style=" width: 100%;" name="bus_route" id='bus_route' onclick="load_trips();" >
                                                     <option value="">Bus Route</option>
                                                     <?php
                                                     $route=$this->db->query("SELECT * from routes where iid='".$this->session->userdata('staff_Org_id')."'");
                                                     $route =$route->result();
                                                     foreach($route as $val){
                                                         ?>
                                                     <option value='<?php echo $val->route_id ?>'   <?php 
                                                       if($this->form->value('bus_route')==$val->route_id){
                                                        echo 'selected=""';   
                                                       }
                                                       ?> ><?php echo $val->rname ?></option>    
                                                         <?php
                                                     }
                                                     ?>
                                                 </select>
                                                 <span id='bus_route_err' style="color:red" ><?php echo $this->form->error('bus_route'); ?></span>
                                            </div>
                                            
                                            <div  class="col-sm-3">
                                                 <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Trips<span style=" float: right ; color: red">*</span></label>
                                                 <select  onchange="load_pickup();" class="select2-me" style=" width: 100%;" name="bus_trip" id='bus_trip' >
                                                     <option value="">Trip</option>
                                                     <?php
                                                   if(strlen($this->form->value('bus_route'))!=0){
                                                       $query = " select * from trips  where iid='".$this->session->userdata('staff_Org_id')."' AND route_id = '".$this->form->value('bus_route')."' ";
                                                        $query = $this->db->query($query);
                                                          $query=$query->result();
                                                            $i=1;
                                                          foreach($query as $val){
                                                              ?>
                                                     <option value="<?php echo $val->trip_id.",".$val->fee ?>"  <?php 
                                                       if($this->form->value('bus_trip')==($val->trip_id.",".$val->fee)){
                                                           echo " SELECTED='' ";
                                                       }
                                                     ?>  >
                                                            Trip - <?php echo $i++ ?>
                                                        </option>
                                                       <?php
                                                   }
                                                   }

                                                     ?>
                                                 </select>
                                                <span id='bus_trip_err' style="color:red" ><?php echo $this->form->error('bus_trip'); ?></span>
                                            
                                            </div>
                                            
                                            <div  class="col-sm-3">
                                                 <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Pick-Up Point<span style=" float: right ; color: red">*</span></label>
                                                 <select  class="select2-me" style=" width: 100%;" name="pickup" id='pickup' >
                                                     <option value="">Bus Route</option>
                                                      <?php
                                                      if(strlen($this->form->value('bus_trip'))!=0){
                                                       $trip=explode(',',$this->form->value('bus_trip'));
                                                        $query = " select * from trip_route where iid='".$this->session->userdata('staff_Org_id')."' AND trip = '".$trip[0]."' ";
                                                        $query = $this->db->query($query);
                                                        ?>
                                                            <option value="">Select A Pick-Up Point</option>
                                                            <?php
                                                        $query=$query->result();
                                                        foreach($query as $val){
                                                            ?>
                                                            <option value="<?php echo $val->trid.",".$val->pick_up ?>" >
                                                                <?php echo $val->pickup_point." ( ".$val->pick_up.")" ?>
                                                            </option>
                                                         <?php
                                                        }
                                                      }
                                                      ?>
                                                 </select>
                                                 <span id='pickup_err' style="color:red" ><?php echo $this->form->error('pickup');  ?></span>
                                            </div>
                                            
                                            <div  class="col-sm-3">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Fee Amount<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" name="trans_fee" id="trans_fee" value="<?php  echo $this->form->value('trans_fee') ?>"  placeholder="Please Enter Transport Fee" class="form-control"  />
                                                <span id='trans_fee_err' style="color:red" ><?php echo $this->form->error('trans_fee');  ?></span>
                                            </div>
                                            
                                            <script>
                                            function load_trips(){
                                                bus_route=$('#bus_route').val();
                                                $('#bus_trip').prop('disabled', true);
                                                $('#bus_route_err').html("");
                                                if(bus_route.length==0){
                                                   $('#bus_route_err').html("** Please select Route");
                                                }else{
                                                    setState('bus_trip','<?php echo base_url() ?>index.php/transport/load_trips','route='+bus_route);
                                                    $('#bus_trip').prop('disabled', false);
                                                }
                                            }
                                            
                                            function load_pickup(){
                                               //bus_trip_err
                                               bus_trip =$('#bus_trip').val();
                                               $('#pickup').prop('disabled', true);
                                               $('#bus_trip_err').html('');
                                               if(bus_trip.length==0){
                                                   $('#bus_trip_err').html('** please select trip');
                                               }else{
                                                    bus_trip=bus_trip.split(',');
                                                    setState('pickup','<?php echo base_url() ?>index.php/transport/load_pickups','trip='+bus_trip[0]);
                                                    $('#pickup').prop('disabled', false);
                                                    $('#trans_fee').val(bus_trip[1]);
                                               }
                                             }
                                            function load_pickup_points(){
                                            }
                                           
                                            </script>
                                            
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>
                                       
                                    
                                    <div class="box"  style=" height: auto"  >
                                        <div class="form-actions col-sm-offset-4 col-sm-4">
                                            <input type="submit" name="submit" value="Create Student" onclick="upload_snapshot();" class="btn btn-primary btn-block" />
                                        </div>
                                    </div>
                                    
                                    
				    
                                
                                </form>
                                
                                
                            </div>
                    </div>
                    
                    <span style=" color : red; float: right">Note : * Mandatory Fields</span>
                    
                <script type="text/javascript">

                    function get_class_sections(class_id) {
                      if(class_id.length!=0){
                         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
                      }       
                    }
                    
                    function get_district_list(state_id) {
                      if(class_id.length!=0){
                         setState('district_selector_holder','<?php echo base_url() ?>index.php/students/load_district','state_id='+state_id); 
                      }       
                    }
                    
                </script>
        </div>
    </div>
</div>
    
<?php
$this->load->view('structure/footer');
?>
