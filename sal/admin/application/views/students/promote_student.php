<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');

?>

<script>
function myFunction() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setState('bday_msg','<?php echo base_url() ?>index.php/students/promote_students/','check='+ck_string);
            }else{
                alert('Please choose atleast one checkbox.');
                location.reload();
            }

                  
                }

function terminate() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            var reason =$('#reason').val();
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setStates('reason_err','<?php echo base_url() ?>index.php/students/terminate_stu/','check='+ck_string+ '&reason='+reason);
            }else{
                alert('Please choose atleast one value.');
            }

                  
                }

    function same_class() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            var reason =$('#reason1').val();
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setStates('reason_err2','<?php echo base_url() ?>index.php/students/same_class_stu/','check='+ck_string+ '&reason='+reason);
            }else{
                alert('Please choose atleast one value.');
            }

                  
                }

    function myFunction1() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setState('bday_msg1','<?php echo base_url() ?>index.php/students/promote_students/','check='+ck_string);
            }else{
                alert('Please choose atleast one checkbox.');
                location.reload();
            }

                  
                }

    function myFunction2() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setState('bday_msg2','<?php echo base_url() ?>index.php/students/promote_students/','check='+ck_string);
            }else{
                alert('Please choose atleast one checkbox.');
                location.reload();
            }

                  
                }

    function promote() {

                  var ck_string = "";
            $.each($("input[id='check']:checked"), function(){  
                ck_string += " "+$(this).val();  
            });
            var pclass =$('#pclass').val();
            var section1 =$('#section1').val();
            if (ck_string ){
                ck_string = ck_string .substring(1);
                setStates('reason_err1','<?php echo base_url() ?>index.php/students/promote_stu/','check='+ck_string+ '&pclass='+pclass+ '&section1='+section1);
            }else{
                alert('Please choose atleast one value.');
            }

                  
                }

