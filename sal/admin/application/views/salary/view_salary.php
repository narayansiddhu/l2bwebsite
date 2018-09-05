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
                            <a href="<?php echo base_url(); ?>index.php/salary/View">Salary</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">View Salary</a>
                        </li>
                    </ul>
            </div> 
    
    <?php
          if(strlen($this->session->userdata('staff_Salary_add'))>0 ){
              ?><br/>
                  <div id="successMsg" class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>Success!</strong>
                   <?php echo $this->session->userdata('staff_Salary_add'); ?>
                  </div>
              <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
             <?php
              $this->session->unset_userdata('staff_Salary_add');
          }
      ?>
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Salary</h3> 
                        <div class="actions"> 
                                  <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/salary/add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add Salary</a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">

                         <thead>
                             <tr>
                                 <th>S.no</th>
                                 <th>Staff</th>
                                 <th>Amount</th>
                                 <th>Assigned By</th>
                                 <th>Actions</th>
                             </tr>
                         </thead>
                         <tbody>
                           <?php
                             $query=$this->db->query("SELECT s.id,s.amount,st.name as staff,a.name as assign FROM salary s JOIN staff st ON s.staff_id=st.id JOIN staff a ON s.assigned_by=a.id WHERE s.iid='".$this->session->userdata("staff_Org_id")."' ");
                             $query=$query->result();
                             $i=1;
                             foreach ($query as $value) {
                                 ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $value->staff ?></td>
                                    <td id="td_amount_<?php echo $value->id ?>"><?php echo $value->amount ?></td>
                                    <td id="td_assign_<?php echo $value->id ?>"  ><?php echo $value->assign ?></td>
                                    <td id="td_action_<?php echo $value->id ?>" ><button onclick="edit_salary('<?php echo $value->id ?>','<?php echo $value->amount ?>');" class="btn btn-mini"><i class="fa fa fa-pencil-square-o"></i></button></td>
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
    
    <script>
      function edit_salary(sid,amount){
          
          id='td_action_'+sid;
          
          setState(id,'<?php echo base_url() ?>index.php/salary/edit','sid='+sid+'&amount='+amount);
      }
      
      function save_salary(sid){
          var amount = $('#new_amount_'+sid).val();
        
          id="new_amount_error_"+sid;
          setState(id,'<?php echo base_url() ?>index.php/salary/save_sal','sid='+sid+'&amount='+amount);
      }
      
    </script>
    
    
</div>
<?php
$this->load->view('structure/footer');
?>
