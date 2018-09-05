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
                    <a href="<?php echo base_url(); ?>index.php/students">Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Search Students</a>
                </li>
            </ul>
        </div>
    
            <div class="box box-bordered box-color nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Search Students</h3> 
                    </div>
                    <div class="box-content nopadding">  
                        <form class='form-horizontal form-bordered' method="get" >
                            <div class="col-sm-12 nopadding" style=" border-bottom:   1px solid #cccccc">
                                <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">user Id</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="id" data-validate="required" data-message-required="value_required"  placeholder=" Enter User Id" >
                                                <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('id');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>
                                <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">Admission No</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="admission_no" data-validate="required" data-message-required="value_required"  placeholder=" Enter Admission No" >
                                                <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('admission_no');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>
                                <div class="col-sm-4 nopadding">
                                      <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">Text</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="text" data-validate="required" data-message-required="value_required"  placeholder=" Enter Text To Search" >
                                                <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('stdname');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>
                            </div>
                            <div    class="form-actions col-sm-offset-2 col-sm-10">
                                <button class="btn  btn-primary " type="submit"  > Search student</button>
                                <span style=" color: red"><?php
                                  if(strlen($error)>0){
                                      echo "<br/>";
                                  }
                                echo $error ?></span>
                            </div>
                        </form>



                    </div>
            </div>
        
    <?php
      
    
        if(!$result){
            
        }else{
         ?>
            
                    <div class="box box-bordered box-color nopadding">
                            <div class="box-title">
                                    <h3><i class="fa fa-th-list"></i>Search Results</h3> 
                            </div>
                            
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin dataTable table-bordered">
                                    <thead>
                                            <tr>
                                                   
                                                    <th>Student name</th>
                                                    <th >Userid</th>
                                                    <th>Gender</th>
                                                    <th >mobile</th>
                                                    <th >Admission No</th>
                                                   <?php
                                                   if($this->session->userdata('staff_level')>3){
                                                       ?>
                                                       <th>Actions</th>
                                                     <?php
                                                   }
                                                   ?>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=1;
                                           foreach ($result as $value) {
                                              
                                             ?>
                                            <tr>
                                               
                                                <td>
                                                    <a href="<?php echo base_url() ?>index.php/Students/view_details/<?php  echo $value->userid   ?>"><?php echo $value->name ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $value->userid ?></td>
                                                <td><?php if($value->sex==1)
                                                            {
                                                                echo "Male";
                                                            }else{
                                                                echo "Female";
                                                            }
                                                            ?></td>
                                                <td><?php echo $value->phone ?></td>
                                                <td><?php echo $value->admission_no ?></td>
                                                <?php
                                                   if($this->session->userdata('staff_level')>3){
                                                       ?>
                                                         <td>
                                                    <a href="<?php echo base_url() ?>index.php/Students/view_details/<?php  echo $value->userid   ?>"><i class="fa fa-eye" aria-hidden="true"></i>
        </a>
                                                    <a href="<?php echo base_url() ?>index.php/Students/edit/<?php  echo $value->student_id   ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
                                                </td>
                                                        <?php
                                                      }
                                                      ?>


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
