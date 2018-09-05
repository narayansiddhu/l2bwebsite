<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$action=$this->input->get("action");
?>   
<div class="row">
    <div class="col-sm-12">
        <div class="box" >
        
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/exams/">Manage Exam</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url() ?>>index.php/exams/view_settings/<?php echo $exam->id ?>">View exam Settings</a>
                    </li>
                </ul>
            </div>
            
            <?php
if(strlen($this->session->userdata('mark_entered_Sucess'))>0 ){
    ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('mark_entered_Sucess'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
           <?php
    $this->session->unset_userdata('mark_entered_Sucess');
}
?>  
                   <?php
if(strlen($this->session->userdata('marks_updated'))>0 ){
    ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('marks_updated'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
           <?php
    $this->session->unset_userdata('marks_updated');
}
?>  
    
            <div class="box">
                <br/>
                <div class="col-sm-6 nopadding">
                    <table class="table table-bordered table-striped" >
                        <tr>
                            <td>Exam Name</td>
                            <td><?php echo strtoupper($exam->exam) ?></td>
                        </tr>
                        <tr>
                            <td>Exam Type</td>
                            <td>Daily Test</td>
                        </tr>
                        <tr>
                            <td>Class-Section</td>
                            <td><?php echo $section_data->class." - ".$section_data->section ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 nopadding">
                    <table class="table table-bordered table-striped" >
                        <tr>
                            <td>Exam Date</td>
                            <td><?php echo date("d-m-Y",$section_data->examdate); ?></td>
                        </tr>
                        <tr>
                            <td>Exam Timmings</td>
                            <td><?php echo date("H:i",$section_data->starttime); ?> -- <?php echo date("H:i",$section_data->endtime); ?> </td>
                        </tr>
                        <tr>
                            <td>Maxmarks-Minmarks</td>
                            <td><?php echo $section_data->maxmarks." - ".$section_data->minmarks ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php
          
              if($activity=="edit"){
                  $marks="SELECT m.*,s.name ,s.userid,s.roll,s.admission_no FROM student s LEFT JOIN `grandtest_marks` m  ON m.student_id=s.student_id WHERE m.exam_id='".$section_data->id."' ORDER BY s.roll ASC";
                $marks=$this->db->query($marks)->result();
                ?>
                <div class="box box-bordered box-color" style=" clear: both;" >
                    <div class="box-title">
                        <h3><i class="fa fa-list"></i>Edit Marks List</h3>  
                    </div>
                    <div class="box-content nopadding"> 
                         <form enctype="multipart/form-data" class="form-horizontal form-bordered" action="<?php echo base_url(); ?>index.php/exams/save_daily_exammarks" method="post" >
                   <input type="hidden" name="section" value="<?php echo $section_data->sid; ?>" />
                   <input type="hidden" name="maxmarks" value="<?php echo $section_data->maxmarks; ?>" />
                   <input type="hidden" name="exam_id" value="<?php echo $exam->id; ?>" />
                   <input type="hidden" name="mcid" value="<?php echo  $section_data->id; ?>" />
                 
                        <table  class="table table-bordered table-striped"  >
                            <thead>
                            <tr>
                                <th>Roll</th>
                                <th>Student Name</th>
                                <th>Userid</th>
                                <th>Admission No</th>
                                <th>Marks</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $students_ids="";
                                    foreach ($marks as $value) {
                                        $students_ids.=$value->student_id.",";
                                     ?>
                                <tr>
                                    <td><?php echo $value->roll ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->userid ?></td>
                                    <td><?php echo $value->admission_no ?></td>
                                    <td>
                                        <input type="hidden" name="markid_<?php echo $value->student_id ?>" value="<?php echo $value->	mark_id ?>" />
                                        <input placeholder="Enter <?php echo ucfirst($value->name) ?> Marks" class="form-control" type="text" name="student_<?php echo $value->student_id ?>"
                                               value="<?php 
                                               if(strlen($this->form->value("student_".$value->student_id))>0){
                                                   echo $this->form->value("student_".$value->student_id);
                                               }else {
                                                   if($value->marks==-1){
                                                        echo "A";
                                                    }else{
                                                    echo $value->marks ;
                                                    }
                                               }
                                               ?>" />
                                        
                                        <span style=" color: red;"><?php echo $this->form->error("student_".$value->student_id) ?></span>
                                       </td>
                                </tr>    
                                     <?php                
                                    }
                                    $students_ids= substr($students_ids, 0, strlen($students_ids)-1);
                                    
                                ?>
                            <input type="hidden" name="students_ids" value="<?php echo $students_ids ?>" />
                                <tr>
                                    <td colspan="5" style=" text-align: center" ><input type="submit" class="btn btn-primary"  name="submit" value="Update Marks List " /> </td>
                                </tr>
                            </tbody>
                        </table>
                         </form>
                    </div>
                </div>
                    
                <?php
              }else{
                  $marks="SELECT m.*,s.name ,s.userid,s.roll,s.admission_no FROM  `grandtest_marks` m JOIN  student s  ON m.student_id=s.student_id  WHERE `exam_id` ='".$section_data->id."' ORDER BY s.roll ASC  "; 
            $marks=$this->db->query($marks);
             
            if($marks->num_rows()==0){
                
                if($action=="upload_sheet"){
                    ?>
            <div class="box">
                <div class="col-sm-3 nopadding">
                    &nbsp;
                </div>
                <div class="col-sm-6 nopadding">
                  <?php 
                // print_r($section_data); 
                  ?>  
            <div class="box box-bordered box-color" style=" clear: both;" >
            <div class="box-title">
                <h3><i class="fa fa-th-list"></i>Upload Marks Sheet</h3>                  
            </div>
            <div class="box-content nopadding">   
                <form enctype="multipart/form-data" class="form-horizontal form-bordered" action="<?php echo base_url(); ?>index.php/exams/upload_daily_exammarks" method="post" >
                   <input type="hidden" name="section" value="<?php echo $section_data->sid; ?>" />
                   <input type="hidden" name="maxmarks" value="<?php echo $section_data->maxmarks; ?>" />
                   <input type="hidden" name="exam_id" value="<?php echo $exam->id; ?>" />
                   <input type="hidden" name="mcid" value="<?php echo  $section_data->id; ?>" />
                    <div class="form-group">
                    <label for="textfield" class="control-label col-sm-2">Marks Sheet</label>
                    <div class="col-sm-10">
                        <input name="sheet" type="file" class="form-control" />
                        <span id="subject_err" style=" color: red">
                                <?php
                                   echo $this->form->error('section');   
                                ?>
                        </span>    
                    </div>
                </div>

                    <div style=" text-align: center ; padding-top: 10px ; margin-bottom: 10px; padding-left: 25%" class="col-sm-2 "  >
                        <button   type="submit" class="btn btn-primary"  >Upload Marks Sheet</button>
                    </div>
                    
            </form>  
            </div>
            </div>
                </div>
                <div class="col-sm-3 nopadding">
                    &nbsp;
                </div>
            </div>
              
                    <?php
                }else{?>
            <a onclick="download_sheet();" class="btn btn-block btn-primary"  style=" clear: both; text-align: center; " rel="tooltip" title="" data-original-title="Download Marks Sheet" >Download Marks Sheet</a>    
            <script>
                function download_sheet(){
                        window.open('<?php echo base_url() ?>index.php/exams/downnload_grandtest_sheet/?exam_id=<?php echo $exam->id ?>&section_id=<?php echo $section_data->sid ?>');
                        window.location.href="<?php echo base_url() ?>index.php/exams/view_daily_exammarks/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>?action=upload_sheet" ;
                    } 

              </script> 

               <?php
                }
            }else{
                $marks="SELECT m.*,s.name ,s.userid,s.roll,s.admission_no FROM student s LEFT JOIN `grandtest_marks` m  ON m.student_id=s.student_id WHERE m.exam_id='".$section_data->id."' ORDER BY s.roll ASC";
                $marks=$this->db->query($marks)->result();
                $marks_rank=array();
                foreach($marks as $value){
                    if($value->marks!=-1){
                      if(!isset($marks_rank[$value->marks])){
                          $marks_rank[$value->marks]=$value->marks;
                      }
                   }
                }
                $marks_rank=array_unique($marks_rank); krsort($marks_rank);
                 function find_pos($arr ,$val){
                        $i=1;
                        foreach ($arr as $value) {
                            if($value==$val){
                                return $i;
                            }else{
                                $i++;
                            }
                        }
                    }
               
                ?>
                <div class="box box-bordered box-color" style=" clear: both;" >
                    <div class="box-title">
                        <h3><i class="fa fa-list"></i>Marks List</h3>     
                        <div class="actions">
                             <a href="<?php echo base_url() ?>index.php/exams/edit_daily_exammarks/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>" class="btn btn-primary" rel="tooltip" title="" data-original-title="Edit Marks"  ><i class="fa fa-pencil-square-o"></i>&nbsp;Edit</a>
                             <a target="_blank" href="<?php echo base_url() ?>index.php/exams/print_daily_exammarks/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>" class="btn btn-primary" rel="tooltip" title="" data-original-title="Print Marks Sheet"  ><i class="fa fa-print"></i>&nbsp;Print</a>
                             <a href="<?php echo base_url() ?>index.php/exams/send_daily_exammarks/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>" class="btn btn-primary" rel="tooltip" title="" data-original-title="Send Results"  ><i class="fa fa-comments-o"></i>&nbsp;Send SMS</a>
                        </div>
                    </div>
                    <div class="box-content nopadding"> 
                        <table  class="table datatable table-bordered table-striped" >
                            <thead>
                            <tr>
                                <th>Roll</th>
                                <th>Student Name</th>
                                <th>Userid</th>
                                <th>Admission No</th>
                                <th>Marks</th>
                                <th>Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($marks as $value) {
                                     ?>
                                <tr>
                                    <td><?php echo $value->roll ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->userid ?></td>
                                    <td><?php echo $value->admission_no ?></td>
                                    <td><?php 
                                    if($value->marks==-1){
                                        echo "Absent";
                                    }else{
                                    echo $value->marks ;
                                    }
                                     ?></td>
                                    <td><?php 
                                    if($value->marks==-1){
                                        echo "--";
                                    }else{
                                        echo find_pos($marks_rank,$value->marks);
                                    }
                                     ?></td>
                                </tr>    
                                     <?php                
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    
                <?php
                
            }
              }
            ?>
            
            
            
            <?php
      
            ?>
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>