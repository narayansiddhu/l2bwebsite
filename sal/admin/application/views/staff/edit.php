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
                <?php
                if($this->session->userdata('staff_level')>3){
                   ?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/staff/view">Manage Staff</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                  <?php
                }else{
                   ?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                  <?php 
                }
                ?>

                <li>
                    <a href="">Edit Staff</a>
                </li>
            </ul>
    </div> 
    <?php
      if(strlen($this->session->userdata('staff_details_update'))>0 ){
    ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('staff_details_update'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
	              $("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('staff_details_update');
}
    ?>
    <div class="box box-bordered box-color"  >
            <div class="box-title">
                    <h3>
                            <i class="fa fa-th-list"></i>Edit Staff</h3>
            </div>


            <div class="box-content nopadding">
                <form  action="<?php echo base_url(); ?>index.php/staff/update_profile" method="POST" enctype="multipart/form-data"  class='form-horizontal form-bordered'>
                    <input type="hidden" name="staff_id" value="<?php echo $staff_details->id; ?>" />
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-3">Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="sname"  placeholder="Enter Your Name" class="form-control" value="<?php 
                                  if( strlen($this->form->value('sname'))!=0 ){
                                      echo $this->form->value('sname');
                                  }else{
                                      echo $staff_details->name;
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
                            <label for="textfield" class="control-label col-sm-3">E-mail Id</label>
                            <div class="col-sm-9">
                                <input type="text" name="email"  placeholder="Enter Your Email id" class="form-control" value="<?php 
                                  if( strlen($this->form->value('email'))!=0 ){
                                      echo $this->form->value('email');
                                  }else{
                                      echo $staff_details->email;
                                  }
                                ?>"   >
                                 <span style=" color: red">
                                        <?php
                                          echo $this->form->error('email');   
                                        ?>
                                    </span>       
                            </div>
                    </div> 
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-3">D.O.B</label>
                            <div class="col-sm-9">
                                <input type="text" name="sdob"  placeholder="Date Of Birth" class="form-control datepick" value="<?php 
                                  if( strlen($this->form->value('sdob'))!=0 ){
                                      echo $this->form->value('sdob');
                                  }else{
                                       echo date("d/m/Y",$staff_details->dob);
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
                            <label for="textfield" class="control-label col-sm-3">D.O.J</label>
                            <div class="col-sm-9">
                                <input type="text" name="sdoj"  placeholder="Date Of Joining" class="form-control datepick" value="<?php 
                                  if( strlen($this->form->value('sdoj'))!=0 ){
                                      echo $this->form->value('sdoj');
                                  }else{
                                      echo date("d/m/Y",$staff_details->doj);
                                  }
                                ?>"   >
                                 <span style=" color: red">
                                        <?php
                                          echo $this->form->error('sdoj');   
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
                                      $gender= $staff_details->sex;
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
                                      $bg= $staff_details->bloodg;
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
                                      echo $staff_details->phone;
                                  }      ?>"   >
                                <span style=" color: red">
                                    <?php
                                          echo $this->form->error('sphone');   
                                    ?>
                                </span>       
                            </div>
                    </div> 
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-3">Salary</label>
                            <div class="col-sm-9">
                                <input type="text" name="salary"  placeholder="Enter Your Phone" class="form-control" value="<?php
                                  if( strlen($this->form->value('salary'))!=0 ){
                                      echo $this->form->value('salary');
                                  }else{
                                      echo $staff_details->sal_amount;
                                  }      ?>"   >
                                <span style=" color: red">
                                    <?php
                                          echo $this->form->error('salary');   
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
                                      echo $staff_details->qualification;
                                  }      ?>"   >
                                <span style=" color: red">
                                    <?php
                                          echo $this->form->error('squalification');   
                                    ?>
                                </span>       
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label col-sm-3">Photo </label>
                        <div class="col-sm-9">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
                                        <img src="<?php echo assets_path  ?>/uploads/<?php  echo $staff_details->img  ?>" alt="...">
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
                            <button type="submit" class="btn btn-primary">Update Staff Details</button>
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