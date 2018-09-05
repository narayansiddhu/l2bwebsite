<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$mcid= $this->input->get("mcid");
$subject= $this->input->get("subject");
$upload_sheet=0;
if((strlen($mcid)!=0)&&(strlen($subject)!=0) ){
   $ecid= $mcid=explode(',',$mcid);
    if(sizeof($mcid)==2){
     // $ecid = explode(",",$mcid);
      $section = $ecid[1];
      $ecid =$ecid[0];
      $section_details=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from mcexam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND ec.id='".$ecid."'  ORDER BY c.numeric_val DESC");
      if($section_details->num_rows()>0){
          $section_details =$section_details->row();
          $sub_details =$this->db->query("SELECT e.id,e.subid,e.starttime,e.endtime,e.maxmarks,e.questions,s.subject, (select count(*) from mcmarks m where m.exam_id=e.id) as marks FROM mcexam e JOIN subjects s ON e.subid=s.sid where e.ecid= '".$ecid."' and e.examid= '".$exam->id."' and e.id= '".$subject."' ");
        if($sub_details->num_rows()>0){
             $sub_details =$sub_details->row();
             $upload_sheet=1;
        }
      }
      
    }
}
if($upload_sheet!=1){
   // redirect("exams/",'refresh');
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
                    <a href="<?php echo base_url(); ?>index.php/exams/">Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url(); ?>index.php/exams/settings/<?php echo $exam->id ?>">Add Results Of <?php echo $exam->exam ?></a>
                </li>
            </ul>
        </div>
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="fa fa-th-list"></i>Add Marks For Failed Records</h3>                  
            </div>
            <div class="box-content nopadding">   
                <div  class="form-horizontal form-bordered">
                    
                    <form  action="<?php echo base_url() ?>index.php/exams/submit_marks_failed_records" method="post" >
                    
                    <table class="table table-hover table-bordered table-striped" >
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Student</th>
                                <th>Correct</th>
                                <th>Wrong</th>
                                <th>Marks</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody style=" max-height: 450px; overflow: scroll;">
                            <?php
                              $s=$_SESSION['failed_records'];
                              $rolls="";
                              foreach ($s as $value) {
                               //   print_r($value);
                                  $rolls.=$value['details']['roll'].",";
                                  ?>
                            <tr>
                                <td><?php echo $value['details']['roll'] ?></td>
                                <td><?php echo $value['details']['name'] ?></td>
                                <td><input type="text" name="correct_<?php echo $value['details']['roll']  ?>" value="<?php echo $this->form->value('correct_'.$value['details']['roll']) ?>" class="form-control" />
                                    <span style=" color: red"><?php echo $this->form->error('correct_'.$value['details']['roll']) ?></span>
                                </td>
                                <td><input type="text" name="wrong_<?php echo $value['details']['roll']  ?>" value="<?php echo $this->form->value('wrong_'.$value['details']['roll']) ?>" class="form-control" />
                                    <span style=" color: red"><?php echo $this->form->error('correct_'.$value['details']['roll']) ?></span>
                                </td>
                                <td><input type="text" name="marks_<?php echo $value['details']['roll']  ?>" value="<?php echo $this->form->value('marks_'.$value['details']['roll']) ?>" class="form-control" />
                                    <span style=" color: red"><?php echo $this->form->error('correct_'.$value['details']['roll']) ?></span>
                                </td>
                                <td><?php echo $value['error'] ?></td>
                            </tr>
                                 <?php 
                              }
                              $rolls = substr($rolls, 0, strlen($rolls)-1)
                            ?>
                        </tbody>
                        <tr>
                            <input type="hidden" name="examid" value="<?php echo $sub_details->id; ?>" />
                            <input type="hidden" name="section" value="<?php echo $section_details->sid; ?>" />
                            <input type="hidden" name="maxmarks" value="<?php echo $sub_details->maxmarks; ?>" />
                            <input type="hidden" name="questions" value="<?php echo $sub_details->questions; ?>" />
                            <input type="hidden" name="exam_id" value="<?php echo $exam->id; ?>" />
                            <input type="hidden" name="mcid" value="<?php echo  $this->input->get("mcid"); ?>" />
                            <input type="hidden" name="rolls" value="<?php echo  $rolls; ?>" />
                            
                            <td colspan="6" style=" text-align: center;"><input type="submit" value="Submit Marks"  class="btn btn-primary" /> </td>
                        </tr>
                    </table>
                            
                    </form>
                 </div>
            </div>            
        </div>
        </div>
    </div>
</div

<?php
$this->load->view('structure/footer');
?>