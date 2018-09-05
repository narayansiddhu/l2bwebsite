<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php
 $status="";$from=$to="";
 $error=""; 
 if(isset($_GET['q'])){
     $q=trim($_GET['q']);
     if($q=='Approved'){
         $status="2";
     }elseif($q=='Pending'){
         $status="1";
     }elseif($q=='Rejected'){
         $status="0";
     }
  }
  if( (isset($_GET['from']))&&(isset($_GET['to']))){
     $from=trim($_GET['from']);
     $to=trim($_GET['to']);
     $from = explode("/",$from);
     $to = explode("/",$to);
     if( (sizeof($from)==3)&& (sizeof($to)==3) ){
         $from=  mktime(0, 0, 0, $from['1'],$from['0'], $from['2']);
         $to=  mktime(0, 0, 0, $to['1'],$to['0'], $to['2']);
         if($from>$to){
             $from=$to="";
             $error ="Please Select Valid From and To Date";
         }
     }else{
         $from=$to="";
         $error ="Please Select Valid From and To Date";
     }

  }
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
                    <a href="<?php echo base_url(); ?>index.php/expenditure/">Expenditure</a>
                </li>
            </ul>

    </div> 
        <div class="box box-bordered box-color ">
            <div class="box-title">
                    <h3><i class="fa fa-th-list"></i> View Expenditure </h3> 
            </div>
            <div class="box-content nopadding"> 
                <form class='form-horizontal form-bordered'  method="get"  >
                    <div class="box">
                        <div class="col-sm-6 nopadding">
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Staff</label>
                                <div class="col-sm-10">
                                    <select class="select2-me" name="staff" style=" width: 100%" >
                                        <option value="" >Select A Staff</option>
                                        <?php
                                        $staff ="SELECT * from  staff WHERE iid='".$this->session->userdata('staff_Org_id')."' AND level!=1 ";
                                        $staff=$this->db->query($staff)->result();
                                        foreach ($staff as $value) {
                                            ?><option value="<?php echo $value->id ?>" ><?php echo $value->name ?></option><?php 
                                        }
                                        ?>
                                    </select>
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('staff');   
                                            ?>
                                    </span>        
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">From</label>
                                <div class="col-sm-10">
                                    <input type="text" name="from" value="<?php echo date("d/m/Y",time()); ?>" id="textfield"  class="form-control datepick "/>
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('to');   
                                            ?>
                                    </span>        
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 nopadding" style=" padding-left: 5px" >
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Reason</label>
                                <div class="col-sm-10">
                                    <select class="select2-me col-sm-4" name="q" style=" width: 100%"  >
                                        <option value="">STATUS</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Pending" >Pending</option>
                                        <option value="Rejected" >Rejected</option>
                                    </select>
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('q');   
                                            ?>
                                    </span>        
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">To</label>
                                <div class="col-sm-10">
                                    <input type="text" name="to" value="<?php echo date("d/m/Y",time()); ?>" id="textfield"  class="form-control datepick "/>
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('to');   
                                            ?>
                                    </span>        
                                </div>
                            </div> 
                        </div>
                        <hr style=" clear: both;"/>
                        <div   class="form-actions col-sm-offset-4 col-sm-4">
                            <input type="submit"  name="" value="Fetch Records" class="btn btn-primary btn-block" />
                        </div>
                    </div>
                    
                    
                        
                </form>
            </div>
        </div>
        
     <?php
     $timer_query ="";
     if((strlen($from)!=0)&&(strlen($to)!=0) ){
         $to=$to+86400;
         $timer_query = " AND (time >='".$from."' AND time <='".$to."'  ) ";                                  
     }
   //  echo "SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND status in (".$status.") ORDER BY time DESC ";
      if( strlen($status)!=0 ){
          ?>
                 <div class="box box-bordered box-color ">
                    <div class="box-title">
                            <h3><i class="fa fa-money"></i>Results</h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Staff Name</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                              if(strlen($timer_query)!=0){
                               $query=("SELECT e.*,s.name FROM `expenditure` e JOIN staff s ON e.staff_id=s.id  WHERE e.iid='".$this->session->userdata('staff_Org_id')."' AND e.status in (".$status.")  ".$timer_query."  ");
                              }else{
                                  $query=("SELECT e.*,s.name FROM `expenditure` e JOIN staff s ON e.staff_id=s.id  WHERE e.iid='".$this->session->userdata('staff_Org_id')."' AND e.status in (".$status.")  ");
                              }
                              if(strlen($this->input->get("staff"))!=0){
                                 $query.="AND e.staff_id='".$this->input->get("staff")."'"; 
                              }
                              $query.="ORDER BY e.time DESC";
                            
                              $query=$this->db->query($query);
                             //   $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND status in (".$status.") ORDER BY time DESC ");
                                 $query=$query->result();
                                 $i=1;
                                 foreach($query as $value){
                                    // print_r($value);
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->name ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y h:i",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2: echo "Approved";break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                
                                <td>
                                    <?php
                                           if($value->status!=2){
                                               ?>
                                            <a href="<?php echo base_url(); ?>index.php/expenditure/edit/<?php echo  $value->id ?>" ><i class="fa fa-pencil-square-o"></i></a>
                                              <?php
                                           }else{
                                               echo "Approved On :".date('d-m-y',$value->approved_on);
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
        <?php
        }else{
            ?>
           <div class="box box-bordered box-color ">
                    <div class="box-title">
                            <h3><i class="fa fa-money"></i>View Expenses</h3> 
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Reason</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(strlen($timer_query)!=0){
                               $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND status =1  ".$timer_query." ORDER BY time DESC ");
                              }else{
                              $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND status =1 ORDER BY time DESC ");
                                } 
                             //   $query=$this->db->query("SELECT * FROM `expenditure` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND status =1 ORDER BY time DESC ");
                                 $query=$query->result();
                                 $i=1;
                                 foreach($query as $value){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $value->reason ?></td>
                                <td><?php echo $value->amount ?></td>
                                <td><?php echo date("d-m-y h:i",$value->time); ?></td>
                                <td><?php 
                                        switch($value->status){
                                           case 1: echo  "Not Approved"; break;
                                           case 2: echo "Approved";break;
                                           case 0: echo "Rejected";break;
                                        }
                                    ?></td>
                                
                                <td>
                                    <?php
                                           if($value->status!=2){
                                               ?>
                                            <a href="<?php echo base_url(); ?>index.php/expenditure/edit/<?php echo  $value->id ?>" ><i class="fa fa-pencil-square-o"></i></a>
                                              <?php
                                           }else{
                                               echo "Approved On :".date('d-m-y',$value->approved_on);
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
            <?php
        }
      ?>
    
</div>
    </div>
</div>

<?php

$this->load->view('structure/footer');

?>