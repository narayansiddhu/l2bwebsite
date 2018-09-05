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
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Attendance Reports</a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                    </ul>
                </div> 
              
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-bordered box-color nopadding">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Send Attendance Reports</h3> 
                </div>
                <div class="box-content nopadding ">  
                        <div class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/Alerts/result_msg" method="post" enctype="multipart/form-data"  >
                               <div class="form-group">
                                   <label for="textfield" class="control-label col-sm-2">Date</label>
                                   <div class="col-sm-10">
                                       <input type="text" name="date_Cal" id="date_Cal"  value="<?php echo date("d/m/Y",time()); ?>"   class="form-control datepick" />
                                       <span id="date_Cal_err" style=" color: red">
                                           <?php echo $this->form->error('ids');    ?>
                                       </span>  

                                   </div>
                               </div>
                                <div class="form-group">
                                   <label for="textfield" class="control-label col-sm-2">Class</label>
                                   <div class="col-sm-10">
                                       <div class="well" style=" height: 150px; overflow-y: scroll;" >
                                           <?php
                                            $q=$this->db->query("SELECT s.sid,s.name as secname,c.name as clsname FROM `section` s JOIN class c ON s.class_id= c.id WHERE s.iid='".$this->session->userdata('staff_id')."' ORDER BY c.id ");
                                            $q = $q->result();$id="";
                                            foreach($q as $value){
                                                $id.=$value->sid.",";
                                               ?>
                                                 <input type="checkbox" name="section_<?php echo $value->sid ?>"  id="section_<?php echo $value->sid ?>" /><?php echo $value->clsname ." - ".$value->secname ?> 
                                                 <br/>
                                                <?php 
                                            }
                                            $id = substr($id, 0, strlen($id)-1);
                                            ?>
                                       </div>
                                       <input type="hidden" name="ids" id="ids" value="<?php echo $id ?>" />
                                       <span id="section_err" style=" color: red">
                                           <?php echo $this->form->error('ids');    ?>
                                       </span>  

                                   </div>
                                </div>
                                <div class="form-actions col-sm-offset-2 col-sm-10">
                                    <input type="button" id="add"  onclick="send_attendance_result();"  name="add" value="Send Attendance Alert" class="btn btn-primary" />
                                    
                                    <span id="errors" style=" color: red">
                                                   <?php
                                                       echo $this->form->error('stdob');
                                                   ?>
                                    </span> <br/>
                                    
                                </div>
                        </div>
                </div>

            </div>
        </div>
    </div>
    
    <script>
       function send_attendance_result(){
           date_Cal = $('#date_Cal').val();
           ids = $('#ids').val();
            $('#date_Cal_err').html("");
           if(date_cal.length==0){
               $('#date_Cal_err').html("Please select date");
           }
           //send_absent_msg         
       }    
    </script>
                   
                   
               
          
        </div>
    </div>
</div>
           


<?php
$this->load->view('structure/footer');
?>
