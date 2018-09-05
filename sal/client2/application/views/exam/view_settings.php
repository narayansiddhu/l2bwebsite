<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

    
<?php
 $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
 $query=$query->result();
 ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box" >
            
        
       <br/>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exam/">Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">View exam Settings</a>
                </li>
            </ul>
        </div>
        <?php
        if(strlen($this->session->userdata('Section_exam_Settings'))>0 ){
            ?><br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Section_exam_Settings'); ?>
                </div>
           <?php
            $this->session->unset_userdata('Section_exam_Settings');
        }
        ?>
   

               
                    <div class="box box-bordered box-color ">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Settings Of <?php echo $exam->exam ?> </h3>
                                            <div class="actions">
                                                <a href="<?php echo base_url() ?>index.php/exam/settings/<?php echo $exam->id  ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i>&nbsp;Edit</a>
                                            </div>
                            </div>
                            <div class="box-content nopadding">                                
                                <div class='form-horizontal form-bordered'  >
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Select Section</label>
                                            <div class="col-sm-10">
                                                <select onchange="fetch_settings();" id="section_id" class="select2-me" style=" width: 100% ">
                                                    <option value="" >Select A Section</option>
                                                    <?php
                                                     foreach ($query as  $value) {
                                                         ?>
                                                    <option value="<?php echo $value->id."-".$value->sid ?>" ><?php echo $value->class." - ".$value->section ?></option>
                                                         <?php
                                                     }
                                                    ?>
                                                </select>
                                                <span id="subname_err" style=" color: red">
                                                     <?php echo $this->form->error('exam') ?>   
                                                </span>        
                                            </div>
                                    </div> 
                                    
                                </div>
                            </div>
                    </div>
                
           


      
       
             <div id='view_setting'  class="box-content nopadding">
           
             </div>
        
      
                        
 
</div>
    </div>
</div>
<script>
    function fetch_settings(){
        section=$('#section_id').val();
        if(section.length!=0){
      setState('view_setting','<?php echo base_url() ?>index.php/ajax/viewexam_Settings','examid='+<?php echo $exam->id ?>+'&ecid='+section+'&from='+<?php echo $exam->startdate?>+'&end='+<?php echo $exam->enddate ?>);  
    }else{
        $('#subname_err').html("** Please select Secton");
    }
    }
</script>
<?php
$this->load->view('structure/footer');
?>