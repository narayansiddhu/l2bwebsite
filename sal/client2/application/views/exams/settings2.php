<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$days =array();
$t=$strt=$exam->startdate;
$end=$exam->enddate;
while($t<=$end){
  $days[]=date("d-m-y",$t);
  $t=strtotime('+1 day', $t);
}
 $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 ");
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
            
            
            <div class="box box-bordered box-color" >
                <div class="box-title">
                    <h3>Exam Settings Of <?php echo ucfirst($exam->exam) ?> </h3>
                </div>
                <div class="box-content nopadding"> 
                    <form method="post" action="<?php echo base_url() ?>index.php/Exams/Save_daily_exam_settings/">
                        
                    
                    <table class="table table-bordered table-striped " >
                        <tr>
                            <th>S.no</th>
                            <th>class-section</th>
                            <th>exam Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>max-marks</th>
                            <th>Min-Marks</th>
                        </tr>
                    
                    <?php
                    $i=1;$secids="";
                    foreach ($query as $value) {
                        $secids.=$value->sid.",";
                        ?>
                    <tr>
                        <td><?php echo $i++; ?>
                            <input type="hidden" name="exam_id_<?php echo $value->sid ?>" value="<?php echo $value->id ?>" />
                            <input type="hidden" name="ecid_<?php echo $value->sid ?>" value="<?php echo $value->ec ?>" />
                        <?php
                        
                        ?></td>
                        <td><?php echo $value->class." - ".$value->section ?></td>
                        <td><select class="select2-me"  name="day_<?php echo $value->sid ?>" <?php
                                          if(strlen($this->form->error('day_'.$value->sid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000; width:100%"';
                                          }else{
                                                echo  ' style="width:100%"';
                                          }
                                          
                                           ?> >
                                             <?php
                                               $edate ="";
                                                if(strlen($this->form->value('day_'.$value->sid))>0 ){
                                                   $edate= $this->form->value('day_'.$value->sid);
                                                }else{
                                                    if(strlen($value->examdate)!=0){
                                                        $edate= date("d-m-y",$value->examdate);  
                                                    }
                                                }
                                                ?>
                                                <option value="" >Exam Date</option>
                                                <?php
                                                foreach ($days as $day) {
                                                    ?><option value="<?php echo $day; ?>" <?php
                                                    if($day==$edate){
                                                       echo "selected";
                                                    }?>  ><?php echo $day; ?></option><?php
                                                }
                                             ?>
                                            </select>
                            <span style=" color: red; "><?php echo  $this->form->error('day_'.$value->sid) ?></span>
                        </td>
                        <td><?php 
                            $starttime ="";
                            if(strlen($this->form->value('start_'.$value->sid))>0 ){
                                 $starttime= $this->form->value('start_'.$value->sid);
                              }else{
                                  if(strlen($value->starttime)!=0){
                                     $starttime=  $value->starttime;
                                     $starttime=date('H:i',$starttime); 
                                  }

                              }

                           ?>
                          <input <?php
                        if(strlen($this->form->error('start_'.$value->sid))>0 ){
                               echo  ' style="border:1px solid #FF0000"';
                        }
                        ?>  type="text" min="0" name="start_<?php echo $value->sid ?>" class="form-control timepick" value="<?php 
                        if(strlen($starttime)>0){
                         echo $starttime;   
                        }
                         ?>" placeholder="Start Time" />
                        <span style=" color: red; "><?php echo  $this->form->error('start_'.$value->sid) ?></span>
                        </td>
                        <td><?php 
                            $endtime ="";
                            if(strlen($this->form->value('end_'.$value->sid))>0 ){
                                 $starttime= $this->form->value('end_'.$value->sid);
                              }else{
                                  if(strlen($value->endtime)!=0){
                                     $endtime=  $value->endtime;
                                     $endtime=date('H:i',$endtime); 
                                  }
                              }

                           ?>
                          <input <?php
                        if(strlen($this->form->error('end_'.$value->sid))>0 ){
                               echo  ' style="border:1px solid #FF0000"';
                        }
                        ?>  type="text" min="0" name="end_<?php echo $value->sid ?>" class="form-control timepick" value="<?php 
                        if(strlen($endtime)>0){
                         echo $endtime;   
                        }
                         ?>" placeholder="End Time" />
                        <span style=" color: red; "><?php echo  $this->form->error('end_'.$value->sid) ?></span>
                        
                        </td>
                        <td><input type="text"  <?php
                                          if(strlen($this->form->error('max_'.$value->sid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                          ?> placeholder="max Marks"  name="max_<?php echo $value->sid ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('max_'.$value->sid))>0 ){
                                                  echo $this->form->value('max_'.$value->sid);
                                                }else{
                                                  if(strlen($value->maxmarks)!=0){
                                                       echo $value->maxmarks;
                                                    }
                                                  
                                                }?>"   />
                        <span style=" color: red; "><?php echo  $this->form->error('max_'.$value->sid) ?></span>
                        
                        </td>
                        <td><input type="text"  <?php
                                          if(strlen($this->form->error('min_'.$value->sid))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                          ?> placeholder="max Marks"  name="min_<?php echo $value->sid ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('min_'.$value->sid))>0 ){
                                                  echo $this->form->value('min_'.$value->sid);
                                                }else{
                                                  if(strlen($value->minmarks)!=0){
                                                       echo $value->minmarks;
                                                    }
                                                  
                                                }?>"   />
                        <span style=" color: red; "><?php echo  $this->form->error('min_'.$value->sid) ?></span>
                        
                        </td>
                        
                    </tr>
                        <?php
                    }
                    
                    ?>
                    <input type="hidden" name="section_ids" value="<?php echo substr($secids, 0,  strlen($secids)-1)  ?>" />
                    <input type="hidden" name="exam" value="<?php echo $exam->id  ?>" />
                    
                    <tr>
                        <td colspan="7" style=" text-align: center;"><button class="btn btn-primary" type="submit" name="submit">Save Exam Settings</button></td>
                    </tr>
                    </table>
                        </form>
                </div>
            </div>
   </div>
 </div>
</div>
<?php
$this->load->view('structure/footer');
?>