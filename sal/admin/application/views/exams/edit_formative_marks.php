<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');?>  
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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li> 
                <li>
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section->section ." - ".$section->class ?> </a>
                </li> 
            </ul>
        </div>         
            <br/>
            <div class="box" >
                <div class="col-sm-6 nopadding">
                    <table class="table table-bordered " >
                        <tr>
                            <th>Exam Name</th>
                            <td><?php echo $exam->exam ?></td>
                        </tr><tr>
                            <th>Class-Section</th>
                            <td><?php echo $section->class ." - ".$section->section ?></td>
                        </tr>
                        <tr>
                            <th>Exam Date</th>
                            <td><?php echo $section->class ." - ".$section->section ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6 nopadding">
                    <table class="table table-bordered " style="padding-left: 25px;" >
                        <tr>
                            <th>Subject</th>
                            <td><?php echo $exam_details->subject ?></td>
                        </tr>
                        <tr>
                            <th>Max Marks</th>
                            <td><?php echo $exam_details->maxmarks ?></td>
                        </tr>
                        <tr>
                            <th>Min Marks</th>
                            <td><?php echo $exam_details->minmarks ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
            $marks="SELECT * from formative_marks where exam_id='".$exam_details->id."' ";
             $marks=$this->db->query($marks)->result();
             $marks_array=array();
             foreach ($marks as $value) {
  $t=0;
        if($value->part_1==-1){
            $value->part_1="A";
        }else{
            $t+=$value->part_1;
        }
        if($value->part_2==-1){
            $value->part_2="A";
        }else{
            $t+=$value->part_2;
        }
        if($value->part_3==-1){
            $value->part_3="A";
        }else{
            $t+=$value->part_3;
        }
        if($value->part_4==-1){
            $value->part_4="A";
        }else{
            $t+=$value->part_4;
        }
 
           $marks_array[$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'markid'=>$value->fmid);
       
        }
         $students="SELECT s.student_id as id,s.name ,s.roll,s.userid  from student s where s.section_id='".$section->sid."' ";
         $students=$this->db->query($students)->result();
            ?>
            <div class="box box-color box-bordered" style=" clear: both;">
                <div class="box-title">
                    <h3>Edit Marks</h3>
                </div> 
                <div class="box-content nopadding">
                    <form  method="post" action="<?php echo base_url() ?>index.php/exams/save_formative_marks">
                      
                    <table class="table table-bordered " style=" width: 100%; "  >
                        <thead>
                            <tr>
                            <th>Roll.NO</th>
                            <th>Student Name</th>
                            <th>Part 1</th>
                            <th>Part 2</th>
                            <th>Part 3</th>
                            <th>Part 4</th>
                        </tr>
                        </thead>
                        <tbody>
                         <?php
                         $st_ids="";
                            foreach ($students as $value) {
                                $st_ids.=$value->id.",";
                                ?>
                            <tr>
                                <td><?php echo $value->roll ?></td>
                                <td><?php echo $value->name ?>
                                    <input  type="hidden" class="form-control" name='markid_<?php echo $value->id ?>' value="<?php 
                                            if(isset($marks_array[$value->id]['markid'])){
                                                   echo $marks_array[$value->id]['markid'];
                                              }?>"  />
                                </td>
                                <td><input  type="text" class="form-control" name='marks_1_<?php echo $value->id ?>' value="<?php 
                                            if(strlen($this->form->value('marks_1_'.$value->id))>0 ){
                                                echo $this->form->value('marks_1_'.$value->id);  
                                              }elseif(isset($marks_array[$value->id]['part1'])){
                                                  echo $marks_array[$value->id]['part1'];
                                              }?>"  />
                                    <span style=" color:  red; font-size: 10px"><?php echo $this->form->error('marks_1_'.$value->id) ?></span>
                                </td>
                                <td><input  type="text" class="form-control" name='marks_2_<?php echo $value->id ?>' value="<?php 
                                            if(strlen($this->form->value('marks_2_'.$value->id))>0 ){
                                                echo $this->form->value('marks_2_'.$value->id);  
                                              }elseif(isset($marks_array[$value->id]['part2'])){
                                                 echo $marks_array[$value->id]['part2'];
                                              }?>"  />
                                    <span style=" color:  red; font-size: 10px"><?php echo $this->form->error('marks_2_'.$value->id) ?></span>
                                </td>
                                <td><input  type="text" class="form-control" name='marks_3_<?php echo $value->id ?>' value="<?php 
                                            if(strlen($this->form->value('marks_3_'.$value->id))>0 ){
                                                echo $this->form->value('marks_3_'.$value->id);  
                                              }elseif(isset($marks_array[$value->id]['part3'])){
                                                   echo $marks_array[$value->id]['part3'];
                                              }?>"  />
                                    <span style=" color:  red; font-size: 10px"><?php echo $this->form->error('marks_3_'.$value->id) ?></span>
                                </td>
                                <td><input  type="text" class="form-control" name='marks_4_<?php echo $value->id ?>' value="<?php 
                                            if(strlen($this->form->value('marks_4_'.$value->id))>0 ){
                                                echo $this->form->value('marks_4_'.$value->id);  
                                              }elseif(isset($marks_array[$value->id]['part4'])){
                                                   echo $marks_array[$value->id]['part4'];
                                              }?>"  />
                                    <span style=" color:  red; font-size: 10px"><?php echo $this->form->error('marks_4_'.$value->id) ?></span>
                                </td>
                            </tr>
                                 <?php
                            }
                            $st_ids=  substr($st_ids, 0,  strlen($st_ids)-1);
                         ?>
                        <input type="hidden" name="students_ids" value="<?php echo $st_ids ?>" />
                        <input type="hidden" name="exam_ids" value="<?php echo $exam->id ?>" />
                        <input type="hidden" name="section_id" value="<?php echo $section->sid ?>" />
                        <input type="hidden" name="exam_details" value="<?php echo $exam_details->id ?>" />
            
                            <tr>
                                <td style=" text-align: center"  colspan="6"><button class="btn btn-primary" type="submit">Save Marks</button></td>
                            </tr>
                        </tbody>
                    </table>
                          
                    </form>
                </div>
            </div>   
            
                 
        </div>
    </div>
</div>
            
            
<?php
$this->load->view('structure/footer');
?>