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
                        <a href="<?php echo base_url(); ?>index.php/Parents/">Manage Parents</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">View Details Of <?php echo $parent->name   ?></a>
                    </li>
                </ul> 
            </div>
             
             <?php
                if(strlen($this->session->userdata('parent_Link_Sucess'))>0 ){
                    ?><br/>
                        <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php echo $this->session->userdata('parent_Link_Sucess'); ?>
                        </div>
                    <script>
                         $("#successMsg").fadeIn();
                         $("#successMsg").delay(2000).fadeOut();
                    </script>
                    <?php
                     $this->session->unset_userdata('parent_Link_Sucess');
                 }
            ?> 
                    
            <div class="row">
                    
                    <div class="col-sm-4">
                        <div class="box box-bordered box-color" >
                            <div class="box-title">
                                <h3 ><i class="fa fa-male"></i>&nbsp;Parent Details</h3>
                            </div>                        
                            <div class="box-content nopadding">
                                <table style=" " class="table table-hover table-nomargin table-bordered">
                                    <tr>
                                        <td><i class="fa fa-user"></i>&nbsp;<?php echo $parent->name  ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-envelope"></i>&nbsp;<?php echo $parent->email  ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-key" aria-hidden="true"></i>&nbsp;<?php echo $parent->password  ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $parent->phone  ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-home" aria-hidden="true"></i>&nbsp;<?php echo $parent->address  ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-suitcase" aria-hidden="true"></i>&nbsp;<?php echo $parent->profession  ?></td>
                                    </tr>
                                </table>              
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-8">
                        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-child"></i>Student Accounts Linked</h3>
                        <div class="actions">
                            <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url()  ?>index.php/Parents/link_students/<?php echo $parent->parent_id ?>"><i class="fa fa-link"></i>&nbsp;Link Students</a>  
                        </div>
                </div>                        
                <div class="box-content " style=" text-align: center; max-height: 450px; overflow-y: scroll ">
                       <?php 
                         $stud="SELECT st.*, s.name as sec_name , c.name as cls_name FROM `student` st JOIN `section` s ON st.section_id=s.sid JOIN `class` c ON s.class_id = c.id  WHERE st.parent_id   ='".$parent->parent_id."'  AND st.iid ='".$this->session->userdata('staff_Org_id')."' ";
                         $stud = $this->db->query($stud);
                         if($stud->num_rows()>0){
                             $stud = $stud->result();
                             ?><br/>
                             <div class="row " style="  margin-left: 5px; ">
                        <?php
                           foreach($stud  as $value ){
                               ?> 
                        <div  style='border-radius: 15px;  float: left; width: 32%; height: 150px;border:  1px solid #666666; margin: 4px'>
                            <img src="<?php echo assets_path ?>/uploads/<?php 
                                if(strlen($value->photo)==0){
                                echo 'dummy_user.png';      
                                }else{
                                    echo $value->photo;
                                }
                             
                            ?>" class="img-rounded" alt="Cinque Terre" width="50" height="50"><br/>
                            <?php echo $value->name  ?><br/>
                            <?php echo $value->userid  ?><br/>
                            <?php echo $value->phone  ?><br/>
                            <?php echo $value->email  ?><br/>
                            <?php echo $value->cls_name." - ".$value->sec_name  ?>
                        </div>
                              <?php
                           }
                        ?>
                    </div>
                             <?php
                         }else{
                             ?><br/>
                    <span style=" margin-top: 20px; color: red"> ** No students Accounts Linked To this Parent Account</span>
                             <?php
                         }
                        ?>        
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