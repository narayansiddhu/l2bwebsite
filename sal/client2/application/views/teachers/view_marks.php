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
    $marks[$value->student_id]= $value->marks;
 }
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-color box-bordered nopadding">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bar-chart-o"></i>
                              <?php echo $section->class ?>,<?php echo $section->section ?>  - <?php echo $exam->exam ?>,<?php echo $exam->subject ?> Mark's List
                        </h3>
                        <div class="actions">
                            <a href="<?php echo base_url() ?>/index.php/teachers/edit_marks/<?php echo $exam->id ?>/<?php echo $section->sid ?>" class="btn btn-mini ">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit
                                </a>                                
                        </div>
                </div>
                <div class="box-content nopadding">                   
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>roll</th>
                                    <th>Student</th>
                                    <th>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                  $i=1;
                                  $ids="";
                                    $students=$this->db->query("SELECT `student_id`,`name`,`roll` FROM `student` WHERE `section_id`='".$section->sid."' ");
                                    $students=$students->result(); 
                                    foreach ($students as $value) {
                                       $ids.=$value->student_id.",";
                                        ?>
                                        <tr>
                                           <td><?php echo $value->roll ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td> <?php
                                                    if(array_key_exists($value->student_id,$marks)){
                                                        echo $marks[$value->student_id];
                                                    }else{
                                                        echo "--";
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                       <?php
                                     }
                                 $ids=  substr($ids, 0, strlen($ids)-1);
                                 
                                ?>
                            <input type="hidden" name="student_ids" value="<?php echo $ids ?>" />
                            
                                    
                            </tbody>
                        </table>
                        
                    
                    
                </div>
        </div>
    </div>
</div>
        

<?php
$this->load->view('structure/footer');
?>