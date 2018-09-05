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
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section_query->class." - ".$section_query->section  ?> </a>
                </li> 
            </ul>
         </div>
<?php
  if(sizeof($query)==0){
      ?><h3 style=" text-align: center;  color:  red">** No Exams Scheduled..</h3>
          <?php
  }else{
        $query= "SELECT e.id,e.maxmarks,e.minmarks,s.subject,c.cid,sl.sl_id as sec_lang,s.sid  FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$ecids->id."'  ";
        $query=$this->db->query($query); 
        $query=$query->result();
        $grade_array=  unserialize(GPA_GRADING);
        $ranks=array();
        $studprev_marks_array=array();
        $stud_marks=array();
        $s=$this->db->query("SELECT student_id as id,name from student WHERE section_id ='".$section_query->sid."'  ")->result();;
            
        foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
         
            $studprev_marks_array[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0);
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
            $subject_array=array();
             $overall_total=10*(sizeof($query));
            foreach ($query as $val)
            {
              
              $subject_array[$val->sid]=array("name"=>$val->subject,"max_marks"=>"100","exam_id"=>$val->id);
                $type=1;
                if(strlen($val->sec_lang)!=0){
                   $type=2; 
                }
                $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ORDER BY st.student_id ")->result();
                $marks[$val->id]['max']=8;
                foreach($k as $p){
                    $gr="";$m="";
                    if($p->marks!=-1){
                        $stud_marks[$p->student_id][$val->sid]['sm']=$p->marks;
                      }else{
                        $stud_marks[$p->student_id][$val->sid]['sm']="A";
                    }
                }
            }
            
$prev_ids="";
$prev_exams="SELECT c.id,e.exam FROM examinations e JOIN examination_cls c ON c.examid = e.id WHERE e.enddate <'".$exam->startdate."' and e.type=4 and e.iid='".$this->session->userdata("staff_Org_id")."' AND c.sectionid='".$section_query->sid."' AND c.status=1 ";
$prev_exams=$this->db->query($prev_exams);
$f_error="";
if($prev_exams->num_rows()==0){
    $f_error="** No Formative Exams Found Previously";
                                 
}else{
    $string=" The Avarage Of ";
    $prev_exams=$prev_exams->result();
    foreach($prev_exams as $val){
       $prev_ids.=$val->id.",";
       $string.=$val->exam." ,";
    }
    $prev_ids=  substr($prev_ids, 0, strlen($prev_ids)-1);
    $string = substr($string, 0, strlen($string)-1);
    $string.=" For Remaining 20 Marks";
    
    $exams ="SELECT  courseid, GROUP_concat(id)as ids,s.subject,s.sid  FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid   where ecid in (".$prev_ids.")  GROUP BY courseid ";
    $exams=$this->db->query($exams) ; 
    if($exams->num_rows()==0){
        $f_error="** No Formative Exams Found Previously";
    }else{
        $exams=$exams->result();
        foreach ($exams as $value) {
           $ids=$value->ids;
           if(isset($subject_array[$value->sid])){
                $subject_array[$value->sid]["ids"]=$ids;
                $size= sizeof(array_filter(explode(",",$ids)));
                $p_marks="SELECT student_id,exam_id, (part_1+part_2+part_3+part_4) as total,part_1,part_2,part_3,part_4 FROM `formative_marks` where exam_id IN (".$ids.") ";
                $p_marks=$this->db->query($p_marks)->result();
                foreach ($p_marks as  $val){
                    if(!isset($stud_marks[$val->student_id][$value->sid]['fm'])){
                        $stud_marks[$val->student_id][$value->sid]['fm']=array("total_marks"=>0,"final"=>0,"Formatives"=>sizeof($ids),"fm_ids"=>$ids,"Actual_marks"=>0);
                    }
                    $t=0;
                    if($val->part_1!=-1){
                       $t+=$val->part_1; 
                    }
                    if($val->part_2!=-1){
                       $t+=$val->part_2; 
                    }
                    if($val->part_3!=-1){
                       $t+=$val->part_3; 
                    }
                    if($val->part_4!=-1){
                       $t+=$val->part_4; 
                    }
                    $stud_marks[$val->student_id][$value->sid]['fm']["total_marks"]+=$t;
                    $stud_marks[$val->student_id][$value->sid]['fm']["final"]+=round( ($t/$size) );
                    $stud_marks[$val->student_id][$value->sid]['fm'][$val->exam_id]=round( ($t/$size) );
                } 
           }         
        }
    }  
  }
  $ranks= array();
  foreach($s as $stud){
    foreach($query as $sub){
        $total=$sm=$fm=0;
        if(isset($stud_marks[$stud->id][$sub->sid]['sm'])){
       if($stud_marks[$stud->id][$sub->sid]['sm']!="A"){
            $sm=$stud_marks[$stud->id][$sub->sid]['sm'];
        }
            
        }
         if(isset($stud_marks[$stud->id][$sub->sid]['fm'])){
             $fm=$stud_marks[$stud->id][$sub->sid]['fm']['final'];
         }else{
             $fm=$stud_marks[$stud->id][$sub->sid]['fm']['final']=0;
         }
        $total=$sm+$fm;$total=$sm+$fm;
        $stud_marks[$stud->id][$sub->sid]['total']=$sm+$fm;
        $ranks[$sub->sid][$total]=$total;
        $stud_marks[$stud->id]['total']+=$total;
    }
  }
  
  
   foreach ($ranks as $key => $value) {
        $value=array_unique($value);            
        krsort($value);
        $ranks[$key]=$value;
    }
     $grade_graph_array = array('0'=>0,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0);
    $totals=array();
    $overall_total=(sizeof($query))*100;
    foreach($stud_marks as $k=>$value){
        if(!isset($value['total'])){
           $value['total']=0; 
        }
        $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
      if(isset($grade_graph_array[$stud_marks[$k]['grade']])){
           $grade_graph_array[$stud_marks[$k]['grade']]++;
      }
       $totals[$value['total']]=$value['total'];
    }
    krsort($totals);
?>  
<div style=" clear: both ; padding-top: 10px " class="  box  box-bordered nopadding">
    <div class="box-title">
        <h3><i class="fa fa-bar-chart-o"></i>&nbsp;Results</h3>
           <!--  <ul class="tabs">
                <li class="active">
                        <a href="#t7" data-toggle="tab">Summative Marks</a>
                </li>
               <li>
                        <a href="#t8" data-toggle="tab">Final Result</a>
                </li>
                <li>
                        <a href="#t9" data-toggle="tab">Grading & Graph</a>
                </li>-->
            </ul>
    </div>
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
    <div class="box-content nopadding"  >
        <div >
                                <h4>Grading & Graphical Representation</h4>
                                <div class="box" style=" padding: 5px;">
                                    <div class="col-sm-4 nopadding">
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
                        </div>
        
        <div >
        
        <br style="clear:both" />
        
        <br style="clear:both" />
        <br style="clear:both" />
        
        
            <div class="tab-pane  active bordered " id="t7">
                <div class="box">
                        <div style=" float: left; width: 73%; ">
                           <h4 style=" margin: 4px; text-align: left; color:  #0066cc; font-size: 24px; " >Summative Result</h4>
                        </div>
                        <div style=" float: left; width: 27%;; text-align: right ">
                            <a target="_blank" class="btn btn-primary"   href="<?php echo base_url(); ?>index.php/exams/summative_report_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>?grading=cce"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Report Card</a>
                                <a target="_blank" href="http://ems.snetworkit.com/schooln/office//index.php/exams/print_formative_marks/59/329" class="btn btn-primary" ><i class="fa fa-print"></i>&nbsp;print</a>
                                <a target="_blank" href="http://ems.snetworkit.com/schooln/office//index.php/exams/send_formative_cards/59/329"  class="btn btn-primary" ><i class="fa fa-comments-o"></i>&nbsp;Send SMS</a>
                             
                        </div>
                    </div>


               
               <table class="table table-bordered table-striped" style=" width: 100%; overflow: auto; " >
                   <thead>
                       <tr>
                           <th>Student</th>
                           <?php
                                  foreach($query as $val){
                                    ?>
                                <th style=" text-align: center">
                                    <a href="<?php echo base_url() ?>index.php/exams/edit_marks/<?php echo $val->id ?>/<?php echo $section ?>"  target="_blank" rel="tooltip" title="" data-original-title="Edit <?php echo $val->subject ?> Marks" ><i class="fa fa-pencil-square-o"></i><?php echo $val->subject ?></a>
                                    <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                            <tr>
                                                <td>S.M</td>
                                                <td>F.A</td>
                                                <td>T</td>
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
                        foreach($s as $stud){
                            
                                ?>
                            <tr>
                                <td><a  target="_blank" href="<?php echo base_url(); ?>index.php/exams/download_card/<?php echo $exam->id ?>/<?php echo $section_query->sid ?>/<?php echo $stud->id ?>"><?php echo $stud->name ?></a></td>
                                <?php
                                  foreach($query as $sub){
                                     ?>
                                <td>
                                <table class="table-bordered" style=" text-align: center; width: 100%; ">
                                    <tr>
                                        <td><?php
                                        if(isset($stud_marks[$stud->id][$sub->sid]['sm'])){
                                          echo $stud_marks[$stud->id][$sub->sid]['sm'];  
                                        }else{
                                            echo "--";
                                        }
                                         ?></td>
                                        <td><?php 
                                        if(isset($stud_marks[$stud->id][$sub->sid]['fm']['final'])){
                                          echo $stud_marks[$stud->id][$sub->sid]['fm']['final'];  
                                        }else{
                                            echo "--";
                                        }
                                      //  echo $stud_marks[$stud->id][$sub->sid]['fm']['final'] ?></td>
                                        <td><?php
                                        if(isset($stud_marks[$stud->id][$sub->sid]['total'])){
                                          echo $stud_marks[$stud->id][$sub->sid]['total'];  
                                        }else{
                                            echo "--";
                                        }
                                        ?></td>
                                    </tr>
                                </table>
                                </td>
                                     <?php 
                                  }
                               ?>
                                <td><?php
                                if(isset($stud_marks[$stud->id]['total'])){
                                          echo $stud_marks[$stud->id]['total'];  
                                        }else{
                                            echo "--";
                                        }
                                ?></td>
                                <td  style=" text-align: center; color:  orangered"><?php
                                
                                echo $grade_array[1]['grading'][$stud_marks[$stud->id]['grade']]['grade']; ?></td>
                                <td style=" text-align: center; color:  #006633"><?php 
                                if($stud_marks[$stud->id]['total']==0){
                                     echo "--";
                                }else{
                                    echo  find_pos($totals, $stud_marks[$stud->id]['total']);
                                }
                                
                                  ?></td>
                            </tr>
                               <?php
                        }
                       ?>
                   </tbody>
               </table>
            </div>
            
            <!--<div class="tab-pane" id="t8">
         	<div class="box-content nopadding">
								<div class="tabs-container">
									<ul class="tabs tabs-inline tabs-left">
										<li class='active'>
											<a href="#first" data-toggle='tab'>
												<i class="fa fa-lock"></i>Security</a>
										</li>
										<li>
											<a href="#second" data-toggle='tab'>
												<i class="fa fa-user"></i>Account</a>
										</li>
										<li>
											<a href="#thirds" data-toggle='tab'>
												<i class="fa fa-twitter"></i>Social</a>
										</li>
									</ul>
								</div>
								<div class="tab-content padding tab-content-inline">
									<div class="tab-pane active" id="first">
										Lorem ipsum Proident do cupidatat exercitation amet velit dolor Ut reprehenderit magna. Lorem ipsum Aute cupidatat labore deserunt nisi irure aliquip. Lorem ipsum Magna aliqua Duis tempor in dolor culpa nulla. Lorem ipsum Aliqua eiusmod qui veniam officia
										voluptate sed esse sed. Lorem ipsum In ad dolore labore sed est dolor laboris tempor consequat. Lorem ipsum Ex aute dolor sunt cillum adipisicing in irure in. Lorem ipsum Do sunt esse eu esse exercitation est id non tempor Excepteur nisi. Lorem
										ipsum Magna labore officia ex in amet adipisicing sunt Excepteur esse irure cillum exercitation. Lorem ipsum Sunt eiusmod voluptate cupidatat Duis eu magna esse nisi. Lorem ipsum Cillum qui exercitation ea eu cillum commodo commodo non velit
										incididunt culpa elit. Lorem ipsum Est minim est sunt Duis tempor ut nulla. Lorem ipsum In ut elit minim cillum id dolor do Duis.
									</div>
									<div class="tab-pane" id="second">
										Lorem ipsum Proident do cupidatat exercitation amet velit dolor Ut reprehenderit magna. Lorem ipsum Elit fugiat aliqua in culpa Ut aliquip Duis id ea enim in velit. Lorem ipsum Dolore qui dolor id enim aliquip Ut occaecat proident occaecat dolore. Lorem
										ipsum Ut veniam tempor eu dolor dolore pariatur consectetur ea elit. Lorem ipsum In dolore anim aliqua nulla ullamco sunt. Lorem ipsum Ex nulla sit esse tempor Excepteur eiusmod labore occaecat sint. Lorem ipsum Laboris ullamco Excepteur nulla
										occaecat occaecat amet mollit irure esse. Lorem ipsum Fugiat in irure aliqua officia nisi eiusmod aute officia. Lorem ipsum Incididunt consequat nulla in eu aute fugiat in anim enim proident do. Lorem ipsum In fugiat in commodo id aliquip in.
										Lorem ipsum Sit commodo in qui amet sit adipisicing incididunt dolor. Lorem ipsum Exercitation esse fugiat do enim enim esse laboris minim. Lorem ipsum Ex eiusmod quis do laborum sunt officia ullamco veniam veniam sunt ex. Lorem ipsum Anim dolore
										quis cillum magna eu magna consectetur laborum magna ut do. Lorem ipsum Excepteur laboris incididunt sed in dolor occaecat ad anim officia velit nulla. Lorem ipsum Dolore eiusmod non sit amet non tempor consequat. Lorem ipsum Do commodo aute
										quis reprehenderit reprehenderit tempor reprehenderit sint. Lorem ipsum Amet voluptate incididunt ex dolore cupidatat ut.
									</div>
									<div class="tab-pane" id="thirds">
										Lorem ipsum Officia quis sint sit sit tempor proident est enim exercitation nostrud do pariatur. Lorem ipsum Veniam aute Duis eu fugiat voluptate ut sed aliquip sunt Duis in cupidatat. Lorem ipsum Ut Excepteur exercitation do quis ut esse ut in dolor
										in. Lorem ipsum Cupidatat anim quis magna esse consequat est dolor sunt quis ex. Lorem ipsum Mollit sint sunt tempor culpa est eiusmod commodo officia est tempor mollit. Lorem ipsum Enim Duis cillum cupidatat eu cupidatat eiusmod ullamco. Lorem
										ipsum Sed dolor cillum non in minim et consequat incididunt ut minim nulla consectetur. Lorem ipsum Deserunt consectetur sint est aute id exercitation dolor ad nulla. Lorem ipsum Dolore velit Duis voluptate dolore consequat dolor Ut.
									</div>
								</div>
							</div>
						                             
            </div>-->
                        
                        
                    </div>
        </div>
    </div>
</div>

              <h5 style="color:red; text-align: right">G =>Grade P =>Grade Points</h5>
                
<?php
            
      
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