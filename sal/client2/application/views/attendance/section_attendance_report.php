<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=getdate();
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
$s_m=$month."-".$year;
?>
<style>
    
.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

    .tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }

    </style>
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
                            <a href="<?php echo base_url(); ?>index.php/Attendance/view">Attendance Reports</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        
                        <li>
                            <a href="">Attendance Reports</a>
                        </li>
                    </ul>
                </div>
            <div class="box">
                    
                    <div class="box-title">
                        <h3><?php echo strtoupper( $section_data->class ." - ".$section_data->section);  ?> Attendance Report</h3> 
                        <div class="actions">
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
                    <select style=" width:80%; float: left; "  class="select2-me" name="month" id="month">
                        <?php
                        while($start<$now){
                            $start=  getdate($start);
                            ?><option value="<?php echo $start['mon']."-".$start['year']; ?>"
                                    <?php
                                     if($s_m==$start['mon']."-".$start['year']){
                                       echo " SELECTED='' ";  
                                     }
                                    ?>><?php echo $start['month']." - ".$start['year']; ?></option><?php
                            $start =  mktime(0, 0, 0, $start['mon']+1, 1,$start['year']);
                        }
                        ?>
                    </select>  
                            <button class="btn" onclick="fetch_attendance1();" style=" background-color :  #0066cc; color: white; width:20%; height: 30px; float: left; " ><i class="fa fa-search"></i></button>
                            <script>
                            function fetch_attendance1(){
                                month=$('#month').val();
                                month=month.split("-");
                                location.replace("<?php echo base_url() ?>index.php/attendance/attendance_report/<?php echo $section_data->sid ?>?month="+month[0]+"&year="+month[1]+"&submit=Fetch+Attendance");
                             }
                            </script>
                        </div>
                    </div>
                   
            </div>
              <?php
                $att_month =$month;
              $att_year =$year;
             $section =$section_data->sid;
              $attendance_array = array();
              $q="SELECT group_concat(a.student) as students, ad.day, ad.slot FROM `attendance` a JOIN attendance_date ad ON a.date_id = ad.id  where ad.section = '".$section."' AND ( ad.day >='".mktime(0, 0, 0, $att_month, 1, $att_year)."' AND ad.day <'".mktime(0, 0, 0, ($att_month+1), 1, $att_year)."' ) GROUP BY ad.slot , ad.day ORDER BY ad.day ASC";
              $q=  $this->db->query($q);
              $q = $q->result();
              foreach($q as $value){
                  $students = explode(",",$value->students);
                  $students= array_filter($students);
                  foreach($students as $stud){
                      $attendance_array[$value->day][$value->slot][$stud] = $stud;
                  }
              }
              $stud = $this->db->query("SELECT * FROM student WHERE section_id = '".$section."' ");
              $stud = $stud->result();
              $k= $from =mktime(0, 0, 0, $att_month, 1, $att_year);
              $to =mktime(0, 0, 0, $att_month +1 , 1, $att_year);
              $k = getdate($k);
              $stud_array=array();
              $no_of_stud=  sizeof($stud);
              foreach ($stud as $value) {
    $stud_array[$value->student_id]= array("name"=>$value->name,"roll"=>$value->roll,"userid"=>$value->userid,"admission_no"=>$value->admission_no);
                }
              ?>
            <div class="box box-bordered ">
                <div class="box-content nopadding">
                        <ul class="tabs tabs-inline tabs-top">
                                <li class='active'>
                                        <a href="#first11" data-toggle='tab'>Day-Wise Summary</a>
                                </li>
                                <li>
                                        <a href="#thirds3322" data-toggle='tab'><i class="fa fa-tag"></i>Brief Summary</a>
                                </li>
                               
                        </ul>
                    <hr style=" clear: both"/>
                        <div class="tab-content nopadding tab-content-inline tab-content-bottom">
                            <div class="tab-pane active nopadding" id="first11">
                                <div class="box nopadding">
                                    <div class="col-sm-6 nopadding " style=" border-right: 1px solid #ccc">
                                        <button  class="btn" style=" float: right; margin-top: 10px; height: 25px; margin-right: 8px; color: white; background-color: #386ee0 " onclick="Print_table('days_list');"><i class="fa fa-print"></i></button>
                                        <div  id="days_list" class="box" >
                                            <table align="left" class="tab_td1 datatable" border="0" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;">Date</th>
                                                    <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;">Morning Slot</th>
                                                    <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;">Afternoon Slot</th>
                                                </tr>
                                                <?php
                                                 foreach ($attendance_array as $this_day => $value) {    
                                                   ?>
                                                   <tr>
                                                       <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"> 
                                                           <a href="#day_<?php echo $this_day ?>" data-toggle="tab">
                                                            <?php echo date("d-m-Y",$this_day) ?>
                                                            </a>
                                                       </td>
                                                           <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;">
                                                                <?php 
                                                       if(isset($attendance_array[$this_day]['1'])){
                                                           echo number_format(($no_of_stud-sizeof($attendance_array[$this_day]['1']))/$no_of_stud *100 ,2);
                                                         echo  "%&nbsp;&nbsp;&nbsp; ( ". sizeof($attendance_array[$this_day]['1'])." ) ";
                                                       }else{
                                                           echo "--";
                                                       }
                                                       ?></td>
                                                       <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"><?php 
                                                       if(isset($attendance_array[$this_day]['2'])){
                                                           echo number_format(($no_of_stud-sizeof($attendance_array[$this_day]['2']))/$no_of_stud *100 ,2);
                                                         echo  "%&nbsp;&nbsp;&nbsp; ( ". sizeof($attendance_array[$this_day]['2'])." ) ";
                                                       }else{
                                                           echo "--";
                                                       }
                                                       ?></td>
                                                   </tr>
                                                   <?php
                                                  }
                                                ?>
                                        </table>
                                        </div>   
                                        
                                    </div>
                                    <div class="col-sm-6 nopadding" style=" max-height: 650px; overflow-y :scroll">
                             <script src="<?php echo assets_path  ?>graphs/code/highcharts.js"></script>
