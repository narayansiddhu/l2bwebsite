<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">  
    <?php
     $this->load->view('alerts/navbar');
    ?>
        
     <?php
        if(strlen($this->session->userdata('Alerts_send_sucess'))>0 ){
            ?><br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Alerts_send_sucess'); ?>
                </div>
           <?php
            $this->session->unset_userdata('Alerts_send_sucess');
        }
    ?>
    <div class="col-sm-12 nopadding">
        <div class="box nopadding">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3><i class="fa fa-envelope-o" aria-hidden="true"></i>Promotional Activity</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <div  action="<?php echo base_url(); ?>index.php/Alerts/send_alerts" method="post" class='form-horizontal form-bordered' >
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Mobile No</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id='mobile'  rows="4"  style=" resize: none" ></textarea>
                            <span id="message_error" style=" color: red">
                                      
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id='message'  rows="4"  style=" resize: none" ></textarea>
                            <span id="message_error" style=" color: red">
                                      
                            </span>  
                            
                        </div>
                    </div> 
                    <div class="form-actions col-sm-offset-2 col-sm-10">
                        <input type="button" id="add" onclick="send_alert();"  name="add" value="Send SMS Alert" class="btn btn-primary" />
                        <span id="errors" style=" color: red">
                                       <?php
                                           echo $this->form->error('stdob');
                                       ?>
                        </span> <br/>
                        <span style=" color: red">Note : Please don't refresh the page in middle of process..</span>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>       
    <script>
     function send_alert(){
        $('#to_error').html("");
        $('#message_error').html("");
        var count=0;
        var str = $('#message').val();
        str=str.trim();
        var ids="";
         for(i=1;i<=3;i++){
           id="to_"+i; 
           if($("#"+id).prop('checked') == true){
               count++;
               ids+=i+",";
           }
         }
         
         if(count!=0){
           if(str.length==0){
                $('#message_error').html("Please Enter Alert Message");
            }else{
                $( "#add" ).val("Please Wait Sending  Alerts ...."); 
                $( "#add" ).prop( "disabled", true ); 
                setState('errors','<?php echo base_url() ?>index.php/Alerts/send_alerts','ids='+ids+'&message='+str);
            }
         }else{
             $('#to_error').html("Please select Any One Category");
         }
        
         
     }
    </script>
        
</div>
<?php
$this->load->view('structure/footer');
?>
