<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php
 $query=$this->db->query("SELECT s.student_id,m.marks  FROM `marks` m RIGHT JOIN student s ON m.student_id=s.student_id WHERE s.section_id='".$section->sid."' AND m.exam_id='".$exam->id."'");
 $marks=array();
 $query=$query->result();
 foreach ($query as $value) {
     if($value->marks==-1){
         $value->marks="A";
     }
    $marks[$value->student_id]= $value->marks;
 }
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
                        <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a><i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Edit Marks Of <?php echo $exam->exam ?>,<?php echo $exam->subject ?></a>
                    </li> 
                </ul>
            </div>
              <?php
            if(strlen($this->session->userdata('marks_update_sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('marks_update_sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('marks_update_sucess');
            }
            ?>
            
            <div class="box ">
                <div class="box-title">
                    <h3 style=" color:  #66cc00">
                                <i class="fa fa-pencil-square-o"></i>
                                Exam Info
                       </h3>
                </div>
                <div class="box-content nopadding" >
                    <br/>
                    <div class="box">
                        <div class="col-sm-4 nopadding" >
                            <table  class="table table-bordered" style=" color:  teal">
                                <tr>
                                    <td>Class</td>
                                    <td><?php echo $section->class ?></td>
                                </tr>
                                <tr>
                                    <td>Section</td>
                                    <td><?php echo $section->section ?></td>
                                </tr>
                                <tr>
                                    <td>Students</td>
                                    <td><?php echo $section->students ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-8 nopadding">
                            <div class="box" style="padding-left: 10px" >
                                
                           
                            <table  class="table table-bordered" style=" color:  teal">
                                <tr>
                                    <td style=" text-align: center" colspan="2"><?php echo strtoupper($exam->subject) ?></td>
                                </tr>
                                <tr>
                                    <td>Date Of Exam :<?php echo date("d-m-Y" ,$exam->examdate) ?></td>
                                    <td>Timings :<?php echo date("H:i", $exam->starttime) ?> -- <?php echo  date("H:i",$exam->endtime) ?></td>
                                </tr>
                                <tr>
                                    <td>Total Marks : <?php echo $exam->maxmarks ?></td>
                                    <td>Cut-Off Marks : <?php echo $exam->minmarks ?></td>
                                </tr>
                            </table>
                                 </div>
                        </div>
                    </div>
                </div>
            </div>
                    
                    <?php
                    
            if(sizeof($this->form->errors)>0){
               ?><br/>
                    <h5 style=" text-align: center; color:  red">** Some Errors Occurred Please Check  </h5>
               <?php 
            }
                    ?>
            <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3><i class="fa fa-bar-chart-o"></i>
                              <?php echo $section->class ?>,<?php echo $section->section ?>  - <?php echo $exam->exam ?>,<?php echo $exam->subject ?> Mark's List
                        </h3>
                </div>
                <div class="box-content nopadding">
                    <form method="post" action="<?php echo base_url(); ?>index.php/exams/save_marks">
                        <input type="hidden" name="examid" value="<?php echo $exam->id ?>" />
                        <input type="hidden" name="maxmarks" value="<?php echo $exam->maxmarks ?>" />
                        <input type="hidden" name="minmarks" value="<?php echo $exam->minmarks ?>" />
                        <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                        
                  
                        <table class="table table-hover table-nomargin  table-bordered"  style="width: 100%;">
                                <thead>
                                <tr>
                                   
                                    <th>roll</th>
                                    <th>Student</th>
                                    <th>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                 
                                  $ids="";
                                    $students=$this->db->query("SELECT `student_id`,`name`,`roll` FROM `student` WHERE `section_id`='".$section->sid."' ");
                                    $students=$students->result(); 
                                    foreach ($students as $value) {
                                       $ids.=$value->student_id.",";
                                        ?>
                                        <tr>
                                           
                                            <td><?php echo $value->roll ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><input  style="width: 100%" type="text" class="form-control" name="marks_<?php echo $value->student_id ?>" value="<?php
                                              if(strlen($this->form->value('marks_'.$value->student_id))>0 ){
                                                echo $this->form->value('marks_'.$value->student_id);  
                                              }elseif(array_key_exists($value->student_id,$marks)){
                                                  echo $marks[$value->student_id];
                                              }?>" />
                                                <span style="color: red"><?php echo $this->form->error('marks_'.$value->student_id); ?></span>
                                             
                                                <input type="hidden" name="action_<?php echo $value->student_id ?>"  value="<?php
                                               if(array_key_exists($value->student_id,$marks)){
                                                   echo "update";
                                                 }else{
                                                     echo "insert";
                                                 }
                                             ?>"     />
                                             
                                            
                                            </td>
                                        </tr>
                                       <?php
                                     }
                                 $ids=  substr($ids, 0, strlen($ids)-1);
                                 
                                ?>
                            <input type="hidden" name="student_ids" value="<?php echo $ids ?>" />
                            
                                    
                            </tbody>
                        </table>
                        <br/><br/>  
                        <div class="col-sm-12"> 
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                <input type="submit"  name="submit" value="Save marks" class="btn btn-primary btn-block  " />  
                            </div>
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                           
                        </div>
                           <br/><br/>              
                    </form>
                </div>
        </div>
            
        </div>
    </div>
</div>
        

<?php
$this->load->view('structure/footer');
?>