</script>

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
                    <a href="<?php echo base_url(); ?>index.php/students">Students</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Promote Students</a>
                </li>
            </ul>
        </div>
    
            <div class="box box-bordered box-color nopadding">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Search Students</h3> 
                    </div>
                    <div class="box-content nopadding">  
                        <form class='form-horizontal form-bordered' method="get" action ="">
                            <div class="col-sm-12 nopadding" style=" border-bottom:   1px solid #cccccc">
                                <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">Class</label>
                                            <div class="col-sm-10">
                                                <select onchange="load_classes();" class="select2-me" style=" width: 100%" name="sclass" id="sclass" class="form-control" >
                                                    <option value="">Select Class </option>
                                                    <?php
                                                  echo   $cls= "SELECT * from class where iid='".$this->session->userdata("staff_Org_id")."' order by id desc";
                                                     $cls = $this->db->query($cls)->result();
                                                     foreach ($cls as $value) {
                                                      ?>
                                                    <option value="<?php echo $value->id ?>"
                                                            <?php
                                                            if($_GET['sclass']==$value->id){
                                                               echo 'selected=""'; 
                                                            }
                                                            
                                                            ?>  ><?php echo $value->name ?></option>
                                                      <?php   
                                                     }
                                                     ?>
                                                </select>
                                                <span style=" color: red" id="class_err">
                                                    <?php
                                                        echo $this->form->error('class_err');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>
                                <div class="col-sm-4 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">Section</label>
                                            <div class="col-sm-10">
                                                <select style="width: 100%" class="select2-me" id="section" name="section" >
                                                            <option value="" >Select Section</option>
                                                             <?php
                                                             $sclass =  $_GET["sclass"];
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.id='".$sclass."'  ORDER BY c.id";
            $query = $this->db->query($query);
            ?>
                
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->sid ?>" <?php
                if($_GET['section']==($val->sid) ){
                    echo ' SELECTED="" ' ;
                }
                ?> >
                    <?php echo $val->sec_name ?>
                </option>
             <?php
            }
                                                             ?>
                                                    </select>
                                                <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('section');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>

                                <script>
                                             function load_classes(){
                                                 sclass =$('#sclass').val();
                                                 $('#class_err').html("");
                                                 if(sclass.length==0){
                                                      $('#class_err').html("** Please Select Class First");
                                                      setState('section','<?php echo base_url() ?>index.php/students/load_sec','sclass='+sclass);
                                                  }else{
                                                      //load_class_sec
                                                    setState('section','<?php echo base_url() ?>index.php/students/load_sec','sclass='+sclass);
                                                  }
                                             }
                               </script> 

                            <div class="col-sm-4 nopadding">
                               <div class="form-actions col-sm-offset-2 col-sm-10">

                                    <button class="btn  btn-primary " type="submit"> Search student</button>
                                    <span style=" color: red"><?php
                                  if(strlen($error)>0){
                                      echo "<br/>";
                                      echo $error;
                                  }?></span>
                                </div>
                            </div>
                                
                            </div>
                            
                        </form>



                    </div>
            </div>

            <?php
      
               
        if(!$result){
            
        }else{
         ?>
                          <form action="" method="post">
                          <div class="form-actions col-sm-offset-9 col-sm-3">
                            <button class="btn  btn-primary " type="button" data-toggle="modal" data-target="#myModal1" onclick="myFunction1()"> Promote</button>
                            <button class="btn  btn-primary " type="button" data-toggle="modal" data-target="#myModal" onclick="myFunction()"> Terminate</button>
                            <button class="btn  btn-primary " type="button" data-toggle="modal" data-target="#myModal2" onclick="myFunction2()"> Same Class</button>
                            <span style=" color: red">
                                               <?php     if(($error)>0){
                                                echo $error;
                                                }?>
                                                </span> 
                          </div>
                          <br><br>
                    <div class="box box-bordered box-color nopadding">
                            <div class="box-title">
                                    <h3><i class="fa fa-th-list"></i>Search Results</h3> 
                            </div>
                            
                            <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin table-bordered">
                                    <thead>
                                            <tr>
                                                    <th>
                                                        <input type="checkbox" onchange="checkAll()" name="" />
                                                    </th>
                                                   
                                                    <th>Student name</th>
                                                     <th>Roll No</th>
                                                    <th >Userid</th>
                                                    <th>Gender</th>
                                                    <th >mobile</th>
                                                    <th >Admission No</th>

                                                  <!-- <?php
                                                   if($this->session->userdata('staff_level')>3){
                                                       ?>
                                                       <th>Actions</th>
                                                     <?php
                                                   }
                                                   ?>-->
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=1;
                                           foreach ($result as $value) {
                                              
                                             ?>
                                            <tr>
                                              <td>
                                                  <input type="checkbox" id="check" name="check[]" value="<?php echo $value->student_id ?>" />
                                              </td>
                                               
                                                <td>
                                                    <?php echo $value->name ?>
                                                    
                                                </td>
                                                <td><?php echo $value->roll ?></td>
                                                <td><?php echo $value->userid ?></td>
                                                <td><?php if($value->sex==1)
                                                            {
                                                                echo "Male";
                                                            }else{
                                                                echo "Female";
                                                            }
                                                            ?></td>
                                                <td><?php echo $value->phone ?></td>
                                                <td><?php echo $value->admission_no ?></td>
                                                
                                                <!--<?php
                                                   if($this->session->userdata('staff_level')>3){
                                                       ?>
                                                         <td>
                                                    <a href="<?php echo base_url() ?>index.php/Students/view_details/<?php  echo $value->userid   ?>"><i class="fa fa-eye" aria-hidden="true"></i>
        </a>
                                                    <a href="<?php echo base_url() ?>index.php/Students/edit/<?php  echo $value->student_id   ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
        </a>
                                                </td>
                                                        <?php
                                                      }
                                                      ?>-->


                                            </tr>
                                             <?php 
                                           }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                           
                    </div>
                
         <?php
        }
       
    ?>
    
    
   </div>
    </div> 
</div>

