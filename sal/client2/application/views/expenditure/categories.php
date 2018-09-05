<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');

$edit = FALSE;

if(strlen($this->input->get("id"))!=0){
    $id= $this->input->get("id");
    $query ="SELECT *  FROM `expense_category` WHERE cat_id  = '".$id."' AND iid='".$this->session->userdata('staff_Org_id')."'";
    $query = $this->db->query($query);    
    if($query->num_rows()>0){
         $query = $query->row();
         $edit = TRUE;
         
    }    
}


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
                        <a href="<?php echo base_url(); ?>index.php/expenditure">Expenditure</a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>">Categories</a>
                    </li>
                </ul>

        </div>
        <?php
        if(strlen($this->session->userdata('expense_category_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('expense_category_Sucess'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(3000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('expense_category_Sucess');
        }
        ?> 
         
                   
                   
            <div class="row">
                
             
                
                <div class="col-sm-5">
                    <div class="box box-bordered box-color">
        
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Create Category</h3>                                     
                        </div>

                        <div class="box-content nopadding">    

                            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/expenditure/save_category" method="post" >

                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Category</label>
                                        <div class="col-sm-10">
                                            <input type="text"  name="category" placeholder="Enter Category Name" class="form-control" value="<?php 
                                            if( (strlen($this->form->value('category'))==0) && ($edit) ){
                                                echo $query->name;
                                            }else{
                                                echo $this->form->value('category');
                                            }
                                              ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                       echo $this->form->error('category');   
                                                    ?>
                                                </span>        
                                        </div>
                                </div>    
                                 
                                <?php  
                                  if($edit){
                                      ?>
                                <input type="hidden" name="cat_id" value="<?php  echo $query->cat_id; ?>" />
                                     <?php
                                  }
                                
                                
                                ?>


                                <div  class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit"  name="submit" value="<?php
                                    if($edit){
                                        ?>Edit Category<?php
                                    }else{
                                        ?>Create Category<?php
                                    }
                                    ?>" class="btn btn-primary" />
                                </div>




                            </form>

                        </div>
                        
                    </div>
                </div>
                
                <div class="col-sm-7">
                    <div class="box box-bordered  box-color">
                        <div class="box-title">
                            <h3>Category List</h3>
                            
                        </div>
                        <div  class="box-content nopadding">
                            <?php
                               $cat=$this->db->query(" SELECT * FROM `expense_category` where iid = '".$this->session->userdata('staff_Org_id')."' ");
                               ?>
                            <table class="table table-hover table-nomargin datatable table-bordered" style=" ">
                                <thead>
                                    <th>S.No</th> 
                                    <th>Category Name</th> 
                                    <th>Action</th> 
                                </thead>
                                <tbody>
                                <?php
                                   if($cat->num_rows()>0){
                                       $cat = $cat->result();$i=1;
                                       foreach($cat as $value){
                                           ?>
                                        <tr>
                                            <td ><?php echo $i++; ?></td>
                                             <td ><?php echo $value->name; ?></td>
                                             <td>
                                                 <a href="?id=<?php echo $value->cat_id ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                             </td>                                            
                                        </tr> 
                                        <?php
                                       }
                                        
                                   }                                ?> 
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
    

<?php

$this->load->view('structure/footer');

?>