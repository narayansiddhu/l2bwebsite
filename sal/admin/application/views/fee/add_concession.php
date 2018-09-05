<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/">Fee </a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/fee/manage_concessions">Manage Fee Concession</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Add Concessions</a>
                </li>
            </ul>
    </div>     
    <?php
        if(strlen($this->session->userdata('Fee_added_Sucess'))>0 ){
            ?><br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Fee_added_Sucess'); ?>
                </div>
           <?php
            $this->session->unset_userdata('Fee_added_Sucess');
        }
        ?>
            <div class="row nopadding">
            
            <div class="col-sm-6">
                
            
            <div class="box box-bordered box-color" id="fee_form" >
                <div class="box-title">
                    <h3><i class="fa fa-th-list"></i>Add Fee Concessions</h3> 
                </div>
                <div class="box-content nopadding"  >                                
                       <div class='form-horizontal form-bordered' >
                            <div class="form-group">
                                      <label for="textfield" class="control-label col-sm-2">Class-section</label>
                                      <div class="col-sm-10">
                                          <select  onchange="load_students();"  class="select2-me" id="cls" name="class" style=" width: 100%;" >
                                              <option value="">Select Class-Section</option>
                                              <?php 
                                                $cls="SELECT s.sid,s.name as sec_name , c.name as cls_name FROM `section` s JOIN class c ON s.class_id=c.id where s.iid='".$this->session->userdata('staff_Org_id')."' ";
                                                $cls = $this->db->query($cls);
                                                $cls = $cls->result();
                                                foreach($cls as $val){
                                                    ?>
                                                  <option value="<?php echo $val->sid ?>" 
                                                      <?php
                                                        if($this->form->value("class")==$val->sid){
                                                           echo 'selected="" '; 
                                                        }
                                                        ?> 
                                                      ><?php echo $val->cls_name." - ".$val->sec_name; ?></option>
                                                   <?php 
                                                }
                                                ?>
                                          </select>
                                          <span  id="section_err" style="color: red;"><?php echo $this->form->error("class") ?></span>
                                      </div>
                                   <?php
                                     
                                      ?>   
                            </div>
                            <div class="form-group">
                                      <label for="textfield" class="control-label col-sm-2">Students</label>
                                      <div class="col-sm-10">
                                          <select id="student"  class="select2-me" name="student" style=" width: 100%;" >
                                              <option value="">Select Students</option>
                                              <?php
                                               if(strlen($this->form->value("class"))!=0){
                                               
                                                $query = " select * from student where iid='".$this->session->userdata('staff_Org_id')."' AND section_id = '".$this->form->value("class")."' ";
                                                $query = $this->db->query($query);
                                                ?>
                                                    <option value="">Select A Student</option>
                                                    <?php
                                                $query=$query->result();
                                                foreach($query as $val){
                                                    ?>
                                                    <option value="<?php echo $val->student_id ?>" 
                                                            <?php
                                                              if($this->form->value('student_id')==$val->student_id ){
                                                                echo 'selected=""';  
                                                              }
                                                            ?>
                                                             >
                                                        <?php echo $val->userid."-".$val->name ?>
                                                    </option>
                                                 <?php
                                                }
                                                }
                                              ?>
                                             
                                          </select>
                                          
                                          <span id='stud_err' style="color: red;"><?php echo $this->form->error('student_id') ?></span>
                                      </div>
                            </div>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="button" onclick="load_fee_structure();"  name="submit"  value="Fetch Fee Structure" class="btn btn-primary" />
                                  <span id='errors' style="color: red;"></span>
                            </div>
                      </div>
                </div>
            </div>
             </div>
            
            <div class="col-sm-6">
            <div class="box box-bordered box-color" style=" display: none" id="fee_structure"  >
                <div class="box-title">
                    <h3><i class="fa fa-th-list"></i><span id="fee_title">Fee Concession</span></h3> 
                </div>
                <div class="box-content nopadding" id="Fee_structure_content">    
            </div>
            <?php
                if(strlen($this->form->value('student_id'))!=0){
                    $_SESSION['error_array'] =$this->form->errors;
                    $_SESSION['value_array'] =$this->form->values;
                 ?>
                <script>
                  setState('Fee_structure_content','<?php echo base_url() ?>index.php/fee/Load_fee_structure','student=<?php echo $this->form->value('student_id') ?>');
                   $('#fee_structure').show();
                 </script>
                 <?php
                }
             ?>
            <script>
                
                function load_fee_structure(){
                   student =$('#student').val();
                   $('#fee_structure').hide(); 
                   if(student.length==0){
                        $('#stud_err').html(' ** please select Student');//cat_err
                     }else{
                        setState('Fee_structure_content','<?php echo base_url() ?>index.php/fee/Load_fee_structure','student='+student);
                        $('#fee_structure').show(); 
                     }
                }
                
                function load_students(){
                   cls =$('#cls').val();
                   $('#student').prop('disabled', true);
                   if(cls.length==0){
                        $('#section_err').html(' ** please select Section');//cat_err
                      //cls_err
                     }else{
                        setState('student','<?php echo base_url() ?>index.php/fee/load_students','section='+cls);
                        $('#student').prop('disabled', false); 
                     } 
                }
            </script>
        </div> 
            </div>
                </div>
    </div>
  </div>    
<?php
$this->load->view('structure/footer');
?>