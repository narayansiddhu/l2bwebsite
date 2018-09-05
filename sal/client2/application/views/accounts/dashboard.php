<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php
$fee=$this->db->query("SELECT sum(amount) as total from fee_accounts where iid='".$this->session->userdata("staff_Org_id")."' ")->row();
$fee =$fee->total;
if( strlen($fee)==0){
   $fee =0;
}
$lib=$this->db->query("SELECT sum(amount) as total from lib_payments where iid='".$this->session->userdata("staff_Org_id")."' ")->row();
$lib =$lib->total;
if( strlen($lib)==0){
   $lib =0;
}
$tran=$this->db->query("SELECT sum(fee) as total from transport_fee where iid='".$this->session->userdata("staff_Org_id")."' ")->row();
$tran =$tran->total;
if( strlen($tran)==0){
   $tran =0;
}
$in_bound= $fee+$lib+$tran;
$expenses="select sum(amount) as total from expenditure where  status = 1 AND iid='".$this->session->userdata("staff_Org_id")."'";
$expenses =$this->db->query($expenses)->row();
$expenses =$expenses->total;
if( strlen($expenses)==0){
   $expenses =0;
}                  
$travel_main="SELECT sum(amount) as total FROM `trans_maintaince` where  iid='".$this->session->userdata("staff_Org_id")."'";
$travel_main =$this->db->query($travel_main)->row();
$travel_main = $travel_main->total;
if( strlen($travel_main)==0){
          $travel_main=0;
}
$in_bound=$in_bound-($expenses+$travel_main);
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
                    <li >
                        <a href="">Accounts</a>
                    </li>
                </ul>
            </div><br>
                <?php
      if(strlen($this->session->userdata('pass_add_Sucess'))>0 ){
    ?>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        
         <?php echo $this->session->userdata('pass_add_Sucess'); ?>
        </div>
       <script>
                $("#successMsg").fadeIn();
        $("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('pass_add_Sucess');
}
    ?>
            <div class="box">
                <div class="col-sm-2 col-sm-offset-10">
                       <a href="<?php echo base_url(); ?>index.php/accounts/settings" title="Settings"><i style=" color: #012B72;font-size: 30px;" class="fa fa-cogs"></i></a>
                </div>
            </div>
            <div class="box" >
                <div style=" float: left; width: 30%;">
                    <h3 style=" color: teal; margin-top: 40px;">Total Available Balance :&nbsp;&nbsp;&nbsp;<?php echo $in_bound ?></h3>
                </div>
                <div style=" float: left; width: 40%;">
                    <div class="box" style=" margin-top: 40px;">
                        <form >
                        <div class="col-sm-4 nopadding">
                            <input type="text" value="<?php echo $this->input->get("from"); ?>" name="from" placeholder="Select From Date" class="form-control datepick" />
                        </div>
                            <div class="col-sm-1 nopadding" style=" text-align: center;">
                                --
                        </div>
                        <div class="col-sm-4 nopadding">
                                <input type="text" value="<?php echo $this->input->get("to"); ?>" name="to" placeholder="Select To Date" class="form-control datepick" />
                        
                        </div>
                        <div class="col-sm-1 nopadding">
                            <button type="submit"  class="btn btn-primary" style=" height: 34px;"><i class="fa fa-search"></i></button>
                        </div>
                            <div class="col-sm-1 nopadding">
                            <a type="submit" href="<?php echo base_url(); ?>index.php/accounts/print_details/all/?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>" rel="tooltip" title="" data-original-title="Print Details" style="background-color: orange ; padding-top: 12px; padding-bottom: 8px;"  class="btn btn-primary" style=" height: 45px;"><i class="fa fa-print"></i></a>
                        </div>
                            <div class="col-sm-1 nopadding">
                            <a type="submit" href="<?php echo base_url(); ?>index.php/accounts/print_details/all/brief_report?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>" rel="tooltip" title="" data-original-title="Print Brief Details"  style="background-color: greenyellow;  padding-top: 12px; padding-bottom: 8px;"  class="btn btn-primary" style=" height: 45px;"><i class="fa fa-info-circle"></i></a>
                        </div>
                             </form>
                    </div>
                </div>
                <div style=" float: left; width: 30%; margin-top: 25px;">
                    <a style=" float: right" class="new_title1" href="<?php echo base_url(); ?>index.php/accounts/view"  >
                        <i class="fa fa-usd fa-2x"></i>
                        <p>Fees</p>
                    </a>
                    
                    <a style=" float: right" class="new_title1" href="<?php echo base_url(); ?>index.php/expenditure/"  >
                        <i class="fa fa-money fa-2x"></i>
                        <p>Expenditure</p>
                    </a>
                    <a style=" float: right" class="new_title1" href="<?php echo base_url(); ?>index.php/salary/pay_salary"  >
                        <i class="fa fa-inr fa-2x"></i>
                        <p>Salaries</p>
                    </a>
                    
                </div>
            </div>
            <br style=" clear: both ; "/>
            <hr style=" clear: both ; "/>
            <div class="box">
                 <?php
                 $t="";
                    $d= getdate();
                   // print_r($d);
                   $from=  mktime(0, 0, 0, $d['mon'], $d['mday'], $d['year']);
                   $to=mktime(0, 0, 0, $d['mon'], $d['mday']+1, $d['year']);
                     if((strlen($this->input->get("from"))!=0) ){
                         $from =  explode("/",$this->input->get("from"));
                         $from=  mktime(0, 0, 0, $from[1], $from[0], $from[2]);
                     }
                     $t = " ( ".date("d-m-y",$from)." )";
                     if(strlen($this->input->get("to"))!=0){
                         $to =  explode("/",$this->input->get("to"));
                         $to=  mktime(0, 0, 0, $to[1], $to[0], $to[2]);
                          $t = " (  ".date("d-m-y",$from) ." -- ".date("d-m-y",$to)." )";
                     }
                     
                   ?>
                <div class="col-sm-6 nopadding">
                    <div class="box" style=" padding-right: 10px;">
                        <div class="col-sm-9 nopadding">
                           <h4 style="text-align: left; color:  #ff6600">INCOME <?php echo $t ?></h4>
                        </div>
                        <div class="col-sm-3 nopadding" style=" text-align: right">
                            <a target="_blank" rel="tooltip" title="" href="<?php echo base_url(); ?>index.php/accounts/print_details/income/?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>" data-original-title="Print Details"  class="btn btn-orange"><i class="fa fa-print"></i></a>
                            <a target="_blank" rel="tooltip" title="" data-original-title="Print Brief Report" href="<?php echo base_url(); ?>index.php/accounts/print_details/income/brief_report?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>"  class="btn btn-darkblue"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                       </div>
                      <?php 
                         $income="SELECT c.category, (select sum(amount) from fee_accounts where (time >=".$from." AND  time <".$to."  ) AND category= c.cid  ) as total FROM `fee_category` c WHERE iid='".$this->session->userdata("staff_Org_id")."' ";
                         $income =$this->db->query($income)->result();
                      //   print_r($income);
                         $travel_income ="SELECT sum(fee) as total FROM `transport_fee` where  (timestamp >=".$from." AND  timestamp <".$to."  )  AND iid='".$this->session->userdata("staff_Org_id")."' ";      
                         $travel_income =$this->db->query($travel_income)->row();
                         $travel_income=$travel_income->total;
                         if( strlen($travel_income)==0){
                            $travel_income =0;
                         }
                         $t_income=0;
                         $t_income+=$travel_income;
                         $lib=$this->db->query("SELECT sum(amount) as total from lib_payments where iid='".$this->session->userdata("staff_Org_id")."' AND (time >=".$from." AND  time <".$to."  ) ")->row();
                        $lib =$lib->total;
                        if( strlen($lib)==0){
                           $lib =0;
                        }

                    ?>
                    <table class=" table table-bordered " style=" width: 100%; text-align: center" >
                        <tr>
                            <td style=" text-align: center">Category</td>
                            <td style=" text-align: center">Amount</td>
                        </tr>
                        <?php
                        foreach ($income as $value) {
                                  if( strlen($value->total)==0){
                                      $value->total=0;
                                  }
                                ?>
                        <tr>
                            <td><?php echo $value->category ?></td>
                            <td><?php echo $value->total ?></td>
                        </tr>
                              <?php
                                $t_income+=$value->total;
                              }
                              ?>
                        
                        <?php
                        $travelcheck="SELECT `transport` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
                        $travelcheck = $this->db->query($travelcheck)->row();
                        if($travelcheck->transport!=0){
                            ?>
                        <tr>
                            <td>Transport Dept</td>
                            <td><?php echo $travel_income ?></td>
                        </tr>
                            <?php
                        }
                        
                        ?>
                        
                        <tr>
                            <td>Library </td>
                            <td><?php echo $lib ?></td>
                        </tr>
                        <tr style=" color: #ff6600">
                            <td>Total Income</td>
                             <td><?php echo $t_income ?></td>
                        </tr>    
                    </table>
                        
                    </div>
                </div>
                <div class="col-sm-6 nopadding" style=" border-left: 1px solid #cccccc; ">
                    <div class="box" style="  padding-left: 15px">
                        <div class="col-sm-9 nopadding">
                          <h4 style=" text-align:left; color:  #009933">EXPENSES <?php echo $t ?></h4>
                        </div>
                        <div class="col-sm-3 nopadding" style=" text-align: right">
                            <a target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_details/expenses/?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>" rel="tooltip" title="" data-original-title="Print Details"  class="btn btn-orange"><i class="fa fa-print"></i></a>
                            <a target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_details/expenses/brief_report?from=<?php echo $this->input->get("from") ?>&to=<?php echo $this->input->get("to") ?>" rel="tooltip" title="" data-original-title="Print Brief Report"  class="btn btn-darkblue"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                        </div>
                   
                    <?php
                     $expenses="SELECT c.name ,  (select sum(amount) from expenditure where (time >=".$from." AND  time <".$to."  ) AND status = 1 AND category=c.cat_id ) as total FROM `expense_category` c WHERE c.iid='".$this->session->userdata("staff_Org_id")."'";
                     $expenses =$this->db->query($expenses)->result();
                     $travel_main="SELECT sum(amount) as total FROM `trans_maintaince` where (timestamp >=".$from." AND  timestamp <".$to."  ) AND iid='".$this->session->userdata("staff_Org_id")."'";
                     $travel_main =$this->db->query($travel_main)->row();
                     $travel_main = $travel_main->total;
                    if( strlen($travel_main)==0){
                               $travel_main=0;
                    }
                            
                     $t_expenses=0;
                    $t_expenses+=$travel_main;
                     ?>
                    <table class=" table table-bordered " style=" width: 100%;  text-align: center" >
                        <tr>
                            <td style=" text-align: center">Category</td>
                            <td style=" text-align: center">Amount</td>
                        </tr>
                        <?php 
                              foreach ($expenses as $value) {
                                  if( strlen($value->total)==0){
                                      $value->total=0;
                                  }
                                ?>
                        <tr>
                            <td><?php echo $value->name ?></td>
                            <td><?php echo $value->total ?></td>
                        </tr>
                              <?php
                                $t_expenses+=$value->total;
                              }
                             
                       
                              ?>
                        <?php
                        if($travelcheck->transport!=0){
                          ?>
                        <tr>
                            <td>Transport Maintaince</td>
                            <td><?php echo $travel_main ?></td>
                            
                        </tr>
                          <?php   
                        }
                        
                        ?>
                        
                        
                        <tr style=" color: #009933">
                            <td>Total Expenditure</td>
                             <td><?php echo $t_expenses ?></td>
                        </tr>    
                              <?php
                        ?>
                    </table>
                         </div>
                </div>
            </div>
        </div>
   </div>
</div>
<?php
$this->load->view('structure/footer');
?>