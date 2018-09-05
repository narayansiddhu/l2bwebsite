<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$view_att=0;
if($this->input->get("view")=="attendance"){
   $view_att=1; 
}
?>
<style type="text/css">
    .form-group11{
        height: 65px;
        max-height: 70px;
    }
    .form-group12{
        height: 130px;
        max-height: 140px;
    }
    .fip{
        height: 100%;
    }
</style>
<script>
    function month_attendance(){
      year=$('#att_year').val();
      month=$('#att_month').val();
      $("#fetch_att_error").html("");
     if(year.length == 0 || month.length==0 ){
         $("#fetch_att_error").html("<br/> Please Select Month And Year");
     }else{
        
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_attendance','student=<?php echo $student->student_id; ?>&month='+month+'&year='+year);
     }
    
  }
  function fetch_exam_results(){
   // exam_results_holder
    exam=$('#exam_r').val();
   $("#rexam_err").html("");
    if(exam.length==0){
        //rexam_err
        $("#rexam_err").html("Please select Exam");
    }else{
        //fetch_exam_results
        setState('exam_results_holder','<?php echo base_url() ?>index.php/Students/fetch_exam_results','student=<?php echo $student->student_id; ?>&exam='+exam);
        
    }
  }
  
</script>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <br/>
            
        <ul class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() ?>index.php/Login/dashboard">Home</a>
                </li>
                <li class="active">
                    <a href="">View <?php echo $student->name  ?> Details</a>  
                </li>
            </ul>
        </ul>
            <div class="box">
            
            <div class="pull-left">
                <h2 style=" color:  #ff6600" ><?php echo strtoupper($student->name) ?></h2>
            </div>
            <div  class="pull-right" >
                            <a class="new_title" href="">
                               <i class="fa fa-user fa-2x"></i>
                                <p>Profile</p>
                           </a> 
                            <btn onclick="course();" class="new_title" >
                               <i class="fa fa-sitemap fa-2x"></i>
                                <p>Course</p>
                            </btn> 
                            <btn onclick="fee_payments();" class="new_title" >
                               <i class="fa fa-inr fa-2x"></i>
                                <p>Fees</p>
                           </btn> 
                            <btn onclick="library();" class="new_title" >
                               <i class="fa fa-book fa-2x"></i>
                                <p>Library</p>
                            </btn>
                            <btn onclick="timetable();" class="new_title" >
                               <i class="fa fa-tasks fa-2x"></i>
                                <p>Timetable</p>
                            </btn>
                        <btn class="new_title" onclick="assignments();" >
                            <i class="fa fa-files-o fa-2x" aria-hidden="true"></i>
                            <p> Assignments</p>
                        </btn>
                <a class="new_title" onclick="attendance();" href="?view=attendance" >
                            <i class="fa fa-check fa-2x" aria-hidden="true"></i>
                            <p> Attendance</p>
                        </a>
                        
                        <btn onclick="results();"  class="new_title">
                            <i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i> <p>Results</p>
                        </btn>
            </div>
        </div>
            <br style=" clear: both"/>
                    <hr style=" clear: both"/>
       <div style=" clear: both" class="box">
            <div class="col-sm-3 nopadding" style=" margin-top: 21px; padding-top: 10px; border:2px solid #318EEE">
           <div style=" text-align: center"><br/>
           <?php
              if(strlen($student->photo)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$student->photo)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $student->photo  ?>" alt="..." style=" width: 100px;; height: 100px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
                 }
              }
              ?>
           </div><hr/>
           <h3 style=" text-align: center; color:  #318EEE"><u><?php echo $student->name ?></u></h3>
           <table class=" table table-bordered nopadding" style=" width:100%">
               <tr>
                   <td><i class="fa fa-sitemap" aria-hidden="true"></i>&nbsp;<?php echo $student->cls_name ." - " .$student->sec_name ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;<?php echo $student->userid ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<?php echo date('d-m-Y',$student->birthday) ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $student->phone ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo $student->email ?></td>
               </tr>
           </table>
            
       </div>
            <div class="col-sm-9 ">
                
                <div class="box" style="margin-top: 21px;  border:  2px solid #318EEE">
                    <div id="result_holder" style=" clear: both; " class="box nopadding">
                        <?php 
                  if($view_att==1){
                      
                      $now=time();    
            if( ( (strlen($this->input->post('month'))!=0 ) && (strlen($this->input->post('year'))!=0 ) )){
              
                  $now=   mktime(0,0,0,$this->input->post('month'),1,$this->input->post('year'));
             
            }
            $time=  getdate($now);
            $from=mktime(0,0,0,$time['mon'],1,$time['year']);
            $to=mktime(0,0,0,$time['mon']+1,1,$time['year']);
            if($this->session->userdata("institute_att_type")==1){
             //   echo "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student->student_id."' AND s.iid='".$this->session->userdata("parent_org_id")."' ";
                $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student->student_id."' AND s.iid='".$this->session->userdata("parent_org_id")."' ");
              
                        $student = $student->row();
                        $dates_array=array();
                        $dates = $this->db->query( " SELECT * FROM attendance_date WHERE iid='".$this->session->userdata("parent_org_id")."' and section='".$student->section_id."' ");
                        $a="";
                        if($dates->num_rows()>0){
                            $dates= $dates->result();
                            $st_att=array();
                            $std_att=$this->db->query("SELECT DISTINCT(date_id) from attendance WHERE  student='".$student->student_id."' ");
                            $std_att=$std_att->result();
                            foreach ($std_att as $value) {
                                $st_att[$value->date_id]=1;
                            }
                            $monthly_att=array();
                            foreach ($dates as $value) {
                                if(isset($dates_array[$value->id])){
                                    $dates_array[$value->id][$value->slot]=1;
                               }else{
                                    $dates_array[$value->id] = array('day'=>$value->day);
                                    $dates_array[$value->id][$value->slot]=$value->slot;
                                }
                                $d= getdate($value->day);
                                if(isset($monthly_att[$d['year']][$d['mon']])){
                                     $monthly_att[$d['year']][$d['mon']]['total']++;
                                }else{
                                    $monthly_att[$d['year']][$d['mon']] = array('month'=>$d['month'],'total'=>1,'absent'=>0);
                                }
                                $monthly_att[$d['year']][$d['mon']]['att'][$value->day][$value->slot]=$value->id;
                                if(isset($st_att[$value->id])){
                                    $a.=$value->id.",";
                                    $monthly_att[$d['year']][$d['mon']]['absent']++;
                                }
                                
                            }

                            $grph_names="";
                            foreach ($monthly_att as $key=>$value) {
                                foreach ($value as $k=>$val) {
                                    $pr=( ($val['total']-$val['absent'])/$val['total'])*100;
                                  $grph_names.="[ '".$val['month']."<br/>(".$key.")' ,".$pr."],";
                                }
                            }
                            ?>
                              <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '<br/><hr/><?php echo $student->name  ?> Attendance Report '
                                            },
                                            subtitle: {
                                                text: ''
                                            },
                                            xAxis: {
                                                type: 'category',
                                                labels: {
                                                    rotation: -45,
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: {
                                                    text: 'Attendance percentage'
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                pointFormat: 'Attendance in % : '
                                            },
                                            series: [{
                                                name: 'Attendance % :',
                                                data: [<?php echo $grph_names ?>
                                                ],
                                                dataLabels: {
                                                    enabled: true,
                                                    rotation: -90,
                                                    color: '#FFFFFF',
                                                    align: 'right',
                                                    format: '{point.y:.1f}', // one decimal
                                                    y: 10, // 10 pixels down from the top
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            }]
                                        });
                                    });
                                                    </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <br/><br/><hr/>
                <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto">
                    
                </div>
                <div class="box" >
                        <div class="box ">
                                
                            <div class="box-content nopadding"  style=" min-height: 300px; max-height: 550px;" >
                                <h3 style=" text-align: center; color:  #ff6600">Attendance Brief History</h3>
                                        
                                <div class="tabs-container" style=" border:  1px solid #cccccc">
                                                <ul class="tabs tabs-inline tabs-left">
                                                    <?php
                                                      $i=1;
                                                      foreach ($monthly_att as $key=>$value) {
                                                            foreach ($value as $k=>$val) {
                                                                $pr=( ($val['total']-$val['absent'])/$val['total'])*100;
                                                              $grph_names.="[ '".$val['month']."<br/>(".$key.")' ,".$pr."],";
                                                              ?>
                                                            <li <?php 
                                                                if($i==1){
                                                                    ?>class='active'<?php
                                                                }
                                                                ?>>
                                                                    <a href="#<?php echo $i  ?>" data-toggle='tab'>
                                                                    <?php echo $val['month']."-".$key ?>
                                                                    </a>
                                                                </li>
                                                               <?php
                                                                  $i++;
                                                            }
                                                      }
                                                    ?>
                                                </ul>
                                        </div>
                                    <div class="tab-content padding tab-content-inline nopadding" style=" max-height: 450px; overflow-y: scroll"  >
                                        <?php
                                                      $i=1;
                                                      foreach ($monthly_att as $key=>$value) {
                                                            foreach ($value as $k=>$val) {
                                                                $pr=( ($val['total']-$val['absent'])/$val['total'])*100;
                                                              $grph_names.="[ '".$val['month']."<br/>(".$key.")' ,".$pr."],";
                                                              ?>
                                                            <div class="tab-pane <?php 
                                                                if($i==1){
                                                                    ?>active<?php
                                                                }
                                                                ?>" id="<?php echo $i  ?>"
                                                                >
                                                                <h4 style=" text-align: center ; color:  teal"><?php 
                                                                  echo "Attendance Report Of ".$val['month']."-".$key ;
                                                                  ?></h4>
                                                                <table class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Date</th>
                                                                            <th>Morning</th>
                                                                            <th>Afternoon</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                          $total=0;$present=0;
                                                                            foreach ($val['att'] as $kr=>$vark) {
                                                            //                    print_r($vark);
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php  echo date("d-m-y",$kr) ?></td>
                                                                                    <td><?php 
                                                                                        if(isset($vark[1])){
                                                                                            $total++;
                                                                                            if(isset($st_att[$vark[1]])){
                                                                                                $present++;
                                                                                                ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                                            }else{
                                                                                                ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                                            }
                                                                                        } else{
                                                                                            echo "--";
                                                                                        }
                                                                                    ?></td>
                                                                                    <td><?php 
                                                                                        if(isset($vark[2])){
                                                                                            $total++;
                                                                                            if(isset($st_att[$vark[2]])){
                                                                                                $present++;
                                                                                                ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                                            }else{
                                                                                                ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                                            }
                                                                                        } else{
                                                                                            echo "--";
                                                                                        }
                                                                                    ?></td>
                                                                                </tr>
                                                                               <?php
                                                                            }
                                                                        ?>
                                                                        
                                                                    </tbody>
                                                                </table>
                                                                <h4 style=" margin: 0px; text-align: center; padding-top: 8px; ">Total : <?php echo $total ?> Present : <?php echo $total-$present ?> Absent : <?php echo $present ?></h4>
                                                            </div>
                                                               <?php
                                                                  $i++;
                                                            }
                                                      }
                                                    ?>        
                                        </div>
                                </div>
                        </div>
                    
                </div>
                
				
                            <?php
                        }else{
                          ?><br/><br/>
                <span style=" color: red">** Invalid Student Selected</span>
                       <?php  
                            
                        }
                    
            }else{
                   $att_set =$this->db->query( "select * from attendance_settings where section ='".$student->section_id."' ");
                
                   if($att_set->num_rows()!=0){
                    $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student->student_id."' AND s.iid='".$this->session->userdata("parent_org_id")."' ");
                  if($student->num_rows()==0){
                       ?><br/><br/>
                <span style=" color: red; text-align: center">** Invalid Student Selected</span>
                       <?php
                   }else{
                        $student = $student->row();
                   
                        $att_set =$att_set->row();

                        $att_config=$this->db->query("SELECT * from attendance_config where asid='".$att_set->aid."' ")->result();
                        $att_config_arr = array();
                        $att_ids="";
                        foreach ($att_config as $value) {
                            $att_ids.=$value->acid.",";
                         $att_config_arr[$value->acid]=$value->time;   
                        }
                        $att_ids = substr($att_ids,0,strlen($att_ids)-1);
                        $att=$this->db->query("SELECT d.* , (select count(*)  from attendance a where student='".$student->student_id."' AND a.date_id=d.id  ) as att_status FROM attendance_date d  where  slot IN (".$att_ids.")")->result(); 
                        $att_array=array();
                        $month_att_arr=array();
                        foreach ($att as $value) {
                            $day=  getdate($value->day);
                            $day =$day['month']."-".$day['year'];
                            if(!isset($month_att_arr[$day])){
                                   $month_att_arr[$day]=array("total"=>0,"present"=>0);
                             }
                             $month_att_arr[$day]['total']++;
                             if($value->att_status!=0){
                                $month_att_arr[$day]['present']++; 
                             }

                            $att_array[$day][$value->day][$value->slot]=$value->att_status;
                        }
                        $grph_names ="";
                        foreach($month_att_arr as $key=>$value){
                            $per =(($value["total"]-$value["present"])/$value["total"])*100;
                            $per =  number_format($per,2);
                            if(strlen($per)==0){
                               $per=0; 
                            }
                            $grph_names.= "['".$key." (".$per."%) ',".$per."] ,";
                        }
                       $grph_names =  substr($grph_names, 0, strlen($grph_names)-1);
                       ?>
                       <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '<br/><hr/><?php echo $student->name  ?> Attendance Report '
                                            },
                                            subtitle: {
                                                text: ''
                                            },
                                            xAxis: {
                                                type: 'category',
                                                labels: {
                                                    rotation: -45,
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            },
                                            yAxis: {
                                                min: 0,
                                                title: {
                                                    text: 'Attendance percentage'
                                                }
                                            },
                                            legend: {
                                                enabled: false
                                            },
                                            tooltip: {
                                                pointFormat: 'Attendance in % : '
                                            },
                                            series: [{
                                                name: 'Attendance % :',
                                                data: [<?php echo $grph_names ?>
                                                ],
                                                dataLabels: {
                                                    enabled: true,
                                                    rotation: -90,
                                                    color: '#FFFFFF',
                                                    align: 'right',
                                                    format: '{point.y:.1f}', // one decimal
                                                    y: 10, // 10 pixels down from the top
                                                    style: {
                                                        fontSize: '13px',
                                                        fontFamily: 'Verdana, sans-serif'
                                                    }
                                                }
                                            }]
                                        });
                                    });
                                   </script>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <br/><br/><hr/>
                <div id="container" style="min-width: 300px; height: 400px; margin: 0 auto">
                    
                </div>
                <div class="box" >
                    
                        <div class="box ">
                                        
                            <div class="box-content nopadding"  style=" min-height: 300px; max-height: 550px;" >
                                <h3 style=" text-align: center; color:  #ff6600; border-bottom:  1px solid #cccccc">Attendance Brief History</h3>
                                
                                <div class="tabs-container nopadding" style=" border: 1px solid #cccccc" >
                                                <ul class="tabs tabs-inline tabs-left">
                                                    <?php
                                                      $i=1;
                                                      foreach ($month_att_arr as $key=>$value) {
                                                            ?>
                                                            <li <?php 
                                                                if($i==1){
                                                                    ?>class='active'<?php
                                                                }
                                                                ?>>
                                                                    <a href="#<?php echo $i  ?>" data-toggle='tab'>
                                                                    <?php echo $key ?>
                                                                    </a>
                                                                </li>
                                                               <?php
                                                                  $i++;
                                                           
                                                      }
                                                    ?>
                                                </ul>
                                </div>
                                    <div class="tab-content padding tab-content-inline nopadding" style=" max-height: 450px; overflow-y: scroll"  >
                                        <?php
                                        $i=1;
                                        foreach ($month_att_arr as $key=>$value) {
                                               ?>
                                              <div class="tab-pane <?php 
                                                  if($i==1){
                                                      ?>active<?php
                                                  }
                                                  ?>" id="<?php echo $i  ?>"
                                                  >
                                                  <h3 style=" text-align: center"><?php 
                                                    echo "Attendance Report Of ".$key ;
                                                    ?></h3>
                                                  <table class="table table-bordered table-striped" style=" text-align: center ">
                                                      <thead>
                                                          <tr>
                                                              <th style=" text-align: center ">Date</th>
                                                              <?php
                                                                  foreach ($att_config_arr as $tim) {
                                                                      ?>
                                                                    <th style=" text-align: center "><?php   echo substr($tim,0,strlen($tim)-2).":".substr($tim,strlen($tim)-2) ?></th>
                                                                      <?php 
                                                                   }
                                                              ?>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <?php
                                                    foreach ($att_array[$key] as $d=>$var) {
                                                        ?>
                                                          <tr>
                                                              <th style=" text-align: center "><?php echo date("d-m-Y",$d) ?></th>
                                                                  <?php
                                                                  foreach ($att_config_arr as  $c=>$tim) {
                                                                      ?>
                                                                    <td><?php 
                                                                       if(isset($var[$c])){
                                                                           if($var[$c]==0){
                                                                               ?><i class="fa fa-check" aria-hidden="true"></i><?php
                                                                              }else{
                                                                                  ?><i class="fa fa-times" aria-hidden="true"></i><?php
                                                                              }
                                                                       }else{
                                                                           echo "--";
                                                                       }  
                                                                            ?></td>
                                                                      <?php 
                                                                   }
                                                              ?>
                                                          </tr>
                                                         <?php
                                                    }
                                                          ?>
                                                      </tbody>
                                                  </table>
                                                  <h4 style=" text-align: center; color : #ff0000  ">Total Slots :<?php echo $value["total"] ?>&nbsp;Present Slots :<?php echo $value["total"]-$value["present"] ?>&nbsp;Attendance Perentage :<?php echo number_format( ( ($value["total"]-$value["present"]) /$value["total"])*100,2 ) ?></h4>

                                              </div>
                          <?php
                          $i++;
                                        }
                                        ?>
                                    </div>
                            </div>
                        </div>
                   
                </div>
                        
                           
                       <?php   
                    }
                }else{
                    ?><br/><br/>
                <span style=" color: red; text-align: center">** Attendance Settings Not Yet Configured </span>
                    <?php
                }
            }
            
                  }else{
                      ?>
                        <div class="box-content nopadding">
                                <h3 style='color:  #ff9900; text-align: center'>Profile</h3>
                                <hr/>
                             <div  class='form-horizontal form-bordered'>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->name ?></span> 
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Father Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->father_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mother Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold">  <?php echo $student->mother_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Admission No</label>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->userid ?></span>
                                    </div>
 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Class-Section</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->cls_name ." - " .$student->sec_name ?></span>
                                    </div>
                                 </div>
                                <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Roll No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->roll ?></span>
                                    </div> 
                                 </div>
                                 <?php // print_r($student);
                                  $blood_group = unserialize(blood_groups); 
                                  $Caste_system=  unserialize(Caste_system);
                                 ?>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Birthday</label>
                                           <span class="form-control" style=" font-weight:  bold"><?php echo date('d-m-Y',$student->birthday) ?>
                                           </span> 
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mobile No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->phone ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($blood_group[$student->bloodgroup])){
                                                echo $blood_group[$student->bloodgroup];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Gender</label>
                                               <span class="form-control" style=" font-weight:  bold"><?php if($student->sex ==1){
                                                    echo "Male";
                                                }else{
                                                    echo "Female";
                                                    }    ?></span>
                                            
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Email</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->email ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Caste</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($Caste_system[$student->caste])){
                                                echo $Caste_system[$student->caste];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Address</label>
                                            <textarea  disabled="" rows="5" cols="7"  class="form-control" style=" resize: none; font-weight:  bold"><?php echo $student->address ?></textarea>
                                     </div> 
                                     <br/>
                                 </div>
                                
                             </div>
                                <div class="box" style=" clear: both;  height: 10px;">
                                    &nbsp;
                                </div>
                        </div>   
                          <?php
                  }
                
                ?>
                            
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
    </div>   
