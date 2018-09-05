<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$cls_section= $this->input->post("cls_section");
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
                    <a href="<?php echo base_url(); ?>index.php/Exams">Exams</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php">Reports</a>
                </li>                
            </ul>
        </div>
        <?php
        if(strlen($cls_section)==0){
            $cls= "SELECT s.sid,s.name as sec_name , c.name as cls_name FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ";
            $cls = $this->db->query($cls)->result();
            ?>
            <br/>
            <div class="row" >
                <form action="" method="post">
                  <div class="col-sm-4">
                     <h4 style=" color: #386ee0; text-align: right">Exam Reports :</h4>
                  </div>
                <div class="col-sm-4 " style=" padding-top: 7px; padding-left: 0px; ">
                          <select name="cls_section" id="cls_section" class='select2-me' style="width:100%; " >
                                <option value="">Select A Class/Section</option>
                                <?php
                                foreach ($cls as $value) {
                                    ?><option value="<?php echo $value->sid ?>" ><?php echo $value->cls_name." - ".$value->sec_name  ?></option><?php
                                }
                                ?>
                            </select>  
                            <span id='student_err' style=" color: red">

                            </span>  
                </div>
                <div class="col-sm-4" style=" padding-top: 7px; padding-left: 0px; " >
                    <button class="btn btn-primary" type="submit">Fetch Results</button>
                </div>
                    </form>
            </div>
            <hr/>
            <?php
        }else{
            $cls= "SELECT s.sid,s.name as sec_name , c.name as cls_name, (select count(*) from student WHERE section_id=s.sid ) as students FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND s.sid='".$cls_section."' ";
            $cls = $this->db->query($cls)->row();
            $exams ="SELECT ec.id , e.exam , e.startdate , e.enddate , (select count(*) FROM `exam`  where ecid = ec.id ) as exams FROM `examination_cls` ec JOIN examinations e ON ec.examid=e.id where ec.sectionid='".$cls_section."' ";   
            $exams = $this->db->query($exams)->result();
            ?>
            <br/>
            <div class="box">
                <div class="col-sm-3">
                    <h4 style=" text-align: center; color: #386ee0">Section Details</h4>
                    <hr/>
                    <table class="table table-bordered">
                        <tr>
                            <th>Class</th>
                            <td><?php echo $cls->cls_name ?></td>
                        </tr>
                        <tr>
                            <th>Section</th>
                            <td><?php echo $cls->sec_name ?></td>
                        </tr>
                        <tr>
                            <th>Students</th>
                            <td><?php echo $cls->students ?></td>
                        </tr>
                    </table>
                    
                </div>
                <div style=" border: 1px solid #cccccc" class="col-sm-9">
                    <h4 style=" text-align: left; color: #386ee0; width: 50%; float: left ">Exams Scheduled</h4>
                    <span id="errors_exam" style=" text-align: right; color: red; width: 50%; float: left ; padding-top: 10px " ></span>
                    <hr style=" clear: both"/>
                    <form action="" method="post" >
                        <table class="table table-bordered " style=" width: 100%" >
                            <tr>
                                <th></th>
                                <th>Exam</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                                <th>No Of Exams</th>
                            </tr>
                            <?php
                            $ex=0;
                            $exam_ids="";
                            foreach ($exams as $value) {
                                    ?>
                                        <tr>
                                            <td style=" text-align: center"><input type="checkbox" value="<?php echo $value->id ?>" name="exam_<?php echo $value->id ?>" id="exam_<?php echo $value->id ?>" /></td>
                                            <td><?php echo $value->exam ?></td>
                                            <td><?php echo date("d-m-Y",$value->startdate) ?></td>
                                            <td><?php echo date("d-m-Y",$value->enddate) ?></td>
                                            <td><?php echo $value->exams ?></td>
                                        </tr>
                                      <?php  
                                      $ex++;
                                      $exam_ids.=$value->id.",";
                                }
                            if($ex!=0){
                                $exam_ids = substr($exam_ids, 0, strlen($exam_ids)-1);
                               ?>
                                        <input type="hidden" name="exam_ids" id="exam_ids" value="<?php echo $exam_ids ?>" />
                                         <tr>
                                            <td colspan="3">
                                                <button type="button" onclick="fetch_reports();" class="btn btn-primary" style=" float: right">Fetch Detailed Report</button>
                                            </td>
                                            <td colspan="2">
                                                <button type="button" onclick="fetch_progress_cards();" class="btn btn-primary" style=" float: left" >Generate Report Card</button>
                                            </td>
                                        </tr>
                                        <script>
                                          function fetch_progress_cards(){
                                         $('#errors_exam').html("");
                                             exam_ids = $('#exam_ids').val();
                                             exam_ids=exam_ids.split(',');
                                             var i,chk;
                                             chk="";
                                            for (i = 0; i < exam_ids.length; ++i) {
                                              //  alert($("#exam_"+exam_ids[i]).prop('checked'));
                                                if($("#exam_"+exam_ids[i]).prop('checked')){
                                                  chk=chk+exam_ids[i]+"_";   
                                                 }
                                             }
                                             
                                             if(chk.length==0){
                                                 $('#errors_exam').html("** Please Check Any of the exam to Fetch Progress Cards");
                                             }else{
//                                                 chk=  chk.substring(0, (chk.length)-1 );
//                                                 chk = chk.replace(",","_");
                              //                   alert(chk);
                            window.location.href = '<?php echo base_url() ?>index.php/Progrees_cards/report_cards/<?php echo $cls->sid  ?>/'+chk;
                                                         //alert(chk);
                                             }
                                             
                                             
                                          } 
                                          function fetch_reports(){
                                         $('#errors_exam').html("");
                                             exam_ids = $('#exam_ids').val();
                                             exam_ids=exam_ids.split(',');
                                             var i,chk;
                                             chk="";
                                            for (i = 0; i < exam_ids.length; ++i) {
                                                if($("#exam_"+exam_ids[i]).prop('checked')){
                                                  chk=chk+exam_ids[i]+",";   
                                                 }
                                             }
                                             
                                             if(chk.length==0){
                                                 $('#errors_exam').html("** Please Check Any of the exam to fetch Report");
                                             }else{
                                                 alert(chk);
                                             }
                                             
                                             
                                          }
                                        </script>
                               <?php  
                            }
                                
                            ?>
                                        
                        </table>
                    </form>
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
