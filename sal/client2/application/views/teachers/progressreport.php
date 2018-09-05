<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script>
    function sendsms( section ,exam ){
        $('#send_sms_btn').prop( "disabled", true ); 
         setState('errors','<?php echo base_url() ?>index.php/teachers/send_report','section='+section+'&exam='+exam);
         $('#send_sms_btn').prop( "disabled", false ); 
    }
</script>

<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    
    <?php 
    $sections=array();
    $query=$this->db->query("SELECT  DISTINCT(c.secid) ,s.name as section, cl.name as class FROM `course` c JOIN section s ON c.secid=s.sid JOIN class cl ON s.class_id=cl.id where tid='".$this->session->userdata('staff_id')."'");
    $query=$query->result();
    foreach($query as $value){
          $sections[$value->secid]=array('id'=>$value->secid,'section'=>$value->section,'class'=>$value->class);       
    }
    $query=$this->db->query("SELECT s.sid,s.name as section ,cl.name as class FROM `section`  s  JOIN class cl ON s.class_id=cl.id WHERE cls_tch_id='".$this->session->userdata('staff_id')."'");
    $query=$query->result();
    foreach($query as $value){
          $sections[$value->sid]=array('id'=>$value->sid,'section'=>$value->section,'class'=>$value->class);       
    }
    ?>
    
    <div class="col-sm-12">
        <div  class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        
                        <li>
                            <a href="">Exam Results</a>
                        </li>
                    </ul>

            </div>
        
            <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Results</h3>
                            <div class="actions">
                                    <a href="#" class="btn btn-mini content-slideUp">
                                            <i class="fa fa-angle-down"></i>
                                    </a>
                            </div>
                    </div>
                    <div class="box-content nopadding">
                        <div class='form-horizontal form-bordered' >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Class - Section</label>
                                <div class="col-sm-10">
                                    <select class="select2-me" id="section" name="section" onchange="fetch_exams();"   style=" width: 100% "  >
                                        <option value="" >Select A Section</option>
                                        <?php
                                          foreach ($sections as $value) {
                                              ?><option value="<?php echo $value['id'] ?>" ><?php echo $value['class']." - ".$value['section'];  ?></option><?php 
                                          }                                            
                                        ?>
                                       </select> 
                                    <span id="section_err" style=" color: red">
                                              
                                    </span>  
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Exam</label>
                                <div class="col-sm-10" id="exam_holder" >
                                    <select disabled="" class="select2-me" id="exam" name="exam" onchange=""   style=" width: 100% "  >
                                         <option value="" >Please select Section</option>
                                        
                                       </select> 
                                    <span id="exam_err" style=" color: red">
                                              
                                    </span>  
                                </div>
                            </div> 
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="button" disabled="" id="fetch"  onclick="fetch_reports();" value="Fetch Exam Results" class="btn btn-primary" />
                                <span id="errors" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                               ?>
                                    </span> 
                            </div>
                        </div>
                    </div>
            </div> 
    
    
    <script>
     function fetch_reports(){
        $( "#section" ).prop( "disabled", true ); 
        $( "#exam" ).prop( "disabled", true ); 
        $( "#fetch" ).prop( "disabled", true ); 
        
        $('#section_err').html("");
        $('#exam_err').html("");
        //exam_err
        section =$("#section").val();
        exam =$("#exam").val();
    
        if(section.length==0){
            $('#section_err').html("Please select Section");
        }else{
            
            if(exam.length==0){
              
               $('#exam_err').html("Please select exam");
            }else{
              setState('results','<?php echo base_url() ?>index.php/teachers/fetch_marks_report','section='+section+'&exam='+exam);
            }
            //
        }
         $( "#section" ).prop( "disabled", false ); 
         $( "#exam" ).prop( "disabled", false ); 
         $( "#fetch" ).prop( "disabled", false ); 
     }    
     
     function fetch_exams(){
        $( "#fetch" ).prop( "disabled", true ); 
        $( "#exam" ).prop( "disabled", true ); 
        $( "#exam_holder" ).html("");
        $('#section_err').html("");
        section =$("#section").val();
        if(section.length==0){
            $('#section_err').html("Please select Section");
        }else{
            setState('exam_holder','<?php echo base_url() ?>index.php/teachers/fetch_exams','section='+section);
            //
        }
         $( "#section" ).prop( "disabled", false ); 
         $( "#exam" ).prop( "disabled", false ); 
         
     } 
     
     
     //results_holder
    
   
    
     
     
     
    </script>
    
    <div class="box" id="results" >
       
            
    </div>
    
    </div>
    </div>
</div>
                               
                          

<?php
$this->load->view('structure/footer');
?>
