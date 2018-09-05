<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$stud=$this->input->get("student");
$stud_err="";$show=1;
if(strlen($stud)!=0){
   $query=  $this->db->query("SELECT s.student_id,s.phone,s.email,s.photo,s.roll,s.name,s.userid,c.id as cls_id,se.sid ,c.name as clsname , se.name as section  FROM  student s JOIN class c ON c.id=s.class_id JOIN section se ON se.sid=s.section_id  where s.userid = '".$stud."' AND s.iid='".$this->session->userdata("staff_Org_id")."'");
     if($query->num_rows()==1){
         $student=$query->row();
         $show=0;
     }else{
        $stud_err="** invalid student selected"; 
     }        
}
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
                            <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/accounts/view/">Add Fee Payment</a>
                        </li>
                    </ul>

            </div> 
            
            <div class="box" >
                <?php
                if(strlen($this->session->userdata('add_record_sucess'))>0 ){
                     $id=  array_filter(explode(",",$this->session->userdata('add_record_sucess')));
                    ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                        New Record Added Successfully With Invoice No : <?php  echo $id[0]?>, <a target="_blank" href="<?php echo base_url() ?>index.php/accounts/printout/<?php echo $id[0] ?>">Click Here For Prinout</a>
                        </div>
                    <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(7000).fadeOut();
                   </script>
                    
                   <?php
                    $this->session->unset_userdata('add_record_sucess');
                }

            ?>
            </div>
            
            <div class="box">
                <?php 
                      if($show==1){
                           ?>
                <div class="col-sm-6 nopadding">
                           <div class="box box-bordered box-color">                 
                        <div class="box-title">
                            <h3><i class="fa fa-money"></i>Add Fee Payment</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered'  >
                                
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-4">Class</label>
                                    <div class="col-sm-8">
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
                                
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-4">Section</label>
                                    <div class="col-sm-8">
                                        <select onchange="load_students();"  name="section" id="section" class='select2-me' style="width:100%;" >
                                            <option value="">Select A Section</option>
                                        </select>  
                                        <span id='section_err' style=" color: red">
                                                
                                        </span>  
                                    </div>
                            </div>
                                
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-4">Student</label>
                                    <div class="col-sm-8">
                                        <select  name="student" id="student" class='select2-me' style="width:100%;" >
                                            <option value="">Select A Student</option>
                                        </select>  
                                        <span id='student_err' style=" color: red">

                                        </span>  
                                    </div>
                                </div>
                                
<!--                                <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-4">Category</label>
                                        <div class="col-sm-8">
                                            <select onchange="load_balance();"  name="category" id="category" class='select2-me' style="width:100%;" >
                                                <option value="">Select A Category</option>
                                                 <?php
                                                 $credential = array('iid' =>$this->session->userdata('staff_Org_id'));
                                                 $query = $this->db->get_where('fee_category', $credential);
                                                   $query=$query->result();
                                                   foreach($query as $val){
                                                       ?>
                                                 <option value="<?php echo $val->cid ?>" >
                                                     <?php echo $val->category ?>
                                                 </option>
                                                    <?php
                                                   }

                                                 ?>
                                            </select>  
                                            <span id='cat_err' style=" color: red">

                                            </span>  
                                        </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-4">Amount</label>
                                    <div class="col-sm-8">
                                        <label class="form-control" id="totalamount" placeholder="Balance" style=" width: 50%; float: left"  >Balance</label>
                                        <input type="text" name="totalamount" id="totalamount" disabled="" placeholder="Balance" class="form-control" style=" width: 50%; float: left"  />
                                        <input type="number" min="1" max="100" name="amount" id="amount" placeholder="Enter Amount" class="form-control"  style=" width: 50%; float: left" />
                                       <span id='amount_err' style=" color: red">

                                        </span> 
                                    </div>  onclick="add_record();"
                                </div>-->
                                
                            <div  class="form-actions col-sm-offset-4 col-sm-10">
                                <input type="button" onclick="fetch_fee_due();" id="Add_records_btn"  name="submit"  value="Pay Fee's" class="btn btn-primary" />
                                 <span id='errors' style=" color: red">
                                 </span>
                                 <input type="hidden" name="fee_checker" id='fee_checker' value="" />
                                
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                           <?php
                      }else{
                          ?>
                <div class="col-sm-3 nopadding">
                    <div class="box box-bordered">
                         <div class="box-title">
                             <h3 style=""><i class="fa fa-child"></i>Student Details</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div style=" text-align: center"><br/>
           <?php
              if(strlen($student->photo)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$student->photo)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $student->photo  ?>" alt="..." style=" width: 100px;; height: 100px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 100px;"   >
            
                <?php
                 }
              }
              ?>
           </div><hr/>
           <h3 style=" text-align: center; color:  #318EEE"><?php echo $student->name ?></h3>
           <table class=" table table-bordered nopadding" style=" width:100%">
               <tr>
                   <td><i class="fa fa-sitemap" aria-hidden="true"></i>&nbsp;<?php echo $student->clsname ." - " .$student->section ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;<?php echo $student->userid ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;<?php echo $student->phone ?></td>
               </tr>
               <tr>
                   <td><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php echo $student->email ?></td>
               </tr>
           </table>
           <h4 style=" text-align: center; color:  #ff3333">Other Balances</h4>
           <?php
            $check="SELECT `transport`,`hostel` FROM `modules_acess` WHERE  iid='".$this->session->userdata('staff_Org_id')."' ";
            $check = $this->db->query($check)->row();
            if($check->transport!=0){
                $trans ="SELECT * ,(SELECT sum(fee) FROM `transport_fee`  WHERE student =st.stud_id ) as paid FROM `stud_transport` st where st.stud_id ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $trans = $trans->row();
                 //   print_r($trans);
                    ?>
           <p style=" text-align: center; font-size: 18px">Transportation : <?php echo $trans->fee_amount-$trans->paid ?></p>
                    <?php
                }
                
           }
           if($check->hostel!=0){
               $trans ="SELECT * ,(SELECT sum(fee) FROM `hostel_feepayment`  WHERE student =st.student_id ) as paid FROM `hostel_students` st where st.student_id ='".$student->student_id."'   ";
                $trans = $this->db->query($trans);
                if($trans->num_rows()>0){
                    $trans = $trans->row();
                 //   print_r($trans);
                    ?>
           <p style=" text-align: center; font-size: 18px">HOSTEL DEPT : <?php echo $trans->fee-$trans->paid ?></p>
                    <?php
                }
                
           }
           
           ?>
                        </div>
                    </div>
                </div>
                    
                <div class="col-sm-9 nopadding">
                    <div class="box box-bordered" style=" padding-left: 10px;">
                         <div class="box-title">
                         <h3><i class="fa fa-inr"></i>Fee Payment Details</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <form method="post" action="<?php echo base_url() ?>index.php/accounts/add" class='form-horizontal form-bordered'  >
                               <?php
                                $fee= "SELECT f.fid,f.fee,c.cid,c.category ,(select sum(amount)  from  fee_accounts a where  f.category = a.category AND a.student_id= '".$student->student_id."' ) as paid ,(select sum(amount)  from  fee_concession fc where  f.category = fc.cat_id AND fc.std_id='".$student->student_id."' ) as concession FROM `fee_class` f JOIN fee_category c ON f.category=c.cid WHERE f.cls_id='".$student->cls_id."' ";
                                $fee =$this->db->query($fee);
                                $cat_ids="";
                                if($fee->num_rows()==0){
                                   ?>
                                <h3 style=" text-align: center; color: red; padding-top: 50px;">** Fee Structure Not Yet Configured</h3>    
                                   <?php
                                }else{
                                    ?>
                                <input type="hidden" name="student_id" value="<?php echo $student->student_id ?>" />
                                <input type="hidden" name="userid" value="<?php echo $student->userid ?>" />
                                        <table style="width: 100%;" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Total</th>
                                            <th>Balance</th>
                                            <th>Pay Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                          
                                          $fee=$fee->result();
                                          foreach($fee as $f){
                                              $cat_ids.=$f->cid.",";
                                             ?>
                                        <tr>
                                            <th><?php echo $f->category ?></th>
                                            <td><?php echo $f->fee ?></td>
                                            <td><?php echo $f->fee-($f->paid+$f->concession) ?></td>
                                            <td>
                                                <input type="hidden" name="cat_bal_<?php echo $f->cid ?>" value="<?php echo $f->fee-($f->paid+$f->concession) ?>" />
                                                <input type="text" onkeyup="Get_total();"  name="pay_cat_<?php echo $f->cid ?>" id="pay_cat_<?php echo $f->cid ?>" value="<?php
                                                if(strlen($this->form->value("pay_cat_".$f->cid))==0){
                                                    echo 0;
                                                }else{
                                                echo $this->form->value("pay_cat_".$f->cid);
                                                }   
                                                        ?>" class="form-control" placeholder="Enter Amount To Pay" />
                                                <span style=" color: red"><?php echo $this->form->error("pay_cat_".$f->cid) ?></span>

                                            </td>
                                    <script>
                                        document.getElementById('pay_cat_<?php echo $f->cid ?>').addEventListener('keydown', function(e) {
                                            var key   = e.keyCode ? e.keyCode : e.which;

                                            if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                                                 (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
                                                 (key >= 35 && key <= 40) ||
                                                 (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                                                 (key >= 96 && key <= 105)
                                               )) e.preventDefault();
                                        });
                                        </script>
                                        </tr>    
                                             <?php
                                          }
                                         $cat_ids = substr($cat_ids, 0,strlen($cat_ids)-1);
                                          ?>
                                    
                                    <input type="hidden" id="catids" name="catids" value="<?php echo $cat_ids ?>" />
                                    
                                    <script>
 
                                        function Get_total(){
                                          //  Total_fee=$('Total_fee').html();
                                            ids = $('#catids').val();
                                            ids= ids.split(",");
                                            total=0;
                                            for (i=0; i<ids.length; i++)
                                            {
                                                total+=Number($('#pay_cat_'+ids[i]).val());
                                            }
                                            $('#Total_fee').html(total);
                                        }
                                    </script>
                                        <tr>
                                            <th>
                                                Payment Type
                                            </th>
                                            <td colspan="2">
                                                <select onchange="check_ref();"  name="payment_type" id="payment_type" class="select2-me" style=" width: 100%" >
                                                    <?php
                                                    $type=  unserialize(fee_payment);
                                                
                                                     foreach($type as $k=>$val){
                                                      ?>
                                                    <option value="<?php  echo $k ?>"
                                                            <?php
                                                              if($this->form->value("payment_type")==$k){
                                                                 echo 'selected=""'; 
                                                              }
                                                              ?> 
                                                            ><?php echo $val ?></option>
                                                       <?php   
                                                     }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="ref_no" name="ref_no" style="display: none" value="<?php echo $this->form->value("ref_no") ?>" placeholder="Enter Reference No " />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td colspan="2" style=" text-align: center;" >
                                                <button onclick="disable_button();" id="submit_button" type="submit" class="btn btn-primary btn-block  ">Pay fee</button>
                                                <span style=" color: red"><?php echo $this->form->error("error") ?></span>
                                            </td>
                                            <td style=" text-align: right">Total Amount : <span id="Total_fee">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                    
                                    <?php
                                }
                                ?>
                                
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    function disable_button(){
                         $('#submit_button').hide();
                    }
                    function enable_button(){
                        $('#submit_button').show();
                    }
                function check_ref(){
                    payment_type =$('#payment_type').val();
                    if(payment_type==1){
                       $('#ref_no').hide(); 
                    }else{
                         $('#ref_no').show();
                    }
                }
               </script>
                          <?php
                      }
                    ?>
                
            </div>
            
            <span style=" clear: both; float: right; color: red">** Payment Type Other Than cash Need to be approved </span>
       </div>
    </div>
