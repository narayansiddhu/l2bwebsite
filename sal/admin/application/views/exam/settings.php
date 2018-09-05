<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=$section = $this->input->get("section");
$show_details=0;
if( (strlen($t)!=0)&&(sizeof(explode("-", $t))==2) ){
    $t = explode("-",$t);
    $section_query="SELECT ec.id,s.sid,s.name as section,c.name as class FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.name='".$t[1]."' AND c.name='".$t[0]."' AND s.iid='".$this->session->userdata('staff_Org_id')."' ";
    $section_query = $this->db->query($section_query);
    if($section_query->num_rows()>0){
      $show_details =1;  
      $section_query = $section_query->row();
    }
}
?>

    
<?php
 $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
 $query=$query->result();
 ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
        <br/>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exam/">Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url(); ?>index.php/exam/settings/<?php echo $exam->id ?>">exam Settings</a>
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

                
                    <div class="box box-bordered box-color ">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Settings Of <?php echo $exam->exam ?> </h3> 
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
                                                    <option value="<?php echo $value->class."-".$value->section ?>"
                                                            <?php
                                                            if($section==($value->class."-".$value->section)){
                                                                echo "selected";
                                                            }
                                                            ?>
                                                            ><?php echo $value->class." - ".$value->section ?></option>
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
                
           <?php
              if($show_details==1){
                    $query=  $this->db->query("SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$section_query->id."'  ORDER BY sub.sid asc");
                    $query=$query->result();
                    $days =array();
                    $t=$strt=$exam->startdate;
                    $end=$exam->enddate;
                    while($t<=$end){
                      $days[]=date("d-m-y",$t);
                      $t=strtotime('+1 day', $t);
                    }

                    ?>
              
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3><i class="fa fa-th-list"></i>Settings Of <?php echo $section_query->class.",".$section_query->section ?> </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exam/savesettings" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="examid" value="<?php echo  $exam->id ?>"/>
                                    <input type="hidden" name="ecid" value="<?php echo  $section_query->id ?>"/>
                                    <input type="hidden" name="section_details" value="<?php echo  $section_query->class.'-'.$section_query->section ?>"/>
                                    
                         <table style=" width: 100%" class="table table-hover table-nomargin table-bordered" >
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start Time</th>
                                    <th>Ending Time</th>
                                    <th>Max Marks</th>
                                    <th>Cut-Off Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                  $ids="";
                                  foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                            <select class="select2-me" name="day_<?php echo $value->id ?>" <?php
                                          if(strlen($this->form->error('day_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?> >
                                             <?php
                                               
                                                if(strlen($this->form->value('day_'.$value->id))>0 ){
                                                   $edate= $this->form->value('day_'.$value->id);
                                                }else{
                                                  $edate= date("d-m-y",$value->examdate);  
                                                }
                                                ?>
                                                <option value="" >select</option>
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
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <input <?php
                                          if(strlen($this->form->error('start_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  type="text" min="0" name="start_<?php echo $value->id ?>" class="form-control timepick" value="<?php echo $starttime ?>" />
                                        </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('ending_'.$value->id))>0 ){
                                                   $end_time= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $end_time=  $value->endtime; 
                                                  $end_time=date('H:i',$end_time); 
                                                }
                                                 
                                             ?>
                                            
                                            <input <?php
                                          if(strlen($this->form->error('span_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  type="text"  name="ending_<?php echo $value->id ?>" class="form-control timepick" value="<?php echo $end_time  ?>"  />
                                        </td>
                                        <td><input type="text"  <?php
                                          if(strlen($this->form->error('max_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  name="max_<?php echo $value->id ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>"   /></td>
                                        <td>
                                            <input type="text"
                                                   <?php
                                          if(strlen($this->form->error('min_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>   name="min_<?php echo $value->id ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('min_'.$value->id))>0 ){
                                                  echo $this->form->value('min_'.$value->id);
                                                }else{
                                                  echo $value->minmarks; 
                                                }?>"  /></td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            
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
                       window.location.href = "<?php echo base_url() ?>index.php/exam/settings/"+<?php echo $exam->id ?>+"?section="+section;
                    }else{
                        $('#subname_err').html("** Please select Secton");
                    }
            }
           </script>
      
   </div>
 </div>
</div>
            

<?php
$this->load->view('structure/footer');
?>