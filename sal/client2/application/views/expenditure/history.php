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
                    <a href="<?php echo base_url(); ?>index.php/expenditure">Expenditure</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">History</a>
                </li>
            </ul>

    </div> 
           <?php
if(strlen($this->session->userdata('edit_expenditure_sucess'))>0 ){
    ?><br/>
    <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('edit_expenditure_sucess'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(3000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('edit_expenditure_sucess');
}
?>
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-money"></i> Expenditure History  </h3> 
                        <div class="actions">
                            <a target="_blank" href="<?php echo base_url() ?>index.php/expenditure/print_expenditure"  class="btn btn-primary"><i class="fa fa-print" ></i>&nbsp;Print</a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-hover table-nomargin dataTable table-bordered"  style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Reason</th>
                                <th>Reference Person</th>
                                <th>Mode payment</th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $i=1;
                             $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND staff_id='".$this->session->userdata('staff_id')."' ORDER BY time DESC ");
                             $query=$query->result();
                             foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->ref_person ?></td>
                                <td><?php
                                        switch($value->mode){
                                           case 1: echo  "Cash"; break;
                                           case 2: echo "Cheque";break;
                                           case 3: echo "Card Payments";break;
                                           case 4: echo "Other payment Modes";break;
                                        }
                                        ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2:echo "Approved On :".date('d-m-y',$value->approved_on);
                                                  break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                <td>
                                    <a onclick="load_image('<?php echo $value->file ?>')" href="#modal-1" role="button"  data-toggle="modal" rel="tooltip" title="" data-original-title="View Bill Image" ><i class="fa fa-eye"></i></a>
                                    <?php
                                    if($value->status!=2){
                                        ?>
                                       <a rel="tooltip" title="" data-original-title="Edit Expenditure" href="<?php echo base_url(); ?>index.php/expenditure/edit/<?php echo  $value->id ?>" ><i class="fa fa-pencil-square-o"></i></a>
                                       <?php
                                    }else{
                                       ?>
                                       <a target="_blank" rel="tooltip" title="" data-original-title="Print Voucher" href="<?php echo base_url(); ?>index.php/expenditure/print_voucher/<?php echo  $value->id ?>" ><i class="fa fa-print"></i></a>
                                       <?php
                                    }
                                    ?>
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
<div id="modal-1" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">View Bills</h4>
				</div>
				<!-- /.modal-header -->
				<div class="modal-body">
                                    <div id='image_box'>
                                        
                                    </div>
				</div>
				<!-- /.modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<!-- /.modal-footer -->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
<script>
//image_box
function load_image(file_name){
       setState('image_box','<?php echo base_url() ?>index.php/expenditure/load_bill','file='+file_name);
}
</script>

<?php

$this->load->view('structure/footer');

?>