<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$ecids=$this->input->get('ecids');
if(strlen($ecids)!=0){
    $examids = implode(",",array_filter( explode(",",$ecids)));        
}
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
            <?php
             if(strlen($ecids)==0){
                 ?>
            <div class="box  ">
                <?php  
                $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject,(select count(*) from marks where exam_id = e.id) as marks FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE examid='".$exam->id."' AND ecid='".$section->id."'  ";
                $query=$this->db->query($query); 
                $query=$query->result();
                ?>
                <div class="box-title">
                    <h3 style=" color:  #66cc00"> <i class="fa fa-plus"></i>&nbsp;Add Marks Sheet</h3>
                </div>
                <div class="box-content nopadding">
                    <div class='form-horizontal form-bordered'>
                        <div  style=" text-align: center" class="col-sm-12 nopadding"><br/>
                            <?php 
                              if(sizeof($query)>0){
                                   $c=0; $sub_ids="";
                                   foreach ($query as $value) {
                                       if($value->marks==0){
                                           ?>
                            <input type="checkbox" id="subject_<?php echo $value->id ?>" name="subject_<?php echo $value->id ?>" />&nbsp;<span style=" color:  #006666; font-size: 15px;"><?php echo $value->subject ?></span>&nbsp;&nbsp;
                                           <?php
                                           $sub_ids.=$value->id.",";$c++;
                                       }
                                   }
                                   if($c==0){
                                       ?>
                            <h3 style=" text-align: center; color:  orangered">** Marks Already Entered For All Exams</h3>
                                      <?php
                                   }else{
                                       ?>
                            <input type="hidden" name="subids" id="subids" value="<?php echo substr($sub_ids, 0, strlen($sub_ids)-1)  ?>" />
                            <hr style=" padding:1.5px;"/><button onclick="download_sheet();" class="btn btn-primary">Download Marks Sheet</button>
                            <span id="errors"></span>  
                            <script>
                                   function download_sheet(){
                                       sub=$('#subids').val();i=0;$('#errors').html(" ");
                                       sub=sub.split(',');check_ids="";
                                       for(i=0;i<sub.length;i++){
                                          if($("#subject_"+sub[i]).prop('checked')==true){
                                                check_ids=check_ids+","+sub[i];  
                                          }
                                       }
                                       if(check_ids.length==0){
                                           $('#errors').html(" ** Please select Subjects");
                                       }else{
                                           window.open('<?php echo base_url() ?>index.php/exams/downnload_sample_sheet/?ecids='+check_ids+'&section=<?php echo $section->sid ?>');
                                           window.location.href="<?php echo base_url() ?>index.php/exams/add_results/<?php echo $exam->id ?>/<?php echo $section->sid ?>?ecids="+check_ids+'&section=<?php echo $section->sid ?>';
                                        }        
                                   }    
                                   
                                 </script>
                                       <?php
                                   }
                              }else{
                                  ?>
                            <h3 style=" text-align: center; color:  orangered">** No Exams Found..</h3>
                                      <?php
                              }
                            ?>
                       </div>
                    </div>
                    <br/>
                </div>
            </div>
                 <?php
                 
             }else{
                 ?>
            <div class="box">
                <div class="col-sm-6 nopadding">
                    <div class="box box-bordered">
                        <div class="box-title">
                            <h3 style=" color:  #66cc00"> <i class="fa fa-list"></i>&nbsp;Upload Marks List of <?php echo $exam->exam ?>  <?php echo $section->class." - ".$section->section ?></h3>
                        </div>
                        <div class="box-content nopadding">
<!--                            <table class="nopadding" style=" width: 100%; border: 1px solid #cccccc">
                                <tr style=" text-align: center;  ">
                                    <td style=" width: 50%;"><strong>Exam</strong><br/><?php echo $exam->exam ?></td>
                                    <td style=" width: 50%; border-left: 1px solid #cccccc"><strong>Class-Section</strong><br/><?php echo $section->class." - ".$section->section ?></td>
                                 </tr>                        
                            </table>-->
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
                                    $query=$this->db->query("SELECT e.id as id,ex.exam,s.subject,e.maxmarks,e.minmarks from exam e JOIN examinations ex ON ex.id=e.examid JOIN course c ON c.cid=e.courseid  JOIN subjects s ON s.sid=c.subid WHERE e.id IN (".$examids.")");
                                    $query=$query->result();$i=1;
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
                <div class="col-sm-6 ">
                    <div class="box box-bordered">
                        <div class="box-title">
                            <h3 style=" color:  #66cc00"> <i class="fa fa-plus"></i>&nbsp;Upload Mark Sheet</h3>
                        </div>
                        <div class="box-content nopadding">
                            <form class='form-horizontal form-bordered'  method="post" action="<?php echo base_url(); ?>index.php/exams/submit_marks" enctype="multipart/form-data" >
                                <div class="form-group" >
                                        <label for="textfield" class="control-label col-sm-2">Exam</label>
                                        <div class="col-sm-10" id="exam_holder" >
                                            <input type="file" name="file" class="form-control" /> 
                                            <span id="exam_err" style=" color: red">
                                                  <?php
                                                   echo $this->form->error("file");
                                                  ?>
                                            </span> 
                                        </div>
                                </div>
                                <input type="hidden" name="exam" value="<?php echo $exam->id ?>" />
                                <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                                <input type="hidden" name="eid" value="<?php echo $examids ?>" />
                                <input type="hidden" name="exams_details" value='<?php echo $query ?>' />
                                
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit" name="Upload_file" class="btn btn-primary" value="Upload Marks" />
                                </div>

                            </form>
                        </div>
                    </div>
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