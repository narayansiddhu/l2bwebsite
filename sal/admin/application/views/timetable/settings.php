<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style>
    .redborder{
        border: 1px solid red;
    }
    </style>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');

$q=$this->db->query("SELECT c.cid,s.subject  FROM `course` c JOIN subjects s ON c.subid=s.sid  WHERE `secid` = '".$timetable->sid."'");
$q=$q->result();
$course=array();

foreach ($q as $key => $value) {
  $course[$value->cid] =$value->subject; 
}
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
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/">Time Table</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/<?php echo $timetable->sid ?>">View Time Table Of <?php echo $timetable->class ." , ".$timetable->section ?></a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Edit Time Table OF <?php echo $timetable->class ." , ".$timetable->section ?></a>
                </li>
            </ul>
    </div> 
    <?php
      if(sizeof($course)==0){
          ?>
            <br/>
            <div class="alert alert-warning alert-dismissable">
            <strong>Warning!</strong>
              Please add  course Structure to prepare Timetable
            </div>  
          <?php
      }else{
          ?>
            
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Time Table OF <?php echo $timetable->class ." , ".$timetable->section ?></h3> 

                        </div>
                        <div class="box-content nopadding"> 
                           <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/timetable/save_settings" method="post" enctype="multipart/form-data"  >

                            <table class="table table-hover table-nomargin" >
                                <thead>
                                    <tr>
                                        <th>Day/timings</th>
                                <?php
                                $weekdays = unserialize (Week_days);
                                $start=$timetable->start;
                                $noofc=$timetable->classes;
                                $span=$timetable->span;
                                
                                $periods=$this->db->query("SELECT DISTINCT (time_start),time_end  FROM `class_routine` WHERE `tid` = '".$timetable->tid."'  ");
                                $periods =$periods->result();
                                $interval_arr =array();
                                $prev_end=0;
                                foreach($periods as $period){
                                     if( ($prev_end!=0) && ($prev_end !=$period->time_start) ){
                                         
                                         $interval_arr[] = array('period'=>'Break','start'=>$prev_end,'ending' =>$period->time_start); 
                                     }
                                    ?>
                                   <th><?php echo date("H:i",mktime(substr($period->time_start,0,strlen($period->time_start)-2), substr($period->time_start,strlen($period->time_start)-2)))  ?> - <?php echo date("H:i",mktime(substr($period->time_end,0,strlen($period->time_end)-2), substr($period->time_end,strlen($period->time_end)-2)))  ?></th>
                                    <?php
                                    $prev_end=$period->time_end;
                                }
                               
                                
                                ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                       $query=$this->db->query("SELECT cr.*, c.tid as staff_id FROM class_routine cr LEFT JOIN course c ON cr.course_id=c.cid WHERE cr.tid='".$timetable->tid."'  ORDER BY day asc,time_start ASC");
                                        $query=$query->result();
                                        $prev="";$ids="";
                                        foreach ($query as $value) {
                                            $ids.=$value->class_routine_id.",";
                                          if($prev!=$value->day){
                                            if($prev!=""){
                                                ?>
                                                 </tr>
                                             <?php
                                            }
                                              ?>
                                            <tr>
                                                <th><?php echo $weekdays[$value->day] ?></th>
                                                <td>
                                                    <input type="hidden" name="Staff_<?php echo  $value->class_routine_id;  ?>" value="<?php echo $value->staff_id ?>" />
                                                    <input type="hidden" name="day_timings_sub_<?php echo  $value->class_routine_id;  ?>" value="<?php echo $value->day.",".$value->time_start.",".$value->time_end ?>" />
                                                    <select  style=" width: 80px; "   name="cr_subject_<?php echo  $value->class_routine_id;  ?>" class="select2-me ">
                                                    
                                                        <option value="" >Select</option>
                                                        <?php 
                                                      if(strlen($this->form->value('cr_subject_'.$value->class_routine_id))!=0 )
                                                      {
                                                          $cid=$this->form->value('cr_subject_'.$value->class_routine_id);
                                                      }else{
                                                          $cid=$value->course_id;
                                                      }
                                                            foreach ($course as $key => $val) {
                                                                ?>
                                                             <option <?php
                                                                if($cid==$key){
                                                                  echo "selected";  
                                                                }?>  value="<?php echo $key ?>" ><?php echo $val ?></option>
                                                             <?php 
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php
                                                      if(strlen($this->form->error('cr_subject_'.$value->class_routine_id))!=0){
                                                          echo "<br/>".$this->form->error('cr_subject_'.$value->class_routine_id);
                                                      }
                                                    ?>
                                                </td>

                                            <?php
                                          }else{
                                              ?>
                                                <td>
                                                    <input type="hidden" name="Staff_<?php echo  $value->class_routine_id;  ?>" value="<?php echo $value->staff_id ?>" />
                                                    <input type="hidden" name="day_timings_sub_<?php echo  $value->class_routine_id;  ?>" value="<?php echo $value->day.",".$value->time_start.",".$value->time_end ?>" />
                                                    <select  style=" width: 80px; "   name="cr_subject_<?php echo  $value->class_routine_id;  ?>" class="select2-me ">
                                                    
                                                        <option value="" >Select</option>
                                                        <?php 
                                                      if(strlen($this->form->value('cr_subject_'.$value->class_routine_id))!=0 )
                                                      {
                                                          $cid=$this->form->value('cr_subject_'.$value->class_routine_id);
                                                      }else{
                                                          $cid=$value->course_id;
                                                      }
                                                            foreach ($course as $key => $val) {
                                                                ?>
                                                             <option <?php
                                                                if($cid==$key){
                                                                  echo "selected";  
                                                                }?>  value="<?php echo $key ?>" ><?php echo $val ?></option>
                                                             <?php 
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php
                                                      if(strlen($this->form->error('cr_subject_'.$value->class_routine_id))!=0){
                                                          echo "<br/>".$this->form->error('cr_subject_'.$value->class_routine_id);
                                                      }
                                                    ?>
                                                </td>
                                                <?php
                                          }
                                           $prev =$value->day;
                                        }

                                         if($prev!=""){
                                                ?>
                                                 </tr>
                                             <?php
                                            }

                                        $ids=substr($ids,0,strlen($ids)-1);

                                    ?>
                                </tbody>
                            </table>

                               <div class="form-actions  col-sm-12">
                                   <div class="col-sm-4">
                                       &nbsp;
                                   </div>
                                   <div class="col-sm-4">
                                       <input type="submit" name="submit" value="Update Timetable " class="btn btn-primary btn-block" />
                                   </div>
                                   <div class="col-sm-4">
                                       &nbsp;   
                                   </div>
                                   <input type="hidden" name="tid" value="<?php echo $timetable->tid ?>" />
                                   <input type="hidden" name="ids" value="<?php echo $ids ?>" />

                               </div>
                           </form> 
                        </div>
                    </div>
               
          <?php
      }
    ?>
        </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>