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
                    <a href="<?php echo base_url(); ?>index.php/parents/View">Manage Parents</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/parents/parent_add">Add Parent</a>
                </li>
            </ul>
        </div>
    

    
    
        
            <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3><i class="fa fa-male"></i>Create Parent</h3> 
                                    <div class="actions">
                                            <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url()  ?>index.php/parents/bulk"><i class="fa fa-plus"></i>&nbsp;Create Bulk</a>  
                                    </div>
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/parents/create" method="post" enctype="multipart/form-data"  >
                                    
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Name<span style=" float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="prntname" placeholder="Enter Full Name" class="form-control" value="<?php echo $this->form->value('prntname') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('prntname');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div>    
                                    
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">E-mail<span style=" float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="prntemail" placeholder="Enter E-mail" class="form-control" value="<?php echo $this->form->value('prntemail') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('prntemail');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div> 
                                    
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Phone<span style=" float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="prntphone" placeholder="Enter Phone No" class="form-control" value="<?php echo $this->form->value('prntphone') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('prntphone');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Profession<span style=" float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="prntprof" placeholder="Enter Profession" class="form-control" value="<?php echo $this->form->value('prntprof') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('prntprof');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Address<span style=" float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" placeholder="Enter Addresss" rows="4" style=" resize: none"  name="prntadd" ><?php echo $this->form->value('prntadd')  ?></textarea>
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('prntadd');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div>
                                    
                                   
                                    <div class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="submit" name="submit" value="Create Parent" class="btn btn-primary" />
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
