<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style>
    #slider {
  position: relative;
  overflow: hidden;
  margin: 20px auto 0 auto;
  border-radius: 4px;
}

#slider ul {
  position: relative;
  margin: 0;
  padding: 0;
  height: 200px;
  list-style: none;
}

#slider ul li {
  position: relative;
  display: block;
  float: left;
  margin: 0;
  padding: 0;
  width: 500px;
  height: 300px;
  background: #ccc;
  text-align: center;
  line-height: 300px;
}

a.control_prev, a.control_next {
  position: absolute;
  top: 40%;
  z-index: 999;
  display: block;
  padding: 4% 3%;
  width: auto;
  height: auto;
  background: #2a2a2a;
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  font-size: 18px;
  opacity: 0.8;
  cursor: pointer;
}

a.control_prev:hover, a.control_next:hover {
  opacity: 1;
  -webkit-transition: all 0.2s ease;
}

a.control_prev {
  border-radius: 0 2px 2px 0;
}

a.control_next {
  right: 0;
  border-radius: 2px 0 0 2px;
}

.slider_option {
  position: relative;
  margin: 10px auto;
  width: 160px;
  font-size: 18px;
}

</style>
<?php
$q="SELECT sex,count(*) as count FROM `student` WHERE `section_id` = '".$section_data->sid."' AND iid= '".$this->session->userdata("staff_Org_id")."' GROUP BY sex";
$q=$this->db->query($q);
$q=$q->result();
$b=$g=0;
foreach($q as $t){
   if($t->sex==2){
      $g = $g+$t->count;
   }else if($t->sex==1){
       $b = $b+$t->count;
   } 
}

$check="SELECT `transport`,`hostel` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
$check = $this->db->query($check)->row();
$hostel="SELECT count(*) as total  FROM `hostel_students` h JOIN student s ON h.student_id = s.student_id WHERE s.section_id='".$section_data->sid."' ";
$hostel =$this->db->query($hostel)->row();
$trans="SELECT count(*)  as total FROM `stud_transport` h JOIN student s ON h.stud_id = s.student_id WHERE s.section_id='".$section_data->sid."' ";
$trans =$this->db->query($trans)->row();
$total =$b+$g;
$day_Scholars =$b+$g;
$hostelers =0;
$transport =0;
if($check->transport==1){
    $transport=$trans->total;
    $day_Scholars=$day_Scholars-$transport;
}
if($check->transport==1){
    $hostelers=$hostel->total;
    $day_Scholars=$day_Scholars-$hostelers;
}
$medium = unserialize (medium);
?>
<script type="text/javascript" src="<?php echo assets_path ?>highcharts/js/jquery.min.js"></script>	
<?php
  if($total!=0){
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
                text: 'Total Class Strength : <?php  echo $b+$g ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
             plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                    name: 'Boys (<?php echo $b ?>)',
                    y: <?php echo $b ?>
                },{
                    name: 'Girls (<?php echo $g ?>)',
                    y: <?php echo $g ?>
                }]
        }]
        });
    });
    </script>
    <script type="text/javascript">
    $(function () {
        Highcharts.chart('days_container', {
            chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Class Wise Report'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                    name: 'Days-Scholars (<?php echo $day_Scholars ?>)',
                    y: <?php echo $day_Scholars ?>
                },{
                    name: 'Transportation (<?php echo $transport ?>)',
                    y: <?php echo $transport ?>
                },{
                    name: 'Hostlers (<?php echo $hostelers ?>)',
                    y: <?php echo $hostelers ?>
                }]
        }]
        });
    });
    </script>
     <?php
  }
?>
    

