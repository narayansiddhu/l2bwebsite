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
                <a href="<?php echo base_url(); ?>">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/academics/class_list">Manage Class</a>
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $section_data->sid  ?>">View Section Details</a>
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $section_data->sid  ?>">Edit Section</a>
            </li>
        </ul>
    </div>
    
    
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Edit Section</h3> 
                </div>
                <div class="box-content nopadding ">    
                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/academics/editsection" method="post" enctype="multipart/form-data"  >

                       <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Section Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Sectionname" placeholder="Enter Section Name" class="form-control" value="<?php
                                       if(strlen($this->form->value('Sectionname'))==0){
                                           echo $section_data->section;
                                       } else{
                                         echo $this->form->value('Sectionname');  
                                       }
                                            
                                    ?>" > 
                                    <span style=" color: red">
                                            <?php
                                               echo $this->form->error('Sectionname');   
                                            ?>
                                        </span>        
                                </div>
                        </div> 
                        <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Incharge</label>
                                <div class="col-sm-10">
                                    <select name="class_teacher"  class='select2-me' style="width:100%;" onclick="cls_select();" onselect="cls_select();" >
                                        <option value="">Select A Class Incharge </option>
                                         <?php
                                         $staffid="";
                                         if(strlen($this->form->value('class_teacher'))==0){
                                           $staffid= $section_data->staff_id;
                                       } else{
                                         $staffid= $this->form->value('class_teacher');  
                                       }
                                         
                                         $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                         $query1 = $this->db->get_where('staff', $credential);
                                         $query1=$query1->result();
                                         foreach($query1 as $val){
                                            ?>
                                            <option value="<?php echo $val->id ?>" <?php
                                       if($staffid== $val->id){
                                           echo "selected";
                                       }

                                      ?>  ><?php echo $val->name ?></option>
                                          <?php
                                         }

                                         ?>
                                    </select> 
                                    <span> <?php
                                               echo $this->form->error('class_teacher');   
                                            ?></span>
                                </div>
                        </div>


                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                                <input type="submit"  name="submit" value="edit Section" class="btn btn-primary" />
                                <input type="hidden" name="sectionid" value="<?php echo $section_data->sid ?>" />
                                <input type="hidden" name="class_name" value="<?php echo $section_data->class_id ?>" />
                                
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
