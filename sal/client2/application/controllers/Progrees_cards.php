<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Progrees_cards extends CI_Controller {

    function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('examinations');
            $this->load->model('form');
            $this->load->library("pagination");$this->operations->is_login();
            /* cache control */
            
            $check="SELECT `exams` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->exams==0){
                 $this->session->set_userdata('blocked_module', 'Library Module'); 
                redirect("/Acessdenied/","refresh");
            }
        }
    
    public function index(){
        $this->load->view('Progrees_cards/index.php');
    }
    
    public function  report_cards($sid,$exam_ids){
       
        if((strlen($sid)==0)||(strlen($exam_ids)==0) ){
            redirect("Progrees_cards/","refresh");
        }else{
            $cls= "SELECT s.sid,s.name as sec_name , c.name as cls_name FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' AND s.sid='".$sid."' ";
            $cls = $this->db->query($cls)->row();
            //print_r($cls);
             $exam_ids =str_replace("_",",",$exam_ids);
             $exam_ids = array_filter(explode(",",$exam_ids));
             $exam_ids = implode(",", $exam_ids);
            $exams ="SELECT ec.id , e.exam , e.startdate , e.enddate , (select count(*) FROM `exam`  where ecid = ec.id ) as exams,e.type FROM `examination_cls` ec JOIN examinations e ON ec.examid=e.id where ec.sectionid='".$sid."' AND ec.id IN (".$exam_ids.") ";   
            $exams = $this->db->query($exams)->result();
            $desc_exams="";
            $mul_exams="";
            foreach ($exams as  $value) {
                if($value->type==1){
                    $desc_exams.=$value->id.",";
                }else{
                       $mul_exams.=$value->id.",";
                }
            }
            $desc_exams = substr($desc_exams,0, strlen($desc_exams)-1);
            $mul_exams = substr($mul_exams,0, strlen($mul_exams)-1);
            
            // print_r($exams);
            
            $examination_details=array();
            $course ="SELECT c.cid,s.sid,s.subject from course c  JOIN subjects s ON c.subid=s.sid WHERE c.secid='".$cls->sid."' ";
            $course=  $this->db->query($course)->result();
            $query="SELECT e.id,e.ecid,e.maxmarks,e.minmarks,s.sid,s.subject FROM `exam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE e.ecid IN  (".$desc_exams.")  ";
            $query=$this->db->query($query); 
            $query=$query->result();
            $exams_ids="";
            $overall_totals=array();
                    
            $att_month_query=array();
            $att_query=$this->db->query("SELECT day,group_concat(id) as attd_id FROM `attendance_date` where section='".$cls->sid."'  GROUP BY day ORDER BY day ASC");
            $att_query=$att_query->result();
            $prev_month="";
            foreach($att_query as $at){
                $day  = getdate($at->day);
                $mont_yr=strtolower(substr($day['month'],0,3).",".$day['year']);
              //echo "<br/>".$at->attd_id;
                if(!isset($att_month_query[strtolower($mont_yr)])){
                    $att_month_query[$mont_yr]=array("slots"=>$at->attd_id,"count"=>sizeof(array_filter( explode(",",$at->attd_id))));
                }else{
                    $slots=$att_month_query[$mont_yr]['slots'];
                     $slots.=",".$at->attd_id;
                    $att_month_query[$mont_yr]['slots']=$slots;
                    $att_month_query[$mont_yr]['count']=$att_month_query[$mont_yr]['count']+sizeof(array_filter( explode(",",$at->attd_id)));
                }
                $prev_month=$mont_yr;
            }
            
            
            
            $marks=array();
            $ranks=array();
            $stud_marks=array();
            $s=$this->db->query("SELECT student_id as id,name,roll,userid,photo,admission_no from student WHERE section_id ='".$cls->sid."'  ")->result();;
            foreach($s as $value){
                $stud_marks[$value->id]=array('name'=>$value->name,'id'=>$value->id);
                foreach ($att_month_query as $at=>$val) {
                    $slots= implode(",",array_filter(explode(",",$val['slots'])));
                    $count=$this->db->query( "SELECT count(*) as count FROM `attendance` where student='".$value->id."' AND date_id IN(".$slots.")")->row();
                    $count =$count->count;
                    $stud_marks[$value->id]['attendance'][$at]=$count;
                }
            }
            foreach($query as $value){
                if(!isset($overall_totals[$value->ecid]["total"])){
                     $overall_totals[$value->ecid]["total"]=0;
                 }
               $overall_totals[$value->ecid]["total"]+=$value->maxmarks;
               $examination_details[$value->ecid][$value->sid] = array("Name"=>$value->subject,"max"=>$value->maxmarks,"min"=>$value->minmarks);
                   $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN marks m ON  m.student_id=st.student_id WHERE st.section_id ='".$cls->sid."' AND m.exam_id='".$value->id."'  ORDER BY st.student_id ")->result();
                    foreach($k as $p){
                        if($p->marks!=-1){
                            $marks[$value->ecid][$value->sid][$p->student_id]=$p->marks ;
                           $ranks[$value->ecid][$value->id][$p->marks]=$p->marks;
                           if(!isset($stud_marks[$p->student_id][$value->ecid]['total'])){
                                $stud_marks[$p->student_id][$value->ecid]['total']=0;
                            }
                            $stud_marks[$p->student_id][$value->ecid]['total']+=$p->marks; 
                        }else{
                           $marks[$value->ecid][$value->sid][$p->student_id]="A";
                        }
                    }     
               
            }
            
            if(strlen($mul_exams)!=0){
                $mulquery="SELECT e.id,e.ecid,e.maxmarks,s.sid,s.subject FROM `mcexam` e JOIN course c ON e.courseid=c.cid JOIN subjects s ON e.subid=s.sid WHERE e.ecid IN  (".$mul_exams.")  ";
                $mulquery=$this->db->query($mulquery); 
                $mulquery=$mulquery->result();
                foreach($mulquery as $value){
                    if(!isset($overall_totals[$value->ecid]["total"])){
                         $overall_totals[$value->ecid]["total"]=0;
                     }
                   $overall_totals[$value->ecid]["total"]+=$value->maxmarks;
                   $examination_details[$value->ecid][$value->sid] = array("Name"=>$value->subject,"max"=>$value->maxmarks,"min"=>0);
                   
                   $k=$this->db->query("SELECT st.student_id,m.marks FROM `student` st LEFT JOIN mcmarks m ON  m.student_id=st.student_id WHERE st.section_id ='".$cls->sid."' AND m.exam_id='".$value->id."'  ORDER BY st.student_id ")->result();
                    foreach($k as $p){
                        if($p->marks!=-1){
                            $marks[$value->ecid][$value->sid][$p->student_id]=$p->marks ;
                           $ranks[$value->ecid][$value->id][$p->marks]=$p->marks;
                           if(!isset($stud_marks[$p->student_id][$value->ecid]['total'])){
                                $stud_marks[$p->student_id][$value->ecid]['total']=0;
                            }
                            $stud_marks[$p->student_id][$value->ecid]['total']+=$p->marks; 
                        }else{
                           $marks[$value->ecid][$value->sid][$p->student_id]="A";
                        }
                    }     
               
                    
                   
                } 
            }
            
            
            function find_grade($total,$overall){
                $per=($total/$overall)*100;
                $per=number_format ($per,2);
                $grade ="";
                switch($per){
                    case $per>90 : $grade="O"; break;
                    case $per>80 : $grade="A"; break;
                    case $per>70 : $grade="B"; break;
                    case $per>60 : $grade="C"; break;
                    case $per>50 : $grade="D"; break;
                    case $per>40 : $grade="E"; break;
                    default : $grade="F";break;
                }
                return $grade;
            }
            //print_r($marks);
            //print_r($stud_marks);
            foreach($exams as $val){
                
                $grade_array[$val->id] = array('O'=>0,'A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0,'F'=>0);
                foreach ($stud_marks as $k=>$value) {
                    if(isset($stud_marks[$k][$val->id]['total'])){
                        $stud_marks[$k][$val->id]['grade']=find_grade($stud_marks[$k][$val->id]['total'],$overall_totals[$val->id]["total"]);
                        $grade_array[$val->id][$stud_marks[$k][$val->id]['grade']]++;
                        $totals[$val->id][$stud_marks[$k][$val->id]['total']]=$stud_marks[$k][$val->id]['total'];
                    }else{
                        $grade_array[$val->id]["F"]++;
                    }
                }
                krsort($totals[$val->id]); 
             
            }
            
            
            
                                   $institute= $this->fetch_institute_details();
  //      $this->load->library('Pdf');
       echo $header='  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Progress Report</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />
<style>
body {
  -webkit-print-color-adjust: exact;
}
.verticalLine {
  border-left: 3px inset #6C0000;
  padding:5px;
  margin:5px;
}
.tab{
	width:100%;
}
.tab td{
	text-align:center;
	width:16%;
	font-size:12px;
}
body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:780px;margin-left:auto;padding:5px; margin-right: auto; }
.right_txt p{padding:0px; margin:0px; text-align:justify; color:#407c3d; text-align:right; line-height:1.4em; }
p{ text-align:justify;}
.left{float:left; padding:0px; margin:0px;}
.right{float:right;padding:0px; margin:0px;	}
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

</style>
</head>
<body>';        
        $html_final='';
$institute_html='<div class="total"  style="clear:both;border:3px solid #000000;padding:20px; height: 1160px;">
<div>
<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#FFFFFF">
<tr><td align="left"><img src="'. assets_path ."/uploads/".$institute->logo.'" width="148" height="77" /></td>
<td align="center"><!--<img src="#" width="125" height="92" />--></td>
<td align="right">
<div class="right_txt" style="color:#00306C"><h1 style="font-size:22px; ">'.strtoupper($institute->name).'</h1><p>
'.str_replace("\n", "<br/>", $institute->address).'</p>
</div>
</td>
</tr>
</table>
<hr color="#00306C" />
<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
<h2>Progress Report</h2>
</div>
<div style="border:2px solid #00306C;height:160px;">
';
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

                foreach($s as $stud){    
                    $stud_html_str='';
                    $stud_html_str.=$institute_html;
                    $std_image="dummy_user.png";
                      if(!(strlen($stud->photo)!=0)){
                         if(file_exists(assets_path."/uploads/".$stud->photo)){
                           $std_image =$stud->photo  ;
                         }            
                      }

                    $stud_details='<div style="float:left;width:35%">
                                            <img src="'.assets_path .'/uploads/'.$std_image.'"  width="160" height="120" style="padding:10px;margin:10px;" />
                                    </div>
                                    <div class="verticalLine" style="float:left;height:138px;">&nbsp;</div>
                                    <div  style="float:left;padding:5px;">
                                    <table align="left" class="tab_td2" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                            <td>Name</td>
                                        <td>:</td>
                                        <th>'.$stud->name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Class</td>
                                        <td>:</td>
                                        <th>'.$cls->cls_name.'-'.$cls->sec_name.'</th>
                                    </tr>
                                    <tr>
                                            <td>Medium</td>
                                        <td>:</td>
                                        <th>English</th>
                                    </tr>
                                    <tr>
                                            <td>Admission No</td>
                                        <td>:</td>
                                        <th>'.$stud->userid.'</th>
                                    </tr>
                                    <tr>
                                            <td>R.No</td>
                                        <td>:</td>
                                        <th>'.$stud->roll.'</th>
                                    </tr>

                                    </table>
                                    </div>
                                    </div>
                                    <br style="clear:both" />
                                    ';
                    $stud_html_str.=$stud_details;
                    $width =100/(sizeof($exams)+1);
                    $exam_html='<div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
                                        <h2>Report Card</h2>
                                        </div>';
                    $exam_html.= '<table align="left" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <th width="'.$width.'%">SUBJECT</th>';
                    foreach ($exams as  $e) {
                    $exam_html.='<th>'.strtoupper($e->exam).'</th>';
                    }
                    $exam_html.='</tr>';
                    foreach ($course as $sub) {
                        $exam_html.='<tr><th>'.strtoupper($sub->subject).'</th>';
                        foreach ($exams as  $e) {
                           $fail="";
                           
                            if(isset($marks[$e->id][$sub->sid][$stud->id])){
                                 if($marks[$e->id][$sub->sid][$stud->id]<$examination_details[$e->id][$sub->sid]['min']){
$fail=' style="color:red" ';
                                }
                                $exam_html.='<td><span '.$fail.'>'.$marks[$e->id][$sub->sid][$stud->id].'</span> / '.$examination_details[$e->id][$sub->sid]['max'].' </td>';
                            }else{
                                $exam_html.='<td>--</td>';
                            }
                        }
                        $exam_html.='</tr>';
                    }
                    $exam_html.='<tr><th>TOTAL MARKS</th>';
                    foreach ($exams as  $e) {
                        $exam_html.='<td>'.$overall_totals[$e->id]["total"].'</td>';
                    }
                    $exam_html.='</tr>';
                    $exam_html.='<tr><th>TOTAL SECURED</th>';
                    foreach ($exams as  $e) {
                        if(isset($stud_marks[$stud->id][$e->id]["total"])){
                                $exam_html.='<td>'.$stud_marks[$stud->id][$e->id]["total"].'</td>';
                            }else{
                                $exam_html.='<td>--</td>';
                            }
                    }
                    $exam_html.='</tr>';
                    $exam_html.='<tr><th>PERCENTAGE</th>';
                    foreach ($exams as  $e) {
                        $exam_html.='<td style="color:#268e00; font-size:16px"><strong>';
                        if(isset($stud_marks[$stud->id][$e->id]["total"])){
                               $exam_html.=(number_format( ($stud_marks[$stud->id][$e->id]["total"]/$overall_totals[$e->id]['total']),2 )*100);
                            }else{
                                $exam_html.='--';
                            }
                            $exam_html.='</strong> % </td>';
                    }
                    $exam_html.='</tr>';
                    $exam_html.='<tr><th>GRADE</th>';
                    foreach ($exams as  $e) {
                        if(isset($stud_marks[$stud->id][$e->id]["grade"])){
                                $exam_html.='<td>'.$stud_marks[$stud->id][$e->id]["grade"].'</td>';
                            }else{
                                $exam_html.='<td>--</td>';
                            }
                    }
                    
                    $exam_html.='</tr>';
                    $exam_html.='<tr><th>RANK</th>';
                    foreach ($exams as  $e) {
                            if(isset($stud_marks[$stud->id][$e->id]["total"])){
                                $exam_html.='<td>'.find_pos($totals[$e->id],$stud_marks[$stud->id][$e->id]["total"]).'</td>';
                            }else{
                                $exam_html.='<td>--</td>';
                            }
                    }
                    $exam_html.='</tr>';
                    
                    //$course
                    $exam_html.='</table><br style="clear:both"/><br/><br/><br/>';
                    $mont_html="";
                        $mn_per_html="";
                        foreach($att_month_query as $att=>$per){
                            
                            $mont_html.="<th>".strtoupper($att)."</th>";
                            $mn_per_html.="<td>".( number_format(($stud_marks[$stud->id]['attendance'][$att]/$per['count'])*100,2) )."%</td>";
                        }
                        $attendance_html='
                        <div style="border:2px solid #00306C;text-align:center;background-color:#A4A4A4;">
                        <h2>Attendance Report</h2>
                        </div>

                        <div style=" text-align:center">
                        <table align="center" class="tab_td1" border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>'.$mont_html.'
                        </tr>
                        <tr>'.$mn_per_html.'</tr>
                        </table>

                        </div>
                        ';
                    $sub_str="";$stud_total=0;
                        $signs='<div style="clear:both;"></div>
                                <br />
                                </div>
                                <br />
                                <br />
                                <br />
                                <br />
                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Student Sign</span>
                                <br />
                                <br />
                                </div>

                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Parent Sign</span>
                                <br />
                                <br />
                                </div>

                                <div  style="width:33%;float:left;text-align:center">
                                <br />
                                <br />
                                <br />
                                <span style="border-top:1px solid #000000;text-align:center;font-size:14px">Principal Sign</span>
                                <br />
                                <br />
                                </div>
                                <br />
                                <br />
                                <br />
                                <br />

                                <div style="clear:both;"></div>
                                <hr color="#00306C" />
                                <table class="tab">
                                <tr>
                                        <td>A</td><td>B</td><td>C</td><td>D</td><td>E</td><td>F</td>
                                </tr>
                                <tr>
                                    <td width="16%">90% and Above</td>
                                    <td width="16%">80% and < 90%</td>
                                        <td width="16%">70% and < 80%</td>
                                        <td width="16%">60% and < 70%</td>
                                    <td width="16%">50% and < 60%</td>
                                    <td width="16%">40% and < 50%</td>
                                </tr>
                                </table>

                                <div style="clear:both;"></div>
                                
                                </div>
                                ';
                                        
                            $stud_html_str.=$exam_html.$attendance_html.$signs;
                                                                 echo $stud_html_str;
                
                                                                 
                         }
              
            
          
            
            
        }
        
        
        
    }
    
    private function fetch_institute_details(){
             $query=  $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
              return  $query->row();
        }
    
    

}
?>
    