<!-- Terminate Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form class='form-horizontal form-bordered' action="" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Terminate Students</h4>
        </div>
        <div class="modal-body">
            
          <p id="bday_msg"></p>
          <span id="reason_err"></span><br><br>
        
          <div class="col-sm-12 nopadding">
            <div class="form-group1" >
               <div class="col-sm-2"> 
                <label for="field-1" class="control-label">Reason <span style=" float: right ; color: red">*</span></label>
            </div>
            <div class="col-sm-10"> 
             <textarea name="reason" id="reason" class="form-control" style=" resize: none" placeholder="Please enter Reason For Termination"></textarea>
            
         </div>
            </div>
        </div>
        </div><br><br>
        <div class="modal-footer">

            <button type="button" class="btn btn-primary" onclick="terminate()">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>
    </form>
      </div>
    </div>
  </div>

  <!-- Same Class Modal -->

  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form class='form-horizontal form-bordered' action="" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Same Class Students</h4>
        </div>
        <div class="modal-body">
            
          <p id="bday_msg2"></p>

          <span id="reason_err2"></span><br><br>
        
          <div class="col-sm-12 nopadding">
            <div class="form-group1" >
               <div class="col-sm-2"> 
                <label for="field-1" class="control-label">Reason <span style=" float: right ; color: red">*</span></label>
            </div>
            <div class="col-sm-10"> 
             <textarea name="reason1" id="reason1" class="form-control" style=" resize: none" placeholder="Please enter Reason For Same Class"></textarea>
            
         </div>
            </div>
        </div>
        </div><br><br>
        <div class="modal-footer">

            <button type="button" class="btn btn-primary" onclick="same_class()">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>
    </form>
      </div>
    </div>
  </div>

  <!--Promote Modal -->
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form class='form-horizontal form-bordered' action="" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Promote Students</h4>
        </div>
        <div class="modal-body">
            
        
          <p id="bday_msg1"></p>
          <span id="reason_err1"></span><br><br>
<?php
        $class = $_GET['sclass'];
       // $class = $class + 1;
          //echo $class;  
		  
		//  " and id='".$class."'";
		  
		  ?>
          <div class="col-sm-6 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2">Class<span style="float: right ; color: red">*</span></label>
                                            <div class="col-sm-10">
                                                <select onchange="load_classes1();" class="select2-me" style=" width: 100%" name="pclass" id="pclass" class="form-control" >
                                                    <option value="">Select Class </option>
                                                    <?php
                                                     $cls= "SELECT * from class where iid='".$this->session->userdata("staff_Org_id")."'";
                                                     $cls = $this->db->query($cls)->result();
                                                     foreach ($cls as $value) {
                                                      ?>
                                                    <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                                      <?php   
                                                     }
                                                     ?>
                                                </select>
                                                <span style=" color: red" id="class_err1">
                                                    <?php
                                                        echo $this->form->error('class_err');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>
                                <?php
								  $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.id='".$sclass."'  ORDER BY c.id";
								?>
                                <div class="col-sm-6 nopadding">
                                        <div class="form-group">
                                            <label for="field-1" class="control-label col-sm-2"> <span style=" float: right ; color: red">*</span>Section</label>
                                            <div class="col-sm-10">
                                                <select style="width: 100%" class="select2-me" id="section1" name="section1" >
                                                            <option value="" >Select Section</option>
                                                             <?php
                                                             $sclass =  $_GET["pclass"];
            $query = " SELECT c.id,s.sid,s.name as sec_name , c.name as cls_name FROM `class` c JOIN section s ON s.class_id=c.id where c.iid='".$this->session->userdata('staff_Org_id')."' AND c.id='".$sclass."'  ORDER BY c.id";
            $query = $this->db->query($query);
            ?>
                
                <?php
            $query=$query->result();
            foreach($query as $val){
                ?>
                <option value="<?php echo $val->sid ?>">
                    <?php echo $val->sec_name ?>
                </option>
             <?php
            }
                                                             ?>
                                                    </select>
                                                <span style=" color: red">
                                                    <?php
                                                        echo $this->form->error('section');
                                                       ?>
                                                </span> 
                                            </div>
                                        </div>

                                </div>

                                <script>
                                             function load_classes1(){
                                                 sclass =$('#pclass').val();
                                                 $('#class_err1').html("");
                                                 if(sclass.length==0){
                                                      $('#class_err1').html("** Please Select Class First");
                                                      setState('section1','<?php echo base_url() ?>index.php/students/load_sec','sclass='+sclass);
                                                  }else{
                                                      //load_class_sec

                                                    setState('section1','<?php echo base_url() ?>index.php/students/load_sec','sclass='+sclass);

                                                  }
                                             }
                               </script>
        </div><br><br>
        <div class="modal-footer">

            <button type="button" class="btn btn-primary" onclick="promote()">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>
    </form>
      </div>
    </div>
  </div>
   
<script>
  function checkAll() {
     var checkboxes = document.getElementsByTagName('input');
   var val = null;
     for (var i = 0; i < checkboxes.length; i++) {
         if (checkboxes[i].type == 'checkbox') {
             if (val === null) val = checkboxes[i].checked;
             checkboxes[i].checked = val;
         }
     }
 }


</script>

<?php
$this->load->view('structure/footer');
?>
