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
                <a href="<?php echo base_url(); ?>index.php/library/">Library</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="">Pay Fine</a>
            </li>
        </ul>
    </div>  
    
    <?php
        if(strlen($this->session->userdata('lib_payments_sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('lib_payments_sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('lib_payments_sucess');
        }
    ?>
           <div class="box">
               <div class="col-sm-6 nopadding">
                   <div class="box box-bordered box-color " >
                    
                        <div class="box-title">
                            <h3>Transaction Details </h3>
                            
                        </div>  
                        <div  class="form-horizontal box-content nopadding">
                           
                            <table class="table table-bordered table-striped ">
                                <tr>
                                    <th>Transaction Id </th>
                                    <td colspan="2"><?php echo $book->trans_id ?></td>
                                </tr>
                                <tr>
                                    <th>Issued On</th>
                                    <td colspan="2"><?php echo date('d-m-y',$book->issued_date); ?></td>
                                </tr>
                                <tr>
                                    <th>Submitted On</th>
                                    <td colspan="2" >
                                        <?php 
                                                if($book->return_date!=0){
                                                    echo date('d-m-y',$book->return_date); 
                                                }else{
                                                    echo "---";
                                                }
                                                ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fine </th>
                                    <td colspan="2"><?php echo $fee->fee; ?></td>
                                </tr>
                                <tr>
                                    <th>Fine Imposed On </th>
                                    <td colspan="2"><?php echo date('d-m-y',$fee->time); ?></td>
                                </tr>
                                <tr>
                                    <th>Reason</th>
                                    <td colspan="2"><?php echo $fee->description ?></td>
                                </tr>
                            </table>
                        </div>
                   
           </div>
               </div>
               <div class="col-sm-6 nopadding">
                   <div style=" padding-left: 5px" class="box box-bordered box-color">
               
                    <div class="box-title">
                        <h3>Payment Details</h3>
                        <div class="actions" style=" color: white">
                            <h3>Total :<span id='total_m'></span>&nbsp;&nbsp;&nbsp;</h3><h3>Balance :<span id='balance_m'></span> </h3>
                        </div>
                    </div>  
                    <div  class="box-content nopadding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Amount</th>
                                    <th>Payed On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                  $query=$this->db->query("SELECT *  FROM `lib_payments` WHERE `fee_id` = '".$fee->fine_id."'");
                                   $total=0;$i=1;
                                  if($query->num_rows()>0){
                                        $query=$query->result();
                                        foreach ($query as $value) {
                                          ?>
                                              <tr>
                                                  <td><?php echo $i++; ?></td>
                                                  <td><?php echo $value->amount ;
                                                          $total+=$value->amount;
                                                          ?></td>
                                                  <td><?php echo date('d-m-y',$value->time); ?></td>
                                              </tr>
                                          <?php
                                        } 
                                  }else{
                                      ?>
                                              <tr><td colspan="3">No Records Found.....</td></tr>
                                      <?php
                                  }
                                  $bal=$fee->fee-$total;
                                ?>
                            </tbody>
                        </table>
                        <script>
                        $('#total_m').html('<?php echo $total ?>');
                        $('#balance_m').html('<?php echo $bal ?>');
                        </script>
                        
                    </div>
                
           </div>
                   
                   <?php
                if($bal>0){
                  ?>           
                   <div class="box box-bordered box-color" style=" padding-left: 5px">

                        <div class="box-title">
                            <h3>Pay Fine</h3>                                    
                        </div>  
                        <div  class="box-content nopadding">
                            <div class=' form-horizontal form-bordered'>

                                    <input type="hidden" id="max_fine" value="<?php echo $bal ?>" />
                                     <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Amount</label>
                                        <div class="col-sm-10">
                                            <input class="form-control"   type="text" name="payment" id="pay_amount" value="<?php echo $bal; ?>" />
                                            <span id="pay_amount_err" style="color:red" >

                                            </span>
                                        </div>
                                    </div>
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="button"  onclick="pay_fine();"  id="payments"  value="Pay Fine" class="btn btn-primary" />

                                      <span id="pay_errors" style="color:red" >

                                            </span>
                                    </div>

                            </div>
                        </div>
                    </div>                           
                  <?php
                }
           ?>
                   
               </div>
           </div>
           
    
        </div> 
    </div>
</div>
<script>
 function pay_fine(){
     var pay=$('#pay_amount').val();
     var max=$('#max_fine').val();
        $('#pay_amount_err').html("");
    //pay_amount_err
    if(pay.length==0){
        $('#pay_amount_err').html("Please eneter Payemnt Amount.");
    }else{
        if(!$.isNumeric(pay)){
          $('#pay_amount_err').html("Please enter numeric value ")  ;
        }else{
          if(pay>max){
              $('#pay_amount_err').html("Max Balance Excedded")  ;
          } else{ 
             setState('pay_errors','<?php echo base_url() ?>index.php/library/make_payment','amount='+pay+'&fine_id=<?php echo $fee->fine_id  ?>'+'&max_fine='+max);
          }
        }
    }
    
    
 }    

</script>
<?php
$this->load->view('structure/footer');
?>

