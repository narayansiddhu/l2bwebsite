<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
    <script type="text/javascript" src="<?php echo assets_path ?>highcharts/js/jquery.min.js"></script>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">

                <div class="box ">
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  orange">
                            <i class="fa fa-child fa-3x"></i>
                        </div>
                        <div class="rigth_part" >
                            <strong style=" font-size:  inherit;">&nbsp;&nbsp;
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
                            </strong><br/>
                            <p>Total Students</p>
                        </div>
                    </div>

                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #006699">
                            <i class="fa fa-user fa-3x" ></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  inherit;">&nbsp;&nbsp;
                                <?php
                                if(strlen($this->session->userdata("tstaff_count"))==0){
                                    //staff_Org_id
                                    $query=$this->db->query("SELECT count(*) as total  FROM `staff` WHERE iid='".$this->session->userdata("staff_Org_id")."' ");
                                    $query=$query->row();
                                    echo $query->total;
                                    $this->session->set_userdata("tstaff_count",$query->total);
                                }else{
                                    echo $this->session->userdata("tstaff_count");
                                }
                                ?>
                            </strong><br/>
                            <p>Total Staff</p>
                        </div>
                    </div>

                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #32ff66">
                            <i class="fa fa-male fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  inherit;">&nbsp;&nbsp;<?php
                                if(strlen($this->session->userdata("parent_count"))==0){
                                    //staff_Org_id
                                    $query=$this->db->query("SELECT count(*) as total FROM `parent` WHERE iid='".$this->session->userdata("staff_Org_id")."' ");
                                    $query=$query->row();
                                    echo $query->total;
                                    $this->session->set_userdata("parent_count",$query->total);
                                }else{
                                    echo $this->session->userdata("parent_count");
                                }
                                ?></strong><br/>
                            <p>Total Parents</p>
                        </div>
                    </div>

                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #990099">
                            <i class="fa fa-comments-o fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  inherit;">&nbsp;&nbsp;
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
                            </strong><br/>
                            <p>SMS Credits</p>
                        </div>
                    </div>

                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #999900">
                            <i class="fa fa-phone fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  inherit;">&nbsp;&nbsp;20
                                <?php
                                //                $query=$this->db->query("SELECT * FROM `messaging` where  iid='".$this->session->userdata("staff_Org_id")."' ");
                                //                $query=$query->row();
                                //                $url="http://smslogin.mobi/spanelv2/api.php?username=".$query->username."&password=".$query->password;
                                //                $t= file($url);
                                //                if(is_numeric($t['0'])){
                                //                    echo $t['0'];
                                //                }else{
                                //                    echo "0";
                                //                }
                                ?>
                            </strong><br/>
                            <p>Voice Credits</p>
                        </div>
                    </div>
                </div>
                <?php
                $q=$this->db->query("SELECT * FROM `staff` where id= '".$this->session->userdata("staff_id")."' ");
                $q = $q->row();
                ?>
                <br style=" clear: both; color:#012B72"/>
                <br style=" clear: both; color:#012B72"/>
                <div class="box">

                    <div  style="float: left; width: 24%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">

                        <div  style=" width: 100%;">
                            <?php
                            $inst="select logo from institutes where id='".$this->session->userdata("staff_Org_id")."'";
                            $inst=$this->db->query($inst);
                            $inst = $inst->row();

                            $inst =$inst->logo;
                            if(strlen($inst)==0){

                                ?>
                                <img src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style=" width: 100%; height:133px;"   >
                                <?php
                            }else{
                                if(!file_exists(assets_path."/uploads/".$inst)){
                                    ?>
                                    <img src="<?php echo assets_path  ?>/uploads/<?php  echo $inst ?>" alt="..." style="height:133px; width:100%;" alt="<?php echo assets_path  ?>/uploads/snetwork.png"   >
                                    <?php

                                }
                                else{

                                    ?>
                                    <img src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style=" height:133px; width:100%;"   >

                                    <?php
                                }


                            }
                            ?>
                        </div>
                        <hr/>
                        <div style=" border: 1px solid #999999; border-radius: 10px; height:  140px;   " id='personal_details' class="box">
                            <div  style=" float: left; width: 23%; padding-top: 25px; padding-left: 5px;">
                                <?php
                                if(strlen($q->img)==0){
                                    ?>
                                    <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 80px;; height: 80px;"   >

                                    <?php
                                }else{
                                    if(file_exists(assets_path."/uploads/".$q->img)){
                                        ?>
                                        <img src="<?php echo assets_path  ?>/uploads/<?php  echo $q->img  ?>" alt="..." style=" width: 80px;; height: 80px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 80px;; height: 80px;"   >

                                        <?php
                                    }
                                }
                                ?>
                            </div>

                            <div  style=" float: left; width: 73%;padding-top: 25px; font-size: inherit; padding-left: 17px;" id='personal_info' >
                                <i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo ucfirst ($q->name) ?><br/>
                                <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $q->email ?><br/>
                                <i class="fa fa-phone-square"></i>&nbsp;&nbsp;<?php echo $q->phone ?><br/>
                                <i class="fa fa-briefcase"></i>&nbsp;&nbsp;<?php echo ($q->qualification) ?></strong>

                            </div>
                            <script>
                                // alert($("#personal_details").width());
                                if($("#personal_details").width() < 220 ){
                                    $("#personal_info").css("clear","both");
                                    $("#personal_info").css("font-size","10");
                                    $("#personal_details").css("height","auto");
                                }
                            </script>
                        </div>

                    </div>

                    <div  style="float: left; width: 24%;; margin-top: 10px; margin-left: 5px; margin-right: 10px; ">

                        <div style=" border: 1px solid #999999; border-radius: 10px; height: 320px; overflow-y: auto;   " class="box">
                            <h4 style=" width: 90%; float: left; color: #434348 ; padding: 0px; text-align: center"><i class="fa fa-birthday-cake" aria-hidden="true"></i>&nbsp;Birthdays</h4>
                            <a  onclick="load_template();" style=" float: left; padding-top: 8px;" href="#modal-1" role="button"  data-toggle="modal" rel="tooltip" title="" data-original-title="Send SMS " ><i class="fa fa-comments-o fa-2x"></i></a>
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
                            <table class="table table-bordered" style=" font-size: inherit; width: 100%" >
                                <thead>
                                <tr>
                                    <th>Name</th><th>Phone</th><th>Role</th>
                                </tr>
                                </thead>
                                <tbody >
                                <?php
                                if(sizeof($birthdays)==0){
                                    ?><tr><td style=" color: red; text-align: center;" colspan="3">** no records found..</td></tr><?php
                                }else{
                                    foreach($birthdays as $val){
                                        ?>
                                        <tr>
                                            <td><?php echo $val['name'] ?></td>
                                            <td><?php echo $val['phone'] ?></td>
                                            <td><?php echo $val['role'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="modal-1" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">Send Birthday Wishes </h4>
                                </div>
                                <!-- /.modal-header -->
                                <div id="modal_body" class="modal-body">
                                    <textarea name="bday_msg" id="bday_msg" class="form-control" style=" resize: none" placeholder="Please enter Reason For rejection"></textarea>
                                    <span id="reason_err" style="color: red"></span>
                                    <br/>
                                    <span style=" clear: both; color: #cc3300">For Name Use <#name#> In Message Content</span>

                                </div>
                                <!-- /.modal-body -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="button" onclick="send_bday_Alerts();" id="send_b_alerts" class="btn btn-primary">Send Wishes </button>
                                </div>
                                <!-- /.modal-footer -->
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
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


                    <div  style="float: left; width: 24%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">
                        <?php
                        $day = getdate();
                        $day=mktime(0,0,0,$day['mon'],$day['mday'],$day['year']);
                        ?>
                        <div style=" border: 1px solid #999999; border-radius: 10px; height: 320px;   " class="box">
                            <?php

                            $staff_att="SELECT id FROM `staff_attendance_date` where iid='".$this->session->userdata("staff_Org_id")."' and day='".$day."'";
                            $staff_att= $this->db->query($staff_att);
                            if($staff_att->num_rows()>0){
                                $staff_att= $staff_att->row();
                                $staff_att="SELECT `staff_attendance`.status, count(*) as count FROM `staff_attendance` join `staff` where date_id='".$staff_att->id."' and `staff`.id = `staff_attendance`.staff and `staff`.status = '1' GROUP BY `staff_attendance`.status";


                                $staff_att= $this->db->query($staff_att);
                                $staff_att= $staff_att->result();
                                $stff_present=0;$stff_absent=0;
                                foreach($staff_att as $val){
                                    if($val->status==1){
                                        $stff_present=$val->count;
                                    }else{
                                        $stff_absent= $val->count;
                                    }
                                }
                                if($stff_present!=0){
                                    $stff_present =($stff_present/($stff_present+$stff_absent))*100;
                                }
                                if($stff_absent!=0){
                                    $stff_absent =($stff_absent/($stff_present+$stff_absent))*100;
                                }
                                ?>
                                <script>
                                    $(function () {
                                        Highcharts.chart('container', {
                                            chart: {
                                                type: 'pie',
                                                options3d: {
                                                    enabled: true,
                                                    alpha: 45,
                                                    beta: 0
                                                }
                                            },
                                            title: {
                                                text: 'Staff Attendance'
                                            },
                                            tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                            plotOptions: {
                                                pie: {
                                                    allowPointSelect: true,
                                                    cursor: 'pointer',
                                                    depth: 35,
                                                    dataLabels: {
                                                        enabled: true,
                                                        format: '{point.name}'
                                                    }
                                                }
                                            },
                                            series: [{
                                                type: 'pie',
                                                name: 'Percentage',
                                                data: [
                                                    ['Present', <?php echo $stff_present ?>],
                                                    ['Absent', <?php echo $stff_absent ?>],
                                                ]
                                            }]
                                        });
                                    });
                                </script>
                                <?php

                            }else{
                                $staff_att= $staff_att->result();

                            }
                            ?>
                            <div id="container" style=" margin-left: 5px;padding-right: 8px; margin-top: 5px; width : 100%; height: 300px; ">
                                <h4 style=" text-align:center;">Staff Attendance </h4>
                                <h4 style=" text-align: center; color:  red ; margin-left: 15px; font-size: inherit; margin-top:130px; padding-right: 10px;"> ** Today's Attendance Not Added Yet..</h4>
                            </div>
                        </div>
                    </div>

                    <div  style="float: left; width: 24%;; margin-top: 10px; margin-left: 5px;">
                        <div style=" border: 1px solid #999999; border-radius: 10px; height: 320px;   " class="box">
                            <?php
                            $student_Att="SELECT group_concat(id) as ids  from attendance_date where iid='".$this->session->userdata("staff_Org_id")."' and day='".$day."' ";
                            $student_Att= $this->db->query($student_Att);
                            $student_Att= $student_Att->row();
                            $student_Att= $student_Att->ids;
                            if(strlen($student_Att)!=0){
                                $stud_abs="SELECT count(*) as absenties from attendance where date_id in (".$student_Att.")";
                                $stud_abs= $this->db->query($stud_abs);
                                $stud_abs= $stud_abs->row();
                                $stud_abs= $stud_abs->absenties;
                                $present=(($this->session->userdata("student_count")-$stud_abs)/$this->session->userdata("student_count"))*100;
                                $abs=100-$present;
                                ?>
                                <script>
                                    $(function () {
                                        Highcharts.chart('container_stud', {
                                            chart: {
                                                type: 'pie',
                                                options3d: {
                                                    enabled: true,
                                                    alpha: 45,
                                                    beta: 0
                                                }
                                            },
                                            title: {
                                                text: 'Student Attendance'
                                            },
                                            tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                            plotOptions: {
                                                pie: {
                                                    allowPointSelect: true,
                                                    cursor: 'pointer',
                                                    depth: 35,
                                                    dataLabels: {
                                                        enabled: true,
                                                        format: '{point.name}'
                                                    }
                                                }
                                            },
                                            series: [{
                                                type: 'pie',
                                                name: 'Percentage',
                                                data: [
                                                    ['Present', <?php echo $present ?>],
                                                    ['Absent', <?php echo $abs ?>],
                                                ]
                                            }]
                                        });
                                    });
                                </script>
                                <?php
                            }

                            ?>
                            <div id="container_stud" style=" margin-left: 5px; padding-right: 8px; margin-top: 5px;  width : 100%; height: 300px; ">
                                <h4 style=" text-align:center;">Student Attendance </h4>
                                <h4 style=" text-align: center; color:  red ;font-size: inherit; margin-left: 15px; margin-top:130px; padding-right: 10px;"> ** Today's Attendance Not Added Yet..</h4>

                            </div>
                        </div>
                    </div>

                </div>
                <script src="<?php echo assets_path  ?>graphs/code/highcharts.js"></script>
                <script src="<?php echo assets_path  ?>graphs/code//modules/exporting.js"></script>
                <script src="<?php echo assets_path  ?>graphs/code//highcharts-3d.js"></script>
            </div>
        </div>
    </div>
<?php
$this->load->view('structure/footer');
?>