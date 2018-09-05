<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<script src="<?php echo assets_path ?>js/jquery.min.js"></script>

<script src="<?php echo assets_path ?>js/ajaxfunction.js"></script>
<script type="text/javascript">
function onSubmit()
{
    var otp =$('#otp').val();
    
    username = otp.trim();
    $('#error').html('');
    
    var error = 0;
    if(username.length == 0)
    {
        $('#otp').css("border-color",'#F00');
        error=1;
    }
   
    if(error == 0)
    {
       otpsetState('error','<?php echo base_url() ?>index.php/accounts/validate_otp','otp='+otp);
    }
}
</script>

 <!--<div class="row">
    <div class="col-sm-12">
        <div class="box">
   <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/accounts/">Accounts</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">OTP</a>
                </li>
            </ul>
    </div>-->

<?php
      if(strlen($this->session->userdata('Success'))>0 ){
    ?>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('Success'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
        $("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('Success');
}
    ?>

<div class="row" id="text">
        
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Enter OTP</h3>
                </div>
                <div class="box-content nopadding">
                    <form class='form-horizontal form-bordered' action="" method="post">
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-4">OTP</label>
                                <div class="col-sm-8">
                                    <input type="password" name="otp" id="otp" maxlength="6" placeholder="Enter OTP" class="form-control"> 
                                     <div style=" width: 70%; float: left; text-align: left; color: red" >
                                        &nbsp;    
                                        <span id="error"></span>
                                    </div>       
                                </div>
                        </div>
                        
                         <div class="form-actions col-sm-offset-4 col-sm-4">
                                <button type="button" class="btn btn-primary" onclick="onSubmit();">Submit</button>
                        </div>
                     </form>
                        <div class="form-actions col-sm-4">
                            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/accounts/send1/" method="post">
                                <button type="submit" class="btn btn-primary" name="resend">Resend</button>
                                </form>
                        </div>
                   
                       
                    </div>
                </div>
            </div>
    </div>

<?php
$this->load->view('structure/footer');
?>