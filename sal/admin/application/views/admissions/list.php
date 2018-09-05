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
                        <a href="<?php echo base_url(); ?>index.php/students">Students</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/Admissions">Admissions</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href=""><?php echo $status ?></a>
                    </li>
                </ul>
            </div>
            
           
                         <?php
            if(strlen($this->session->userdata('admission_reject_Sucess'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('admission_reject_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('admission_reject_Sucess');
            }
            ?>
   
            
            <?php
            $list=" SELECT s.*,c.name as cls_name FROM `student_admission` s JOIN class c ON c.id=s.class_id  where s.iid='".$this->session->userdata("staff_Org_id")."' and status= '".$status_no."' ";
            $list = $this->db->query($list)->result();
            ?>
            <div class="box box-bordered box-color" id="results_table">
                    <div class="box-title">
                        <h3><?php echo $status ?></h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>Class</th>
                                    <th>Medium</th>
                                    <th>Previous School</th>
                                   <?php
                                        if($status_no==0){
                                            ?>
                                    <th>Remarks</th>
                                            <?php
                                        }
                                   ?>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                $medium = unserialize(medium);
                                    foreach ($list as $value) {
                                       ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->phone ?></td>
                                    <td><?php echo $value->cls_name ?></td>
                                    <td><?php echo $medium[$value->medium] ?></td>
                                    <td><?php echo $value->prev_school ?></td>
                                   <?php
                                        if($status_no==0){
                                            ?>
                                    <td><?php echo $value->remark ?></td>
                                            <?php
                                        }
                                   ?>
                                    <td>
                                        <?php
                                               switch ($value->status) {
                                                   case 1: ?>
                                                           <a href="<?php echo base_url(); ?>index.php/Admissions/Proceed_aprove/<?php echo $value->adm_id  ?>" rel="tooltip" title="" data-original-title="Approve" ><i class="fa fa-check"></i></a>
                                                        <a onclick="rejection_list(<?php echo $value->adm_id ?>);"  href="#modal-1" role="button"  data-toggle="modal" rel="tooltip" title="" data-original-title="Reject" ><i class="fa fa-times"></i></a>
                                                       <?php
                                                       break;
                                                   case 0 :
                                                       ?>
                                                       <a href="<?php echo base_url(); ?>index.php/Admissions/Proceed_aprove/<?php echo $value->adm_id  ?>" rel="tooltip" title="" data-original-title="Approve" ><i class="fa fa-check"></i></a>
                                                       <?php
                                                       break;
                                                   case 2 :
                                                       ?>
                                                       <a href="<?php echo base_url(); ?>index.php/Admissions/View_approved/<?php echo $value->adm_id  ?>" rel="tooltip" title="" data-original-title="View" ><i class="fa fa-eye"></i></a>
                                                       <?php
                                                       break;
                                                   default:
                                                       break;
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
                
            <div id="modal-1" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Enter Reason For Rejection</h4>
				</div>
				<!-- /.modal-header -->
				<div id="modal_body" class="modal-body">
                                    <textarea name="rjctreason" id="rjctreason" class="form-control" style=" resize: none" placeholder="Please enter Reason For rejection"></textarea>
                                    <span id="reason_err" style="color: red"></span>
                                    <input type="hidden" id="adminss_id" name="adminss_id" value="" />
                                </div>
				<!-- /.modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="button" onclick="submit_reject();" class="btn btn-primary">Reject Admission</button>
				</div>
				<!-- /.modal-footer -->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
    </div>
        
        <script>
         function submit_reject(){
            adminss_id=$('#adminss_id').val();
            rjctreason=$('#rjctreason').val();
            if(rjctreason.length==0){
                $('#reason_err').html("** please enter reason for rejection");
            }else{
               setState('reason_err','<?php echo base_url() ?>index.php/Admissions/reject_update/','adminss_id='+adminss_id+'&rjctreason='+rjctreason);
            }
            
         }
         function rejection_list(vaue){
             $('#adminss_id').val(vaue);
      //      setState('results_table','<?php echo base_url() ?>index.php/accounts/change_pay_status/'+ref_id+'/1',"");
         }
         
        </script>
    </div>
</div>
            
<?php
$this->load->view('structure/footer');
?>