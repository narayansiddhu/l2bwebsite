<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div  class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>index.php/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/library/payments">Payments</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/submit">Fee Payments</a>
            </li>
        </ul>
    </div>        
        
 
    <div id="results" class="box">
        
           <div class="box box-bordered box-color">
                    <div class="box">
                        <div class="box-title">
                            <h3>Fee Payments</h3>
                        </div>
                        <div   class="box-content nopadding">
                            <table class="table table-hover table-bordered table-nomargin">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Issue Id</th>
                                        <th>Book</th>
                                        <th>Student</th>
                                        <th>Description</th>
                                        <th>Total Fee</th>
                                        <th>Paid Amount</th>
                                        <th>Paid On</th>                                    
                                    </tr>
                                </thead>
                                <tbody id="results_holder" >
                                    <?php  
                                    
                                    $i=1+($page *$per_page);
                                    
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
                                                 <td><?php echo $value->trans_id; ?></td>
                                                 <td><?php echo $value->name; ?></td>
                                                 <td><?php echo $value->std_name; ?></td>
                                                 <td><?php echo $value->description; ?></td>
                                                 <td><?php echo $value->fee; ?></td>
                                                 <td><?php echo $value->amount; ?></td>
                                                 <td><?php echo date("d-m-y h:i",$value->time); ?></td>
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
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>