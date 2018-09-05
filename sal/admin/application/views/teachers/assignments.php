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
                            <a href="">Add Assignments</a>
                        </li>
                    </ul>

            </div>
          
                <?php
                $course=$this->db->query("SELECT cr.cid,s.name  as section,c.name as cls_name,sub.subject FROM course cr JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid  where cr.tid='".$this->session->userdata('staff_id')."'");
                $course=$course->result();
                ?>
            <?php
            if(strlen($this->session->userdata('assignments_insert'))>0 ){
                ?><br/>
                    <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Success!</strong>
                     <?php echo $this->session->userdata('assignments_insert'); ?>
                    </div>
               <?php
                $this->session->unset_userdata('assignments_insert');
            }
        ?>
    
    
    
        <div class="box box-color box-bordered nopadding">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                              Issue Assignment
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
                    <form class='form-horizontal form-bordered' method="post" action="<?php echo base_url() ?>/index.php/teachers/add_assignment" >
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Course</label>
                            <div class="col-sm-10">
                                <select id="course" name="course" class="select2-me" style=" width: 50%" > 
                                    <option value="">Section Course</option>
                                    <?php
                                        foreach ($course as $value) {
                                          ?>
                                    <option value="<?php echo $value->cid ?>" <?php  
                                       if($this->form->value('course')==$value->cid){
                                           echo "SELECTED";
                                       }?>  ><?php echo $value->cls_name." , ".$value->section ."  --  ".$value->subject ?></option>
                                         <?php
                                        }  
                                    ?>
                                </select>
                                <span id="course_err" style=" color: red">
                                     <?php echo $this->form->error('course'); ?>      
                                </span>  
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Submission Date</label>
                            <div class="col-sm-10">
                                <input type="text" name="sub_date" id="textfield" style=" width: 50%;"  value="<?php echo $this->form->value('sub_date'); ?>" class="form-control datepick"/>
                                <span id="new_date_err" style=" color: red">
                                        <?php echo $this->form->error('sub_date'); ?>   
                                </span>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Assignment</label>
                            <div class="col-sm-10">
                                <textarea name="assignment" style=" resize: none" class="form-control"><?php  echo $this->form->value('assignment');   ?></textarea>
                                <span id="new_date_err" style=" color: red">
                                       <?php echo $this->form->error('assignment'); ?>          
                                </span>  
                            </div>
                        </div>
                        
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="submit" id="add"  name="add" value="Add Assignment" class="btn btn-primary" />
                            <span id="errors" style=" color: red">
                                           <?php
                                               echo $this->form->error('stdob');
                                           ?>
                                </span> 
                        </div>
                    </form>
                </div>
        </div>
    
    <div class="row" id="scheduletiming"  >
        
    </div>
        
    </div>
  </div>
</div>
 

<?php
$this->load->view('structure/footer');


//SELECT * FROM `exam` where courseid in (1,7,13)
?>