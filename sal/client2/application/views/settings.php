<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
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
                            <a href="">Account Settings</a>
                        </li>
                    </ul>
            </div>
        <?php
          if(strlen($this->session->userdata('user_settings_update'))>0 ){
        ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success!</strong>
             <?php echo $this->session->userdata('user_settings_update')  ?>
            </div>
        <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
       <?php
        $this->session->unset_userdata('user_settings_update');
    }
        ?>
    <div class="row">
        
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Update user profile</h3>
                </div>
                <div class="box-content nopadding">
                    <?php
                    $query=$this->db->query("select * from staff where id='".$this->session->userdata('staff_id')."'");
                    $query=$query->row();
              
                    ?>
                    <form  action="<?php echo base_url(); ?>index.php/settings/update_profile" method="POST" enctype="multipart/form-data"  class='form-horizontal form-bordered'>
                       
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="sname"  placeholder="Enter Your Name" class="form-control" value="<?php 
                                      if( strlen($this->form->value('sname'))!=0 ){
                                          echo $this->form->value('sname');
                                      }else{
                                          echo $query->name;
                                      }
                                    ?>"   >
                                     <span style=" color: red">
                                            <?php
                                              echo $this->form->error('sname');   
                                            ?>
                                        </span>       
                                </div>
                        </div> 
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Dob</label>
                                <div class="col-sm-9">
                                    <input type="text" name="sdob"  placeholder="Enter Date Of Birth" class="form-control datepick" value="<?php 
                                      if( strlen($this->form->value('sdob'))!=0 ){
                                          echo $this->form->value('sdob');
                                      }else{
                                          echo $query->dob;
                                      }
                                    ?>"   >
                                     <span style=" color: red">
                                            <?php
                                              echo $this->form->error('sdob');   
                                            ?>
                                        </span>       
                                </div>
                        </div> 
                        
                       
                        <div class="form-group">
                                <?php
                            $gender="";
                                    if( strlen($this->form->value('sgender'))!=0 ){
                                          $gender= $this->form->value('sgender');
                                      }else{
                                          $gender= $query->sex;
                                      }
                                    ?>
                            
                                    <label for="textfield" class="control-label col-sm-3">Gender</label>
                                    <div class="col-sm-9">
                                        <input type="radio" name="sgender" value="1" <?php 
                                             if($gender ==1 ){
                                                 echo "checked";
                                                 }?> /> Male &nbsp; &nbsp;<input  type="radio" name="sgender" value="2"  <?php 
                                             if($gender ==2 ){
                                                 echo "checked";
                                                 }?> /> Female
                                        <span style=" color: red">
                                                <?php
                                                   if(strlen($this->form->error('sgender')) >0){
                                                       echo "<br/>";
                                                   }
                                                     echo $this->form->error('sgender');   
                                                   ?>
                                            </span>        
                                    </div>
                            </div>
                        
                        <div class="form-group">
                            <?php
                            $bg="";
                                    if( strlen($this->form->value('sbloodg'))!=0 ){
                                          $bg= $this->form->value('sbloodg');
                                      }else{
                                          $bg= $query->bloodg;
                                      }
                                    ?>
                                    <label for="textfield" class="control-label col-sm-3">Blood Group</label>
                                    <div class="col-sm-9">
                                         <select name="sbloodg" id="sbloodg" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                             <option value="" >Select Blood Group</option> 
                                         <?php
                                      
                                         $blood_group = unserialize (blood_groups);
                                        foreach ($blood_group as $key=>$value) {
                                            ?>
                                             <option  value="<?php echo $key ?>" <?php
                                           if($bg== $key){
                                               echo "selected";
                                           }
                                          ?>   ><?php echo $value ?></option>
                                           <?php
                                        }

                                       ?>
                                        </select>
                                        <?php echo $this->form->value('sbloodg'); ?>
                                        <span style=" color: red">
                                                <?php
                                                if(strlen($this->form->error('sbloodg')) >0){
                                                       echo "<br/>";
                                                   }
                                                    echo $this->form->error('sbloodg')
                                                   ?>
                                            </span>        
                                    </div>


                            </div> 
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" name="sphone"  placeholder="Enter Your Phone" class="form-control" value="<?php
                                      if( strlen($this->form->value('sphone'))!=0 ){
                                          echo $this->form->value('sphone');
                                      }else{
                                          echo $query->phone;
                                      }      ?>"   >
                                    <span style=" color: red">
                                        <?php
                                              echo $this->form->error('sphone');   
                                        ?>
                                    </span>       
                                </div>
                        </div> 
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Qualification</label>
                                <div class="col-sm-9">
                                    <input type="text" name="squalification"  placeholder="Enter Your Phone" class="form-control" value="<?php
                                      if( strlen($this->form->value('squalification'))!=0 ){
                                          echo $this->form->value('squalification');
                                      }else{
                                          echo $query->qualification;
                                      }      ?>"   >
                                    <span style=" color: red">
                                        <?php
                                              echo $this->form->error('squalification');   
                                        ?>
                                    </span>       
                                </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="field-1" class="control-label col-sm-3">photo </label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $query->img  ?>" alt="...">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                                    <div>
                                        <span class="btn btn-white btn-file">
                                                <span class="fileinput-new">Change image</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="stdimage" accept="image/*">
                                        </span>
                                            <a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                                <span style=" color: red">
                                        <?php
                                              echo $this->form->error('stdimage');   
                                        ?>
                                    </span>
                            </div>
                    </div>
                        
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Update User profile</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        
        
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-th-list"></i>Update User password</h3>
                                </div>
                                <div class="box-content nopadding">
                                    <form action="<?php echo base_url(); ?>index.php/settings/update_password"  method="POST" class='form-horizontal form-bordered'>
                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-4">Old Password</label>
                                                        <div class="col-sm-8">
                                                            <input type="password" name="oldpass"  placeholder="Enter Old password" class="form-control"   >
                                                             <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('oldpass');   
                                                                       ?>
                                                                </span>       
                                                        </div>
                                                </div>
                                                <div class="form-group">
                                                        <label for="password" class="control-label col-sm-4">New Password</label>
                                                        <div class="col-sm-8">
                                                            <input type="password" name="newpass" id="mobile" placeholder="Enter new Password" class="form-control" >
                                                            <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('newpass');   
                                                                       ?>
                                                                </span>
                                                        </div>
                                                </div>
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

                                                <div class="form-actions col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                                </div>
                                        </form>

                                </div>
                        </div>
        </div>
        
        
    </div>
            
    </div>
</div>
    </div>
<?php
$this->load->view('structure/footer');
?>