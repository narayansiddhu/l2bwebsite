<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
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
                    <a href="<?php echo base_url(); ?>index.php/staff">Manage Staff</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/staff/Add_staff/">Create Staff</a>
                </li>
            </ul>
    </div> 
    
    
            <div class="box ">
                <div class="box-title">
                    <h3><i class="fa fa-user"></i>Create Staff</h3> 
                </div>
                <div class="box-content nopadding">                                
                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/staff/add" method="post" enctype="multipart/form-data"  >

                        <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Personal Details :</h4>
                        <div class="box"  style=" height: auto"  >
                            <div class="col-sm-4">
                                <div class="form-group1">
                                    <label for="textfield" class="control-label">Full Name<span style=" float: right ; color: red">*</span></label>
                                    <input type="text" name="stusername" maxlength="50" placeholder="Enter Full Name" class="form-control" value="<?php echo $this->form->value('stusername') ?>" > 
                                        <span style=" color: red">
                                                <?php
                                                   echo $this->form->error('stusername');
                                                ?>
                                            </span>
                                </div>
                                
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                            <label for="textfield" class="control-label ">Gender<span style=" float: right ; color: red">*</span></label>
                                                <div class="form-control" style=" border: none">
                                                    <input type="radio" name="gender" value="1" <?php 
                                                     if($this->form->value('gender') ==1 ){
                                                         echo "checked";
                                                         }?> />
                                                        Male <input  type="radio" name="gender" value="2"  <?php
                                                     if($this->form->value('gender') ==2 ){
                                                         echo "checked";
                                                         }?> /> Female
                                                </div>
                                                
                                                <span style=" color: red">
                                                        <?php                                                          
                                                             echo $this->form->error('gender');   
                                                           ?>
                                                    </span>  
                                    </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group</label>
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
                            
                            <div class="col-sm-4" style=" clear: both">
                                <div class="form-group1">
                                        <label for="textfield" class="control-label">Mobile<span style=" float: right ; color: red">*</span></label>
                                        <input type="text" maxlength="10" name="stmobile" placeholder="Enter Mobile Number" class="form-control" value="<?php echo $this->form->value('stmobile') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stmobile')
                                                       ?>
                                                </span>
                                    </div> 
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                        <label for="textfield"   class="control-label ">E-mail<span style=" float: right ; color: red">*</span></label>
                                        <input type="text" maxlength="30" name="stemail" placeholder="Enter E-mail Id" class="form-control"  value="<?php echo $this->form->value('stemail') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stemail');
                                                       ?>
                                                </span> 
                                    </div> 
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group1">
                                        <label for="textfield" class="control-label">Qualification<span style=" float: right ; color: red">*</span></label>
                                        <input type="text" maxlength="30" name="stqualification" placeholder="Enter Staff Qualification" class="form-control" value="<?php   echo $this->form->value('stqualification'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stqualification');
                                                       ?>
                                                </span>
                                    </div> 
                            </div>
                            
                            <div class="col-sm-4" style=" clear: both">
                                
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label">Date Of Birth<span style=" float: right ; color: red">*</span></label>
                                             <input type="text" name="stdob" placeholder="Select Date Of Birth" id="stdob" class="form-control datepick" value="<?php echo $this->form->value('stdob') ?>">
                                             <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('stdob');
                                                   ?>
                                            </span> 
                                    </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="form-group1">
                                            <label for="textfield" class="control-label">Date Of Joining</label>
                                             <input type="text" name="stdoj" placeholder="Select Date Of Joining" id="stdoj" class="form-control datepick"  value="<?php echo $this->form->value('stdoj') ?>"  >
                                             <span style=" color: red">
                                                 <?php
                                                            echo $this->form->error('stdoj');
                                                           ?>      
                                             </span>
                                    </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="form-group1" >
                                        <label for="textfield" class="control-label">User Level<span style=" float: right ; color: red">*</span></label>
                                                <select name="stlevel" id="stlevel" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                    <option value="">Select Staff Level</option> 
                                                 <?php
                                                $staff_level = unserialize (staff_level);
                                                foreach ($staff_level as $key => $value) {
                                                    if( ($key >0)&&($key <6)){
                                                        ?>
                                                    <option value="<?php echo $key ?>" <?php
                                                      if($this->form->value('stlevel')== $key){
                                                          echo "selected";
                                                      }?> ><?php echo $value ?></option>
                                                   <?php
                                                    }else{
                                                        if($key==-1){
                                                            ?>
                                                            <option value="<?php echo $key ?>" <?php
                                                      if($this->form->value('stlevel')== $key){
                                                          echo "selected";
                                                      }?> ><?php echo $value ?></option>
                                                            <?php
                                                        }
                                                        
                                                    }

                                                 }
                                               ?>
                                                </select>
                                             <span style=" color: red">
                                                 <?php
                                                            echo $this->form->error('stlevel');
                                                           ?>      
                                             </span> 

                                    </div>
                            </div>
                            
                            
                        </div>
                        <div class="box"  style=" clear: both; height: auto"  >
                           <div class="col-sm-6 nopadding"  >
                                <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Professional Details</h4>
                                <div class="box">
                                    <div class="col-sm-6 nopadding" >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Salary<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" name="stsalary" placeholder="Enter Staff Salary" class="form-control" value="<?php   echo $this->form->value('stsalary'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stsalary');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="col-sm-6 " >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Casual Leaves<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" name="Leaves" placeholder="Enter No Of Casual Leaves" class="form-control" value="<?php   echo $this->form->value('Leaves'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('Leaves');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="col-sm-6 nopadding" >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Years Of Experience<span style=" float: right ; color: red">*</span></label>
                                            <input maxlength="2" name="stexperience" placeholder="Enter Years Of Experience" type="number" class="form-control" value="<?php   echo $this->form->value('stexperience'); ?>" >
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stexperience');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="col-sm-6 " >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Bank Account No</label>
                                            <input type="text" maxlength="20" name="account" placeholder="Enter Bank Account No" class="form-control" value="<?php   echo $this->form->value('account'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('account');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="col-sm-6 nopadding" >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">PF No</label>
                                            <input type="text" name="pfno" placeholder="Enter PF NO" class="form-control" value="<?php   echo $this->form->value('pfno'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('pfno');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6  " >
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Aadhar Card No <span style=" float: right ; color: red">*</span></label>
                                            <input type="text" maxlength="12" name="aadhar" placeholder="Enter Aadhar Card No" class="form-control" value="<?php   echo $this->form->value('aadhar'); ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('aadhar');
                                                       ?>
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Staff Image :</h4>
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
                                                            <div id="my_camera" style=" height: 100px; float: left; border: 2px solid #ff00cc;">
                                                            </div>
                                                           <button class="btn btn-primary"  onClick="take_snapshot();" style=" color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 35px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-camera fa-3x" aria-hidden="true"></i></button>
                                                            
                                                            <div id="results" style=" height: 130px; float: left;">    
                                                                <?php
                                                                if($this->form->value("image_type")==1){
                                                                    if(strlen($this->session->userdata('staff_dummy_img')) >0){
                                                                        ?>
                                                                <img style=" width: 280px; height: 180px;" src="<?php echo assets_path ?>uploads/temp/<?php echo $this->session->userdata('staff_dummy_img')   ?>" />
                                                                      <?php 
                                                                    }
                                                                 
                                                                }
                                                                ?>
                                                            </div>
                                                       </div>
                                                    
                                                        <script language="JavaScript">
                                                                Webcam.set({
                                                                        width: 280,
                                                                        height:180,
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
                                                                                  Webcam.upload( data_uri, '<?php echo base_url() ?>index.php/staff/Upload_snap', function(code, text) {
                                                                                    });
                                                                                   
                                                                            } );	
                                                                            
                                                                    }
                                          
                                                            </script>
                                                    
                                    </div> 
                                    <div class="form-group2" id='upload_pic' style=" display: none"  >
                                                        <div class="col-sm-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 200px;"></div>
                                                                    <div>
                                                                            <span class="btn btn-default btn-file">
                                                                                    <span class="fileinput-new">Select image</span>
                                                                            <span class="fileinput-exists">Change</span>
                                                                            <input type="file" name="stdimage" accept="image/*">
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
                            
                            
                            <div class="form-actions col-sm-offset-4 col-sm-4">
                                <input type="submit" name="submit" value="Create Staff" class="btn btn-primary btn-block" />
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
                
        </div>
    </div>  
</div>
    
<?php
$this->load->view('structure/footer');
?>
