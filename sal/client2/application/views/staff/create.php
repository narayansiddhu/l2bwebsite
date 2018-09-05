<?php
// New Theme implementation headers->css and js plugines
$this->load->view('structure/header');
$this->load->view('structure/js');
//--> New Theme implementation headers->css and js plugines
?>

<body>
<div id="wrapper">

    <?php
    // Navigation Links in right side panel
    $this->load->view('structure/body');
    //--> Navigation Links in right side panel
    ?>

    <script src="<?php echo assets_path ?>js/webcamjs-master/webcam.min.js" ></script>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <?php $this->load->view('structure/nav'); ?>
        </div>
        <!--- Contenet -->
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-8">
                <h1>Staff Users</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url() ?>index.php/Dashboard"> Home </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/staff/View_staff">Manage Staff</a>
                    </li>
                    <li class="active">
                        <strong>Create Staff</strong>
                    </li>
                </ol>
            </div>
            <div class="col-sm-4">
                <div class="title-action">
                    <a href="<?php echo base_url() ?>index.php/staff/View_staff" class="btn btn-primary "><i class="fa fa-users-cog"></i> View Staff</a>
                </div>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <!-- Content here -->

            <div class="row">

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h2>Create Staff  <small> (<span class="text-danger"> * </span>Fields are Required) </small></h2>

                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <form role="form" id="form" action="<?php echo base_url(); ?>index.php/staff/add" method="post" enctype="multipart/form-data" >

                                    <div class="col-md-12"><h3 class="m-t-none text-success" style="font-weight: normal; "> Personal Details : </h3>
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Full Name <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input type="text" name="stusername" maxlength="50" placeholder="Enter Full Name" class="form-control" value="<?php echo $this->form->value('stusername') ?>" required>
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stusername'); ?> </span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label> Gender <small><span class="text-danger"> * </span>(Required)</small></label><br>
                                                    <div class="radio radio-info radio-inline" style="padding-top: 8px">
                                                        <input type="radio" id="inlineRadio1" name="gender" value="1" <?php
                                                        if($this->form->value('gender') == 1 ){
                                                            echo "checked";
                                                        }?> />
                                                        <label for="inlineRadio1"> Male</label>
                                                    </div>
                                                    <div class="radio radio-inline" style="padding-top: 8px">
                                                        <input  type="radio" id="inlineRadio2"  name="gender" value="2"  <?php
                                                        if($this->form->value('gender') == 2 ){
                                                            echo "checked";
                                                        }?> /> <label for="inlineRadio2">Female</label>
                                                    </div><br>
                                                    <span class="text-danger font-bold"><?= $this->form->error('gender'); ?> </span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="stbg">Blood Group <small>(Optional)</small></label>
                                                    <select name="stbg" id="stbg" class="select2_demo_1 form-control" >
                                                        <option value=""> Select Blood Group</option>
                                                        <?php
                                                        $blood_group = unserialize (blood_groups);
                                                        foreach ($blood_group as $key=>$value) { ?>
                                                            <option  value="<?php echo $key ?>" <?php
                                                            if($this->form->value('stbg')== $key){
                                                                echo "selected";
                                                            } ?>   ><?= $value ?>
                                                            </option>
                                                            <?php } ?>
                                                    </select>
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stbg'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Mobile <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input type="text" name="stmobile" placeholder="Enter Mobile Number" class="form-control" value="<?php echo $this->form->value('stmobile') ?>" pattern="{6-9}[1]{0-9}[9]" required>
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stmobile'); ?> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label> E-mail <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input type="text" maxlength="30" name="stemail" placeholder="Enter E-mail Id" class="form-control"  value="<?php echo $this->form->value('stemail') ?>" required>
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stemail'); ?> </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label> Qualification <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input type="text" maxlength="30" name="stqualification" placeholder="Enter Staff Qualification" class="form-control" value="<?= $this->form->value('stqualification'); ?>" required>
                                                    <span class="text-danger font-bold">  <?=  $this->form->error('stqualification'); ?> </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" id="data_1">
                                                    <label> Date Of Birth <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                                                        <input type="text" name="stdob" placeholder="Select Date Of Birth" id="stdob" class="form-control" value="<?php echo $this->form->value('stdob')?>" ><br>
                                                    </div>
                                                    <span class="text-danger font-bold">  <?=  $this->form->error('stdob'); ?> </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="stlevel">User Level <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <select name="stlevel" id="stlevel" class="select2_demo_1 form-control" >
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
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stlevel'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="hr-line-dashed"></div><h3 class="m-t-none text-success" style="font-weight: normal; ">Professional Details : </h3>
                                        <div class="row">
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label>Salary <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input type="text" name="stsalary" placeholder="Enter Staff Salary" class="form-control" value="<?= $this->form->value('stsalary'); ?>">
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stsalary'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4" >
                                                <div class="form-group">
                                                    <label>Years Of Experience <small><span class="text-danger"> * </span>(Required)</small></label>
                                                    <input maxlength="2" name="stexperience" placeholder="Enter Years Of Experience" type="number" class="form-control" value="<?php   echo $this->form->value('stexperience'); ?>">
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stexperience'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group" id="data_1">
                                                    <label> Date Of Joining <small>(Optional)</small></label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                                                        <input type="text" name="stdoj" placeholder="Select Date Of Joining" id="stdoj" class="form-control datepick"  value="<?php echo $this->form->value('stdoj') ?>" ><br>
                                                    </div>
                                                    <span class="text-danger font-bold">  <?= $this->form->error('stdoj'); ?> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6" >
                                                        <div class="form-group">
                                                            <label>Casual Leaves <small><span class="text-danger"> * </span>(Required)</small></label>
                                                            <input type="text" name="Leaves" placeholder="Enter No Of Casual Leaves" class="form-control" value="<?php   echo $this->form->value('Leaves'); ?>" >
                                                            <span class="text-danger font-bold"> <?= $this->form->error('Leaves'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" >
                                                        <div class="form-group">
                                                            <label>Bank Account Number <small>(Optional)</small></label>
                                                            <input type="text" maxlength="20" name="account" placeholder="Enter Bank Account No" class="form-control" value="<?php   echo $this->form->value('account'); ?>" >
                                                            <span class="text-danger font-bold">  <?= $this->form->error('account'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6" >
                                                        <div class="form-group">
                                                            <label>PF Number <small> (Optional)</small></label>
                                                            <input type="text" name="pfno" placeholder="Enter PF NO" class="form-control" value="<?php   echo $this->form->value('pfno'); ?>" >
                                                            <span class="text-danger font-bold">  <?=  $this->form->error('pfno'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" >
                                                        <div class="form-group">
                                                            <label>Aadhar Number <small><span class="text-danger"> * </span>(Required)</small></label>
                                                            <input type="text" name="aadhar" placeholder="Enter Aadhar Card No" class="form-control" value="<?php echo $this->form->value('aadhar'); ?>" data-mask="9999 9999 9999" >
                                                            <span class="help-block hidden">9999 9999 9999</span>
                                                            <span class="text-danger font-bold">  <?=  $this->form->error('aadhar'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-actions col-sm-offset-8 col-sm-4">
                                                            <button class="btn btn-sm btn-primary m-t-n-xs" type="submit" name="submit">Create Staff</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="col-md-12" >
                                                    <div class="form-group">
                                                        <label>Staff Image <small><span class="text-danger"> * </span>(Required)</small></label><br>

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
                                                        <div class="form-group"  id="snap_shot"  >
                                                            <div style=" width: 100%; height: auto">
                                                                <div id="my_camera" style="height: 100px; float: left; border: solid 1px #EEE; ">
                                                                </div>
                                                                <button class="btn btn-primary"  onClick="take_snapshot();"  style=" color: whitesmoke; float: left; width:40px; height: 100px; margin-top: 35px ; margin-left: 5px; margin-right: 5px;" type="button" ><i class="fa fa-camera " aria-hidden="true"></i></button>

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
                                                                    width: 200,
                                                                    height:140,
                                                                    image_format: 'jpeg',
                                                                    jpeg_quality: 99
                                                                });
                                                                Webcam.attach( '#my_camera' );
                                                            </script>

                                                            <script language="JavaScript">
                                                                function take_snapshot() {
                                                                    alert('web cam processing..');
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
                                                        <div class="form-group" id='upload_pic' style=" display: none"  >
                                                            <div class="col-sm-12">
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 140px;"></div>
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
                                                            <span class="text-danger font-bold"> <?=  $this->form->error('stdimage'); ?></span>
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">

                                            </div>
                                        </div>
                                    </div>


                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!--//-- Conentent End -->
        <?php   $this->load->view('structure/footer'); ?>
    </div>
</div>

</body>
<script>
    $(document).ready(function(){

        $("#form").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 3
                },
                url: {
                    required: true,
                    url: true
                },
                number: {
                    required: true,
                    number: true
                },
                min: {
                    required: true,
                    minlength: 6
                },
                max: {
                    required: true,
                    maxlength: 4
                },
                messages: {
                    'stusername': "Please enter your firstname",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address"
                }
            }
        });
    });
</script>
</html>
