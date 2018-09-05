<div class="row">
    <div class="col-sm-9">
    <div class="col-sm-12">
            <ul class="tiles">
                    <li class="orange high long">
                        <a href="<?php echo base_url() ?>index.php/students/add">
			<span class='count'  style="text-align: center">
                          <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </span>
			<span class='name'>Create Students</span>
                        </a>
                    </li>
                   <li class="blue long">
                            <a href="<?php echo base_url() ?>index.php/parents/parent_add">
                                    <span>
                                        <i class="fa fa-male" aria-hidden="true"></i>
                                    </span>
                                    <span class='name'>Create Parent</span>
                            </a>
                    </li>
                    <li class="green long">
                            <a href="<?php echo base_url() ?>index.php/staff/Add_staff">
                                    <span class='count'>
                                          <i class="fa fa-user-secret" aria-hidden="true"></i>
                                    <span class='name'>Create Staff</span>
                            </span></a>
                    </li>
                    <li class="magenta">
                            <a href="<?php echo base_url() ?>index.php/timetable/view">
                                    <span class='count'>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <span class='name'>Timetable</span>
                            </span></a>
                    </li>
                    <li class="darkblue">
                            <a href="<?php echo base_url() ?>index.php/accounts/view">
                                    <span class='count'>
                                        <i class="fa fa-inr" aria-hidden="true"></i>
                                        <span class='name'>Accounts</span>
                                    </span>
                            </a>
                    </li>
                    <li class="red">
                            <a href="<?php echo base_url() ?>index.php/expenditure">
                                    <span class='count'>
                                           <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                    <span class='name'>Expenditure</span>
                           </span> </a>
                    </li>
                    <li class="lime">
                            <a href="<?php echo base_url() ?>index.php/academics/Cls_structure">
                                    <span class='count'>
                                           <i class="fa fa-sitemap" aria-hidden="true"></i>
                                    <span class='name'>Academics</span>
                           </span> </a>
                    </li>
                    <li class="lightgrey long">
                            <a href="<?php echo base_url() ?>index.php/logs/view">
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
                    <li class="teal">
                            <a href="<?php echo base_url() ?>index.php/attendance/view">
                                    <span class='count'>
                                           <i class="fa fa-check" aria-hidden="true"></i>
                                    <span class='name'>Attendance</span>
                                    </span>
                            </a>
                    </li>
                    <li class="brown ">
                            <a href="<?php echo base_url() ?>index.php/exam/view">
                                    <span class='count'>
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    <span class='name'>Exams</span>
                                    </span>
                            </a>
                    </li>
                    
                    <li class="pink">
                            <a href="<?php echo base_url() ?>index.php/settings/">
                                    <span class='count'>
                                           <i class="fa fa-cogs" aria-hidden="true"></i>
                                    <span class='name'>Profile & Settings</span>
                                    </span>
                            </a>
                    </li>
                    
                    <li class="satblue">
                            <a href="<?php echo base_url() ?>index.php/salary/pay_salary">
                                    <span class='count'>
                                           <i class="fa fa-usd" aria-hidden="true"></i>
                                    <span class='name'>salaries</span>
                                    </span>
                            </a>
                    </li>
                    <li class="blue">
                            <a href="<?php echo base_url() ?>index.php/fee/view">
                                    <span class='count'>
                                           <i class="fa fa-money" aria-hidden="true"></i>
                                    <span class='name'>Fee structure</span>
                                    </span>
                            </a>
                    </li>
                    
                    <li class="grey">
                            <a href="<?php echo base_url() ?>index.php/login/logout">
                                    <span class='count'>
                                          <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    <span class='name'>Logout</span>
                                    </span>
                            </a>
                    </li>
                    
                    
                  
            </ul>
    </div>
    </div>
    <div class="col-sm-3">
	<div class="row">
            <div class="col-md-12">
                <ul class="tiles">
                    <li class="orange long">
                        <a href="<?php echo base_url() ?>index.php/students/add">
                            <span class='count' >
                                <i style=" float: left; width: 15%" class="fa fa-child"></i>&nbsp;
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
                                    </span>
                            <span class='name'>
                                Total Students &nbsp;   
                            </span>         
                        </a>
                    </li>
                    
                    <li class="green long">
                        <a href="<?php echo base_url() ?>index.php/students/add">
                            <span class='count' >
                                <i style=" float: left; width: 15%" class="fa fa-male"></i>&nbsp;
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
                            </span>
                            <span class='name'>
                                Total Parents   
                            </span>         
                        </a>
                    </li>
                    
                    <li class="pink long">
                        <a href="<?php echo base_url() ?>index.php/students/add">
                            <span class='count' >
                                <img src="<?php echo assets_path ?>img/teacher.png" style=" float: left; "  />&nbsp;
                                <?php
                                      if(strlen($this->session->userdata("tstaff_count"))==0){
                                          //staff_Org_id
                                         $query=$this->db->query("SELECT count(*) as total  FROM `staff` WHERE iid='".$this->session->userdata("staff_Org_id")."' AND level=1 "); 
                                         $query=$query->row();
                                         echo $query->total;
                                         $this->session->set_userdata("tstaff_count",$query->total);  
                                      }else{
                                        echo $this->session->userdata("tstaff_count");  
                                      }
                                    ?>
                                    </span>
                            <span class='name'>
                                Total Teaching Staff   
                            </span>         
                        </a>
                    </li>
                    
                    <li class="blue long">
                        <a href="<?php echo base_url() ?>index.php/students/add">
                            <span class='count' >
                                <img src="<?php echo assets_path ?>img/accounts.png" style=" float: left; "  />&nbsp;<?php
                                      if(strlen($this->session->userdata("staff_count"))==0){
                                          //staff_Org_id
                                         $query=$this->db->query("SELECT count(*) as total  FROM `staff` WHERE iid='".$this->session->userdata("staff_Org_id")."' AND level > 2 "); 
                                         $query=$query->row();
                                         echo $query->total;
                                         $this->session->set_userdata("staff_count",$query->total);  
                                      }else{
                                        echo $this->session->userdata("staff_count");  
                                      }
                                    ?>
                                    </span>
                            <span class='name'>
                                Total Non-Teaching Staff   
                            </span>         
                        </a>
                    </li>
                </ul>
                
            </div>
            
            
    	</div>
        
  
        
        
    </div>
</div>
<script>
    function request_credits(){
        alert("Request Credits");
    }
</script>
