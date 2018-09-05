<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>L2B&Co. | ADMIN Login </title>
    <link rel="shortcut icon" href="<?php echo assets_path ?>img/school.ico" />

    <link href="<?php echo assets_path_admin ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo assets_path_admin ?>font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo assets_path_admin ?>css/animate.css" rel="stylesheet">
    <link href="<?php echo assets_path_admin ?>css/style.css" rel="stylesheet">
    <!-- Mainly scripts -->
    <script src="<?php echo assets_path_admin ?>js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo assets_path_admin ?>js/bootstrap.min.js"></script>

    <!--    <script src="--><?php //echo assets_path ?><!--js/jquery.min.js"></script>-->
    <script src="<?php echo assets_path ?>js/ajaxfunction.js"></script>

    <script type="text/javascript">

        function onSubmit(){

            let username = $('#username').val();
            let password = $('#password').val();
            username = username.trim();
            password = password.trim();
            let error = 0;
            if(username.length == 0) {
                $('#username').css("border-color",'#F00');
                error=1;
                return false;
            }else{
                if(password.length == 0) {
                    $('#password').css("border-color",'#F00');
                    error=1;
                    return false;
                } else {
                    if(error == 0) {
                        loginsetState('error','<?php echo base_url() ?>index.php/login/login_check','username='+username+'&password='+password);
                        return false; // return false to cancel form action
                    }
                }
            }
        }
    </script>
</head>
<body class="gray-bg">
<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3 text-center">
            <div class="widget ibox-content"  style="margin-top: 1px;">
                <img class="m-b-md center-block" src="<?= base_url(); ?>../assests/img/L2B_Co.png" style="height: 100px; width: inherit;">
                <h3 class="no-margins">ADMIN Login</h3>
                <form class="m-t" role="form" method="post" id="login_from" onsubmit="return onSubmit();">
                    <div class="form-group">
                        <input type="text" name="textfield" id="username"  class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" name="textfield" id="password" class="form-control" placeholder="Password" >
                    </div>
                    <button type="submit" name="button" id="button" value="Submit" class="btn btn-warning block full-width m-b">
                        <img src="<?= base_url(); ?>../assests/img/loading.gif" style="width: 30px; height: 30px; display: none; " id="loading">
                        Login
                    </button>

                    <div class="m-b-sm">
                        <a href="#">
                            <a href="#"><small>Forgot password?</small></a>
                        </a>
                    </div>
                </form>
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

</html>
