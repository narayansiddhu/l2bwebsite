<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style type="text/css">
    .form-group1{
        height: 70px;
        max-height: 75px;
    }
    .form-group2{
        height: 140px;
        max-height: 150px;
    }
    .fip{
        height: 100%;
    }
</style>
<?php
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
                    <a href="<?php echo base_url(); ?>index.php/students/">Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Edit Student</a>
                </li>
            </ul>
        </div>
   <?php
    if(strlen($this->session->userdata('student_edit_Sucess'))>0 ){
        ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
                     <button type="button" class="close" data-dismiss="alert">×</button>
                      <?php echo $this->session->userdata('student_edit_Sucess'); ?>
                     </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(2000).fadeOut();
                        </script>
       <?php
        $this->session->unset_userdata('student_edit_Sucess');
    }
    ?>
    
        
                    <div class="box ">
                            <div class="box-title">
                                    <h3><i class="fa fa-child"></i>Edit Student </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/students/edit_stud" method="post" enctype="multipart/form-data"  >
                                    <input type="hidden" name="studid" value="<?php echo $student->student_id	 ?>" />
                                    <input type="hidden" name="student_login_id" value="<?php echo $student->userid	 ?>" />
                                    <div class="box"  style=" height: auto"  >
                                        <div class="col-sm-5">
                                            <div class="form-group1" >
                                                <label for="field-1" class="control-label">Name <span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" name="stdname" data-validate="required" data-message-required="value_required" value="<?php 
                                            if(strlen($this->form->value('stdname'))>0){
                                                echo $this->form->value('stdname');
                                            }else{
                                                echo $student->name;
                                            }
                                            ?>" placeholder=" enter Student name" maxlength="50" />
                                            <span style=" color: red">
                                                <?php
                                                    echo $this->form->error('stdname');
                                                   ?>
                                            </span> 
                                            </div>
                                            <div class="form-group1" >
                                            <label for="field-2" class="control-label">Mother<span style=" float: right ; color: red">*</span></label>
                        
                                                    <input type="text" maxlength="50" class="form-control" name="stdmother" placeholder="Enter Student Mother Name" value="<?php 
                                            if(strlen($this->form->value('stdmother'))>0){
                                                echo $this->form->value('stdmother');
                                            }else{
                                                echo $student->mother_name;
                                            }
                                            ?>" >
                                                        <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdmother');
                                                        ?>
                                                    </span>
                                            </div>
                                            <div class="box">
                                                <div class="col-sm-6 nopadding">
                                                    <div class="form-group1" >
                                                    <label for="field-2" class="control-label">Date Of Birth<span style=" float: right ; color: red">*</span></label>                        
                                                                <input type="text" name="stddob" placeholder="Select Date Of Birth" id="stddob" class="form-control datepick" value="<?php if(strlen($this->form->value('stddob'))>0){
                                                echo $this->form->value('stddob');
                                            }else{
                                                echo date("d/m/Y",$student->birthday);
                                            }
                                            ?>">
                                                                <span style=" color: red">
                                                                    <?php
                                                                        echo $this->form->error('stddob');
                                                                       ?>
                                                                </span>     
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 nopadding">
                                                    <div class="form-group1" style=" padding-left: 10px" >
                                                    <label for="field-2" class="control-label">Date Of Admission<span style=" float: right ; color: red">*</span></label>                        
                                                        <input type="text" name="admission_date" placeholder="Select Date Of Admission" id="stddob" class="form-control datepick" value="<?php if(strlen($this->form->value('admission_date'))>0){
                                                echo $this->form->value('admission_date');
                                            }else{
                                                echo date("d/m/Y",$student->admission_date);
                                            }
                                            ?>">
                                                        <span style=" color: red; font-size: inherit"><?php echo $this->form->error('admission_date');  ?></span>     
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box">
                                                <div class="col-sm-6 nopadding">
                                                    
                                            <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group<span style=" float: right ; color: red">*</span></label>
                                           <?php
                                           $blood_grp ="";
                                            if(strlen($this->form->value('stbg'))>0){
                                                $blood_grp =  $this->form->value('stbg');
                                            }else{
                                                $blood_grp = $student->bloodgroup;
                                            }
                                            ?>
                                                 <select name="stbg" id="stbg" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                        <option value="" >Select Blood Group</option> 
                                                    <?php                                       
                                                    $blood_group = unserialize (blood_groups);
                                                   foreach ($blood_group as $key=>$value) {
                                                       ?>
                                                        <option  value="<?php echo $key ?>" <?php
                                                      if($blood_grp== $key){
                                                          echo "selected";
                                                      }

                                                     ?>   ><?php echo $value ?></option>
                                                      <?php
                                                   }

                                                  ?>
                                                   </select>
                                                   <span style=" color: red">
                                                           <?php
                                                               echo $this->form->error('stbg')
                                                              ?>
                                                       </span>        
                                            
                                       </div> 
                                                </div>
                                                <div class="col-sm-6  nopadding ">
                                                    
                                                    <div class="form-group1 " style=" padding-left: 10px">
                                            <label for="textfield" class="control-label">Caste<span style=" float: right ; color: red">*</span></label>
                                           <?php
                                           $caste ="";
                                            if(strlen($this->form->value('caste'))>0){
                                                $caste =  $this->form->value('caste');
                                            }else{
                                                $caste = $student->caste;
                                            }
                                            ?>
                                                 <select name="caste" id="caste" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                        <option value="" >Select Caste</option> 
                                                    <?php                                       
                                                    $Caste_system = unserialize (Caste_system);
                                                   foreach ($Caste_system as $key=>$value) {
                                                       ?>
                                                        <option  value="<?php echo $key ?>" <?php
                                                      if($caste == $key){
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
                                            
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label ">Father<span style=" float: right ; color: red">*</span></label>
                                            <input type="text" class="form-control" maxlength="50" name="stdfather" placeholder="Enter Student Father Name" value="<?php 
                                            if(strlen($this->form->value('stdfather'))>0){
                                                echo $this->form->value('stdfather');
                                            }else{
                                                echo $student->father_name;
                                            }
                                            ?>" >
                                            <span style=" color: red">
                                                <?php echo $this->form->error('stdfather'); ?>
                                            </span>
                                            </div>
                                            <div class="box">
                                                <div class="col-sm-6 nopadding">
                                                    <div class="form-group1" >
                                            
                                            <label for="field-2" class="control-label " style="clear : both" >Gender<span style="color: red">*</span></label>
                                           <?php
                                           $gender="";
                                            if(strlen($this->form->value('stdsex'))>0){
                                                $gender =  $this->form->value('stdsex');
                                            }else{
                                                $gender = $student->sex;
                                            }
                                            ?>
                                                <select class="select2-me" style=" width: 100%" name="stdsex" class="form-control">
                                                        <option value="">select</option>
                                                        <option <?php if($gender ==1 ){ echo "selected";
                                                             }?> value="1">male</option>
                                                       <option <?php if($gender ==2 ){ echo "selected";
                                                             }?>  value="2">female</option>
                                                    </select>
                                                  <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdsex');
                                                           ?>
                                                  </span>
                                            </div>
                                                </div>
                                                <div class="col-sm-6 nopadding">
                                                    
                                                    <div class="form-group1" style=" padding-left: 10px" >
                                                        <label for="textfield" class="control-label">Religion</label>
<?php
                                                                $Rel= unserialize (Religion);
                                                   
                                           $religion="";
                                            if(strlen($this->form->value('religion'))>0){
                                                $religion =  $this->form->value('religion');
                                            }else{
                                                $religion = $student->religion;
                                            }
                                            ?>
                                                             <select name="religion" id="religion" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                                    <option value="" >Select religion</option> 
                                                                <?php                                       
                                                                
                                                               
                                                               foreach ($Rel as $key=>$value) {
                                                                   ?>
                                                                    <option  value="<?php echo $key ?>" <?php
                                                                  if($religion== $key){
                                                                      echo "selected";
                                                                  }

                                                                 ?>   ><?php echo $value ?></option>
                                                                  <?php
                                                               }

                                                              ?>
                                                               </select>
                                                               <span style=" color: red">
                                                                       <?php
                                                                           echo $this->form->error('religion')
                                                                          ?>
                                                                   </span>        

                                                   </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            <div class="form-group2" >
                                            <label for="field-2" class="control-label">Identification Moles<span style=" float: right ; color: red">*</span></label>
                                                    <textarea class="form-control" name="moles" value="" placeholder="Enter Moles For Identification" style=" width: 100%; height: 100px; resize: none" ><?php
                                                    if(strlen($this->form->value('moles'))>0){
                                                echo $this->form->value('moles');
                                            }else{
                                                echo $student->moles;
                                            } ?></textarea>
                                                        <span style=" color: red"><?php  echo $this->form->error('moles'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="box"  style=" height: auto"  >
                                                <label for="textfield" class="control-label ">Image</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <div class="form-group2" id='upload_pic'>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 150px; height: 150px;">
                                                    <img name="stdimage" style="width: 145px; height: 130px;"  src="<?php echo assets_path ?>/uploads/<?php echo $student->photo ?>" />
                                                             </div>
                                                    <div>
                                                            <span class="btn btn-default btn-file">
                                                                    <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input type="file" name="stdimage" src="<?php echo assets_path ?>/uploads/<?php echo $student->photo ?>">
                                                            </span>
                                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                    </div>
                                            </div>
                                                            <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdimage');
                                                                   ?>
                                                          </span>
                                                    </div>
                                            </div> 
                                            
				        </div>
                                    </div><br style=" clear: both;" />
                                    <div class="box"  style=" clear: both; height: auto"  >
                                        <h4 style=" margin: 0px; margin-top: 35px;  width: 100%; color: #66cc00   ">Communication Details :</h4>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
						<label for="field-2" class="control-label">Student Mobile<span style=" float: right ; color: red">*</span></label>
                                                <input type="text" class="form-control" name="stdphone" value="<?php
                                                if(strlen(trim($this->form->value('stdphone')))!=0){
                                                    echo $this->form->value('stdphone');
                                                }else{
                                                    echo $student->phone;
                                                } ?>" placeholder="enter Student Mobile No " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('stdphone');
                                                               ?>
                                                      </span>
					    </div>
                                            <div class="box">
                                                <div  class="col-sm-6 nopadding">
                                               <?php
                                               $state=$district="";
                                                 if(strlen(trim($this->form->value('state')))!=0){
                                                     $state = $this->form->value('state');
                                                 }else{
                                                     $district=$student->district;
                                                     $state_q ="SELECT * FROM `districts` where id='".$student->district."'  ";
                                                     $state_q = $this->db->query($state_q);
                                                     if($state_q->num_rows()!=0){
                                                         $state_q =$state_q->row();
                                                         $state=$state_q->st_id;
                                                         $district=$student->district;
                                                     }
                                                 }
                                               ?>
                                            <div class="form-group1">
                                                        <label for="field-2" class="control-label">State<span style=" float: right ; color: red">*</span></label>
                                                        <select style="width: 100%" class="select2-me" name="state"  data-validate="required" id="class_id" 
                                                                    data-message-required="value_required"
                                                                            onchange="return get_district_list(this.value)">
                                                            <option value="">select state </option>
                                                           <?php 
                                                               $st = $this->db->query(" SELECT * FROM `state` ")->result();	

                                                                foreach($st as $row){
                                                                        ?>
                                                                    <option value="<?php echo $row->state_id ?>"
                                                               <?php if($state==$row->state_id){
                                                                            echo "selected";
                                                                        }?>  >
                                                                    <?php echo $row->name;?>
                                                                    </option>
                                                                     <?php
                                                                }
                                                              ?>
                                                        </select>
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('state');
                                                               ?>
                                                        </span> 
                                                    
                                            </div>
                                                </div>
                                                <div class="col-sm-6 nopadding">
                                                    
                                                    <div class="form-group1" style=' padding-left: 10px '>
                                                    <label for="field-2" class="control-label">District<span style=" float: right ; color: red">*</span></label>
                                                    <select name="district" style="width: 100%" class="select2-me"  id="district_selector_holder">
                                                        <?php
                                                        if( strlen($state)!=0){
                                                                     $data =$this->db->query("SELECT id,district FROM `districts` where st_id = '".$state."' ") ;
                                   $data = $data->result();
                        ?>
                        <option value="">SELECT DISTRICT</option> 
                        <?php
                            foreach ($data as $value) {
                                ?><option value="<?php echo  $value->id ?>" <?php if($district==$value->id){
                                                                            echo "selected";
                                                                        }?>  ><?php echo $value->district  ?></option><?php
                            }     
                                                       }else{
                                                           ?><option value="">select_State_first</option>
                                                        <?php
                                                       }
                                                        
                                                        ?>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('district');
                                                        ?>
                                                    </span> 
                                                </div>
                                                </div>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group1">
                                                <label for="field-1" class="control-label">Student E-mail</label>
                                                <input type="text" class="form-control" name="stdemail" value="<?php 
                                                if(strlen(trim($this->form->value('stdemail')))!=0){
                                                    echo $this->form->value('stdemail');
                                                }else{
                                                    echo $student->email;
                                                }  ?>" placeholder="enter Student e-mail" >
                                                    <span style=" color: red">
                                                        <?php echo $this->form->error('stdemail'); ?>
                                                  </span>

                                            </div>
                                            <div class="form-group1" >
						<label for="field-2" class="control-label">Locality<span style=" float: right ; color: red">*</span></label>
                        
							<input type="text" class="form-control" name="Locality" value="<?php 
                                                        if(strlen(trim($this->form->value('Locality')))!=0){
                                                    echo $this->form->value('Locality');
                                                }else{
                                                    echo $student->locality;
                                                } ?>" placeholder="enter Town or Mandal or Area name " >
						    <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('Locality');
                                                               ?>
                                                      </span>
					    </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group2">
                                                    <label for="field-2" class="control-label">Address<span style=" float: right ; color: red">*</span></label>
                                                    <textarea class="form-control" name="stdaddress" value="" placeholder="Enter Student Address" style=" width: 100%; height: 100px; resize: none" ><?php 
                                                    if(strlen(trim($this->form->value('stdaddress')))!=0){
                                                        echo $this->form->value('stdaddress');
                                                    }else{
                                                        echo $student->address;
                                                    } 
                                                         ?></textarea>
                                                        <span style=" color: red">
                                                                <?php
                                                                    echo $this->form->error('stdaddress');
                                                                   ?>
                                                          </span>
                                            </div>
                                            
                                            
				        </div>
                                        
                                    </div>
                                    
                                    <div class="box">
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Academic Details :</h4>
                                    <div class="box"  style=" height: auto ; padding-left: 15px;"  >
                                        <div class="col-sm-6 nopadding">
                                            <?php
                                               $medium=$class="";
                                                 if(strlen(trim($this->form->value('medium')))!=0){
                                                     $medium = $this->form->value('medium');
                                                 }else{
                                                     $class=$student->class_id."-".$student->section_id;
                                                     $medium_q ="SELECT * FROM `class` where id='".$student->class_id."'  ";
                                                     $medium_q = $this->db->query($medium_q);
                                                     if($medium_q->num_rows()!=0){
                                                         $medium_q =$medium_q->row();
                                                         $medium=$medium_q->medium;
                                                     }
                                                 }
                                             
                                               ?>
                                            <div class="form-group1">
                                                    <label for="textfield" class="control-label">Medium Of Study<span style=" float: right ; color: red">*</span></label>
                                                    <select  onchange="load_classes();" name="medium" id="medium" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                             <option value="" >Select Medium</option> 
                                                         <?php                                       
                                                         $medium_arr = unserialize (medium);
                                                        foreach ($medium_arr as $key=>$value) {
                                                            ?>
                                                             <option  value="<?php echo $key ?>" <?php
                                                           if($medium== $key){
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
                                            
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Admission No<span style=" float: right ; color: red">*</span></label>
                                                        <input type="text" name="userid" class="form-control" placeholder="Please Enter Admission No" value="<?php
                                                        if(strlen(trim($this->form->value('userid')))!=0){
                                                            echo $this->form->value('userid');
                                                        }else{
                                                           echo $student->admission_no;
                                                        } ?>" />
                                                        <span style=" color: red">
                                                            <?php
                                                                echo $this->form->error('userid');
                                                            ?>
                                                        </span> 
					    </div>
                                            
                                            
                                        </div>
                                     <div class="col-sm-6">
                                            <div class="form-group1">
                                                <label for="field-2" class="control-label">Class-section<span style=" float: right ; color: red">*</span></label>
                                                <select style="width: 100%" class="select2-me" id="stdclass" name="stdclass" >
                                                            <option value="" >Class - Section</option>
                                                             <?php
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.medium='".$medium."'  ORDER BY c.id ";
            $query = $this->db->query($query);
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->id."-".$val->sid ?>" <?php
                if($class==($val->id."-".$val->sid) ){
                    echo ' SELECTED="" ' ;
                }
                ?> >
                    <?php echo $val->cls_name." -".$val->sec_name ?>
                </option>
             <?php
            }
                                                             ?>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdclass');
                                                           ?>
                                                    </span>
					    </div>
                                         <div class="form-group1">
						<label for="field-2" class="control-label">Roll<span style=" float: right ; color: red">*</span></label>
                                                   <input type="text" class="form-control" name="stdroll" placeholder="Enter Student Roll No" value="<?php
                                                   if(strlen(trim($this->form->value('stdroll')))!=0){
                                                            echo $this->form->value('stdroll');
                                                        }else{
                                                           echo $student->roll;
                                                        } ?>" >
                                                        <span style=" color: red">
                                                            <?php echo $this->form->error('stdroll');  ?>
                                                        </span>
					</div>
                                        </div>   
                                    </div> 
                                           <script>
                                             function load_classes(){
                                                 medium =$('#medium').val();
                                                 $('#medium_err').html("");
                                                 if(medium.length==0){
                                                      $('#medium_err').html("** Please Select Medium Of Study");
                                                  }else{
                                                      //load_class_sec
                                                    setState('stdclass','<?php echo base_url() ?>index.php/students/load_class_sec','medium='+medium);
                                                  }
                                             }
                                           </script> 
                                        </div>
                                        <div class="col-sm-6 nopadding">
                                            <h4 style=" clear: both; margin: 0px; padding-top: 15px; width: 100%; color: #66cc00    ">Document Details :</h4>
                                    <div class="box"  style=" height: auto; padding-left: 15px;"  >
                                        <div class="box">
                                           <div class="form-group1">
                                                <label for="textfield" style=" padding-bottom: 5px;" class="control-label">Id Proof<span style=" float: right ; color: red">*</span></label><br/>
                                                <div class="box">
                                                    <?php
                                                      $id_proff ="";
                                                      if(strlen($this->form->value('id_proff'))!=0){
                                                          $id_proff = $this->form->value('id_proff');
                                                      }else{
                                                         $id_proff = $student->id_proof; 
                                                      }
                                                    ?>
                                                    <div  style=" width:50%; float: left;  margin-right: 10px">
                                                        <select class="select2-me" name="id_proff" style=" width:100%" >
                                                        <option value="" >Select Id Proof</option>
                                                        <option <?php
                                                           if($id_proff==1){
                                                               echo " selected='' ";
                                                           }
                                                           ?>  value="1" >Aadhar card</option>
                                                        <option <?php
                                                           if($id_proff==2){
                                                               echo " selected='' ";
                                                           }
                                                           ?> value="2" >Passport</option>
                                                    </select> 
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('id_proff');
                                                        ?>
                                                    </span>
                                                    </div>
                                                    <div style="width:46%; float: left">
                                                        <input type="text" class="form-control" maxlength="50" name="proffid" placeholder="Enter Proof id No" value="<?php 
                                                        if(strlen($this->form->value('proffid'))!=0){
                                                         echo $this->form->value('proffid');
                                                      }else{
                                                         echo $student->proofid; 
                                                      } ?>" >
                                                        <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('proffid');
                                                        ?>
                                                    </span>
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                        <?php 
                                         $stud_documents ="SELECT * from stud_documents WHERE std_id ='".$student->student_id."' " ;
                                         $stud_documents = $this->db->query($stud_documents);
                                         $tc=$bon=$caste_doc=$income=$tc_date=$tc_Serial_no="";
                                         if($stud_documents->num_rows()==0){
                                             $data = array(
                                                    'std_id' =>$student->student_id,
                                                    'iid' =>$this->session->userdata('staff_Org_id'),
                                                    'transfer' =>"0",
                                                    'bonafide' =>"0",
                                                    'caste' =>"0",
                                                    'income' =>"0",
                                                   'tc_issue_date' =>"0",
                                                   'tc_serial_no' =>"0"
                                                );
                                                $this->db->insert('stud_documents',$data);
                                                $tc=$bon=$caste_doc=$income=$tc_Serial_no="";
                                                $tc_date=time();
                                         }else{
                                             $stud_documents =$stud_documents->row();
                                         //    print_r($stud_documents);
                                             $tc=$stud_documents->transfer;
                                             $bon =$stud_documents->bonafide;
                                             $caste_doc =$stud_documents->caste;
                                             $income =$stud_documents->income;
                                             $tc_date =$stud_documents->tc_issue_date;
                                             $tc_Serial_no =$stud_documents->tc_serial_no;
                                         }
                                        ?>
                                        <div class="box" style=" clear: both">
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Transfer Certificate : </label>
                                                    <input type="checkbox" 
                                                           <?php
                                                           if(strlen($this->form->value("transfer_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }else{
                                                               if($tc!=0){
                                                                    echo 'checked="" ';
                                                               }
                                                           }
                                                           ?>
                                                            style="padding-bottom: 5px; margin-top: 10px; float: left;" onclick="$('#tc_details').toggle();" class="control-label" name="transfer_cert" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Bonafide Certificate : </label>
                                                    <input type="checkbox"
                                                           <?php
                                                           if(strlen($this->form->value("Bonafide_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }else{
                                                               if($bon!=0){
                                                                    echo 'checked="" ';
                                                               }
                                                           }
                                                           ?>
                                                           style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="Bonafide_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Income Certificate : </label>
                                                    <input type="checkbox" <?php
                                                           if(strlen($this->form->value("income_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }else{
                                                               if($income!=0){
                                                                    echo 'checked="" ';
                                                               }
                                                           }
                                                           ?> style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="income_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-3 nopadding">
                                                <div class="form-group1" style=" height: 35px;">
                                                    <label for="textfield" style=" padding-bottom: 5px;  width: 85%; float: left" class="control-label">Caste Certificate : </label>
                                                    <input type="checkbox" <?php
                                                           if(strlen($this->form->value("caste_cert"))!=0 ){
                                                            echo '   checked="" ';
                                                           }else{
                                                               if($caste_doc!=0){
                                                                    echo 'checked="" ';
                                                               }
                                                           }
                                                           ?> style="padding-bottom: 5px; margin-top: 10px; float: left;" class="control-label" name="caste_cert" />
                                                 </div>
                                            </div>
                                            <div class="col-sm-12 nopadding" id="tc_details" style="
                                                 <?php
                                                           if(strlen($this->form->value("transfer_cert"))!=0 ){
                                                          echo ' display: none; ';
                                                           }else{
                                                               if($tc==0){                                                                   
                                                                   echo ' display: none; ';
                                                               }
                                                           
                                                           }
                                                           ?>
                                                 " >
                                                <label style=" float: left; width: 20%">Transfer Certificate Details : </label>
                                                <div style=" float: left; width: 40%;">
                                                  <input type="text" name="tc_issue_date" placeholder="Tc Issue Date" 
                                                         value="<?php 
                                                         if(strlen($this->form->value("tc_issue_date"))!=0){
                                                          echo    $this->form->value("tc_issue_date");
                                                         }  else{
                                                            echo date("d/m/Y",$tc_date); 
                                                         }  
                                                         ?>" class="form-control datepick" />
                                                  <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('tc_issue_date');
                                                        ?>
                                                    </span>
                                                </div>
                                                <div style="margin-left: 5px; float: left; width: 38%;">
                                                
                                                <input type="text" name="tc_serial_no"  
                                                       value="<?php 
                                                         if(strlen($this->form->value("tc_serial_no"))!=0){
                                                          echo    $this->form->value("tc_serial_no");
                                                         }  else{
                                                            echo $tc_Serial_no; 
                                                         }  
                                                         ?>"
                                                       placeholder="Tc Serial No" class="form-control" />
                                                <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('tc_serial_no');
                                                        ?>
                                                    </span>
                                                </div>
                                           </div>
                                        </div>
                                          
                                    </div>
                                        </div>
                                    </div>
                        	
						<div  class="form-actions col-sm-offset-4 col-sm-4">
                                                    <input type="submit" name="submit" class="btn btn-primary btn-block" value="Edit Student Details" />
						</div>
				    
                                
                                </form>
                                
                                
                            </div>
                    </div>
                
        </div>
    </div>
</div>
    <script type="text/javascript">

    function get_class_sections(class_id) {
      if(class_id.length!=0){
         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
      }       
    }

</script>
<?php
$this->load->view('structure/footer');
?>
