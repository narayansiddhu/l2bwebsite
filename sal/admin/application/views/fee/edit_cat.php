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
                        <a href="<?php echo base_url(); ?>index.php/">Home </a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/fee/category">Fee Category</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Edit Category</a>
                    </li>
                </ul>

        </div>
         <?php
            if(strlen($this->session->userdata('Fee_cat_edit_Sucess'))>0 ){
                ?><br/>
                    <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Success!</strong>
                     <?php echo $this->session->userdata('Fee_cat_edit_Sucess'); ?>
                    </div>
               <?php
                $this->session->unset_userdata('Fee_cat_edit_Sucess');
            }
         ?>
         
         
        <div class="box box-bordered box-color">
            
            <div class="box-title">
                    <h3>
                            <i class="fa fa-th-list"></i>Edit Fee Category</h3>

            </div>
            
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered' >
                      <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Category</label>
                                <div class="col-sm-10">
                                    <input type="text" name="category" value="<?php echo $cat->category ?>" id="cat_name" class="form-control" placeholder="please eneter Category name" />
                                </div>
                      </div>
                      <div  class="form-actions col-sm-offset-2 col-sm-10">
                          <input type="hidden" name="catid" id="cat_id" value="<?php echo $cat->cid   ?>" />
                            <input type="button"  name="submit" onclick="add_Category();" value="Edit Category" class="btn btn-primary" />
                            <span id='errors' style="color: red;"></span>
                      </div>
                </div>
            </div>
            
        </div>
                
         <script>
             function add_Category(){
               var cat=$('#cat_name').val(); 
               setState('errors','<?php echo base_url() ?>index.php/fee/edit_category','cat='+cat+'&catid=<?php echo $cat->cid   ?>'); 
             }
         </script>      
    </div>
           
         </div>
</div>   
<?php

$this->load->view('structure/footer');

?>
