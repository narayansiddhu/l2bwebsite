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
                    <a href="<?php echo base_url(); ?>index.php/parents/View">Manage Parents</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="<?php echo base_url(); ?>index.php/parents/bulk">Create Bulk</a>
                </li>
            </ul>
        </div>
     <?php
    if(strlen($this->session->userdata('bulkprnt_add'))>0 ){
        ?>
          <br/>
          <div id="successMsg" class="alert alert-success alert-dismissable">
                 <button type="button" class="close" data-dismiss="alert">×</button>
                  <?php echo $this->session->userdata('bulkprnt_add'); ?>
                 </div>
                <script>
                       $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                    </script>
       <?php
        $this->session->unset_userdata('bulkprnt_add');
    }
 if(isset($_SESSION['prntdummy_data'])){
     $dummy=$_SESSION['prntdummy_data'];
   
?>      
    
        <div class="box box-color box-bordered" id="failed_records_list">
            <div class="box-title">
                    <h3>
                            <i class="fa fa-table"></i>
                            Failed Records
                    </h3>
            </div>
            <div class="box-content nopadding">
                <table class="table table-hover table-nomargin dataTable table-bordered" style=" max-height: 450px; ">
                            <thead>
                                    <tr>
                                            <th>S.No</th>
                                            <th>name</th>
                                            <th >phone</th>
                                            <th >email</th>
                                            <th >Profession</th>
                                            <th >Address</th>
                                            <th >Error </th>
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
                         $("#failed_records_list").delay(8000).fadeOut();
                    </script>
    
    <?php
    unset($_SESSION['prntdummy_data']);
 }
    
  ?> 
    
    <div class="box box-bordered box-color">
            <div class="box-title">
                    <h3><i class="fa fa-male"></i>Create Bulk Parents  </h3> 
            </div>
            <div class="box-content nopadding">                                
                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/parents/bulk_create" method="post" enctype="multipart/form-data"  >
                   
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
                                <a href="<?php echo assets_path ?>/parents_bulk.csv" target="_blank"  class="badge">Sample File Format</a>
                            </div>
                    </div>
                    
                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="submit" name="submit" class="btn btn-primary" value="Create Parents" />
                    </div>
                    
                    
                </form>
            </div>
    </div>
               
           
            
            
            
            
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
