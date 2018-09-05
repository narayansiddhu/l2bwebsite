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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a>
                </li> 
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/view">View results Of <?php echo $exam->exam ?> </a>
                </li> 
            </ul>
        </div>
          
       
        <div class="box box-bordered box-color ">
            <div class="box-title">
                <h3><i class="fa fa-file-text-o" aria-hidden="true"></i>Results Of <?php echo strtoupper( $exam->exam) ?> , <?php echo $section->class." - ".$section->section ?> , <?php echo $mc_exam->subject ?> </h3>
            </div>
            <div class="box-content nopadding" style=" max-height: 550px;" > 
                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exams/save_mc_marks" method="post" enctype="multipart/form-data"  >
                    <input type="hidden" name="section" value="<?php echo $section->sid ?>"     />  
                    <input type="hidden" name="exam" value="<?php echo $exam->id ?>"     />  
                    <input type="hidden" name="sec_val" value="<?php echo $section->sid."-".$section->id ?>"     />  
                    <input type="hidden" name="mcid" value="<?php echo $mc_exam->id ?>"     />  
                    <input type="hidden" name="questions" value="<?php echo $mc_exam->questions ?>"     />  
                    <input type="hidden" name="maxmarks" value="<?php echo $mc_exam->maxmarks ?>"     />  
                    <div style=" max-height: 450px; overflow-y: scroll">
                        
                    <table style=" height:450px ; overflow: scroll "  class="table table-bordered table-striped"  >
                    <thead style=" position: relative;">
                        <tr>
                            <th>Roll</th>
                            <th>Student</th>
                            <th>Correct</th>
                            <th>Wrong</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody >
                       <?php
                       $students_id="";
                       foreach ($marks as $value) {
                           $students_id.=$value->student_id.",";
                           ?>
                        <tr>
                    <input  type="hidden" value="<?php echo $value->mark_id;
                            ?>"  name="mark_id_<?php echo $value->student_id  ?>" />
                            <td><?php echo $value->roll ?></td>
                            <td><?php echo $value->name ?></td>
                            <td><input class="form-control" type="text" value="<?php
                            if($value->marks==-1){
                                echo "A";
                            }else{
                                echo $value->correct;
                            }
                             ?>"  name="correct_<?php echo $value->student_id  ?>" />
                                <span style=" color: red"><?php echo $this->form->error("correct_".$value->student_id); ?></span>
                            </td>
                            <td><input class="form-control" type="text" value="<?php
                            if($value->marks==-1){
                                echo "A";
                            }else{
                                echo $value->wrong ;
                            }
                            ?>"  name="wrong_<?php echo $value->student_id  ?>" />
                                <span style=" color: red"><?php echo $this->form->error("correct_".$value->student_id); ?></span>
                            
                            </td>
                            <td><input class="form-control" type="text" value="<?php
                                if($value->marks==-1){
                                echo "A";
                            }else{
                                echo $value->marks ;
                            }?>"  name="marks_<?php echo $value->student_id  ?>" />
                                <span style=" color: red"><?php echo $this->form->error("correct_".$value->student_id); ?></span>
                            
                            </td>
                            
                        </tr>
                           <?php
                       }
                       ?>
                    </tbody>                    
                </table>
                    </div>
                    <div style=" text-align: center"><br/>
                        <input type="hidden" name="student_id" value="<?php echo $students_id ?>" />
                        <input type="submit" name="submit" value="Save_marks"  class="btn btn-primary"/><br/><br/>
                    </div>
                </form>
           </div>
        </div>
       
        </div>
    </div>
</div>
        
            
            
<?php
$this->load->view('structure/footer');
?>
