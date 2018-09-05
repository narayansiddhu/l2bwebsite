<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=$section = $this->input->get("section");
 $grade_array=  unserialize(GPA_GRADING);
        
$course_err ="";
if( strlen($t)!=0 ){
    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
  if($section_query->num_rows()==0){
  redirect('exams/view_settings/'.$exam->id);
  }else{    
    $section_query =$section_query->row();
    $ecids="SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1   AND s.sid='".$section_query->sid."' ORDER BY c.numeric_val DESC";
    $ecids = $this->db->query($ecids);
    $ecids = $ecids->row();
    }
}
$overall_total=0;
 $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
$query=$this->db->query($query); 
$query=$query->result();
$exam_ids="";
$subject_array=array();
foreach ($query as $value) {
    $exam_ids.=$value->id.",";
    $type=1;
    if(strlen($value->sec_lang)!=0){
         $type=2;
    }
    $subject_array[$value->id]= array("name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks,"max_Secured"=>0,"type"=>$type);
   if(strlen(trim($value->maxmarks))!=0){
    $overall_total+=$value->maxmarks;
   }
    
}
$exam_ids= substr($exam_ids, 0,  strlen($exam_ids)-1);
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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li> 
                <li>
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section_query->section ." - ".$section_query->class ?> </a>
                </li> 
            </ul>
        </div>
           
            <?php
        if(strlen($this->session->userdata('marks_updated'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('marks_updated'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('marks_updated');
        }
    ?>  
<?php
  if(sizeof($query)==0){
      ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
          <?php
  }else{
      $stud_marks= array();
      $s=$this->db->query("SELECT student_id as id,name,roll,userid from student WHERE section_id ='".$section_query->sid."'  ")->result();;
      foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0,'total_marks'=>0);
        }
        function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
            function gpa_grade1($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "0";
            }
          //   $overall_total=10*(sizeof($query));
    $marks="SELECT * from formative_marks where exam_id IN (".$exam_ids.") and iid='".$this->session->userdata('staff_Org_id')."' ";
 $marks= $this->db->query($marks)->result();
   $maarks_array=array();$ranks=array();
   foreach ($marks as $value) {
  $t=0;
        if($value->part_1==-1){
            $value->part_1="A";
        }else{
            $t+=$value->part_1;
        }
        if($value->part_2==-1){
            $value->part_2="A";
        }else{
            $t+=$value->part_2;
        }
        if($value->part_3==-1){
            $value->part_3="A";
        }else{
            $t+=$value->part_3;
        }
        if($value->part_4==-1){
            $value->part_4="A";
        }else{
            $t+=$value->part_4;
        }
 
        
        $value->marks =  number_format(($t/$subject_array[$value->exam_id]["max"])*100,2);
        $value->marks=gpa_grade($value->marks, $grade_array[$type]['grading']);
        $ranks[$value->exam_id][$value->marks]=$value->marks;

        if($value->marks< $subject_array[$value->exam_id]['max_Secured']){
            $subject_array[$value->exam_id]['max_Secured']=$value->marks;
        }
       $maarks_array[$value->exam_id][$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'total'=>$t,"Grade"=>$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['grade']);
      // print_r($stud_marks[$value->student_id]['total']);exit;
       $stud_marks[$value->student_id]['total']+=$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['Grade_points'];              
    //total_marks
        $stud_marks[$value->student_id]['total_marks']+=$t;
        
        }
        
        
        foreach ($ranks as $key => $value) {
            $value=array_unique($value);            
            krsort($value);
            $ranks[$key]=$value;
        }
         $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'0'=>0);
        $totals=array();

        foreach($stud_marks as $k=>$value){
           $stud_marks[$k]['grade']=gpa_grade1(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
           $grade_graph_array[$stud_marks[$k]['grade']]++;
           $totals[$value['total']]=$value['total'];
        }
        krsort($totals);
   ?>
      <div class="box">
          <div class="col-sm-4 nopadding">
              <div class="box box-bordered box-color">
        <div class="box-title">
                <h3>
                        <i class="fa fa-bars"></i>
                      Exam Settings
                </h3>
        </div>
              <div class="box-content nopadding" style=" max-height: 200px; overflow-y: auto" >
                  <table class="table table-bordered table-striped" >
                      <tr>
                          <th>Subject</th>
                          <th>Max Marks</th>
                          <th>Min Marks</th>
                          <th>Action</th>
                      </tr>
                          <?php
                              foreach ($query as $value) {
                                  ?>
                      <tr>
                          <td><?php echo $value->subject ?></td>
                          <td><?php echo $value->maxmarks ?></td>
                          <td><?php echo $value->minmarks ?></td>
                          <td><?php  
                          if($value->marks_entered==0){
                              ?><a   onclick="download_marks_sheet('<?php echo $value->id ?>','<?php echo $value->subject ?>');" rel="tooltip" title="" data-original-title="Add Exam Marks"><i class="fa fa-plus-square" ></i></a><?php
                          }else{
                              ?><a href="#subject_<?php echo $value->id ?>" data-toggle="tab" ><i class="fa fa-eye" ></i></a><?php
                          }
                           ?></td>
                      </tr>   
                                   <?php
                              }
                          ?>
                  </table>
                  <script>
                    function download_marks_sheet(sub,sub_name){
                        window.open('<?php echo base_url() ?>index.php/exams/download_formativesheet?examid=<?php echo $exam->id ?>&section=<?php echo $section_query->sid ?>&ecid=<?php echo $ecids->id ?>&subject='+sub+'&subject_name='+sub_name);
                        window.location.href = "<?php echo base_url() ?>index.php/exams/formativeadd_marks/"+<?php echo $exam->id ?>+"?&section=<?php echo $section_query->sid ?>&ecid=<?php echo $ecids->id ?>&subject="+sub;
                    }
                  </script>
              </div>
          </div>
          </div>
          <div class="col-sm-4">
              <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>Grading</h3>
                    </div>
                    <div class="box-content nopadding" style=" max-height: 200px; overflow-y: auto"  >
                                 <table class="table table-bordered" style=" width: 100%">
                                        <thead>
                                            <tr style=" font-size: 15px">
                                                <th >First and Third Languages , Non-Languages</th>
                                             <th>Second Language</th>
                                             <th>Points</th>
                                             <th>Grade</th>
                                         </tr>
                                        </thead>
                                     <tbody>
                                        <?php
                                        $i=1;
                           while($i<=8){
                               ?>
                                         <tr>
                                             <td><?php echo $grade_array[1]['grading'][$i]['from'] ."% - " .$grade_array[1]['grading'][$i]['to']."%" ?></td>
                                             <td><?php echo $grade_array[2]['grading'][$i]['from'] ."% - " .$grade_array[2]['grading'][$i]['to']."%" ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['grade'] ?></td>
                                             <td><?php echo $grade_array[1]['grading'][$i]['Grade_points'] ?></td>
                                         </tr>    
                               <?php
                               $i++;
                           }
                                        ?>
                                     </tbody>
                                 </table>
     
                    </div>
              </div>
          </div>
          <div class="col-sm-4 nopadding">
                <?php
                    $g_Str="";
                        foreach ($grade_graph_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                           
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$grade_array[1]['grading'][$key]['grade']." ', y: ".$per." },";
                        }
                    ?>
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
                  text: 'Exam Report'
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
                  name: 'Percentage : ',
                  colorByPoint: true,
                  data: [
                      <?php echo $g_Str ?>
                  ]
              }]
          });
      });
                      </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
                <div id="container" style="min-width: 350px; width: 100%; height:250px; padding-left: 25px; padding-top: 15px;"></div>

          </div>
      </div>
      <br style=" clear: both" />
      <hr style=" clear: both" />
      <div class="box box-bordered box-color" style=" clear: both">
        <div class="box-title">
                <h3><i class="fa fa-bars"></i>Result</h3>
                <ul class="tabs">
                        <li class="active">
                                <a href="#t7" data-toggle="tab">Result</a>
                        </li>
                        <?php
                              foreach ($query as $value) {
                                  ?>
                         <li>
                             <a href="#subject_<?php echo $value->id ?>" data-toggle="tab"><?php echo ucfirst(strtolower($value->subject)) ?> Marks</a>
                        </li>
                              <?php
                              }
                              
                              function find_pos($arr ,$val){
                        $i=1;
                        foreach ($arr as $value) {
                            if($value==$val){
                                return $i;
                            }else{
                                $i++;
                            }
                        }
                    }
                              ?>
                </ul>
        </div>
          <div class="box-content nopadding" style=" overflow:  scroll; max-height: 500px;" >
                <div class="tab-content nopadding">
                <div class="tab-pane active nopadding" id="t7">
                    
                    <div class="box">
                        <div style=" float: left; width: 73%; ">
                           <h4 style=" margin: 4px; text-align: left; color:  #0066cc; font-size: 24px; " >Complete Marks Sheet</h4>
                        </div>
                        <div style=" float: left; width: 27%;; text-align: right ">
                            <a target="_blank" href="<?php echo base_url() ?>/index.php/exams/print_formative_cards/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>"  class="btn btn-primary" ><i class="fa fa-print"></i>&nbsp;Report Card</a>
                                <a target="_blank" href="<?php echo base_url() ?>/index.php/exams/print_formative_marks/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>" class="btn btn-primary" ><i class="fa fa-print"></i>&nbsp;print</a>
                                <a target="_blank" href="<?php echo base_url() ?>/index.php/exams/send_formative_cards/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>"  class="btn btn-primary" ><i class="fa fa-comments-o"></i>&nbsp;Send SMS</a>
                             
                        </div>
                    </div>
                    <hr/>
                    <table class="table table-bordered" style=" text-align: center" >
                        <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <?php
                            foreach ($query as $value) {
                                ?>
                            <th style=" text-align: center">
                            <?php echo ucfirst(strtolower($value->subject)) ?>
                        <table class="table-bordered" >
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>T</td>
                                <td>G</td>
                            </tr>
                        </table>
                        </th>
                                <?php
                            }
                            ?>
                            <th>Total Marks</th>
                            <th>Grade Points</th>
                            <th>Grade Secured</th>
                            <th>Rank</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($s as  $value) {
                                    ?>
                            <tr>
                                <td><?php echo $value->roll ?></td>
                                <td><?php echo $value->name ?></td>
                                <?php
                                foreach ($query as $sub){
                                    ?>
                                <td>
                                    <table class="table table-bordered" style=" border-right: 1px solid #cccccc " >
                                        <?php 
                                         if(isset($maarks_array[$sub->id][$value->id])){
                                             ?>
                                        <tr>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part1'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part2'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part3'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['part4'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['total'] ?></td>
                                            <td><?php echo $maarks_array[$sub->id][$value->id]['Grade'] ?></td>
                                        </tr>
                                             <?php
                                         }else{
                                             ?>
                                              <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>   
                                              <?php
                                         }
                                        ?>
                            
                        </table>
                                </td>
                                     <?php
                                }
                                ?>
                                <td><?php echo $stud_marks[$value->id]['total_marks'] ?></td>
                                <td><?php echo $grade_array[1]['grading'][$stud_marks[$value->id]['grade']]['Grade_points']; ?></td>
                                <td><?php echo $grade_array[1]['grading'][$stud_marks[$value->id]['grade']]['grade']; ?></td>
                                <td><?php echo  find_pos($totals, $stud_marks[$value->id]['total']); ?></td>
                            </tr>    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                    <?php
                              foreach ($query as $sub) {
                                  ?>
                    <div class="tab-pane" id="subject_<?php echo $sub->id ?>">
                        <div class="box">
                        <div style=" float: left; width: 73%; ">
                            <h4 style=" margin: 4px; text-align: left; color:  #0066cc; font-size: 24px; " ><?php echo ucfirst(strtolower( $sub->subject))  ?> Marks Sheet</h4>
                         </div>
                        <div style=" float: left; width: 27%;; text-align: right ">
                                <a target="_blank" href="<?php echo base_url() ?>/index.php/exams/print_formative_marks/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>/<?php echo $sub->id ?>" class="btn btn-primary" ><i class="fa fa-print"></i>&nbsp;Print</a>
                                <a target="_blank" href="<?php echo base_url() ?>/index.php/exams/edit_formative_marks/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>/<?php echo $sub->id ?>" class="btn btn-primary" ><i class="fa fa-pencil-square-o"></i>&nbsp;Edit</a>
                       </div>
                    </div>
                                <hr/>
                        <table class="table table-bordered" style=" text-align: center" >
                        <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                                <th>Part-1</th>
                                <th>Part-2</th>
                                <th>Part-3</th>
                                <th>Part-4</th>
                                <th>Total Marks</th>
                                <th>Grade Secured</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($s as  $stud) {
                                    ?>
                            <tr>
                                <td><?php echo $stud->roll ?></td>
                                <td><?php echo $stud->name ?></td>
                                <td><?php 
                                if(isset($maarks_array[$sub->id][$stud->id]['part1'])){
                                  echo $maarks_array[$sub->id][$stud->id]['part1'];  
                                }else{
                                    echo "--";
                                }
                                 ?></td>
                                <td><?php
                                if(isset($maarks_array[$sub->id][$stud->id]['part2'])){
                                  echo $maarks_array[$sub->id][$stud->id]['part2'];  
                                }else{
                                    echo "--";
                                }
                                ?></td>
                                <td><?php
                                 if(isset($maarks_array[$sub->id][$stud->id]['part3'])){
                                  echo $maarks_array[$sub->id][$stud->id]['part3'];  
                                }else{
                                    echo "--";
                                }
                                ?></td>
                                <td><?php 
                                if(isset($maarks_array[$sub->id][$stud->id]['part4'])){
                                  echo $maarks_array[$sub->id][$stud->id]['part4'];  
                                }else{
                                    echo "--";
                                }
                               ?></td>
                                <td><?php
                                if(isset($maarks_array[$sub->id][$stud->id]['total'])){
                                  echo $maarks_array[$sub->id][$stud->id]['total'];  
                                }else{
                                    echo "--";
                                }
                                 ?></td>
                                <td><?php
                                if(isset($maarks_array[$sub->id][$stud->id]['Grade'])){
                                  echo $maarks_array[$sub->id][$stud->id]['Grade'];  
                                }else{
                                    echo "--";
                                }
                                 ?></td>
                            </tr>    
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    </div>
                    <?php
                    }
                    ?>

                </div>
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