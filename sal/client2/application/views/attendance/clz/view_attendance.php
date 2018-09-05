<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$total_stud=$this->db->query("select count(*) as total from student where section_id= '".$section->sid."' ")->row();
$total_stud =$total_stud->total;
$config_array = array();
$configs=$this->db->query("SELECT * from attendance_config where asid ='".$att_settings->aid."' ")->result();
foreach ($configs as $value) {
  $config_array[$value->acid] = array("time"=>$value->time);  
}
$att=$this->db->query("SELECT d.*, (select count(*) from attendance a JOIN student s ON a.student=s.student_id where s.section_id=d.section AND a.date_id=d.id ) as absenties FROM `attendance_date`  d where d.section ='".$section->sid."' ORDER BY  d.day ASC");
$att =$att->result();
$att_array=array();
foreach ($att as $value) {
     $d=  getdate($value->day);
     $d=$d['month']."-".$d['year'];
     $att_array[$d][$value->day][$value->slot]=$value->absenties;
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
                    <a href="<?php echo base_url(); ?>index.php/Clzattendance/">Attendance</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Clzattendance/view/">View Attendance</a><i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Clzattendance/view/"><?php echo $section->class." - ".$section->section ?> Attendance </a>
                </li>
            </ul>
    </div> 
            <div class="box">
                 <div class="col-sm-4 nopadding">
          <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3><i class="fa fa-info-circle"></i>&nbsp;Section Info</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Class</td>
                            <td><?php  echo $section->class ?></td>
                        </tr>
                        <tr>
                            <td>Section</td>
                            <td><?php  echo $section->section ?></td>
                        </tr>
                        
                        <tr>
                            <td>Students</td>
                            <td><?php echo $total_stud ?></td>
                        </tr>
                        <tr>
                            <td>Attendance Slots</td>
                            <td><?php  echo $att_settings->no_of_times ?></td>
                        </tr>
                    </table>
                </div>
          </div>
      </div>  
                <div class="col-sm-8 nopadding" >
                    <div class="box box-bordered box-color" style=" padding-left: 10px;" >
                        <div class="box-title">
                            <h3><i class="fa fa-check"></i>&nbsp;Attendance Slots Info</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <table style=" text-align: center" class="table table-bordered table-striped">
                                <tr>
                                    <?php
                                    $i=1;
                                    foreach ($config_array as $value) {
                                        ?>
                                    <td>Slot - <?php echo $i++ ?> <hr/>
                                        <?php echo substr($value['time'],0,strlen($value['time'])-2).":".substr($value['time'],strlen($value['time'])-2) ?>
                                   </td>
                                         <?php
                                    }
                                    ?>
                                </tr> 
                            </table>
                        </div>
                     </div>
                    <br/>
                </div>
            </div>
            <hr style=" clear: both"/>
            <div class="box box-bordered box-color ">
							<div class="box-title">
								<h3>
									<i class="fa fa-bars"></i>Attendance Reports</h3>
							</div>
							<div class="box-content nopadding">
								<div class="tabs-container">
									<ul class="tabs tabs-inline tabs-left">
                                                                            <?php
                                                                            $i=1;
                                                                                foreach ($att_array as $key=>$value) {
                                                                                  ?>
                                                                            <li  <?php 
                                                                              if($i==1){
                                                                                  echo "class='active'";
                                                                              }
                                                                              $i++;
                                                                            ?> >
											<a href="#<?php echo $key ?>" data-toggle='tab'>
                                                                                            <i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;	<?php echo $key ?></a>
										</li> 										</li>                                  

                                                                                      <?php                                  
                                                                                }
                                                                            ?>
										
									</ul>
								</div>
								<div class="tab-content  tab-content-inline">
                                                                    <script src="https://code.highcharts.com/highcharts.js"></script>
                                                                    <script src="https://code.highcharts.com/modules/exporting.js"></script>

                                                                    <?php
                                                                            $i=1;
                                                                                foreach ($att_array as $key=>$value) {
                                                                                  ?>
                                                                            <div class="tab-pane <?php 
                                                                              if($i==1){
                                                                                  echo "active";
                                                                              }
                                                                              ?>" id="<?php echo $key ?>"  >
									     
                                                                                <div id="container_<?php echo $i ?>" style=" width: 1100px; height: 400px; margin: 0 auto"></div>

<hr/>
                <table class="table table-bordered  datatable" style="padding: none ; text-align: center">
                    <thead>
                    <tr>
                        <th style=" text-align: center">Date</th>
                        <?php
                         foreach ($config_array as $con) {
                             ?><th style=" text-align: center">
                                   <?php echo substr($con['time'],0,strlen($con['time'])-2).":".substr($con['time'],strlen($con['time'])-2) ?>
                        </th>       
                             <?php                                                                                 
                          }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $x_date="";$y_per="";$in_count=1;$avg=0;
                    foreach ($value as $d=>$date) {
                        $x_date.="'".date("d-m-y",$d)."',";
                       // print_r($date);
                        $avg=0;$in_count=0;
                       ?>
                    <tr>
                        <th style=" text-align: center"><?php echo date("d-m-y",$d) ?></th>    
                        <?php
                         foreach ($config_array as $con=>$cf) {
                             ?><td>
                                 <?php 
                                   if(isset($date[$con])){
                                      echo number_format(((($total_stud-$date[$con])/$total_stud)*100),2);
                                      $avg = ((($total_stud-$date[$con])/$total_stud)*100)+$avg;
                                      $in_count++;
                                   }else{
                                      echo "--";
                                   }
                                 ?>
                              </td>
                           <?php
                         }
                         $y_per.=number_format(($avg/$in_count),2).",";
                        ?>
                    </tr>
                       <?php
                    }
                    $y_per= substr($y_per, 0, strlen($y_per)-1);
                    $x_date=substr($x_date, 0, strlen($x_date)-1);
                    ?>
                    </tbody>
                </table>
                                                                                <script type="text/javascript">

Highcharts.chart('container_<?php echo $i ?>', {
    chart: {
        type: 'line'
    },
    title: {
        text: ' Attendance Report of <?php echo $key ?>'
    },
    xAxis: {
        categories: [<?php echo $x_date ?>]
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
        data: [<?php echo $y_per ?>]
    }]
});
		</script>
                
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
</div>
<?php
$this->load->view('structure/footer');
?>
