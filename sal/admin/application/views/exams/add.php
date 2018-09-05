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
                    <a href="<?php echo base_url(); ?>index.php/exams/">Manage Exams</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Create Exam</a>
                </li>
            </ul>
        </div>
              
        
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3>
                                    <i class="fa fa-th-list"></i>Create Exam</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exams/create" method="post" enctype="multipart/form-data"  >
                            <div class="box"  >
                                <div class="col-sm-6 nopadding">
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Exam Name</label>
                                            <div class="col-sm-10">
                                                <input  type="text" id="subname" name="exam" placeholder="Enter Exam Name" class="form-control" value="<?php echo $this->form->value('exam') ?>" > 
                                                <span id="subname_err" style=" color: red">
                                                     <?php echo $this->form->error('exam') ?>   
                                                </span>        
                                            </div>
                                    </div> 
                                    <div class="form-group">
                                        <label for="field-2" class="control-label col-sm-2">Start date</label>

                                        <div class="col-sm-10">
                                                <input  type="text" name="strtdate" placeholder="Select starting date" id="strtdate" class="form-control datepick" value="<?php echo $this->form->value('strtdate') ?>">
                                                <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('strtdate');
                                                              ?>
                                                       </span>     
                                        </div>
                            </div>
                                </div>
                                <div class="col-sm-6 nopadding">
                                    <div class="form-group" style=" height: 55px">
                                    <label for="textfield" class="control-label col-sm-2">Exam Type</label>
                                    <div class="col-sm-10" style=" height: 53px" >
                                        <select  name="type" class="select2-me" style=" width: 100%;" >
                                            <option value="">Select Exam Type</option>
                                            <option 
                                                <?php
                                                 if($this->form->value('type')==4){
                                                    echo "selected"; 
                                                 }
                                                ?>
                                                value="4">Formative Exams</option> 
                                            <option 
                                                <?php
                                                 if($this->form->value('type')==5){
                                                    echo "selected"; 
                                                 }
                                                ?>
                                                value="5">Summative Exam</option> 
                                            <option 
                                                <?php
                                                 if($this->form->value('type')==1){
                                                    echo "selected"; 
                                                 }
                                                ?>
                                                value="1">Descriptive Exam</option>
                                            <option 
                                                <?php
                                                 if($this->form->value('type')==2){
                                                    echo "selected"; 
                                                 }
                                                ?>
                                                value="2">Multiple Choice Exam</option>         
                                            <option 
                                                <?php
                                                 if($this->form->value('type')==3){
                                                    echo "selected"; 
                                                 }
                                                ?>
                                                value="3">Daily Tests</option>         
                                        </select>
                                        <span id="subname_err" style=" color: red">
                                             <?php echo $this->form->error('type') ?>   
                                        </span>        
                                    </div>
                            </div>
                                    <div class="form-group">
                                        <label for="field-2" class="control-label col-sm-2">End Date</label>

                                        <div class="col-sm-10">
                                                <input  type="text" name="enddate" placeholder="Select Ending Date" id="enddate" class="form-control datepick" value="<?php echo $this->form->value('enddate') ?>">
                                                <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('enddate');
                                                              ?>
                                                       </span>     
                                        </div>
                            </div>
                                </div>
                            </div>
                            <hr style=" clear: both; padding: 0px; margin: 0px; "/>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="submit"   name="submit" value="Create Exam" class="btn btn-primary" />
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