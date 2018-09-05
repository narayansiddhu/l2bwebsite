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
                            <a href="">Salary History</a>
                        </li>
                    </ul>

            </div>
       
        
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                            <i class="fa fa-bar-chart-o"></i>
                                Salary History
                        </h3>
                        <div class="actions">
                                <a href="#" class="btn btn-mini content-refresh">
                                        <i class="fa fa-refresh"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-remove">
                                        <i class="fa fa-times"></i>
                                </a>
                                <a href="#" class="btn btn-mini content-slideUp">
                                        <i class="fa fa-angle-down"></i>
                                </a>
                        </div>
                </div>
                <div class="box-content nopadding">
                        <div class="statistic-big">
                           <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Month</th>
                                        <th>Paid On</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT p.amount,m.month,m.paid_on FROM  salary_paid p JOIN salary_month m ON p.month_id=m.id  WHERE p.staff='".$this->session->userdata('staff_id')."'");
                                      $query=$query->result();$i=1;
                                      foreach($query as $value){
                                          ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php 
                                                           $month=getdate($value->month);
                                                           echo $month['month'].",".$month['year'];
                                                      ?></td>
                                                <td><?php
                                                    echo date('d-m-Y',$value->paid_on);
                                                   ?></td>
                                                <td><?php echo $value->amount  ?></td>
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
$this->load->view('structure/footer');


//SELECT * FROM `exam` where courseid in (1,7,13)
?>