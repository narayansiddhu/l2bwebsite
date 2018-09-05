<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php   
$section="";
$section_err="";
$stat=FALSE;
$time=  getdate();
if( ( isset($_GET['month']) && isset($_GET['year']) && isset($_GET['section']) )){
  if( ( is_numeric($_GET['month']) && is_numeric($_GET['year']) && is_numeric($_GET['section']) ) ){
        $now=   mktime(0,0,0,$_GET['month'],1,$_GET['year']);
        $time=  getdate($now);
        $from=mktime(0,0,0,$time['mon'],1,$time['year']);
        $to=mktime(0,0,0,$time['mon']+1,1,$time['year']);   
        $stat=TRUE;
        $section=$_GET['section'];
  }else{
     $section_err="Please select section "; 
  }
}



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
                            <a href="">Attendance Reports</a>
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
                <?php 
                    $sections=array();
                    $query=$this->db->query("SELECT  DISTINCT(c.secid) ,s.name as section, cl.name as class FROM `course` c JOIN section s ON c.secid=s.sid JOIN class cl ON s.class_id=cl.id where tid='".$this->session->userdata('staff_id')."'");
                    $query=$query->result();
                    foreach($query as $value){
                          $sections[$value->secid]=array('id'=>$value->secid,'section'=>$value->section,'class'=>$value->class);       
                    }
                    $query=$this->db->query("SELECT s.sid,s.name as section ,cl.name as class FROM `section`  s  JOIN class cl ON s.class_id=cl.id WHERE cls_tch_id='".$this->session->userdata('staff_id')."'");
                    $query=$query->result();
                    foreach($query as $value){
                          $sections[$value->sid]=array('id'=>$value->sid,'section'=>$value->section,'class'=>$value->class);       
                    }
                    ?>
                    <div class="box-content nopadding">
                        <form class='form-horizontal form-bordered' >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Section</label>
                                <div class="col-sm-9">
                                    <select class="select2-me" id="section" name="section" onchange="fetch_exams();"   style=" width: 100% "  >
                                        <option value="" >Select A Section</option>
                                        <?php
                                          foreach ($sections as $value) {
                                              if($section==$value['id'])
                                                {
                                                             $section_details=$value['class']." - ".$value['section'];
                                                }
                                              ?><option value="<?php echo $value['id'] ?>"
                                                      <?php 
                                                           if($section==$value['id']){
                                                               echo "selected";
                                                           }
                                                        ?>                                                      
                                                      ><?php echo $value['class']." - ".$value['section'];  ?></option><?php 
                                          }                                            
                                        ?>
                                       </select>    
                                    
<!--                                    <select class="select2-me" id="section" name="section"  style=" width: 100% "  >
                                        <option value="" >Select A Section</option>
                                        <?php
                                          $query=$this->db->query("SELECT s.sid,s.name as section,c.name as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid=1")->result();   
                                        foreach($query as $r){
                                            if($section==$r->sid)
                                                {
                                                             $section_details=$r->class." - ".$r->section;
                                                }
                                              ?>
                                        <option value="<?php echo $r->sid ?>" <?php 
                                                           if($section==$r->sid){
                                                               echo "selected";
                                                           }
                                         ?> ><?php echo $r->class." - ".$r->section ?></option>
                                                  <?php
                                          }
                                          
                                        ?>
                                       </select> -->
                                    
                                    
                                    
                                    <span id="new_date_err" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                                  ?>
                                    </span>  
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Month - Year</label>
                                <div class="col-sm-9">
                                    <div class="col-sm-6 ">
                                       <select class="select2-me" id="month" name="month"  style=" width: 90% "  >
                                        <option value="" >Select A Month</option>
                                        <?php
                                          for ($m=1; $m<=12; $m++) {
                                              ?> <option value="<?php echo $m ?>"  <?php
                                                           if($time['mon']==$m){
                                                               echo "selected";
                                                           }
                                               ?> ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
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
                                           if($time['year']==$i){
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
                                                   echo $section_err;
                                               ?>
                                    </span> 
                            </div>
                        </form>
                    </div>
            </div> 
   
    <?php
         if($stat){
             ?>
             <div class="box box-color box-bordered nopadding" id="timetable"   >
                 <div class="box-title">
                         <h3>
                                 <i class="fa fa-bar-chart-o"></i>
                                Attendance Report For <?php echo $time['month'] ." , " .$time['year']  ?> 
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
        <div class="box-content nopadding" style=" overflow-x: auto ;  " >
         <?php
                 $att_month =$time['mon'];
               $att_year =$time['year'];
             
              $attendance_array = array();
              $q="SELECT group_concat(a.student) as students, ad.day, ad.slot FROM `attendance` a JOIN attendance_date ad ON a.date_id = ad.id  where ad.section = '".$section."' AND ( ad.day >='".mktime(0, 0, 0, $att_month, 1, $att_year)."' AND ad.day <'".mktime(0, 0, 0, ($att_month+1), 1, $att_year)."' ) GROUP BY ad.slot , ad.day ORDER BY ad.day ASC";
              $q=  $this->db->query($q);
              $q = $q->result();
              foreach($q as $value){
                  $students = explode(",",$value->students);
                  foreach($students as $stud){
                      $attendance_array[$value->day][$value->slot][$stud] = $stud;
                  }
                  
              }
              
              $stud = $this->db->query("SELECT * FROM student WHERE section_id = '".$section."' ");
              
              if($stud->num_rows()>0){
                  
              
              
              $stud = $stud->result();
             $k= $from =mktime(0, 0, 0, $att_month, 1, $att_year);
              $to =mktime(0, 0, 0, $att_month +1 , 1, $att_year);
              $k = getdate($k);
              
              ?>
                
                         <div class='form-horizontal form-bordered' style=" overflow: auto; width:100% "   >
                             <table class="table table-hover table-nomargin table-bordered">
                                 <thead>
                                     <tr>
                                         <th>Students</th>
                                         <?php
                                         
                                         foreach ($attendance_array as $this_day => $value) {    
                                               ?>
                                                <th colspan="2">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <th colspan="2" style=" text-align: center"><?php echo date("d-m-y",$this_day); ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th style=" text-align: center">AM</th>
                                                            <th style=" text-align: center">PM</th>
                                                        </tr>
                                                    </table>
                                                </th>
                                               <?php
                                           }
                                         ?>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php
                                       foreach($stud as $value ){
                                           ?>
                                            <tr>
                                                <td><?php echo $value->name ?></td>
                                                <?php
                                                      foreach ($attendance_array as $this_day => $vr) { 
                                                          ?>
                                                            <td style=" text-align: center"><?php
                                                              if(isset($attendance_array[$this_day][1][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td> 
                                                            <td style=" text-align: center"><?php
                                                              if(isset($attendance_array[$this_day][2][$value->student_id])){
                                                                  echo "A";
                                                              }else{
                                                                  echo "P";
                                                              }
                                                            
                                                            ?></td>        
                                                          <?php
                                                          $this_day =$this_day+86400;
                                                        }
                                                    ?>
                                            </tr>
                                          <?php
                                       }
                                     ?>
                                 </tbody>
                             </table>
                         </div>
                  
              <?php 
              }else{
                  ?>
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Attendance Report</h3> 
                    </div>
                    <div style="text-align: center;" class="box-content nopadding"> 
                        <strong >No Students Record Found</strong>
                    </div>
                  <?php
              }
                ?>
            
            
        </div>
    </div>
          <?php
         }
    ?>
        
        
    </div>
    </div>    
</div>

<?php
$this->load->view('structure/footer');
?>