<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
      
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/class_list">Manage Class</a>
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Section Details</a>
                </li>
            </ul>
        </div>
    <?php
    if(strlen($this->session->userdata('Section_add_Sucess'))>0 ){
        ?>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $this->session->userdata('Section_add_Sucess'); ?>
            </div>
        <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
       <?php
        $this->session->unset_userdata('Section_add_Sucess');
    }
    ?>
           
        
    <div class="row" >
            <div class="col-sm-5">
                <div class="box box-bordered box-color" >
                    <div class="box-title">
                        <h3>Course Details </h3>
                        <?php
                        if($this->session->userdata('staff_level')>3){
                          ?>
                        <div class="actions">
                            <a  class="btn btn-primary" href="<?php echo base_url() ?>index.php/academics/assign_sub?section=<?php echo $section_data->sid  ?>" ><i class="fa fa-plus"></i>&nbsp;Add Subject</a> 
                           
                        </div>
                          <?php
                        }
                        ?>
                        
                    </div>
                    <div class="box-content nopadding" style=" max-height: 200px; overflow-y: scroll">
                        <div class='form-horizontal form-bordered'>
                           <table  class="table table-hover table-nomargin table-bordered"  style="width: 100%">
                               <thead>
                                          <th>S.no</th>
                                          <th>Subject</th>
                                          <th>Faculty Name</th>
                                          <th>Actions</th>
                                       </thead>
                               <tbody>
                                   <?php
                                     $query="SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$section_data->sid."' ORDER BY sec.sid , s.sid ASC";
                                     $query=$this->db->query($query);
                                     if($query->num_rows()==0){
                                         ?><tr>
                                             <td  colspan="4" style=" text-align: center; color: red" >NO Records Found..</td>
</tr>
                                             <?php
                                     }else{
                                     $query=$query->result();$i=1;
                                     foreach($query as  $value){
                                       ?>
                                      <tr id="tr_<?php   echo $value->cid; ?>">
                                               <td id="id_<?php   echo $value->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                           <td style=" width: 15%" ><?php echo $value->subject; ?></td>
                                           <td  style=" width: 40%"><?php if($value->name !=NULL ){
                                                               echo $value->name;
                                                           }else{
                                                               echo "--";
                                                           }?></td>
                                           <td id="td_actions_<?php   echo $value->cid; ?>" >
                                               <?php if($value->name !=NULL ){
                                                   ?>
                                               <button  onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Edit Faculty" >
                                                   <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                               </button>
                                                  <?php
                                               }else{
                                                   ?>
                                               <button onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Assign Faculty" >
                                                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                                               </button>
                                                  <?php
                                               }?>


                                           </td>
                                       </tr>   
                                      <?php
                                     }}
                                     ?>
                               </tbody> 
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 " style="  margin-top: 20px;">
                <div class="w3-content w3-section" style="max-width:500px">
                <div class="mySlides" id="container"  style="width:100% ; width : 350px; height: 250px; ">
                 <h4  style=" color: red; text-align: center">Please Add Student To View Graphs </h4>
                </div>
                <div class="mySlides" id="days_container"  style="width:100% ; width : 350px; height: 250px; ">
                 <h4  style=" color: red; text-align: center">Please Add Student To View Graphs </h4>
                </div>
                                  
                </div>
                <script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 6000); // Change image every 2 seconds
}
</script>
<!--               <div id="container" style=" width : 350px; height: 250px; ">
                  
               </div>  -->
            </div>
            <div class="col-sm-3"><br/>
                <div class="col-sm-12" style=" background-color: #ff9933; color: white; min-height: 50px;">
                        <div class="pull-left">
                            <strong><br/>Class<br/></strong>
                        </div>
                        <div class="pull-right">
                            <strong><br/><?php echo $section_data->class ?> ( <?php echo $medium[$section_data->medium] ?>)<br/></strong>
                        </div>
                    </div>
                    <div class="col-sm-12" style=" background-color: #006699; color: white; min-height: 50px;">
                        <div class="pull-left">
                            <strong><br/>Section<br/></strong>
                        </div>
                        <div class="pull-right">
                            <strong><br/><?php echo $section_data->section  ?><br/></strong>
                        </div>
                    </div>
                <div class="col-sm-12" style=" background-color : #999900; color: white; min-height: 50px;">
                        <div class="pull-left">
                            <strong><br/>Medium<br/></strong>
                        </div>
                        <div class="pull-right">
                            <strong><br/><?php echo $medium[$section_data->medium]  ?><br/></strong>
                        </div>
                    </div>
                    <div class="col-sm-12" style=" background-color: #009999; color: white; min-height: 50px;">
                        <div class="pull-left">
                            <strong><br/>In-charge<br/></strong>
                        </div>
                        <div class="pull-right">
                            <strong><br/><?php 
                            if(strlen($section_data->staff_name)==0){
                                echo "----";
                            }else{
                              echo   $section_data->staff_name;
                            }?><br/></strong>
                        </div>
                    </div>
                <div class="col-sm-12" style=" padding-top: 3px; height: 75px;">
                    <div style="height: 75px; padding-top: 10px; background-color: #3399ff ;  text-align: center; width: 32%; float: left; margin:1.5px;">
                            <a style=" color: #ffffff;" href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $section_data->sid ?>" rel="tooltip" title="" data-original-title="Edit section " >
                               <i class="fa fa-pencil-square-o fa-4x"></i> 
                            </a> 
                        </div>  
                        <div style="height: 75px;padding-top: 10px;background-color:  limegreen;  text-align: center;width: 32%; float: left;margin:1.5px;">
                            <a target="_blank" rel="tooltip" title="" data-original-title="Account's Info " href="<?php echo base_url() ?>index.php/accounts/view/<?php echo $section_data->class_id  ?>/<?php echo $section_data->sid  ?>" style=" color: #ffffff;" >
                                <i class="fa fa-usd fa-4x"></i> 
                            </a> 
                        </div>
                    <div style="height: 75px;padding-top: 10px;background-color: #ffcc00; text-align: center; width: 32%;float: left; margin:1.5px;">
                            <a style=" color: #ffffff;" target="_blank" href="<?php echo base_url(); ?>index.php/academics/print_section_info/<?php echo $section_data->sid ?>" rel="tooltip" title="" data-original-title="Print section Info" >
                                <i class="fa fa-print fa-4x"></i> 
                            </a> 
                        </div>  
                        
                </div>
            </div>
            
