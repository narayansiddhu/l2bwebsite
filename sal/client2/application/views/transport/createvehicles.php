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
                        <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/transport/view/">Add Vehicles</a>
                    </li>
                </ul>
            </div>
            <div class="box ">
                    <div class="box-title">
                            <h3><i class="fa fa-car"></i>Create Vehicles</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/transport/add_vehicle" method="post" enctype="multipart/form-data"  >
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                           
                                <h4 style=" margin: 0px; padding-top: 8px;  width: 100%; color: #66cc00   ">Personal Details :</h4>
                              
                                <div class="box" style=" width: 100%; height: auto">

                                     <div class="col-sm-4 nopadding" >
                                         <div class="form-group1">
                                             <label for="textfield" class="control-label ">Vechile No<span style=" float: right ; color: red">*</span></label>
                                                 <input type="text" name="vechile_no" placeholder="Enter Vechile No" class="form-control" value="<?php echo $this->form->value('vechile_no') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('vechile_no');   
                                                         ?>
                                                     </span>        
                                         </div>
                                     </div>  

                                     <div class="col-sm-4 " >

                                          <div class="form-group1">
                                              <label for="textfield" class="control-label ">Manufacturer<span style=" float: right ; color: red">*</span></label>
                                                  <input type="text" name="Manufacturer" placeholder="Enter Manufacturer Name" class="form-control" value="<?php echo $this->form->value('Manufacturer') ?>" > 
                                                  <span style=" color: red">
                                                          <?php
                                                              echo $this->form->error('Manufacturer')
                                                             ?>
                                                      </span>  
                                          </div> 
                                     </div>

                                     <div class="col-sm-4 " >

                                          <div class="form-group1">
                                              <label for="textfield" class="control-label ">Fuel Type<span style=" float: right ; color: red">*</span></label>
                                              <Select class=" select2-me" style=" clear: both; width: 100%;" name="type">
                                                  <option value="">Select Fuel Type</option>
                                                  <?php
                                                    $fuel= unserialize(fuel);
                                                    foreach ($fuel as $key => $value) {
                                                        ?><option value="<?php echo $key ?>"
                                                                <?php 
                                                                if($this->form->value('type')==$key){
                                                                    echo 'selected="';
                                                                }
                                                                ?>
                                                                ><?php echo $value ?></option><?php
                                                    }
                                                  ?>
                                              </Select>  
                                                   
                                                    <span style=" color: red">
                                                          <?php
                                                              echo $this->form->error('stemail');
                                                             ?>
                                                      </span>
                                          </div> 

                                      </div>

                                 </div>
                                 
                            
                           </div>
                            <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                           
                            <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Documents Details :</h4>
                                
                                <div class="box">
                                    <table class="table table-bordered ">
                                        
                                        <tbody>
                                            <tr>
                                                <th>Document</th>
                                                <th>Document No</th>
                                                <th>validity</th>
                                            </tr>
                                            <tr>
                                                <td>Registration No( R.c)</td>
                                                <td><input type="text" name="rcno" placeholder="Enter Registration No" class="form-control" value="<?php echo $this->form->value('rcno') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('rcno');   
                                                         ?>
                                                     </span></td>
                                                <td><input type="text" name="rcdate" placeholder="Registration Validity date" class="form-control datepick" value="<?php echo $this->form->value('rcdate') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('rcdate');   
                                                         ?>
                                                     </span></td>
                                            </tr>
                                            <tr>
                                                <td>Insurance</td>
                                                <td><input type="text" name="insurance" placeholder="Enter Insurance Reg No" class="form-control" value="<?php echo $this->form->value('insurance') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('insurance');   
                                                         ?>
                                                     </span></td>
                                                <td><input type="text" name="insurancedate" placeholder="insurance Validity date" class="form-control datepick" value="<?php echo $this->form->value('insurancedate') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('insurancedate');   
                                                         ?>
                                                     </span></td>
                                            </tr>
                                            <tr>
                                                <td>P.U.C (Pollution)</td>
                                                <td><input type="text" name="puc" placeholder="Enter Pollution Checcking No" class="form-control" value="<?php echo $this->form->value('puc') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('puc');   
                                                         ?>
                                                     </span></td>
                                                <td><input type="text" name="pucdate" placeholder="Registration Validity date" class="form-control datepick" value="<?php echo $this->form->value('pucdate') ?>" > 
                                                 <span style=" color: red">
                                                         <?php
                                                            echo $this->form->error('pucdate');   
                                                         ?>
                                                     </span></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            
                            </div>
                            <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                              <div class="form-actions col-sm-offset-4 col-sm-4">
                                  <input type="submit" name="submit" value="Create Vechile"  class="btn btn-primary btn-block" />
                            </div>
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