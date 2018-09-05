<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$q=$this->db->query("SELECT c.cid,s.subject  FROM `course` c JOIN subjects s ON c.subid=s.sid  WHERE `secid` = '".$timetable->sid."'");
$q=$q->result();
$course=array();

$medium= unserialize(medium);
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
                   <a href="<?php echo base_url(); ?>index.php/timetable/view/">Manage Time Table</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/<?php echo $timetable->sid ?>">View Time Table OF <?php echo $timetable->class ." , ".$timetable->section ?></a>
                </li>
            </ul>

    </div> 
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Time Table OF <?php echo $timetable->class ." , ".$timetable->section ?> (<?php echo $medium[$timetable->medium] ?>) </h3> 
                        <div class="actions">
                            <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/timetable/edit/<?php echo $timetable->tid   ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit Time Table</a>  
                            <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/timetable/edit_timings/<?php echo $timetable->tid   ?>" class="btn btn-primary"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp;Edit Intervals</a>  
                        </div>     
                </div>
                <div class="box-content nopadding"> 
                  
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
                            $subjects=array();
                            
                               $query=$this->db->query("SELECT cr.*,c.subid,s.subject,st.name ,st.email FROM class_routine cr LEFT JOIN course c ON cr.course_id =c.cid LEFT JOIN subjects s ON c.subid=s.sid  JOIN staff st ON c.tid=st.id WHERE cr.tid='".$timetable->tid."' ORDER BY cr.day asc,cr.time_start ASC ");
                                
                               if($query->num_rows()>0){
                                 $total_rows =   $query->num_rows();  
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
                                        <td><?php 
                                               if( strlen($value->subject)==0){
                                                   
                                                   echo "--";
                                               }else{
                                                   if(isset($subjects[$value->subject])){
                                                       $subjects[$value->subject]['count']++;
                                                   }else{
                                                       $subjects[$value->subject]['count']=1; 
                                                       $subjects[$value->subject]['st_name'] =$value->name;
                                                       $subjects[$value->subject]['st_mail'] =$value->email;
                                                   }
                                                   echo $value->subject;
                                               }?></td>
                                        
                                    <?php
                                  }else{
                                      ?>
                                        <td>
                                            <?php 
                                               if( strlen($value->subject)==0){
                                                   echo "--";
                                               }else{
                                                   if(isset($subjects[$value->subject])){
                                                       $subjects[$value->subject]['count']++;
                                                   }else{
                                                       $subjects[$value->subject]['count']=1; 
                                                       $subjects[$value->subject]['st_name'] =$value->name;
                                                       $subjects[$value->subject]['st_mail'] =$value->email;
                                                   }
                                                   echo $value->subject;
                                               }?>
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
                               }else{
                                   ?>
                                         <tr >
                                             <td colspan="<?php echo sizeof($periods)+1 ?>" style=" text-align: center; color: red">** No Records Found..</td>
                                         </tr>
                                   <?php
                               } 
                            ?>
                            <tr>
                                <th>Intervals </th>
                                <th colspan="<?php echo ($noofc) ?>" style=" color: red" >
                                   <?php
                                   $str= "";
                      foreach ($interval_arr as $value) {
                          
                          $str .= date("H:i",mktime(substr($value['start'],0,strlen($value['start'])-2), substr($value['start'],strlen($value['start'])-2))) . " - " .date("H:i",mktime(substr($value['ending'],0,strlen($value['ending'])-2), substr($value['ending'],strlen($value['ending'])-2))) ." ,";
                      }
                      echo $str=substr($str,0,  strlen($str)-1);
                                   ?>
                                </th>
                            </tr>
                        </tbody>
                        
                        
                    </table>
                    
                    
                    
                </div>
            </div>
             
            <div class="box">
                
                <div class="col-sm-6 nopadding " >
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Course Details</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Subject</th>
                                        <th>No.Of Classes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $graph_s="";
                                    if(sizeof($subjects)==0){
                                     ?>
                                    <tr><td colspan="3" style=" text-align: center; color: red">** No Records Found..</td></tr>    
                                     <?php   
                                    }
                                    
                                      foreach ($subjects as $key=>$value) {
                                        $graph_s.="{name: '".$key."(".$value['count'].")',y: ".(($value['count']/$total_rows)*100).",sliced: true,selected: true},";
                                         ?>
                                    <tr>
                                        <td><?php  echo $key ?></td>
                                        <td><?php  echo $value['st_name'] ?></td>
                                        <td><?php  echo $value['count'] ?></td>
                                        
                                    </tr>
                                         <?php
                                       }
                                    ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
                <?php
                $graph_s= substr($graph_s,0,strlen($graph_s)-1);;
                ?>
                <script type="text/javascript" src="<?php echo assets_path ?>highcharts/js/jquery.min.js"></script>		

    <script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Total No Of Classes : <?php echo $total_rows ?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Value',
            colorByPoint: true,
            data: [<?php echo $graph_s ?>]
        }]
    });
});
</script>
<div class="col-sm-6 " style=" margin-top: 10px; padding-left: 15px;">
                    
                            <div id="container" style="min-width: 500px;width : 100%; height: 250px; ">
                                <h4 style=" color: red; text-align: center">** No records Found to display Graph</h4>
                             </div>  
                      
                </div>
            </div>
     <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
    
            
        </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>