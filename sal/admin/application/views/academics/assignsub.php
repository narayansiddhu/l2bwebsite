<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
       <br/>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/assign_sub/">Assign Faculty</a>
                </li>
            </ul>

    </div> 
        
        <?php
            if(strlen($this->session->userdata('course_add_Sucess'))>0 ){
                ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Success!</strong>
                     <?php echo $this->session->userdata('course_add_Sucess'); ?>
                    </div>
                <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
               <?php
                $this->session->unset_userdata('course_add_Sucess');
            }
            $secid="";
           if(strlen($this->form->value('section'))==0){
               
                if(isset($_GET['section'])){
                   $secid=$_GET['section'];
               }
            }
            
            ?>

       
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-th-list"></i>Assign Subject</h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <form action="<?php echo base_url(); ?>index.php/academics/add_course" method="post" class='form-horizontal form-bordered' >
                              <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class-section</label>
                                        <div class="col-sm-10">
                                            <select name="section" id="class_name" class='select2-me' style="width:100%;"  >
                                                <option value="">Select A Section</option>
                                                 <?php
                                                 
                                                
                                                 $query = $this->db->query("SELECT s.sid,s.name as sec,c.name as class FROM `section` s JOIN class c ON s.`class_id` =c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.id");
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->sid ?>"  <?php
                                               if($secid== $val->sid){
                                                   echo "selected";
                                               }
                                              ?>  ><?php echo $val->class."--".$val->sec ?></option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>
                                             <span  style=" color: red">
                                               <?php  echo $this->form->error('section');   ?>
                                             </span>
                                        </div>
                                </div>

                               <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Subject</label>
                                        <div class="col-sm-10">
                                            <select name="subject_name" id="subject_name" class='select2-me' style="width:100%;"   >
                                                <option value="">Select A Subject</option>
                                                 <?php
                                                 $credential = array('iid' =>$this->session->userdata('staff_Org_id'));
                                                 $query = $this->db->get_where('subjects', $credential);
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->sid ?>" <?php
                                               if($this->form->value('subject_name')== $val->sid){
                                                   echo "selected";
                                               }
                                              ?>  ><?php echo $val->subject ?></option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>
                                            <span  style=" color: red">
                                               <?php  echo $this->form->error('subject_name'); ?>
                                             </span>
                                        </div>
                                </div>

                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Teacher</label>
                                    <div class="col-sm-10">
                                        <select name="sub_teacher" id="sub_teacher"   class='select2-me' style="width:100%;" >
                                            <option value="">Select A Teacher</option>
                                             <?php
                                             $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                             $query1 = $this->db->get_where('staff', $credential);
                                             $query1=$query1->result();
                                             foreach($query1 as $val){
                                                ?>
                                                <option value="<?php echo $val->id ?>" <?php
                                               if($this->form->value('sub_teacher')== $val->id){
                                                   echo "selected";
                                               }
                                              ?>  ><?php echo $val->name ." - ".$val->email ?></option>
                                              <?php
                                             }

                                             ?>
                                        </select>  
                                         <span  style=" color: red">
                                               <?php  echo $this->form->error('sub_teacher'); ?>
                                        </span>
                                    </div>
                            </div>

                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit"  name="submit" value="Add Subject To course" onclick="add_course();" class="btn btn-primary" />
                                    <span id="errors"></span>

                                </div>


                            </form>
                        </div>
                </div>
          
           </div>
    </div>
</div>  
    
<?php
$this->load->view('structure/footer');
?>
