<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<script>
function myFunction() {
    var checkBox = document.getElementById("salary");
    var checkBox1 = document.getElementById("expend");
    var checkBox2 = document.getElementById("fee");
    var text = document.getElementById("text");
    if (checkBox.checked == true){
        text.style.display = "block";

        var id =$('#salary').val();
        
    }
    else if (checkBox1.checked == true){
        text.style.display = "block";
    }
    else if (checkBox2.checked == true){
        text.style.display = "block";
    }
     else {
       text.style.display = "block";
    }
}
</script>

<div class="row">
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

            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/accounts/settings_add" method="post">
                <label class="checkbox-inline">
                  <h4><input type="checkbox" value="1" id="salary" name="checks"  onclick="myFunction()">Salaries</h4>
                </label>
                <label class="checkbox-inline">
                  <h4><input type="checkbox" value="2" id="expend" name="checks1"  onclick="myFunction()">Expenditure</h4>
                </label>
                <label class="checkbox-inline">
                  <h4><input type="checkbox" value="3" id="fee" name="checks2"  onclick="myFunction()">Fees</h4>
                </label>
         
   </div>
    </div>
</div>


<div class="row" id="text">
        
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Set Password</h3>
                </div>
                <div class="box-content nopadding">
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
                        </div> <br><br><br>
                        <div class="form-group">
                                                        <label for="password" class="control-label col-sm-4">Confirm Password</label>
                                                        <div class="col-sm-8">
                                                            <input type="password" name="cnfrmpass" id="mobile" placeholder="Re-enter new password" class="form-control" >
                                                            <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('cnfrmpass');   
                                                                       ?>
                                                                </span>
                                                        </div>
                                                </div>
                        
                         <div class="form-actions col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">Set Password</button>
                        </div>
                        
                    </form>
                       
                    </div>
                </div>
            </div>
        
        
    </div><br>

    <?php
      if(strlen($this->session->userdata('pass_add_error1'))>0 ){
    ?>
        <div id="successMsg" class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('pass_add_error1'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
        $("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('pass_add_error1');
}
    ?>

        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Forgot Password</h3>
                </div>
                <div class="box-content nopadding">
                  <form action="<?php echo base_url(); ?>index.php/accounts/send" method="post">  
                    <div class="col-sm-offset-2 col-sm-4"> 
                        <label class="radio-inline">
                  <h4><input type="radio" value="1" name="checks">Salaries</h4>
                </label>
                <label class="radio-inline">
                  <h4><input type="radio" value="2" name="checks">Expenditure</h4>
                </label>
                <label class="radio-inline">
                  <h4><input type="radio" value="3" name="checks">Fees</h4>
                </label>
                   </div>      
                        <div class="form-actions col-sm-offset-2 col-sm-4">
                                <button type="submit" class="btn btn-primary">Forgot Password</button>
                        </div>
                    </form>
                       
                    </div>
                </div>
            </div>
     

            
           
<?php
$this->load->view('structure/footer');
?>