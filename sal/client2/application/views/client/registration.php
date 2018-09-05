<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>S-Network | REGISTRATION </title>
    <link rel="shortcut icon" href="<?php echo assets_path ?>img/school.ico" />

    <link href="<?php echo assets_path_admin ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo assets_path_admin ?>font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/animate.css" rel="stylesheet">
    <link href="<?php echo assets_path_admin ?>css/style.css" rel="stylesheet">


    <link href="<?php echo assets_path_admin ?>css/plugins/iCheck/custom.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/cropper/cropper.min.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/switchery/switchery.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/nouslider/jquery.nouislider.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/datapicker/datepicker3.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
    <link href="<?php echo assets_path_admin ?>css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/clockpicker/clockpicker.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/select2/select2.min.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/plugins/dualListbox/bootstrap-duallistbox.min.css" rel="stylesheet">


    <!-- Mainly scripts -->
    <script src="<?php echo assets_path_admin ?>js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo assets_path_admin ?>js/bootstrap.min.js"></script>

<!--    <script src="--><?php //echo assets_path ?><!--js/jquery.min.js"></script>-->
    <script src="<?php echo assets_path ?>js/ajaxfunction.js"></script>



    <script type="text/javascript">


    </script>
</head>
<body class="gray-bg">
<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-6 col-sm-6 text-center" style="">
            <div class="widget ibox-content"  style="margin-top: 1px; height: 430px; max-height: 428px;">
                <img class="m-b-md center-block" src="<?= base_url(); ?>../assests/img/L2B_Co.png" style="height: 100px; width: inherit;">
                <p>
                    <a class="btn btn-default btn-rounded" id="company" href="javascript:void(0)" style="margin-top: 40px;">Company</a>
                    <a class="btn btn-default btn-rounded" id="individual" href="javascript:void(0)" style="margin-top: 40px; margin-left: 20px;">Individual</a>
                </p>
            </div>

        </div>
        <div class="col-md-6 col-sm-6 text-center">
            <div class="widget ibox-content"  style="margin-top: 1px;">
                <h2 class="font-bold" id="reg_head" style="margin-top: 20px;">Sales Partner Registration</h2>
                <div id="individual_div" style="display: none;">
                    <form class="m-t" role="form" method="post" id="individual_reg_from" onsubmit="return onSubmit();" >
                        <div class="form-group">
                            <input type="text" name="user_name" id="user_name"  class="form-control" placeholder="UserName">
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone_num" id="phone_num"  class="form-control" placeholder="Mobile Number">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email"  class="form-control" placeholder="Email ID">
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group" id="data_3">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" title="Select Date of Birth" value="" data-mask="99/99/9999" placeholder="Date of Birth">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" title="Select Date of Birth" value="" data-mask="99" placeholder="Years of Exp.">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="qualification" id="qualification"  class="form-control" placeholder="Qualification">
                        </div>
                        <div class="form-group">
                            <input type="text" name="current_wrk_place" id="current_wrk_place"  class="form-control" placeholder="Current Work Place">
                        </div>
                        <div class="form-group">
                            <input type="text" name="current_job_title" id="current_job_title"  class="form-control" placeholder="Current Job Title">
                        </div>
                        <button type="submit" name="button" id="button" value="Submit" class="btn btn-warning block center-block m-b">
                            <img src="<?= base_url(); ?>../assests/img/loading.gif" style="width: 30px; height: 30px; display: none; " id="loading">
                            Submit
                        </button>
                    </form>
                </div>

                <div id="company_div" style="display: block;">
                    <form class="m-t" role="form" method="post" id="company_reg_from" >
                        <div class="form-group">
                            <input type="text" name="poc_name" id="poc_name"  class="form-control" placeholder="POC Name">
                        </div>
                        <div class="form-group">
                            <input type="text" name="brand_name" id="brand_name"  class="form-control" placeholder="Brand Name">
                        </div>
                        <div class="form-group">
                            <input type="text" name="phone_num" id="phone_num"  class="form-control" placeholder="Mobile Number">
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email"  class="form-control" placeholder="Email ID">
                        </div>
                        <div class="form-group">
                            <input type="text" name="company_reg_name" id="company_reg_name"  class="form-control" placeholder="Company registration Name">
                        </div>
                        <div class="form-group">
                            <input type="text" name="company_reg_as" id="company_reg_as"  class="form-control" placeholder="Company Registered as">
                        </div>
                        <div class="form-group">
                            <input type="text" name="cin_no" id="cin_no"  class="form-control" placeholder="CIN Number">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                        <input type="text" class="form-control" title="Team Size" value="" data-mask="99" placeholder="Team Size.">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" title="Years of Establish" data-mask="9999" placeholder="Year of Establish.">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="url" name="website" id="website" class="form-control" placeholder="Your company Website">
                        </div>
                        <button type="submit" name="button" id="button" value="Submit" class="btn btn-warning block center-block m-b">
                            <img src="<?= base_url(); ?>../assests/img/loading.gif" style="width: 30px; height: 30px; display: none; " id="loading">
                            Submit
                        </button>
                    </form>
                </div>
            </div>

                <div class="alert alert-danger alert-dismissable" style="margin-bottom: 9px; display: none;" id="error">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    Invalid Details <a class="alert-link" href="#">Reset Password</a>.
                </div>
            </div>

        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-12 text-center center-block">
            © 2016 - 2018 Lead 2 Business All Rights Reserved<br>
            <small>
                +91 9014551553 |
                venki.l2b@gmail.com
            </small>
        </div>

    </div>
