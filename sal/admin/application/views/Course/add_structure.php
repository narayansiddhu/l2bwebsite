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
                    <a href="<?php echo base_url(); ?>">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Course Structure</a>
                </li>
            </ul>
    </div>
    <br/><hr/>
                <div class="box">
                        <div class="col-sm-4 nopadding" >
                            
                            <h4 style=" color:  #cc6600; text-align: center">Class Info</h4>
                            <table  class="table table-bordered" style=" color:  teal">
                                <tr>
                                    <td>Class</td>
                                    <td><?php echo $class_data->name ?></td>
                                </tr>
                                <tr>
                                    <td>Subject</td>
                                    <td><?php echo $course->subject ?></td>
                                </tr>
                                <tr>
                                    <td>Chapters</td>
                                    <td><?php echo $course->chapters ?></td>
                                </tr>
                            </table>
                        </div>
                       
                    <div class="col-sm-8">
                        <div class="box " style="  border: 1px solid #ccc">
                            
                        <div class="box-content nopadding">    

                            <form class='form-horizontal form-bordered' action="<?php echo base_url()  ?>index.php/Course/add_topic"  method="post" >

                                <input type="hidden" name="cls_id" value="<?php echo $course->class_id ?>" />
                                <input type="hidden" name="sub_id" value="<?php echo $course->sub_id ?>" />
                                <input type="hidden" name="csid" value="<?php echo $course->csid ?>" />
                                    <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Chapter Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" style=" width: 100%" name="chapter" placeholder="Enter Chapter Name" class="form-control" value="<?php echo $this->form->value('chapter') ?>" > 
                                            <span style=" color: red">
                                                    <?php
                                                       echo $this->form->error('chapter');   
                                                    ?>
                                                </span>        
                                        </div>
                                </div>    

                                <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">No Of Sub-topics</label>
                                    <div class="col-sm-10">
                                        <input type="text" style=" width: 90%; float: left" id="topics" name="topics" placeholder="Enter No Of Sub-Topics" class="form-control" value="<?php echo $this->form->value('topics') ?>" > 
                                        &nbsp;&nbsp; &nbsp;<a href="#" onclick="add_sub_topics();" class="btn btn-large btn-primary" style="  float: left"><i class="fa fa-plus"></i></a>
                            
                                        <span style=" clear: both; color: red">
                                                <?php
                                                    echo $this->form->error('medium')
                                                   ?>
                                            </span>        
                                    </div>
<script>
    function add_sub_topics(){
        topics =$("#topics").val();
        setState('sub_topics_holder','<?php echo base_url() ?>index.php/Course/add_sub_topic_textbox','topics='+topics);
                       
    }
    
                                        document.getElementById('topics').addEventListener('keydown', function(e) {
                                            var key   = e.keyCode ? e.keyCode : e.which;

                                            if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                                                 (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
                                                 (key >= 35 && key <= 40) ||
                                                 (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                                                 (key >= 96 && key <= 105)
                                               )) e.preventDefault();
                                        });
                                        </script>

                            </div> 
                                <div id="sub_topics_holder">
                                    
                                    <?php
                      if(strlen($this->form->value('topics'))!=0){
                           
                            if(is_numeric($this->form->value('topics'))){
                                for($i=1;$i<=$this->form->value('topics');$i++){
                                    ?>
                                    <div class="form-group">
                                           <label for="textfield" class="control-label col-sm-2">Sub-Topic - <?php echo $i; ?></label>
                                           <div class="col-sm-10">
                                         <input type="text" style=" width:80% ; float: left"  name="subtopic_<?php echo $i; ?>"  placeholder="Enter Sub-Topic"   class="form-control" value="<?php  echo $this->form->value('subtopic_'.$i); ?>" >
                            <input type="text" style="margin-left: 5px;  width:18%; float: left" id='nfclasses_<?php echo $i; ?>' name="nfclasses_<?php echo $i; ?>"  placeholder="No Of Hours"   class="form-control" value="<?php  echo $this->form->value('subtopic_'.$i); ?>" >
                            <script>
                                 document.getElementById('nfclasses_<?php echo $i ?>').addEventListener('keydown', function(e) {
                                     var key   = e.keyCode ? e.keyCode : e.which;

                                     if (!( [8, 9, 13, 27, 46, 110, 190].indexOf(key) !== -1 ||
                                          (key == 65 && ( e.ctrlKey || e.metaKey  ) ) || 
                                          (key >= 35 && key <= 40) ||
                                          (key >= 48 && key <= 57 && !(e.shiftKey || e.altKey)) ||
                                          (key >= 96 && key <= 105)
                                        )) e.preventDefault();
                                 });
                             </script>
                                               <span id='noofsecerr' style=" clear: both; width:80% ; float: left; color: red">
                                                       <?php
                                                           echo $this->form->error('subtopic_'.$i);   
                                                       ?>
                                               </span> 
                                               <span id='noofsecerr' style=" margin-left: 5px; width:18% ; float: left; color: red">
                                                       <?php
                                                           echo $this->form->error('nfclasses_'.$i);   
                                                       ?>
                                               </span> 
                                           </div>
                                    </div>
                                   <?php
                                }
                            }
                         
                      }
                    ?>
                                    
                                </div>
                                <hr style=" padding: 0px; margin: 0px;"/>
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit" 
                                                   <?php  
                                                   if(strlen($this->form->value('topics'))==0){
                                                       echo 'disabled=""';
                                                   }
                                                   ?>
                                                    id="submit_btn"  name="submit" value="Add Chapter To Structure" class="btn btn-primary" />
                                        </div>

                                
                                </form>
                        </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>

