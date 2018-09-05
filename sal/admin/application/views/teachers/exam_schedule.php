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
                            <a href="<?php echo base_url(); ?>index.php/teachers/view_exams">Exam Schedule</a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                         <li>
                            <a href="">Schedule Of  <?php echo $exam->exam ?></a>
                        </li>
                    </ul>

            </div>
     
    
        <div class="box box-color box-bordered nopadding">
            <div class="box-title" style=" ">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                               Schedule Of  <?php echo $exam->exam ?>
                        </h3>
                        <div class="actions">
                                <a href="#" class="btn btn-mini content-refresh">
                                        <i class="fa fa-refresh"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-remove">
                                        <i class="fa fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-slideUp">
                                        <i class="fa fa-angle-down"></i>
                                </a>
                        </div>
                </div>
                <div class="box-content nopadding">
                    <div class='form-horizontal form-bordered' >
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Section</label>
                            <div class="col-sm-10">
                                <select id="section" name="section" class="select2-me" style=" width: 100%" > 
                                    <option value="">Select A Section</option>
                                    <?php
                                    foreach ($class_list as $value) {
                                        ?><option value="<?php echo $value->id ?>"><?php echo $value->cls_name." - ".$value->section ?></option><?php
                                    }
                                    ?>
                                </select>
                                <span id="new_date_err" style=" color: red">
                                           <?php
                                               echo $this->form->error('stdob');
                                              ?>
                                </span>  
                            </div>
                        </div> 
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="button" id="add" onclick="fetch_scheddule();" name="add" value="Fetch Exam Schedule" class="btn btn-primary" />
                            <span id="errors" style=" color: red">
                                           <?php
                                               echo $this->form->error('stdob');
                                           ?>
                                </span> 
                        </div>
                    </div>
                </div>
        </div>
    
    <div class="box" id="scheduletiming"  >
        
    </div>
        
   
    <script>
      function fetch_scheddule(){
          $('#errors').html("");
          $('#scheduletiming').html("");
         var section=$('#section').val();
          if(section.length==0){
              $('#errors').html("Please select a section");
          }else{
               setState('scheduletiming','<?php echo base_url() ?>index.php/teachers/fetch_schedule','ecid='+section+'&examid=<?php echo $exam->id ?>'+'&type=<?php echo $exam->type ?>');
      
          }
      }    
    </script>
</div>
 </div> 
</div>
<?php
$this->load->view('structure/footer');


//SELECT * FROM `exam` where courseid in (1,7,13)
?>