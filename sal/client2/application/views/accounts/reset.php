<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<!--<div class="row">
   <div class="col-sm-12">
        <div class="box">
        <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/accounts/">Accounts</a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/accounts/settings">Settings</a>
                    </li>
                </ul>
            </div><br><br>

            

            
   </div>
    </div>
</div>-->

 <?php
      if(strlen($this->session->userdata('pass_add_error'))>0 ){
    ?>
        <div id="successMsg" class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('pass_add_error'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
        $("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('pass_add_error');
}
    ?>


<div class="row" id="text">
        
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Re-Set Password</h3>
                </div>
                <div class="box-content nopadding">
                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/accounts/reset" method="post">
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-4">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" name="password" maxlength="50" placeholder="Enter Password" class="form-control" value="<?php echo $this->form->value('password') ?>" > 
                                     <span style=" color: red">
                                            <?php
                                              echo $this->form->error('password');   
                                            ?>
                                    </span>       
                                </div>
                        </div>
                        <div class="form-group">
                                                        <label for="password" class="control-label col-sm-4">Confirm Password</label>
                                                        <div class="col-sm-8">
                                                            <input type="password" name="cnfrmpass" placeholder="Re-enter new password" class="form-control" >
                                                            <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('cnfrmpass');   
                                                                       ?>
                                                                </span>
                                                        </div>
                                                </div>
                        
                         <div class="form-actions col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Set Password</button>
                        </div>

                    </form>
                       
                    </div>
                </div>
            </div>


        
        
    </div>

     

            
           
<?php
$this->load->view('structure/footer');
?>