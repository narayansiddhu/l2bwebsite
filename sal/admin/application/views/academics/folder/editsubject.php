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
                    <a href="<?php echo base_url(); ?>index.php/academics/edit_subject/">Edit Subject</a>
                </li>
            </ul>

    </div>

    <?php
        if(strlen($this->session->userdata('subject_edit_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('subject_edit_Sucess'); ?>
                </div>
            <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
           <?php
            $this->session->unset_userdata('subject_edit_Sucess');
        }
    ?>

    <div class="col-sm-12">
        <div class="box">
            <div class="box-content nopadding">
               <div class="row">

                    <div class="col-sm-12">
                        <div class="box box-bordered box-color">
                                <div class="box-title">
                                        <h3>
                                                <i class="fa fa-th-list"></i>Create Subject</h3> 
                                </div>
                                <div class="box-content nopadding">                                
                                    <div class='form-horizontal form-bordered' >
                                     <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Subject Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" id="subname" name="subname" placeholder="Enter Subject Name" class="form-control" value="<?php echo $subject->subject ?>" > 
                                                    <span id="subname_err" style=" color: red">

                                                    </span> 
                                                    <input type="hidden" id="subid"   value="<?php echo $subject->sid ?>" > 
                                                    
                                                </div>
                                        </div> 
                                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="button" onclick="createsub();"  name="submit" value="Edit Subject" class="btn btn-primary" />
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
<script>
  function createsub(){
      var sub=$('#subname').val();
      var subid=$('#subid').val();
      if(sub.length==0){
        $('#subname_err').text('** please enter Subject name');
      }else{
          setState('subname_err','<?php echo base_url() ?>index.php/academics/edit_sub','subject='+sub+'&subid='+subid);
      }
  }  
</script>             

</div>   
<?php
$this->load->view('structure/footer');
?>