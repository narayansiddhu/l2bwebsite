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
                    <a href="<?php echo base_url(); ?>index.php/exam/add">Create Exam</a>
                </li>
            </ul>
        </div>
              
        
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3>
                                    <i class="fa fa-th-list"></i>Define Exam</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exam/create" method="post" enctype="multipart/form-data"  >
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Exam Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="subname" name="exam" placeholder="Enter Exam Name" class="form-control" value="<?php echo $this->form->value('exam') ?>" > 
                                        <span id="subname_err" style=" color: red">
                                             <?php echo $this->form->error('exam') ?>   
                                        </span>        
                                    </div>
                            </div> 
                            <div class="form-group">
                                        <label for="field-2" class="control-label col-sm-2">Start date</label>

                                        <div class="col-sm-10">
                                                <input type="text" name="strtdate" placeholder="Select starting date" id="strtdate" class="form-control datepick" value="<?php echo $this->form->value('strtdate') ?>">
                                                <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('strtdate');
                                                              ?>
                                                       </span>     
                                        </div>
                            </div>
                            <div class="form-group">
                                        <label for="field-2" class="control-label col-sm-2">End Date</label>

                                        <div class="col-sm-10">
                                                <input type="text" name="enddate" placeholder="Select Date Of Birth" id="enddate" class="form-control datepick" value="<?php echo $this->form->value('enddate') ?>">
                                                <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('enddate');
                                                              ?>
                                                       </span>     
                                        </div>
                            </div>
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
//SELECT c.cid,c.iid,s.subject,se.name as sec_name,se.class_id,cl.name,cl.numeric_val FROM `course` c join subjects s ON c.sid=s.sid join section se ON c.`secid`=se.sid JOIN class cl ON se.class_id =cl.id  order by cl.numeric_val DESC  

?>