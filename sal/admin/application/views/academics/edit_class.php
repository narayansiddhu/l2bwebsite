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
                    <a href="<?php echo base_url(); ?>index.php/academics/class_list">Manage Class</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Edit Class</a>
                </li>
            </ul>
            
    </div>
    <?php
if(strlen($this->session->userdata('class_edit_Sucess'))>0 ){
    ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('class_edit_Sucess'); ?>
        </div>
    <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
   <?php
    $this->session->unset_userdata('class_edit_Sucess');
}
?>    
 
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3><i class="fa fa-sitemap"></i>Edit Class</h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/academics/edit_Cls" method="post" >
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Class Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="clsname" placeholder="Enter Class Name" class="form-control" value="<?php 
                                                        if(strlen($this->form->value('clsname'))==0){
                                                            echo $class_data->name;
                                                        } else{
                                                          echo $this->form->value('clsname'); 
                                                        }
                                                    ?>" > 
                                                    <span style=" color: red">
                                                            <?php
                                                               echo $this->form->error('clsname');   
                                                            ?>
                                                        </span>        
                                                </div>
                                        </div>  
                                    </div>
                                    <div class="col-sm-6 nopadding">
                                        <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Medium Of Study</label>
                                                <div class="col-sm-10">
                                                    <select name="medium" id="medium" class='select2-me' style="width:100%;" data-placeholder="Please select something">
                                                            <?php                                       
                                                                 $medium = unserialize (medium);$i=1;
                                                                 if( strlen( $this->form->value('medium'))>0){
                                                                       $med=$this->form->value('medium');
                                                                 }else{
                                                                    $med= $class_data->medium;
                                                                 }
                                                                     
                                                                 foreach ($medium as $key=>$value) {
                                                                     ?>
                                                                      <option  value="<?php echo $key ?>" <?php
                                                                    if($med== $key){
                                                                        echo "selected";
                                                                    }
                                                                   ?>   ><?php echo $value ?></option>
                                                                    <?php
                                                                    $i++;
                                                                 }

                                                               ?>
                                                    </select>
                                                    
                                                    <span style=" color: red">
                                                            <?php
                                                               echo $this->form->error('medium');   
                                                            ?>
                                                        </span>        
                                                </div>
                                        </div>  
                                    </div>
                                      
                                    <div  class="form-actions  col-sm-10" style=" border-top:  1px solid #cccccc">
                                      <input type="submit"  name="submit" value="Edit Class" class="btn btn-primary col-sm-offset-2" />
                                      <input type="hidden" name="classid" value="<?php echo $class_data->id ?>" />
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
