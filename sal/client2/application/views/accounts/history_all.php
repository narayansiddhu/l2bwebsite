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
                        <a href="<?php echo base_url(); ?>index.php/accounts/view/">View All Transactions</a>
                    </li>
                </ul>

        </div> 
    
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i> Accounts History  </h3> 
                        <div class="actions"> 
                                <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/search_record"><i class="fa fa-eye"></i>&nbsp;Search</a>
                            </div>
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Student</th>
                                <th>Recipt</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Added By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $i=1;

                             $query=$this->db->query("SELECT f.account_id,s.name,s.userid,f.recipt,sum(f.amount) as amount,f.time,st.name as staff  FROM fee_accounts f JOIN student s ON f.student_id=s.student_id JOIN staff st ON f.staff_id=st.id WHERE f.iid='".$this->session->userdata('staff_Org_id')."'  GROUP BY f.recipt ORDER BY f.time DESC");
                             $query=$query->result();
                             foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->name ?></td>
                                <td><a target="_blank" href="<?php echo base_url()  ?>index.php/accounts/printout/<?php echo $value->recipt ?>"><?php echo $value->recipt ?></a></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y H:I",$value->time); ?></td>
                                <td><?php echo $value->staff ?></td>
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