<script src="<?php echo assets_path  ?>graphs/code//modules/exporting.js"></script>
<script src="<?php echo assets_path  ?>graphs/code//highcharts-3d.js"></script>
                                        <div class="tab-content">
                                        
                                                <?php
                                                $i=1;
                                                foreach ($attendance_array as $this_day => $value) {    
                                                   ?>
                                        <div class="tab-pane <?php 
                                        if($i==1){
                                            echo 'active';
                                        }
                                        ?>" style=" clear: both;" id="day_<?php echo $this_day ?>">
                                            <div class="box">
                                                <h4 style=" float: left; text-align: center; width: 90%;"><?php echo date("d-m-Y",$this_day) ?> Attendance Report</h4> 
                                                <button class="btn" style=" float: right;color: white; background-color: #386ee0  " onclick="PrintElem('day_print_<?php echo $this_day ?>','<?php echo date("d-m-Y",$this_day); ?>');"><i class="fa fa-print"></i></button>
                                            </div>
                                            <div class="box" id="day_print_<?php echo $this_day ?>">
                                            
                                        <?php 
                                        $morning=$after_noon="--";
                                        if(isset($attendance_array[$this_day]['1'])){
                                        $morning = sizeof($attendance_array[$this_day]['1']);
                                        }
                                        if(isset($attendance_array[$this_day]['2'])){
                                        $after_noon = sizeof($attendance_array[$this_day]['2']);
                                        }
                                        $graphs="";
                                        if($morning!="--"){
                                            
                                            $graphs.="['Morninng', ".number_format(($no_of_stud-sizeof($attendance_array[$this_day]['1']))/$no_of_stud *100 ,2)."],";
                                        }
                                        if($after_noon!="--"){
                                            $graphs.="['Afternoon', ".number_format(($no_of_stud-sizeof($attendance_array[$this_day]['2']))/$no_of_stud *100 ,2)."],";
                                        }
                                        ?>
                                            
                                            <div id="day_graph_<?php echo $this_day ?>" style="width: 500px; height: 450px; margin: 0 auto"></div>
		<script type="text/javascript">

Highcharts.chart('day_graph_<?php echo $this_day ?>', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Attendance Report'
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
            text: 'Attendance Percentage'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Attendance Percentage<b>{point.y:.1f} %</b>'
    },
    series: [{
        name: 'Population',
        data: [
            <?php   echo $graphs; ?>
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '8px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
		</script>
                                        
                                        <?php
                                        if($morning!="--"){
                                            ?><br/><br/><br/>
                                                 <h4 style=" clear: both; color: red; text-align: center">Morning Slot Absentee List</h4>
                                                <table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                                  <tr>
                                                      <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;">Roll No</th>
                                                      <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;" >Student Name</th>
                                                 </tr>
                                                         <?php
                                                            foreach ($attendance_array[$this_day]['1'] as $s=>$value) {
                                                                ?>
                                                         <tr>
                                                             <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"><?php echo ($stud_array[$s]['roll']); ?></td>
                                                             <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"><?php echo ($stud_array[$s]['name']); ?></td>
                                                         </tr>
                                                                <?php                                         
                                                            }
                                                         ?>
                                                 </table>
                                                     <?php
                                            
                                            
                                        }else{
                                            ?><h4 style=" color: red; text-align: center">Morning Slot Attendance Not Added</h4><?php
                                        }
                                        if($after_noon!="--"){
                                        
                                            ?>
                                                <h4 style=" color: red; text-align: center">Afternoon Slot Absentee List</h4>
                                                 <table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                                                  <tr>
                                                      <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;">Roll No</th>
                                                             <th style="  text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3;" >Student Name</th>
                                                         </tr>
                                                         <?php
                                                            foreach ($attendance_array[$this_day]['2'] as $s=>$value) {
                                                                ?>
                                                         <tr>
                                                             <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"><?php echo ($stud_array[$s]['roll']); ?></td>
                                                             <td style="text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top;"><?php echo ($stud_array[$s]['name']); ?></td>
                                                         </tr>
                                                                <?php                                         
                                                            }
                                                         ?>
                                                 </table>
                                            <?php
                                        }else{
                                            ?><h4 style=" color: red; text-align: center">Afternoon Slot Attendance Not Added</h4><?php
                                        }
                                        ?>
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
                             
                             
                                         
                             ?>
                             
                            </div>
                            <div class="tab-pane" id="second22">
                            7778888888 
                            </div>
                            <div class="tab-pane nopadding" id="thirds3322" style=" width: 100%; overflow: scroll" >
                                
                            <table class="table table-hover datatable table-nomargin table-bordered">
                                 <thead>
                                     <tr>
                                         <th>Students</th>
                                         <?php
                                         
                                         foreach ($attendance_array as $this_day => $value) {    
                                               ?>
                                                <th colspan="2">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <th colspan="2" style=" text-align: center"><?php echo date("d-m-y",$this_day); ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th style=" text-align: center">Slot-1</th>
                                                            <th style=" text-align: center">Slot-2</th>
                                                        </tr>
                                                    </table>
                                                </th>
                                                
                                               <?php
                                           }
                                         ?>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php
                                       foreach($stud as $value ){
                                           ?>
                                            <tr>
                                                <td><?php echo $value->name ?></td>
                                                <?php
                                                      foreach ($attendance_array as $this_day => $vr) { 
                                                          ?>
                                                            <td style=" text-align: center"><?php
                                                              if(isset($attendance_array[$this_day][1][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td> 
                                                            <td style=" text-align: center"><?php
                                                              if(isset($attendance_array[$this_day][2][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td> 
                                                          <?php
                                                          $this_day =$this_day+86400;
                                                        }
                                                    ?>
                                            </tr>
                                          <?php
                                       }
                                     ?>
                                 </tbody>
                             </table>
                                </div>
                        </div>
                </div>
        </div>

        </div>
    
    
    </div>    
</div>
<script>
   function fetch_attendance(){
      att_month = $('#att_month').val();
      att_year = $('#att_year').val();
      setState('results_holder','<?php echo base_url() ?>index.php/attendance/load_att_report','att_month='+att_month+'&att_year='+att_year+'&section=<?php echo $section_data->sid  ?>');
                 
   }    
   //Print_table
   
   function PrintElem(elem,date)
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');

        mywindow.document.write('<html><head><title>' + document.title  + '</title>');                
        mywindow.document.write('</head><body style="border:1px double black;" ><div style="width:780px;margin-left:auto;padding:5px; margin-right: auto;  ">');
        mywindow.document.write('<h4 style=" color: teal; text-align: center" >Attendance Report Of <?php echo $section_data->class ." - ".$section_data->section ?> On '+date+' </h4>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</div></body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
   function Print_table(elem)
    {
        var mywindow = window.open('', 'PRINT', 'height=400,width=600');

        mywindow.document.write('<html><head><title>' + document.title  + '</title>');                
        mywindow.document.write('</head><body style="border:1px double black;" ><div style="width:780px;margin-left:auto;padding:5px; margin-right: auto;  ">');
        mywindow.document.write('<h4 style=" color: teal; text-align: center" >Attendance Report Of <?php echo $section_data->class ." - ".$section_data->section ?> </h4>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</div></body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
   
</script>

    
<?php
$this->load->view('structure/footer');
?>