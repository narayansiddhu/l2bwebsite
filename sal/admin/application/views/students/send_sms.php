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
                    <a href="<?php echo base_url(); ?>index.php/students/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/students/">Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/students/View/<?php echo $section->class_id ?>/<?php echo $section->section ?>"><?php echo $section->class." , ".$section->section ?></a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Send Alert</a>
                </li>
            </ul>
    </div>
   
   
    <?php
         if(strlen($this->session->userdata('Send_student_msg'))>0 ){
             ?><br/>
             <div id="successMsg" class="alert alert-success alert-dismissable">
                 <button type="button" class="close" data-dismiss="alert">×</button>
                  <?php echo $this->session->userdata('Send_student_msg'); ?>
                 </div>
                <script>
                         $("#successMsg").fadeIn();
                         $("#successMsg").delay(7000).fadeOut();
                    </script>
            <?php
             $this->session->unset_userdata('Send_student_msg');
         }
     ?>    
   
   
     
   <div class="box box-color box-bordered">
   <div class="box-title">
        <h3>Send SMS to <?php echo $section->class." , ".$section->section ?> </h3> 
        
   </div>
       
       
    <div class="box-content nopadding"  >
        
        <div class='form-horizontal form-bordered' id="preview_message_div"  >
            <div class="form-group">
                    <label for="field-1" class="control-label col-sm-2">Student Details</label>
                    <div class="col-sm-10" >
                        <button onclick="add_message('NAME')" >Name</button> &nbsp; <button onclick="add_message('MOBILE')" >Mobile</button> &nbsp; <button onclick="add_message('USERID')" >Userid</button> &nbsp;
                    </div>
            </div>
            
            <div class="form-group">
                    <label for="field-1" class="control-label col-sm-2">Message </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="4" style=" resize: none" id="message" name="message"  placeholder="Please enter Message Content" ><?php echo $this->form->value('stdemail') ?></textarea>
                        <span style=" color: red" id="message_error"></span>
                    </div>
            </div>
            
            <div  class="form-actions col-sm-offset-2 col-sm-10">
                <input type="button" onclick="preview_msg();"  class="btn btn-primary" value=" Preview Message content" />
            </div>
            
        </div>
        
        <div class='form-horizontal form-bordered' id="send_sms_dispaly" style=" height:350px; display: none"  >
            <div class="col-sm-12 nopadding" style=" height:280px; overflow-y: auto; ">
                <table class="table table-hover table-nomargin  table-bordered">
                    <thead >
                        <tr>
                            <th>Name</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody id="send_sms_prev">

                    </tbody>
                </table>
            </div>
            <div class="col-sm-12">
                <br/><br/>
                <div class="col-sm-6" style="text-align: right ">
                    <button class="btn btn-primary" onclick="back();" style=" float: right"> << BACK </button>
                </div>
                <div class="col-sm-6" style="text-align: left ">
                    <button id="send_btn" class="btn btn-primary" onclick="send_msg();" style=" float: left">  Send SMS  >> </button>
                </div>
            </div>
             
            
        </div>
       
    </div>
   </div>
   </div>
</div>
 </div>
<script>
  function add_message(val){
      textArea = $('#message').val();
      textArea = textArea+'#'+val+'#';
      $('#message').val(textArea);
   }    
  
  function preview_msg(){
      message=$('#message').val();
      $('#message_error').html("");
      message =message.trim()
      if(message.length ==0  ){
          $('#message_error').html("Please enter Message");
      }else{
         $("#preview_message_div").hide();
         $('#send_sms_dispaly').show();
         setState('send_sms_prev','<?php echo base_url() ?>index.php/Students/preview_sms','message='+message+'&section=<?php echo $section->sid ?>');  
      }
  }
  
  function back(){
         $("#preview_message_div").show();
         $('#send_sms_dispaly').hide();
  }
  
  function send_msg(){
        $('#send_btn').prop( "disabled", true ); 
        $('#send_btn').html(" Sending ... ");
        message=$('#message').val();
        message =message.trim();
        setState('send_sms_prev','<?php echo base_url() ?>index.php/Students/send_msg','message='+message+'&section=<?php echo $section->sid ?>');  

        
  }
  
  
</script>

<?php 
$this->load->view('structure/footer');
?>