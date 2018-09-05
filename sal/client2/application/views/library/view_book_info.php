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
                <a href="<?php echo base_url(); ?>index.php/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/library/books">Books</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="">View Book Info</a>
            </li>
        </ul>
    </div>  
    
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>Book Details</h3>
                            <div class="actions">
                                <a href="<?php echo base_url(); ?>index.php/library/edit_book/<?php echo  $book->book_id ?>" class="btn btn-mini ">
                                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Edit
                                </a>  
                            </div>
                        </div>  
                        <div  class="box-content nopadding">
                            
                            <div class='form-horizontal form-bordered'>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Book Id</label>
                                    <div class="col-sm-10">
                                        <label class="form-control"  ><?php echo $book->buid ?></label>
                                    </div>
                                </div> 
                            </div>
                            <div class='form-horizontal form-bordered'>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Title</label>
                                    <div class="col-sm-10">
                                        <label class="form-control"  ><?php echo $book->name ?></label>
                                    </div>
                                </div> 
                            </div>
                            <div class='form-horizontal form-bordered'>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Author</label>
                                    <div class="col-sm-10">
                                        <label class="form-control"  ><?php echo $book->author ?></label>
                                    </div>
                                </div> 
                            </div>
                            <div class='form-horizontal form-bordered'>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Price</label>
                                    <div class="col-sm-10">
                                        <label class="form-control"  ><?php echo $book->price ?></label>
                                    </div>
                                </div> 
                            </div>
                            <div class='form-horizontal form-bordered'>
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Status</label>
                                    <div class="col-sm-10">
                                        <label class="form-control"><?php
                                            if($book->status==1){
                                                              echo "Available";
                                                           }else{
                                                              echo "Un-Available"; 
                                                           }
                                            
                                            ?></label>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    
           </div>
        
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>List Of Issues</h3>
                            <div class="actions">
                                <a href="#" class="btn btn-mini content-refresh">
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-remove">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-slideUp">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Issue id</th>
                                        <th>Issue date</th>
                                        <th>Return date</th>
                                        <th>student </th>
                                        <th>Class</th>
                                       
                                    </tr>
                                </thead> 
                                <tbody>
                                   <?php
                                     $query=$this->db->query( "SELECT li.issue_id,li.trans_id,li.issued_date,li.return_date,li.fine,li.status,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid,c.name as class,se.name as section FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id JOIN class c ON s.class_id =c.id JOIN section se ON s.section_id=se.sid  WHERE li.iid='".$this->session->userdata('staff_Org_id')."' AND lb.book_id='".$book->book_id."'  ");
                                     $query=$query->result();
                                     foreach ($query as $value) {
                                         ?>
                                    <tr>
                                        <td><?php echo $value->trans_id ?></td>
                                        <td><?php echo date('d-m-y',$value->issued_date); ?></td>
                                        <td><?php 
                                                if($value->return_date==0){
                                                    echo "--";
                                                }else{
                                                    echo date('d-m-y',$value->return_date); 
                                                }
                                        ?></td>
                                        <td><?php echo $value->studname ?></td>
                                        <td><?php echo $value->section."-".$value->class ?></td>
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

