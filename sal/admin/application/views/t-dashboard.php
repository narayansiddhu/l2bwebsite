<div class="row">
    <div class="col-sm-12 nopadding">
            <ul class="tiles">
                    <li class="orange high long">
                        <a href="<?php echo base_url() ?>index.php/teachers/view_exams">
							<span class='count'>
                            <img src="<?php echo assets_path ?>img/test.png"  /></span>
						<span class='name'>Exams Schedule</span>
                        </a>
                    </li>
                   <li class="blue long">
                            <a href="<?php echo base_url() ?>index.php/teachers/assignments">
                                    <span>
                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                    </span>
                                    <span class='name'>Add Assignment</span>
                            </a>
                    </li>
                    <li class="green long">
                            <a href="<?php echo base_url() ?>index.php/teachers/timetable">
                                    <span class='count'>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <span class='name'>Timetable</span>
                            </span></a>
                    </li>
                    <li class="darkblue long">
                            <a href="<?php echo base_url() ?>index.php/teachers/exam_reports">
                                    <span class='count'>
                               		<i class="fa fa-book" aria-hidden="true"></i>

                                    <span class='name'>Result's</span>
                            </span></a>
                    </li>
                    <li class="red long">
                        <a href="<?php echo base_url() ?>index.php/teachers/salary">
                                    <span class='count'>
                                           <i class="fa fa-money" aria-hidden="true"></i>
                                    <span class='name'>Salary History</span>
                                    </span>
                            </a>                           
                    </li>
                    
                    <li class="lightgrey long">
                            <a href="<?php echo base_url() ?>index.php/settings/">
                                    <span class='nopadding'>
                                            <h5>User info</h5>
                                            <p>Name :<?php echo $this->session->userdata('staff_name'); ?> <br /> 
                                               email :<?php echo $this->session->userdata('staff_user'); ?> <br />
                                               
                                            </p>
                                    </span>
                                    <span class='name'>
                                            <i class="fa fa-sign-in" aria-hidden="true"></i>
                                            <span class="right">Activity Logs</span>
                                    </span>
                            </a>
                    </li>
                    <li class="pink long">
                            <a href="<?php echo base_url() ?>index.php/settings">
                                    <span class='count'>
                                           <i class="fa fa-cogs" aria-hidden="true"></i>
                                    <span class='name'>Profile & Settings</span>
                                    </span>
                            </a>
                    </li>
                    <li class="teal long ">
                            <a href="<?php echo base_url() ?>index.php/teachers/self_attendance">
                                    <span class='count'>
                                           <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    <span class='name'>Self Attendance</span>
                                    </span>
                            </a>
                    </li>
                    <li class="satblue long">
                            <a href="<?php echo base_url() ?>index.php/logs/view">
                                    <span class='count'>
                                           <i class="fa fa-globe" aria-hidden="true"></i>
                                    <span class='name'>Log's</span>
                                    </span>
                            </a>
                    </li>
                    </li>
                    <li class="purple long">
                        <a href="<?php echo base_url() ?>index.php/students/View">
                                    <span class='count'>
                                      <i class="fa fa-users" aria-hidden="true"></i>
                                    <span class='name'>View Students</span>
                                    </span>
                            </a>
                    </li>
                    <li class="brown long">
                            <a href="<?php echo base_url() ?>index.php/teachers/attendance_Report">
                                    <span class='count'>
                                           <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    <span class='name'>Student Attendance</span>
                                    </span>
                            </a>
                    </li>
                    
                  
            </ul>

    </div>
    
</div>
<script>
    function request_credits(){
        alert("Request Credits");
    }
</script>
