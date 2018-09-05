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
                    <a href="<?php echo base_url(); ?>index.php/accounts/view/">Manage Fee</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/fee/manage_concessions">Manage Fee Concession</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Concessions</a>
                </li>
            </ul>

    </div>
    
    
    <div class="row nopadding">
        <div class="col-sm-4">
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-child"></i>Student Details</h3> 
                           
                    </div>
                    <div class="box-content nopadding">                                
                        <table class="table table-hover table-nomargin  table-bordered">
                            <tr>
                                <th>Student</th><td><?php echo $concession->stud_name ?></td>
                            </tr>
                            <tr>
                                <th>Class</th>
                                <td><?php echo $concession->cls_name." - ".$concession->sec_name ?></td>
                            </tr>
                            <tr>
                                <th>Admission No</th><td><?php echo $concession->userid ?></td>
                            </tr>
                            <tr>
                                <th>Roll No</th><td><?php echo $concession->roll ?></td>
                            </tr>
                            <tr>
                                <th>Assigned By</th>
                                <td><?php echo $concession->staff_name ?></td>
                            </tr>
                            <tr>
                                <th>Assigned On</th>
                                <td><?php echo date('d-m-y H:i',$concession->time) ?></td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-inr"></i>Concession Details</h3> 
                            <div class="actions">
                                <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/fee/edit_concession/<?php echo $concession->cid ?>" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Concession</a>
                            </div>
                    </div>
                    <div class="box-content nopadding">                                
                        <?php
                          $q=" SELECT f.id,f.amount,c.category,c.cid FROM fee_concession f JOIN fee_category c ON f.cat_id=c.cid WHERE f.conc_id='".$concession->cid."'  ";
                          $q = $this->db->query($q);
                          $q =$q->result();
                          $concess_Array= array();
                          foreach($q as $val){
                              $concess_Array[$val->cid]= array('old_id'=>$val->id,'amount'=>$val->amount);
                          }
                          $paid_Arr=array();
                        foreach ($paid_details as $val){
                            $paid_Arr[$val->category] =$val->total;
                        }
                        
                        ?><form action="<?php echo base_url() ?>index.php/fee/save_con_changes" method="post">
                            <input type="hidden" name="conc_id" value="<?php
                               echo $concession->cid;
                            ?>" />
                            <input type="hidden" name="student_id" value="<?php
                               echo $concession->student_id;
                            ?>" />
                            
                            <table class="table table-hover table-nomargin  table-bordered">
                                <tr>
                                    <th style=" padding-top: 25px;">Reason
                                    </th>
                                    <td colspan="4"><textarea style=" resize: none" name="reason"  class="form-control"><?php echo $concession->reason  ?></textarea></td>
                                </tr>
                                <tr>
                                    <th>S.no</th>
                                    <th>Category</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Concession</th>
                                <tr>
                            
                                <?php
                                $i=1;$cat_id="";
                                foreach ($cls_details as $value) {
                                    $total=$paid=0;
                                    $cat_id=$cat_id.",".$value->catid;
                                    ?>
                                  <tr>
                                      <td><?php echo $i++ ?></td>
                                      <td><?php echo $value->category ?></td>
                                      <td><?php 
                                      if((strlen($value->fee))>0){
                                        $total =  $value->fee ;
                                        echo $value->fee ;
                                      }else{
                                          echo "0";
                                          
                                      } 
                                      
                                      ?></td>
                                      <td><?php 
                                          if(isset($paid_Arr[$value->catid])){
                                              $paid =$paid_Arr[$value->catid];
                                              echo $paid_Arr[$value->catid];
                                          }else{
                                              echo "0";
                                          }
                                      
                                      ?></td>
                                      <td>
                                          <?php
                                          $con=0;
                                            if(isset($concess_Array[$value->catid]['amount'] )){
                                             $con= $concess_Array[$value->catid]['amount']   ;
                                             ?>
                                          <input type="hidden" name="old_conession_<?php echo $value->catid ?>" value="<?php echo $concess_Array[$value->catid]['old_id']   ?>" />
                                             <?php
                                            } 
                                          ?>
                                          <input type="hidden" name="total_<?php echo $value->catid ?>" value="<?php echo $total.",".$paid ?>" />
                                          <input type="text" name="concession_<?php echo $value->catid ?>" value="<?php echo $con ?>" class="form form-control" />
                                      </td>

                                  </tr>
                                   <?php
                                }
                                
                                ?>
                                 <input type="hidden" name="cat_id" value="<?php echo $cat_id ?>" />
                                  <tr  >
                                      <td colspan="5" style=" text-align: center" ><input type="submit" name="submit"  class="btn btn-primary" value="Save Concession" /></td> 
                                  </tr>
                            
                        </table>
                        </form>
                    </div>
            </div>
        </div>
    </div>
            
                
        </div>           
    </div>
  </div>    
<?php
$this->load->view('structure/footer');
?>