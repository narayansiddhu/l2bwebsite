<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <?php
    $exams=$this->db->query("SELECT * FROM `examinations` where iid='".$this->session->userdata('staff_id')."' AND status=1 ");
    $exams = $exams->result();
    ?>
    
    <?php
    if(strlen($this->session->userdata('Alerts_send_sucess'))>0 ){
        ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            
             <?php echo $this->session->userdata('Alerts_send_sucess'); ?>
            </div>
        <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(2000).fadeOut();
           </script>
       <?php
        $this->session->unset_userdata('Alerts_send_sucess');
    }
    ?>
           
        
           <div class="row nopadding">
               
               <div class="col-sm-6">
                   <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Send SMS</h3> 
                        </div>
                        <div class="box-content nopadding ">  
                            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/result_msg" method="post" enctype="multipart/form-data"  >
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Exams</label>
                                    <div class="col-sm-10">
                                        <select onchange="load_sections();" class="select2-me" name="exam_list" id="exam_list" style=" width: 100%">
                                            <option value="">Select Exam</option>
                                            <?php
                                          foreach ($exams as $value) {
                                              ?><option value="<?php echo $value->id ?>" ><?php echo $value->exam;  ?></option><?php 
                                          }                                            
                                        ?>
                                        </select>
                                        <span id="exam_err" style=" color: red">
                                            <?php echo $this->form->error('ids');    ?>
                                        </span>  

                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Sections</label>
                                    <div class="col-sm-10">
                                        <div class="form-control" id="section_list" style="width: 100%; min-height: 80px; overflow-y: scroll">
                                            
                                        </div>
                                          <span id="" style=" color: red">
                                            <?php echo $this->form->error('message');    ?>
                                        </span>  
                                    </div>
                                </div>
                                
<!--                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">customize</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="customize" value="2"
                                               <?php  if(strlen($this->form->value('customize'))!=0){
                                                   echo "checked";
                                               } ?>
                                               onclick="$('#language').toggle();$('#add').val('Preview');$('#message_div').toggle();" style=" width: 10%; float: left"   />&nbsp;
                                        <div id="language" class="col-sm-12" style="display:none;" >
                                            
                                            <a href="#" onclick="add_message('name');" class="btn btn-mini">Name</a>
                                            <a href="#" onclick="add_message('subject');" class="btn btn-mini">Subject</a>
                                            <a href="#" onclick="add_message('securedmarks');" class="btn btn-mini">Secured Marks</a>
                                            <a href="#" onclick="add_message('totalmarks');" class="btn btn-mini">Total dmarks</a>
                                        </div>
                                        
                                    </div>
                                </div>-->
                                <input type="hidden" id="ids_list" name="ids"  />
                                <div class="form-group" id="message_div" style=" display: none">
                                    <label for="textfield" class="control-label col-sm-2">Message</label>
                                    <div class="col-sm-10">
                                        <textarea name="message" maxlength="1500" id="message_text" rows="4" style="resize:none;" class="form-control"><?php echo $this->form->value('message') ?></textarea>
                                          <span id="" style=" color: red">
                                            <?php echo $this->form->error('message');    ?>
                                        </span>  
                                    </div>
                                </div>
                                
                                <div class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="submit" id="add"   name="add" value="Send Results" class="btn btn-primary" />
                                    
                                    <span id="errors" style=" color: red">
                                                   <?php
                                                       echo $this->form->error('stdob');
                                                   ?>
                                    </span> <br/>
                                    
                                </div>
                            
                            </form>
                        </div>
                       
                   </div>
                   
                   
               </div>
               
               <div class="col-sm-6">
                   <div class="box box-bordered box-color nopadding">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Send SMS</h3> 
                        </div>
                        <div class="box-content nopadding ">  
                            <div class="form-horizontal form-bordered">
                                <div class="form-group">
                                    <h3 for="textfield" sty class="control-label col-sm-12">Sample Message content</h3>
                                    <div class="col-sm-12">
                                        Dear student13 ,<br/> Your results For Exam :unit 1 is as follows : <br/>
                                        english : - 89 <br/>
                                        telugu : - 95 <br/>
                                        hindi : - 72  <br/>
                                        science : -  98<br/>
                                        computer : - 100 <br/>
                                        Grand Total : 454/ 500
                                    </div>
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
   
   function load_sections(){
       exam =$('#exam_list').val();
       $('#exam_err').html("");
       $('#section_list').html("");
       if(exam.length ==0){
           $('#exam_err').html("Please select exam");
       }else{
           setState('section_list','<?php echo base_url() ?>index.php/Alerts/load_sections','exam='+exam);             
       }
   }
   
   function add_message(key){
       message_text = $('#message_text').val();
       message_text = message_text+'#'+key+'#';
       $('#message_text').val(message_text);        
   }

   function fetch_contacts(id){
     if($('#'+id).prop('checked')){
          //sms_grp_id
         ids = $('#sms_grp_id').val();   
         ids = ids+","+id;
         $('#sms_grp_id').val(ids);
         setState('mobile','<?php echo base_url() ?>index.php/Alerts/load_contacts','id='+id+'&mobile='+$('#mobile').html());            
     }else{
         ids = $('#sms_grp_id').val();   
        ids= ids.replace( id, " ") ;
        
        $('#sms_grp_id').val(ids);
        setState('mobile','<?php echo base_url() ?>index.php/Alerts/remove_contacts','id='+id+'&mobile='+$('#mobile').html());            
        
     }
   }    
</script>


<?php
$this->load->view('structure/footer');
?>
