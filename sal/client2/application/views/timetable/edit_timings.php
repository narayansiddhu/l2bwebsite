<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php
                    
    $query=$this->db->query("SELECT DISTINCT (time_start),time_end  FROM `class_routine` WHERE `tid` = '".$timetable->tid."'  ");
    $periods= $query->num_rows();
    $query=$query->result();

    $schedule =array();
    $prev_end=0;
    $i=1;
    foreach ($query as $value) {
          
        if($prev_end!=0){
            if($prev_end !=$value->time_start){
                  $schedule[] = array('period'=>'Break','start'=>$prev_end,'ending' =>$value->time_start);  
                 $schedule[] = array('period'=>'Period - '.$i++,'start'=>$value->time_start,'ending' =>$value->time_end);  

            }else{
                $schedule[] = array('period'=>'Period - '.$i++,'start'=>$value->time_start,'ending' =>$value->time_end);  
        }
        }else{
            $schedule[] = array('period'=>'Period - '.$i++,'start'=>$value->time_start,'ending' =>$value->time_end);  
        }
        $prev_end=$value->time_end;

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
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/<?php echo $timetable->sid ?>">View <?php echo $timetable->class ." - ".$timetable->section ?> Timetable</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/">Edit Timings Of <?php echo $timetable->class ." - ".$timetable->section ?></a>
                </li>
            </ul>

    </div> 
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Intervals</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <div class='form-horizontal form-bordered'>
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Period</label>
                            <div class="col-sm-10">
                                    <select style="width: 100%" id='period' name="period" class="select2-me" >
                                            <option value="">Select a Period</option>
                                            <?php
                                              foreach ($schedule as $key => $value) {
                                                   
                                                    if($value['period']!='Break'){
                                                         ?>
                                                         <option value="<?php echo $key ?>"><?php echo $value['period']  ?></option>
                                                          <?php
                                                    }
                                                }
                                            
                                              
                                            ?>
                                    </select>
                                    <span id='period_errors' style=" color: red"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Span</label>
                            <div class="col-sm-10">
                                <input type="text" id='span' value="" placeholder="Please Enter Span in minutes" class="form-control" /> <strong>Min</strong>
                                <span id='span_errors' style=" color: red"></span>
                            </div>
                        </div>
<!--                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Action</label>
                            <div class="col-sm-10">
                                <div class="col-sm-6">
                                    <input type="radio" name="action" id='action1'   /> Add Interval 
                                </div>
                                <div class="col-sm-6">
                                    <input type="radio" name="action" id='action2'   />Remove Interval
                                </div>
                                <span id='iaction_errors' style=" color: red"></span>
                            </div>
                        </div>-->
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="button" onclick="submit_details();"  value="Submit " class="btn btn-primary" />
                            <span id='errors' style=" color: red"></span>
                        </div>   
                        <?php 
                         
                        ?>
                    </div>
                </div>
            </div>
        
    <script>
      function submit_details(){
          count=0;
          period =$('#period').val();
          span=$('#span').val();
          
         
        
          $('#period_errors').html('');
          $('#span_errors').html('');
          //$('#iaction_errors').html('');
          if(period.length == 0){
              $('#period_errors').html('Please select Number Of Periods');
              count++;
          }
          if(span.length == 0){
              $('#span_errors').html('Please Enter Span');
              count++;
          }else if(isNaN(span)){
              $('#span_errors').html('Enter Numeric Value');
              count++;
          }else{
              k= Math.abs(span);
              if(k != span){
                  $('#span_errors').html('Please Enter valid Numeric Value');
                  count++;
              }else if(span ==0){
                  $('#span_errors').html('Please Enter valid Numeric Value');
                  count++;
              }
            
          }

          if(count ==0){
            
              setState('errors','<?php echo base_url() ?>index.php/timetable/modify_timings','period='+period+'&span='+span+'&action=add&schedule=<?php echo json_encode($schedule) ?>&tid=<?php echo $timetable->tid ?>');
          }
          
      }    
    </script>
    
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Edit Timings Of <?php echo $timetable->class ." - ".$timetable->section ?></h3> 
                        
                </div>
                <div class="box-content nopadding"> 
                     
                    <table class="table table-hover table-nomargin">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        </thead>
                        <?php
                          
                          $prev ="" ;
                          foreach ($schedule as $key =>$value) {
                              
                             ?>
                        <tr>
                            <td><?php echo $value['period'] ;
                                 if($value['period']=='Break'){
                                     ?> (<a onclick="remove_break('<?php echo $prev; ?>','<?php echo (mktime(substr($value['ending'],0,strlen($value['ending'])-2), substr($value['ending'],strlen($value['ending'])-2)) - mktime(substr($value['start'],0,strlen($value['start'])-2), substr($value['start'],strlen($value['start'])-2)) )/60  ?>');" rel="tooltip" title="" data-original-title="Delete Break"><i class="fa fa-times" aria-hidden="true"></i></a>  ) <?php
                                 }
                                ?></td>
                            <td><?php
                            echo date('H:i', mktime(substr($value['start'],0,strlen($value['start'])-2), substr($value['start'],strlen($value['start'])-2)) );
                            ?></td>
                            <td><?php 
                             echo date('H:i', mktime(substr($value['ending'],0,strlen($value['ending'])-2), substr($value['ending'],strlen($value['ending'])-2)) );
                            ?></td>
                        </tr>
                             <?php
                             $prev =$key;
                             
                          }
                          
                        ?>
                        
                    </table>
                </div>
            </div>
        </div>
    
        </div>        
    </div>
</div>
    
<script>
   function remove_break(period,span){
       setState('errors','<?php echo base_url() ?>index.php/timetable/modify_timings','period='+period+'&span='+span+'&action=remove&schedule=<?php echo json_encode($schedule) ?>&tid=<?php echo $timetable->tid ?>');
   }
    </script>

<?php
$this->load->view('structure/footer');
?>