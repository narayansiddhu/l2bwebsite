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
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/Admissions">Admissions</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/Admissions/Approved">Approved Admissions</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="">View Student Info</a>
                    </li>
                </ul>
            </div>
            <?php
           //  echo "<pre>";
             //print_r($adms_id);
             
             $student= $this->db->query("SELECT s.*,p.name as parent,p.parent_id as parentid,c.name as cls_name,se.name as sec_name FROM `student` s LEFT JOIN parent p ON s.parent_id=p.parent_id JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id=se.sid WHERE s.student_id='".$adms_id->remark."' AND s.iid='".$this->session->userdata('staff_Org_id')."'");
             $student =$student->row();
            // print_r($student);
            
            ?>
            <div class="box">
       <div class="col-sm-3 nopadding" style=" margin-top: 21px; padding-top: 10px; border:2px solid #318EEE">
           <div style=" text-align: center"><br/>
           <?php
              if(strlen($student->photo)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$student->photo)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $student->photo  ?>" alt="..." style=" width: 100px;; height: 100px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
                 }
              }
              ?>
           </div><hr/>
           <h3 style=" text-align: center; color:  #318EEE"><u><?php echo $student->name ?></u></h3>
           <table class=" table table-bordered nopadding" style=" width:100%">
               <tr>
                   <td><i class="fa fa-sitemap" aria-hidden="true"></i>&nbsp;<?php echo $student->cls_name ." - " .$student->sec_name ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;<?php echo $student->admission_no ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<?php echo date('d-m-Y',$student->birthday) ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $student->phone ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo $student->email ?></td>
               </tr>
               <tr>
                   <td style=" text-align: center"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Login Details</td>
               </tr>
               <tr>
                   <td><i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;<?php echo $student->userid ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-key" aria-hidden="true"></i>&nbsp;<?php echo $student->password ?></td>
               </tr>
               
           </table>
            
       </div>
       <div class="col-sm-9"  style=" padding-top: 0px;">
           <div class="box   box-bordered box-color " style=" margin: 0px">
                <div class="box-title">
                    <h3><i class="fa fa-user"></i> &nbsp;&nbsp;
                                <?php echo $student->name; ?> Details
                        </h3>
                    <div class="actions">
                        <a target="_blank" class="btn btn-primary" style=" color: #386ee0 ; background-color: white" href="<?php echo base_url(); ?>index.php/Students/view_details/<?php echo $student->userid ?>">View Profile</a>
               </div>
                </div>
               
               <div id="result_holder"  style=" max-height: 450px;" class="box-content nopadding">
                   <div  class='form-horizontal form-bordered'>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->name ?></span> 
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Father Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->father_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mother Name</label><br/>
                                            <span class="form-control" style=" font-weight:  bold">  <?php echo $student->mother_name ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Admission No</label>
                                            <span class="form-control" style=" font-weight:  bold"> <?php echo $student->userid ?></span>
                                    </div>
 
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Class-Section</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->cls_name ." - " .$student->sec_name ?></span>
                                    </div>
                                 </div>
                                <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Roll No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->roll ?></span>
                                    </div> 
                                 </div>
                                 <?php // print_r($student);
                                  $blood_group = unserialize(blood_groups); 
                                  $Caste_system=  unserialize(Caste_system);
                                 ?>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Birthday</label>
                                           <span class="form-control" style=" font-weight:  bold"><?php echo date('d-m-Y',$student->birthday) ?>
                                           </span> 
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Mobile No</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->phone ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Blood Group</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($blood_group[$student->bloodgroup])){
                                                echo $blood_group[$student->bloodgroup];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div> 
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Gender</label>
                                               <span class="form-control" style=" font-weight:  bold"><?php if($student->sex ==1){
                                                    echo "Male";
                                                }else{
                                                    echo "Female";
                                                    }    ?></span>
                                            
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Email</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->email ?></span>
                                    </div> 
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Caste</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php
                                            if(isset($Caste_system[$student->caste])){
                                                echo $Caste_system[$student->caste];
                                            }else{
                                                echo "--";
                                            }
                                             ?></span>
                                    </div>
                                 </div>
                                 <div class="col-sm-4">
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Address</label>
                                            <textarea  disabled="" style=' height: 90px '  class="form-control" style=" resize: none; font-weight:  bold"><?php echo $student->address ?></textarea>
                                     </div>
                                     <div class="form-group1">
                                            <label for="textfield" class="control-label">Locality</label>
                                            <span class="form-control" style=" font-weight:  bold"><?php echo $student->locality ?></span>
                                    </div>
                                 </div>
                                
                             </div>
                   <br style=" clear: both"/> <br style=" clear: both"/>
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
