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
                <a href="<?php echo base_url(); ?>index.php/library/category">Categories</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="">Category Based Books</a>
            </li>
        </ul>
    </div> 
            
    
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3><i class="fa fa-book"></i>View Books Of <?php echo $category->category ?></h3>
                            <div class="actions">
                                <a href="<?php echo base_url() ?>index.php/library/books_new" style=" color: #3866E0; background-color: white" class="btn btn-primary"><i class="fa fa-plus" ></i>&nbsp;Add Book</a>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <table  class="table table-hover table-nomargin table-bordered datatable ">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Title</th>
                                        <th>price</th>
                                        <th>author</th>
                                        <th>status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    $i=1;
                                        foreach ($books as $value) {
                                           ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <?php echo $value->name ?>
                                                </td>
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

