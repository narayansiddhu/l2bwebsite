<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium= unserialize(medium);
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
                            <a href="<?php echo base_url(); ?>index.php/Hostel/">Hostel</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Hostel/">Manage Students</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Admit Students</a>
                        </li>
                    </ul>
            </div>
            <?php
            if(strlen($this->session->userdata('vechile_add_Sucess'))>0 ){
                ?>       <br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('vechile_add_Sucess'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('vechile_add_Sucess');
            }
            ?>
                
            
                        
<script>
    function load_sections(){
         //class    
         $('#cls_err').html(' ');//cat_err
         $('#section').prop('disabled', true);
        cls = $('#class').val();  //category       
        if(cls.length==0){
           $('#cls_err').html(' ** please select class');//cat_err
         //cls_err
        }else{
           setState('section','<?php echo base_url() ?>index.php/accounts/load_sections','cls='+cls);
           $('#section').prop('disabled', false); 
        }

    }
    
    function load_students(){
        $('#section_err').html(' ');//cat_err
        $('#student').prop('disabled', true);
        section = $('#section').val();  //category    
       
        if(section.length==0){
           $('#section_err').html(' ** please select Section');//cat_err
         //cls_err
        }else{
           setState('student','<?php echo base_url() ?>index.php/accounts/load_students','section='+section);
           $('#student').prop('disabled', false); 
        }     
    }
    
    function load_rooms(){
      $('#block_err').html(' ');//cat_err
         $('#room').prop('disabled', true);
        block = $('#block').val();  //category    
        if(block.length==0){
           $('#block_err').html(' ** please select Route');//cat_err
         //cls_err
        }else{
            setState('room','<?php echo base_url() ?>index.php/Hostel/load_rooms','block='+block);
            setState('Fee_structure','<?php echo base_url() ?>index.php/Hostel/load_fee_structure','block='+block);
            $('#room').prop('disabled', false); 
         }
    }
    
    function Admit_Student(){
       student = $('#student').val(); 
       room =$('#room').val();
       amount =$('#amount').val();
       $("#student_err").html("");
       $("#room_err").html("");
       $("#amount_err").html("");
       count=0;
       if(student.length==0 ){
           count++;
            $("#student_err").html("** Please select Student");
       }
       if(room.length==0 ){
            count++;
            $("#room_err").html("** Please select Student");
       }else{
           room=room.split(",");
            if(room[1]==0){
                 count++;
                $("#room_err").html("** Room Already Filled");
            }
       }
       if(amount.length==0){
        count++;
            $("#amount_err").html("** Please enter Amount");
       }
       if(count==0){
            student=student.split(",");
        setState('errors','<?php echo base_url() ?>index.php/Hostel/add_student','student='+student[0]+'&room='+room[0]+'&amount='+amount);
            
        }
       
      // pickups= pickups.split(",");
     //    student=student.split(",");
       
       
    }
   
</script>

                           <div class="box">
                                <div class="col-sm-9">
                                    
                                    <div class="box ">
                                    <div class="box-title">
                                            <h3><i class="fa fa-road"></i>Admit Student</h3> 
                                    </div>
                                        <div id='admit_form'  class="box-content nopadding">  
                                            <div class='form-horizontal form-bordered'   >

                                                        <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">

                                                             <div class="col-sm-4 nopadding" >
                                                                 <div class="form-group1">
                                                                         <label for="textfield" class="control-label">Class</label>
                                                                             <select onchange="load_sections();"  name="class" id="class" class='select2-me' style="width:100%;" >
                                                                                 <option value="">Select A Class</option>
                                                                                  <?php
                                                                                  //$credential = array('iid' =>$this->session->userdata('staff_Org_id'));
                                                                                  $query = " select * from class where iid='".$this->session->userdata('staff_Org_id')."' ";
                                                                                  $query = $this->db->query($query);
                                                                                    $query=$query->result();
                                                                                    foreach($query as $val){
                                                                                        ?>
                                                                                  <option value="<?php echo $val->id ?>" >
                                                                                      <?php echo $val->name ?>
                                                                                  </option>
                                                                                     <?php
                                                                                    }

                                                                                  ?>
                                                                             </select>  
                                                                             <span id='cls_err' style=" color: red">

                                                                             </span>  
                                                             </div>
                                                         </div>   
                                                            <div class="col-sm-4 " >

                                                                 <div class="form-group1">
                                                                 <label for="textfield" class="control-label">Section</label>
                                                                    <select onchange="load_students();"  name="section" id="section" class='select2-me' style="width:100%;" >
                                                                         <option value="">Select A Section</option>
                                                                     </select>  
                                                                     <span id='section_err' style=" color: red">

                                                                     </span> 
                                                         </div>
                                                            </div>
                                                            <div class="col-sm-4 " >

                                                                 <div class="form-group1">
                                                                 <label for="textfield" class="control-label">Student</label>
                                                                    <select   name="student" id="student" class='select2-me' style="width:100%;" >
                                                                         <option value="">Select A Student</option>
                                                                     </select>  
                                                                     <span id='student_err' style=" color: red">

                                                                     </span>
                                                             </div>

                                                             </div>


                                                        </div>

                                                        <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">

                                                             <div class="col-sm-4 nopadding" >
                                                                 <div class="form-group1">
                                                                         <label for="textfield" class="control-label">Block</label>
                                                                             <select onchange="load_rooms();"  name="block" id="block" class='select2-me' style="width:100%;" >
                                                                                 <option value="">Select Block</option>
                                                                                  <?php
                                                                                  $query = " select * from hostel_blocks where iid='".$this->session->userdata('staff_Org_id')."' ";
                                                                                  $query = $this->db->query($query);
                                                                                    $query=$query->result();
                                                                                    foreach($query as $val){
                                                                                        ?>
                                                                                  <option value="<?php echo $val->block_id ?>" >
                                                                                      <?php echo $val->block_name ?>
                                                                                  </option>
                                                                                     <?php
                                                                                    }
                                                                                  ?>
                                                                             </select>  
                                                                             <span id='block_err' style=" color: red">

                                                                             </span>  
                                                             </div>
                                                         </div>   

                                                             <div class="col-sm-4 " >

                                                                 <div class="form-group1">
                                                                 <label for="textfield" class="control-label">Room</label>
                                                                 <select    name="room" id="room" class='select2-me' style="width:100%;" >
                                                                         <option value="">Select Room</option>
                                                                     </select>  
                                                                     <span id='room_err' style=" color: red">

                                                                     </span> 
                                                         </div>
                                                            </div>

                                                            <div class="col-sm-4 " >

                                                                 <div class="form-group1">
                                                                 <label for="textfield" class="control-label">Fee Amount</label>
                                                                 <input type='text' name="fee_amount" id='amount' class="form-control" placeholder="Please Enter Fee Amount ..."  /> 
                                                                     <span id='amount_err' style=" color: red">

                                                                     </span>
                                                             </div>

                                                             </div>
                                                        </div>

                                                        <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                                                           <div class="form-actions col-sm-offset-4 col-sm-4">
                                                               <input type="button" name="submit" value="Add Student To Trip" onclick="Admit_Student();" class="btn btn-primary btn-block" />
                                                               <span id="errors"  style=" color: red; text-align: center"></span>
                                                           </div>
                                                       </div>
                                                    </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="box ">
                                    <div class="box-title">
                                            <h3><i class="fa fa-inr"></i>Fee Structure</h3> 
                                    </div>
                                    <div id='Fee_structure' class="box-content nopadding">  
                                        
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