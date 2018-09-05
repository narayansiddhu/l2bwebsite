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
                            <a href="<?php echo base_url(); ?>index.php/accounts/">Manage Fee</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Payment Approvals</a>
                        </li>
                    </ul>

            </div> 
    
            <div class="box box-bordered box-color" id="results_table">
                    <div class="box-title">
                        <h3><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;Payment Approval</h3> 
                        <div class="actions"> 
                            <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/payment_approved"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;Approved List</a>
                            <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/payment_rejected"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>&nbsp;Rejected List</a>
                        
                        </div>
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Payment Type</th>
                                    <th>Reference No</th>
                                    <th>Recipt</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $i=1;
                                 $pay_type=  unserialize(fee_payment);
                              //   echo "SELECT r.rid,r.ref_no ,sum(f.amount) as amount,f.recipt ,f.pay_type,f.time FROM fee_pay_reference r JOIN fee_accounts f ON f.ref_id=r.rid where r.iid='".$this->session->userdata("staff_Org_id")."' AND f.status=2 ";
                                 $query=$this->db->query("SELECT r.rid,r.ref_no ,sum(f.amount) as amount,f.recipt ,f.pay_type,f.time FROM fee_pay_reference r JOIN fee_accounts f ON f.ref_id=r.rid where r.iid='".$this->session->userdata("staff_Org_id")."' AND f.status=2 ");
                                 $query=$query->result();
                                 foreach($query as $value){
                                     if(strlen(trim($value->ref_no))!=0){
                                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $pay_type[$value->pay_type] ?></td>
                                    <td><?php echo $value->ref_no ?></td>   
                                    <td><?php echo $value->recipt ?></td>
                                    <td><?php echo $value->amount ?></td>
                                    <td><?php echo date("d-m-y h:i", $value->time) ?></td>
                                    <td>
                                        <a onclick="Approve(<?php echo $value->rid ?>);" rel="tooltip" title="" data-original-title="Approve"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                        <a onclick="Reject(<?php echo $value->rid ?>);" rel="tooltip" title="" data-original-title="Reject" style=" color: red; text-decoration: none;"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>
                                        <span id='error_<?php echo $value->rid ?>' style=" clear: both; color: red"></span>
                                    </td>
                                </tr>
                                  <?php
                                     }
                                  
                                 }
                                ?>
                            </tbody>
                        </table>
                    </div>
            </div>
            
        </div>
    </div>
</div>
    <script> 
        function Approve(ref_id){
             setState('results_table','<?php echo base_url() ?>index.php/accounts/change_pay_status/'+ref_id+'/1',"");
        }
        function Reject(ref_id){
            setState('results_table','<?php echo base_url() ?>index.php/accounts/change_pay_status/'+ref_id+'/0',"");
        }
    </script>

<?php

$this->load->view('structure/footer');

?>