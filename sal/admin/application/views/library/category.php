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
                <li href="<?php echo base_url(); ?>index.php/library/category">
                    <a href="components-messages.html">Manage Category</a>
                   
                </li>
            </ul>
            
        </div>
       <?php
            if(strlen($this->session->userdata('book_category_sucess'))>0 ){
                ?><br/>
                    <div  id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Success!</strong>
                     <?php echo $this->session->userdata('book_category_sucess'); ?>
                    </div>
                   <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(7000).fadeOut();
                   </script>
               <?php
                $this->session->unset_userdata('book_category_sucess');
            }
        ?>
       <?php
            if(strlen($error)>0 ){
                ?><br/>
                    <div  id="successMsg1" class="alert alert-error alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Warning!</strong>
                     <?php echo $error ?>
                    </div>
                   <script>
                        $("#successMsg1").fadeIn();
                        $("#successMsg1").delay(3000).fadeOut();
                   </script>
               <?php
                
            }
        ?>
                   <div class="box ">
                       <div class="col-sm-6 nopadding">
                           <div class="box box-bordered box-color">
                <?php
                   if(sizeof($cat)==0){
                       ?>
                       <div class="box">
                        <div class="box-title">
                            <h3>Add New Category</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered'  >
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Category</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="category" id="category" placeholder="Enter Category Name" class="form-control"  />
                                   
                                        <span id='student_err' style=" color: red">
                                                
                                        </span>  
                                    </div>
                            </div>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                 <input type="button" onclick="add_record();"  name="submit"  value="Add Category's" class="btn btn-primary" />
                                 <span id='errors' style=" color: red">

                                 </span>
                            </div>
                            </div>
                        </div>
                    </div>
                      <?php
                   }else{
                      ?>
                       <div class="box">
                        <div class="box-title">
                            <h3>Edit Category</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered'  >
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Category</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="category" id="ucategory" placeholder="Enter Category Name" class="form-control" value="<?php  echo $cat['category']   ?>"  />
                                        <input type="hidden" name="id" id="ucategoryid" placeholder="Enter Category Name" class="form-control" value="<?php  echo $cat['catid']   ?>"  />
                                        <span id='student_err' style=" color: red">
                                                
                                        </span>  
                                    </div>
                            </div>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                 <input type="button" onclick="update_record();"  name="submit"  value="Update Category" class="btn btn-primary" />
                                 <span id='uerrors' style=" color: red">

                                 </span>
                            </div>
                            </div>
                        </div>
                    </div>
                      <?php
                   }
                
                ?>
                    
                </div>   
                       </div>
                       <div class="col-sm-6 nopaddding">
                           <div class="box box-bordered box-color" style=" padding-left: 5px;">
                    <div class="box">
                        <div class="box-title">
                            <h3>List Of Category</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <table class="table table-hover table-nomargin dataTable table-bordered" style=" width: 100%">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                      $query=$this->db->query( "SELECT * FROM `book_category` where iid='".$this->session->userdata('staff_Org_id')."'");
                                      $query=$query->result();$i=1;
                                      foreach($query as $value ){
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $value->category ?></td>
                                            <td>
                                                <a class="btn" href="<?php echo base_url(); ?>index.php/library/category/<?php echo $value->category ?>" rel="tooltip" title="" data-original-title="View Books"  ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                <a class="btn" href="<?php echo base_url(); ?>index.php/library/category?edit=<?php echo base64_encode($value->catid) ?>" rel="tooltip" title="" data-original-title="Edit Category" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        <?php
                                      }
                                    ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
          </div>
                       </div>                       
                   </div>
             
       
           
       </div>
    </div>
                
     
</div>
<script>
  function add_record(){
      $('#student_err').html('');
      cat=$('#category').val().trim();
      if(cat.length==0){
          $('#student_err').html('<br/>Pease enter Category');
      }else{
          setState('errors','<?php echo base_url() ?>index.php/library/add_category','category='+cat+'&action=add');
      }
  }
  function update_record(){
      $('#student_err').html('');
      cat=$('#ucategory').val().trim();
      catid=$('#ucategoryid').val().trim();
     
      if(cat.length==0){
          $('#student_err').html('<br/>Pease enter Category');
      }else{
        setState('uerrors','<?php echo base_url() ?>index.php/library/add_category','category='+cat+'&catid='+catid+'&action=update');
      }
  }
  
  
</script>
<?php
$this->load->view('structure/footer');
?>