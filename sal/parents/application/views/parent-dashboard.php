
<div class="row">
    
    <div class="col-sm-12">
            <ul class="tiles">
                    <li class="orange high long">
                        <a href="<?php echo base_url() ?>index.php/Students/timetable">
							<span class='count'>
                          <i class="fa fa-calendar" aria-hidden="true"></i></span>
						<span class='name'>Time Table</span>
                        </a>
                    </li>
                   <li class="blue long">
                            <a href="<?php echo base_url() ?>index.php/Students/exam_schedule" >
                                    <span>
                                       <i class="fa fa-file-text" aria-hidden="true"></i>
                                    </span>
                                    <span class='name'>Exam Schedules</span>
                            </a>
                    </li>
                    <li class="green long">
                            <a href="<?php echo base_url() ?>index.php/Students/assignments">
                                    <span class='count'>
                                    <i class="fa fa-files-o" aria-hidden="true"></i>
                                    <span class='name'>Assignment</span>
                            </span></a>
                    </li>
                    <li class="darkblue long">
                            <a href="<?php echo base_url() ?>index.php/Students/exam_reports" >
                                    <span class='count'>
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    <span class='name'>Exam Reports</span>
                            </span></a>
                    </li>
                    <li class="red long">
                            <a href="<?php echo base_url() ?>index.php/Students/attendance">
                                    <span class='count'>
                                         <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                    <span class='name'>Attendance</span>
                           </span> </a>
                    </li>
                    <li class="lightgrey long">
                            <a href="<?php echo base_url() ?>/index.php/settings/">
                                    <span class='nopadding'>
                                            <h5>User info</h5>
                                            <p>Name :<?php echo $this->session->userdata('parent_name'); ?> <br /> 
                                               email :<?php echo $this->session->userdata('parent_email'); ?> <br />
                                            </p>
                                    </span>
                                    <span class='name'>
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                            <span class="right">User Info</span>
                                    </span>
                            </a>
                    </li>
                                       
                    <li class="brown long">
                        <a href="<?php echo base_url() ?>index.php/Students/library">
                                    <span class='count'>
                                        
                               		<i class="fa fa-book" aria-hidden="true"></i>
                                    <span class='name'>Library Reports</span>
                            </span></a>
                    </li>
                    
                    
                    
                    <li class="teal long ">
                            <a href="<?php echo base_url() ?>index.php/Students/fees">
                                    <span class='count'>
                                           <i class="fa fa-inr" aria-hidden="true"></i>
                                    <span class='name'>Fee Payments</span>
                                    </span>
                            </a>
                    </li>
                    <li class="pink ">
                        <a href="<?php echo base_url() ?>/index.php/settings/">
                                    <span class='count'>
                                           <i class="fa fa-cogs" aria-hidden="true"></i>
                                    <span class='name'>Profile & Settings</span>
                                    </span>
                            </a>
                    </li>
                    
                    <li class="lime ">
                        <a href="#">
                                <span class='count'>
                                   <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span class='name'>Sign Out</span>
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
