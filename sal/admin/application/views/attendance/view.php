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
<!--                <div class="col-sm-4 nopadding ">
                    <div class="box box-bordered box-color ">
                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Monthly Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <div class='form-horizontal form-bordered' action="<?php echo base_url() ?>index.php/attendance/view?"  >

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
                                            
                                            <span id="cls_name_err" style=" color: red">
                                            </span>        
                                        </div>
                                </div>
                               
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Month-year</label>
                                        <div class="col-sm-10">
                                            <select class="select2-me" id="att_month" name="month"  style=" width: 50%  ; float: left"  >
                                                <option value="" >Month</option>
                                                <?php
                                                  for ($m=1; $m<=12; $m++) {
                                                      $time = mktime(0, 0, 0, $m, 1, 2016) ;
                                                      $time = getdate($time);
                                                      ?> <option value="<?php echo $m ?>"  <?php 
                                                        if($month==$m){
                                                            echo "selected";
                                                        }

                                                      ?> ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                                    }
                                                    ?>
                                           </select> 
                                            <select class="select2-me" id="att_year" name="year"  style=" width:50% ;float: left "  >
                                                                <option value="" >Year</option>
                                                                <?php
                                                                    $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                                                    $start=$start->row();
                                                                    $start=$start->timestamp;
                                                                    $start=getdate($start);
                                                                    $start=$start['year'];
                                                                    $now=getdate();
                                                                    $now=$now['year'];
                                                                    for($i=$start;$i<=$now;$i++){
                                                                      ?>
                                                                <option value="<?php echo $i; ?>"  <?php
                                                                        if($time['year']==$i){
                                                                            echo "selected";
                                                                        }

                                                                     ?> ><?php echo $i; ?></option>
                                                                      <?php
                                                                    }

                                                                ?>
                                                               </select> 
                                            <span id="month_year_err" style=" color: red">
                        
                                                </span>        
                                        </div>
                                </div> 
                                
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button type="submit" onclick="fetch_monthly_attendance();" class="btn btn-primary" >Fetch Attendance Report</button>
                                   
                                </div>

                           </div>
                        </div>

                    </div>
                </div>-->
                <div class="col-sm-4 nopadding  ">
                    <div class="box box-bordered box-color" >
                    
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
                                            
                                            <span id="dtcls_name_err" style=" color: red">
                                            </span>        
                                        </div>
                                </div>

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Date</label>
                                        <div class="col-sm-10">
                                            <input type="text"  name="att_date" id="att_date" class="form-control datepick" value="<?php echo date('d/m/Y'); ?>" />
                                            
                                            <span id="att_date_err" style=" color: red">
                                                    
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
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered box-color" style=" padding-left: 10px;">
                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Student-wise Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                           <form class='form-horizontal form-bordered' action="<?php echo base_url() ?>index.php/attendance/view?"  >

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
                                            
                                            <span id="stdcls_name_err" style=" color: red">
                                            </span>        
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select style=" width: 100%; "  class="select2-me" name="student" id="student">
                                                <option value="" >Select Student</option>                                      
                                            </select>                                            
                                            <span id="att_date_err" style=" color: red">
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
                            
                <div class="col-sm-4 nopadding">
                    <?php
                    $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                    $start=$start->row();
                    $start =$start->timestamp;
                    $start=getdate($start); 
                    $start=mktime(0, 0, 0, $start['mon'], 1,$start['year']);
                    $now =time();
                    $now= getdate($now);
                    $now=mktime(0, 0, 0, $now['mon']+1, 1,$now['year']);
                    ?>
                    <div class="box box-bordered box-color" style=" padding-left: 10px;">                    
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Month-wise Report</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                           <div class='form-horizontal form-bordered'>
                               <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class - section </label>
                                        <div class="col-sm-10" style=" height: 55px">
                                            <select class="select2-me"   id="mnth_cls_name" name="mnth_cls_name"  style=" width: 100% "  >
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
                                            
                                            <span id="mnth_cls_name_err" style=" color: red">
                                            </span>        
                                        </div>
                                </div>
                               <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Month</label>
                                        <div class="col-sm-10">
                                            <select style=" width: 100%; "  class="select2-me" name="month" id="month">
                                                <option value="" >Select Month</option>    
                                                <?php
                                                while($start<$now){
                                                    $start=  getdate($start);
                                                    ?><option value="<?php echo $start['mon']."-".$start['year']; ?>"><?php echo $start['month']." - ".$start['year']; ?></option><?php
                                                    $start =  mktime(0, 0, 0, $start['mon']+1, 1,$start['year']);
                                                }

                                                ?>
                                            </select>                                            
                                            <span id="month_err" style=" color: red">
                                            </span>        
                                        </div>
                                </div> 
                               <div  class="form-actions col-sm-offset-2 col-sm-10">
                                   <button type="button" id="std_report" onclick="monthly_attendance();" class="btn btn-primary">Fetch Attendance Report</button>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                //attendance/attendance_report/1?month=6&year=2017&submit=Fetch+Attendance
             function monthly_attendance(){
                 mnth_cls_name =$('#mnth_cls_name').val();
                 month =$('#month').val();
                 if(mnth_cls_name.length==0){
                    $('#mnth_cls_name_err').html("** Select Class Name");
                 }else if(month.length==0){
                    $('#month_err').html("** Select Month-Year");
                 }else{
                     //trip=trip.split(',');
                     month=month.split("-");
                     window.open("<?php echo base_url() ?>index.php/attendance/attendance_report/"+mnth_cls_name+"?month="+month[0]+"&year="+month[1]+"&submit=Fetch+Attendance");
                 }
                 
             }
             
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
                             
                              $q="SELECT d.section ,d.slot ,  (SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id = (d.id) ) as abs_count  FROM `attendance_date` d where d.day ='".$ti."' AND d.section='".$this->input->get("dtcls_name")."'  ORDER BY d.slot  ";
                              $q= $this->db->query($q);
                               if($q->num_rows()==0){
                                  ?>
                        <h4 style=" color: red; text-align: center">** No Attendance Records Found.. for <?php echo $sections->cls_name ." - ".$sections->sec_name  ?> On <?php echo $this->input->get("att_date"); ?></h4>
                                   <?php
                               }else{
                                  ?><br/><br/><hr/>
                <h4 style=" color: red; text-align: center"> Attendance Reports of <?php echo $sections->cls_name ." - ".$sections->sec_name  ?> On <?php echo $this->input->get("att_date"); ?></h4>
                      <br/><br/>       <?php
                                       foreach ($q->result() as $value) {
                                           $slot="Morning Slot";
                                           if($value->slot==2){
                                             $slot="Afternoon Slot";
                                           }
                                           
                                           ?>
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
            text: '<?php echo $slot; ?> ON <?php echo $this->input->get("att_date") ?>'
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
                <div class="col-sm-6">
               
                                <div id="container_<?php echo $value->slot ?>" style="min-width: 300px; height: 400px; margin: 0 auto">

                                </div>
                     </div>
                                           <?php
                                       }
                             ?>
		
                
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                                
                                    <?php    
                               }
                               
                          }else{
                          
                         $sections = " SELECT s.sid,c.id,s.name as sec_name , c.name as cls_name ,(SELECT count(*) from student st where st.section_id=s.sid ) as total FROM `section` s JOIN class c ON s.class_id = c.id where s.iid= '".$this->session->userdata('staff_Org_id')."' ";

             $sections = $this->db->query($sections);
            $q="SELECT d.section , group_concat(d.id)as date_id , (SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id IN (group_concat(d.id))  ) as abs_count  FROM `attendance_date` d where d.day ='".$ti."'  GROUP BY d.section  ORDER BY d.section  ";
            
            if($sections->num_rows()>0){
                $attendance_list = array();$q = $this->db->query($q);
                if($q->num_rows()>0){
                    
                
                $q = $q->result();
                
                foreach($q as $value){
                       $attendance_list[$value->section] = array("date_ids"=>$value->date_id,"count"=>$value->abs_count);
                }
                $sections2 =$sections->result();
                $grph_names="";$clses="";$pers="";$toatl=0;$absenties=0;
                foreach($sections2 as $value){
                    if(isset($attendance_list[$value->sid])){
                      $abs=$attendance_list[$value->sid]['count'];
                    }else{
                        $abs=0;
                    }
                    if($value->total!=0){
                        $toatl+=$value->total;
                        $absenties+=($value->total-$abs);
                        $clses.="'".$value->cls_name." - ".$value->sec_name."( ".$value->total.")',";
                        $pers.= round((($value->total-$abs)/$value->total)*100 ).",";
                       $grph_names.="[ '".$value->cls_name." - ".$value->sec_name."<br/>( ".$value->total.")' ,".((($value->total-$abs)/$value->total)*100 )."],";
                     
                    }
               }
                ?>
                
                
            <script type="text/javascript">
$(function () {
Highcharts.chart('container', {
title: {
text: 'Attendance Report Of <?php echo $this->input->get("att_date"); ?> '
},
xAxis: {
categories: [<?php echo $clses ?>]
},
labels: {
items: [{
html: 'Total Attendane Summary',
style: {
    left: '50px',
    top: '18px',
    color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
}
}]
},
series: [{
type: 'column',
name: 'Present %',
data: [<?php echo $pers ?>]
},  {
type: 'pie',
name: 'Value : ',
data: [{
name: 'Absent (<?php echo (($toatl-$absenties)) ?>)',
y: <?php echo (($toatl-$absenties)/$toatl)*100 ?>,
color: Highcharts.getOptions().colors[2] // Jane's color
}, {
name: 'Present <?php echo $absenties  ?>',
y: <?php echo (($absenties)/$toatl)*100 ?> ,
color: Highcharts.getOptions().colors[0] // Joe's color
}],
center: [100, 80],
size: 100,
showInLegend: false,
dataLabels: {
enabled: false
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
                <?php
                }else{
                    echo "<br/><br/><hr/>";
                }
            }
            ?>
         <div class="box box-bordered box-color"  >
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>View Attendance Report Of <?php echo $this->input->get('att_date') ?>  </h3> 
                    <div class="actions"> 
                        <a class="btn " target="_blank" rel="tooltip" title="" data-original-title="Print Report" href=" <?php echo base_url(); ?>index.php/attendance/att_print_out/<?php echo str_replace("/", "-", $this->input->get('att_date')) ?>" ><i class="fa fa-print"></i></a>
                        <a class="btn " target="_blank" rel="tooltip" title="" data-original-title="Print Absenties Details" href=" <?php echo base_url(); ?>index.php/attendance/student_att_print_out/<?php echo str_replace("/", "-", $this->input->get('att_date')) ?>/student_list" ><i class="fa fa-user"></i></a>
                  
                    </div>
                    </div>
                    <div class="box-content nopadding" style=" max-height:350px; overflow: scroll;"> 
                        <table class="table table-hover table-nomargin table-bordered" style=" text-align: center" >
                            <thead>
                                <tr >
                                    <th style=" text-align: center">Class - section</th>
                                    <th style=" text-align: center">Total Students</th>
                                    <th style=" text-align: center">Morning</th>
                                    <th style=" text-align: center">Afternoon</th>
                                </tr>
                            </thead>
                            <tbody >
                         <?php
                          if($sections->num_rows()>0){
                    $q = $this->db->query("SELECT d.section ,d.day,d.slot,(SELECT count( DISTINCT(student)) FROM `attendance` a where a.date_id =d.id  ) as abs_count FROM `attendance_date` d  where d.day ='".$ti."'  GROUP BY d.section ,d.slot ORDER BY d.section ");
                    if($q->num_rows()>0){
                       $attendance_list = array();
                       $q = $q->result();
                        
                       foreach($q as $value){
                           $attendance_list[$value->section][ $value->slot ] = array("count"=>$value->abs_count);
                       }
                        $i=1;$total_str=$total_abs=0;
                       $sections = $sections->result();
                       foreach($sections as $value){
                           
                           if($value->total!=0){
                                ?>
                                <tr>
                                    <td><?php echo $value->cls_name." - ".$value->sec_name ?></td>
                                    <td><?php echo $value->total ;
                                     $total_str =$total_str+$value->total ;
                                      ?></td>
                                    <td style=" text-align: center">
                                        <?php 
                                         if(isset($attendance_list[$value->sid][1])){
                                             echo ($value->total-$attendance_list[$value->sid][1]["count"]);
                                             $per= (($value->total-$attendance_list[$value->sid][1]["count"])/$value->total)*100;
                                             echo "<br/>(".number_format($per,2).")";
                                         }else{
                                             echo "--";
                                         }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                         if(isset($attendance_list[$value->sid][2])){
                                             echo ($value->total-$attendance_list[$value->sid][2]["count"]);
                                             $per= (($value->total-$attendance_list[$value->sid][2]["count"])/$value->total)*100;
                                             echo "<br/>(".number_format($per,2).")";
                                         }else{
                                             echo "--";
                                         }
                                        ?>
                                    </td>
                                </tr>
                                                                                                   
                          <?php
                           }
                         
                       }
                   }else{
                                ?>
                                <tr>
                                    <td colspan="5"><span style=" color: red">** Please Add Attendance To view Report's</span></td>
                                </tr>
                       <?php
                       }
                }else{
                    ?>
                        <tr>
                            <td colspan="5"> <span style=" color: red;">No Class Structure Found..</span></td>
                        </tr>
               
                    <?php
                }   
                         ?>
                 </tbody>
                        </table> 
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
                   $student =$this->db->query( "SELECT s.* , c.name as cls_name , se.name as sec_name from student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id where s.student_id='".$student."' AND s.iid='".$this->session->userdata("staff_Org_id")."' ");
                   if($student->num_rows()==0){
                       ?><br/><br/>
                <span style=" color: red">** Invalid Student Selected</span>
                       <?php
                   }else{
                        $student = $student->row();
                        $dates_array=array();
                        $dates = $this->db->query( " SELECT * FROM attendance_date WHERE iid='".$this->session->userdata("staff_Org_id")."' and section='".$student->section_id."' ");
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
                                                    <a target="_blank" class="btn btn-primary" href="<?php echo base_url(); ?>index.php/attendance/student_brief_print/<?php echo $_GET['student'] ?>" ><i class="fa fa-print"></i>&nbsp;Print</a>
                                                </div>
                                </div>
                            <div class="box-content nopadding"  style=" min-height: 300px; max-height: 550px;" >
                                        <div class="tabs-container">
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
                                                                <h3 style=" text-align: center"><?php 
                                                                  echo "Attendance Report Of ".$val['month']."-".$key ;
                                                                  ?></h3>
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
                                                                <h4 style=" margin: 0px; text-align: center; padding-top: 8px; ">Total : <?php echo $total ?> Absent : <?php echo $present ?> Present : <?php echo $total-$present ?></h4>
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
                </div>
                
				
                            <?php
                        }else{
                          ?><br/><br/>
                <span style=" color: red">** Invalid Student Selected</span>
                       <?php  
                            
                        }
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