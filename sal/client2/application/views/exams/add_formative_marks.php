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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li> 
                <li>
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section_array->section ." - ".$section_array->class ?> </a>
                </li> 
            </ul>
            <a style=" float: right; margin-top:3px; margin-right: 15px;" class="btn btn-mini btn-primary" href="<?php echo base_url(); ?>index.php/exams/results/<?php echo $exam->id ?>?section=<?php echo $section_array->sid ?>&grading=cce">Change Grading To G.P.A</a>
        </div>
           <br/>
            <div class="box">
                <div class="col-sm-5 nopadding">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3 > <i class="fa fa-plus"></i>&nbsp;Exam Details</h3>
                        </div>
                        <div class="box-content nopadding">
                    <table class="table table-bordered table-striped"  >
                        <tr>
                            <th>Class-section</th>
                            <td> <?php echo $section_array->class." - ".$section_array->section  ?></td>
                        </tr>
                        <tr>
                            <th>Exam Name</th>
                            <td> <?php echo $exam->exam  ?></td>
                        </tr>
                        <tr>
                            <th>Subject Name</th>
                            <td> <?php echo $exam_details->subject  ?></td>
                        </tr>
                        <tr>
                            <th>Max Marks</th>
                            <td> <?php echo $exam_details->maxmarks  ?></td>
                        </tr>
                    </table>   
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 nopadding " >
                <div class="box box-color box-bordered" style=" padding-left: 10px;">
                        <div class="box-title">
                            <h3 > <i class="fa fa-plus"></i>&nbsp;Upload Mark Sheet</h3>
                        </div>
                        <div class="box-content nopadding">
                            <form class='form-horizontal form-bordered'  method="post" action="<?php echo base_url(); ?>index.php/exams/submit_formativemarks" enctype="multipart/form-data" >
                                <div class="form-group" >
                                        <label for="textfield" class="control-label col-sm-2">Exam</label>
                                        <div class="col-sm-10" id="exam_holder" >
                                            <input type="file" name="file" class="form-control" /> 
                                            <span id="exam_err" style=" color: red">
                                                  <?php
                                                   echo $this->form->error("file");
                                                  ?>
                                            </span> 
                                        </div>
                                </div>
                                
                                <input type="hidden" name="exam" value="<?php echo $exam->id ?>" />
                                <input type="hidden" name="section" value="<?php echo $section_array->sid ?>" />
                                <input type="hidden" name="eid" value="<?php echo $exam_details->id ?>" />
                                <input type="hidden" name="ecid" value="<?php echo $section_array->id ?>" />
                                <input type="hidden" name="exams_details" value='<?php echo $exam_details->id ?>' />
                                
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="Upload_file" class="btn btn-primary" value="Upload Marks" />
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