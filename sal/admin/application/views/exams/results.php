<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=$section = $this->input->get("section");

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
$query="SELECT e.id,e.maxmarks,e.minmarks,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
$query=$this->db->query($query); 
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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li> 
                <li>
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section_query->section ." - ".$section_query->class ?> </a>
                </li> 
            </ul>
            <a style=" float: right; margin-top:3px; margin-right: 15px;" class="btn btn-mini btn-primary" href="<?php echo base_url(); ?>index.php/exams/results/<?php echo $exam->id ?>?section=<?php echo $section_query->sid ?>&grading=cce">Change Grading To G.P.A</a>
        </div>
<?php
  if(sizeof($query)==0){
      ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
          <?php
  }else{
      if(strlen($this->input->get("grading"))!=0){
         $query= "SELECT e.id,e.maxmarks,e.minmarks,s.subject,c.cid,sl.sl_id as sec_lang  FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
         $query=$this->db->query($query); 
         $query=$query->result();
         $grade_array=  unserialize(GPA_GRADING);
          $ranks=array();
          $stud_marks=array();
          $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
            
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
            }
            $overall_total=0;
            function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "Absent";
            }
             $overall_total=10*(sizeof($query));
            foreach ($query as $val)
            {
                $type=1;
                if(strlen($val->sec_lang)!=0){
                   $type=2; 
                }
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                $marks[$val->id]['max']=8;
                foreach($k as $p){
                    $gr="";$m="";
                    if($p->marks!=-1){
                        $p->marks=  number_format(($p->marks/$val->maxmarks)*100,2);
                        $p->marks=gpa_grade($p->marks, $grade_array[$type]['grading']);
                        $marks[$val->id][$p->student_id]= $p->marks;
                        $ranks[$val->id][$p->marks]=$p->marks;
                        
                        if($p->marks<$marks[$val->id]['max']){
                            $marks[$val->id]['max']=$p->marks;
                        }
                        $stud_marks[$p->student_id]['total']+=$grade_array[$type]['grading'][$p->marks]['Grade_points'];      
                    }else{
                       $marks[$val->id][$p->student_id]=$p->marks;    
                    }
                }
            }
            
            foreach ($ranks as $key => $value) {
                $value=array_unique($value);            
                krsort($value);
                $ranks[$key]=$value;
            }
             $grade_graph_array = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0);
            $totals=array();
            
            foreach($stud_marks as $k=>$value){
               $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
               $grade_graph_array[$stud_marks[$k]['grade']]++;
               $totals[$value['total']]=$value['total'];
            }
            krsort($totals);
            
            ?>
      <div class="box">
          <div class="col-sm-3 nopadding">
              <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>Exam Settings</h3>
                        
                    </div>
                    <div class="box-content nopadding" style=" max-height: 200px; overflow-y: auto"  >
                        <table class="table table-bordered" style=" width: 100%">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Total</th>
                                    <th>cut-off</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                  foreach($query as $sub){
                                      ?>
                                <tr>
                                    <td><a rel="tooltip" title=""
                                           <?php
                                        if(strlen($sub->sec_lang)==0){
                                          ?> data-original-title="Set <?php echo $sub->subject ?> As Second language " onclick="set_As_default('<?php echo $sub->cid ?>')" <?php
                                        }
                                        ?>
                                           ><?php echo $sub->subject ?>
                                        <?php
                                        if(strlen($sub->sec_lang)!=0){
                                            echo "(2nd Language)";
                                        }
                                        ?></a></td>
                                    <td><?php echo $sub->maxmarks ?></td>
                                    <td><?php echo $sub->minmarks ?></td>
                                </tr>   
                                       <?php
                                  }
                                ?>
                            </tbody>
                        </table>
                        <span id='section_names'></span>
                    </div>
              </div>
          </div>
          <script>
          function set_As_default(subject){
               setState('section_names','<?php echo base_url() ?>index.php/Course/set_as_second_language','course='+subject+'&section=<?php echo $section  ?>');
          }
          </script>
          
          <div class="col-sm-5">
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
                                             <td><?php echo $grade_array[1]['grading'][$i]['from'] ." - " .$grade_array[1]['grading'][$i]['to'] ?></td>
                                             <td><?php echo $grade_array[2]['grading'][$i]['from'] ." - " .$grade_array[2]['grading'][$i]['to'] ?></td>
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
      
      <br style=" clear: both;"/>
      <hr/>
      <?php
