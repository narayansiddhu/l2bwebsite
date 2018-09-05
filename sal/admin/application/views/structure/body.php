<?php
    $q=$this->db->query("SELECT * FROM `staff` where id= '".$this->session->userdata("staff_id")."' ");
    $q = $q->row();
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>

                        <?php
                        $inst="select logo from institutes where id='".$this->session->userdata("staff_Org_id")."'";
                        $inst=$this->db->query($inst);
                        $inst = $inst->row();

                        $inst =$inst->logo;
                        if(strlen($inst) == 0){ ?>
                            <img class="wow bounceInLeft" data-wow-delay="0.1s" src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style="width:100%;"  ><?php
                        } else {
                            if(!file_exists(assets_path."/uploads/".$inst)){ ?>
                                <img class="wow bounceInLeft" data-wow-delay="0.1s" src="<?php echo assets_path  ?>/uploads/<?php  echo $inst ?>" style="width:100%;"  alt="<?php echo assets_path  ?>/uploads/snetwork.png">
                            <?php } else { ?>
                                <img class="wow bounceInLeft" data-wow-delay="0.1s" src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style="width:100%;"   >
                            <?php }
                        }
                        ?>
<!--                        <img alt="image" class="img-circle" src="--><?//= assets_path_admin ?><!--img/profile_small.png" />-->
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs">
                                    <strong class="font-bold">
                                        <?= $this->session->userdata('staff_user'); ?>
                                    </strong>
                                </span>
                                <span class="text-muted text-xs block">
                                    <?= $this->session->userdata('staff_name'); ?>
                                    <b class="caret"></b>
                                </span>
                            </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo base_url() ?>index.php/settings/">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url() ?>index.php/Notices/staff_notices">Notifications</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/logs/view">Messages</a></li>
                        <li><a href="<?php echo base_url() ?>index.php/login/logout/">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    SN+
                </div>
            </li>
            <?php
                $nav_active = $this->uri->segment(1);
                $nav_active_sub = $this->uri->segment(2);
                switch ($nav_active){
                    case 'Dashboard'     : $dashboard = 'active'; break;
                    case 'Staff'         : $staff = $Staff = 'active'; break;
                    case 'Admissions'    : $Admissions = 'active'; break;
                    case 'students'      : $students = 'active'; break;
                    case 'parents'       : $parents = 'active'; break;
                    case 'timetable'     : $timetable = 'active'; break;
                    case 'attendance'    : $attendance = 'active'; break;
                    case 'clzattendance' : $clzattendance = 'active'; break;
                    case 'exams'         : $exams = 'active'; break;
                    case 'accounts'      : $accounts = 'active'; break;
                    case 'Alerts'        : $Alerts = 'active'; break;
                    case 'transport'     : $transport = 'active'; break;
                    case 'Hostel'        : $Hostel = 'active'; break;
                    case 'idcards'       : $idcards = 'active'; break;
                    case 'expenditure'   : $expenditure = 'active'; break;
                    case 'teachers'      : $teachers = 'active'; break;
                }
                switch ($nav_active_sub){
                    case 'View_staff'          : $Staff = 'active'; break;
                    case 'subjects'            : $subjects = 'active'; break;
                    case 'class_list'          : $class_list = 'active'; break;
                    case 'view_incharges'      : $view_incharges = 'active'; break;
                    case 'books'               : $books = 'active'; break;
                    case 'category'            : $category = 'active'; break;
                    case 'issue'               : $issue = 'active'; break;
                    case 'submit'              : $submit = 'active'; break;
                    case 'payments'            : $payments = 'active'; break;
                    case 'search'              : $search = 'active'; break;
                    case 'view_request'        : $view_request = 'active'; break;
                    case 'staff_notices'       : $staff_notices = 'active'; break;
                    case 'add_record'          : $add_record = 'active'; break;
                    case 'manage_concessions'  : $manage_concessions = 'active'; break;
                    case 'history'             : $history = 'active'; break;
                    case 'salary'              : $salary = 'active'; break;
                    case 'pay_salary'          : $pay_salary = 'active'; break;
                    case 'fee_payment'         : $fee_payment = 'active'; break;
                    case 'pay_fee'             : $pay_fee = 'active'; break;
                    case 'View'                : $view = $View = 'active'; break;
                    case 'exam_reports'        : $exam_reports = 'active'; break;
                    case 'examination'         : $examination = 'active'; break;
                    case 'timetable'           : $t_timetable = 'active'; break;
                    case 'view_Staff_course'   : $view_Staff_course = 'active'; break;
                    case 'vehicles'            : $vehicles = 'active'; break;
                    case 'drivers'             : $drivers = 'active'; break;
                    case 'routes'              : $routes = 'active'; break;
                    case 'stud_details'        : $stud_details = 'active'; break;
                    case 'maintenance'         : $maintenance = 'active'; break;
                    case 'Blocks'              : $Blocks = 'active'; break;
                    case 'Rooms'               : $Rooms = 'active'; break;
                    case 'Fee_structure'       : $Fee_structure = 'active'; break;
                    case 'Manage_students'     : $Manage_students = 'active'; break;
                }
                if($this->session->userdata("staff_level")>=8){
            ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Staff)) echo $Staff ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/Staff">
                            <i class="fa fa-group"></i>
                            <span class="nav-label">Staff</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Staff">View Staff</a></li>
                            <li><a href="#">Create Staff</a></li>
                            <li><a href="#">View In-charges</a></li>
                            <li><a href="#">Print Staff</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($subjects)) echo $subjects ; ?>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span class="nav-label">Subjects</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/academics/subjects">View Subjects</a></li>
                            <li><a href="#">Create Class</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($class_list)) echo $class_list ; ?>">
                        <a href="#">
                            <i class="fa fa-sitemap"></i>
                            <span class="nav-label">Classes</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/academics/class_list">View Class</a></li>
                            <li><a href="#">Create Subject</a></li>
                            <li><a href="#">Assign Course</a></li>
                            <li><a href="#">Assign Academic Structure</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($Admissions)) echo $Admissions ; ?>">
                        <a href="#">
                            <i class="fa fa-user-plus"></i>
                            <span class="nav-label">Admissions</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Admissions">View Admissions</a></li>
                            <li><a href="#">Add Admission</a></li>
                            <li><a href="#">Approved  Admissions</a></li>
                            <li><a href="#">Rejected Admissions</a></li>
                            <li><a href="#">Approved Report</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($students)) echo $students ; ?>">
                        <a href="#">

                            <i class="fas fa-user-graduate"></i>
                            <span class="nav-label">Students</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/students/View">View Students</a></li>
                            <li><a href="#">Create Student</a></li>
                            <li><a href="#">Admissions</a></li>
                            <li><a href="#">Search Student</a></li>
                            <li><a href="#">Promote Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($parents)) echo $parents ; ?>" >
                        <a href="#">
                            <i class="fa fa-user-friends"></i>
                            <span class="nav-label">Parents</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/parents">View Parents</a></li>
                            <li><a href="#">Create Student </a></li>
                            <li><a href="#">Link Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($timetable)) echo $timetable ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/timetable/view">
                            <i class="fa fa-pie-chart"></i>
                            <span class="nav-label">Timetable</span>
                        </a>
                    </li>
                    <?php
                    $a = $this->db->query("select type from institutes where id='".$this->session->userdata("staff_Org_id")."'  ")->row();
                    $a = $a->type;
                    if($a == 1){
                        ?>
                        <li class="<?php if(isset($attendance)) echo $attendance ; ?>" >
                            <a href="<?php echo base_url(); ?>index.php/attendance/">
                                <i class="fa fa-calendar-check-o"></i>
                                <span class="nav-label">Attendance</span>
                            </a>
                        </li>
                        <?php
                    } else { ?>
                    <li class="<?php if(isset($clzattendance)) echo $clzattendance ; ?>" >
                        <a href="<?php echo base_url(); ?>index.php/clzattendance/">
                                <i class="fa fa-calendar-check-o"></i>
                                <span class="nav-label">Attendance</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="<?php if(isset($exams)) echo $exams ; ?>" >
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span class="nav-label">Exams</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/exams">View Exams</a></li>
                            <li><a href="#">Create New Exam </a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($accounts)) echo $accounts ; ?>" >
                        <a href="#">
                            <i class="fa fa-rupee-sign"></i>
                            <span class="nav-label">Accounts</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/accounts/Dashboard">View Accounts</a></li>
                            <li><a href="#">Salaries </a></li>
                            <li><a href="#">Expenditure </a></li>
                            <li><a href="#">Fees </a></li>
                            <li><a href="#">Settings </a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($Alerts)) echo $Alerts ; ?>" >
                        <a href="#">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">SMS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Alerts">View SMS</a></li>
                            <li><a href="#">Alerts </a></li>
                            <li><a href="#">Results </a></li>
                            <li><a href="#">Attendance </a></li>
                            <li><a href="#">Fee Alerts </a></li>
                            <li><a href="#">SMS History </a></li>
                            <li><a href="#">SMS Templates </a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($transport)) echo $transport ; ?>" >
                        <a href="#">
                            <i class="fa fa-bus"></i>
                            <span class="nav-label">Transport </span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/transport">View Transports</a></li>
                            <li>
                                <a href="#">Buses/Vehicles <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Vehicles</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Drivers <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Drivers</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Routes <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Routes</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Students <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Students </a>
                                    </li>
                                </ul>
                            </li>

                            <li><a href="#">Search</a></li>
                            <li><a href="#">Maintenance</a></li>
                            <li><a href="#">Fee Payments</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($Hostel)) echo $Hostel ; ?>" >
                        <a href="#">
                            <i class="fa fa-building"></i>
                            <span class="nav-label">Hostel </span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Hostel">View Hostel</a></li>
                            <li>
                                <a href="#">Rooms <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Rooms</a>
                                    </li>
                                    <li>
                                        <a href="#">Add Blocks</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Students <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Add Students</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Fee Structure</a></li>
                            <li><a href="#">Maintenance</a></li>
                            <li><a href="#">Fee Payments</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($idcards)) echo $idcards ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/idcards">
                            <i class="fa fa-id-badge"></i>
                            <span class="nav-label">Id Cards</span>
                        </a>
                    </li>


            <?php
                }elseif($this->session->userdata("staff_level")==7){
            ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Staff)) echo $Staff ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/Staff"><i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Staff</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="#">Create Staff</a></li>
                            <li><a href="#">View In-charges</a></li>
                            <li><a href="#">Print Staff</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($Admissions)) echo $Admissions ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Admissions</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Admissions">View Admissions</a></li>
                            <li><a href="#">Add Admission</a></li>
                            <li><a href="#">Approved  Admissions</a></li>
                            <li><a href="#">Rejected Admissions</a></li>
                            <li><a href="#">Approved Report</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($students)) echo $students ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Students</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/students/View">View Students</a></li>
                            <li><a href="#">Create Student</a></li>
                            <li><a href="#">Admissions</a></li>
                            <li><a href="#">Search Student</a></li>
                            <li><a href="#">Promote Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($parents)) echo $parents ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Parents</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/parents">View Parents</a></li>
                            <li><a href="#">Create Student </a></li>
                            <li><a href="#">Link Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($exams)) echo $exams ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Exams</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/exams">View Exams</a></li>
                            <li><a href="#">Create New Exam </a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($accounts)) echo $accounts ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/accounts">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Fees</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($expenditure)) echo $expenditure ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/expenditure/history">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Expenses</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($teachers)) echo $teachers ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/teachers/salary">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Salary</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Alerts)) echo $Alerts ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">SMS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Alerts">View SMS</a></li>
                            <li><a href="#">Alerts </a></li>
                            <li><a href="#">Results </a></li>
                            <li><a href="#">Attendance </a></li>
                            <li><a href="#">Fee Alerts </a></li>
                            <li><a href="#">SMS History </a></li>
                            <li><a href="#">SMS Templates </a></li>
                        </ul>
                    </li>

        <?php
            }elseif($this->session->userdata("staff_level")==6){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Staff)) echo $Staff ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/Staff"><i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Staff</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="#">Create Staff</a></li>
                            <li><a href="#">View In-charges</a></li>
                            <li><a href="#">Print Staff</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($Admissions)) echo $Admissions ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Admissions</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Admissions">View Admissions</a></li>
                            <li><a href="#">Add Admission</a></li>
                            <li><a href="#">Approved  Admissions</a></li>
                            <li><a href="#">Rejected Admissions</a></li>
                            <li><a href="#">Approved Report</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($students)) echo $students ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Students</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/students/View">View Students</a></li>
                            <li><a href="#">Create Student</a></li>
                            <li><a href="#">Admissions</a></li>
                            <li><a href="#">Search Student</a></li>
                            <li><a href="#">Promote Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($parents)) echo $parents ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Parents</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/parents">View Parents</a></li>
                            <li><a href="#">Create Student </a></li>
                            <li><a href="#">Link Student</a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($view_incharges)) echo $view_incharges ; ?>">
                        <a href="<?php echo base_url() ?>index.php/staff/view_incharges">
                            <i class="fa fa-user-secret"></i>
                            <span class="nav-label">In-Charges</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($exams)) echo $exams ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">Exams</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/exams">View Exams</a></li>
                            <li><a href="#">Create New Exam </a></li>
                        </ul>
                    </li>
                    <?php
                    $a = $this->db->query("select type from institutes where id='".$this->session->userdata("staff_Org_id")."'  ")->row();
                    $a = $a->type;
                    if($a == 1){
                        ?>
                        <li class="<?php if(isset($attendance)) echo $attendance ; ?>" >
                            <a href="<?php echo base_url(); ?>index.php/attendance/">
                                <i class="fa fa-calendar-check-o"></i>
                                <span class="nav-label">Attendance</span>
                            </a>
                        </li>
                        <?php
                    } else { ?>
                        <li class="<?php if(isset($clzattendance)) echo $clzattendance ; ?>" >
                            <a href="<?php echo base_url(); ?>index.php/clzattendance/">
                                <i class="fa fa-calendar-check-o"></i>
                                <span class="nav-label">Attendance</span>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="<?php if(isset($expenditure)) echo $expenditure ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/expenditure/history">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Expenses</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($teachers)) echo $teachers ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/teachers/salary">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Salary</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Alerts)) echo $Alerts ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">SMS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Alerts">View SMS</a></li>
                            <li><a href="#">Alerts </a></li>
                            <li><a href="#">Results </a></li>
                            <li><a href="#">Attendance </a></li>
                            <li><a href="#">Fee Alerts </a></li>
                            <li><a href="#">SMS History </a></li>
                            <li><a href="#">SMS Templates </a></li>
                        </ul>
                    </li>
        <?php
            }elseif($this->session->userdata("staff_level")==5){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($books)) echo $books ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/books">
                            <i class="fa fa-book"></i>
                            <span class="nav-label">Books</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($category)) echo $category ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/category">
                            <i class="fa fa-object-group"></i>
                            <span class="nav-label">Categories</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($issue)) echo $issue ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/issue">
                            <i class="fa fa-handshake-o"></i>
                            <span class="nav-label">Issue</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($submit)) echo $submit ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/submit" >
                            <i class="fa fa-reply-all"></i>
                            <span class="nav-label">Return</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($payments)) echo $payments ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/payments" >
                            <i class="fa fa-rupee-sign"></i>
                            <span class="nav-label">Payments</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($search)) echo $search ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/search" >
                            <i class="fa fa-search"></i>
                            <span class="nav-label">Search</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($view_request)) echo $view_request ; ?>">
                        <a href="<?php echo base_url() ?>index.php/library/view_request" >
                            <i class="fa fa-hand-paper-o"></i>
                            <span class="nav-label">Request</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($staff_notices)) echo $staff_notices ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Notices/staff_notices" >
                            <i class="fa fa-bell"></i>
                            <span class="nav-label">Notifications</span>
                        </a>
                    </li>
        <?php
            }elseif($this->session->userdata("staff_level")==3){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($accounts)) echo $accounts ; ?>">
                        <a href="<?php echo base_url() ?>index.php/accounts" >
                            <i class="fa fa-cogs"></i>
                            <span class="nav-label">Manage Fee</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($add_record)) echo $add_record ; ?>">
                        <a href="<?php echo base_url() ?>index.php/accounts/add_record" >
                            <i class="fa fa-money "></i>
                            <span class="nav-label">Pay Fee</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($manage_concessions)) echo $manage_concessions ; ?>">
                        <a href="<?php echo base_url() ?>index.php/fee/manage_concessions" >
                            <i class="fa fa-minus-circle "></i>
                            <span class="nav-label">Concession</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($history)) echo $history; ?>">
                        <a href="<?php echo base_url() ?>index.php/expenditure/history" >
                            <i class="fa fa-usd "></i>
                            <span class="nav-label">Expenses</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($salary)) echo $salary; ?>">
                        <a href="<?php echo base_url() ?>index.php/teachers/salary" >
                            <i class="fa fa-inr "></i>
                            <span class="nav-label">Salary</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($pay_salary)) echo $pay_salary; ?>">
                        <a href="<?php echo base_url() ?>index.php/salary/pay_salary" >
                            <i class="fa fa-money-bill-alt "></i>
                            <span class="nav-label">pay Salary</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($fee_payment)) echo $fee_payment; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/fee_payment" >
                            <i class="fa fa-bus "></i>
                            <span class="nav-label">Bus Fee</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($pay_fee)) echo $pay_fee; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/pay_fee" >
                            <i class="fa fa-building"></i>
                            <span class="nav-label">Hostel Fee</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($staff_notices)) echo $staff_notices ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Notices/staff_notices" >
                            <i class="fa fa-bell"></i>
                            <span class="nav-label">Notifications</span>
                        </a>
                    </li>
        <?php
            }elseif($this->session->userdata("staff_level")==1){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($staff)) echo $staff ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/staff">
                            <i class="fa fa-file-text "></i>
                            <span class="nav-label">Assignments</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($View)) echo $View ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/students/View">
                            <i class="fa fa-check "></i>
                            <span class="nav-label">Attendance</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($view_Staff_course)) echo $view_Staff_course ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/course/view_Staff_course">
                            <i class="fa fa-sitemap "></i>
                            <span class="nav-label">Academics</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($t_timetable)) echo $t_timetable ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/teachers/timetable">
                            <i class="fa fa-calendar "></i>
                            <span class="nav-label">Timetable</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($examination)) echo $examination ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/teachers/examination">
                            <i class="fa fa-pencil-square-o"></i>
                            <span class="nav-label">Exams</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($exam_reports)) echo $exam_reports ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/teachers/exam_reports">
                            <i class="fa fa-pencil-square-o"></i>
                            <span class="nav-label">Results</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($expenditure)) echo $expenditure ; ?>">
                        <a href="<?php echo base_url(); ?>index.php/expenditure/history">
                            <i class="fa fa-flask"></i>
                            <span class="nav-label">Expenses</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($salary)) echo $salary; ?>">
                        <a href="<?php echo base_url() ?>index.php/teachers/salary" >
                            <i class="fa fa-inr "></i>
                            <span class="nav-label">Salary</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Alerts)) echo $Alerts ; ?>">
                        <a href="#">
                            <i class="fa fa-bar-chart-o"></i>
                            <span class="nav-label">SMS</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="<?php echo base_url(); ?>index.php/Alerts">View SMS</a></li>
                            <li><a href="#">Alerts </a></li>
                            <li><a href="#">Results </a></li>
                            <li><a href="#">Attendance </a></li>
                            <li><a href="#">Fee Alerts </a></li>
                            <li><a href="#">SMS History </a></li>
                            <li><a href="#">SMS Templates </a></li>
                        </ul>
                    </li>
                    <li class="<?php if(isset($staff_notices)) echo $staff_notices ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Notices/staff_notices" >
                            <i class="fa fa-bell"></i>
                            <span class="nav-label">Notifications</span>
                        </a>
                    </li>
        <?php
            }elseif($this->session->userdata("staff_level")==2){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($vehicles)) echo $vehicles ; ?>">
                        <a href="<?php echo base_url() ?>index.php/transport/vehicles">
                            <i class="fa fa-bus "></i>
                            <span class="nav-label">Buses</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($drivers)) echo $drivers ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/drivers">
                            <i class="fa fa-user "></i>
                            <span class="nav-label">Drivers</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($routes)) echo $routes ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/routes">
                            <i class="fa fa-road"></i>
                            <span class="nav-label">Routes</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($stud_details)) echo $stud_details ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/stud_details">
                            <i class="fa fa-child"></i>
                            <span class="nav-label">Routes</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($search)) echo $search ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/search">
                            <i class="fa fa-search"></i>
                            <span class="nav-label">Search</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($maintenance)) echo $maintenance ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/maintenance">
                            <i class="fa fa-cogs "></i>
                            <span class="nav-label">Maintenance</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($fee_payment)) echo $fee_payment ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Transport/fee_payment">
                            <i class="fa fa-money-bill-alt "></i>
                            <span class="nav-label">Maintenance</span>
                        </a>
                    </li>
        <?php
            }elseif($this->session->userdata("staff_level")==4){
        ?>
                    <li class="<?php if(isset($dashboard)) echo $dashboard ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Dashboard">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Blocks)) echo $Blocks ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Blocks">
                            <i class="fa fa-blogger "></i>
                            <span class="nav-label">Blocks</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Rooms)) echo $Rooms ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Rooms">
                            <i class="fa fa-building "></i>
                            <span class="nav-label">Rooms</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Fee_structure)) echo $Fee_structure ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Fee_structure">
                            <i class="fa fa-dollar-sign "></i>
                            <span class="nav-label">Fee Structure</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($Manage_students)) echo $Manage_students ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/Manage_students">
                            <i class="fa fa-child"></i>
                            <span class="nav-label">Students</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($search)) echo $search ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/search">
                            <i class="fa fa-search"></i>
                            <span class="nav-label">Search</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($maintenance)) echo $maintenance ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/maintenance">
                            <i class="fa fa-cogs"></i>
                            <span class="nav-label">Maintenance</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($pay_fee)) echo $pay_fee ; ?>">
                        <a href="<?php echo base_url() ?>index.php/Hostel/pay_fee">
                            <i class="fa fa-money-check"></i>
                            <span class="nav-label">Fees</span>
                        </a>
                    </li>
        <?php } ?>
        </ul>
    </div>
</nav>