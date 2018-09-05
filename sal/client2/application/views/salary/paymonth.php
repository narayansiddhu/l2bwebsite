
<?php
$this->load->view('structure/header');
$this->load->view('structure/js');

$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="col-sm-12 col-sm-offset-11"><a href="<?php echo base_url(); ?>index.php/accounts/logout"><button type="button" class="btn btn-primary"><i class="fa fa-angle-left" aria-hidden="true"></i> Back</button></a></div>
            <br/>
            
            <br>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/salary/View/">Salary</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Pay  Salary</a>
                        </li>
                    </ul>

            </div> 
             <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Select Month </h3>
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
                        <div class='form-horizontal form-bordered' >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-3">Month - Year</label>
                                <div class="col-sm-9">
                                    <div class="col-sm-6 ">
                                       <select class="select2-me" id="month" name="month"  style=" width: 90% "  >
                                        <option value="" >Select A Month</option>
                                        <?php
                                          for ($m=1; $m<=12; $m++) {
                                              ?> <option value="<?php echo $m ?>" ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                            }
                                            ?>
                                       </select> 
                                    </div>
                                    <div class="col-sm-6">
                                       <select class="select2-me" id="year" name="year"  style=" width: 90% "  >
                                        <option value="" >Select A Year</option>
                                        <?php
                                            $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                            $start=$start->row();
                                            $start=$start->timestamp;
                                            $start=getdate($start);
                                            $start=$start['year'];
                                            $now=getdate();
                                            $now=$now['year'];
                                            for($i=$start;$i<=$now;$i++){
                                              ?>
                                        <option value="<?php echo $i; ?>"  <?php
                                           if($i==$now){
                                               echo "selected";
                                           }?> ><?php echo $i; ?></option>
                                              <?php
                                            }
                                            
                                        ?>
                                       </select> 
                                    </div>
                                    
                                    <span id="new_date_err" style=" color: red">
                                              
                                    </span>  
                                </div>
                            </div> 
                            
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="button" id="add"  onclick="check_sal();"  name="" value="Pay salary" class="btn btn-primary" />
                                <span id="errors"  style=" color: red ; ">
                                               <?php
                                                   echo $this->form->error('stdob');
                                               ?>
                                </span> 
                            </div>
                        </div>
                    </div>
            </div> 
    
             <script>
                function check_sal(){
                  $('#new_date_err').html("");
                  month=$('#month').val();  
                  year=$('#year').val();
                  if( (month.length==0) || (year.length == 0) ){
                       $('#new_date_err').html("Please select month & year");
                   }else{
                       setState('errors','<?php echo base_url() ?>index.php/salary/check_month_salary','month='+month+'&year='+year);
                   }
                }    
                </script>
   
             <div class="box box-color box-bordered nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-bar-chart-o"></i>Select Month </h3>
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
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                      <thead>
                                         <tr>
                                             <th>S.no</th>
                                             <th>Month</th>
                                             <th>Paid on</th>
                                             <th>Added By</th>
                                             <th>Amount Paid</th>
                                             <th>Action</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         <?php
                                            $query=$this->db->query("SELECT m.id,m.month,m.paid_on,s.name as paid_by,sum(p.amount) as amount  FROM salary_month m JOIN staff s ON m.paid_by=s.id  JOIN salary_paid p ON p.month_id=m.id WHERE m.iid='".$this->session->userdata('staff_Org_id')."'");
                                            $query=$query->result();
                                            $i=1;
                                            foreach($query as $value){
                                                if($value->id!=NULL){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php 
                                                               $month=getdate($value->month);
                                                               echo $month['month'].",".$month['year'];
                                                          ?></td>
                                                        <td>
                                                            <?php 
                                                              echo  date('d-m-y',$value->paid_on);
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value->paid_by ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value->amount ?>
                                                        </td>
                                                        <td><a href="<?php echo base_url() ?>index.php/salary/view_monthly/<?php echo $value->id  ?>"><i class="fa fa-eye"></i></a></td>
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
 

<?php
$this->load->view('structure/footer');
?>

