<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    
    <div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        
                        <li>
                            <a href="">Self Attendance</a>
                        </li>
                    </ul>

            </div>
        
            <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Select Month </h3>
                            <div class="actions">
                                    <a href="#" class="btn btn-mini content-refresh">
                                            <i class="fa fa-refresh"></i>
                                    </a>
                                    <a href="#" class="btn btn-mini content-remove">
                                            <i class="fa fa-times"></i>
                                    </a>
                                    <a href="#" class="btn btn-mini content-slideUp">
                                            <i class="fa fa-angle-down"></i>
                                    </a>
                            </div>
                    </div>
                    <div class="box-content nopadding">
                        <form class='form-horizontal form-bordered' >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Month - Year</label>
                                <div class="col-sm-9">
                                    <div class="col-sm-6 ">
                                       <select class="select2-me" id="month" name="month"  style=" width: 90% "  >
                                        <option value="" >Select A Month</option>
                                        <?php
                                          for ($m=1; $m<=12; $m++) {
                                              ?> <option value="<?php echo $m ?>" ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                            }
                                            ?>
                                       </select> 
                                    </div>
                                    <div class="col-sm-6">
                                       <select class="select2-me" id="year" name="year"  style=" width: 90% "  >
                                        <option value="" >Select A Year</option>
                                        <?php
                                            $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                            $start=$start->row();
                                            $start=$start->timestamp;
                                            $start=getdate($start);
                                            $start=$start['year'];
                                            $now=getdate();
                                            $now=$now['year'];
                                            for($i=$start;$i<=$now;$i++){
                                              ?>
                                        <option value="<?php echo $i; ?>"  <?php
                                           if($i==$now){
                                               echo "selected";
                                           }?> ><?php echo $i; ?></option>
                                              <?php
                                            }
                                            
                                        ?>
                                       </select> 
                                    </div>
                                    
                                    <span id="new_date_err" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                                  ?>
                                    </span>  
                                </div>
                            </div> 
                            
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="submit" id="add"  name="" value="Fetch Attendance" class="btn btn-primary" />
                                <span id="errors" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                               ?>
                                    </span> 
                            </div>
                        </form>
                    </div>
            </div> 
    
    
    
             
        <?php
            $now=time();    
            if( ( isset($_GET['month']) && isset($_GET['year']) )){
              if( ( is_numeric($_GET['month']) && is_numeric($_GET['year']) ) ){
                  $now=   mktime(0,0,0,$_GET['month'],1,$_GET['year']);
              }
            }
            $time=  getdate($now);
            
            $from=mktime(0,0,0,$time['mon'],1,$time['year']);
            $to=mktime(0,0,0,$time['mon']+1,1,$time['year']);
            $query="SELECT d.day,s.status FROM staff_attendance_date d LEFT JOIN staff_attendance s ON s.date_id=d.id WHERE s.staff=2 AND ( d.day >= '".$from."' AND  d.day < '".$to."' ) ORDER BY d.day ASC ";
            $query=$this->db->query($query);
            $query=$query->result();
         ?>
        
            <div class="box box-color box-bordered nopadding" id="timetable"   >
                 <div class="box-title">
                         <h3>
                                 <i class="fa fa-bar-chart-o"></i>
                                 Attendance For <?php echo $time['month'] ." , " .$time['year']  ?>
                         </h3>
                         <div class="actions">
                                 <a href="#" class="btn btn-mini content-refresh">
                                         <i class="fa fa-refresh"></i>
                                 </a>
                                 <a href="#" class="btn btn-mini content-remove">
                                         <i class="fa fa-times"></i>
                                 </a>
                                 <a href="#" class="btn btn-mini content-slideUp">
                                         <i class="fa fa-angle-down"></i>
                                 </a>
                         </div>
                 </div>
                 <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">

                         <thead>
                             <tr>
                                 <th>S.no</th>
                                 <th>Date</th>
                                 <th>Status</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php 
                                $i=1;
                                $p=0;
                                foreach ($query as $value) {
                                  ?>
                             <tr>
                                 <td><?php echo $i++ ?></td>
                                 <td><?php echo date('d-m-Y' , $value->day) ?></td>
                                 <td><?php 
                                       switch($value->status){
                                         case 0 : echo "--";  break;
                                         case 1 : echo "present";$p++;  break;
                                         case 2: echo "Absent";  break;
                                       }
                                 ?></td>
                             </tr>
                                 <?php
                                }
                             
                                ?>
                             
                         </tbody>
                     </table>
                     
                     <div class='form-horizontal form-bordered' >
                         <div class="col-sm-12 form-group" style=" border-top: 1px solid #cccccc">
                            <div class="col-sm-4">
                                  <label for="textfield" class="control-label col-sm-3">Total</label>
                                   <label for="textfield" class="control-label col-sm-3"><?php echo $i=$i-1; ?></label>
                            </div>
                         <div class="col-sm-4">
                               <label for="textfield" class="control-label col-sm-3">Present</label>
                                <label for="textfield" class="control-label col-sm-3"><?php echo $p; ?></label>
                         </div>
                         <div class="col-sm-4">
                               <label for="textfield" class="control-label col-sm-3">Absent</label>
                                <label for="textfield" class="control-label col-sm-3"><?php echo $i-$p; ?></label>
                         </div>
                     </div>
                     </div>
                     
                 </div> 

            </div>
       
    
    
        </div>
    </div>
</div>
 

<?php
$this->load->view('structure/footer');
?>
