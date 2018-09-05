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
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/fee/manage_concessions">Manage Fee Concession</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Concessions</a>
                </li>
            </ul>

    </div>  
    <div class="row nopadding">
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-child"></i>Student Details</h3> 
                           
                    </div>
                    <div class="box-content nopadding">                                
                        <table class="table table-hover table-nomargin  table-bordered">
                            <tr>
                                <th>Student</th><td><?php echo $concession->stud_name ?></td>
                            </tr>
                            <tr>
                                <th>Class</th>
                                <td><?php echo $concession->cls_name." - ".$concession->sec_name ?></td>
                            </tr>
                            <tr>
                                <th>Admission No</th><td><?php echo $concession->userid ?></td>
                            </tr>
                            <tr>
                                <th>Roll No</th><td><?php echo $concession->roll ?></td>
                            </tr>
                            <tr>
                                <th>Assigned By</th>
                                <td><?php echo $concession->staff_name ?></td>
                            </tr>
                            <tr>
                                <th>Assigned On</th>
                                <td><?php echo date('d-m-y H:i',$concession->time) ?></td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-inr"></i>Concession Details</h3> 
                            <div class="actions">
                                <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/fee/edit_concession/<?php echo $concession->cid ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Concession</a>
                            </div>
                    </div>
                    <div class="box-content nopadding">                                
                        <?php
                          $q=" SELECT f.id,f.amount,c.category,c.cid FROM fee_concession f JOIN fee_category c ON f.cat_id=c.cid WHERE f.conc_id='".$concession->cid."'  ";
                          $q = $this->db->query($q);
                          $q =$q->result();
                          
                          ?>
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                <tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                foreach ($q as $value) {
                                    ?>
                                <tr style=""> 
                                      <td><?php echo $i++ ?></td>
                                      <td><?php echo $value->category ?></td>
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
  </div>    
<?php
$this->load->view('structure/footer');
?>