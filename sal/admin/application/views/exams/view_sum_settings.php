<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');

 $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
 $query=$query->result();
 ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box" >
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Manage Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">View exam Settings</a>
                </li>
            </ul>
        </div>
            <div class="row" style=" padding-top: 15px;">
           <div class="col-sm-12">
                <div class="pull-left">
                    <h3 style=" color: #66cc00;"><?php echo strtoupper($exam->exam) ?>(<?php echo date('d-m-y',$exam->startdate) ?> TO <?php echo date('d-m-y',$exam->enddate) ?>)</h3>
                </div>
                <div class="pull-right">
                    <ul class="minitiles">   
                        <?php
                         if(time()<$exam->startdate){
                             ?>
                        <li class="blue">
                            <a href="<?php echo base_url() ?>index.php/exams/settings/<?php echo $exam->id ?>" rel="tooltip" title="" data-original-title="Edit exam settings " ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </li>
                        <li class="green">
                            <a href="<?php echo base_url(); ?>index.php/exams/assign/<?php echo  $exam->id ?>"   rel="tooltip"  title="" data-original-title="Re-Assign Exam" >
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </a>
                        </li>
                        
                             <?php
                         }
                        ?>
                        
                        <li class="orange">
                            <a target="_blank" href="<?php echo base_url() ?>index.php/exams/print_schedule/<?php echo $exam->id ?>" rel="tooltip" title="" data-original-title="Print Exam Schedule" >
                                <i class="fa fa-print"></i>
                            </a>
                        </li>
                        
                    </ul>
                </div>
           </div>
           
       </div>
       <hr/>
        <?php
        if(strlen($this->session->userdata('Section_exam_Settings'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Section_exam_Settings'); ?>
                </div>
            <script>
                            $("#successMsg").fadeIn();
                            $("#successMsg").delay(2000).fadeOut();
                       </script>
           <?php
            $this->session->unset_userdata('Section_exam_Settings');
        }
        ?>
    <div class="box" id="exam_schedule" style=" max-height: 550px; overflow-y: scroll">
            
      <?php
        foreach ($query as  $value) {
            ?>
            <div class="box">
                <div class="box-title">
                    <h3 style=" color:#66cc00 ">
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;Exam Schedule Of <?php echo $value->class." - ".$value->section ?>
                    </h3>
                                <div class="actions">
                                    <a   target="_blank" href="<?php echo base_url() ?>index.php/exams/halltickets/<?php echo $value->id ?>" rel="tooltip" title="" data-original-title="Download Halltickets " class="btn "><i class="fa fa-ticket" aria-hidden="true"></i></a>
                                 <?php
                                  if(time()<$exam->startdate){
                                    ?>
                                    <a href="<?php echo base_url() ?>index.php/exams/settings/<?php echo $exam->id  ?>?section=<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="Edit exam settings " class="btn"><i class="fa fa-pencil-square-o"></i></a>
                                    
                                    <?php
                                  }else{
                                     ?>
                                      <a href="<?php echo base_url() ?>index.php/exams/add_results/<?php echo $exam->id  ?>/<?php echo $value->sid ?>" target="_blank" rel="tooltip" title="" data-original-title="Add Marks " class="btn "> <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                      <a href="<?php echo base_url() ?>index.php/exams/results/<?php echo $exam->id  ?>?section=<?php echo $value->sid ?>" target="_blank" rel="tooltip" title="" data-original-title="Exam Results " class="btn "><i class="fa fa-adn" aria-hidden="true"></i></a>
                                   <?php  
                                  }
                                 ?>
                                  
                                </div>
                </div>
                <div id='exam_scheddule_<?php echo $value->sid ?>' class="box-content nopadding"> 
                    
                </div>
                <script>
                       setState('exam_scheddule_<?php echo $value->sid ?>','<?php echo base_url() ?>index.php/ajax/viewexam_Settings','examid='+<?php echo $exam->id ?>+'&ecid=<?php echo $value->id."-".$value->sid ?>&from='+<?php echo $exam->startdate?>+'&end='+<?php echo $exam->enddate ?>);    
                </script>
            </div>
          <?php
        }
       ?>
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