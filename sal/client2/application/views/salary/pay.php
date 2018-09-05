<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12"  >    
            <div class="box">
                <br/>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/salary/View">Salary</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Pay salary</a>
                </li>
            </ul>

    </div> 
   <?php
   
        if(strlen($this->session->userdata('salary_pay'))>0){
         ?>
          <br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('salary_pay'); ?>
                </div>  
         <?php
          $this->session->unset_userdata('salary_pay');
        }

    ?>
    
                <div class="box box-bordered box-color">
                    <div class="box-title">
                          <?php
                            $t=getdate($month);
                            $ids=0;
                          ?>
                            <h3><i class="fa fa-th-list"></i>Pay Salary For the month <?php  echo $t['month'] .",".$t['year'] ?></h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <form method="post" action="<?php echo base_url(); ?>index.php/salary/pay_sal"  class='form-horizontal form-bordered' >
                             <table class="table table-hover table-nomargin  table-bordered" id="DataTables_Table_0"  style="width: 100%;">
                             <thead>
                                 <tr>
                                     <th>S.no</th>
                                     <th>Staff</th>
                                     <th>Salary</th>
                                     <th>Total Days</th>
                                     <th>Present</th>
                                     <th>Percentage</th>
                                     <th>Amount To Pay</th>
                                 </tr>
                             </thead>
                             <tbody>
                             <input type="hidden" name="month" value="<?php echo $month ?>" />
                               <?php
                            $query=$this->db->query("SELECT s.id,s.name,count(d.id) as total,s.email,SUM(CASE WHEN a.status = 0 THEN 1 END) AS not_done,SUM(CASE WHEN a.status = 1 THEN 1 END) AS present,SUM(CASE WHEN a.status = 2 THEN 1 END) AS absent,sal.amount FROM staff_attendance a JOIN staff s ON a.staff=s.id JOIN staff_attendance_date d ON a.date_id=d.id LEFT JOIN salary sal ON s.id=sal.staff_id WHERE a.iid='".$this->session->userdata("staff_Org_id")."' AND s.status!=0 AND ( d.day >='". mktime(0, 0, 0, $t['mon'], 1, $t['year'])."' AND d.day < '".mktime(0, 0, 0, $t['mon']+1, 1, $t['year'])."' ) GROUP BY staff");
                                 if($query->num_rows()>0){
                                     
                                 $query=$query->result();
                                 $i=1;
                                 $ids="";
                                 foreach ($query as $value) {
                                     $ids.=$value->id.",";
                                     ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->amount ?></td>
                                        <td><?php echo $value->total ?></td>
                                        <td><?php echo $value->present ?></td>
                                        <td><?php
                                           echo $per=round( (($value->present/$value->total) * 100 ));
                                           ?>%</td>
                                            <td>
                                                <input type="text" placeholder="Enter Amount" class="form-control" name="amount_<?php echo $value->id ?>"  value="<?php
                                                     if(strlen($this->form->value("amount_". $value->id))!=0 ){
                                                         echo $this->form->value("amount_".$value->id);
                                                     }else{
                                                     echo ceil( ($value->present/$value->total)*($value->amount));
                                                     }
                                                     ?>"   />
                                                        <span style=" color: red">
                                                          <?php     $this->form->error("amount_". $value->id); ?>
                                                        </span>
                                                    
                                             </td>
                                    </tr>
                                    <?php
                                 }
                                 $ids=substr($ids,0,strlen($ids)-1);
                                 }else{
                                     ?>
                                    <tr><td colspan="7" style=" text-align: center; color: red"><br/>** Staff Attendance Records Not Found..</td></tr>
                                     <?php 
                                 }
                               ?>
                             </tbody>
                       </table>
                             <input type="hidden" name="ids" value="<?php echo $ids ?>" />
                             <br/>
                              <?php
                                if($ids!=0){
                                   ?>
                                     <div class="col-sm-12"> 
                                        <div class="col-sm-4">
                                            &nbsp;
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="submit"  name="submit" value="Pay Salary" class="btn btn-primary btn-block  " />  
                                        </div>
                                        <div class="col-sm-4">
                                            &nbsp;
                                        </div>
                                       <br/>  <br/>  
                                    </div>
                                   <?php
                                }
                              ?>
                            
                          </form>
                    </div>
                </div>
            </div>

        </div>
</div>

<?php

?>