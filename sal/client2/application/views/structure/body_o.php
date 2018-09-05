<div class="container-fluid" id="content">
    <div id="left" class="ui-sortable ui-resizable forced-hide" style="display: none;">
        &nbsp;
    </div>
<div id="main" style="margin-left: 0px;">
    <div class="box" style=" background:#012B72; padding-left: 8px; height:200px; text-align:left;">
<?php 
 if($this->session->userdata("staff_level")>=8){
     ?>
     <div class="box" style="padding-left: 4px;  height: 70px;">
         <br/>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/Staff"  >
             <i class="fa fa-user fa-2x"></i>
             <p>Staff</p>
         </a>

         <a class="new_title" href="<?php echo base_url(); ?>index.php/academics/class_list"  >
             <i class="fa fa-sitemap fa-2x"></i>
             <p>Classes</p>
         </a>

         <a class="new_title" href="<?php echo base_url(); ?>index.php/academics/subjects"  >
             <i class="fa fa-scribd fa-2x"></i>
             <p>Subjects</p>
         </a>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/Admissions"  >
             <i class="fa fa-user-plus fa-2x"></i>
             <p>Admissions</p>
         </a>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/students/View"  >
             <i class="fa fa-child fa-2x"></i>
             <p>Students</p>
         </a>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/parents"  >
             <i class="fa fa-male fa-2x"></i>
             <p>Parents</p>
         </a>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/timetable/view"  >
             <i class="fa fa-tasks fa-2x"></i>
             <p>Timetable</p>
         </a>
         <?php
         $a=$this->db->query("select type from institutes where id='".$this->session->userdata("staff_Org_id")."'  ")->row();
         $a =$a->type;
         if($a==1){
             ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/attendance/"  >
                 <i class="fa fa-check fa-2x"></i>
                 <p>Attendance</p>
             </a>
             <?php
         }else{
             ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/clzattendance/"  >
                 <i class="fa fa-check fa-2x"></i>
                 <p>Attendance</p>
             </a>
             <?php
         }
         ?>


         <a class="new_title" href="<?php echo base_url(); ?>index.php/exams"  >
             <i class="fa fa-pencil-square-o fa-2x"></i>
             <p>Exams</p>
         </a>

         <a class="new_title" href="<?php echo base_url(); ?>index.php/accounts/Dashboard"  >
             <i class="fa fa-inr fa-2x"></i>
             <p>Accounts</p>
         </a>

         <!--            <a class="new_title" href="<?php echo base_url(); ?>index.php/expenditure/"  >
                <i class="fa fa-money fa-2x"></i>
                <p>Expenditure</p>
            </a>-->
         <a class="new_title" href="<?php echo base_url(); ?>index.php/Alerts"  >
             <i class="fa fa-comments-o fa-2x"></i>
             <p>SMS</p>
         </a>
         <!--            <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/"  >
                <i class="fa fa-bell-o fa-2x"></i>
                <p>Notifications</p>
            </a>-->
         <a class="new_title" href="<?php echo base_url(); ?>index.php/transport"  >
             <i class="fa fa-bus fa-2x"></i>
             <p>Transport</p>
         </a>
         <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel"  >
             <i class="fa fa-building fa-2x"></i>
             <p>Hostel</p>
         </a>

         <a class="new_title " href="<?php echo base_url(); ?>index.php/idcards"  style=" text-decoration: none !important;" >
             <i class="fa fa-id-badge fa-2x" aria-hidden="true"></i>
             <p>Id Cards</p>
         </a>


     </div>
     <?php
 } elseif($this->session->userdata("staff_level")==7){
     ?>
          <div class="box" style="  height: 70px;">
<br/>
<div style="width: 30%; float:left; ">
    <h2 style=" color:  #ff6600; font-family: 'Century Gothic', Times, serif;" >Management Staff</h2>
</div>
<div style="width: 70%; float:left; text-align: right ">
     <a class="new_title" href="<?php echo base_url(); ?>index.php/staff"  >
                 <i class="fa fa-user fa-2x"></i>
                 <p>staff</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Admissions"  >
            <i class="fa fa-user-plus fa-2x" aria-hidden="true"></i>
            <p>Admissions</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/students/View"  >
            <i class="fa fa-child fa-2x" aria-hidden="true"></i>
                 <p>Students</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/parents"  >
           <i class="fa fa-male fa-2x" aria-hidden="true"></i>
                 <p>Parents</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/exams"  >
            <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                 <p>Exams</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/accounts"  >
            <i class="fa fa-money fa-2x" aria-hidden="true"></i>
                 <p>Fees</p>
        </a>  
    <?php  
               $a=$this->db->query("select type from institutes where id='".$this->session->userdata("staff_Org_id")."'  ")->row();
            $a =$a->type;
            if($a==1){
               ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/attendance/"  >
                <i class="fa fa-check fa-2x"></i>
                <p>Attendance</p>
            </a>
                   <?php 
            }else{
                ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/clzattendance/"  >
                <i class="fa fa-check fa-2x"></i>
                <p>Attendance</p>
            </a>
                    <?php
            }
            ?>

        <a class="new_title" href="<?php echo base_url(); ?>index.php/expenditure/history"  >
            <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                 <p>Expenses</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/salary"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
            <p>Salary</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Alerts"  >
            <i class="fa fa-comments-o fa-2x" aria-hidden="true"></i>
            <p>SMS</p>
        </a>
        
</div>
          </div>
       <?php
 }elseif($this->session->userdata("staff_level")==6){ 
     ?>
          <div class="box" style="  height: 70px;">
<br/>
<div style="width: 30%; float:left; ">
    <h2 style=" color:  #ff6600; font-family: 'Century Gothic', Times, serif;" >Administrative Staff</h2>
</div>
<div style="width: 70%; float:left; text-align: right ">
        <a class="new_title" href="<?php echo base_url(); ?>index.php/staff"  >
                 <i class="fa fa-user fa-2x"></i>
                 <p>staff</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Admissions"  >
            <i class="fa fa-user-plus fa-2x" aria-hidden="true"></i>
            <p>Admissions</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/students/View"  >
            <i class="fa fa-child fa-2x" aria-hidden="true"></i>
                 <p>Students</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/parents"  >
           <i class="fa fa-male fa-2x" aria-hidden="true"></i>
                 <p>Parents</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/staff/view_incharges"  >
            <i class="fa fa-user-secret fa-2x" aria-hidden="true"></i>
            <p>In-Charges</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/exams"  >
            <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                 <p>Exams</p>
        </a> 
        <?php  
               $a=$this->db->query("select type from institutes where id='".$this->session->userdata("staff_Org_id")."'  ")->row();
            $a =$a->type;
            if($a==1){
               ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/attendance/"  >
                <i class="fa fa-check fa-2x"></i>
                <p>Attendance</p>
            </a>
                   <?php 
            }else{
                ?><a class="new_title"  href="<?php echo base_url(); ?>index.php/clzattendance/"  >
                <i class="fa fa-check fa-2x"></i>
                <p>Attendance</p>
            </a>
                    <?php
            }
            ?> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/expenditure/history"  >
            <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                 <p>Expenses</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/salary"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
            <p>Salary</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Alerts"  >
            <i class="fa fa-comments-o fa-2x" aria-hidden="true"></i>
            <p>SMS</p>
        </a>
   
</div>
          </div>
       <?php
 }elseif($this->session->userdata("staff_level")==5){
        ?>
          <div class="box" style="  height: 70px;">
<br/>
<div style="width: 48%; float:left; ">
    <h2 style=" color:  #ff6600; font-family: 'Century Gothic', Times, serif;" >LIBRARY MANAGEMENT</h2>
</div>
<div style="width: 52%; float:left; text-align: right ">
     <a class="new_title" href="<?php echo base_url(); ?>index.php/library/books"  >
                 <i class="fa fa-book fa-2x"></i>
                 <p>Books</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/category"  >
            <i class="fa fa-object-group fa-2x" aria-hidden="true"></i>
                 <p>Categories</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/issue"  >
           <i class="fa fa-handshake-o fa-2x" aria-hidden="true"></i>
                 <p>Issue</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/submit"  >
            <i class="fa fa-reply-all fa-2x" aria-hidden="true"></i>
                 <p>Return</p>
        </a>   
        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/payments"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
                 <p>Payments</p>
        </a>

        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/search"  >
            <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                 <p>Search</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/library/view_request"  >
            <i class="fa fa-hand-paper-o fa-2x" aria-hidden="true"></i>    
            <p>Request</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/staff_notices"  >
            <i class="fa fa-globe fa-2x" aria-hidden="true"></i>    
            <p>Notifications</p>
        </a>
<!--        <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/staff_notices"  >
            <i class="fa fa-level-up fa-2x" aria-hidden="true"></i>
            <p style="font-size: 11.5px">Out Standing</p>
        </a>-->
    
</div>
          </div>
       <?php
    }elseif($this->session->userdata("staff_level")==3){
        ?>
          <div class="box" style="  height: 70px;">
<br/>
            <div style="width: 40%; float:left; ">
    <h2 style=" color:  #ff6600 ;font-family: 'Century Gothic', Times, serif;" >Accountants</h2>
</div>
<div style="width: 60%; float:left; text-align: right ">
        
        <a class="new_title" href="<?php echo base_url(); ?>index.php/accounts"  >
            <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
            <p style=" font-size: 12.8px;">Manage Fee</p>
        </a>   
        <a class="new_title" href="<?php echo base_url(); ?>index.php/accounts/add_record"  >
            <i class="fa fa-money fa-2x" aria-hidden="true"></i>
                 <p>Pay Fee</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/fee/manage_concessions"  >
            <i class="fa fa-minus-circle fa-2x" aria-hidden="true"></i>
                 <p>Concession</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/expenditure/history"  >
            <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                 <p>Expenses</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/salary"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
            <p>Salary</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/salary/pay_salary"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
            <p>pay Salary</p>
        </a>
        
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/fee_payment"  >
            <i class="fa fa-bus fa-2x" aria-hidden="true"></i>
            <p style="">Bus Fee</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/pay_fee"  >
            <i class="fa fa-building-o fa-2x" aria-hidden="true"></i>
            <p>Hostel Fee</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/staff_notices"  >
            <i class="fa fa-globe fa-2x" aria-hidden="true"></i>    
            <p>Notifications</p>
        </a>
</div>
          </div>
        <?php
    }elseif($this->session->userdata("staff_level")==1){
        ?>
                  <div class="box" style="  height: 70px;">
<br/>
<div style="width: 30%; float:left; ">
    <h2 style=' color:  #ff6600; font-family: "Century Gothic", Times, serif; '  >Faculty Member </h2>
</div>
<div style="width: 70%; float:left; text-align: right ">
     <a class="new_title" href="<?php echo base_url(); ?>index.php/staff"  >
                 <i class="fa fa-file-text fa-2x" aria-hidden="true"></i>
                 <p>Assignments</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/students/View"  >
            <i class="fa fa-check  fa-2x" aria-hidden="true"></i>
                 <p>Attendance</p>
        </a>   
        <a  class="new_title" href="<?php echo base_url(); ?>index.php/course/view_Staff_course"  >
                    <i class="fa fa-sitemap fa-2x "></i>
                    <p>Academics</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/timetable"  >
           <i class="fa fa-calendar fa-2x" aria-hidden="true"></i>
                 <p>Timetable</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/examination"  >
            <i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i>
                 <p>Exams</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/exam_reports"  >
            <i class="fa fa-font fa-2x" aria-hidden="true"></i>
                 <p>Results</p>
        </a>   
<!--        <a class="new_title" href="<?php echo base_url(); ?>index.php/accounts/add_record"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
                 <p>Pay Fee</p>
        </a>-->

        <a class="new_title" href="<?php echo base_url(); ?>index.php/expenditure/history"  >
            <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                 <p>Expenses</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/teachers/salary"  >
            <i class="fa fa-inr fa-2x" aria-hidden="true"></i>
            <p>Salary</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Alerts"  >
            <i class="fa fa-comments-o fa-2x" aria-hidden="true"></i>
            <p>SMS</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/staff_notices"  >
            <i class="fa fa-globe fa-2x" aria-hidden="true"></i>    
            <p>Notifications</p>
        </a>
<!--        <a class="new_title" href="<?php echo base_url(); ?>index.php/Notices/staff_notices"  >
            <i class="fa fa-level-up fa-2x" aria-hidden="true"></i>
            <p style="font-size: 11.5px">Out Standing</p>
        </a>-->
    
</div>
</div>
        <?php
    }   elseif($this->session->userdata("staff_level")==2){
        ?>
                            <div class="box" style="  height: 70px;">
<br/>
<div style="width: 45%; float:left; ">
    <h2 style=" color:  #ff6600; font-family: 'Century Gothic', Times, serif;" >Transport Department </h2>
</div>
<div style="width: 55%; float:left; text-align: right ">
     <a class="new_title" href="<?php echo base_url(); ?>index.php/transport/vehicles"  >
                 <i class="fa fa-bus fa-2x" aria-hidden="true"></i>
                 <p>Buses</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/drivers"  >
            <i class="fa fa-user  fa-2x" aria-hidden="true"></i>
                 <p>Drivers</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/routes"  >
           <i class="fa fa-road fa-2x" aria-hidden="true"></i>
                 <p>Routes</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/stud_details"  >
           <i class="fa fa-child fa-2x" aria-hidden="true"></i>
                 <p>Students</p>
        </a> 
     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/search"  >
            <i class="fa fa-usd fa-2x" aria-hidden="true"></i>
                 <p>Search</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/maintenance"  >
            <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
            <p>Maintenance</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Transport/fee_payment"  >
            <i class="fa fa-money fa-2x" aria-hidden="true"></i>
            <p>Fees</p>
        </a>
        <a  class="new_title" href="<?php echo base_url(); ?>index.php/login/logout"  >
                    <i class="fa fa-sign-out fa-2x "></i>
                     <p>Logout</p>
        </a>
    </div>
                            </div>
          
        <?php
       
    } elseif($this->session->userdata("staff_level")==4){
        ?>
                            <div class="box" style="  height: 70px;">
<br/>
<div style="width: 45%; float:left; ">
    <h2 style=" color:  #ff6600; font-family: 'Century Gothic', Times, serif;" >Hostel Department </h2>
</div>
<div style="width: 55%; float:left; text-align: right ">
     <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/Blocks"  >
                 <i class="fa fa-th-large fa-2x" aria-hidden="true"></i>
                 <p>Blocks</p>
        </a>     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/Rooms"  >
            <i class="fa fa-building  fa-2x" aria-hidden="true"></i>
                 <p>Rooms</p>
        </a>    
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/Fee_structure"  >
           <i class="fa fa-dollar fa-2x" aria-hidden="true"></i>
           <p style=" font-size: 11.7px;">Fee Structure</p>
        </a> 
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/Manage_students"  >
           <i class="fa fa-child fa-2x" aria-hidden="true"></i>
                 <p>Students</p>
        </a> 
     
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/search"  >
            <i class="fa fa-search fa-2x" aria-hidden="true"></i>
                 <p>Search</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/maintenance"  >
            <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
            <p>Maintenance</p>
        </a>
        <a class="new_title" href="<?php echo base_url(); ?>index.php/Hostel/pay_fee"  >
            <i class="fa fa-money fa-2x" aria-hidden="true"></i>
            <p>Fees</p>
        </a>
        <a  class="new_title" href="<?php echo base_url(); ?>index.php/login/logout"  >
                    <i class="fa fa-sign-out fa-2x "></i>
                     <p>Logout</p>
        </a>
    </div>
                            </div>
          
        <?php
       
    }  
    
    
 
 
?>         <br style=" clear: both; "/>
   
<div class="box" style=" min-height: 520px; margin-left:1%; margin-right: 1%;background:#FFFFFF;border-radius:3px;box-shadow:rgba(0, 0, 0, 0.05) 0 0 8px; padding: 1%; ">

         