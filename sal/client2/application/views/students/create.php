<?php
$this->load->view('structure/header');
?>
<style type="text/css">
    .form-group1{
        height: 70px;
        max-height: 75px;
    }
    .form-group2{
        height: 140px;
        max-height: 150px;
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
                ?><br/>
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
                // print_r($_SESSION);
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
                                                    <div class="form-group1" >
                                                    <label for="field-2" class="control-label">Date Of Birth<span style=" float: right ; color: red">*</span></label>                        
                                                                <input type="text" name="stddob" placeholder="Select Date Of Birth" id="stddob" class="form-control datepick" value="<?php echo $this->form->value('stddob') ?>">
                                                                <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('stddob');
                                                                       ?>
                                                                </span>     
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 nopadding">
                                                    <div class="form-group1" style=" padding-left: 10px" >
                                                    <label for="field-2" class="control-label">Date Of Admission<span style=" float: right ; color: red">*</span></label>                        
                                                        <input type="text" name="admission_date" placeholder="Select Date Of Admission" id="stddob" class="form-control datepick" value="<?php echo $this->form->value('admission_date') ?>">
                                                        <span style=" color: red; font-size: inherit"><?php echo $this->form->error('admission_date');  ?></span>     
                                                    </div>
                                                </div>
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
                                                <div class="col-sm-6  nopadding ">
                                                    
                                                    <div class="form-group1 " style=" padding-left: 10px">
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
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label ">Father<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" maxlength="50" name="stdfather" placeholder="Enter Student Father Name" value="<?php echo $this->form->value('stdfather');  ?>" >
                                            <span style=" color: red">
                                                <?php echo $this->form->error('stdfather'); ?>
                                            </span>
                                            </div>
                                            <div class="box">
                                                <div class="col-sm-6 nopadding">
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
                                                <div class="col-sm-6 nopadding">
                                                    
                                                    <div class="form-group1" style=" padding-left: 10px" >
                                                        <label for="textfield" class="control-label">Religion</label>
<?php
                                                                $Rel= unserialize (Religion);
                                                             
                                                    ?>
                                                             <select name="religion" id="religion" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                                    <option value="" >Select religion</option> 
                                                                <?php                                       
                                                                
                                                               
                                                               foreach ($Rel as $key=>$value) {
                                                                   ?>
                                                                    <option  value="<?php echo $key ?>" <?php
                                                                  if($this->form->value('religion')== $key){
                                                                      echo "selected";
                                                                  }

                                                                 ?>   ><?php echo $value ?></option>
                                                                  <?php
                                                               }

                                                              ?>
                                                               </select>
                                                               <span style=" color: red">
                                                                       <?php
                                                                           echo $this->form->error('religion')
                                                                          ?>
                                                                   </span>        

                                                   </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            <div class="form-group2" >
                                            <label for="field-2" class="control-label">Identification Moles<span style=" float: right ; color: red">*</span></label>
                                                    <textarea class="form-control" name="moles" value="" placeholder="Enter Moles For Identification" style=" width: 100%; height: 100px; resize: none" ><?php echo $this->form->value('moles') ?></textarea>
                                                        <span style=" color: red"><?php  echo $this->form->error('moles'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
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
                                    
                                    <div class="form-group2" 
                                         <?php
                                          if($this->form->value("image_type")==2){
                                              echo "STYLE='display:none'";
                                          }
                                         ?>
                                         id="snap_shot"  >
                                                       <div style=" width: 100%; height: auto">
                                                            <div id="my_camera" style=" height: 50px; float: left;
                                                                <?php
                                                                 if($this->form->value("image_type")==1){
                                                                     if(strlen($this->session->userdata('student_dummy_img')) >0){
                                                                         echo " display:none; ";
                                                                     }
                                                                 }
                                                                 ?>
                                                                 ">    
                                                            </div>
                                                           <button class="btn btn-primary" id="snap_btn"  onClick="take_snapshot();" style="<?php
                                                                 if($this->form->value("image_type")==1){
                                                                     if(strlen($this->session->userdata('student_dummy_img')) >0){
                                                                         echo " display:none; ";
                                                                     }
                                                                 }
                                                                 ?> color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 50px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-camera fa-3x" aria-hidden="true"></i></button>
                                                            <div id="results" style="  height: 50px; float: left;">    
                                                                <?php
                                                                if($this->form->value("image_type")==1){
                                                                    if(strlen($this->session->userdata('student_dummy_img')) >0){
                                                                        ?>
                                                                <img style=" width: 330px; height: 235px;" src="<?php echo assets_path ?>uploads/temp/<?php echo $this->session->userdata('student_dummy_img')   ?>" />
                                                                      <?php 
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                               <button class="btn btn-primary" id="retake_btn"  onClick="retake_snap();" style="
                                                                       <?php
                                                                 if(strlen($this->form->value("image_type"))==0){
                                                                        echo " display:none; ";
                                                                 }else{
                                                                  if($this->form->value("image_type")==1){
                                                                    if(strlen($this->session->userdata('student_dummy_img')) >0){
                                                                      //  echo " display:none; ";
                                                                     }
                                                                 }
                                                                 }
                                                                 ?> color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 50px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-retweet fa-3x" aria-hidden="true"></i></button>
                                                            
                                                       </div>
                                                    
                                                        <script language="JavaScript">
                                                                Webcam.set({
                                                                        width: 330,
                                                                        height:235,
                                                                        image_format: 'jpeg',
                                                                        jpeg_quality: 100
                                                                });
                                                                Webcam.attach( '#my_camera' );
                                                        </script>
                                                        
                                                        <script language="JavaScript">
                                                            function retake_snap(){
                                                                                $('#my_camera').show();
                                                                                $('#results').hide();
                                                                                $('#retake_btn').hide();
                                                                                $('#snap_btn').show();
                                                                
                                                            }
                                                                    function take_snapshot() {
                                                                            // take snapshot and get image data
                                                                            Webcam.snap( function(data_uri) {
                                                                                $('#my_camera').hide();
                                                                                $('#results').show();
                                                                                $('#retake_btn').show();
                                                                                $('#snap_btn').hide();
                                                                                    document.getElementById('results').innerHTML = 
                                                                                            '<img src="'+data_uri+'"/>';
                                                                                  Webcam.upload( data_uri, '<?php echo base_url() ?>index.php/students/Upload_snap', function(code, text) {
                                                                                    });
                                                                                   
                                                                            } );	
                                                                            
                                                                    }
                                          
                                                            </script>
                                                    
                                    </div> 
                                    <div class="form-group2" id='upload_pic' 
                                         <?php
                                          if($this->form->value("image_type")!=2){
                                              echo "STYLE='display:none'";
                                          }
                                         ?>
                                           >
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
                                    </div><br style=" clear: both;" />
                                    <div class="box"  style=" clear: both; height: auto"  >
                                        <h4 style=" margin: 0px; margin-top: 35px;  width: 100%; color: #66cc00   ">Communication Details :</h4>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
						<label for="field-2" class="control-label">Student Mobile<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" class="form-control" name="stdphone" value="<?php echo $this->form->value('stdphone') ?>" placeholder="enter Student Mobile No " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('stdphone');
                                                               ?>
                                                      </span>
					    </div>
                                            <div class="form-group1">
						<label for="field-2" class="control-label">Parent Mobile<span style=" float: right ; color: red">*</span></label>
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
                                                                    <option value="<?php echo $row->state_id ?>"
                                                               <?php if($this->form->value('state')==$row->state_id){
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
                                                <label for="field-1" class="control-label">Parent E-mail<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" class="form-control" name="prntemail" value="<?php echo $this->form->value('prntemail') ?>" placeholder="enter Parent e-mail" >
                                                    <span style=" color: red">
                                                        <?php echo $this->form->error('prntemail'); ?>
                                                  </span>
                                            </div>
                                            <div class="form-group1">
                                                    <label for="field-2" class="control-label">District<span style=" float: right ; color: red">*</span></label>
                                                    <select name="district" style="width: 100%" class="select2-me"  id="district_selector_holder">
                                                        <option value="">select_State_first</option>
                                                        <?php
                                                        if( strlen($this->form->value('state'))!=0){
                                                                     $data =$this->db->query("SELECT id,district FROM `districts` where st_id = '".$this->form->value('state')."' ") ;
                                   $data = $data->result();
                        ?>
                        <option value="">SELECT DISTRICT</option> 
                        <?php
                            foreach ($data as $value) {
                                ?><option value="<?php echo  $value->id ?>" <?php if($this->form->value('district')==$value->id){
                                                                            echo "selected";
                                                                        }?>  ><?php echo $value->district  ?></option><?php
                            }     
                                                       }
                                                        
                                                        ?>
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
                                        </div>
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Document Details :</h4>
                                    <div class="box"  style=" height: auto; padding-left: 15px;"  >
                                        <div class="box">
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
                                        <div class="box" style=" clear: both">
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Transfer Certificate : </label>
                                                    <input type="checkbox" 
                                                           <?php
                                                           if(strlen($this->form->value("transfer_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }
                                                           ?>
                                                            style="padding-bottom: 5px; margin-top: 10px; float: left;" onclick="$('#tc_details').toggle();" class="control-label" name="transfer_cert" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Bonafide Certificate : </label>
                                                    <input type="checkbox"
                                                           <?php
                                                           if(strlen($this->form->value("Bonafide_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }
                                                           ?>
                                                           style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="Bonafide_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Income Certificate : </label>
                                                    <input type="checkbox" <?php
                                                           if(strlen($this->form->value("income_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }
                                                           ?> style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="income_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Caste Certificate : </label>
                                                    <input type="checkbox" <?php
                                                           if(strlen($this->form->value("caste_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }
                                                           ?> style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="caste_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-12 nopadding" id="tc_details" style="
                                                 <?php
                                                           if(strlen($this->form->value("transfer_cert"))!=0 ){
                                                          //  echo '   checked="" ';
                                                           }else{
                                                               echo ' display: none; ';
                                                           }
                                                           ?>
                                                 " >
                                                <label style=" float: left; width: 20%">Transfer Certificate Details : </label>
                                                <div style=" float: left; width: 40%;">
                                                  <input type="text" name="tc_issue_date" placeholder="Tc Issue Date"  class="form-control datepick" />
                                                  <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('tc_issue_date');
                                                        ?>
                                                    </span>
                                                </div>
                                                <div style="margin-left: 5px; float: left; width: 38%;">
                                                
                                                <input type="text" name="tc_serial_no" placeholder="Tc Serial No" class="form-control" />
                                                <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('tc_serial_no');
                                                        ?>
                                                    </span>
                                                </div>
                                           </div>
                                        </div>
                                          
                                    </div>
                                        </div>
                                    </div>
                                    <div class="box">
                                        
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
