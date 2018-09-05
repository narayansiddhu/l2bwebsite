<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ajax extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('logs');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->library("pagination");
            /* cache control */
           $this->operations->is_login();
        }
        
	public function index()
	{
           if(is_null($this->session->userdata('staff_id'))){
             $this->load->view('login');
           }	   
	}
        
        public function Load_assign(){
           $cid=$this->input->post('cid');
           $arr=$this->fetch_course($cid);
           $arr->tid;
           ?>
           <select class='select2-me' style="width:50%;" id="select_<?php echo $cid ?>" >
                    <option value="">Select A Teacher</option>
                     <?php
                     $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level'=>1 );
                     $query1 = $this->db->get_where('staff', $credential);
                     $query1=$query1->result();
                     foreach($query1 as $val){
                        ?>
                        <option value="<?php echo $val->id ?>"  <?php 
                        if($arr->tid==$val->id ){
                          echo "selected";  
                        }?>  ><?php echo $val->name ?></option>
                      <?php
                     }

                     ?>
           </select>  &nbsp;&nbsp;&nbsp;<button  onclick="save_faculty('<?php echo $cid; ?>');"  class="btn btn-mini" rel="tooltip" title="" data-placement="right" data-original-title="Save Faculty" >
                                           Save
                                         </button>
           <span style="color: red" id="error_<?php echo $cid ?>" ></span>
           <?php
        }
        
        private function fetch_course($id){
           $credential = array('cid' =>$id);
           $query = $this->db->get_where('course', $credential);
           $query=$query->row();
           return $query;
        }
        
        public function save_assigned(){
        
           $i=$this->input->post('i');
            $data = array(
                    'tid' => $this->input->post('staff')
                    );
                    $id=trim($this->input->post('cid'));
                    $this->db->where('cid', $id);
                    $this->db->update('course', $data);  
         $this->logs->insert_staff_log(15,'Edited Assigned faculty ',$id);
         $query = $this->db->query("SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE c.cid='".$this->input->post('cid')."' ORDER BY sec.sid , s.sid ASC");
         $query=$query->row();
            ?>
                                                  <td id="id_<?php   echo $query->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                   <td style=" width: 15%" ><?php echo $query->subject; ?></td>
                                   <td  style=" width: 40%"><?php if($query->name !=NULL ){
                                                       echo $query->name;
                                                   }else{
                                                       echo "--";
                                                   }?></td>
                                   <td id="td_actions_<?php   echo $query->cid; ?>" >
                                       <?php if($query->name !=NULL ){
                                           ?>
                                       <button  onclick="assign_faculty('<?php echo $query->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Edit Faculty" >
                                           <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }else{
                                           ?>
                                       <button onclick="assign_faculty('<?php echo $query->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Assign Faculty" >
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }?>
                                       
                                       
                                   </td>

           <?php
        }
        
        public function exam_Settings(){
            
            $ecid=explode('-',$this->input->post('ecid'));
            $query=  $this->db->query("SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc");
            $query=$query->result();
            $days =array();
            $t=$strt=$this->input->post('from');
            $end=$this->input->post('end');
            while($t<=$end){
              $days[]=date("d-m-y",$t);
              $t=strtotime('+1 day', $t);
            }
            
            $sectiondetails=  $this->get_class_Section_detail($ecid[1]);
            ?>
              
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Settings Of <?php echo $sectiondetails->class.",".$sectiondetails->section ?> </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exam/savesettings" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="examid" value="<?php echo  $this->input->post('examid'); ?>"/>
                                    <input type="hidden" name="ecid" value="<?php echo  $this->input->post('ecid'); ?>"/>
                         <table style=" width: 100%" class="table table-hover" >
                            <thead>
                                <tr>
                                    
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start time</th>
                                    <th>Span</th>
                                    <th>Max Marks</th>
                                    <th>Min Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                  $ids="";
                                  foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                            <select class="select2-me" name="day_<?php echo $value->id ?>" <?php
                                          if(strlen($this->form->error('day_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?> >
                                             <?php
                                               
                                                if(strlen($this->form->value('day_'.$value->id))>0 ){
                                                   $edate= $this->form->value('day_'.$value->id);
                                                }else{
                                                  $edate= date("d-m-y",$value->examdate);  
                                                }
                                                ?>
                                                <option value="" >select</option>
                                                <?php
                                                foreach ($days as $day) {
                                                    ?><option value="<?php echo $day; ?>" <?php
                                                    if($day==$edate){
                                                       echo "selected";
                                                    }?>  ><?php echo $day; ?></option><?php
                                                }
                                             ?>
                                            </select>
                                           </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <input <?php
                                          if(strlen($this->form->error('start_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  type="text" min="0" name="start_<?php echo $value->id ?>" class="form-control" value="<?php echo $starttime ?>" />
                                        </td>
                                        <td>
                                            <input <?php
                                          if(strlen($this->form->error('span_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  type="text" min="0" name="span_<?php echo $value->id ?>" class="form-control" value="<?php
                                        if(strlen($this->form->value('span_'.$value->id))>0 ){
                                                  echo $this->form->value('span_'.$value->id);
                                                }else{
                                                  echo $value->timespan; 
                                                }?>"  />
                                        </td>
                                        <td><input type="text"  <?php
                                          if(strlen($this->form->error('max_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>  name="max_<?php echo $value->id ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>"   /></td>
                                        <td>
                                            <input type="text"
                                                   <?php
                                          if(strlen($this->form->error('min_'.$value->id))>0 ){
                                                 echo  ' style="border:1px solid #FF0000"';
                                          }
                                           ?>   name="min_<?php echo $value->id ?>" min="0" class="form-control" value="<?php
                                        if(strlen($this->form->value('min_'.$value->id))>0 ){
                                                  echo $this->form->value('min_'.$value->id);
                                                }else{
                                                  echo $value->minmarks; 
                                                }?>"  /></td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            </tbody>
                            
                         </table> 
                        <span style=" color: red" >
                           <?php 
                                      $idsarr=  explode(',', $ids);
                                      $c=0;
                                      foreach ($idsarr as $id) {
                                          if(strlen($this->form->value('slot_error_'.$id))>0 ){
                                                 echo "<br/>".$this->form->value('slot_error_'.$id);
                                               }
                                      }
                                   ?>
                        </span>
                                    
                        <div class="table-pagination">
                            <button class="btn btn-primary">Save Setting's</button>
                         </div>
                             
                    </form>
                </div>
             </div>
                
              <?php
            
            
            
        }
        
        public function viewmcexam_Settings(){
            
            $ecid=explode('-',$this->input->post('ecid'));
            $query=  $this->db->query("SELECT e.id,sub.sid,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`questions` ,(select count(*) from mcmarks where  exam_id=e.id) as t_marks FROM `mcexam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc");
            
            $days =array();
            $t=$strt=$this->input->post('from');
            $end=$this->input->post('end');
            while($t<=$end){
              $days[]=date("d-m-y",$t);
              $t=strtotime('+1 day', $t);
            }
            
            $sectiondetails=  $this->get_class_Section_detail($ecid[1]);
            ?>
                                           
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exam/savesettings" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="examid" value="<?php echo  $this->input->post('examid'); ?>"/>
                                    <input type="hidden" name="ecid" value="<?php echo  $this->input->post('ecid'); ?>"/>
                         <table style=" width: 100%" class="table table-hover table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start time</th>
                                    <th>Span</th>
                                    <th>Questions</th>
                                    <th>Max Marks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                  $ids="";
                                if($query->num_rows()>0){
                                    $query=$query->result();
                                
                                    foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                          <?php echo date("d-m-y",$value->examdate); ?>
                                           </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <?php echo $starttime ?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('span_'.$value->id))>0 ){
                                                  echo $this->form->value('span_'.$value->id);
                                                }else{
                                                  echo $value->timespan; 
                                                  }?>&nbsp;Min
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('ques_'.$value->id))>0 ){
                                                  echo $this->form->value('ques_'.$value->id);
                                                }else{
                                                  echo $value->questions; 
                                                }?></td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>
                                        </td>
                                        <td>
                                            <?php
                                               if($value->t_marks==0){
                                                   ?>
                                            <a target="_blank" onclick="window.open('<?php echo base_url() ?>index.php/exams/download_sheet?examid=<?php echo $this->input->post("examid") ?>&ecid=<?php echo $ecid[0].",".$ecid[1] ?>&subject=<?php echo $value->id ?>');window.open('<?php echo base_url(); ?>index.php/exams/add_marks/<?php echo $this->input->post("examid") ?>?mcid=<?php echo $ecid[0].",".$ecid[1] ?>&subject=<?php echo $value->id ?>');">Add Marks</a><?php
                                               }else{
                                                   ?>
                                            <a target="_blank" href="<?php echo base_url() ?>index.php/exams/edit_mc_marks/<?php echo $this->input->post("examid") ?>/<?php echo $ecid[1]."-".$ecid[0] ?>/<?php echo $value->id ?>" >View/Edit</a><?php
                                               }
                                            ?>
                                        </td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="6" style=" text-align: center ; color: red">** Schedule Not Configured</td>
                                    </tr>
                                     <?php
                                }
                                
                                  
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            </tbody>
                            
                         </table> 
                        <span style=" color: red" >
                           <?php 
                                      $idsarr=  explode(',', $ids);
                                      $c=0;
                                      foreach ($idsarr as $id) {
                                          if(strlen($this->form->value('slot_error_'.$id))>0 ){
                                                 echo "<br/>".$this->form->value('slot_error_'.$id);
                                               }
                                      }
                                   ?>
                        </span>
                                    
                        
                             
                    </form>
            <?php
            
            
            
        }
        
        public function viewexam_Settings(){
            
            $ecid=explode('-',$this->input->post('ecid'));
			
           // echo "SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc";
            $query=  $this->db->query("SELECT e.id,sub.subject,e.`examdate`,e.`starttime`,e.`endtime`,e.`timespan`,e.`maxmarks`,e.`minmarks` FROM `exam` e JOIN subjects sub ON e.subid=sub.sid JOIN examination_cls ec ON e.ecid=ec.id  WHERE ec.`id` ='".$ecid[0]."'  ORDER BY sub.sid asc");
            
            $days =array();
            $t=$strt=$this->input->post('from');
            $end=$this->input->post('end');
            while($t<=$end){
              $days[]=date("d-m-y",$t);
              $t=strtotime('+1 day', $t);
            }
            
            $sectiondetails=  $this->get_class_Section_detail($ecid[1]);
            ?>
                                           
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/exam/savesettings" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="examid" value="<?php echo  $this->input->post('examid'); ?>"/>
                                    <input type="hidden" name="ecid" value="<?php echo  $this->input->post('ecid'); ?>"/>
                         <table style=" width: 100%" class="table table-hover table-bordered table-striped" >
                            <thead>
                                <tr>
                                    
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th>Start time</th>
                                    <th>Span</th>
                                    <th>Max Marks</th>
                                    <th>Pass Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                  $ids="";
                                if($query->num_rows()>0){
                                    $query=$query->result();
                                
                                    foreach ($query as $value) {
                                      $ids.=$value->id.",";
                                           ?>
                                <tr>
                                        <td>
                                            <?php  echo $value->subject; ?>
                                            <input type="hidden" name="subject_<?php echo $value->id ?>" class="form-control" value="<?php  
                                             echo $value->subject; 
                                            ?>" />
                                        </td>
                                        <td>
                                          <?php echo date("d-m-y",$value->examdate); ?>
                                           </td>
                                        <td>
                                            <?php 
                                              if(strlen($this->form->value('start_'.$value->id))>0 ){
                                                   $starttime= $this->form->value('start_'.$value->id);
                                                }else{
                                                  $starttime=  $value->starttime; 
                                                  $starttime=date('H:i',$starttime); 
                                                }
                                                 
                                             ?>
                                            <?php echo $starttime ?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('span_'.$value->id))>0 ){
                                                  echo $this->form->value('span_'.$value->id);
                                                }else{
                                                  echo $value->timespan; 
                                                  }?>&nbsp;Min
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('max_'.$value->id))>0 ){
                                                  echo $this->form->value('max_'.$value->id);
                                                }else{
                                                  echo $value->maxmarks; 
                                                }?>
                                        </td>
                                        <td>
                                            <?php
                                        if(strlen($this->form->value('min_'.$value->id))>0 ){
                                                  echo $this->form->value('min_'.$value->id);
                                                }else{
                                                  echo $value->minmarks; 
                                                }?></td>
                                          
                                    </tr>
                                        <?php
                                  }
                                   $ids=substr($ids,0,strlen($ids)-1);
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="6" style=" text-align: center ; color: red">** Schedule Not Configured</td>
                                    </tr>
                                     <?php
                                }
                                
                                  
                                   
                                ?>
                            <input type="hidden" name="exam_ids" value="<?php echo $ids ?>" />
                            </tbody>
                            
                         </table> 
                        <span style=" color: red" >
                           <?php 
                                      $idsarr=  explode(',', $ids);
                                      $c=0;
                                      foreach ($idsarr as $id) {
                                          if(strlen($this->form->value('slot_error_'.$id))>0 ){
                                                 echo "<br/>".$this->form->value('slot_error_'.$id);
                                               }
                                      }
                                   ?>
                        </span>
                                    
                        
                             
                    </form>
            <?php
            
            
            
        }
        
        public  function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false ) {
	$code_string = "";
	// Translate the $text into barcode the correct $code_type
	if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
		$chksum = 104;
		// Must not change order of array elements as the checksum depends on the array's key to validate final code
		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
		$code_keys = array_keys($code_array);
		$code_values = array_flip($code_keys);
		for ( $X = 1; $X <= strlen($text); $X++ ) {
			$activeKey = substr( $text, ($X-1), 1);
			$code_string .= $code_array[$activeKey];
			$chksum=($chksum + ($code_values[$activeKey] * $X));
		}
		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

		$code_string = "211214" . $code_string . "2331112";
	} elseif ( strtolower($code_type) == "code128a" ) {
		$chksum = 103;
		$text = strtoupper($text); // Code 128A doesn't support lower case
		// Must not change order of array elements as the checksum depends on the array's key to validate final code
		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
		$code_keys = array_keys($code_array);
		$code_values = array_flip($code_keys);
		for ( $X = 1; $X <= strlen($text); $X++ ) {
			$activeKey = substr( $text, ($X-1), 1);
			$code_string .= $code_array[$activeKey];
			$chksum=($chksum + ($code_values[$activeKey] * $X));
		}
		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

		$code_string = "211412" . $code_string . "2331112";
	} elseif ( strtolower($code_type) == "code39" ) {
		$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

		// Convert to uppercase
		$upper_text = strtoupper($text);

		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
			$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
		}

		$code_string = "1211212111" . $code_string . "121121211";
	} elseif ( strtolower($code_type) == "code25" ) {
		$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
		$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

		for ( $X = 1; $X <= strlen($text); $X++ ) {
			for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
				if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
					$temp[$X] = $code_array2[$Y];
			}
		}

		for ( $X=1; $X<=strlen($text); $X+=2 ) {
			if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
				$temp1 = explode( "-", $temp[$X] );
				$temp2 = explode( "-", $temp[($X + 1)] );
				for ( $Y = 0; $Y < count($temp1); $Y++ )
					$code_string .= $temp1[$Y] . $temp2[$Y];
			}
		}

		$code_string = "1111" . $code_string . "311";
	} elseif ( strtolower($code_type) == "codabar" ) {
		$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
		$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

		// Convert to uppercase
		$upper_text = strtoupper($text);

		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
			for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
				if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
					$code_string .= $code_array2[$Y] . "1";
			}
		}
		$code_string = "11221211" . $code_string . "1122121";
	}

	// Pad the edges of the barcode
	$code_length = 20;
	if ($print) {
		$text_height = 30;
	} else {
		$text_height = 0;
	}
	
	for ( $i=1; $i <= strlen($code_string); $i++ ){
		$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
        }

	if ( strtolower($orientation) == "horizontal" ) {
		$img_width = $code_length;
		$img_height = $size;
	} else {
		$img_width = $size;
		$img_height = $code_length;
	}

	$image = imagecreate($img_width, $img_height + $text_height);
	$black = imagecolorallocate ($image, 0, 0, 0);
	$white = imagecolorallocate ($image, 255, 255, 255);

	imagefill( $image, 0, 0, $white );
	if ( $print ) {
		imagestring($image, 5, 31, $img_height, $text, $black );
	}

	$location = 10;
	for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
		$cur_size = $location + ( substr($code_string, ($position-1), 1) );
		if ( strtolower($orientation) == "horizontal" )
			imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
		else
			imagefilledrectangle( $image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black) );
		$location = $cur_size;
	}
	
	// Draw barcode to the screen or save in a file
	if ( $filepath=="" ) {
		header ('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	} else {
		imagepng($image,$filepath);
		imagedestroy($image);		
	}
}

        
        public function active_exam(){
            $examid=  $this->input->post('examid');
            $data = array(
               'status' =>1,
               );

            $this->db->where('id', $examid);
            $this->db->update('examinations', $data); 
           $this->load_exam_details($examid,  $this->input->post('serial'));
        }
        
        public function inactive_exam(){
            $examid=  $this->input->post('examid');
            $data = array(
               'status' =>0,
               );

            $this->db->where('id', $examid);
            $this->db->update('examinations', $data);  
            $this->load_exam_details($examid,  $this->input->post('serial'));
        }
        
        private function load_exam_details($examid,$serial){
            $credential = array('id' =>$examid);
            $query = $this->db->get_where('examinations', $credential);
            $row=$query->row();
            ?>
            
                                         <td id="sno_<?php echo $row->id; ?>"><?php echo $serial ?></td>
                                        <td><?php echo $row->exam; ?></td>
                                        <td><?php echo date('d-m-y',$row->startdate); ?></td>
                                        <td><?php echo date('d-m-y',$row->enddate); ?></td>
                                        <td>
                                            <?php 
                                               if($row->status==1){
                                                   echo "Active";
                                               }else{
                                                   echo "In-Active";
                                               }
                                            
                                            ?>
                                        </td>
                                        
                                        <td>
                                             <?php
                                              if($row->status==1){
                                                 ?>
                                                   <a href="<?php echo base_url(); ?>index.php/exam/view_settings/<?php echo  $row->id ?>" class="btn ">
                                                       <i class="fa fa-eye" aria-hidden="true"></i>
                                                   </a> &nbsp;&nbsp;
                                                   <a href="<?php echo base_url(); ?>index.php/exam/settings/<?php echo  $row->id ?>" class="btn">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                   &nbsp;&nbsp;
                                                   <a  onclick="deactivate('<?php echo $row->id; ?>');"  class="btn">
                                                        <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                                    </a>
                                                <?php
                                              }else{
                                                  ?>
                                                  <a onclick="activate('<?php echo $row->id; ?>');" class="btn">
                                                        <i class="fa fa-toggle-on" aria-hidden="true"></i>
                                                    </a>
                                                        <?php
                                              }
                                             
                                             ?>
                                            
                                            
                                        </td>
            <?php
        }
        
        
        public function get_class_Section_detail($section){
           
           $query = $this->db->query("SELECT s.name as section ,c.name as class FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.sid='".$section."'");
           $query=$query->row();
           return $query;
        }
        
        public function fetch_support_queries(){
         $q=   "SELECT * , 
	IF( SUBSTR( a.userid, 1, 1 ) =  's', 
		(SELECT name FROM staff s WHERE SUBSTR(a.userid,2) = s.id) , 
		IF(SUBSTR( a.userid, 1, 1 ) =  'c', (SELECT name FROM parent p WHERE SUBSTR(a.userid,2) = p.parent_id),IF(SUBSTR( a.userid, 1, 1 ) =  'p', (SELECT name FROM student s WHERE SUBSTR(a.userid,2) = s.student_id),'in_valid' ) )) as raw 
, i.name  FROM  `support_queries` a JOIN institutes i ON i.id = a.iid";
        }
        
        
}
?>
