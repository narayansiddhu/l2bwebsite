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
                    <a href="<?php echo base_url(); ?>index.php/exams/">Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url(); ?>index.php/exams/settings/<?php echo $exam->id ?>">Add Results Of <?php echo $exam->exam ?></a>
                </li>
            </ul>
        </div>
        <?php
          if($upload_sheet==0){
              $query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from mcexam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
              $query=$query->result();

              ?>
              <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="fa fa-th-list"></i>Add Marks List</h3>                  
            </div>
            <div class="box-content nopadding">   
                <div  class="form-horizontal form-bordered">
                    <div class="col-sm-5 nopadding" >
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Section</label>
                            <div class="col-sm-10">
                                <select  onchange="load_subjects();" name="section" style=" width: 100%;" id="section" class="select2-me">
                                    <option value="">Select Section</option>
                                    <?php
                                     foreach ($query as $value) {
                                         ?>
                                    <option value="<?php echo $value->id.",".$value->sid ?>"><?php echo $value->class." - ".$value->section ?></option>
                                         <?php
                                     }
                                    ?>
                                </select>
                                <span id="section_err" style=" color: red">
                                        <?php
                                           echo $this->form->error('section');   
                                        ?>
                                    </span>        
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 nopadding" style=" border-right:  1px solid #cccccc" >
                        <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">subject</label>
                            <div class="col-sm-10">
                                <select  onchange="download_sheet();" name="subject" style=" width: 100%;" id="subject" class="select2-me">
                                    <option value="">Select Section</option>
                                    
                                </select>
                                <span id="subject_err" style=" color: red">
                                        <?php
                                           echo $this->form->error('section');   
                                        ?>
                                </span>    
                            </div>
                        </div>
                    </div>
                    <div style=" text-align: center ; padding-top: 10px ; " class="col-sm-2 "  >
                        <button onclick="download_sheet();"  type="submit" class="btn btn-primary"  >Download  Sheet</button>
                    </div>
                 </div>
            </div>            
        </div>
              <?php
          }else{
               ?>
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-bordered box-color">
                    <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Exam Details</h3>                  
                    </div>
                    <div class="box-content nopadding">   
                        <div  class="form-horizontal form-bordered">
                            <table class="table table-hover table-bordered  table-striped">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <td><?php echo $exam->exam ?></td>
                                    </tr>
                                    <tr>
                                        <th>Class</th>
                                        <td><?php echo $section_details->class ?></td>
                                    </tr>
                                    <tr>
                                        <th>Section</th>
                                        <td><?php echo $section_details->section ?></td>
                                    </tr>
                                    <tr>
                                        <th>Subject</th>
                                        <td><?php echo $sub_details->subject ?></td>
                                    </tr>
                                    <tr>
                                        <th>Exam Date</th>
                                        <td><?php echo date("d-m-y",$sub_details->starttime) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Timings</th>
                                        <td><?php echo date("H:i",$sub_details->starttime)." - ".date("H:i",$sub_details->endtime) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Questions</th>
                                        <td><?php echo $sub_details->questions ?></td>
                                    </tr>
                                    <tr>
                                        <th>Max Marks</th>
                                        <td><?php echo $sub_details->maxmarks ?></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
               </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-bordered box-color">
                    <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Upload Mark sheet</h3>                  
                    </div>
                    <div class="box-content nopadding">   
                        <form  enctype="multipart/form-data"  method="post"  action="<?php echo base_url() ?>index.php/exams/upload_mcmarks_sheet" class="form-horizontal form-bordered">
                            <input type="hidden" name="examid" value="<?php echo $sub_details->id; ?>" />
                            <input type="hidden" name="section" value="<?php echo $section_details->sid; ?>" />
                            <input type="hidden" name="maxmarks" value="<?php echo $sub_details->maxmarks; ?>" />
                            <input type="hidden" name="questions" value="<?php echo $sub_details->questions; ?>" />
                            <input type="hidden" name="exam_id" value="<?php echo $exam->id; ?>" />
                            <input type="hidden" name="mcid" value="<?php echo  $this->input->get("mcid"); ?>" />
                            
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Marks Sheet</label>
                                <div class="col-sm-10" style=" text-align: center">
                                    <input  type="file" class="form-control" name="sheet" placeholder="please select Marks Sheet" />
                                    <span style=" clear: both; color: red">
                                        
                                    </span>
                                </div>                                
                            </div>
                            <div  class="form-actions col-sm-offset-4 col-sm-4">
                                <input type="submit"   name="submit" value="Upload Marks Sheet" class="btn btn-primary" />
                            </div>
                        </form>
                    </div>
               </div>
            </div>
        </div>
               
             <?php
          }
        ?>
        
        
        
        
        <span  id="results" >
            
        </span>
        <script>
            function load_subjects(){
                
                 section=$('#section').val();
                 $('#section_err').html("");
                if(section.length!=0){
                 //  window.location.href = "<?php echo base_url() ?>index.php/exams/settings/"+<?php echo $exam->id ?>+"?section="+section;
                setState('subject','<?php echo base_url() ?>index.php/exams/load_subjects','examid='+<?php echo $exam->id ?>+'&ecid='+section);  
              
                 }else{
                    $('#section_err').html("** Please select Secton");
                }
            }
            function download_sheet(){
                 section=$('#section').val();
                 subject=$('#subject').val();
                 $('#section_err').html("");
                 $('#subject_err').html("");
                 if(section.length==0){
                     $('#section_err').html("** Please select Secton");
                 }else{
                    if(subject.length==0){
                        $('#subject_err').html("** Please select Subject");
                    }else{
                        subject =subject.split(",");
                        if(subject[1]==0){
                            window.open('<?php echo base_url() ?>index.php/exams/download_sheet?examid=<?php echo $exam->id ?>&ecid='+section+'&subject='+subject[0]);
                            window.location.href = "<?php echo base_url() ?>index.php/exams/add_marks/"+<?php echo $exam->id ?>+"?mcid="+section+"&subject="+subject[0];
                       
                           }else{
                           alert("Marks Already Uploaded");
                        }
                     //window.location.href = "<?php echo base_url() ?>index.php/exams/settings/"+<?php echo $exam->id ?>+"?section="+section;
                     // setState('results','<?php echo base_url() ?>index.php/exams/download_sheet?examid=<?php echo $exam->id ?>&ecid='+section+'&subject='+subject,"");  

                    }
                 }
            }
            </script>
        
        </div>
    </div>
  </div>

<?php
$this->load->view('structure/footer');
?>