<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            
            <div align="center" class="box ">
                <div class="box-content " align="center" style=" text-align: center; ">
                       <?php 
                        $stud="SELECT st.*, s.name as sec_name , c.name as cls_name FROM `student` st JOIN `section` s ON st.section_id=s.sid JOIN `class` c ON s.class_id = c.id  WHERE st.parent_id   ='".$this->session->userdata('parent_id')."'  AND st.iid ='".$this->session->userdata('parent_org_id')."' ";
                         $stud = $this->db->query($stud);
                         if($stud->num_rows()>0){
                             $stud = $stud->result();
                             ?><br/>
                             <div class="row " style="  margin-left: 5px;">
                                <?php
                                   foreach($stud  as $value ){
                                       ?> 
                                <div align="center"  style='border-radius: 8px;  float : left ; width: 32%; height: 160px;border:  1px solid #666666; margin: 4px; padding-top: 5px' >
                                    <a href="<?php echo base_url() ?>index.php/students/view_student/<?php echo $value->userid ?>" style=" text-decoration: none; ">
                                            <img src="<?php echo assets_path ?>/uploads/<?php 
                                        if(strlen($value->photo)==0){
                                        echo 'dummy_user.png';      
                                        }else{
                                            echo $value->photo;
                                        }

                                    ?>" class="img-rounded" alt="Cinque Terre" width="50" height="50">
                                        </a>
                                        <br/>
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
                    <span style=" margin-top: 20px; color: red"> ** No students Accounts Linked To this Parent Account, Please contact Admin</span>
                             <?php
                         }
                        ?>        
                </div>
            </div>
            
        </div>
    </div>
</div>


<?php
$this->load->view('structure/footer');
?>