<script src="<?php echo assets_path  ?>graphs/code//highcharts.js"></script>
<script src="<?php echo assets_path  ?>graphs/code//modules/exporting.js"></script>

            
     
    </div>
        <?php
        
        $timetable = $this->db->query("SELECT * FROM `timings`  WHERE section ='".$section_data->sid."' ");
        if($timetable->num_rows()==0){
            ?>
             <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-calendar"></i>Time Table</h3> 
                        
                        <?php
                        if($this->session->userdata('staff_level')>3){
                          ?>
                       <div class="actions">
                            <a class="btn btn-primary"  href="<?php echo base_url() ?>index.php/timetable/create"><i class="fa fa-plus"></i>&nbsp;&nbsp;Create Time Table</a>  
                        </div> 
                          <?php
                        }
                        ?>
                        
                </div>
                <div class="box-content nopadding" style=" text-align: center"><br/>
                    <h4 style=" margin: 0px; text-align: center; color: red" >Time Table Not Yet Configured</h4><br/>
                </div>
            </div>
             </div>
           <?php
        }else{
            $timetable=$timetable->row();
        $q=$this->db->query("SELECT c.cid,s.subject  FROM `course` c JOIN subjects s ON c.subid=s.sid  WHERE `secid` = '".$section_data->sid."'");
        $q=$q->result();
        $course=array();
        foreach ($q as $key => $value) {
          $course[$value->cid] =$value->subject; 
        }
        
        ?>
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-calendar"></i>Time Table</h3> 
                        <div class="actions" >
                        <?php
                        if($this->session->userdata('staff_level')>3){
                          ?>
                            <a  href="<?php echo base_url() ?>index.php/timetable/edit/<?php echo $timetable->tid   ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit</a>  
                          <?php
                        }
                        ?>
                            <a  href="<?php echo base_url() ?>index.php/timetable/pdf_print/<?php echo $timetable->tid   ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</a>  
                        </div>     
                </div>
                <div class="box-content nopadding"> 
                  
                    <table class="table table-bordered  table-hover table-nomargin" >
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
                               $query=$this->db->query("SELECT cr.*,c.subid,s.subject FROM class_routine cr LEFT JOIN course c ON cr.course_id =c.cid LEFT JOIN subjects s ON c.subid=s.sid  WHERE cr.tid='".$timetable->tid."' ORDER BY cr.day asc,cr.time_start ASC ");
                                
                               if($query->num_rows()>0){
                               
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
                                             <td></td>
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
        </div>    
       
       <?php
        }
       ?>
        <?php 
        $students="SELECT *  FROM `student` WHERE `section_id` = '".$section_data->sid."' AND iid= '".$this->session->userdata("staff_Org_id")."'  ORDER BY roll ASC ";
        $students=$this->db->query($students)->result();
        ?>
           
           <?php
    if(strlen($this->session->userdata('Student_delete_sucess'))>0 ){
        ?>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $this->session->userdata('Student_delete_sucess'); ?>
            </div>
        <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
       <?php
        $this->session->unset_userdata('Student_delete_sucess');
    }
    ?>
           
           <div class="box box-bordered box-color" >
            <div class="box-title">
                <h3><i class="fa fa-users"></i>Students List</h3>   
                <div class="actions">
                    <a  class=" btn btn-primary" target="_blank"  href="<?php echo base_url() ?>index.php/Students/print_students/<?php echo $section_data->class_id?>/<?php echo $section_data->section?>"><i class="fa fa-print"></i>&nbsp;Print</a>
                    <a  class="btn btn-primary" href="<?php echo base_url() ?>index.php/Students/Send_sms/<?php echo $section_data->class_id ?>/<?php echo $section_data->section ?>"  rel="tooltip" title="" data-original-title="Send SMS"><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Send SMS</a>
       
                </div>
            </div>
            <div class="box-content nopadding" style=" max-height: 500px; overflow-y: scroll" >
                <table  class="table table-hover table-nomargin table-bordered"  style="width: 100%">
                             <thead>
                                        <tr>
                                            <th >Roll No</th>
                                            <th>Student name</th>
                                            <th>Userid</th>
                                            <?php
                                           if($this->session->userdata('staff_level')>3){
                                               ?>
                                                <th >password</th>
                                                <?php
                                              }
                                              ?>           
                                            <th>Gender</th>
                                            <th >mobile</th>
                                            <th>Actions</th>                                                
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i=1;
                                    foreach ($students as $value) {
                                         ?>
                                        <tr>
                                            <td><?php echo $value->roll ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><?php echo $value->userid ?></td>
                                            <?php
                                               if($this->session->userdata('staff_level')>3){
                                                   ?>
                                                    <td><?php echo $value->password ?></td>
                                                    <?php
                                                  }
                                                  ?>
                                            
                                            
                                            <td><?php
                                           // echo $value->sex;
                                            if($value->sex==1)
                                                        {
                                                            echo "Male";
                                                        }else{
                                                            echo "Female";
                                                        }
                                                        ?></td>
                                            <td><?php echo $value->phone ?></td>
                                            
                                            <td>
                                                <a href="<?php echo base_url() ?>index.php/Students/view_details/<?php  echo $value->userid   ?>"  rel="tooltip" title="" data-original-title="View Student Details"  ><i class="fa fa-eye" aria-hidden="true"></i>
</a>
                                                <?php
                                                if($this->session->userdata('staff_level')>3){
                                                 ?>
                                                <a href="<?php echo base_url() ?>index.php/Students/edit/<?php  echo $value->student_id   ?>"  rel="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
</a> 
                                                <?php
                                                  if($this->session->userdata('staff_Org_id')==52){
                                                      ?>
                                                <a onclick="Delete_student('<?php  echo $value->student_id   ?>');"  rel="tooltip" title="" data-original-title="Edit"><i class="fa fa-trash" aria-hidden="true"></i>
</a> 
                                                  <?php
                                                  }
                                                
                                                }
                                                ?>
                                               
                                                
                                                
                                            </td>
                                           
                                        </tr>
                                         <?php 
                                       }
                                   ?>
                                </tbody>
                        </table>
            </div>
        </div>
           <span id="delete_student"></span>
        
    <script>
        function assign_faculty(value){
            //td_actions_
            id='td_actions_'+value;
            setState(id,'<?php echo base_url() ?>index.php/ajax/Load_assign','cid='+value);
        }

        function Delete_student(value){
            
              setState('delete_student','<?php echo base_url() ?>index.php/Students/delete_student','student_id='+value);  
            
        }
        function save_faculty(value){
            teach=$('#select_'+value).val();
            //id_
            i=$('#id_'+value).html();
            if(teach.length!=0){
              setState('tr_'+value,'<?php echo base_url() ?>index.php/ajax/save_assigned','cid='+value+'&staff='+teach+'&i='+i);  
            }else{
                //error_
                $('#error_'+value).html("<br/>** Please select faculty");
            }
        }
    </script> 
       
       </div>
    </div>

</div>
<?php
$this->load->view('structure/footer');
?>
