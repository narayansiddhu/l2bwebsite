<?php
$this->load->view('structure/header');
?>
<style type="text/css">
    .form-group1{
        height: 70px;
        max-height: 75px;
    }
    .form-group2{
        height: 130px;
        max-height: 140px;
    }
    .fip{
        height: 100%;
    }
</style>
<?php
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
                        <a href="">New Admission</a>
                    </li>
                </ul>
            </div>
                <?php
            if(strlen($this->session->userdata('student_add_Sucess'))>0 ){
                ?>
            <br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('student_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('student_add_Sucess');
            }
            ?>
    
                    <div class="box ">
                            <div class="box-title">
                                <h3><i class="fa fa-child"></i>Add Admission</h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Admissions/create" method="post" enctype="multipart/form-data"  >
                                    
                                    <h4 style=" margin: 0px; padding-top: 15px;  width: 100%; color: #66cc00   ">Personal Details :</h4>
                                    <div class="box"  style=" height: auto"  >
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                                <label for="field-1" class="control-label">Name <span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" name="stdname" data-validate="required" data-message-required="value_required" value="<?php echo $this->form->value('stdname') ?>" placeholder=" enter Student name" maxlength="50" />
                                            <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('stdname');
                                                   ?>
                                            </span> 
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label ">Father</label>
                                            <input type="text" class="form-control" maxlength="50" name="stdfather" placeholder="Enter Student Father Name" value="<?php echo $this->form->value('stdfather');  ?>" >
                                            <span style=" color: red">
                                                <?php echo $this->form->error('stdfather'); ?>
                                            </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1" >
                                            <label for="field-2" class="control-label">Mother</label>
                        
                                                    <input type="text" maxlength="50" class="form-control" name="stdmother" placeholder="Enter Student Mother Name" value="<?php echo $this->form->value('stdmother');  ?>" >
                                                        <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdmother');
                                                        ?>
                                                    </span>
                                            </div>
				        </div>
                                        
                                        
                                        <div class="col-sm-4">
                                                <div class="form-group1" >
                                                    <label for="field-2" class="control-label">D.O.B</label>                        
                                                                <input type="text" name="stddob" placeholder="Select Date Of Birth" id="stddob" class="form-control datepick" value="<?php echo $this->form->value('stddob') ?>">
                                                                <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('stddob');
                                                                       ?>
                                                                </span>     
                                                    </div>
                                        </div>
                                        
                                            <div class="col-sm-4">
                                                <div class="form-group1" >
                                                    <label for="field-2" class="control-label " style="clear : both" >Gender<span style="color: red">*</span></label>

                                                        <select class="select2-me" style=" width: 100%" name="stdsex" class="form-control">
                                                                <option value="">select</option>
                                                                <option <?php if($this->form->value('stdsex') ==1 ){ echo "selected";
                                                                     }?> value="1">male</option>
                                                               <option <?php if($this->form->value('stdsex') ==2 ){ echo "selected";
                                                                     }?>  value="2">female</option>
                                                            </select>
                                                          <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdsex');
                                                                   ?>
                                                          </span>
                                                </div>
                                            </div>
                                        <div class="col-sm-4">
                                            <div class="col-sm-6 nopadding">
                                                <div class="form-group1">
                                            <label for="textfield" class="control-label">Caste</label>
                                           
                                                 <select name="caste" id="caste" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                        <option value="" >Select Caste</option> 
                                                    <?php                                       
                                                    $Caste_system = unserialize (Caste_system);
                                                   foreach ($Caste_system as $key=>$value) {
                                                       ?>
                                                        <option  value="<?php echo $key ?>" <?php
                                                      if($this->form->value('caste')== $key){
                                                          echo "selected";
                                                      }

                                                     ?>   ><?php echo $value ?></option>
                                                      <?php
                                                   }

                                                  ?>
                                                   </select>
                                                   <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('caste')
                                                              ?>
                                                       </span>        
                                            
                                       </div> 
                                            </div>
                                            <div class="col-sm-6 nopadding">
                                                <div class="form-group1"  style=" padding-left: 10px">
                                                        <label for="textfield" class="control-label">Religion</label>

                                                             <select name="religion" id="religion" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                                    <option value="" >Select religion</option> 
                                                                <?php                                       
                                                                $Religion = unserialize (Religion);
                                                               foreach ($Religion as $key=>$value) {
                                                                   ?>
                                                                    <option  value="<?php echo $key ?>" <?php
                                                                  if($this->form->value('religion')== $key){
                                                                      echo "selected";
                                                                  }

                                                                 ?>   ><?php echo $value ?></option>
                                                                  <?php
                                                               }

                                                              ?>
                                                               </select>
                                                               <span style=" color: red">
                                                                       <?php
                                                                           echo $this->form->error('caste')
                                                                          ?>
                                                                   </span>        

                                                   </div>
                                            </div>
                                             
                                           
                                        </div>
                                        
                                        
                                            <div class="col-sm-4">
                                                <div class="form-group1" >
                                                        <label for="textfield" class="control-label">Medium Of Study<span style=" float: right ; color: red">*</span></label>

                                                             <select name="medium" id="medium" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                                    <option value="" >Select Medium</option> 
                                                                <?php                                       
                                                                $medium = unserialize (medium);
                                                               foreach ($medium as $key=>$value) {
                                                                   ?>
                                                                    <option  value="<?php echo $key ?>" <?php
                                                                  if($this->form->value('medium')== $key){
                                                                      echo "selected";
                                                                  }

                                                                 ?>   ><?php echo $value ?></option>
                                                                  <?php
                                                               }

                                                              ?>
                                                               </select>
                                                               <span style=" color: red">
                                                                       <?php
                                                                           echo $this->form->error('medium')
                                                                          ?>
                                                                   </span>        

                                                   </div>
                                                <div class="form-group1" >
						<label for="field-2" class="control-label">Class Of Admission<span style=" float: right ; color: red">*</span></label>
                                                <?php
                                                
                                                ?>
                                                <select class="select2-me" style=" width: 100%" name="std_cls" class="form-control" name="class_admission" >
                                                    <option value="">Select Class </option>
                                                    <?php
                                                  echo   $cls= "SELECT * from class where iid='".$this->session->userdata("staff_Org_id")."'";
                                                     $cls = $this->db->query($cls)->result();
                                                     foreach ($cls as $value) {
                                                      ?>
                                                    <option value="<?php echo $value->id ?>"
                                                            <?php
                                                            if($this->form->value('std_cls')==$value->id){
                                                               echo 'selected=""'; 
                                                            }
                                                            
                                                            ?>  ><?php echo $value->name ?></option>
                                                      <?php   
                                                     }
                                                     ?>
                                                </select>
                                                
							  <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('std_cls');
                                                               ?>
                                                      </span>
					    </div>
                                            </div>
                                        
                                        
                                            <div class="col-sm-4">
                                                <div class="form-group1" >
                                                    <label for="field-2" class="control-label">Mobile<span style=" float: right ; color: red">*</span></label>
                                                            <input type="text" maxlength="50" class="form-control" name="Mobile" placeholder="Enter Mobile No" value="<?php echo $this->form->value('Mobile');  ?>" >
                                                                <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('Mobile');
                                                                ?>
                                                            </span>
                                                    </div>
                                                <div class="form-group1" >
						<label for="field-2" class="control-label">E-mail</label>
                        
							<input type="text" class="form-control" name="email" value="<?php echo $this->form->value('email') ?>" placeholder="Enter E-mail " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('email');
                                                               ?>
                                                      </span>
					    </div>
                                                
                                            </div>
                                      
                                        
                                        <div class="col-sm-4">
                                            <div class="form-group2">
                                                    <label for="field-2" class="control-label">Address</label>
                                                    <textarea class="form-control" name="stdaddress" value="" placeholder="Enter Student Address" style=" width: 100%; height: 100px; resize: none" ><?php echo $this->form->value('stdaddress') ?></textarea>
                                                        <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdaddress');
                                                                   ?>
                                                          </span>
                                            </div>
                                            
				        </div>
                                        
                                    </div>
                                    <div class="box">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Previous Academic Study:</h4>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
                                                    <label for="textfield" class="control-label">Medium Of Study<span style=" float: right ; color: red">*</span></label>
                                                    <select name="medium" id="medium" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                             <option value="" >Select Medium</option> 
                                                         <?php                                       
                                                         $medium = unserialize (medium);
                                                        foreach ($medium as $key=>$value) {
                                                            ?>
                                                             <option  value="<?php echo $key ?>" <?php
                                                           if($this->form->value('medium')== $key){
                                                               echo "selected";
                                                           }

                                                          ?>   ><?php echo $value ?></option>
                                                           <?php
                                                        }

                                                       ?>
                                                        </select>
                                                        <span id="medium_err" style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('medium')
                                                                   ?>
                                                            </span>       
                                            </div> 
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Previous Class<span style=" float: right ; color: red">*</span></label>
                                                        <input type="text" name="prev_cls" class="form-control" placeholder="Enter Prevoius Class of study" value="<?php echo $this->form->value('prev_cls');  ?>" />
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('prev_cls');
                                                            ?>
                                                        </span> 
					    </div>
                                        </div>
                                     <div class="col-sm-4">
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Previous School<span style=" float: right ; color: red">*</span></label>
                                                    <input type="text" name="prev_School" class="form-control" placeholder="Enter Prevoius Schoool Name" value="<?php echo $this->form->value('prev_School');  ?>" />
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('prev_School');
                                                           ?>
                                                    </span>
					    </div>
                                    </div>   
                                        
                                    </div>
                                    
                                    <div class="box"  style=" height: auto"  >
                                        <div class="form-actions col-sm-offset-4 col-sm-4">
                                            <input type="submit" name="submit" value="Add Admission" onclick="upload_snapshot();" class="btn btn-primary btn-block" />
                                        </div>
                                    </div>
                                    
                                    
				    
                                
                                </form>
                                
                                
                            </div>
                    </div>
                    
                    <span style=" color : red; float: right">Note : * Mandatory Fields</span>
                    
                <script type="text/javascript">

                    function get_class_sections(class_id) {
                      if(class_id.length!=0){
                         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
                      }       
                    }
                    
                    function get_district_list(state_id) {
                      if(class_id.length!=0){
                         setState('district_selector_holder','<?php echo base_url() ?>index.php/students/load_district','state_id='+state_id); 
                      }       
                    }
                    
                </script>
        </div>
    </div>
</div>
    
<?php
$this->load->view('structure/footer');
?>
