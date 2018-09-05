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
                            <a href="<?php echo base_url(); ?>">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Create Notice</a>
                        </li>
                    </ul>
            </div>
            
            <div class="box box-bordered box-color">
        
                <div class="box-title">
                        <h3><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Create Notifications</h3>                                     
                </div>

                <div class="box-content nopadding">    

                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Notices/create" method="post" >

                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Title</label>
                                <div class="col-sm-10">
                                    <input type="text"  name="title" placeholder="Enter Title" class="form-control" value="<?php echo $this->form->value('title') ?>" > 
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('title');   
                                            ?>
                                        </span>        
                                </div>
                        </div>    

                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Description</label>
                                <div class="col-sm-10">
                                    <textarea  style=" resize: none" class="form-control"  name="description"><?php echo $this->form->value('description') ?></textarea>
                                    <span id='noofsecerr' style=" clear: both; color: red">
                                            <?php
                                                echo $this->form->error('description');   
                                            ?>
                                    </span>        
                                </div>
                        </div>
                        
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Expires On</label>
                                <div class="col-sm-10">
                                    <input type="text" name="expiry" placeholder="Select Expiry Date"  class="form-control datepick" value="<?php echo $this->form->value('expiry') ?>">

                                     <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('expiry');
                                                   ?>
                                    </span>        
                                </div>
                        </div>
                        <div class="form-group">
                            
                                <label for="textfield" class="control-label col-sm-2">Notify To</label>
                                <div class="col-sm-10">
                                    <input type="checkbox" name="notify_1" 
                                           <?php 
                                           if(strlen($this->form->value('notify_1'))!=0){
                                               echo  'checked="" ';   
                                           }  
                                           ?> />&nbsp;&nbsp;Staff &nbsp;&nbsp;&nbsp; 
                                        <input type="checkbox" name="notify_2"
                                               <?php 
                                           if(strlen($this->form->value('notify_2'))!=0){
                                               echo  'checked="" ';   
                                           }  
                                           ?>
                                               />&nbsp;&nbsp;Parents
                                    &nbsp;&nbsp;&nbsp;<input type="checkbox" name="notify_3" 
                                               <?php 
                                           if(strlen($this->form->value('notify_3'))!=0){
                                               echo  'checked="" ';   
                                           }  
                                           ?>
                                               />&nbsp;&nbsp;Students
                                     <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('notify');
                                                   ?>
                                    </span>        
                                </div>
                        </div>

                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="submit"  name="submit" value="Create Notice" class="btn btn-primary" />
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