<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <br/>
    
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/class_list">Manage Class</a>
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/class_list">Add Section</a>
                </li>
            </ul>
        </div>
    <?php
    if(strlen($this->session->userdata('Section_add_Sucess'))>0 ){
        ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success!</strong>
             <?php echo $this->session->userdata('Section_add_Sucess'); ?>
            </div>
        <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
       <?php
        $this->session->unset_userdata('Section_add_Sucess');
    }
    ?>
    
        <div class="box-content nopadding">
           <div class="row">
               
               <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Create Section's</h3> 
                            </div>
                            <div class="box-content nopadding ">    
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/academics/Add_section" method="post" enctype="multipart/form-data"  >
                                <input type="hidden" name="class_name" value="<?php echo $class_data->id  ?>" />
                                   <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Section Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="Sectionname" placeholder="Enter Section Name" class="form-control" value="<?php echo $this->form->value('Sectionname') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('Sectionname');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div> 
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Class Teacher</label>
                                            <div class="col-sm-10">
                                                <select name="class_teacher"  class='select2-me' style="width:100%;" onclick="cls_select();" onselect="cls_select();" >
                                                    <option value="">Select A Class Teacher</option>
                                                     <?php
                                                     $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                                     $query1 = $this->db->get_where('staff', $credential);
                                                     $query1=$query1->result();
                                                     foreach($query1 as $val){
                                                        ?>
                                                        <option value="<?php echo $val->id ?>" <?php
                                                   if($this->form->value('class_teacher')== $val->id){
                                                       echo "selected";
                                                   }
                                                
                                                  ?>  ><?php echo $val->name ?></option>
                                                      <?php
                                                     }
                                                       
                                                     ?>
                                                </select>  
                                            </div>
                                    </div>
                                    
                                        
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit"  name="submit" value="Create Section" class="btn btn-primary" />
                                    </div>
                                </form>
                            </div>
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