</div>
<script>
  
  function profile(){
     
     setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_profile','student=<?php echo $student->student_id; ?>');
  }
  
  function fee_payments(){
//      $('#result_header').html('<i class="fa fa-money"></i> Fee Payments');
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_fee','student=<?php echo $student->student_id; ?>');
      
  }
  
  function library(){
//       $('#result_header').html('<i class="fa fa-book"></i> Library');
       //ajax_library
       setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_library','student=<?php echo $student->student_id; ?>');
  
  }
  
  function timetable(){
//      $('#result_header').html('<i class="fa fa-calendar"></i> Time Table');
      //ajax_timetable
       setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_timetable','student=<?php echo $student->student_id; ?>');
  
  }
  
  function assignments(){
    //  $('#result_header').html('<i class="fa fa-files-o"></i> Assignments');
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student=<?php echo $student->student_id; ?>');
  }
  
  function attendance(){
     setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_attendance','student=<?php echo $student->student_id; ?>');
  }
  
  function course(){
          setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_course','student=<?php echo $student->student_id; ?>');
  }
  
  
  
  function results(){
      setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_exam_results','student=<?php echo $student->student_id; ?>');           
  }
  
  function fetch_assignments(){
     subdate=$('#subdate').val(); 
     $("#sub_date_error").html("");
     if(subdate.length==0){
         $("#sub_date_error").html("<br/>Please select date");
     }else{
         //assignments_holder
         setState('result_holder','<?php echo base_url() ?>index.php/Students/ajax_assignments','student=<?php echo $student->student_id; ?>&date='+subdate); 
     }
  }
  
  
</script>


<?php
$this->load->view('structure/footer');
?>
