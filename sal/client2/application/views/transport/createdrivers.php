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
                            <a href="<?php echo base_url(); ?>index.php/timetable/view/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/transport/drivers/">Manage Drivers</a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/transport/">Add Driver</a>
                        </li>

                    </ul>
            </div>
            
            <div class="box ">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Create Driver</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/transport/add_driver" method="post" enctype="multipart/form-data"  >
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                           
                            <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Personal Details :</h4>
                              
                           <div class="box" style=" width: 100%; height: auto">
                               
                                <div class="col-sm-4 nopadding" >
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">Full Name<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" name="stusername" placeholder="Enter Full Name" class="form-control" value="<?php echo $this->form->value('stusername') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                       echo $this->form->error('stusername');   
                                                    ?>
                                                </span>        
                                    </div>
                                </div>   
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">Mobile<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" name="stmobile" placeholder="Enter Mobile Number" class="form-control" value="<?php echo $this->form->value('stmobile') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stmobile')
                                                       ?>
                                                </span>  
                                    </div> 
                               </div>
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">E-mail</label>
                                            <input type="text" name="stemail" placeholder="Enter E-mail Id" class="form-control"  value="<?php echo $this->form->value('stemail') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stemail');
                                                       ?>
                                                </span>
                                    </div> 
                                    
                                </div>
                               
                                <div class="col-sm-4 nopadding" >
                                    <div class="form-group1">
                                        <label for="textfield" class="control-label ">D.O.B<span style=" float: right ; color: red">*</span></label>
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
                                            <label for="textfield" class="control-label ">D.O.J<span style=" float: right ; color: red">*</span></label>
                                             <input type="text" name="stdoj" placeholder="Select Date Of Joining" id="stdoj" class="form-control datepick"  value="<?php echo $this->form->value('stdoj') ?>"  >

                                             <span style=" color: red">
                                                 <?php
                                                            echo $this->form->error('stdoj');
                                                           ?>      
                                             </span> 
                                    </div>
                                </div>
                                
                               
                                <div class="col-sm-4"  >
                                    
                                    
                                    
                                    
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label ">Salary<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" name="stsalary" placeholder="Enter Staff Salary" class="form-control" value="<?php   echo $this->form->value('stsalary'); ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stsalary');
                                                           ?>
                                                    </span>        
                                    </div>
                                    
                                </div>    
                            
                                </div>
                            
                           </div>
                            
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                           
                           <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00 ;  ">Documents Details :</h4>
                           <div class="box"> <br/>
                               <div class="col-sm-4 nopadding">
                                   <div class="form-group1">
                                    <input type="checkbox" name="aadhar" value="1" <?php
                                    if(strlen($this->form->value('aadhar')) !=0 ){
                                       echo "checked=''"; 
                                    }
                                    ?> />&nbsp;&nbsp;Aadhar Card no
                                    <input type="text" name="aadharcard" placeholder="Enter Aadhar Card No" class="form-control" value="<?php   echo $this->form->value('aadharcard'); ?>" > 
                                    <span style=" color: red">
                                            <?php
                                                echo $this->form->error('aadharcard');
                                               ?>
                                        </span>   
                                    </div>
                                    <div class="form-group2"><br/>
                                        <input type="hidden" name="Driving" value="1" <?php
                                    if(strlen($this->form->value('Driving')) !=0 ){
                                       echo "checked=''"; 
                                    }
                                    ?> />Driving License Details <span style="color: red">*</span><br/>
                                        &nbsp;&nbsp;License No 
                                    <input type="text" name="Drivinglic" style=" width: 93%; margin-left: 7%;" placeholder="Driving license No" class="form-control" value="<?php   echo $this->form->value('Drivinglic'); ?>" > 
                                    <span style=" color: red">
                                            <?php
                                                echo $this->form->error('Drivinglic');
                                               ?>
                                        </span>
                                    &nbsp;&nbsp;Validity Date
                                    <input type="text" placeholder="License Expiry Date"  style=" width: 93%; margin-left: 7%;" name="dlcvalid" class="form-control datepick" value="<?php   echo $this->form->value('dlcvalid'); ?>" > 
                                    <span style=" color: red">
                                            <?php
                                                echo $this->form->error('dlcvalid');
                                               ?>
                                        </span>   
                                    </div>
                               </div>
                               <div class="col-sm-8 ">
                                    <label for="textfield" class="control-label ">Image</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" onclick="show_img_upload();" name="image_type" id="snap_chck" value="1"  <?php
                                    if(strlen($this->form->value("image_type"))==0){
                                        echo "checked=''";
                                    }else{
                                        if($this->form->value("image_type")==1){
                                            echo "checked=''";
                                        }
                                    }
                                    ?> /> Cam Snap &nbsp;<input type="checkbox" onclick="show_img_upload();" name="image_type" id="image_upload" value="2" <?php 
                                    if($this->form->value("image_type")==2){
                                            echo "checked=''";
                                        }
                                    ?> />Upload Image
                                    <div class="form-group2"  id="snap_shot"  >
                                                       <div style=" width: 100%; height: auto">
                                                            <div id="my_camera" style=" height: 100px; float: left;">    
                                                            </div>
                                                           <button class="btn btn-primary"  onClick="take_snapshot();" style=" color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 50px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-camera fa-3x" aria-hidden="true"></i></button>
                                                            
                                                            <div id="results" style=" height: 120px; float: left;">    
                                                                <?php
                                                                if($this->form->value("image_type")==1){
                                                                    if(strlen($this->session->userdata('driver_dummy_img')) >0){
                                                                        ?>
                                                                <img style=" width: 350px; height: 200px;" src="<?php echo assets_path ?>uploads/temp/<?php echo $this->session->userdata('driver_dummy_img')   ?>" />
                                                                      <?php 
                                                                    }
                                                                 
                                                                }
                                                                ?>
                                                            </div>
                                                       </div>
                                                    
                                                        <script language="JavaScript">
                                                                Webcam.set({
                                                                        width: 350,
                                                                        height:200,
                                                                        image_format: 'jpeg',
                                                                        jpeg_quality: 90
                                                                });
                                                                Webcam.attach( '#my_camera' );
                                                        </script>
                                                        
                                                        <script language="JavaScript">
                                                                    function take_snapshot() {
                                                                            // take snapshot and get image data
                                                                            Webcam.snap( function(data_uri) {
                                                                                    document.getElementById('results').innerHTML = 
                                                                                            '<img src="'+data_uri+'"/>';
                                                                                   Webcam.upload( data_uri, '<?php echo base_url() ?>index.php/Transport/Upload_snap', function(code, text) {
                                                                                    });
                                                                                   
                                                                            } );	
                                                                            
                                                                    }
                                                                    
                                           
                                           
//                                                                     Webcam.upload( data_uri, 'myscript.php', function(code, text) {
//                                                                                            // Upload complete!
//                                                                                            // 'code' will be the HTTP response code from the server, e.g. 200
//                                                                                            // 'text' will be the raw response content
//                                                                                    } );

                                                            </script>
                                                    
                                    </div> 
                                    <div class="form-group2" id='upload_pic' style=" display: none"  >
                                                        <div class="col-sm-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100%; height: 120px;"></div>
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
                                    function show_img_upload(){
                                        
                                       if($('#snap_chck').prop('checked')){
                                           $('#image_upload').attr('checked', false); // Checks it
                                          $('#snap_shot').show();$('#upload_pic').hide();
                                       }else{
                                           $('#image_upload').attr('checked', true); // Checks it
                                         
                                           $('#snap_shot').hide();$('#upload_pic').show();
                                           
                                           
                                      }
                                    }
                                    </script>
                               </div>
                           </div>
                           </div>
                            
                          <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                              <div class="form-actions col-sm-offset-4 col-sm-4">
                                  <input type="submit" name="submit" value="Create Driver" onclick="upload_snapshot();" class="btn btn-primary btn-block" />
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