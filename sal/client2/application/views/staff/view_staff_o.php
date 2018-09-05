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
                    <a href="<?php echo base_url(); ?>index.php/staff/View_staff">Manage Staff</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href=""><?php echo $staff_details->name; ?> Details</a>
                </li>
            </ul>

    </div> 
    
    <br/>
    <?php
      if(strlen($this->session->userdata('staff_details_update'))>0 ){
    ?>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('staff_details_update'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('staff_details_update');
}
    ?>
    <div class="box" style=" height: 500px;">
        <div class="col-sm-3" >
            <div class="box  box-bordered" style=" border-top: 1px solid  #cccccc; ">
                
                <div class="box-content nopadding">
            <div style=" text-align: center; padding-top: 10px;">
                <?php
              if(strlen($staff_details->img)==0){
                 ?>
            <i class="fa fa-user-secret fa-5x" aria-hidden="true" style=" width: 100px; height: 100px;"  ></i>
            <?php
              }else{
                  ?>
                 <img src="<?php echo assets_path  ?>/uploads/<?php  echo $staff_details->img  ?>" alt="..." style=" width: 100px; height: 100px;"   >
                 <?php
              }
              ?>
            </div>
            
             <hr/>
             <h3 style=" text-align: center; color: #ff9900"><u><?php echo $staff_details->name; ?></u></h3>
             <table class="table table-hover table-nomargin table-bordered">
                
                <tr>
                   <td><i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $staff_details->email; ?></td>
                </tr>
                <tr>
                   <td><i class="fa fa-key"></i>&nbsp;&nbsp;<?php echo $staff_details->password; ?></td>
                </tr>
                <tr>
                   <td><i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $staff_details->phone; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-suitcase"></i>&nbsp;&nbsp;<?php echo $staff_details->qualification; ?></td>
                </tr>
                <tr>
                    <td>D.O.B :<?php
                    echo date("d/m/Y",$staff_details->dob);
                     ?></td>
                </tr>
                <tr>
                   <td>D.O.J :<?php
                   echo date("d/m/Y",$staff_details->doj);
                    ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-sign-in"></i>&nbsp;&nbsp;<?php echo date("d-m-y H:i",$staff_details->last_login);  ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-inr"></i>&nbsp;&nbsp;<?php echo $staff_details->sal_amount; ?></td>
                </tr>
                
        </table>
                </div>
            </div>
        </div>
        <div class="col-sm-9 " style=" padding-top: 0px;">
            <div class="box box-color  box-bordered " style=" margin: 0px">
                <div class="box-title">
                    <h3><i class="fa fa-user"></i> &nbsp;&nbsp;
                                <?php echo $staff_details->name; ?> Details
                        </h3>
                        <ul class="tabs">
                                <li class="active">
                                        <a href="#t7" data-toggle="tab">Course</a>
                                </li>
                                <li>
                                        <a href="#t8" data-toggle="tab">Timetable</a>
                                </li>
                                <li>
                                        <a href="#t9" data-toggle="tab">Salary</a>
                                </li>
                                <?php
                                  if($staff_details->status==0){
                                            ?>
                                            
                                            <?php 
                                        }else{ ?>
                                <li>
                                    <a href="<?php echo base_url() ?>index.php/staff/edit/<?php echo $staff_details->id ?>" >Edit</a>
                                </li>
                                <?php } ?>
                        </ul>
                    
                </div>
                <div class="box-content nopadding">
                        <div class="tab-content">
                                <div class="tab-pane active" id="t7">
                                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Class - Section</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              $query=$this->db->query("SELECT se.name as sec_name ,su.subject,cl.name as class_name FROM `course` c JOIN section se ON c.secid=se.sid JOIN subjects as su ON c.subid=su.sid  JOIN class cl ON se.class_id =cl.id WHERE tid='".$staff_details->id."'");
                              if($query->num_rows()>0){
                                    $query=$query->result();$i=1;
                                    foreach($query as $value){
                                        ?>
                                          <tr>
                                              <td><?php echo $i++; ?></td>
                                              <td><?php 
                                                       echo $value->class_name ." - " .$value->sec_name 
                                                    ?></td>
                                              <td><?php
                                                  echo $value->subject;
                                                 ?></td>
                                            
                                          </tr>
                                     <?php
                                    }
                              }
                              ?>
                        </tbody>
                        
                    </table> 
                                   
                                </div>
                                <div class="tab-pane" id="t8">
                                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Day</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Class - Section</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT t.time_start,t.time_end,t.day,s.name as section,c.name as cls_name,sub.subject FROM class_routine t JOIN course cr ON t.course_id=cr.cid JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid WHERE cr.tid='".$staff_details->id."' ORDER BY t.day ASC,  t.time_start ASC ");
                                      if($query->num_rows()>0){
                                            $query=$query->result();$i=1;
                                            foreach($query as $value){
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php
                                                             $Week_days = unserialize (Week_days);
                                                             echo $Week_days[$value->day];
                                                      ?></td>
                                                      <td><?php echo substr($value->time_start, 0, strlen($value->time_start)-2).":".substr($value->time_start, strlen($value->time_start)-2,strlen($value->time_start) ); ?></td>
                                                      <td><?php echo substr($value->time_end, 0, strlen($value->time_end)-2).":".substr($value->time_end, strlen($value->time_end)-2,strlen($value->time_end) ); ?></td>
                                                      <td><?php echo $value->cls_name ." - ".$value->section ?></td>
                                                      <td><?php echo $value->subject ?></td>
                                                  </tr>
                                             <?php
                                            }
                                      }
                                      ?>
                                </tbody>
                            </table> 
                                </div>
                                <div class="tab-pane" id="t9">
                                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Month</th>
                                        <th>Paid On</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT p.amount,m.month,m.paid_on FROM  salary_paid p JOIN salary_month m ON p.month_id=m.id  WHERE p.staff='".$staff_details->id."'");
                                      if($query->num_rows()>0){
                                            $query=$query->result();$i=1;
                                            foreach($query as $value){
                                                ?>
                                                  <tr>
                                                      <td><?php echo $i++; ?></td>
                                                      <td><?php 
                                                                 $month=getdate($value->month);
                                                                 echo $month['month'].",".$month['year'];
                                                            ?></td>
                                                      <td><?php
                                                          echo date('d-m-Y',$value->paid_on);
                                                         ?></td>
                                                      <td><?php echo $value->amount  ?></td>
                                                  </tr>
                                             <?php
                                            }
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
    
    
    <script>
        function profile(){
            setState('result_holder','<?php echo base_url() ?>index.php/staff/ajax_profile','staff=<?php echo $staff_details->id; ?>');
         }
        function salary(){
            setState('result_holder','<?php echo base_url() ?>index.php/staff/ajax_salary','staff=<?php echo $staff_details->id; ?>');
         }  
       
         function course(){
               setState('result_holder','<?php echo base_url() ?>index.php/staff/ajax_course','staff=<?php echo $staff_details->id; ?>');
         }
         function timetable(){
               setState('result_holder','<?php echo base_url() ?>index.php/staff/ajax_timetable','staff=<?php echo $staff_details->id; ?>');
         }
         
    </script>
    
     </div>
    </div>      
</div>
<?php
$this->load->view('structure/footer');
?>