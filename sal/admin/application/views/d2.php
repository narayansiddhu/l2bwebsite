<?php
// New Theme implementation headers->css and js plugines
$this->load->view('structure/header');
$this->load->view('structure/js');
//--> New Theme implementation headers->css and js plugines
?>

<body>
<div id="wrapper">

    <?php
    // Navigation Links in right side panel
    $this->load->view('structure/body');
    //--> Navigation Links in right side panel


    ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <?php $this->load->view('structure/nav'); ?>
        </div>
        <div class="wrapper" style="padding: 20px 10px 10px; " >
            <div class="row">
                <div class="col-lg-3 wow fadeInDown" data-wow-delay="0.1s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Annual</span>
                            <h5>Students</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"> <i class="fa fa-user-graduate text-success"></i>
                                <?php
                                if(strlen($this->session->userdata("student_count"))==0){
                                    //staff_Org_id
                                    $query=$this->db->query("SELECT count(*) as total FROM `student` WHERE iid='".$this->session->userdata("staff_Org_id")."' ");
                                    $query=$query->row();
                                    echo $query->total;
                                    $this->session->set_userdata("student_count",$query->total);
                                }else{
                                    echo $this->session->userdata("student_count");
                                }
                                ?>
                            </h1>
                            <div style="padding-bottom: 5px;"></div>
                            <div class="stat-percent font-bold text-success">
                                <small>Incresed by</small>
                                <i class="fa fa-level-up-alt"></i>
                                98%
                            </div>
                            <small>Total Students</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 wow fadeInDown" data-wow-delay="0.1s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">Monthly</span>
                            <h5>Staff</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><i class="fa fa-user-tie text-info"></i>
                                <?php
                                if(strlen($this->session->userdata("tstaff_count")) == 0){
                                    //staff_Org_id
                                    $query=$this->db->query("SELECT count(*) as total  FROM `staff` WHERE iid='".$this->session->userdata("staff_Org_id")."' ");
                                    $query=$query->row();
                                    echo $query->total;
                                    $this->session->set_userdata("tstaff_count",$query->total);
                                }else{
                                    echo $this->session->userdata("tstaff_count");
                                }
                                ?>
                            </h1>
                            <div style="padding-bottom: 5px;"></div>
                            <div class="stat-percent font-bold text-info">
                                <small>Incresed by</small>
                                <i class="fa fa-level-up-alt"></i> 20%
                            </div>
                            <small>Total Staff</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 wow fadeInDown" data-wow-delay="0.1s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">Today</span>
                            <h5> Credits </h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1 class="no-margins">
                                        <?php
                                        $query=$this->db->query("SELECT * FROM `messaging` where  iid='".$this->session->userdata("staff_Org_id")."' ");
                                        $query=$query->row();
                                        $url="http://smslogin.mobi/spanelv2/api.php?username=".$query->username."&password=".$query->password;
                                        $t= file($url);
                                        if(is_numeric($t['0'])){
                                            echo $t['0'];
                                        }else{
                                            echo "0";
                                        }
                                        ?>
                                    </h1>
                                    <div style="padding-bottom: 5px;"></div>
                                    <div class="font-bold text-navy">
                                        <i class="fa fa-comment"></i>
                                        <small>SMS Credits</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h1 class="no-margins">206,12</h1>
                                    <div style="padding-bottom: 5px;"></div>
                                    <div class="font-bold text-navy">
                                        <i class="fa fa-phone fa-rotate-90"></i>
                                        <small>Voice Credits</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 wow fadeInDown" data-wow-delay="0.2s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">Inactive</span>
                            <h5>Parents</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins"><i class="fa fa-user-friends"></i>
                                <?php
                                if(strlen($this->session->userdata("parent_count"))==0){
                                    //staff_Org_id
                                    $query=$this->db->query("SELECT count(*) as total FROM `parent` WHERE iid='".$this->session->userdata("staff_Org_id")."' ");
                                    $query=$query->row();
                                    echo $query->total;
                                    $this->session->set_userdata("parent_count",$query->total);
                                }else{
                                    echo $this->session->userdata("parent_count");
                                }
                                ?>
                            </h1>
                            <div style="padding-bottom: 5px;"></div>
                            <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down-alt"></i></div>
                            <small>Registerd Parents</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-bottom white-bg dashboard-header wow fadeIn" data-wow-delay="0.2s">

            <div class="col-lg-4 wow fadeInLeft" data-wow-delay="0.8s">

                <?php
                $birthdays=array();
                $date = getdate();
                $date=$date['mday']."/".$date['mon'];
                $stud="SELECT * from student where bday='".$date."' AND iid='".$this->session->userdata("staff_Org_id")."'";
                $stud=$this->db->query($stud);
                $stud=$stud->result();
                $staff="SELECT * from staff  where bday='".$date."' AND iid='".$this->session->userdata("staff_Org_id")."'";
                $staff=$this->db->query($staff);
                $staff=$staff->result();
                foreach($stud as $val){
                    $birthdays[]=array("name"=>$val->name,'phone'=>$val->phone,"role"=>"student");
                }
                foreach($staff as $val){
                    $birthdays[]=array("name"=>$val->name,'phone'=>$val->phone,"role"=>"staff");
                }
                ?>

                <h2>Birthday Alerts
                    <?php if( sizeof($birthdays) > 0 ) { ?>
                        <a class="pull-right" onclick="load_template();" style="" href="#modal-1" role="button"  data-toggle="modal" rel="tooltip" title="" data-original-title="Send SMS " >
                            <i class="fa fa-send" style="font-size: 21px; padding-right:25px; "></i>
                        </a>
                    <?php } ?>
                </h2>

                <small>You have <?= sizeof($birthdays); ?> notifications.</small>

                <table class="table table-hover margin bottom table-responsive">

                    <tbody>
                    <?php
                    if(sizeof($birthdays) == 0){
                        ?><tr><td style=" color: red; text-align: center;" colspan="3">No Birthday Notifications Found..</td></tr><?php
                    }else{
                        $i=1;
                        foreach($birthdays as $val){
                            if($val['role'] == 'student') { $label_color = 'label-success'; }
                            if($val['role'] == 'staff') { $label_color = 'label-primary'; }
                            if($val['role'] == 'parent') { $label_color = 'label-warning-light'; }
                            ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td>
                                    <?php echo ucwords(strtolower(substr($val['name'],0,13).'...')); ?>
                                    <span class="label <?= $label_color; ?> "><?= $val['role']; ?></span>
                                </td>
                                <td> <i class="fa fa-phone fa-rotate-90"></i><?php echo '+91 '.$val['phone'] = 9014551553; ?> </span></td>
                            </tr>
                            <?php
                        }
                    }

                    ?>

                    </tbody>
                </table>

                <div class="modal inmodal fade" id="modal-1" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <i class="fa fa-birthday-cake modal-icon"></i>
                                <h4 class="modal-title" id="myModalLabel">Send Birthday Wishes </h4>
                                <small class="font-bold">This alerts are sent to all the contacts in Birthday Notification panel</small>
                            </div>
                            <div class="modal-body" id="modal_body">
                                <textarea name="bday_msg" id="bday_msg" rows="5" class="form-control" style=" resize: none" placeholder="Please enter Reason For rejection"></textarea>
                                <span id="reason_err" style="color: red"></span>
                                <br/>
                                <span style=" clear: both; color: #cc3300">For Name Use <#name#> In Message Content</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" onclick="send_bday_Alerts();" id="send_b_alerts" class="btn btn-primary">Send Wishes </button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    //load_template
                    function load_template(){
                        setState('bday_msg','<?php echo base_url() ?>index.php/Alerts/load_template/3','type=3');
                    }

                    function send_bday_Alerts(){
                        msg =$('#bday_msg').val();
                        setState('reason_err','<?php echo base_url() ?>index.php/Alerts/send_bday_alerts/','message='+msg);
                    }

                </script>

            </div>
            <div class="col-lg-6">
                <div class="flot-chart dashboard-chart">
                    <?php
                    $day = getdate();
                    $day=mktime(0,0,0,$day['mon'],$day['mday'],$day['year']);
                    ?>
                    <?php

                    $query = $this->db->query("SELECT distinct `time`, `amount` FROM `expenditure` WHERE `iid` = '".$this->session->userdata('staff_Org_id')."' AND `staff_id` = '".$this->session->userdata('staff_id')."' GROUP BY `time`  LIMIT 0,7");
                    $res =  $query->result();

                    foreach ($res as $key => $val) {
                        $exp['time'][] = date('M jS',$val->time);
                        $exp['amount'][] = $val->amount;
                    }

                    $pat = '';
                    for($z = 0; $z < sizeof($exp['time']); $z++ ){
                        $pat .= '['.$z.', "'.$exp['time'][$z].'"]';
                        if($z != sizeof($exp['time'])-1) {
                            $pat .= ',';
                        }
                    }
                    $graph_data1 = '';
                    for($y = 0; $y < sizeof($exp['amount']); $y++ ) {
                        $graph_data1 .= '['.$y.','.$exp['amount'][$y].']';
                        if($y != sizeof($exp['amount'])-1) {
                            $graph_data1 .= ',';
                        }
                    }

                    ?>
                    <div class="flot-chart-content  wow zoomIn" data-wow-delay="0.8s" id="flot-dashboard-chart">
                    </div>
                </div>
                <div class="row text-left">
                    <div class="col-xs-4">
                        <div class=" m-l-md">
                            <span class="h4 font-bold m-t block"><i class="fa fa-rupee-sign"></i> 406,100 /-</span>
                            <small class="text-muted m-b block">Total Income Report</small>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <span class="h4 font-bold m-t block"><i class="fa fa-rupee-sign"></i> 150,401 /-</span>
                        <small class="text-muted m-b block">Total Expenses Report</small>
                    </div>
                    <div class="col-xs-4">
                        <span class="h4 font-bold m-t block"><i class="fa fa-rupee-sign"></i> 16,822 /-</span>
                        <small class="text-muted m-b block"> Income Due</small>
                    </div>

                </div>
            </div>

            <div class="col-lg-2 wow fadeInRight" data-wow-delay="0.8s" style="padding-left: 10px; padding-right: 3px;">
                <h2 style="font-size: 22px; line-height: 30px;">Sticky Notes<img src="<?= base_url(); ?>../assests/img/loading.gif" style="width: 24px; height:24px; display: none; " id="loading">
                    <button class="btn btn-white pull-right" data-clipboard-target="#copytext" style="padding: 3px 5px 3px;"><i class="fa fa-copy"></i></button></h2>
                <div class="row text-center">
                    <?php
                    $query = $this->db->query("SELECT `notes` from `sticky_notes` where `staff_id`=".$this->session->userdata("staff_id")." AND `status` = 1");
                    $res_n =  $query->result();
                    ?>
                    <textarea title="Enter Some thing to save notes" class="form-control sticky-notes " id="copytext" rows="10" style="border-radius: 5px; border:1px solid #ccc; background-color: #f5f5f9ad;"><?= $res_n[0]->notes; ?></textarea>
                    <input type="hidden" value="<?= $this->session->userdata("staff_id"); ?>" id="staff_id">
                </div>
            </div>

        </div>

        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-8 wow bounceInUp" data-wow-delay="0.5s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-warning pull-right">Today</span>
                            <h3> User Attendance </h3>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">Total Users</small>
                                    <h2 class="font-bold"><i class="fa fa-user-friends"></i> <?= $this->session->userdata("tstaff_count")+ $this->session->userdata("student_count"); ?></h2>
                                </div>

                                <div class="col-xs-4">
                                    <small class="stats-label">Total Staff</small>
                                    <h2 class="font-bold"><i class="fa fa-user-shield"></i> <?= $this->session->userdata("tstaff_count"); ?></h2>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">Total Students</small>
                                    <h2 class="font-bold"><i class="fa fa-user-graduate"></i> <?= $this->session->userdata("student_count"); ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <div class="row">

                                <?php
                                $day = getdate();
                                $day=mktime(0,0,0,$day['mon'],$day['mday'],$day['year']);
                                ?>
                                <?php

                                $staff_att="SELECT id FROM `staff_attendance_date` where iid='".$this->session->userdata("staff_Org_id")."' and day='".$day."'";
                                $staff_att= $this->db->query($staff_att);
                                if($staff_att->num_rows()>0) {
                                    $staff_att = $staff_att->row();
                                    $staff_att = "SELECT `staff_attendance`.status, count(*) as count FROM `staff_attendance` join `staff` where date_id='" . $staff_att->id . "' and `staff`.id = `staff_attendance`.staff and `staff`.status = '1' GROUP BY `staff_attendance`.status";


                                    $staff_att = $this->db->query($staff_att);
                                    $staff_att = $staff_att->result();
                                    $stff_present = 0;
                                    $stff_absent = 0;
                                    foreach ($staff_att as $val) {
                                        if ($val->status == 1) {
                                            $stff_present = $val->count;
                                        } else {
                                            $stff_absent = $val->count;
                                        }
                                    }

                                    $stff_present = (int)$stff_present;
                                    $stff_absent = (int)$stff_absent;
                                    $total_staff = ($stff_present + $stff_absent);

                                    if ($stff_present != 0) {
                                        $stff_present_per = ($stff_present / $total_staff) * 100;
                                    }
                                    if ($stff_absent != 0) {
                                        $stff_absent_per = ($stff_absent / $total_staff) * 100;
                                    }
                                    ?>


                                <?php } else { $staff_att= $staff_att->result();  } ?>
                                <div class="col-xs-4">
                                    <small class="stats-label">Active Staff</small>
                                    <h2 class="font-bold text-success"><i class="fa fa-user-shield"></i> <?php if(!empty($total_staff)) echo $total_staff; else echo '-NA-'; ?></h2>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">% Present</small>
                                    <h2 class="font-bold" style="color: #2ba52b;"><?php if(!empty($stff_present_per)) echo round($stff_present_per, 2).' %'; else echo '-NA-'; ?> </h2>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">% Absent</small>
                                    <h2 class="font-bold text-danger"><?php if(!empty($stff_absent_per)) echo round($stff_absent_per, 2).' %'; else echo '-NA-'; ?> </h2>
                                </div>
                            </div>
                        </div>


                        <div class="ibox-content">
                            <div class="row">
                                <?php

                                $student_Att ="SELECT group_concat(id) as ids  from attendance_date where iid='".$this->session->userdata("staff_Org_id")."' and day='".$day."' ";
                                $student_Att = $this->db->query($student_Att);
                                $student_Att = $student_Att->row();
                                $student_Att = $student_Att->ids;
                                if(strlen($student_Att)!=0){
                                    $stud_abs ="SELECT count(*) as absenties from attendance where date_id in (".$student_Att.")";
                                    $stud_abs = $this->db->query($stud_abs);
                                    $stud_abs = $stud_abs->row();
                                    $stud_abs = $stud_abs->absenties;
                                    $present = (($this->session->userdata("student_count")-$stud_abs)/$this->session->userdata("student_count"))*100;
                                    $abs = 100-$present;
                                    ?>


                                <?php } ?>
                                <div class="col-xs-4">
                                    <small class="stats-label">Active Students</small>
                                    <h2 class="font-bold text-success"><i class="fa fa-user-graduate"></i> <?= $this->session->userdata("student_count"); ?></h2>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">% Present</small>
                                    <h2 class="font-bold font-bold" style="color: #2ba52b;"><?php if(!empty($present)) echo round($present, 2).' %'; else echo '-NA-'; ?> </h2>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">% Absent</small>
                                    <h2 class="font-bold text-danger"><?php if(!empty($abs)) echo round($abs, 2).' %'; else echo '-NA-'; ?> </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInLeft" data-wow-delay="0.5s">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">Today</span>
                            <h3>
                                Attendance Statistics
                            </h3>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <p style="padding: 0px 15px 0px ;">
                                    Today user Attendance Statistics (<?= date("d M Y"); ?>)
                                </p>
                                <div class="row text-center">
                                    <?php if(!isset($total_staff)){ ?>
                                        <div class="col-md-12">
                                            <div class="alert alert-danger alert-dismissable" id="error" style="margin: 15px;">
                                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                No Data Found <a class="alert-link" href="#">Add Attendance</a>.
                                            </div>
                                        </div>
                                    <?php } else{ ?>

                                        <div class="col-md-6">
                                            <canvas id="doughnutChart2" width="100" height="100" style="margin: 18px auto 0"></canvas>
                                            <h5><i class="fa fa-user-shield"></i> Staff [<?php if(isset($total_staff)) echo $total_staff; ?> ] </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <canvas id="doughnutChart" width="100" height="100" style="margin: 18px auto 0"></canvas>
                                            <h5><i class="fa fa-user-graduate"></i> Students [<?= $this->session->userdata("student_count"); ?>] </h5>
                                        </div>
                                    <?php  } ?>
                                </div>
                                <div class="m-t">
                                    <p class="no-margins" style="padding:5px 15px 0px;">Some of the classes cannot be get attendances. Some of the parents can't reached the message service.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content">

            <div class="small-chat-box fadeInRight animated">

                <div class="heading" draggable="true">
                    <small class="chat-date pull-right">
                        27 Jun 2018
                    </small>
                    Support Chat
                </div>

                <div class="content">

                    <div class="left">
                        <div class="author-name">
                            Monica Jackson <small class="chat-date">
                                10:02 am
                            </small>
                        </div>
                        <div class="chat-message active">
                            Lorem Ipsum is simply dummy text input.
                        </div>

                    </div>
                    <div class="right">
                        <div class="author-name">
                            Mick Smith
                            <small class="chat-date">
                                11:24 am
                            </small>
                        </div>
                        <div class="chat-message">
                            Lorem Ipsum is simpl.
                        </div>
                    </div>
                    <div class="left">
                        <div class="author-name">
                            Alice Novak
                            <small class="chat-date">
                                08:45 pm
                            </small>
                        </div>
                        <div class="chat-message active">
                            Check this stock char.
                        </div>
                    </div>
                    <div class="right">
                        <div class="author-name">
                            Anna Lamson
                            <small class="chat-date">
                                11:24 am
                            </small>
                        </div>
                        <div class="chat-message">
                            The standard chunk of Lorem Ipsum
                        </div>
                    </div>
                    <div class="left">
                        <div class="author-name">
                            Mick Lane
                            <small class="chat-date">
                                08:45 pm
                            </small>
                        </div>
                        <div class="chat-message active">
                            I belive that. Lorem Ipsum is simply dummy text.
                        </div>
                    </div>


                </div>
                <div class="form-chat">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control">
                        <span class="input-group-btn"> <button
                                    class="btn btn-primary" type="button">Send
                </button> </span></div>
                </div>

            </div>
            <div id="small-chat">

                <span class="badge badge-warning pull-right">5</span>
                <a class="open-small-chat">
                    <i class="fa fa-comments"></i>

                </a>
            </div>

        </div>
        <?php   $this->load->view('structure/footer'); ?>
    </div>
