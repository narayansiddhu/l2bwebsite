<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12"  >    
            <div class="box">
                <br/>
                <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/salary/View/">Salary</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Pay salary</a>
                        </li>
                    </ul>

            </div> 
    
                <div class="box box-bordered box-color">
                    <div class="box-title">
                          <?php
                            $t=getdate($month_sal->month);
                          ?>
                            <h3><i class="fa fa-th-list"></i>Salary For the month <?php  echo $t['month'] .",".$t['year'] ?> Paid By <?php echo $month_sal->name ?> ON <?php echo date('d-m-Y', $month_sal->paid_on); ?>  </h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <div  class='form-horizontal form-bordered' >
                         
                             <table class="table table-hover table-nomargin  table-bordered" id="DataTables_Table_0"  style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Name</th>
                                        <th>E-mail</th>
                                        <th>Amount</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                       $i=1;
                                        foreach ($records as $value) {
                                          ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value->name ?></td>
                                                <td><?php echo $value->email ?></td>
                                                <td><?php echo $value->amount ?></td>
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

<?php

?>