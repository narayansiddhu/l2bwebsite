<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <?php // $this->load->view('academics/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Cls_structure">Academics</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Create_class">Create Class</a>
                </li>
            </ul>
            
    </div>  
    <?php
        if(strlen($this->session->userdata('class_add_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('class_add_Sucess'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('class_add_Sucess');
        }
    ?>    
    
    
           
    <div class="box box-bordered box-color">
        
        <div class="box-title">
                <h3><i class="fa fa-th-list"></i>Create Class</h3>                                     
        </div>
        
        <div class="box-content nopadding">    

            <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/academics/Add_class" method="post" >

                <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Class Name</label>
                        <div class="col-sm-10">
                            <input type="text" style=" width: 50%" name="clsname" placeholder="Enter Class Name" class="form-control" value="<?php echo $this->form->value('clsname') ?>" > 
                            <span style=" color: red">
                                    <?php
                                       echo $this->form->error('clsname');   
                                    ?>
                                </span>        
                        </div>
                </div>    

                <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">No.of Sections</label>
                        <div class="col-sm-10">
                            <input type="text" id='noofsec' name="noofsec"  style=" width: 45%;  float: left" placeholder="Enter Number Of Section's"   class="form-control" value="<?php
                                 if(strlen($this->form->value('noofsec'))==0){
                                     //echo 1;
                                 }else{
                                    echo $this->form->value('noofsec');
                                 }         

                                    ?>" >&nbsp;&nbsp; &nbsp;<a href="#" onclick="add_sections();" class="btn btn-large btn-primary" style="  float: left"><i class="fa fa-plus"></i></a>
                            <br/>
                            <span id='noofsecerr' style=" clear: both; color: red">
                                    <?php
                                        echo $this->form->error('noofsec');   
                                    ?>
                            </span>        
                        </div>
                </div>
                <?php
                      if(strlen($this->form->value('noofsec'))!=0){
                           
                            if(is_numeric($this->form->value('noofsec'))){
                                for($i=1;$i<=$this->form->value('noofsec');$i++){
                                    ?>
                                    <div class="form-group">
                                           <label for="textfield" class="control-label col-sm-2">Section _ <?php echo $i; ?></label>
                                           <div class="col-sm-10">
                                               <input type="text" style=" width: 50%" id='Secname_<?php echo $i; ?>' name="Secname_<?php echo $i; ?>"  placeholder="name"   class="form-control" value="<?php  echo $this->form->value('Secname_'.$i); ?>" >

                                               <span id='noofsecerr' style=" clear: both; color: red">
                                                       <?php
                                                           echo $this->form->error('Secname_'.$i);   
                                                       ?>
                                               </span>        
                                           </div>
                                    </div>
                                   <?php
                                }
                            }
                         
                      }
                    ?>
                <div id="section_names">
                    
                    
                </div>

                <div  class="form-actions col-sm-offset-2 col-sm-10">
                    <input type="submit"  name="submit" value="Create Class" class="btn btn-primary" />
                </div>

                <script>
                function add_sections(){
                   noofsec = $('#noofsec').val();
                   if(noofsec.length==0){
                       $('#noofsecerr').html(" please enter numeric value"); 
                   }else if(isNaN(noofsec)){
                       $('#noofsecerr').html(" Invalid Numeric Value");
                       //noofsecerr
                   }else{
                        if(noofsec >= 1){
                            $('#section_names').html("");
                             setState('section_names','<?php echo base_url() ?>index.php/academics/add_section_names','noofsec='+noofsec);
                        }else{
                          $('#noofsecerr').html("Enter Valid Numeric Value");
                        }
                   }
                }
            </script>


            </form>

        </div>
    </div>
    
    
                    
        </div>
    </div>    
</div>

    
<?php
$this->load->view('structure/footer');
?>
