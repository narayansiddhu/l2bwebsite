<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box"><br/>
     <?php // $this->load->view('timetable/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                   <a href="<?php echo base_url(); ?>">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Create Timetable</a>
                </li>
            </ul>
    </div>
    
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Create Timetable</h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/timetable/create_step2" method="post" enctype="multipart/form-data"  >

                            <div class="form-group">
						<label for="field-2" class="control-label col-sm-2">class</label>
                        
						<div class="col-sm-10">
                                                    <select style="width: 100%" class="select2-me" name="stdclass"  data-validate="required" id="class_id" 
								data-message-required="value_required"
									onchange="return get_class_sections(this.value)">
                                                        <option value="">select Class </option>
                                                       <?php 
                                                            $credential = array('iid'=>$this->session->userdata('staff_Org_id') );
                                                           $classes = $this->db->get_where('class', $credential)->result_array();	

                                                            foreach($classes as $row){
                                                                    ?>
                                                                <option value="<?php echo $row['id'];?>" <?php if($this->form->value('stdclass')==$row['id']){
                                                                        echo "selected";
                                                                    }?>  >
                                                                <?php echo $row['name'];?>
                                                                </option>
                                                                 <?php
                                                            }
							  ?>
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdclass');
                                                           ?>
                                                    </span> 
						</div> 
					</div>
                                        
					<div class="form-group">
						<label for="field-2" class="control-label col-sm-2">section</label>
                                                <div class="col-sm-10">
                                                    <select name="stdsection" style="width: 100%" class="select2-me"  id="section_selector_holder">
                                                        <option value="">select_class_first</option>
                                                        <?php 
                                                        if(strlen($this->form->value('stdclass'))!=0){
                                                              
                                                        $credential = array('iid'=>$this->session->userdata('staff_Org_id') ,'class_id' =>$this->form->value('stdclass') );
                                                        $classes = $this->db->get_where('section', $credential)->result_array();	
                                                          ?>
                                                        <option value="">Select Section</option>
                                                          <?php
                                                        foreach($classes as $row){
                                                            ?>
                                                            <option value="<?php echo $row['sid'];?>" <?php
                                                            if($this->form->value('stdsection')==$row['sid']){
                                                                echo "selected";
                                                            }  ?> >
                                                            <?php echo $row['name'];?>
                                                            </option>
                                                            <?php
                                                        }

                                                        }
                                                        ?> 
                                                    </select>
                                                    <span style=" color: red">
                                                        <?php
                                                            echo $this->form->error('stdsection');
                                                        ?>
                                                    </span> 
                                                </div>
					</div>
                                       
                                    
                                
                                        <div class="form-group " >
                                            <label   for="textfield" class="control-label col-sm-2 ">No Of Periods</label>
                                            <div  class="col-sm-10">
                                                <input type="text" id="periods" name="periods"  style="   " value="<?php echo $this->form->value('periods'); ?>" placeholder="Enter No Of  Periods per day " class="form-control"/>
                                                <span id="periodserr" style=" color: red">
                                                        <?php
                                                           echo $this->form->error('periods');   
                                                        ?>
                                                </span>        
                                            </div>
                                        </div>
                                    
                                        <div class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit" name="submit" value="Configure Time Table" class="btn btn-primary" />
                                        </div>
                            
                           
                            
                            
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function get_class_sections(class_id) {
      if(class_id.length!=0){
         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
      }       
    }
    
    function load_sections(class_id,section){
    alert("pavancvbn");
     setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id+'&section='+section); 

    }
    
    
    //periods_time
    function add_periods(){    
        periods = $('#periods').val();
        i=1;
        if(periods.length==0){
            $('#periodserr').html(" please enter numeric value"); 
        }else if(isNaN(periods)){
            $('#periodserr').html(" Invalid Numeric Value");
            //noofsecerr
        }else{
             if(periods >= 1){
                 $('#periods_time').html("");
                 var t ="";
                 for(i=1;i<=periods;i++){
                     var div = document.getElementById('periods_time');

                     div.innerHTML = div.innerHTML + "<div class='form-group'><label  class='control-label col-sm-2'>Period _ "+i+"</label><div class='col-sm-10'><input type='text' style=' width: 45%; float: left' name='from_"+i+"' id='from_"+i+"' placeholder='ENTER FROM TIME '  class='form-control timepick' /><span style=' width: 10%; float: left; text-align:center'>&nbsp;&nbsp;--&nbsp;&nbsp;</span><input type='time' placeholder='ENTER TO TIME '  style=' width: 45%; float: left' name='to_"+i+"' class='form-control timepick' /></div></div>  ";
                              
  
                 }
                }else{
               $('#periodserr').html("Enter Valid Numeric Value");
             }
        }
    }
    
</script>

<?php
$this->load->view('structure/footer');
?>