<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$message=$msg_error=$sub_msg="";$preview=0;
if(isset($_POST['submit'])){
    $message=$_POST['message'];
    if(strlen($message)==0){
       $msg_error="** Please Enter Message Content" ;
    }else{
        $preview=1;
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
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a> <i class="fa fa-angle-right" aria-hidden="true"></i>
                 
                </li> 
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/view_daily_exammarks/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>">View results Of <?php echo $exam->exam ?> , <?php echo $section_data->section ." - ".$section_data->class ?> </a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li> 
                <li>
                    <a href="">Send Results </a>
                </li> 
            </ul>
            
        </div>         
            <div class="box" >
                <?php
                if($preview==1){
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
 $grade_array=  unserialize(GPA_GRADING);
                    $marks="SELECT m.*,s.name,s.student_id as id ,s.userid,s.roll,s.phone,s.admission_no FROM student s LEFT JOIN `grandtest_marks` m  ON m.student_id=s.student_id WHERE m.exam_id='".$section_data->id."' ORDER BY s.roll ASC";
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
                   
                    foreach($marks as $value){
                       $std_msg=$message;
                       $message_array[$value->id]= array("name"=>$value->name,"phone"=>$value->phone,"roll"=>$value->roll);
                       $std_msg = str_replace("<#student_name#>", $value->name , $std_msg );
                        $std_msg = str_replace("<#Rollno#>", $value->roll , $std_msg );
                        $std_msg = str_replace("<#Userid#>", $value->userid , $std_msg );
                       $std_msg = str_replace("<#Admission_no#>", $value->admission_no , $std_msg );
$m="Absent";$r="--";
                       if($value->marks!=-1){
                         $m=$value->marks;
                         $r= find_pos($marks_rank,$value->marks );
                       }
                       $std_msg = str_replace("<#Exam#>", $exam->exam , $std_msg );
                       $std_msg = str_replace("<#Class#>", $section_data->class ." - ".$section_data->section , $std_msg );
                       $std_msg = str_replace("<#Marks#>", $m , $std_msg );
                       $std_msg = str_replace("<#Rank#>", $r , $std_msg );
                       $message_array[$value->id]['message']=$std_msg;
                    }
                        
          $_SESSION['messge_content']= $message_array;
          
          ?>
          <div class="col-sm-12 nopadding" >
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3><i class="fa fa-comments-o"></i>Message Preview</h3>
                            <div class="actions">
                                <form method="post" action="<?php echo base_url() ?>index.php/exams/send_daily_test_results"  >
                                    <a style=" color: white;" href="<?php echo base_url() ?>index.php/exams/send_formative_cards/<?php echo $exam->id ?>/<?php echo $section_data->sid ?>"  class="btn btn-primary"><i class="fa fa-undo" aria-hidden="true"></i>Back</a>
                                    <button  style=" color: white;"  type="submit"  class="btn btn-primary"  ><i class="fa fa-share" aria-hidden="true"></i>Send SMS</button>
                                      <input type="hidden" name="examid" value="<?php echo $exam->id ?>" />
                                      <input type="hidden" name="section" value="<?php echo $section_data->sid ?>" />
                                   
                                </form>
                             </div>
                        </div>
                        <div class="box-content nopadding" >
                            <table class="table table-bordered datatable" style=" width: 100%;" >
                                <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Message Content</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                              foreach ($message_array as $value) {
                                                  ?>
                                    <tr>
                                        <td><?php echo $value['roll'] ?></td>
                                        <td><?php echo $value['name'] ?></td>
                                        <td><?php echo $value['phone'] ?></td>
                                        <td><?php echo str_replace("\n", "<br/>" ,$value['message']) ?></td>
                                    </tr>
                                                  <?php
                                              }
                                    ?>
                                </tbody>
                            </table>
                        </div>    
                    </div>
          </div>
                <span id='msg_sent'></span>
              
          <?php
          
          
          
                     }else{
                    ?>
                        <div class="col-sm-6 nopadding" >
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3><i class="fa fa-comments-o"></i>Send SMS</h3>
                        </div>
                        <div class="box-content nopadding" >
                            <form class='form-horizontal form-bordered' action="?action=preview_sms" method="post" >
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Message Content</label>
                                    <div class="col-sm-10">
                                        <textarea rows="5" cols="5" style=" resize: none" id="attmessage" class="form-control" name="message" ><?php echo $this->form->value('clsname') ?></textarea>
                                        <span style=" color: red">
                                                <?php
                                                   echo $msg_error;   
                                                ?>
                                            </span>        
                                    </div>
                                 </div> 
                                
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Message Content</label>
                                    <div class="col-sm-10">
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('student_name','attmessage');" class="btn btn-mini">Student Name</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Rollno','attmessage');" class="btn btn-mini">Roll No</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Class','attmessage');" class="btn btn-mini">Class-Section</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Userid','attmessage');" class="btn btn-mini">Userid</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Admission_no','attmessage');" class="btn btn-mini">Admission No</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Exam','attmessage');" class="btn btn-mini">Exam Name</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Marks','attmessage');" class="btn btn-mini">Marks Secured</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Rank','attmessage');" class="btn btn-mini">Rank Secured</a>
                                              
                                    </div>
                                </div>
                                <div class="form-group" style=" text-align: center"><br/>
                                    <button type="submit" name="submit" class="btn btn-primary">Preview Message</button><br/><br/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                function customize_msg_content(name,txtid){
                    attmessage = $('#'+txtid).val();
            attmessage =attmessage+"<#"+name+"#> ";
            $('#'+txtid).val(attmessage);
                }
                </script>
                        <?php
                }
                ?>
                
                
            </div>
            
        </div>
    </div>
</div>
            
            
<?php
$this->load->view('structure/footer');
?>