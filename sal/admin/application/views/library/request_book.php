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
                <a href="<?php echo base_url(); ?>index.php/library/view_request">Requests</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
                <li >
                    <a href="">Request Book</a>
                </li>
            </ul>
        </div>
    <?php
        if(strlen($this->session->userdata('rbook_add_Sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('rbook_add_Sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('rbook_add_Sucess');
        }
    ?>
                
    
           <div class="box box-bordered box-color">
               
                    <div class="box-title">
                        <h3><i class="fa fa-hand-paper-o" aria-hidden="true"></i>Request Books</h3>
                        
                    </div>
                    <div  class="box-content nopadding">
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/library/add_request" method="post" > 
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Book title</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="rtitle"  placeholder="Enter Book title" class="form-control"  value="<?php echo $this->form->value('rtitle') ?>" />
                                         <span  style=" color: red">
                                             <?php echo $this->form->error('rtitle') ?>   
                                         </span>  
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Category</label>
                                <div class="col-sm-10">
                                    <select style="width: 100%" class="select2-me" name="rcategory">
                                        <option value="">Select Book Category</option>
                                        <?php
                                          $query=$this->db->query("SELECT * FROM `book_category` WHERE iid='".$this->session->userdata('staff_Org_id')."'");
                                          $query=$query->result();
                                          foreach ($query as $value) {
                                            ?>
                                        <option <?php
                                             if($this->form->value('rcategory')==$value->catid){
                                                 echo "selected";
                                             }
                                        ?> value="<?php echo $value->catid ?>"><?php echo $value->category ?></option>
                                                <?php  
                                          }
                                        ?>
                                    </select>
                                    <span  style=" color: red">
                                         <?php echo $this->form->error('rcategory') ?>   
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" name="ramount"  placeholder="Enter Book Price" class="form-control" value="<?php echo $this->form->value('ramount')  ?>"  />
                                   <span id='amount_err' style=" color: red">
                                         <?php echo $this->form->error('ramount') ?> 
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Author</label>
                                <div class="col-sm-10">
                                    <input type="text" name="rauthor"  placeholder="Enter Book Author/Publication" class="form-control" value="<?php echo $this->form->value('rauthor')  ?>"  />
                                    <span id='amount_err' style=" color: red">
                                         <?php echo $this->form->error('rauthor') ?> 
                                    </span> 
                                </div>
                            </div>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="submit"   name="submit"  value="Request New Book" class="btn btn-primary" />
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

