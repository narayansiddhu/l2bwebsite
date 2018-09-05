<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>


<div class="row">
    <?php $this->load->view('academics/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Cls_structure">Academics</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Create_section">Incharge</a>
                </li>
            </ul>
    </div>
    
    
    <?php
        if(strlen($this->session->userdata('staff_add_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('staff_add_Sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('staff_add_Sucess');
        }


     ?>
   
    <div class="col-sm-12">
         <div class="box box-bordered box-color">
            <div class="box-title">
                    <h3><i class="fa fa-th-list"></i>In-charge</h3>
            </div>
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered'>
                    <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Class/Section</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="section" value="<?php echo $section->class."-".$section->section ?>"  class="form-control"  disabled=""/>
                                        <input type="hidden" id="secid" value="<?php echo $section->sid ?>" />
                                    </div>
                            </div>  
                        
                        <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Teacher</label>
                        <div class="col-sm-10">
                            <select  id="staff"   class='select2-me' style="width:100%;" >
                                <option value="">Select A Teacher</option>
                                 <?php
                                 $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                 $query1 = $this->db->get_where('staff', $credential);
                                 $query1=$query1->result();
                                 foreach($query1 as $val){
                                    ?>
                                    <option value="<?php echo $val->id ?>" <?php
                                   if($section->staff_id == $val->id){
                                       echo "selected";
                                   }
                                  ?>  ><?php echo $val->name ?></option>
                                  <?php
                                 }

                                 ?>
                            </select>  
                             <span id='staff_err'  style=" color: red">
                                   <?php  echo $this->form->error('sub_teacher'); ?>
                            </span>
                        </div>
                        </div>
                        
                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="button"  name="submit" value="Update Incharge" onclick="create_incharge();" class="btn btn-primary" />
                            <span id='errors'></span>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  function create_incharge(){
      secid=$('#secid').val();
      staff=$('#staff').val();
      if(staff.length==0){
        alert("Please select Incharge");  
      }else{
          setState('errors','<?php echo base_url() ?>index.php/academics/add_incharge','section='+secid+'&staff='+staff);
      }
  }
</script>

<?php
$this->load->view('structure/footer');
?>