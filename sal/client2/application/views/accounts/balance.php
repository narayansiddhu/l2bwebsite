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
                    <a href="<?php echo base_url(); ?>index.php/accounts/">Accounts</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Balance Of <?php echo $section->class." , ".$section->section ?></a>
                </li>
            </ul>

    </div>
    
    
            <div class="page-header">
        
                <div class="pull-right" style=" text-align: center; align-items: center">
            <ul class="stats">
                <li class="satgreen">
                    <i class="fa fa-inr" aria-hidden="true"></i>
                    <div class="details">
                        <span class="big"><?php echo  $section->fee ?></span>
                        <span>Individual Fee</span>
                    </div>
                </li>
                <li class="pink">
                    <i class="fa fa-inr" aria-hidden="true"></i>
                    <div class="details">
                        <span id="totalamount" class="big"><?php echo  $section->fee ?></span>
                        <span>Total Amount</span>
                    </div>
                </li>
                <li class="blue">
                    <i class="fa fa-inr" aria-hidden="true"></i>
                    <div class="details">
                        <span id="paidamount" class="big"><?php echo  $section->fee ?></span>
                        <span>Paid Amount</span>
                    </div>
                </li>
                <li class="lime">
                    <i class="fa fa-inr" aria-hidden="true"></i>
                    <div class="details">
                        <span id="balanceamount" class="big"><?php echo  $section->fee ?></span>
                        <span>Balance Amount</span>
                    </div>
                </li>
            </ul>
        </div>
        
        
    </div>
   
             <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3>View Fee Balance's Of <?php echo $section->class." , ".$section->section ?> </h3> 
                                <div class="actions" style=" background-color: white;">
                                    <a style=" background-color: white; color: #368EE0" class="btn btn-mini" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_report/<?php echo $section->class_id."/".$section->sid ?>/balance">
                                        <i class="fa fa-print" aria-hidden="true"></i>
                                    </a>&nbsp;
                                    <a style=" background-color: white; color: #368EE0" class="btn btn-mini" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/pdf_report/<?php echo $section->class_id."/".$section->sid ?>/balance">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>&nbsp;
                                    <a style=" background-color: white; color: #368EE0" class="btn btn-mini" target="_blank" href="<?php echo base_url(); ?>index.php/accounts/download_report/<?php echo $section->class_id."/".$section->sid ?>/balance">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                      </a>&nbsp;
                                </div>
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1105px;">
                                <thead>
                                    <tr>
                                        <th>Roll</th>
                                        <th>Student</th>
                                        <th>Admission No</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                      $i=0;
                                      $total_paid=0;
                                      foreach ($balance as $value) {
                                         ?>
                                            <tr>
                                                <td><?php echo $value->roll ?></td>
                                                <td><?php echo $value->name ?></td>
                                                <td><?php echo $value->userid ?></td>

                                                <td><?php 
                                                          $bal= $value->total;
                                                          
                                                        if(strlen($bal)==0){
                                                           $bal=0; 
                                                        }
                                                        echo $bal;
                                                        $total_paid =$total_paid+$bal;
                                                        ?>
                                                </td>
                                                <td><?php echo $section->fee-$value->total ?></td>
                                            </tr>
                                         <?php
                                         $i++;
                                        }

                                    ?>
                                </tbody>
                            </table>      
                        </div>
             </div>
                 
        <?php 
         echo $i;
         echo "<br/>".$total_paid;
        ?>
    <script>
       $('#totalamount').html("<?php echo ($section->fee*$i )  ?>");
       $('#paidamount').html("<?php echo ($total_paid )  ?>");
       $('#balanceamount').html("<?php echo (($section->fee*$i)-$total_paid )  ?>");
    </script>
        </div>
    </div>
    
</div>

<?php
$this->load->view('structure/footer');
?>