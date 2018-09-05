<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=$section = $this->input->get("section");
$course_err ="";$show_results=0;
if( strlen($t)!=0 ){
    $section_query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class  FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 AND s.sid='".$t."' ");
  if($section_query->num_rows()==0){
       $course_err ="** In-valid Selection  ";
  }else{    
    $section_query =$section_query->row();
    $show_results=1;
  }
}
$query=$this->db->query("SELECT ec.id,s.sid,s.name as section,c.name as class ,(select count(*) from exam where ecid=ec.id ) as sub_count FROM `examination_cls` ec JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1  ORDER BY c.numeric_val DESC");
$query=$query->result();
 
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
          
        
        <?php
        if($show_results==1){
            $subjects ="SELECT e.id,e.examdate,e.starttime,e.endtime,e.timespan,e.maxmarks,e.questions,s.subject FROM mcexam e JOIN subjects s ON e.subid=s.sid where ecid='".$section_query->id."' ";
            $subjects = $this->db->query($subjects);
            $subjects = $subjects->result();
            $sub_arr = array();
            $stud_marks=array();
            $ranks= array();
            $s=$this->db->query("SELECT student_id as id,name,roll from student WHERE section_id ='".$section."'  ")->result();;
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id,'roll'=>$value->roll);
                $stud_marks[$value->id]['total']=0;
            }
            $sub_id="";
            foreach($subjects as $val ){
                $sub_id.=$val->id.",";
                $sub_arr[$val->id] = array('id'=>$val->id,"subject"=>$val->subject,"max"=>$val->maxmarks,"questions"=>$val->questions);
                $k=$this->db->query("SELECT st.student_id,m.marks,m.correct,m.wrong FROM `student` st LEFT JOIN mcmarks m ON  m.student_id=st.student_id WHERE st.section_id ='".$section."' AND m.exam_id='".$val->id."'  ")->result();
                foreach ($k as $value) {
                    if($value->marks!=-1){
                       $ranks[$val->id][$value->marks]=$value->marks;
                       $stud_marks[$value->student_id]['total']=$stud_marks[$value->student_id]['total']+$value->marks;
                    }
                     $stud_marks[$value->student_id][$val->id]=array("marks"=>$value->marks,"correct"=>$value->correct,"wrong"=>$value->wrong);
                     
                }
            }
            foreach ($ranks as $key => $value) {
                $value=array_unique($value);            
                krsort($value);
                $ranks[$key]=$value;
            }
            $totals=array();
            foreach($stud_marks as $k=>$value){
                $totals[$value['total']]=$value['total'];
            }
            krsort($totals);
            //print_r($stud_marks);
        
        
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
        ?>
        <div class="box box-bordered box-color ">
            <div class="box-title">
                <h3><i class="fa fa-th-list"></i>Results Of <?php echo strtoupper( $exam->exam) ?> </h3>
                <div class="actions">
                    <a target="_blank" href="<?php echo base_url();  ?>index.php/exams/print_mc_result/<?php echo $exam->id?>?section=<?php echo $this->input->get("section");  ?>" class="btn"><i class="fa fa-print"></i></a>
                </div>
            </div>
            <div style=" max-height: 450px; overflow: scroll" class="box-content nopadding">                                
                
                <table  class="table datatable table-hover table-bordered table-striped"  >
                    <thead >
                        <tr>
                            <th>Roll</th>
                            <th>Student</th>
                            <?php
                                foreach( $sub_arr as $ex){
                                ?>
                                <th align="center" style="  text-align: center">
                                    <a href="<?php echo base_url();  ?>index.php/exams/edit_mc_marks/<?php echo $exam->id?>/<?php echo $this->input->get("section");  ?>/<?php echo $ex['id'] ?>" rel="tooltip" title="" data-original-title="Edit <?php echo $ex['subject']   ?> Marks " aria-hidden="true"><i class="fa fa-pencil-square" aria-hidden="true"></i>&nbsp;<?php echo $ex['subject']   ?></a>
                                    <table class="table-bordered " style="width: 100%; padding: 0px;">
                                        <tr>
                                            <th>c</th>
                                            <th>w</th>
                                            <th>L</th>
                                            <th>M</th>
                                            <th>R</th>
                                        </tr>
                                    </table>
                                </th>
                                <?php
                                }                                      
                                ?>
                                <th>Total<br/></th>
                                <th>Rank<br/></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       // print_r($stud_marks);
                        foreach($stud_marks as $key=>$values){
                            ?>
                        <tr>
                            <td><?php echo $values['roll'] ?></td>
                            <td><?php echo $values['name'] ?></td>
                            <?php
                                foreach( $sub_arr as $k=>$exam){
                                    if(isset($values[$k])){
                                        ?>
                                <td style="  text-align: center">
                                    <table class="table-bordered" style="width: 100%; padding: 0px;">
                                        <tr>
                                            <td><?php
                                            if($values[$k]['marks']!=-1){
                                               echo $values[$k]['correct'] ;
                                            }else{
                                                echo "A";
                                            }    ?></td>
                                            <td><?php
                                            if($values[$k]['marks']!=-1){
                                               echo $values[$k]['wrong'] ;
                                            }else{
                                                echo "A";
                                            }    ?></td>
                                            <td><?php
                                            if($values[$k]['marks']!=-1){
                                               echo $exam['questions']-($values[$k]['correct']+$values[$k]['wrong'])  ;
                                            }else{
                                                echo "A";
                                            }    ?></td>
                                            <td><?php
                                            if($values[$k]['marks']!=-1){
                                               echo $values[$k]['marks'];
                                            }else{
                                                echo "A";
                                            }    ?></td>
                                             <td><?php
                                            if($values[$k]['marks']!=-1){
                                               echo  find_pos($ranks[$k], $values[$k]['marks']);
                                            }else{
                                                echo "A";
                                            }    ?></td>
                                           
                                        </tr>
                                    </table>
                                </td>
                                <?php
                                    }else{
                                        ?>
                                <td style="  text-align: center">
                                    <table class="table-bordered" style="width: 100%">
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    </table>
                                </td>
                                <?php
                                    }
                                
                                }                                      
                                ?>
                                <td><?php echo $values['total'] ?></td>
                                <td><?php echo find_pos($totals,$values['total']) ?></td>
                        </tr>
                            <?php
                        }
                        ?>
                    </tbody>                    
                </table>
                                  
           </div>
        </div>
        <?php
        }else{
            ?>
                       <div class="box box-bordered box-color ">
                        <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Results Of <?php echo strtoupper( $exam->exam) ?> </h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <form class='form-horizontal form-bordered'  >
                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Section</label>
                                        <div class="col-sm-10">
                                            <select id="section_id" name="section" class="select2-me" style=" width: 100% ">
                                                <option value="" >Select A Section</option>
                                                <?php
                                                 foreach ($query as  $value) {
                                                     if( $value->sub_count !=0){
                                                       ?>
                                                <option value="<?php echo $value->sid."-".$value->id ?>"
                                                        <?php
                                                          if($this->input->get("section")==$value->sid."-".$value->id){
                                                              echo "selected";
                                                          }
                                                        ?>
                                                        ><?php echo $value->class." - ".$value->section ;?></option>
                                                     <?php     
                                                        }
                                                     
                                                 }
                                                ?>
                                            </select>
                                            <span id="subname_err" style=" color: red">
                                                 <?php echo $this->form->error('exam') ?>   
                                            </span>        
                                        </div>
                                </div> 
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-primary" type="submit" >Fetch Result's </button>
                                </div>
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