</div>
<script> 
        function fetch_records(){
            $('#herrors').html("");
           var student=$('#student').val(); 
           var recipt=$('#recipt').val();
           var fromdate=$('#fromdate').val();
           var todate=$('#todate').val();
           setState('results_table','<?php echo base_url() ?>index.php/accounts/fetch_records','student='+student+'&recipt='+recipt+'&fromdate='+fromdate+'&todate='+todate);
        }
        
        function checker_fee(id,url,datas){
	id='#'+id;
    rul=url;
		$htmlObj=$.ajax({
		 type:"POST",
		url:rul,
        ifModified:true,
	    dataType:"html",
		data: datas,
        cache: false,
	beforeSend: function(){
		                //$(id).slideDown("slow");
		           },
		success: function(result) {
                    obj = JSON.parse(result);
                    
                    if(obj.login_status=="invalid"){
                         $(id).html("Invalid Login Credentials");
                    }else{
                       window.location.href = obj.redirect_url; 
                    }
		    }
		 });
}

    </script>
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
    
    function fetch_fee_due(){
          $('#student_err').html(' ');
          stud= $('#student').val();
          if(stud.length==0){
               $('#student_err').html('** Please select Student ');
          }else{
              stud=stud.split(",");
              window.location.href="<?php echo base_url() ?>index.php/accounts/add_record?student="+stud[1];
          }
    }
    
    function load_balance(){
        //Add_records_btn
        $('#Add_records_btn').prop('disabled', true);
        count=0;
        $('#student_err').html(' ');//cat_err
        $('#cat_err').html(' ');
        category = $('#category').val();
        stud = $('#student').val();  //category
       
        if(stud.length==0){
           count++;
           $('#student_err').html('Please select student');
       }else if(category.length==0){
           count++;
           $('#cat_err').html('Please select Category');
       }else{
            setState('totalamount','<?php echo base_url() ?>index.php/accounts/fetch_cat_balance','stud='+stud+'&category='+category);
           $('#Add_records_btn').prop('disabled', false);
       }
        
        
    }
    
    function add_record(){
    $('#fee_checker').val('');
        $('#amount_err').html(' ');
        $('#student_err').html(' ');
        $('#cat_err').html(' ');
        //totalamount
        bal=$('#totalamount').html();
       category = $('#category').val();
       stud= $('#student').val();
       amount= $('#amount').val();
       count=0;
       if(stud.length==0){
           count++;
           $('#student_err').html('Please select student');
       }
       if(category.length==0){
           count++;
           $('#cat_err').html('Please select category');
       }
       if(amount.length==0){
           count++;
           $('#amount_err').html('Please Enter Amount');
       }
       
       if(bal.length==0){
           count++;
           $('#amount_err').html('Please Select valid student & category');
       }else {
           if(bal==0){
               count++;
           $('#amount_err').html('Due Amount Already Cleared.');
      
           }else{
              if( isNaN(amount) ){
               count++;
                $('#amount_err').html('Enter Numeric Value');
            }else{
                if(amount % 1 != 0){
                 count++;
                $('#amount_err').html('Decimal Not Allowed');    
                }else{
                    if(!$.isNumeric(amount)){
                        count++;
                        $('#amount_err').html('Invalid Amount');   
                    }
                  }
               } 
           }
       }
       
       if(count==0){
        setState('amount_err','<?php echo base_url() ?>index.php/accounts/add','student='+stud+'&category='+category+'&amount='+amount+'&balance='+bal);
       }
       
    }
</script>

<?php

$this->load->view('structure/footer');

?>