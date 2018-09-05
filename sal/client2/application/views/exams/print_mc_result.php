<?php
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

 echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Examination Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
                    
<style>
body {
 -webkit-print-color-adjust: exact;
}

body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px; }
h1, h2, h3, h4, h5{
padding:0px; margin:0px;
}
.subject{ font-weight:600; text-decoration:underline; text-align:center; font-size:18px;}

.tab_td1{border:1px solid #000; margim-bottom:150px;  }
.tab_td1  td{ text-align:center; border:1px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td1  th{ text-align:center;border:1px solid #000; padding:5px 0px;background-color:#E3E3E3; }

.listt{padding-left:25px; padding-top:0px; padding-bottom:0px; margin:0px;}

.tab_td2{border:0px solid #000; margim-bottom:150px;font-size:16px;  }
.tab_td2  td{ text-align:left; border:0px solid #000; padding:4px 2px; vertical-align:text-top; }
.tab_td2  th{ text-align:left;border:0px solid #000; padding:5px 0px; }
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
</style>
</head>
<body>
<div class="total"  style="border:3px solid #000000;padding:20px;">
<div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'"  width="148" height="60" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
                        '.str_replace("\n", "<br/>", $institute->address).'</p>
                        </div>
</td>
</tr>
</table>
<hr color="#00306C" />';
 ?>
      <h3 style=" text-align: center; color:  red; "><u>Results Of <?php echo $exam->exam ?> ,<?php echo $section_query->class ." - ".$section_query->section ?></u></h3>
           
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
                <br/>
            <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
                    <thead >
                        <tr>
                            <th>Roll</th>
                            <th>Student</th>
                            <?php
                                foreach( $sub_arr as $ex){
                                ?>
                                <th align="center" style="  text-align: center"><?php echo $ex['subject']   ?>
                                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
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
                                    <table align="center" class="tab_td1"  border="0" width="100%" cellpadding="0" cellspacing="0">
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
              
        <?php
        }
        else{
            redirect("exams/","refresh");
        }
        ?>
        
    </body>
    <script>
                    window.print();
                    </script>
</html>