</div>

</body>
<!-- Page-Level Scripts Graph Scripts-->
<script>
    $(document).ready(function() {

        // Sweet alert

        setTimeout(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000
            };
            toastr.success('<?= strtoupper($this->session->userdata("inst_name")); ?>', 'Welcome to  <?= $this->session->userdata('staff_name'); ?>');

        }, 1300);

        var data1 = [
            <?= $graph_data1; ?>
        ];

        $("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
                data1
            ],
            {
                series: {
                    lines: {
                        show: false,
                        fill: true
                    },
                    splines: {
                        show: true,
                        tension: 0.4,
                        lineWidth: 1,
                        fill: 0.4
                    },
                    points: {
                        radius: 1,
                        label: true,
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#d5d5d5",
                    borderWidth: 1,
                    color: '#d5d5d5'
                },
                colors: ["#1ab394", "#1C84C6"],
                xaxis: {
                    ticks: [<?= $pat; ?>]
                },
                yaxis: {
                    ticks: 4
                },
                tooltip: true,
                tooltipOpts: {
                    content: "x: %x, y: %y"
                }
            }
        );

    });
</script>

<script>

    $(document).ready(function () {

        new Clipboard('.btn');

        $('.sticky-notes').on('keyup keypress blur change', function() {
            var dInput = this.value;
            var staff_id = $('#staff_id').val();
            // console.log(dInput);
            $.ajax({
                type: 'POST',
                url: 'Dashboard/notes/sticky_notes',
                data: {
                    staff_id: staff_id,
                    sticky_notes: dInput
                },
                beforeSend: function () {
                    $("#loading").show();
                },
                complete: function () {
                    $("#loading").hide();
                },
                success: function (response) {
                    response = JSON.parse(response);
                    console.log(response);
                    document.getElementById("staff_id").style.color = 'green';
                }
            });
        });

        <?php if(isset($stff_present) && isset($stud_abs)) { ?>
        var doughnutData = {
            labels: ["Present","Absent"],
            datasets: [{
                data: [<?= ($this->session->userdata("student_count")-$stud_abs); ?> ,<?= $stud_abs; ?>],
                backgroundColor: ["#a3e1d4","#dedede"]
            }]
        } ;


        var doughnutOptions = {
            responsive: false,
            legend: {
                display: false
            }
        };


        var ctx4 = document.getElementById("doughnutChart").getContext("2d");
        new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});

        var doughnutData = {
            labels: ["Present","Absent" ],
            datasets: [{
                data: [<?= $stff_present; ?>, <?= $stff_absent; ?>],
                backgroundColor: ["#a3e1d4","#dedede"]
            }]
        } ;


        var doughnutOptions = {
            responsive: false,
            legend: {
                display: false
            }
        };


        var ctx4 = document.getElementById("doughnutChart2").getContext("2d");
        new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});
        <?php } ?>

    });

</script>

</html>
