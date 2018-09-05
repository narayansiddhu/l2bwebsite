<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box ">
            
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        
                        <li>
                            <a href="">View Examinations</a>
                        </li>
                    </ul>

            </div>
            
            <?php
                if(strlen($this->session->userdata('marks_update_sucess'))>0 ){
                    ?>
                            <br/>
                            <div id="successMsg" class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert">×</button>
                              <?php echo $this->session->userdata('marks_update_sucess'); ?>
                             </div>
                            <script>
                                     $("#successMsg").fadeIn();
                                     $("#successMsg").delay(2000).fadeOut();
                                </script>
                   <?php
                    $this->session->unset_userdata('marks_update_sucess');
                }
            ?>   
      
                                <?php
        $course=$this->db->query("SELECT GROUP_CONCAT(cid) as course FROM `course` where tid='".$this->session->userdata('staff_id')."'");
         $course=$course->row();
         
         $course=$course->course;
         if(strlen($course)!=0){
             $query=$this->db->query("SELECT e.id as examid ,ex.exam,sub.subject,cl.name as class,se.sid as secid,se.name as section,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.minmarks ,(select count(*) from marks ms where ms.exam_id = e.id ) as counter FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examinations ex ON e.examid=ex.id JOIN examination_cls ec ON e.ecid=ec.id JOIN section se ON ec.sectionid = se.sid JOIN class cl ON se.class_id=cl.id where courseid in (".$course.")");
             $query=$query->result();
             ?>
                <div class="box box-color box-bordered">
                    
                    <div class="box-title">
                            <h3>
                                    <i class="fa fa-bar-chart-o"></i>
                                    Examination  Schedule
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
                                    <th >S.no</th>
                                    <th>Exam</th>
                                    <th>Section</th>
                                    <th>subject</th>
                                    <th>Exam date</th>
                                    <th>Time</th>
                                    <th>max marks</th>
                                    <th>Min Marks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i=1;
                                         foreach ($query as $value) {
                                           //  print_r($value);
                                           ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value->exam ?></td>
                                                <td><?php echo $value->class."<br/>". $value->section ?></td>
                                                <td><?php echo $value->subject ?></td>
                                                <td><?php 
                                                          if($value->examdate==0){
                                                              echo "--";
                                                          }else{
                                                           echo date('m-d-y',$value->examdate); 
                                                          }?>
                                                </td>
                                                <td><?php if($value->examdate==0){
                                                              echo "--";
                                                          }else{
                                                              echo date('H:i',$value->starttime) ;
                                                          } ?></td>

                                                <td><?php 
                                                     if($value->examdate==0){
                                                              echo "--";
                                                          }else{
                                                              echo $value->maxmarks ;
                                                          }
                                                    ?></td>
                                                <td><?php 
                                                if($value->examdate==0){
                                                              echo "--";
                                                          }else{
                                                              echo $value->minmarks;
                                                          }
                                                 ?></td>
                                                <td>
                                                    <?php
                                                    if($value->counter==0){
                                                        ?>
                                                    <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/enter_marks/<?php echo $value->examid  ?>/<?php echo $value->secid  ?>">  <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                       <?php
                                                    }else{

        //                                            if($value->starttime > time()){
        //                                                      ?>
                                                    <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/view_marks/<?php echo $value->examid  ?>/<?php echo $value->secid  ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    <a class="btn btn-mini" href="<?php echo base_url(); ?>index.php/teachers/edit_marks/<?php echo $value->examid  ?>/<?php echo $value->secid  ?>">  <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                            <?php
        //                                            }  else{
        //                                                echo "__";
        //                                            }   
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                          <?php
                                         }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
             <?php
         }else{
             ?>
                <div class="box box-color box-bordered">

                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-bar-chart-o"></i>
                                        Examination  Schedule
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
                            <table class="table table-hover table-nomargin  table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">

                                 <thead>
                                     <tr>
                                         <th >S.no</th>
                                         <th>Exam</th>
                                         <th>Section</th>
                                         <th>subject</th>
                                         <th>Exam date</th>
                                         <th>Time</th>
                                         <th>max marks</th>
                                         <th>Min Marks</th>
                                         <th>Actions</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td style=" text-align: center" colspan="9">No Records To display</td>
                                     </tr>
                                 </tbody>
                            </table>
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