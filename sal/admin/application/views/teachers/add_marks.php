<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">


           <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                              <?php echo $section->class ?>,<?php echo $section->section ?>  - <?php echo $exam->exam ?>,<?php echo $exam->subject ?> Mark's List
                        </h3>
                        
                </div>
                <div  class="box-content nopadding">
                    <div id="download_box" class="row">
                        <br/>
                        <div class="col-sm-4">
                            &nbsp;
                        </div>
                        <div class="col-sm-4">
                            <a onclick="$('#exam_report').toggle();$('#download_box').toggle();" class="btn btn-primary btn-block" href="<?php echo base_url() ?>index.php/teachers/get_sample_file/<?php echo $exam->id ?>/<?php echo $section->sid ?>">Download Marks list</a>
                              <br/>
                        </div>
                        <div class="col-sm-4">
                            &nbsp;
                        </div>
                        <br/><br/>
                    </div>
                   
                    
                    
                    <form  style=" display: none;"id="exam_report" class="form-horizontal form-bordered" method="post" action="<?php echo base_url(); ?>index.php/teachers/submit_marks" enctype="multipart/form-data">
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
                         <input type="hidden" name="examid" value="<?php echo $exam->id ?>" />
                        <input type="hidden" name="maxmarks" value="<?php echo $exam->maxmarks ?>" />
                        <input type="hidden" name="minmarks" value="<?php echo $exam->minmarks ?>" />
                        <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                        
                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="submit" name="Upload_file" class="btn btn-primary" value="Upload Marks" />
                        </div>
                        
                    </form>
                    
                    <?php
                     if(strlen($this->form->error("file"))>0){
                         ?>
                            <script>
                              $('#exam_report').toggle();
                              $('#download_box').toggle();
                            </script>
                        <?php
                     }
                    ?>
                    
                </div>
                
            </div> 
                 
        </div>
    </div>
</div>
        

<?php
$this->load->view('structure/footer');
?>