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
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/expenditure">Expenses</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/expenditure/history">History</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Edit Expenses</a>
                </li>
            </ul>

    </div> 
        <?php
if(strlen($this->session->userdata('edit_expenditure_sucess'))>0 ){
    ?><br/>
    <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('edit_expenditure_sucess'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('edit_expenditure_sucess');
}

$category ="";
$mode="";
if(strlen($this->form->value('category'))!=0 ){
$category= $this->form->value('category');
 }else{
   $category= $exp->category;
 }
 if(strlen($this->form->value('mode'))!=0 ){
$mode= $this->form->value('mode');
 }else{
   $mode= $exp->mode;
 }
 
?> 
   
           <div class="box ">
                <div class="box-title">
                        <h3><i class="fa fa-money"></i>Add Expenditure</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <form class='form-horizontal form-bordered' enctype="multipart/form-data"  method="post" action="<?php echo base_url() ?>index.php/expenditure/save" >
                        <div class="box">
                            <div class="col-sm-5 nopadding" >
                                <div class="box" >
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Category</label>
                                                <select class="select2-me" name="category" style="width: 100%;">
                                                    <option value="">Select Category</option>
                                                                          <?php
                                        $cat=$this->db->query(" SELECT * FROM `expense_category` where iid = '".$this->session->userdata('staff_Org_id')."' ");
                                        $cat = $cat->result();
                                        foreach($cat as $val){
                                           ?>
                                    <option value="<?php echo $val->cat_id  ?>" 
                                            <?php
                                             if($category==$val->cat_id){
                                                 echo "selected";
                                             }
                                            ?>><?php echo $val->name  ?></option>
                                          <?php
                                        }
                                       
                                    ?>
                                                </select>
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('category');   
                                                        ?>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group1" style=" padding-left: 5px" >
                                            <label for="textfield" class="control-label">Mode Of Payment</label>
                                            <select class="select2-me" name="mode" style=" width: 100%" >
                                                <option value="1" 
                                                            <?php
                                                             if($mode==1){
                                                                 echo "selected";
                                                             }
                                                            ?>>Cash</option>
                                                <option value="2" 
                                                            <?php
                                                             if($mode==2){
                                                                 echo "selected";
                                                             }
                                                            ?>>cheque</option>
                                                <option value="3" 
                                                            <?php
                                                             if($mode==3){
                                                                 echo "selected";
                                                             }
                                                            ?>>Card Payments</option>
                                                <option value="4" 
                                                            <?php
                                                             if($mode==4){
                                                                 echo "selected";
                                                             }
                                                            ?>>Other payment Modes</option>
                                            </select>
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('mode');   
                                                        ?>
                                                </span>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="box" >
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group1">
                                            <label for="textfield" class="control-label">Reference Person</label>
                                            <input type="text" name="refererer" placeholder="Enter Reference Person Name " value="<?php
                                            if(strlen($this->form->value('refererer'))!=0 ){
                                            echo $this->form->value('refererer');
                                             }else{
                                              echo $exp->ref_person;
                                             }
                                             ?>"   class="form-control"  />
                                            <span style=" color: red">
                                                    <?php
                                                       echo $this->form->error('refererer');   
                                                    ?>
                                            </span>  
                                        </div>
                                    </div>
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group1" style=" padding-left: 5px" >
                                            <label for="textfield" class="control-label">Amount</label>
                                                <input type="text" name="amount" placeholder="Enter Amount " value="<?php 
                                                        if(strlen($this->form->value('amount'))!=0 ){
                                            echo $this->form->value('amount');
                                             }else{
                                              echo $exp->amount;
                                             }?>"   class="form-control"  />
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('amount');   
                                                        ?>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 " >
                                <div class="form-group2">
                                    <label for="textfield" class="control-label ">Reason</label>
                                    <textarea  placeholder="enter Reason" rows="4" maxlength="300" style=" resize: none" class="form-control" name="reason" ><?php 
                                                        if(strlen($this->form->value('reason'))!=0 ){
                                            echo $this->form->value('reason');
                                             }else{
                                              echo $exp->reason;
                                             }
                                            ?></textarea>
                                        <span style=" color: red">
                                                <?php
                                                   echo $this->form->error('reason');   
                                                ?>
                                        </span>        
                                    
                                </div> 
                            </div>
                            <div class="col-sm-2 " >
                                <div class="form-group2">
                                    <label for="textfield" class="control-label ">Upload Bill</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 70px;">
                                    <?php
                                      if(strlen($exp->file)!=0){
                                          ?>
                                             <img style="width: 200px; height: 70px;" src="<?php echo assets_path  ?>/uploads/<?php  echo $exp->file  ?>" alt="...">
                                             
                                          <?php
                                      }
                                    ?></div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="bils" accept="image/*"  >
                                                    </span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                    </div>
                                    <span style=" color: red">
                                                <?php
                                                   echo $this->form->error('bils');   
                                                ?>
                                        </span>        
                                    
                                </div> 
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $exp->id ?>" />
                        <div  class="form-actions col-sm-offset-4 col-sm-4">
                            <input type="submit"  name="submit" value="Add Expenditure" class="btn btn-primary btn-block" />
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