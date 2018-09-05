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
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/students/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Support</a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Add Query</a>
                        
                    </li>
                </ul>
            </div>
              
            <div class="box box-bordered box-color ">
                <div class="box-title">
                        <h3>Add Query</h3>
                </div>
                <div class="box-content nopadding ">
                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Support/Add" method="post" enctype="multipart/form-data"    >
                        <div class="form-group">
                                    <label for="field-1" class="control-label col-sm-2">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text"  max="50" class="form-control" name="title" data-validate="required" data-message-required="value_required" value="<?php echo $this->form->value('title') ?>" placeholder="Enter Title" maxlength="50" />
                                        <span style=" color: red">
                                            <?php
                                                echo $this->form->error('title');
                                               ?>
                                        </span> 
                                    </div>
                        </div>
                        <div class="form-group">
                                    <label for="field-2" class="control-label col-sm-2">Query</label>

                                    <div class="col-sm-10 ">
                                        <textarea name="query" rows="5" style=" resize: none; width: 100%"><?php echo $this->form->value('query') ?></textarea>
                                        <span style=" color: red">
                                            <?php
                                                echo $this->form->error('stdroll');
                                            ?>
                                        </span>
                                    </div> 
                            </div>
                        <div class="form-group" >
                                <label for="file" class="control-label col-sm-2">Image</label>
                                <div class="col-sm-10 fip">
                                        <input type="file" name="image" accept="image/*"  class="form-control">
                                        <span style=" color: red">
                                            <?php
                                                echo $this->form->error('image');
                                               ?>
                                      </span>
                                </div>
                        </div>
                        <div  class="form-actions col-sm-offset-4 col-sm-8">
                                <input type="submit"  name="submit"  value="Submit Query" class="btn btn-primary" />
                                 <span id='errors' style=" color: red">

                                 </span>
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