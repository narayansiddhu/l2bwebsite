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
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/submit">Fee Payments</a>
            </li>
        </ul>
    </div>  
    
 
    
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3>Pending Fee Payments</h3>
                            <div class="actions">
                                <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/library/view_all_payments"><i class="fa fa-eye"></i>&nbsp;View Payments</a>
                            </div>
                        </div>
                        <div   class="box-content nopadding">
                            <table class="table table-hover table-bordered table-nomargin">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>issue-id</th>
                                        <th>Book</th>
                                        <th>Student</th>
                                        <th>Student id</th>
                                        <th>Description</th>
                                        <th>Fee</th>
                                        <th>Fee-time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                       
                                    </tr>
                                </thead>
                                <tbody id="results_holder" >
                                    <?php  
                                    
                                    $i=1+($page*$per_page);
                                    
                                    if(!$results){
                                        ?>
                                             <tr>
                                                 <td colspan="8">NO Records found</td></tr>
                                      <?php
                                    }else{
                                        foreach ($results as $value) {
                                           ?>
                                             <tr>
                                                 <td><?php echo $i++; ?></td>
                                                 <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $value->issue_id ?>" ><?php echo $value->trans_id ?></a></td>
                                                 <td><?php echo $value->book_name ?></td>
                                                 <td><?php echo $value->student ?></td>
                                                 <td><?php echo $value->admission ?></td>
                                                 <td><?php echo $value->description ?></td>
                                                 <td><?php echo $value->fee ?></td>
                                                 <td><?php echo date('d-m-y',$value->time) ?></td>
                                                 <td><?php if( $value->status ==1){
                                                               echo "Not paid";
                                                           }elseif( $value->status ==2){
                                                               echo "Payment intiated";
                                                           }else{
                                                               echo "Paid";
                                                           }
                                                         
                                                         ?></td>
                                                 <td>
                                                     <a href="<?php echo base_url(); ?>index.php/library/pay/<?php echo  $value->fine_id ?>"><i class="fa fa-money" aria-hidden="true"></i></a>
                                                 </td>
                                             </tr>
                                            <?php
                                        }
                                    }
                                    
                                    if(!is_null($links)){
                                              ?>
                                           
                                            <tr>
                                                <td colspan="9">
                                                    <div class="table-pagination">
                                                         <?php echo $links ?>
                                                    </div>
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