</div>

</body>
<!-- Chosen -->
<script src="<?php echo assets_path_admin ?>js/plugins/chosen/chosen.jquery.js"></script>

<!-- JSKnob -->
<script src="<?php echo assets_path_admin ?>js/plugins/jsKnob/jquery.knob.js"></script>

<!-- Input Mask-->
<script src="<?php echo assets_path_admin ?>js/plugins/jasny/jasny-bootstrap.min.js"></script>

<!-- Data picker -->
<script src="<?php echo assets_path_admin ?>js/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- NouSlider -->
<script src="<?php echo assets_path_admin ?>js/plugins/nouslider/jquery.nouislider.min.js"></script>

<!-- Switchery -->
<script src="<?php echo assets_path_admin ?>js/plugins/switchery/switchery.js"></script>

<!-- IonRangeSlider -->
<script src="<?php echo assets_path_admin ?>js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>

<!-- iCheck -->
<script src="<?php echo assets_path_admin ?>js/plugins/iCheck/icheck.min.js"></script>

<!-- MENU -->
<script src="<?php echo assets_path_admin ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- Color picker -->
<script src="<?php echo assets_path_admin ?>js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<!-- Clock picker -->
<script src="<?php echo assets_path_admin ?>js/plugins/clockpicker/clockpicker.js"></script>

<!-- Image cropper -->
<script src="<?php echo assets_path_admin ?>js/plugins/cropper/cropper.min.js"></script>

<!-- Date range use moment.js same as full calendar plugin -->
<script src="<?php echo assets_path_admin ?>js/plugins/fullcalendar/moment.min.js"></script>

<!-- Date range picker -->
<script src="<?php echo assets_path_admin ?>js/plugins/daterangepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="<?php echo assets_path_admin ?>js/plugins/select2/select2.full.min.js"></script>

<!-- TouchSpin -->
<script src="<?php echo assets_path_admin ?>js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>

<!-- Tags Input -->
<script src="<?php echo assets_path_admin ?>js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

<!-- Dual Listbox -->
<script src="<?php echo assets_path_admin ?>js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>

<!-- Custom js-registration -->
<script src="<?php echo assets_path_admin ?>js/custom/registration.js"></script>

</html>
