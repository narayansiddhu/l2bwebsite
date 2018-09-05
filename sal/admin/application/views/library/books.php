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
            <li >
                <a href="">View Books</a>
            </li>
        </ul>
    </div> 
    
    <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>Search Books</h3>                            
                        </div>
        
                        <div  class="box-content nopadding">
                            <form action="<?php echo base_url()  ?>/index.php/library/books/" class='form-horizontal form-bordered'  > 
                                    <div class="row" style=" width: 100%">
                                        
                                        <div class="col-sm-6" style=" float: left; width: 46%; " >
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Category</label>
                                                    <div class="col-sm-10">
                                                        <select class="select2-me" name="cat" style=" width: 100%"  >
                                                            <option value="">Category</option>
                                                           <?php
                                                            $query=$this->db->query( "SELECT * FROM `book_category` where iid='".$this->session->userdata('staff_Org_id')."'");
                                                            $query=$query->result();$i=1;
                                                            foreach($query as $value ){
                                                              ?>
                                                            <option value="<?php echo $value->catid ?>"><?php echo $value->category ?></option>
                                                              
                                                              <?php
                                                            }
                                                           ?>
                                                        </select>
                                                        <span  style=" color: red">
                                                             <?php echo $this->form->error('title') ?>   
                                                         </span>  
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style=" float: left; width: 46%; ">
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Text</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="q"  placeholder="Search here..."" class="form-control"  value="<?php echo $this->form->value('title') ?>" />
                                                         <span  style=" color: red">
                                                             <?php echo $this->form->error('title') ?>   
                                                         </span>  
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="form-actions" style=" float: left; width: 8%; ">
                                            <button class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    
                                </form>
                                     
    
                        </div>
                    
    </div>
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>Books List</h3>
                            <div class="actions">
                                <a href="<?php echo base_url() ?>index.php/library/books_new" style=" color: #3866E0; background-color: white" class="btn btn-primary"><i class="fa fa-plus" ></i>&nbsp;Add Book</a>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <table  class="table datatable table-hover table-nomargin table-bordered ">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Title</th>
                                        <th>category</th>
                                        <th>price</th>
                                        <th>author</th>
                                        <th>status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    $i=1;
                                        foreach ($results as $value) {
                                           ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <?php echo $value->name ?>
                                                </td>
                                                <td><?php echo $value->category ?></td>
                                                <td><?php echo $value->price ?></td>
                                                <td><?php echo $value->author ?></td>
                                                <td><?php if($value->status==1){
                                                              echo "Available";
                                                           }else{
                                                              echo "Un-Available"; 
                                                           }
                                                    ?></td>
                                                <td>
                                                    <a href="<?php echo base_url()  ?>/index.php/library/view_book/<?php echo $value->book_id ?>" rel="tooltip" title="" data-original-title="View Book "  class="btn btn-mini"  ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                   <a href="<?php echo base_url()  ?>/index.php/library/edit_book/<?php echo $value->book_id ?>" rel="tooltip" title="" data-original-title="Edit Book "  class="btn btn-mini"  ><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                
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

<?php
$this->load->view('structure/footer');
?>

