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
                    <a href="<?php echo base_url(); ?>index.php/Expenditure">Expenditure</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Approvals</a>
                </li>
            </ul>

    </div> 
    <?php
if(strlen($this->session->userdata('approve_expenditure'))>0 ){
    ?><br/>
    <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('approve_expenditure'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('approve_expenditure');
}
?> 
    
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-money"></i>Expense To Be Approved  </h3>
                        <div class="actions">
                            <a href="<?php echo base_url() ?>index.php/expenditure/approved_list" class="btn btn-primary">
                           <i class="fa fa-thumbs-up" aria-hidden="true"></i> Approved List
                            </a>
                            <a href="<?php echo base_url() ?>index.php/expenditure/rejected_list" class="btn btn-primary">
                           <i class="fa fa-thumbs-down" aria-hidden="true"></i> Rejected List
                            </a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Staff</th>
                                <th>Reason</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $i=1;
                             $query=$this->db->query("SELECT e.*,s.name as staff FROM expenditure e JOIN staff s ON e.staff_id=s.id WHERE e.status=1 AND e.iid='".$this->session->userdata("staff_Org_id")."'   ORDER BY e.time DESC ");
                             $query=$query->result();
                             foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->staff ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y H:i",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2: echo "Approved";break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                
                                <td id="td_action_<?php echo $value->id  ?>">
                                    <a  class="btn" onclick="approve('<?php echo $value->id  ?>');" ><i class="fa fa-check" aria-hidden="true"></i></a>
                                    &nbsp;
                                    <a class="btn"  onclick="reject('<?php echo $value->id  ?>');" ><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>                                    
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
    
<script>
    function approve(id){
        setState('td_action_'+id,'<?php echo base_url() ?>index.php/expenditure/approve','id='+id);
    }
    function reject(id){
        setState('td_action_'+id,'<?php echo base_url() ?>index.php/expenditure/reject','id='+id);
    }
</script>
<?php

$this->load->view('structure/footer');

?>