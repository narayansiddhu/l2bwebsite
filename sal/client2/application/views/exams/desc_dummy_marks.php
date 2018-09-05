<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$ecids=$this->input->get('ecids');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box" >
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Manage Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url() ?>index.php/exams/view_settings/<?php echo $exam->id ?>">View exam Settings</a>
                       <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Add Marks</a>
                </li>
            </ul>
        </div>
           
            <div class="box">
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered">
                        <div class="box-title">
                            <h3 style=" color:  #66cc00"> <i class="fa fa-list"></i>&nbsp;Upload Marks List of <?php echo $exam->exam ?>  <?php echo $section->class." - ".$section->section ?></h3>
                        </div>
                        <div class="box-content nopadding">
                            <table class="table table-bordered "> 
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Subject</th>
                                        <th>Max Marks</th>
                                        <th>Min Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query= json_decode($_SESSION['exams_data']);$i=1;
                                    foreach($query as $e){
                                       ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $e->subject ?></td>
                                        <td><?php echo $e->maxmarks ?></td>
                                        <td><?php echo $e->minmarks ?></td>
                                    </tr>    
                                       <?php 
                                    }
                                   $query= json_encode($query);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
                <div class="col-sm-8 nopadding ">
                    <div class="box box-bordered" style=" padding-left: 10px;">
                        <div class="box-title">
                            <h3 style=" color:  #66cc00"> <i class="fa fa-plus"></i>&nbsp;Upload Mark Sheet</h3>
                        </div>
                        <div class="box-content nopadding">
                            <form class='form-horizontal form-bordered'  method="post" action="<?php echo base_url(); ?>index.php/exams/submit_desc_dummymarks" enctype="multipart/form-data" >
                                <table class="table table-bordered table-striped " style=" width: 100%" >
                                    <thead>
                                        <tr>
                                            <th>Roll-No</th>
                                            <th>Name</th>
                                            <?php
                                            $query = json_decode($query);
                                            foreach($query as $e){
                                              ?>
                                             <th><?php echo $e->subject ?></th>
                                              <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stud_ids="";
                                         $failed_records=$_SESSION['failed_records'];
                                         $i=1;
                                          $stud=$this->db->query("SELECT * from student where section_id='".$section->sid."'")->result();;
                                          foreach($stud as $s){
                                              if(isset($failed_records[$s->student_id])){
                                                  ?>
                                                <tr>
                                                    <td><?php echo $s->roll; ?></td>
                                                    <td><?php echo $s->name; ?></td>
                                                    <?php
                                                    foreach($query as $e){
                                                        if(isset($failed_records[$s->student_id][$e->subject])){
                                                            $stud_ids.=$s->student_id.",";
                                                            //print_r($failed_records[$s->student_id][$e->subject]);
                                                            ?>
                                                            <td>
                                                                <input type="text" name="marks_<?php echo $e->id ?>_<?php echo $s->student_id  ?>" value="<?php
                                                                        if(strlen($this->form->value("marks_".$e->id."_".$s->student_id))){
                                                                            echo $this->form->value("marks_".$e->id."_".$s->student_id);
                                                                        }else{
                                                                            if(strlen($this->form->value("submission"))==0){
                                                                                     echo $failed_records[$s->student_id][$e->subject]['Marks']; 
                                                                            }
                                                                        }
                                                                    ?>"  class="form-control" />
                                                                <span style=" color: red">
                                                                    <?php
                                                                        if(strlen($this->form->error("marks_".$e->id."_".$s->student_id))){
                                                                            echo $this->form->error("marks_".$e->id."_".$s->student_id);
                                                                        }else{
                                                                            if(strlen($this->form->value("submission"))==0){
                                                                                     echo $failed_records[$s->student_id][$e->subject]['reason']; 
                                                                            }
                                                                        }
                                                                    ?>
                                                                </span>
                                                            </td>
                                                            <?php
                                                        }else{
                                                         ?><td>--</td><?php
                                                        }
                                                      }
                                                    ?>
                                                </tr>    
                                             <?php  
                                              }
                                            
                                          }
                                          
                                        ?>
                                    </tbody>
                                </table>
                                <input type="hidden" name="exam" value="<?php echo $exam->id ?>" />
                                <input type="hidden" name='submission' value="submission" />
                                <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                                <input type="hidden" name="eid" value="<?php echo $_SESSION['ecids'] ?>" />
                                <input type="hidden" name="exams_details" value='<?php echo $_SESSION['exams_data'] ?>' />
                                <input type="hidden" name="student_ids" value='<?php echo $stud_ids ?>' />
                                
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="Upload_file" class="btn btn-primary" value="Upload Marks" />
                                </div>

                            </form>
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