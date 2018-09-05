<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
     <div id="results" class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>index.php/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/library/issue">Issue</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/issue_list">Issue List</a>
            </li>
        </ul>
    </div>  
    
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3><i class="fa fa-handshake-o"></i>List Of Book Issued</h3>
                        </div>
                        <div   class="box-content nopadding">
                            <table class="table table-hover table-nomargin table-bordered datatable " >
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Trans-id</th>
                                        <th>Book</th>
                                        <th>Student</th>
                                        <th>Class/Section </th>
                                        <th>Issued On</th>
                                        <th>Status</th>
                                        <th>Returned On</th>
                                        <th>Fine</th>
                                    </tr>
                                </thead>
                                <tbody id="results_holder" >
                                    <?php  
                                    $i=1;
                                        foreach ($results as $value) {
                                           ?>
                                        <tr>
                                            <td><?php echo $i++ ?></td>
                                            <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $value->issue_id ?>" ><?php echo $value->trans_id ?></a></td>
                                            <td><?php echo $value->book."<br/>id: ".$value->buid ?></td>
                                            <td><?php echo $value->studname."<br/>id: ".$value->admissionid ?> </td>
                                            <td><?php echo $value->class."<br/> ".$value->section ?> </td>
                                            <td><?php echo date("d-m-y",$value->issued_date); ?></td>
                                            <td><?php
                                                   if($value->status==1){
                                                       echo "Not Returned";
                                                   }else{
                                                       echo "Returned";
                                                   }
                                               ?></td>
                                            <td><?php
                                                   if($value->status==1){
                                                       echo "---";
                                                   }else{
                                                       echo date("d-m-y",$value->return_date);
                                                   }
                                               ?></td>
                                            <td><?php
                                                   if($value->status==1){
                                                       echo "--";
                                                   }else{
                                                       echo $value->fine;
                                                   }
                                               ?></td>
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