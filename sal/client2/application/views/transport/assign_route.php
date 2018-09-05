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
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Assign Students</a>
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
                
            <div class="box ">
                    <div class="box-title">
                            <h3><i class="fa fa-road"></i>Assign Route To Student</h3> 
                    </div>
                    <div class="box-content nopadding">  
                        
                       
                        
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
    
    function load_trips(){
      $('#route_err').html(' ');//cat_err
         $('#trip').prop('disabled', true);
        route = $('#route').val();  //category       
        if(route.length==0){
           $('#route_err').html(' ** please select Route');//cat_err
         //cls_err
        }else{
           setState('trip','<?php echo base_url() ?>index.php/transport/load_trips','route='+route);
           $('#trip').prop('disabled', false); 
        }
    }
    
    function load_pickups(){
      $('#trip_err').html(' ');//cat_err
         $('#pickups').prop('disabled', true);
        trip = $('#trip').val();  //category   
        
        if(trip.length==0){
           $('#trip_err').html(' ** please select Trip');//cat_err
         //cls_err
        }else{
            trip=trip.split(',');
           setState('pickups','<?php echo base_url() ?>index.php/transport/load_pickups','trip='+trip[0]);
           $('#Fee_Amount').html(trip[1]); //fee_payee
           $('#fee_payee').html(trip[1]);
           $('#pickups').prop('disabled', false); 
        }
    }
     
     function display_pick_tym(){
        //Pick_uptym  
        $('#Pick_uptym').html(' ');
        $('#pickup_err').html(' ');
        pick_up = $('#pickups').val();   
        if(pick_up.length==0){
             $('#pickup_err').html('** Please select Pick-Up Point ');
        }else{
            pick_up = pick_up.split(',');
            $('#Pick_uptym').html(pick_up[1]);
        }
       
     }
   
   function Add_Student_trip(){
     count =0; //student_err , pickup_err
     $("#student_err").html('');
     $("#pickup_err").html('');
     $("#fee_payee_err").html('');
     student = $('#student').val(); 
     //pickups ,fee_payee
     pickups = $('#pickups').val(); 
     fee_payee = $('#fee_payee').val(); 
     
     if(student.length==0){
         $("#student_err").html('** Please select Student');
         count++;
     }
     if(pickups.length==0){
         $("#pickup_err").html('** Please select pick up Point');
         count++;
     }
     if(fee_payee.length==0){
         $("#fee_payee_err").html('** Please Enter Amount To be paid');
         count++;
     }
     
     if(count==0){
         pickups= pickups.split(",");
         student=student.split(",");
         
         setState('errors','<?php echo base_url() ?>index.php/transport/add_stud_record','pickups='+pickups[0]+'&student='+student[0]+'&fee_payee='+fee_payee+'&fee='+fee_payee);
         
     }
     
   }
   
   
</script>
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
                                            <label for="textfield" class="control-label">Route</label>
                                                <select onchange="load_trips();"  name="route" id="route" class='select2-me' style="width:100%;" >
                                                    <option value="">Select Route</option>
                                                     <?php
                                                     $query = " select * from routes where iid='".$this->session->userdata('staff_Org_id')."' ";
                                                     $query = $this->db->query($query);
                                                       $query=$query->result();
                                                       foreach($query as $val){
                                                           ?>
                                                     <option value="<?php echo $val->route_id ?>" >
                                                         <?php echo $val->rname ?>
                                                     </option>
                                                        <?php
                                                       }

                                                     ?>
                                                </select>  
                                                <span id='route_err' style=" color: red">

                                                </span>  
                                </div>
                            </div>   
                               
                                <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                    <label for="textfield" class="control-label">Trip</label>
                                       <select onchange="load_pickups();"  name="trip" id="trip" class='select2-me' style="width:100%;" >
                                            <option value="">Select Trip</option>
                                        </select>  
                                        <span id='trip_err' style=" color: red">
                                                
                                        </span> 
                            </div>
                               </div>
                               
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                    <label for="textfield" class="control-label">Pick-Up Point</label>
                                    <select  onchange="display_pick_tym();"  name="pickup" id="pickups" class='select2-me' style="width:100%;" >
                                            <option value="">Select  Pick-Up Point</option>
                                        </select>  
                                        <span id='pickup_err' style=" color: red">

                                        </span>
                                </div>
                                    
                                </div>
                                
                            
                           </div>
                            
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; ">
                              
                                <div class="col-sm-4 nopadding" >
                                    <div class="form-group1">
                                            <label for="textfield" class="control-label">Pick-Up Time</label>
                                            <label  class="form-control" id="Pick_uptym" ></label> 
                                </div>
                            </div>   
                               
                                <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                    <label for="textfield" class="control-label">Fee Assigned</label>
                                    <label  class="form-control" id="Fee_Amount" ></label> 
                            
                            </div>
                               </div>
                               
                               <div class="col-sm-4 " >
                                
                                    <div class="form-group1">
                                    <label for="textfield" class="control-label">Fee To be Paid</label>
                                    <input type="text" name="fee_payee" id="fee_payee" value="" class="form-control" />
                                        <span id='fee_payee_err' style=" color: red">

                                        </span>
                                </div>
                                    
                                </div>
                                
                            
                           </div>
                            
                           <div class="col-sm-12 nopadding" style=" width: 100%; height: auto; margin-top: 10px; ">
                              <div class="form-actions col-sm-offset-4 col-sm-4">
                                  <input type="button" name="submit" value="Add Student To Trip" onclick="Add_Student_trip();" class="btn btn-primary btn-block" />
                                  <span id="errors"  style=" color: red; text-align: center"></span>
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