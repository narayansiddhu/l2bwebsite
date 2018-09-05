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
                        <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Fee Categories</a>
                    </li>
                </ul>

        </div>
         <?php
            if(strlen($this->session->userdata('Feecat_added_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>Success!</strong>
                     <?php echo $this->session->userdata('Feecat_added_Sucess'); ?>
                    </div>
                <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(2000).fadeOut();
                        </script>
               <?php
                $this->session->unset_userdata('Feecat_added_Sucess');
            }
         ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-th-list"></i>Create Category</h3>

                                </div>
                            <div class="box-content nopadding">
                                <div class='form-horizontal form-bordered' >
                                      <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Category</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="category" id="cat_name" class="form-control" placeholder="please eneter Category name" />
                                                </div>
                                      </div>
                                      <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="button"  name="submit" onclick="add_Category();" value="Add Category" class="btn btn-primary" />
                                            <span id='errors' style="color: red;"></span>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Fee Categories</h3>
                                    
                            </div>
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Category name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = $this->db->query("SELECT *  FROM `fee_category` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'   ");

                                        if ($query->num_rows() > 0) {
                                          $query=$query->result();
                                          $i=1;
                                          foreach ($query as $value) {
                                           ?><tr>
                                               <td><?php echo $i++; ?></td>
                                               <td><?php echo $value->category ?></td>
                                               <td><a href="<?php echo base_url(); ?>index.php/fee/category/<?php echo $value->cid ?>"><i class="fa fa-pencil-square-o"></i></a></td>
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
             function add_Category(){
               var cat=$('#cat_name').val(); 
               setState('errors','<?php echo base_url() ?>index.php/fee/add_category','cat='+cat); 
             }
         </script>      
    
         
          </div>
           
         </div>
</div>   
<?php

$this->load->view('structure/footer');

?>
