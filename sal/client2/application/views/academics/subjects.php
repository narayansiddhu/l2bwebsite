<?php
$this->load->view('structure/header');
$this->load->view('structure/js');

$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium = unserialize(medium);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/subjects">Manage Subjects</a>
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
        if(strlen($this->session->userdata('subject_add_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $this->session->userdata('subject_add_Sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('subject_add_Sucess');
        }
    ?>

           <div class="row nopadding">
               <div class="col-sm-6">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-scribd"></i>Create Subject</h3> 

                            </div>
                            <div class="box-content nopadding">                                
                                <div class='form-horizontal form-bordered' >
                                 <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Subject</label>
                                            <div class="col-sm-10">
                                                <input type="text" placeholder="Enter Subject" name="subname"  id="subname"  class="form-control" />
                                            <span id="subname_err" style="  clear: both; color: red">

                                                </span>        
                                            </div>
                                    </div> 
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="button" onclick="createsub();"  name="submit" value="Create Subject" class="btn btn-primary" />
                                    </div>
                                </div>
                            </div>
                    </div>
                   <hr/>
                   <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-hand-paper-o"></i>Assign Course/staff</h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <div  class='form-horizontal form-bordered' >
                              <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class-section</label>
                                        <div class="col-sm-10">
                                            <select name="section"   id="class_name" class='select2-me' style="width:100%;"  >
                                                <option value="">Select A Section</option>
                                                 <?php
                                                 $query = $this->db->query("SELECT s.sid,s.name as sec,c.name as class,c.medium FROM `section` s JOIN class c ON s.`class_id` =c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.id");
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->sid ?>" ><?php echo $val->class."--".$val->sec ?> (<?php echo $medium[$val->medium] ?> medium) </option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>
                                             <span id='class_name_err' style=" color: red">
                                               <?php  echo $this->form->error('section');   ?>
                                             </span>
                                        </div>
                                </div>
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="button"  onclick="load_settings();"  name="submit" value="Assign Course " class="btn btn-primary" />
                                    </div>
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
                     <hr/>
                   <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-sitemap"></i>Academic Structure</h3> 
                        </div>
                        <div class="box-content nopadding">                                
                            <div  class='form-horizontal form-bordered' >
                              <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Class</label>
                                        <div class="col-sm-10">
                                            <select name="cls_academic"   id="cls_academic" class='select2-me' style="width:100%;"  >
                                                <option value="">Select A Class</option>
                                                 <?php
                                                 $query = $this->db->query("SELECT c.id,c.name as class,c.medium FROM  class c WHERE c.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.id");
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->id ?>" ><?php echo $val->class ?> (<?php echo $medium[$val->medium] ?> medium) </option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>
                                             <span id='cls_academic_err' style=" color: red">
                                               <?php  echo $this->form->error('section');   ?>
                                             </span>
                                        </div>
                                </div>
                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="button"  onclick="load_Academic_settings();"  name="submit" value="Assign Academic Structure " class="btn btn-primary" />
                                    </div>
                            </div>
                        </div>
                    </div>
                   <script>
                        function load_Academic_settings(){
                          cls_name =$('#cls_academic').val();  
                           $('#cls_academic_err').html("");
                          if(cls_name.length==0){
                              $('#cls_academic_err').html("Please select Section ");
                          }else{
                              window.location.replace("<?php echo base_url()?>index.php/Course/Academic_structure/"+cls_name);        
                          }
                        }
                     </script>
                </div>
                           
               <div class="col-sm-6">
                   
                        <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-scribd"></i>Subjects List</h3>
                                </div>
                            <div class="box-content nopadding">
                                <table class="table table-hover datatable table-nomargin">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Subject Name</th>
                                            <th>Action's</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        $query = $this->db->query("SELECT * FROM `subjects` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
                                        if ($query->num_rows() > 0) {
                                            $i=1;
                                            foreach ($query->result() as $row) {
                                               ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row->subject; ?></td>
                                            <td>
                                                <a href="<?php echo base_url() ?>index.php/academics/edit_subject/<?php echo $row->sid ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                <a href="<?php echo base_url() ?>index.php/academics/view_subject_course/<?php echo $row->sid ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                               <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

               </div>
           </div>
           
           
    
           
    <script>
      function createsub(){
          var sub=$('#subname').val();
          if(sub.length==0){
            $('#subname_err').text('** please enter Subject name');
          }else{
              setState('subname_err','<?php echo base_url() ?>index.php/academics/add_subject','subject='+sub);

          }
      }  
    </script>   
    

        
   
        </div>
    </div>
</div>   
<?php
$this->load->view('structure/footer');
?>
