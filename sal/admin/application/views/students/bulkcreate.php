<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>   
    
<div class="row">
      <div class="col-sm-12">
          <div class="box" >
    
              <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/students">Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Create Bulk</a>
                </li>
            </ul>
        </div>
              
     <?php
    if(strlen($this->session->userdata('bulkstd_add'))>0 ){
        ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('bulkstd_add'); ?>
                </div>
                <script>
                         $("#successMsg").fadeIn();
                         $("#successMsg").delay(2000).fadeOut();
                    </script>
       <?php
        $this->session->unset_userdata('bulkstd_add');
    }
    
    if(isset($_SESSION['dummy_data'])){
        $dummy=$_SESSION['dummy_data'];

   ?>      

                    <div class="box box-color box-bordered" id="failed_records_list">
               <div class="box-title">
                       <h3>
                               <i class="fa fa-table"></i>
                               Failed Records
                       </h3>
               </div>
                        <div class="box-content nopadding" style="max-height: 350px; overflow: scroll" >
                       <table class="table table-hover table-nomargin dataTable table-bordered">
                               <thead>
                                       <tr>
                                               <th>S.No</th>
                                               <th>name</th>
                                               <th>roll</th>
                                               <th class='hidden-350'>birthday</th>
                                               <th class='hidden-1024'>gender</th>
                                               <th class='hidden-480'>phone</th>
                                               <th class='hidden-1024'>email</th>
                                               <th class='hidden-480'>Address</th>
                                               <th class='hidden-480'>Error</th>
                                       </tr>
                               </thead>
                               <tbody>
                                    <?php
                                    $i=1;
                                         foreach ($dummy as $value) {
                                             ?>
                                           <tr>
                                               <td><?php echo $i++; ?></td>
                                               <td><?php echo $value[0] ?></td>
                                               <td><?php echo $value[1] ?></td>
                                               <td><?php echo $value[2] ?></td>
                                               <td><?php echo $value[3] ?></td>
                                               <td><?php echo $value[4] ?></td>
                                               <td><?php echo $value[5] ?></td>
                                               <td><?php echo $value[6] ?></td>
                                               <td><?php echo $value['error'] ?></td>
                                           </tr>
                                             <?php
                                         }
                                    ?>
                               </tbody>
                       </table>
               </div>
           </div>
            <script>
                         $("#failed_records_list").fadeIn();
                         $("#failed_records_list").delay(10000).fadeOut();
                    </script>

       <?php
       unset($_SESSION['dummy_data']);
    }

     ?> 
    
        
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-child"></i>Bulk - Create Student  </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/students/create_bulk" method="post" enctype="multipart/form-data"  >
                                    
                                    <div class="form-group">
						<label for="field-2" class="control-label col-sm-2">class</label>
                        
						<div class="col-sm-10">
                                                    <select style="width: 100%" class="select2-me" name="bstdclass"  data-validate="required" id="class_id" 
								data-message-required="value_required"
									onchange="return get_class_sections(this.value)">
                                                        <option value="">select Class </option>
                                                       <?php 
                                                            $credential = array('iid'=>$this->session->userdata('staff_Org_id') );
                                                           $classes = $this->db->get_where('class', $credential)->result_array();	

                                                            foreach($classes as $row){
                                                                    ?>
                                                                <option value="<?php echo $row['id'];?>" <?php if($this->form->value('bstdclass')==$row['id']){
                                                                        echo "selected";
                                                                    }?>  >
                                                                <?php echo $row['name'];?>
                                                                </option>
                                                                 <?php
                                                            }
							  ?>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('bstdclass');
                                                           ?>
                                                    </span> 
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="control-label col-sm-2">section</label>
                                                <div class="col-sm-10">
                                                    <select name="bstdsection" style="width: 100%" class="select2-me"  id="section_selector_holder">
                                                        <option value="">select_class_first</option>

                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('bstdsection');
                                                        ?>
                                                    </span> 
                                                </div>
					</div>
                                        <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">File</label>
                                                <div class="col-sm-10">
                                                    <div style=" width: 100%" class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="input-group">
                                                                        <div class="form-control" data-trigger="fileinput">
                                                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                                <span class="fileinput-filename"></span>
                                                                        </div>
                                                                        <span class="input-group-addon btn btn-default btn-file">
                                                                        <span class="fileinput-new">Select file</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input type="file" name="bfile">
                                                                        </span>
                                                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                        </div>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('bfile');
                                                        ?>
                                                    </span>
                                                    <a href="<?php echo assets_path ?>/students_bulk.csv" target="_blank"  class="badge">Sample File Format</a>
                                                </div>
                                        </div>
                                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit" name="submit" class="btn btn-primary" value=" add students" />
					</div>
                                </form>
                            </div>
                    </div>
                <span style=" color: red">
                    Note ** :<br/>
                    Dob  Format Must Be dd/mm/yyy<br/>
                    Admission No must be unique<br/>
                    Roll No in a class-section Must be unique
                </span>
                    
        </div>
    </div>
</div>
<script type="text/javascript">
    function get_class_sections(class_id) {
      if(class_id.length!=0){
         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
      }       
    }
</script>
  
<?php
$this->load->view('structure/footer');
?>
