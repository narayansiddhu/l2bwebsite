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
                    <a href="<?php echo base_url(); ?>index.php/salary/View/">Staff</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Add Salary</a>
                </li>
            </ul>

    </div> 
    
    
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Add Staff Salary</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <form method="post" action="<?php echo base_url() ?>index.php/salary/add_salary" class='form-horizontal form-bordered' >
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Staff</label>
                        <div class="col-sm-10">
                            <select class="select2-me" name="staff" id="staff" style=" width: 100%" >
                                <option value="" >Select A Staff</option>
                                  <?php
                                  $staff=$this->db->query("SELECT id,name FROM `staff` WHERE iid='".$this->session->userdata('staff_Org_id')."'  ");
                                  $staff=$staff->result();
                                  foreach ($staff as $value) {
                                      ?>
                                <option value="<?php echo $value->id ?>" 
                                        <?php
                                           if($this->form->value('staff')==$value->id){
                                               echo "selected";
                                           }
                                        ?>
                                        ><?php echo $value->name ?></option>   
                                       <?php
                                  } 
                                ?>
                            </select>
                            <span id="new_date_err" style=" color: red">
                                       <?php
                                           echo $this->form->error('staff');
                                          ?>
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Amount</label>
                        <div class="col-sm-10">
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Enter Salary Amount" value="<?php echo $this->form->value('amount') ?>"   />
                            <span id="new_date_err" style=" color: red">
                                       <?php
                                           echo $this->form->error('amount');
                                          ?>
                            </span>  
                        </div>
                    </div>
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="submit" id="add" onclick="" name="add" value="Add Salary" class="btn btn-primary" />
                        <span id="errors" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                       ?>
                            </span> 
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>         
</div>
<?php
$this->load->view('structure/footer');
?>
