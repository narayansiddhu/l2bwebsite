<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=$section = $this->input->get("section");
$course_err ="";
$show_details=0;
if( strlen($t)!=0 ){
    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
  if($section_query->num_rows()==0){
       $course_err ="** In-valid Selection  ";
  }else{
    
    $section_query =$section_query->row();
    $course = $this->db->query("SELECT c.cid,s.subject,s.sid FROM course c  JOIN subjects s ON c.subid=s.sid where secid='".$t."' ");
    if($course->num_rows()>0){
        $course = $course->result();
        $show_details =1;
    }else{
        $course_err ="** Please Configure Course Structure  ";
    }
  }
}

 $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from mcexam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
 $query=$query->result();
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
                    <a href="<?php echo base_url(); ?>index.php/exams/">Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url(); ?>index.php/exams/settings/<?php echo $exam->id ?>">exam Settings</a>
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
    <?php 
       if(strlen($this->form->value('ecid'))){
           $_SESSION['value_array'] = $this->form->values;
           $_SESSION['error_array']=$this->form->errors;
           ?>
                 <input type="hidden"  id="sec_id" value="<?php echo $this->form->value('ecid'); ?>"  />
            <script>
              section=$('#sec_id').val();
             
               setState('view_setting','<?php echo base_url() ?>index.php/ajax/exam_Settings','examid='+<?php echo $exam->id ?>+'&ecid='+section+'&from='+<?php echo $exam->startdate?>+'&end='+<?php echo $exam->enddate ?>);  
            </script>
           <?php
       }
    ?> 
            <div class="row">
                <div class="col-sm-8">
                    <div class="box box-bordered box-color ">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Settings Of <?php echo $exam->exam ?> </h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <div class='form-horizontal form-bordered'  >
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Select Section</label>
                                        <div class="col-sm-10">
                                            <select id="section_id" class="select2-me" style=" width: 100% ">
                                                <option value="" >Select A Section</option>
                                                <?php
                                                 foreach ($query as  $value) {
                                                     ?>
                                                <option value="<?php echo $value->sid ?>"
                                                        <?php
                                                        if($t==($value->sid)){
                                                            echo "selected";
                                                        }
                                                        ?>
                                                        ><?php echo $value->class." - ".$value->section ;
                                                          if( $value->sub_count ==0){
                                                              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   (Not Configured)";
                                                          }else{
                                                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (Configured)";
                                                         }
                                                        
                                                        ?>
                                                         
                                                     </option>
                                                     <?php
                                                 }
                                                ?>
                                            </select>
                                            <span id="subname_err" style=" color: red">
                                                 <?php echo $this->form->error('exam') ?>   
                                            </span>        
                                        </div>
                                </div> 
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-primary" onclick="fetch_settings();" >Fetch Exam Setting's </button>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-bordered box-color ">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Exam's List</h3> 
                        </div>
                        <div class="box-content nopadding" style=" max-height:  300px; overflow-y: scroll ">
                            <table class="table table-bordered table-striped" style=" width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Class-section</th>
                                        <th>exam's</th>
                                    </tr>
                                </thead>
                                <?php
                                foreach ($query as  $value) {
                                    ?>
                                <tr>
                                    <td><?php echo $value->class." - ".$value->section ?></td>
                                    <td><?php echo $value->sub_count ?></td>
                                </tr>
                                   <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                
                <h4 style=" color: red; text-align: center"><?php echo $course_err ?></h4> 
            </div>
                
        
                
           <?php
              if($show_details==1){
                    $query=  $this->db->query("SELECT e.id,sub.sid,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`questions` FROM `mcexam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$section_query->id."'  ORDER BY sub.sid asc");
                    $query=$query->result();
                    $days =array();
                    $t=$strt=$exam->startdate;
                    $end=$exam->enddate;
                    while($t<=$end){
                      $days[]=date("d-m-y",$t);
                      $t=strtotime('+1 day', $t);
                    }
                    
                    $exam_settings = array();
                    foreach($query as $val){
                        $exam_settings[$val->sid]=array('exam_id'=>$val->id,'date'=>$val->examdate,'starttime'=>$val->starttime,'endtime'=>$val->endtime,'timespan'=>$val->timespan,'maxmarks'=>$val->maxmarks,'questions'=>$val->questions);
                    }
                    

                    ?>
              
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3><i class="fa fa-cogs" aria-hidden="true"></i> Settings Of <?php echo $section_query->class.",".$section_query->section ?> </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exams/savemcsettings" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="examid" value="<?php echo  $exam->id ?>"/>
                                    <input type="hidden" name="ecid" value="<?php echo  $section_query->id ?>"/>
                                    <input type="hidden" name="section_details" value="<?php echo  $section_query->sid ?>"/>
                                    
                         <table style=" width: 100%" class="table table-hover table-nomargin table-bordered" >
                            <thead>
                                <tr>
                                    <th>
                                    <input type="checkbox" id='checking_all' onchange="check_select_all();"  name="check_all" value="2" />&nbsp;&nbsp; Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start Time</th>
                                    <th>Ending Time</th>
                                    <th>Questions</th>
                                    <th>Max Marks</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                  $ids="";$old_ids="";
                                  foreach ($course as $value){
                                      $ids.= $value->cid.",";
                                      ?>
                                <tr>
                                    <td>
                                        <?php 
                                          if(isset($exam_settings[$value->sid])){
                                              $old_ids.=$exam_settings[$value->sid]['exam_id'].",";
                                              ?>
                                        <input  type="hidden" name="old_exam_id_<?php echo $value->cid ?>" value="<?php echo $exam_settings[$value->sid]['exam_id']  ?>" />
                                             <?php
                                          }
                                        ?>
                                        <input  type="hidden" name="course_Details_<?php echo $value->cid ?>" value="<?php echo $value->cid.",".$value->sid ?>" />
                                        
                                        <input type="checkbox" value="<?php echo $value->cid ?>" name="subject_<?php echo $value->cid ?>" id="subject_<?php echo $value->cid ?>"
                                               <?php
                                               if(strlen($this->form->value('subject_'.$value->cid))>0){
                                                  echo "checked"; 
                                               }else{
                                                   if(isset($exam_settings[$value->sid])){
                                                      echo "checked"; 
                                                   }
                                               }
                                               ?>
                                               
                                               />
                                        &nbsp;&nbsp;
                                        <?php echo $value->subject ?></td>
                                    <td>
                                            <select class="select2-me"  name="day_<?php echo $value->cid ?>" <?php
                                          if(strlen($this->form->error('day_'.$value->cid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000; width:100%"';
                                          }else{
                                                echo  ' style="width:100%"';
                                          }
                                          
                                           ?> >
                                             <?php
                                               $edate ="";
                                                if(strlen($this->form->value('day_'.$value->cid))>0 ){
                                                   $edate= $this->form->value('day_'.$value->cid);
                                                }else{
                                                    if(isset($exam_settings[$value->sid])){
                                                        $edate= date("d-m-y",$exam_settings[$value->sid]['date']);  
                                                    }
                                                }
                                                ?>
                                                <option value="" >Date</option>
                                                <?php
                                                foreach ($days as $day) {
                                                    ?><option value="<?php echo $day; ?>" <?php
                                                    if($day==$edate){
                                                       echo "selected";
                                                    }?>  ><?php echo $day; ?></option><?php
                                                }
                                             ?>
                                            </select>
                                           </td>
                                        <td>
                                            <?php 
                                              $starttime ="";
                                              if(strlen($this->form->value('start_'.$value->cid))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->cid);
                                                }else{
                                                    if(isset($exam_settings[$value->sid])){
                                                     //   echo $exam_settings[$value->sid]['starttime'];
                                            
                                                       $starttime=  $exam_settings[$value->sid]['starttime'];
                                                       $starttime=date('H:i',$starttime); 
                                                    }
                                                    
                                                }
                                                 
                                             ?>
                                            <input <?php
                                          if(strlen($this->form->error('start_'.$value->cid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                          ?>  type="text" min="0" name="start_<?php echo $value->cid ?>" class="form-control timepick" value="<?php 
                                          if(strlen($starttime)>0){
                                           echo $starttime;   
                                          }
                                           ?>" placeholder="Start Time" />
                                        </td>
                                        <td>
                                            <?php 
                                           $end_time ="";
                                              if(strlen($this->form->value('ending_'.$value->cid))>0 ){
                                                   $end_time= $this->form->value('start_'.$value->cid);
                                                }else{
                                                    if(isset($exam_settings[$value->sid])){
                                                        // echo $exam_settings[$value->sid]['endtime'];
                                            
                                                       $end_time=  $exam_settings[$value->sid]['endtime'];
                                                       $end_time=date('H:i',$end_time); 
                                                    }
                                                  
                                                }
                                                 
                                             ?>
                                            
                                            <input <?php
                                          if(strlen($this->form->error('span_'.$value->cid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  type="text"  name="ending_<?php echo $value->cid ?>" class="form-control timepick" value="<?php 
                                          if(strlen($end_time)>0){
                                           echo $end_time;   
                                          }
                                           ?>"  />
                                        </td>
                                        <td>
                                            <input type="text"
                                                   <?php
                                          if(strlen($this->form->error('questions_'.$value->cid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                          ?>  placeholder="Total Questions"  name="questions_<?php echo $value->cid ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('questions_'.$value->cid))>0 ){
                                                  echo $this->form->value('questions_'.$value->cid);
                                                }else{
                                                  if(isset($exam_settings[$value->sid])){
                                                       echo $exam_settings[$value->sid]['questions'];
                                                    }
                                                }?>"  /></td>
                                        <td><input type="text"  <?php
                                          if(strlen($this->form->error('max_'.$value->cid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                          ?> placeholder="max Marks"  name="max_<?php echo $value->cid ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('max_'.$value->cid))>0 ){
                                                  echo $this->form->value('max_'.$value->cid);
                                                }else{
                                                  if(isset($exam_settings[$value->sid])){
                                                       echo $exam_settings[$value->sid]['maxmarks'];
                                                    }
                                                  
                                                }?>"   /></td>
                                        
                                </tr>
                                      <?php
                                  }
                                  
                                  
                                   $ids=substr($ids,0,strlen($ids)-1);
                                   $old_ids=substr($old_ids,0,strlen($old_ids)-1);
                                   
                                ?>
                            <input type="hidden" id='course_ids' name="course_ids" value="<?php echo $ids ?>" />
                            <input type="hidden" id='course_ids' name="old_ids" value="<?php echo $old_ids ?>" />
                            
                            <tr>
                                <td colspan="6">
                                    <div class="row">
                                        
                                        <div class="col-sm-4" >
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="col-sm-4" >
                                           <button class="btn btn-primary btn-block">Save Setting's</button>
                                        </div>
                                        <div class="col-sm-4" >
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                        
                                    </div>
                                </td> 
                            </tr>
                            </tbody>
                            
                         </table> 
                        <span style=" color: red" >
                           <?php 
                                      $idsarr=  explode(',', $ids);
                                      $c=0;
                                      foreach ($idsarr as $id) {
                                          if(strlen($this->form->value('slot_error_'.$id))>0 ){
                                                 echo "<br/>".$this->form->value('slot_error_'.$id);
                                               }
                                      }
                                   ?>
                        </span>
                                    
                        
                             
                    </form>
                </div>
             </div>
                  
                 <?php
                  
              }
           
           ?>
      
                
           <script>
               function fetch_settings(){
                    section=$('#section_id').val();
                    if(section.length!=0){
                       window.location.href = "<?php echo base_url() ?>index.php/exams/settings/"+<?php echo $exam->id ?>+"?section="+section;
                    }else{
                        $('#subname_err').html("** Please select Secton");
                    }
            }
              function check_select_all(){
                 ids= $('#course_ids').val(); 
                 ids = ids.split(',');
                 if($('#checking_all').prop('checked')){
                     for (var i in ids) {
                          $('#subject_'+ids[i]).prop('checked',true);
                          }
                  }else{
                      for (var i in ids) {
                        $('#subject_'+ids[i]).prop('checked',false);
                        }
                  }
              } 
           </script>
      
   </div>
 </div>
</div>
            

<?php
$this->load->view('structure/footer');
?>