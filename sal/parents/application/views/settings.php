<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            
            
            <br/>
            
                        
                            <ul class="breadcrumb">
                                 <li>
                                     <a href="<?php echo base_url() ?>">Home</a>
                                 </li>
                                 <li class="active">
                                     <a href="">Account Settings</a>  
                                 </li>
                             </ul>
                         <?php
                             if(strlen($this->session->userdata('user_settings_update'))>0 ){
                           ?>
                               <br/>
                               <div class="alert alert-success alert-dismissable">
                               <button type="button" class="close" data-dismiss="alert">×</button>
                               <strong>Success!</strong>
                                <?php echo $this->session->userdata('user_settings_update')  ?>
                               </div>
                          <?php
                                   $this->session->unset_userdata('user_settings_update');
                               }
                           ?>   

                         <div class="col-sm-6 nopadding">
                             <div class="box box-bordered box-color">
                                 <div class="box-title">
                                         <h3>
                                                 <i class="fa fa-th-list"></i>Update user profile</h3>
                                 </div>
                                 <div class="box-content nopadding">
                                     <?php
                                     $query=$this->db->query("select * from parent where parent_id='".$this->session->userdata('parent_id')."'");
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
                                                 <label for="textfield" class="control-label col-sm-3">Address</label>
                                                 <div class="col-sm-9">
                                                     <textarea name="address" class="form-control" style=" resize: none"  ><?php
                                                       if( strlen($this->form->value('address'))!=0 ){
                                                           echo $this->form->value('address');
                                                       }else{
                                                           echo $query->address;
                                                       }      ?></textarea>
                                                     <span style=" color: red">
                                                         <?php
                                                               echo $this->form->error('address');   
                                                         ?>
                                                     </span>       
                                                 </div>
                                         </div>


                                         <div class="form-group">
                                                 <label for="textfield" class="control-label col-sm-3">profession</label>
                                                 <div class="col-sm-9">
                                                     <input type="text" name="sprofession"  placeholder="Enter Your Phone" class="form-control" value="<?php
                                                       if( strlen($this->form->value('sprofession'))!=0 ){
                                                           echo $this->form->value('sprofession');
                                                       }else{
                                                           echo $query->profession;
                                                       }      ?>"   >
                                                     <span style=" color: red">
                                                         <?php
                                                               echo $this->form->error('sprofession');   
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
                         <div class="col-sm-6 nopadding">
                             <div style=" padding-left: 10px" class="box box-bordered box-color">
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
<?php
$this->load->view('structure/footer');
?>