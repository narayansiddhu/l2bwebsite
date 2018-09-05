<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {

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
        }
    
    public function index(){
        redirect("exams/","refresh");
    }
    
    public function view(){
        $this->load->view('exam/view');
    }
    
    public function add(){
        $this->load->view('exam/add');
    }
    
    public function assign($eid){
       
        $exam=$this->examinations->getexam_data($eid);
        if(!$exam){
             $this->load->view('exam/view');
        }else{
            $data['exam']=$exam;
             $this->load->view('exam/assign',$data);
        }
    }
    
    public function view_settings($eid=0){
       if($eid ==0){
           $this->load->view('exam/view'); 
        }else{
            
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 $this->load->view('exam/view');
            }else{
                $data['exam']=$exam;
              //  print_r($data); 
                 $this->load->view('exam/view_settings',$data);
            }
        } 
    }
    
    public function settings($eid=0){
        if($eid ==0){
           $this->load->view('exam/view'); 
        }else{
           
            $exam=$this->examinations->getexam_data($eid);
            if(!$exam){
                 $this->load->view('exam/view');
            }else{
                $data['exam']=$exam;
                 $this->load->view('exam/settings',$data);
            }
        }
    }
        
    public function create(){
        $post=$this->operations->cleanInput($_POST);
        $field = 'exam';
        if(strlen($post[$field]) ==  0)
        {
           $this->form->setError($field,'* Please Provide Exam Name');
        }elseif(!$this->examinations->checkexamname($post[$field])){
             $this->form->setError($field,'* Exam Name Already Exists');
        }
        
        $field = 'strtdate';
        $str=0;
        if(strlen($post[$field]) ==  0)
        {
                $this->form->setError($field,'* Please provide Starting date');
        }else{
            $str=explode("/",$post[$field]);
            $start= mktime(0,0,0,$str[1],$str[0],$str[2]);
            if($start <=time()){
                $this->form->setError($field,'* Please select a valid starting date');
            }
        }
        
        $field = 'enddate';
        $end=0;
            if(strlen($post[$field]) ==  0)
            {
                    $this->form->setError($field,'* Please provide Date Of Birth');
            }else{
                $str=explode("/",$post[$field]);
                $end= mktime(0,0,0,$str[1],$str[0],$str[2]);
                if($end <=time()){
                    $this->form->setError($field,'* Please select a valid ending date');
                }elseif($start!=0){
                   if($end<$start){
                       $this->form->setError($field,'* Please select a valid ending date'); 
                   }
                }
                
            }
            
       if($this->form->num_errors >0 ){
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $this->form->getErrorArray();
           redirect('exam/add', 'refresh');  
        }else{
            $data = array(
                       'iid' => $this->session->userdata('staff_Org_id'),
                       'exam' => $post['exam'],
                       'startdate' => $start,
                       'enddate'=>$end,
                       'status' =>1
                    );
             $this->db->insert('examinations',$data);
             $examid=$this->db->insert_id(); 
             $this->logs->insert_staff_log(17,'Created Examination ' .$post['exam'],$examid);
             redirect('exam/assign/'.$examid, 'refresh');
        }       
        
    }
    
    public function savesettings(){
       
       $post=$this->operations->cleanInput($_POST);
        
        $exam_timings=array();
        $exam_ids=explode(",",$post['exam_ids']);
        foreach($exam_ids as $exam_id){
            
           $iner_count=0;
           $field="day_".$exam_id;
           if(strlen($post[$field])==0){
               
              $this->form->setError($field,'* select Exam date'); 
           }else{
                $examdate=explode('-',$post["day_".$exam_id]);                  
                $frm = $post["start_".$exam_id]; 
                $to  =$post["ending_".$exam_id]; 
                 $time_err_c=0;
                if(strlen($frm)==0){
                    $time_err_c++;
                    $this->form->setError("start_".$exam_id,'* select From Time'); 
                }
                if(strlen($to)==0){
                    $time_err_c++;
                    $this->form->setError("ending_".$exam_id,'* select End Time'); 
                }
                            
                if($time_err_c ==0){
                    $d=array();
                        $d['subject']=$post['subject_'.$exam_id];
                        $d['date']=mktime(0,0,0,$examdate[1],$examdate[0],$examdate[2]);
                        $d['from']=  $this->validate_time($frm, $examdate);
                        $d['end']=$this->validate_time($to, $examdate);
                        $d['span']= ($d['end'] - $d['from'])/60;
                        if(!$this->check_slot($d['from'], $d['end'], $exam_timings)){
                           $this->form->setError('start_'.$exam_id,'* enter exam span');  
                        }else{
                            $exam_timings[]=$d;
                        }                
                }              
                
              }
          $field="max_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter max marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value max');
           }  
           $field="min_".$exam_id;
           if( (strlen($post[$field])==0) ||($post[$field]==0) ){
              $this->form->setError($field,'* enter min marks'); 
           }elseif(!is_numeric($post[$field])){
               $this->form->setError($field,'*enter numeric value min');
           } 
        }
        
        if($this->form->num_errors >0 ){
                $_SESSION['value_array'] = $_POST;
                $_SESSION['error_array'] = $this->form->getErrorArray();
                
         }else{
             $c=0;
              foreach($exam_ids as $exam_id){
                $data = array(
               'examdate' => $exam_timings[$c]['date'],
               'starttime' => $exam_timings[$c]['from'],
               'endtime' => $exam_timings[$c]['end'],
               'timespan' => $exam_timings[$c]['span'],
               'maxmarks' =>  $post['max_'.$exam_id],
               'minmarks' =>  $post['min_'.$exam_id],
               );
              
               $c++;
                $this->db->where('id', $exam_id);
                $this->db->update('exam', $data);  
             }
            $this->session->set_userdata('Section_exam_Settings', 'Sucessfully updated settings');   
           // redirect('exam/settings/'.$this->input->post('examid'), 'refresh'); 
            
         }
         redirect('exam/settings/'.$this->input->post('examid')."?section=".$this->input->post('section_details'), 'refresh'); 
    }
    
    private function check_slot($from,$to,$arr){
        
        foreach ($arr as $value) {
            
          if((($value['from'] <= $from) && ($from <= $value['end'])) ){
            
              return FALSE;
          }
          if((($value['from'] <= $to) && ($to <= $value['end'])) ){
          
              return FALSE;
          }
          
        }        
        return true;
    }
    
    public function configure(){
        $post=$this->operations->cleanInput($_POST);
        $examid=$post['exam_id'];
        $action=$post['action'];
        $sec=explode(',',$post['section_ids']);
        $counter=0;
        if($action=='insert'){
            foreach ($sec as $value) {
                if(isset($post['section_'.$value])){
                    $counter++;
                    $ecid=$this->examinations->add_exam($value,$examid); 
                   // $this->examinations->add_exam_subjects($value,$ecid,$examid);  
                }
            }
        }else{
            $previous=explode(',',$post['previous']);
            foreach ($sec as $value) {
                if(isset($post['section_'.$value])){
                    $counter++;
                    if(in_array($value, $previous)){
                       unset($previous[array_search($value, $previous)]);
                       $ecid=$this->examinations->check_exams($value,$examid);  
                    }else{
                        $ecid=$this->examinations->add_exam($value,$examid); 
                     $this->examinations->add_exam_subjects($value,$ecid,$examid);  
                    }
                    
                }
            }
            
            if(sizeof($previous)>0){
                foreach ($previous as $value) {
                   $this->examinations->deactive_exam($examid,$value);
                }
            }
            
        }
  
        if($counter==0){
             $this->session->set_userdata('class_exam_error', "Please select the section For the exam "); 
           redirect('exam/assign/'.$examid, 'refresh'); 
        }else{
            redirect('exam/settings/'.$examid, 'refresh');
        }
        
        
    }
    
    public function validate_time($ti , $examdate){
       $ti = explode(" ", $ti);
       $part1=$ti[0];
       $part2=$ti[1];
       $part1 = explode(":", $part1);
       $hours=$part1['0'];
       if($part2=="PM"){
           if($hours!=12){
              
                $hours= $hours+12;
                if($hours>=24){
                    $hours=$hours-24;
                }
           }
       }
     
        return mktime($hours,$part1[1],0,$examdate[1],$examdate[0],$examdate[2]);
    }
    
    public function check_results(){
       $this->load->view('exam/check_results');
    }
    
    
    public function load_sections(){
        $exam =$this->input->post("exam");
    }
    
    
}
?>
