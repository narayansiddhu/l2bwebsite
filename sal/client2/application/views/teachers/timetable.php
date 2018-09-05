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
                            <a href="">View Timetable</a>
                        </li>
                    </ul>

            </div>
        
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Section Time Table</h3>
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
                        <div class='form-horizontal form-bordered' >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Section</label>
                                <div class="col-sm-10">
                                    <select class="select2-me" id="section" name="section"  style=" width: 100% "  >
                                        <option value="" >Select A section</option>
                                        <?php
                                            $course=$this->db->query("SELECT DISTINCT( s.sid), s.name as section ,c.name as class FROM course cr JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id  where cr.tid='".$this->session->userdata('staff_id')."'");
                                            $course=$course->result();
                                            foreach($course as $value){
                                                ?>
                                             <option value="<?php  echo $value->sid ?>"><?php echo $value->class ." , ".$value->section ?></option>
                                              <?php
                                            }
                                        ?>
                                    </select>
                                    <span id="new_date_err" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                                  ?>
                                    </span>  
                                </div>
                            </div> 
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="button" id="add" onclick="fetch_scheddule();" name="add" value="Fetch Timetable" class="btn btn-primary" />
                                <span id="errors" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                               ?>
                                    </span> 
                            </div>
                        </div>
                    </div>
            </div>
        </div> 
        
        <div class="col-sm-6">
            <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Select Date</h3>
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
                                <label for="textfield" class="control-label col-sm-2">Day</label>
                                <div class="col-sm-10">
                                    <input type="text" id="date" name="date" placeholder="Place Select date" class="form-control datepick" value="<?php echo $this->form->value('stdob') ?>">
                                </div>
                            </div> 
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="submit" id="add"  name="" value="Fetch Timetable" class="btn btn-primary" />
                                <span id="errors" style=" color: red">
                                               <?php
                                                   echo $this->form->error('stdob');
                                               ?>
                                    </span> 
                            </div>
                        </form>
                    </div>
            </div>
        </div> 
        
        
    </div>
    <script> 
       function fetch_scheddule(){
           $('#new_date_err').html("");
          var section=$('#section').val();
          if(section.length==0){
              $('#new_date_err').html("Please select a Section");
          }else{
                  setState('timetable','<?php echo base_url() ?>index.php/teachers/fetch_timetable','section='+section);
          }
       }  
    </script>
    
    
    <div class="box" id="scheduletiming"  >
       
        <?php

         $t=time();
         if( isset($_GET['date'])){
             $t= explode('/', urldecode($_GET['date'])) ;
             $t= mktime(0, 0, 0, $t[1], $t[0], $t[2]);
          }
         $time=  getdate($t);
         $query=$this->db->query("SELECT t.time_start,t.time_end,t.day,s.name as section,c.name as cls_name,sub.subject FROM class_routine t JOIN course cr ON t.course_id=cr.cid JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid WHERE cr.tid='".$this->session->userdata('staff_id')."' AND t.day='".$time['wday']."' ORDER BY  t.time_start ASC ");
         $query=$query->result();

         ?>
            <div class="box box-color box-bordered nopadding" id="timetable"   >
                 <div class="box-title">
                         <h3>
                                 <i class="fa fa-bar-chart-o"></i>
                                 Time Table ON <?php echo date('d-m-Y',$t); ?>
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
                                 <th>Class</th>
                                 <th>Section</th>
                                 <th>Subject</th>
                                 <th>From</th>
                                 <th>to</th>
                             </tr>
                         </thead>
                         <tbody>
                         <?php
                         $i=1;
                             foreach ($query as $value) {
                              ?>
                             <tr>
                                 <td><?php echo $i++; ?></td>
                                 <td><?php echo $value->cls_name ?></td>
                                 <td><?php echo $value->section ?></td>
                                 <td><?php echo $value->subject ?></td>
                                 <td><?php echo substr($value->time_start, 0, strlen($value->time_start)-2).":".substr($value->time_start, strlen($value->time_start)-2,strlen($value->time_start) ) ?></td>
                                 <td><?php echo substr($value->time_end, 0, strlen($value->time_end)-2).":".substr($value->time_end, strlen($value->time_end)-2,strlen($value->time_end) ) ?></td>

                             </tr>
                              <?php
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

<?php
$this->load->view('structure/footer');
?>