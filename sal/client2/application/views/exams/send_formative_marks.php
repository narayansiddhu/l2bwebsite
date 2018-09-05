<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$message=$msg_error=$sub_msg="";$preview=0;
if(isset($_POST['submit'])){
    $message=$_POST['message'];
    $sub_msg=$_POST['subjectmessage'];
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
                    <a href="">View results Of <?php echo $exam->exam ?> , <?php echo $section->section ." - ".$section->class ?> </a>
                </li> 
                <li>
                    <a href="">Send Results </a>
                </li> 
            </ul>
            
        </div>         
            <div class="box" >
                <?php
                if($preview==1){
                    
 $grade_array=  unserialize(GPA_GRADING);
                    $students="SELECT * from student where section_id='".$section->sid."'";
                    $students= $this->db->query($students)->result();
                    $query="SELECT e.id,e.maxmarks,e.minmarks,s.subject, (select count(*) from formative_marks where exam_id =e.id ) as marks_entered,sl.sl_id as sec_lang FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid LEFT JOIN second_language sl ON sl.course_id=c.cid WHERE examid='".$exam->id."' AND ecid='".$section->id."'  ";
                    $query=$this->db->query($query); 
                    $query=$query->result();
                    $exam_ids="";
                    $subject_array=array();
                    foreach ($query as $value) {
                        $exam_ids.=$value->id.",";
                        $type=1;
                        if(strlen($value->sec_lang)!=0){
                             $type=2;
                        }
                        $subject_array[$value->id]= array("name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks,"max_Secured"=>0,"type"=>$type);
                    }
                    $exam_ids= substr($exam_ids, 0,  strlen($exam_ids)-1);
                         $stud_marks= array();
      $s=$this->db->query("SELECT student_id as id,name,roll,userid,admission_no,phone from student WHERE section_id ='".$section->sid."'  ")->result();;
      foreach($s as $value){
            $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'total'=>0,'total_marks'=>0);
        }
        function gpa_grade($mark,$gpa_array){
                $mark=  number_format($mark);
                foreach ($gpa_array as $key => $value) {
                   if( ($mark>=$value['from'])&&($mark<=$value['to'])  ){
                      return $key; 
                   }        
                }
                return "Absent";
            }
             $overall_total=10*(sizeof($query));
            
    $marks="SELECT * from formative_marks where exam_id IN (".$exam_ids.") ";
 $marks= $this->db->query($marks)->result();
   $maarks_array=array();$ranks=array();
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
 
        
        $value->marks =  number_format(($t/20)*100,2);
        $value->marks=gpa_grade($value->marks, $grade_array[$type]['grading']);
        $ranks[$value->exam_id][$value->marks]=$value->marks;

        if($value->marks< $subject_array[$value->exam_id]['max_Secured']){
            $subject_array[$value->exam_id]['max_Secured']=$value->marks;
        }
       $maarks_array[$value->exam_id][$value->student_id]= array('part1'=>$value->part_1,'part2'=>$value->part_2,'part3'=>$value->part_3,'part4'=>$value->part_4,'total'=>$t,"Grade"=>$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['grade']);
        $stud_marks[$value->student_id]['total']+=$grade_array[$subject_array[$value->exam_id]['type']]['grading'][$value->marks]['Grade_points'];              
    //total_marks
        $stud_marks[$value->student_id]['total_marks']+=$t;
        
        }        
        foreach ($ranks as $key => $value) {
            $value=array_unique($value);            
            krsort($value);
            $ranks[$key]=$value;
        }
         $grade_graph_array = array('0'=>0,'1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0);
        $totals=array();

        foreach($stud_marks as $k=>$value){
           $stud_marks[$k]['grade']=gpa_grade(round($value['total']/$overall_total*100), $grade_array[1]['grading']);
           $grade_graph_array[$stud_marks[$k]['grade']]++;
           $totals[$value['total']]=$value['total'];
        }
        krsort($totals);
 $message_array =array();
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
         foreach ($s as  $value) {
             $message_array[$value->id]= array("name"=>$value->name,"phone"=>$value->phone,"roll"=>$value->roll);
             $std_msg=$message;
             $std_sub_msg="";
             $std_msg = str_replace("<#student_name#>", $value->name , $std_msg );
             $std_msg = str_replace("<#Rollno#>", $value->roll , $std_msg );
             $std_msg = str_replace("<#Userid#>", $value->userid , $std_msg );
            $std_msg = str_replace("<#Admission_no#>", $value->admission_no , $std_msg );
            $subject_msg_string="";
            foreach ($query as $sub){
                $sub_msg_1=$sub_msg;
                $sub_msg_1= str_replace("<#subject_name#>", $sub->subject , $sub_msg_1 );
                      if(isset($maarks_array[$sub->id][$value->id])){
                  $sub_msg_1= str_replace("<#subject_name#>", $sub->subject , $sub_msg_1 );
                   $sub_msg_1= str_replace("<#part1#>", $maarks_array[$sub->id][$value->id]['part1'], $sub_msg_1 );
                    $sub_msg_1= str_replace("<#part2#>", $maarks_array[$sub->id][$value->id]['part2'] , $sub_msg_1 );
                     $sub_msg_1= str_replace("<#part3#>",$maarks_array[$sub->id][$value->id]['part3'] , $sub_msg_1 );
                      $sub_msg_1= str_replace("<#part4#>",$maarks_array[$sub->id][$value->id]['part4'] , $sub_msg_1 );
                      $sub_msg_1= str_replace("<#Subject_total#>",$maarks_array[$sub->id][$value->id]['total'] , $sub_msg_1 );

                     }else{
                         $sub_msg_1= str_replace("<#subject_name#>", $sub->subject , $sub_msg_1 );
                   $sub_msg_1= str_replace("<#part1#>","--", $sub_msg_1 );
                    $sub_msg_1= str_replace("<#part2#>", "--" , $sub_msg_1 );
                     $sub_msg_1= str_replace("<#part3#>","--" , $sub_msg_1 );
                      $sub_msg_1= str_replace("<#part4#>","--" , $sub_msg_1 );
                      $sub_msg_1= str_replace("<#Subject_total#>","--" , $sub_msg_1 );

                     }
                  $subject_msg_string.=$sub_msg_1;
                }

                $std_msg = str_replace("<#Subject_Message_String#>", $subject_msg_string , $std_msg );
                $std_msg = str_replace("<#Grand_Total#>", $stud_marks[$value->id]['total_marks'] , $std_msg );
                $std_msg = str_replace("<#Grade#>", $grade_array[1]['grading'][$stud_marks[$value->id]['grade']]['grade'] , $std_msg );
                $std_msg = str_replace("<#Rank_Secured#>", find_pos($totals, $stud_marks[$value->id]['total']) , $std_msg );
                   $message_array[$value->id]['message']=$std_msg;
          }
          $_SESSION['messge_content']= $message_array;
          
          ?>
          <div class="col-sm-12 nopadding" >
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                            <h3><i class="fa fa-comments-o"></i>Message Preview</h3>
                            <div class="actions">
                                <form method="post" action="<?php echo base_url() ?>index.php/exams/send_formative_results"  >
                                    <a style=" color: white;" href="<?php echo base_url() ?>index.php/exams/send_formative_cards/<?php echo $exam->id ?>/<?php echo $section->sid ?>"  class="btn btn-primary"><i class="fa fa-undo" aria-hidden="true"></i>Back</a>
                                    <button  style=" color: white;"  type="submit"  class="btn btn-primary"  ><i class="fa fa-share" aria-hidden="true"></i>Send SMS</button>
                                      <input type="hidden" name="examid" value="<?php echo $exam->id ?>" />
                                      <input type="hidden" name="section" value="<?php echo $section->sid ?>" />
                                   
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
                                        <td><?php echo $value['message'] ?></td>
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
              <script>
              send_formative_results
              function send_sms(){
                  //snd_btn
                    setState('attmobile','<?php echo base_url() ?>index.php/exams/send_formative_results','examid=<?php echo $exam->id ?>&section=<?php echo $section->sid ?>');             
              }
              //msg_sent
              </script>
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
                                    <label for="textfield" class="control-label col-sm-2">Subject Marks List</label>
                                    <div class="col-sm-10">
                                        <textarea rows="5" cols="5" style=" resize: none" id="subject_string" class="form-control" name="subjectmessage" ><?php echo $this->form->value('clsname') ?></textarea>
                                        <span style=" color: red">
                                                <?php
                                                   echo $this->form->error('clsname');   
                                                ?>
                                            </span>        
                                    </div>
                                 </div> 
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Message Content</label>
                                    <div class="col-sm-10">
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('student_name','attmessage');" class="btn btn-mini">Student Name</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Rollno','attmessage');" class="btn btn-mini">Roll No</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Userid','attmessage');" class="btn btn-mini">Userid</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Admission_no','attmessage');" class="btn btn-mini">Admission No</a>
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('Subject_Message_String','attmessage');" class="btn btn-mini">Subject String</a>
                                        <a style=" margin: 2px;" onclick="customize_msg_content('Grand_Total','attmessage');" class="btn btn-mini" >Grand Total</a>
                                            <a style=" margin: 2px;" onclick="customize_msg_content('Grade','attmessage');" class="btn btn-mini" >Grade Secured</a>
                                   
                                        <hr style=" padding: 0px; margin: 0px"/>
                                         
                                        <a  style=" margin: 2px;" onclick="customize_msg_content('subject_name','subject_string');" class="btn btn-mini">Subject Name</a>
                                         <a style=" margin: 2px;" onclick="customize_msg_content('part1','subject_string');" class="btn btn-mini" >part1</a>
                                            <a style=" margin: 2px;" onclick="customize_msg_content('part2','subject_string');" class="btn btn-mini" >Part2</a>
                                            <a  style=" margin: 2px;"onclick="customize_msg_content('part3','subject_string');" class="btn btn-mini" >Part3</a>
                                            <a style=" margin: 2px;" onclick="customize_msg_content('part4','subject_string');" class="btn btn-mini" >Part4</a>
                                            <a style=" margin: 2px;" onclick="customize_msg_content('Subject_total','subject_string');" class="btn btn-mini" >subject Total</a>
                                            
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