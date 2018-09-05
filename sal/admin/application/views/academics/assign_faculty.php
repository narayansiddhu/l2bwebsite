<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');

$section ="";

if(isset($_GET['section']))
{
    $query=  $this->db->query("SELECT s.sid,s.name as section,c.id as class_id,c.name as class,st.name as staff_name,st.id as staff_id FROM `section` s LEFT JOIN staff st  on s.cls_tch_id=st.id JOIN class c ON s.class_id=c.id WHERE s.sid='".$_GET['section']."' AND s.iid='".$this->session->userdata('staff_Org_id')."'  ");
    if($query->num_rows()==0){
        $section ="";
    }else{
       $section_details=$query->row(); 
       $section =$_GET['section'];
    } 
}

?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Assign Faculty</a>
                </li>
            </ul>

    </div>
    <?php
    
        if(strlen($this->session->userdata('course_alter_sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                    <?php echo $this->session->userdata('course_alter_sucess'); ?>
            </div>
            <script>
               $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('course_alter_sucess');
        }
    ?>
    
           <?php
               if(strlen(trim($section))==0){
                   ?>
                    <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-th-list"></i>Assign Course Structure</h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <div  class='form-horizontal form-bordered' >
                              <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class-section</label>
                                        <div class="col-sm-10">
                                            <select name="section" onselect="load_settings();" onchange="load_settings();" id="class_name" class='select2-me' style="width:100%;"  >
                                                <option value="">Select A Section</option>
                                                 <?php
                                                 $query = $this->db->query("SELECT s.sid,s.name as sec,c.name as class FROM `section` s JOIN class c ON s.`class_id` =c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.id");
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->sid ?>" ><?php echo $val->class."--".$val->sec ?></option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>
                                             <span id='class_name_err' style=" color: red">
                                               <?php  echo $this->form->error('section');   ?>
                                             </span>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   <?php
               } else{
                   ?>
                     <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-th-list"></i>Assign Course Structure of <?php echo $section_details->class ." - ".$section_details->section ?></h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <form method="post" action="<?php echo base_url() ?>index.php/academics/add_faculty" class='form-horizontal form-bordered' >
                                <table class="table table-hover table-nomargin  table-bordered">
                                <?php
                                $su=" SELECT * FROM subjects where iid='".$this->session->userdata('staff_Org_id')."'";
                                $su =$this->db->query($su);
                                $staff = " SELECT * FROM staff where iid='".$this->session->userdata('staff_Org_id')."' AND level=1";
                                $staff =$this->db->query($staff);
                               
                                if(($su->num_rows()>0)&&($staff->num_rows()>0)){
                                     $staff = $staff->result();
                                     $su=$su->result();
                                    $course_array= array();
                                    $query=$query->result();
                                    $query="SELECT s.sid,c.cid,s.subject,st.id as staff_id,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$section_details->sid."' ORDER BY sec.sid , s.sid ASC";
                                    $query=$this->db->query($query);
                                    if($query->num_rows()>0){
                                        $query=$query->result();
                                        foreach($query as $val){
                                            $course_array[$val->sid] =array('course_id'=>$val->cid,'staff'=>$val->staff_id); 
                                        }
                                        
                                    }
                                    $subjects_all="";
                                    foreach($su as $val){
                                        $subjects_all.=$val->sid.",";
                                       ?>
                                       <tr>
                                           <td>
                                               <input type="checkbox" name="subject_<?php  echo $val->sid ?>" <?php
                                            if(isset($course_array[$val->sid])){
                                               echo 'checked="" disabled=""';
                                            }else{
                                                if(strlen($this->form->value('subject_'.$val->sid))!=0){
                                                               echo 'checked="" ';
                                                           }
                                            }
                                            ?> value="1"   />
                                           <?php
                                            if(isset($course_array[$val->sid])){
                                               ?>
                                               <input type="hidden" name="subject_<?php  echo $val->sid ?>" value="1"  />
                                               <?php
                                            }
                                            ?>
                                           </td>
                                           <td>
                                               <?php echo $val->subject ?>
                                           </td>
                                           <td>
                                               <select class="select2-me" name='sub_staff_<?php  echo $val->sid ?>' style=" width: 100%">
                                                   <option value="0" 
                                                           <?php
                                                           if(strlen($this->form->value('sub_staff_'.$val->sid))!=0){
                                                               if($this->form->value('sub_staff_'.$val->sid)==0){
                                                                     echo 'selected=""';
                                                                 }
                                                           }?>
                                                           >Select A Faculty</option>
                                                   <?php
                                                        foreach ($staff as $value) {
                                                            ?>
                                                   <option value="<?php  echo $value->id ?>"
                                                           <?php
                                                           if(strlen($this->form->value('sub_staff_'.$val->sid))!=0){
                                                               if($this->form->value('sub_staff_'.$val->sid)==$value->id){
                                                                     echo 'selected=""';
                                                                 }
                                                           }else{
                                                            if(isset($course_array[$val->sid])){
                                                                 if($course_array[$val->sid]['staff']==$value->id){
                                                                     echo 'selected=""';
                                                                 }
                                                            }
                                                           }
                                                            ?> 
                                                           ><?php  echo $value->name ?></option>
                                                         <?php
                                                        }
                                                   ?>
                                               </select>
                                               <span id='noofsecerr' style=" clear: both; color: red">
                                                        <?php
                                                            echo $this->form->error('sub_staff_'.$val->sid);   
                                                        ?>
                                                </span> 
                                           </td>
                                       </tr>
                                       <?php
                                    }
                                    
                                    $subjects_all = substr($subjects_all, 0, strlen($subjects_all)-1);
                                    ?>
                                       <input type="hidden" name="subjects_all" value="<?php echo $subjects_all ?>" />
                                       <input type="hidden" name="course_details" value='<?php echo json_encode($course_array) ?>' />
                                       <input type="hidden" name="section_val" value='<?php echo $section_details->sid ?>' />
                                        <tr>
                                           <td colspan="3" style=" text-align: center; ">
                                               <div class="row nopadding">
                                                   <div class="col-sm-4">
                                                       &nbsp;
                                                   </div>
                                                   <div class="col-sm-4">
                                                      <input type="submit" name="assign_faculty" value="Assign Faculty" class="btn btn-primary btn-block" />
                                                    </div>
                                                   <div class="col-sm-4">
                                                       &nbsp;
                                                   </div>
                                               </div>
                                            </td> 
                                        </tr>
                                    <?php
                                    
                                }else{
                                    ?>
                                    <tr>
                                        <td colspan="3" style=" text-align: center">
                                           Please add Subjects and Staff To Assign Faculty
                                        </td>
                                    </tr>
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
<script>
   function load_settings(){
     cls_name =$('#class_name').val();  
     if(cls_name.length==0){
         $('#class_name_err').html("Please select Section ");
     }else{
         window.location.replace("<?php echo base_url()?>index.php/academics/assign_faculty?section="+cls_name);        
     }
   }
</script>
<?php
$this->load->view('structure/footer');
?>

