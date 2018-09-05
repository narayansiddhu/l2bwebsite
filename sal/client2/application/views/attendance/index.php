<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
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
                            <a href="">View Attendance </a>
                        </li>
                    </ul>
                </div> 
            
            <div class="box"><br/>
                <div class="col-sm-6 nopadding " style="border-right: 2px solid #318EE0">
                    <div class="col-sm-6">
                        <h3 style=" text-align: center; color:  #ff6633">Staff Attendance</h3>
                    </div>
                    <div class="col-sm-6" >

                            <a class="new_title1" style=" float: right" href="<?php echo base_url(); ?>index.php/staff/attendance"  style=" text-decoration: none !important;" >
                                <i class="fa fa-plus fa-2x"></i>
                                <p>Add</p>
                            </a>
                            <a class="new_title1" style=" float: right" href="<?php echo base_url(); ?>index.php/staff/Monthly_attendance"  style=" text-decoration: none !important;" >
                                <i class="fa fa-file-text-o fa-2x"></i>
                                <p>Reports</p>
                            </a>

                    </div><br/>
                    <?php
                      $staff_att_qur="select d.id,d.day,count(*) as counter from staff_attendance_date d JOIN staff_attendance a ON a.date_id=d.id where d.iid='".$this->session->userdata('staff_Org_id')."' AND a.status=1 GROUP BY d.id ORDER BY d. day DESC LIMIT 0,5 ";
                      $staff_att_qur =$this->db->query($staff_att_qur);
                      $staff=$this->db->query("select count(*) as total from staff where iid='".$this->session->userdata('staff_Org_id')."'")->row();
                      $staff =$staff->total;
                      if($staff_att_qur->num_rows()==0){
                           ?>
                    <h6 style=" color: red; width: 100%; text-align: center">** No Attendance Records Found..</h6>
                         <?php
                      }else{
                          $dates="";$avg="";
                          $staff_att_qur =$staff_att_qur->result();
                          foreach ($staff_att_qur as $value) {

                             $dates.= "'".date('d-m-y',$value->day)."',";
                             $avg.= round(($value->counter/$staff)*100).",";
                          }
                           $dates = substr($dates, 0, strlen($dates)-1);
                          $avg = substr($avg, 0, strlen($avg)-1);
                          ?>

                    <script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: ' Staff Attendance'
        },
        subtitle: {
            text: ''
        },
        xAxs: {
            categories: [
                <?php echo $dates ?>
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Attendance In Percentage ( % )'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key} <br/>',
            pointFormat: '{series.name} Att : ' +
                '{point.y:.1f} %',
            footerFormat: '</span>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Staff',
            data: [<?php echo $avg ?>]

        }]
    });
});
		</script>
                <div id="container" style="width: 500px; height:350px; margin: 0 auto"></div>

                         <?php
                      }
                     ?>
                </div>
                <div class="col-sm-6 nopadding" style=" padding-left: 10px;" >
                    <div class="col-sm-6">
                        <h3 style=" text-align: center; color:  #ff6633">Student Attendance</h3>
                    </div>
                    <div class="col-sm-6" >
                            <a class="new_title1" style=" float: right" href="<?php echo base_url(); ?>index.php/attendance/add_attendance"  style=" text-decoration: none !important;" >
                                <i class="fa fa-plus fa-2x"></i>
                                <p>Add</p>
                            </a>
                            <a class="new_title1" style=" float: right" href="<?php echo base_url(); ?>index.php/attendance/view"  style=" text-decoration: none !important;" >
                                <i class="fa fa-file-text-o fa-2x"></i>
                                <p>Reports</p>
                            </a>
                    </div><br/>

                    <?php

                    $srtud_ar="select d.day,d.id,count(a.id) as counter from attendance_date d LEFT JOIN attendance a ON a.date_id=d.id  where d.iid='".$this->session->userdata("staff_Org_id")."'  GROUP BY d.day  ORDER BY d.day DESC LIMIT 0,5 ";
                    $srtud_ar = $this->db->query($srtud_ar);
                    if($srtud_ar->num_rows()==0){
                         ?>
                    <h6 style=" color: red; width: 100%; text-align: center">** No Attendance Records Found..</h6>
                         <?php
                    }else{
                         $dates="";$avg="";
                         $stud=$this->db->query("SELECT count(*) as total from student where iid='".$this->session->userdata("staff_Org_id")."' ")->row();
                         $stud =$stud->total;
                         $srtud_ar =$srtud_ar->result();
                          foreach ($srtud_ar as $value) {
                             $dates.= "'".date('d-m-y',$value->day)."',";
                             $avg.= round((($stud-$value->counter)/$stud)*100).",";
                          }
                           $dates = substr($dates, 0, strlen($dates)-1);
                          $avg = substr($avg, 0, strlen($avg)-1);
                          ?>

                    <script type="text/javascript">
$(function () {
    Highcharts.chart('studcontainer', {
        chart: {
            type: 'column'
        },
        title: {
            text: ' Student Attendance'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
                <?php echo $dates ?>
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Attendance In Percentage ( % )'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key} <br/>',
            pointFormat: '{series.name} Att : ' +
                '{point.y:.1f} %',
            footerFormat: '</span>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Students',
            data: [<?php echo $avg ?>]

        }]
    });
});
		</script>
                <div id="studcontainer" style="width: 500px; height:350px; margin: 0 auto"></div>

                         <?php
                    }
                    ?>

            </div>
                <script>
                    $( document ).ready(function() {
                        $('.highcharts-credits').css('display','none');
                    });

                </script>
            <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

        </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>