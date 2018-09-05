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
                    <a href="<?php echo base_url(); ?>index.php/library/">Library</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Edit Book</a>
                </li>
            </ul>
        </div>
    <?php
        if(strlen($this->session->userdata('book_edit_Sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('book_edit_Sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('book_edit_Sucess');
        }
    ?>
                
    
           <div class="box box-bordered box-color">
                <div class="box">
                    <div class="box-title">
                        <h3>Edit Books</h3>
                    </div>
                    <div  class="box-content nopadding">
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/library/editbook" method="post" > 
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Book title</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title"  placeholder="Enter Book title" class="form-control"  value="<?php 
                                           if(strlen($this->form->value('title'))==0){
                                              echo $book->name ; 
                                           }else{
                                             echo $this->form->value('title') ;
                                           }
                                           ?>" />
                                         <span  style=" color: red">
                                             <?php echo $this->form->error('title') ?>   
                                         </span>  
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Category</label>
                                <div class="col-sm-10">
                                    <select style="width: 100%" class="select2-me" name="category">
                                        <option value="">Select Book Category</option>
                                        <?php
                                        $cat="";
                                        if(strlen($this->form->value('category'))==0){
                                              $cat= $book->category ; 
                                           }else{
                                             $cat= $this->form->value('category') ;
                                           }
                                          $query=$this->db->query("SELECT * FROM `book_category` WHERE iid='".$this->session->userdata('staff_Org_id')."'");
                                          $query=$query->result();
                                          foreach ($query as $value) {
                                            ?>
                                        <option <?php
                                             if($cat==$value->catid){
                                                 echo "selected";
                                             }
                                        ?> value="<?php echo $value->catid ?>"><?php echo $value->category ?></option>
                                                <?php  
                                          }
                                        ?>
                                    </select>
                                    <span  style=" color: red">
                                         <?php echo $this->form->error('category') ?>   
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Price</label>
                                <div class="col-sm-10">
                                    <input type="text" name="amount"  placeholder="Enter Book Price" class="form-control" value="<?php 
                                            if(strlen($this->form->value('amount'))==0){
                                              echo $book->price ; 
                                           }else{
                                             echo $this->form->value('amount') ;
                                           }
                                            ?>"  />
                                   <span id='amount_err' style=" color: red">
                                         <?php echo $this->form->error('amount') ?> 
                                    </span> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Author</label>
                                <div class="col-sm-10">
                                    <input type="text" name="author"  placeholder="Enter Book Author/Publication" class="form-control" value="<?php 
                                    if(strlen($this->form->value('author'))==0){
                                              echo $book->author ; 
                                           }else{
                                             echo $this->form->value('author') ;
                                           }
                                    
                                    ?>"  />
                                    <span id='amount_err' style=" color: red">
                                         <?php echo $this->form->error('author') ?> 
                                    </span> 
                                </div>
                            </div>
                            <div  class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="hidden" name="bookid" value="<?php echo $book->book_id ;   ?>" />
                                <input type="submit"   name="submit"  value="Edit Book" class="btn btn-primary" />
                                
                            </div>
                        </form>
                    </div> 
                </div>
          </div>
       </div>
    </div>
                
     
</div>

<?php
$this->load->view('structure/footer');
?>

