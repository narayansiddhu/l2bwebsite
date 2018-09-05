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
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Parents/">Manage Parents</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/Parents/view_parent/<?php  echo $parent->parent_id   ?>">View <?php  echo $parent->name   ?> Details</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Link Student Accounts To <?php  echo $parent->name   ?></a>
                </li>
            </ul> 
        </div>  
             
            <br/> 
            <div class="alert alert-success alert-dismissable" id="sucess_msg" style=" display: none" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                 Successfully Linked Student Account To Parent Account 
            </div>
                   
             
        <div class="box box-bordered box-color">
            <div class="box-title">
                    <h3><i class="fa fa-th-list"></i>Link Student Accounts To Parents Account : <?php echo $parent->name ?></h3>
            </div>
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered' method="post" enctype="multipart/form-data"  >
                    <input type="hidden" id="parent_id" name="parent_id" value="<?php echo $parent->parent_id ?>" />      
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Students</label>
                        <div class="col-sm-10">
                                <select name="s2" id="student" class='select2-me' style="width:100%;">
                                    <option value="" >Select Student </option>     
                                    <?php
                                          $stud="SELECT st.*, s.name as sec_name , c.name as cls_name FROM `student` st JOIN `section` s ON st.section_id=s.sid JOIN `class` c ON s.class_id = c.id  WHERE st.iid ='".$this->session->userdata('staff_Org_id')."' ";
                                          $stud= $this->db->query($stud);
                                          $stud = $stud->result();
                                          foreach ($stud as $value) {
                                             ?>
                                    <option value="<?php echo $value->student_id ?>"><?php echo  $value->name." - ".$value->userid."(".$value->cls_name."-".$value->sec_name .") " ?></option>
                                             <?php
                                          }
                                        ?>
                                </select>
                            <span style=" color: red" id="stud_error">
                                
                            </span>
                        </div>
                    </div>
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="button"  onclick="link_students();" name="submit" value="Link Students" class="btn btn-primary" />
                    </div>
                    <div id="results">
                        
                    </div>
                </div>
            </div>
        </div>
            <script>
                function link_students(){
                    student = $('#student').val();
                    $('#stud_error').html("");
                    if(student.length==0){
                        $('#stud_error').html("** Please select ");
                    }else{
                       setState('results','<?php echo base_url() ?>index.php/Parents/create_link','parent=<?php echo $parent->parent_id ?>&student='+student);
                    }
                }
            </script>
             
    </div>
  </div>
</div>
<?php
$this->load->view('structure/footer');
?>