//                           echo "<pre>";
//                       print_r($grade_array);
//                           echo "</pre>";
      ?>
            <div style=" clear: both ; padding-top: 10px " class="  box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>&nbsp;Results</h3>
                            <div class="actions">
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>?grading=cce"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Report Card</a>
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>?grading=cce"><i class="fa fa-comments-o" aria-hidden="true"></i>&nbsp;Send Results</a>
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/print_result/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>?grading=cce"><i class="fa fa-print"></i>&nbsp;Print</a>
                        </div>
                    </div>
                <div class="box-content nopadding" style=" max-height: 500px; overflow-y: auto"  >
                    <?php

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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style=" text-align: center" >Student</th>
                                <?php
                                  foreach($query as $val){
                                    ?>
                                <th style=" text-align: center">
                                    <a href="<?php echo base_url() ?>index.php/exams/edit_marks/<?php echo $val->id ?>/<?php echo $section ?>"  target="_blank" rel="tooltip" title="" data-original-title="Edit <?php echo $val->subject ?> Marks" ><i class="fa fa-pencil-square-o"></i><?php echo $val->subject ?></a>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                            <tr>
                                                <td>G</td>
                                                <td>P</td>
                                            </tr>
                                    </table>
                                </th>
                                    <?php   
                                  }
                                ?> 
                                <th style=" text-align: center" >Total</th>
                                <th style=" text-align: center" >Grade</th>
                                <th style=" text-align: center" >Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              foreach($stud_marks as $stud){
                                ?>
                            <tr>
                                <td><a  target="_blank" href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>/<?php echo $stud['id']?>"><?php echo $stud['name'] ?></a></td>
                                <?php
                                  foreach($query as $sub){
                                        $type=1;
                                        if(strlen($val->sec_lang)!=0){
                                           $type=2; 
                                        }
                                     ?>
                                <td><?php
                                  if(isset($marks[$sub->id][$stud['id']])){
                                      if($marks[$sub->id][$stud['id']]==-1){
                                      ?>
                                      <table class="table-bordered" style=" text-align: center; width: 100%; color:  green  ">
                                          <tr style="background-color: maroon; color:  white;">
                                            <td >A</td>
                                            <td >A</td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }else{
                                          
                                      ?>
                                      <table class="table-bordered nopadding" style=" text-align: center; width: 100%;   <?php
                                       if($marks[$sub->id][$stud['id']]<$sub->minmarks){
                                           echo "color: red; ";
                                       }
                                      ?>">
                                        <tr>
                                            <td  style=" background-color: #009900;  color: white" ><?php
                                             echo $grade_array[$type]['grading'][$marks[$sub->id][$stud['id']]]['grade'];
                                            ?></td>
                                            <td style=" background-color: #ff9900; color:  white "><?php 
                                            echo $grade_array[$type]['grading'][$marks[$sub->id][$stud['id']]]['Grade_points'];
                                            ?></td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }
                                   
                                  }else{
                                      
                                      ?>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                        <tr>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    </table> 
                                      <?php
                                  }
                                ?></td>
                                  <?php
                                  }
                                ?>
                                <td style=" text-align: center; color: #003366"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo ($stud['total']/  sizeof($query));
                                }
                                 ?></td>
                                <td  style=" text-align: center; color:  orangered"><?php echo $grade_array[1]['grading'][$stud['grade']]['grade']; ?></td>
                                <td style=" text-align: center; color:  #006633"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo  find_pos($totals, $stud['total']);
                                }
                                
                                  ?></td>
                            </tr>
                                <?php
                              }
                            ?>
                        </tbody>
                    </table>
                   
                </div>
            </div>     <h5 style="color:red; text-align: right">G =>Grade P =>Grade Points</h5>
                
            <?php
            
      }else {
      $overall_total=0;
      $grade_array = array('O'=>0,'A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0,'F'=>0);
                            $marks=array();
                      $ranks=array();
                      $stud_marks=array();
                      $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
                      foreach($s as $value){
                          $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
                      }
                      
                      foreach ($query as $val)
                      {
                          $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                          $overall_total+=$val->maxmarks;
                          $marks[$val->id]['max']=0;
                          foreach($k as $p){
                              if($marks[$val->id]['max']<$p->marks){
                                $marks[$val->id]['max']=$p->marks;  
                              }
                              
                              $marks[$val->id][$p->student_id]=$p->marks ;
                               $ranks[$val->id][$p->marks]=$p->marks;
                               if($p->marks!=-1){
                                  $stud_marks[$p->student_id]['total']+=$p->marks;  
                               }
                              
                          }
                      }  
                     
                      foreach ($ranks as $key => $value) {
                          $value=array_unique($value);            
                          krsort($value);
                          $ranks[$key]=$value;
                    }
                      
                   function find_grade($total,$overall){
                       $per=($total/$overall)*100;
                       $per=number_format ($per,2);
                       $grade ="";
                       switch($per){
                           case $per>90 : $grade="O"; break;
                           case $per>80 : $grade="A"; break;
                           case $per>70 : $grade="B"; break;
                           case $per>60 : $grade="C"; break;
                           case $per>50 : $grade="D"; break;
                           case $per>40 : $grade="E"; break;
                           default : $grade="F";break;
                       }
                       return $grade;
                   }
                    $totals=array();
                    foreach($stud_marks as $k=>$value){
                        $stud_marks[$k]['grade']=find_grade($value['total'],$overall_total);
                        $grade_array[$stud_marks[$k]['grade']]++;
                        $totals[$value['total']]=$value['total'];
                    }
                    krsort($totals);
      ?>
      <div class="box" style=" height: auto; min-height: 250px;">
          <div class="col-sm-4 nopadding">
              <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>Exam Schedule</h3>
                        
                    </div>
                    <div class="box-content nopadding" style=" max-height: 200px; overflow-y: auto"  >
                        <table class="table table-bordered" style=" width: 100%">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Total</th>
                                    <th>cut-off</th>
                                    <th>Max Secured</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                  foreach($query as $sub){
                                      ?>
                                <tr>
                                    <td><?php echo $sub->subject ?></td>
                                    <td><?php echo $sub->maxmarks ?></td>
                                    <td><?php echo $sub->minmarks ?></td>
                                    <td><?php echo $marks[$sub->id]['max'] ?></td>
                                </tr>   
                                       <?php
                                  }
                                ?>
                            </tbody>
                        </table>
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
                                            <tr  >
                                             <th >Grade &amp; Description</th>
                                             <th >% of marks secured</th>
                                         </tr>
                                        </thead>
                                     <tbody>
                                         
                                         <tr  >
                                             <td >O – OUTSTANDING</td>
                                             <td >90 and above</td>
                                         </tr>
                                         <tr  >
                                             <td >A – EXCELLENT</td>
                                             <td >80% to &lt; 90%</td>
                                         </tr>
                                         <tr  >
                                             <td >B – GOOD</td>
                                             <td >70% to &lt; 80%</td>
                                         </tr>
                                         <tr  >
                                             <td >C – FAIR</td>
                                             <td >60% to &lt; 70%</td>
                                         </tr>
                                         
                                         <tr  >
                                             <td >D – SATISFACTORY</td>
                                             <td >50% to &lt; 60%</td>
                                         </tr>
                                         <tr  >
                                             <td >E – AVERAGE</td>
                                             <td >40% to &lt; 50%</td>
                                         </tr>
                                         <tr  >
                                             <td >F– FAIL</td>
                                             <td > &lt; 40%</td>
                                         </tr>
                                     </tbody>
                                 </table>
     
                    </div>
              </div>
          </div>
          <div class="col-sm-4">
                  		<?php
                    $g_Str="";
                        foreach ($grade_array as $key => $value) {
                            $per =($value/ sizeof($s))*100;   
                            $per = number_format($per, 2);
                            $g_Str.="{  name: '".$key." Grade ', y: ".$per." },";
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
          
      </div><br/>
      <hr/>
      <div style=" clear: both ; padding-top: 10px " class="  box box-color box-bordered nopadding">
                    <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>&nbsp;Results</h3>
                            <div class="actions">
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Report Card</a>
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>"><i class="fa fa-comments-o" aria-hidden="true"></i>&nbsp;Send Results</a>
                                <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/print_result/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>"><i class="fa fa-print"></i>&nbsp;Print</a>
                        </div>
                    </div>
                <div class="box-content nopadding" style=" max-height: 500px; overflow-y: auto"  >
                    <?php

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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style=" text-align: center" >Student</th>
                                <?php
                                  foreach($query as $val){
                                   
                                    ?>
                                <th style=" text-align: center">
                                    <a href="<?php echo base_url() ?>index.php/exams/edit_marks/<?php echo $val->id ?>/<?php echo $section ?>"  target="_blank" rel="tooltip" title="" data-original-title="Edit <?php echo $val->subject ?> Marks" ><i class="fa fa-pencil-square-o"></i><?php echo $val->subject ?></a>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                            <tr>
                                                <td>M</td>
                                                <td>R</td>
                                            </tr>
                                    </table>
                                </th>
                                    <?php   
                                  }
                                ?> 
                                <th style=" text-align: center" >Total</th>
                                <th style=" text-align: center" >Grade</th>
                                <th style=" text-align: center" >Rank</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                           
                            
                              foreach($stud_marks as $stud){
                                 
                                ?>
                            <tr>
                                <td><a  target="_blank" href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>/<?php echo $stud['id']?>"><?php echo $stud['name'] ?></a></td>
                                <?php
                                  foreach($query as $sub){
                                     
                                     ?>
                                <td><?php
                                  if(isset($marks[$sub->id][$stud['id']])){
                                      if($marks[$sub->id][$stud['id']]==-1){
                                          
                                      ?>
                                      <table class="table-bordered" style=" text-align: center; width: 100%; color:  green  ">
                                          <tr style="background-color: maroon; color:  white;">
                                            <td >A</td>
                                            <td >A</td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }else{
                                          
                                      ?>
                                      <table class="table-bordered nopadding" style=" text-align: center; width: 100%;   <?php
                                       if($marks[$sub->id][$stud['id']]<$sub->minmarks){
                                           echo "color: red; ";
                                       }
                                      ?>">
                                        <tr>
                                            <td  style=" background-color: #009900;  color: white" ><?php  echo $marks[$sub->id][$stud['id']];   ?></td>
                                            <td style=" background-color: #ff9900; color:  white "><?php echo  find_pos($ranks[$sub->id], $marks[$sub->id][$stud['id']]); ?></td>
                                        </tr>
                                    </table>     
                                      <?php
                                      }
                                   
                                  }else{
                                      ?>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                        <tr>
                                            <td>--</td>
                                            <td>--</td>
                                        </tr>
                                    </table> 
                                      <?php
                                  }
                                ?></td>
                                  <?php
                                  }
                                ?>
                                <td style=" text-align: center; color: #003366"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo $stud['total'];
                                }
                                 ?></td>
                                <td  style=" text-align: center; color:  orangered"><?php echo $stud['grade']; ?></td>
                                <td style=" text-align: center; color:  #006633"><?php 
                                if($stud['total']==0){
                                     echo "--";
                                }else{
                                    echo  find_pos($totals, $stud['total']);
                                }
                                
                                  ?></td>
                            </tr>
                                <?php
                              }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
      <h5 style="color:red; text-align: right">M =>Subject Marks R =>Subject Wise Rank</h5>
      <?php
  }
  }
?>
            
                 
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            $('.highcharts-credits').css('display', 'none');
        });
    </script>
<?php
$this->load->view('structure/footer');
?>