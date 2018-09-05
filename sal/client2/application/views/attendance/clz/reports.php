<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>	    
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=getdate();
$class="";
if(strlen($this->input->get("month"))>0){
    $month = $this->input->get("month");
}else{
    $month=$t['mon'];
}
if(strlen($this->input->get("year"))>0){
    $year = $this->input->get("year");
}else{
     $year=$t['year'];
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
                            <a href="<?php echo base_url(); ?>index.php/attendance">Attendance</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Student Attendance Reports</a>
                        </li>
                    </ul>
                </div> 
            
            <div class="box">
                <div class="col-sm-4 nopadding ">
                    <div class="box box-bordered box-color ">
                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Monthly Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <form class='form-horizontal form-bordered' action="<?php echo base_url() ?>index.php/Clzattendance/reports?"  >

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class - section </label>
                                        <div class="col-sm-10">
                                            <select class="select2-me" id="cls_name" name="cls_name"  style=" width: 100% "  >
                                                        <option value="" >Class - Section</option>
                                                        <?php
                                                            $section = $this->db->query("SELECT s.sid ,s.name as sec_name , c.name as cls_name FROM `section` s  JOIN class c ON s.class_id=c.id where s.iid='".$this->session->userdata('staff_Org_id')."'   ORDER BY c.id");
                                                            $section = $section->result();
                                                            foreach ($section as $value) {
                                                              ?>
                                                        <option  value="<?php echo $value->sid ?>" <?php
                                                          if($value->sid==$class){
                                                              echo " SELECTED ";
                                                          }
                                                        ?>  ><?php echo $value->cls_name." - ".$value->sec_name ?></option>
                                                             <?php
                                                            }

                                                            ?>
                                                       </select> 
                                            
                                            <span id="cls_name_err" style=" color: red; text-align: center">
                                            </span>        
                                        </div>
                                </div>
                               
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Date wise</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="from" class="form-control datepick" placeholder="From Date" style=" float: left; width: 48%" /> <span style=" float: left ; width: 4%; text-align: center">-</span> <input placeholder="To Date"  style=" float: left; width: 48%" type="text" name="to_date" class="form-control datepick" /> 
                                            <span id="month_year_err" style=" color: red; text-align: center">
                        
                                                </span>        
                                        </div>
                                </div> 
                                
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button type="submit"  class="btn btn-primary" >Fetch Attendance Report</button>
                                   
                                </div>

                           </form>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4 nopadding ">
                    <div class="box box-bordered box-color" style=" padding-left: 10px;">
                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Day-wise  Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                           <form class='form-horizontal form-bordered' action=""  >
                               <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class - section </label>
                                        <div class="col-sm-10" style=" height: 55px">
                                            <select class="select2-me"   id="dtcls_name" name="dtcls_name"  style=" width: 100% "  >
                                                        <option value="" >Class - Section</option>
                                                        <?php
                                                            foreach ($section as $value) {
                                                              ?>
                                                        <option  value="<?php echo $value->sid ?>" <?php
                                                          if($value->sid==$class){
                                                              echo " SELECTED ";
                                                          }
                                                        ?>  ><?php echo $value->cls_name." - ".$value->sec_name ?></option>
                                                             <?php
                                                            }

                                                            ?>
                                                       </select> 
                                            
                                            <span id="dtcls_name_err" style=" color: red; text-align: center">
                                            </span>        
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Date</label>
                                        <div class="col-sm-10">
                                            <input type="text"  name="att_date" id="att_date" class="form-control datepick" value="<?php echo date('d/m/Y'); ?>" />
                                            
                                            <span id="att_date_err" style=" color: red; text-align: center">
                                                    
                                                </span>        
                                        </div>
                                </div> 
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Fetch Attendance Report</button>
                                </div>

                           </form>
                        </div>

                    </div>
                </div>
                <div class="col-sm-4 nopadding ">
                    <div class="box box-bordered box-color" style=" padding-left: 10px;">
                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Student Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                           <form class='form-horizontal form-bordered' action="<?php echo base_url() ?>index.php/Clzattendance/reports?"  >

                               <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class - section </label>
                                        <div class="col-sm-10" style=" height: 55px">
                                            <select class="select2-me" onchange="load_students();"  id="stdcls_name" name="stdcls_name"  style=" width: 100% "  >
                                                        <option value="" >Class - Section</option>
                                                        <?php
                                                            foreach ($section as $value) {
                                                              ?>
                                                        <option  value="<?php echo $value->sid ?>" <?php
                                                          if($value->sid==$class){
                                                              echo " SELECTED ";
                                                          }
                                                        ?>  ><?php echo $value->cls_name." - ".$value->sec_name ?></option>
                                                             <?php
                                                            }

                                                            ?>
                                                       </select> 
                                            
                                            <span id="stdcls_name_err" style=" color: red; text-align: center">
                                            </span>        
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select style=" width: 100%; "  class="select2-me" name="student" id="student">
                                                <option value="" >Class - Section</option>                                      
                                            </select>                                            
                                            <span id="att_date_err" style=" color: red; text-align: center">
                                            </span>        
                                        </div>
                                </div> 
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button type="submit" id="std_report" disabled="" class="btn btn-primary">Fetch Attendance Report</button>
                                        
                                </div>

                           </form>
                        </div>
                        <script>
                            function load_students(){
                               sec = $('#stdcls_name').val();
                               $('#stdcls_name_err').html(" ");
                               if(sec.length==0){
                                  $('#stdcls_name_err').html(" ** Please select Setion to load students");$('#std_report').prop('disabled', true);
                               }else{
                                     setState('student','<?php echo base_url() ?>index.php/attendance/load_students','section='+sec);
                                  $('#std_report').prop('disabled', false);
                               }
                             //  alert(sec);
                            }
                            </script>
                    </div>
                </div>
            </div>
            <script>
             function fetch_day_attendance(){
                att_date = $('#att_date').val(); 
                $('#att_date_err').html("");
                if(att_date.length==0){
                    $('#att_date_err').html("** Please select Date");
                }else{
                   setState('Attendance_reports','<?php echo base_url() ?>index.php/attendance/fetch_day_attendance','att_date='+att_date);
    
                }
             }    
             
             function fetch_monthly_attendance(){
                cls_name = $('#cls_name').val();
                att_month = $('#att_month').val();
                att_year = $('#att_year').val(); 
                count=0;
                $('#cls_name_err').html("");
                $('#month_year_err').html("");
                if(att_month.length==0){
                    $('#month_year_err').html("** Please select Month");
                    count++;
                }
                if(att_year.length==0){
                    $('#month_year_err').html("** Please select Year");
                    count++;
                } 
                 if(count==0){
                    if(cls_name.length==0){
                          setState('Attendance_reports','<?php echo base_url() ?>index.php/attendance/fetch_monthly_attendance','att_month='+att_month+'&att_year='+att_year);
                    }else{
                       setState('Attendance_reports','<?php echo base_url() ?>index.php/attendance/fetch_monthly_attendance','att_month='+att_month+'&att_year='+att_year+'&cls_name='+cls_name);

                    }
                 }
                
              }
            </script>
                
                
            <div class="box " id="Attendance_reports" style=" clear: both; min-height: 450px;">
                <?php
                if(strlen($this->input->get('att_date'))>0 ){
                   $day = explode("/", $this->input->get('att_date'));
                  if(sizeof($day)!=3){
                      ?><h5 style=" color: red; text-align: center">** Invalid Date selected</h5><?php
                  }else{
                      $ti= mktime(0, 0, 0, $day['1'], $day['0'], $day['2']);
                      
                      if($ti>time()){
                        ?><h5 style=" color: red; text-align: center">** Invalid Date selected</h5><?php
                      }else{
                          if(strlen($this->input->get("dtcls_name"))>0){
                              $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' AND s.sid='".$this->input->get("dtcls_name")."' ";
                              $sections = $this->db->query($sections)->row();
                             
                             $q="SELECT d.id,d.section ,d.slot , (SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id = (d.id) ) as abs_count, c.time  FROM `attendance_date` d JOIN attendance_config c ON d.slot=c.acid where d.day ='".$ti."' AND d.section='".$this->input->get("dtcls_name")."'  ORDER BY d.slot  ";
                              $q= $this->db->query($q);
                               if($q->num_rows()==0){
                                  ?>
                        <h4 style=" color: red; text-align: center">** No Attendance Records Found.. for <?php echo $sections->cls_name ." - ".$sections->sec_name  ?> On <?php echo $this->input->get("att_date"); ?></h4>
                                   <?php
                               }else{
                                  ?><br/><br/><hr/>
                    
                      <div class="box box-bordered box-color">
                          <div class="box-title">
                              <h3><i class="fa fa-check"></i>Attendance Reports of <?php echo $sections->cls_name ." - ".$sections->sec_name  ?> On <?php echo $this->input->get("att_date"); ?></h3>
                          </div>
                          <div class="box-content nopadding"  style=" min-height: 300px; max-height: 1050px;" >
                                <div class="tabs-container" >
                                    <ul class="tabs tabs-inline tabs-left">
                                        <?php
                                          $i=1;
                                         $q= $q->result();
                                            foreach ($q as $value) {
                                          
                                           $slot="slot - ".$i;
                                           ?>
                                                <li <?php 
                                                    if($i==1){
                                                        ?>class='active'<?php
                                                    }
                                                    ?>>
                                                        <a href="#<?php echo $i++  ?>" data-toggle='tab'>
                                                        <?php echo $slot ?><br/>
                                                        <?php echo substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2) ?>
                                                        </a>
                                                    </li>
                                                   <?php

                                          }
                                        ?>
                                    </ul>
                            </div>
                            <div class="tab-content padding tab-content-inline " style=" min-height: 450px; "  >
                                <?php
                                $i=1;
            //                    print_r($q);
                                foreach ($q as $value) {
                                     $slot="slot - ".$i;
                                       ?>
                                      <div class="tab-pane <?php 
                                          if($i==1){
                                              ?>active<?php
                                          }
                                          ?> nopadding" id="<?php echo $i++  ?>" style=" clear: both;  text-align: center"
                                          >

        <script type="text/javascript">
    $(function () {
    Highcharts.chart('container_<?php echo $value->slot ?>', {
    chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
    },
    title: {
    text: '<?php echo $slot; ?> ( <?php echo substr($value->time,0,strlen($value->time)-2).":".substr($value->time,strlen($value->time)-2) ?> )'
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
    name: 'Brands',
    colorByPoint: true,
    data: [{
        name: 'Present (<?php echo $sections->total-$value->abs_count ?>)',
        y: <?php echo (($sections->total-$value->abs_count)/$sections->total)*100 ?>
    }, {
        name: 'absent (<?php echo $value->abs_count ?>)',
        y: <?php echo (($value->abs_count)/$sections->total)*100 ?>,
        sliced: true,
        selected: true
    }]
    }]
    });
    });
        </script>
        <div class="box nopadding">
            <div class="col-sm-6">
                <div id="container_<?php echo $value->slot ?>" style="min-width: 400px;height: 400px; ">

                </div>
            </div>
            <div class="col-sm-6">
                <h4 style=" text-align: center; color: orangered">Absenties List</h4>
                <?php 
              $stud="SELECT s.student_id,s.name , s.userid , s.roll,s.phone from attendance a JOIN student s ON a.student=s.student_id WHERE date_id='".$value->id."'  ";
               $stud = $this->db->query($stud)->result();  
             
                ?>
                <table class="table table-bordered table-striped datatable" style=" width:100% ">
                    <thead>
                    <th>Roll No</th>
                    <th>Student</th>
                    <th>Admission No</th>
                    <th>Mobile</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach($stud as $s){
                           ?>
                        <tr>
                            <td><?php echo $s->roll ?></td>
                            <td><?php echo $s->name ?></td>
                            <td><?php echo $s->userid ?></td>
                            <td><?php echo $s->phone ?></td>
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
                                }
                                ?>
                            </div>
                    </div>
                      </div>


		
                
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                                
                                    <?php    
                               }
                               
                          }else{
                          
                         $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' ";

             $sections = $this->db->query($sections);
             if($sections->num_rows()>0){
                $att_set=$this->db->query("select c.acid,c.time,s.section,se.name as sec_name, cl.name as cls_name from attendance_config c JOIN attendance_settings s on c.asid = s.aid JOIN section se ON se.sid= s.section JOIN class cl ON cl.id=se.class_id where c.iid = '".$this->session->userdata("staff_Org_id")."' ")->result();
                if(sizeof($att_set)==0){
                ?><h4 style=" color: red; text-align: center">** No sections found..</h4><?php
                }else{
                    $at_counter=0;
                    $att_set_array=array();
                    foreach($att_set as $value){
                        if(!isset($att_set_array[$value->section])){
                            $to_stud=$this->db->query("SELECT count(*) as total from student where section_id ='".$value->section."' ")->row();
                            $to_stud=$to_stud->total;
                            $att_set_array[$value->section]=array('sec_name'=>$value->sec_name,'cls_name'=>$value->cls_name,"acids"=>"","total_stud" =>$to_stud,"t_slots"=>0,"t_per"=>0 );
                        }
                        $att_set_array[$value->section]["acids"].=$value->acid.",";
                        $att_set_array[$value->section]["attendance"][$value->acid]=array("time"=>$value->time);
                        $a= $this->db->query("SELECT d.* ,(SELECT count(*) from attendance where date_id=d.id ) as total_abs from attendance_date d WHERE d.slot='".$value->acid."' AND d.day='".$ti."' "); 
                            if($a->num_rows()!=0){
                                $at_counter++;
                              $a =$a->row();
                              $att_set_array[$value->section]["t_slots"]++;
                              $att_set_array[$value->section]["t_per"]+=number_format( (100-($a->total_abs/$att_set_array[$value->section]["total_stud"])*100) ,2);
                              $att_set_array[$value->section]["attendance"][$value->acid]["date_id"]=$a->id;
                              $att_set_array[$value->section]["attendance"][$value->acid]["abs_count"]=$a->total_abs;
                            }else{
                               $att_set_array[$value->section]["attendance"][$value->acid]["date_id"]="--";
                                 $att_set_array[$value->section]["attendance"][$value->acid]["abs_count"]="--";
                            }

                    }
                    if($at_counter==0){
                        ?><br/><br/><br/><h4 style=" color: red; text-align: center">** Attendance Not yet Added..</h4><?php
                       }else{

                           $grph_names="";
                            foreach ($att_set_array as $value) {
                              if($value['t_slots']>0){
                                $grph_names.="['".$value['cls_name']." <br/> ".$value['sec_name']."',".($value['t_per']/$value['t_slots'])." ],";
                                 
                              }
                            }
                           $grph_names=substr($grph_names,0,strlen($grph_names)-1);
                          
                       ?>
                       
 <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: '<br/><hr/> Attendance Report On <?php echo date("d-m-Y",$ti); ?> '
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
                   <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3>
                                    <i class="fa fa-check"></i>Attendance Report On <?php echo date("d-m-Y",$ti) ?></h3>
                                        <div class="actions">
                                        </div>
                        </div>
                        <div class="box-content nopadding"  style=" min-height: 300px; max-height: 1050px;" >
                                <div class="tabs-container" >
                                    <ul class="tabs tabs-inline tabs-left">
                                        <?php
                                          $i=1;
                                          foreach ($att_set_array as $key=>$value) {
                                                ?>
                                                <li <?php 
                                                    if($i==1){
                                                        ?>class='active'<?php
                                                    }
                                                    ?>>
                                                        <a href="#<?php echo $i  ?>" data-toggle='tab'>
                                                        <?php echo $value['cls_name']." - ".$value['sec_name'] ?>
                                                        </a>
                                                    </li>
                                                   <?php
                                                      $i++;

                                          }
                                        ?>
                                    </ul>
                            </div>
                            <div class="tab-content padding tab-content-inline " style=" min-height: 450px; "  >
                                        <?php
                                        $i=1;
                                        foreach ($att_set_array as $key=>$value) {
                                               ?>
                                              <div class="tab-pane <?php 
                                                  if($i==1){
                                                      ?>active<?php
                                                  }
                                                  ?> nopadding" id="<?php echo $i  ?>"
                                                  >
                                                  <div style=" width: 100%; height: 30px;"  >
                                                      <div style=" float: left; color:  orangered" >
                                                          <h4> <?php 
                                                    echo $value['cls_name']." - ".$value['sec_name'] ;
                                                    ?>    </h4>
                                                      </div>
                                                      <div style=" float: right; color:  orangered" >
                                                       <h4>Total Students : <?php 
                                                    echo $value['total_stud'] ;
                                                    ?> </h4>   
                                                      </div>
                                                      
                                                      </div><hr/>
                                                  <table class="table " style=" text-align: center; width: 100%;" >
                                                      <tr>
                                                      <?php 
                                                      $grph_names="";
                                                      $i=1;
                                                        foreach ($value['attendance'] as $att) {
?>                                                        
                                                          <td style=" border-left: 1px solid #cccccc; border-right:1px solid #cccccc; border-bottom: 1px solid #cccccc; " >
                                                          Slot - <?php echo $i ?> 
                                                        ( <?php echo substr($att["time"],0,strlen($att["time"])-2).":".substr($att["time"],0,strlen($att["time"])-2); ?> )<hr/>
                                                         <?php 
                                                         if($att["abs_count"]=="--"){
                                                            echo  $att["abs_count"]; 
                                                         }else{
                                                           $grph_names.="['Slot - ".$i++." ',".number_format( ( ($value["total_stud"]-$att["abs_count"])/$value["total_stud"])*100,2)." ],";  
                                                             echo  number_format( ( ($value["total_stud"]-$att["abs_count"])/$value["total_stud"])*100,2)." %" ;
                                                         }
                                                            
                                                         ?>
                                                      </td>
 <?php
                                                        }
                                                       $grph_names=substr($grph_names,0,strlen($grph_names)-1);
                                                      ?></tr>
                                                  </table>
                                                  
 <script type="text/javascript">
                                    $(function () {
                                        Highcharts.chart('sec_container_<?php echo $key ?>', {
                                            chart: {
                                                type: 'column'
                                            },
                                            title: {
                                                text: ''
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
                <hr/>
                <div id="sec_container_<?php echo $key ?>" style="min-width: 300px; height: 500px; margin: 0 auto">
                    
                </div>
                                                  
                                              </div>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </div>
                    </div>
                </div>

               <?php
                    }
                    
                              
                }        
                }else{
                    ?><h4 style=" color: red; text-align: center">** No sections found..</h4><?php
                }
            
            ?>
            <?php
                      }
                      }
                   }
                }
                ?>
                <?php
                  if( (strlen($this->input->get('from'))>0) && (strlen($this->input->get('to_date'))>0) ){
                     $from = explode("/",$this->input->get('from'));
                     $to = explode("/",$this->input->get('to_date'));
                      if( (sizeof($from)!=3) || (sizeof($to)!=3) ){
                          ?>
                    <br/><br/><br/><h4>** Invalid Dates Selected</h4>    
                          <?php
                      }else{
                          $from = mktime(0, 0, 0,$from[1], $from[0],$from[2]);
                          $to = mktime(0, 0, 0,$to[1], $to[0],$to[2]);
                          if(($to > time()) ||($from > time()) ){
                              ?>
                    <br/><br/><br/><h4>** Please Select Valid Dates</h4>    
                          <?php
                          }else{
                              if($from >$to){
                                 ?>
                    <br/><br/><br/><h4>** Pleas Select Valid Date Range</h4>    
                                <?php 
                              }else{
                                 // echo "".$from;
                                 // echo "<br/>".$to;
                                  $cls=$this->input->get("cls_name");   
                                  if(strlen($cls)>0){
                                      $sec=$this->db->query("SELECT s.aid,se.sid, se.name as sec_name , c.name as cls_name ,(select count(*) from student where section_id = se.sid ) as total_students, (select group_concat(acid) FROM attendance_config WHERE asid=s.aid) as acids from attendance_settings s JOIN section se On s.section =se.sid JOIN class c On se.class_id=c.id  WHERE s.iid ='".$this->session->userdata("staff_Org_id")."' AND s.section='".$cls."' ");
                                     
                                  }else{
                                     $sec=$this->db->query("SELECT s.aid,se.sid, se.name as sec_name , c.name as cls_name ,(select count(*) from student where section_id = se.sid ) as total_students, (select group_concat(acid) FROM attendance_config WHERE asid=s.aid) as acids from attendance_settings s JOIN section se On s.section =se.sid JOIN class c On se.class_id=c.id  WHERE s.iid ='".$this->session->userdata("staff_Org_id")."' ");
                                      
                                  }
                                     $sec = $sec->result();
                                     $acids="";
                                     $section_array= array();
                                     foreach ($sec as $value) {
                                         $section_array[$value->sid]= array("sec_name" =>$value->sec_name,"cls_name" =>$value->cls_name,"acids" =>$value->acids,"total_students" =>$value->total_students ); 
                                         $acids.=$value->acids.",";
                                     }
                                     $acids = substr($acids , 0 ,strlen($acids)-1);
                                  $att_date ="SELect d.*,c.time , (select count(*) from attendance where date_id = d.id ) as total_abs from  attendance_date d JOIN attendance_config c ON c.acid = d.slot where slot in (".$acids.") AND (day >= '".$from."' AND day < '".$to."' ) ORDER BY day ASC ";   
                                     $att_date = $this->db->query($att_date)->result();
                                     $date_att = array();
                                      foreach($att_date as $value){
                                         $d =getdate($value->day);  
                                         if(!isset($date_att[$value->day][$value->section])){
                                             $date_att[$value->day][$value->section] = array("slots" =>0,"percentage"=>0);
                                         }
                                         //"slots"
                                         $date_att[$value->day][$value->section]["slots"]++;
                                         $date_att[$value->day][$value->section]["percentage"]+=  number_format( ( ($section_array[$value->section]["total_students"]-$value->total_abs) / $section_array[$value->section]["total_students"] )*100 ,2);
                                         $date_att[$value->day][$value->section]["Attendance"][$value->slot]=array("total_abs"=>$value->total_abs,"time"=>$value->time);;
                                         //$date_att[$value->day][$value->section][$value->slot]["time"]=$value->time;
                                      }
                                     
                                      //['01-03-17','15-03-17']
                                      $labels="";$per_lab="";
                                      foreach($date_att as $key=>$value){
                                        $labels.="'".date("d-m-Y",$key)."',";  
                                        $t_stud =0;$per=0;
                                        foreach ($value as $s) {
                                           $t_stud++;
                                           $per+=number_format($s["percentage"]/$s["slots"],2);
                                        } 
                                        $per_lab.= number_format(($per/$t_stud),2).",";
                                      }
                                      
                                      $labels = substr($labels ,0,strlen($labels)-1);
                                      $per_lab = substr($per_lab ,0,strlen($per_lab)-1);
                                      ?>
                     <script src="https://code.highcharts.com/highcharts.js"></script>
                                                                    <script src="https://code.highcharts.com/modules/exporting.js"></script>

<hr/>
                    <div id="containers_All" style=" width: 1100px; height: 400px; margin: 0 auto" >
         
                    </div>
                                                                    
                    <script type="text/javascript">

Highcharts.chart('containers_All', {
    chart: {
        type: 'line'
    },
    title: {
        text: ' Attendance Report From  <?php echo date("d/m/Y",$from); ?> To <?php echo date("d/m/Y",$to); ?> '
    },
    xAxis: {
        categories:[<?php echo $labels ?>] 
    },
    yAxis: {
        title: {
            text: 'Present Percentage'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Attendance Percentage',
        data: [<?php echo $per_lab ?>]
    }]
});
		</script>
                 
                <div class="box box-bordered box-color ">
							<div class="box-title">
								<h3>
									<i class="fa fa-check"></i>Attendance Report From  <?php echo date("d/m/Y",$from); ?> To <?php echo date("d/m/Y",$to); ?></h3>
							</div>
							<div class="box-content nopadding">
								<div class="tabs-container">
									<ul class="tabs tabs-inline tabs-left">
                                                                            <?php
                                                                              $i=1;
                                                                              foreach($date_att as $key=>$value){
                                                                                   ?>
                                                                            <li <?php if($i==1){
                                                                                echo "class='active'";
                                                                            } ?>>
											<a href="#<?php echo $key ?>" data-toggle='tab'>
												<?php echo date("d-m-Y",$key);  ?></a>
										</li>
                                                                                   <?php
                                                                                   $i++;
                                                                              }
                                                                            
                                                                            ?>
										
										
									</ul>
								</div>
								<div class="tab-content padding tab-content-inline">
									<?php
                                                                              $i=1;
                                                                              foreach($date_att as $key=>$value){
                                                                                   ?>
                                                                        <div class="tab-pane <?php 
                                                                        if($i==1){
                                                                            echo " active ";
                                                                        }
                                                                        ?>" id="<?php echo $key ?>">
                                                                            <h4 style=" text-align: center; color:  #ff6600">Attendance Report ON <?php echo date("d-m-Y",$key); ?></h4>	
                                                                        <?php
                                                                        foreach ($value as $sec_key=>$sec) {
                                                                          //  print_r($section_array[$sec_key]);
                                                                            ?>
                                                                            <div class="box"  >
                                                                                <div class="col-sm-6"  >
                                                                                    <h4 style=" color:  #66ff00"><?php echo  $section_array[$sec_key]["cls_name"] ." - ". $section_array[$sec_key]["sec_name"]  ?></h4>
                                                                                </div>
                                                                                <div class="col-sm-6"  >
                                                                                    <h4 style=" text-align: right ; color:  #66ff00">Total Students : <?php echo  $section_array[$sec_key]["total_students"] ?></h4>
                                                                                </div>
                                                                                <br style=" clear: both"/><hr/>
                                                                                <table class="table table-bordered table-striped" style=" width: 100%; text-align: center ">
                                                                                    <tr>
                                                                                       <?php 
                                                                                        foreach( $sec["Attendance"] as $att_val){
                                                                                            ?>
                                                                                        <td>
                                                                                            <?php echo substr($att_val['time'],0,strlen($att_val['time'])-2).":".substr($att_val['time'],strlen($att_val['time'])-2) ?>
                                                                                            <hr/>
                                                                                            A : <?php echo $att_val['total_abs'] ?> P: <?php echo ($section_array[$sec_key]["total_students"]-$att_val['total_abs']) ?>
                                                                                        </td>
                                                                                             <?php
                                                                                        }
                                                                                       ?> 
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                            <?php  
                                                                        }
                                                                         // print_r($value);
                                                                        ?>
                                                                        </div>
                                                                    
                                                                                   <?php
                                                                                   $i++;
                                                                              }
                                                                              ?>
                                                                        
								</div>
							</div><span style="float: right; color: red">A => Absenties Count P => Presenties Count  </span>
						</div>
					</div>
                    
                
                                      <?php
                                 
                                  
                                  
                              }
                              
                          }
                          
                      }
                     
                  }
                
                ?>
                
              <?php
                if(isset($_GET['stdcls_name']) &&  isset($_GET['student'])  ){
                   $student = $this->input->get('student');
                   $att_set =$this->db->query( "select * from attendance_settings where section ='".$_GET['stdcls_name']."' ");
                if($att_set->num_rows()!=0){
                    $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
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
                            $per =($value["present"]/$value["total"])*100;
                            $per =  number_format($per,2);
                            $grph_names.= "['".$key."',".$per."] ,";
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
                    <div class="col-sm-3" style=" border: 1px solid #318ee0; margin-top: 20px;">
                        <h3 style=" text-align: center">Student Profile</h3>
                        <table class="table  nopadding">
                            <tr>
                                <td><i class="fa fa-user"></i>&nbsp;<?php echo $student->name  ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-sitemap"></i>&nbsp;<?php echo $student->cls_name." - ".$student->sec_name  ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-ticket"></i>&nbsp;<?php echo $student->userid ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-phone"></i>&nbsp;<?php echo $student->phone ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa fa-envelope"></i>&nbsp;<?php echo $student->email ?></td>
                            </tr>
                            
                        </table><hr/>
                        <i class="fa fa-check"></i>  => present<br/>
                        <i class="fa fa-times"></i>  => absent<br/>
                        --&nbsp;  => Attendance not taken<br/>
                    </div>
                    <div class="col-sm-9">
                        <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-bars"></i>Attendance Brief History</h3>
                                                <div class="actions">
                                                    <a style=" color: #386ee0; background-color:  white" target="_blank" class="btn btn-primary" href="<?php echo base_url(); ?>index.php/attendance/student_brief_print/<?php echo $_GET['student'] ?>" ><i class="fa fa-print"></i>&nbsp;Print</a>
                                                </div>
                                </div>
                            <div class="box-content nopadding"  style=" min-height: 300px; max-height: 550px;" >
                                <div class="tabs-container" >
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
                                                  <h4 style=" text-align: center; color : #ff0000  ">Total Slots :<?php echo $value["total"] ?>&nbsp;Present Slots :<?php echo $value["present"] ?>&nbsp;Attendance Perentage :<?php echo number_format( ($value["present"]/$value["total"])*100,2 ) ?></h4>

                                              </div>
                          <?php
                          $i++;
                                        }
                                        ?>
                                    </div>
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
              ?>
            
            </div>
            
        </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>