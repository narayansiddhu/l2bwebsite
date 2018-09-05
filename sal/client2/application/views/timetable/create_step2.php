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
                            <a href="">Create Timetable</a>
                        </li>
                    </ul>
            </div>
    
            <?php 
              $section = $this->db->query("SELECT s.sid,s.name as sec_name , c.name as cls_name FROM `section` s JOIN class c on s.class_id=c.id where s.sid='".$post['stdsection']."' ");
              $section = $section->row();

            ?>
    
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3>
                                    <i class="fa fa-th-list"></i>Configure Timetable of <?php echo $section->sec_name." , ".$section->cls_name ?></h3> 
                    </div>
                    <div class="box-content nopadding">                                
                        <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/timetable/save" method="post" enctype="multipart/form-data"  >
                            <input type="hidden" name="stdsection" value="<?php echo $post['stdsection'] ?>" />        
                            <input type="hidden" name="periods" value="<?php echo $post['periods'] ?>" />          
                                        
                                    
                            <div id="periods_time" class="form-group">
                                    <?php
                                    $periods= $post['periods'];
                                     
                                    for($i=1;$i<=$periods;$i++){
                                              ?>
                                               <div class='form-group'>
                                                   <label  class='control-label col-sm-2'>Period _ <?php echo $i; ?></label>
                                                   <div class='col-sm-10'>
                                                       <input type='text' style=' width: 45%; float: left' name='from_<?php echo $i; ?>' value="<?php echo  $this->form->value('from_'.$i) ?>" placeholder='ENTER FROM TIME ' class="form-control timepick" />
                                                       
                                                       <span style=' width: 10%; float: left; text-align:center'>&nbsp;&nbsp;--&nbsp;&nbsp;</span>
                                                       <input type='text' placeholder='ENTER TO TIME '  style=' width: 45%; float: left' name='to_<?php echo $i; ?>' value="<?php echo  $this->form->value('to_'.$i) ?>" class="form-control timepick" />
                                                       <span  style=' width: 45%; float: left ; color : red'>
                                                                <?php
                                                                   echo $this->form->error('from_'.$i);   
                                                                ?>
                                                        </span>
                                                       <span style=' width: 10%; float: left; text-align:center'>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                       <span  style=' width: 45%; float: left ; color : red'>
                                                                <?php
                                                                   echo $this->form->error('to_'.$i);   
                                                                ?>
                                                        </span>
                                                   </div>
                                               </div> 
                                              <?php
                                          }
                                         
                                    
                                    ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Day's</label>
                                    <div class="col-sm-10">
                                        <?php
                                         $weekdays = unserialize (Week_days);
                                                foreach ($weekdays as $key => $value) {
                                                    ?>
                                                <div class="col-sm-6">
                                                    <div class="check-line">
                                                        <label>
                                                            <input type="checkbox" name="week_day_<?php echo $key ?>"  <?php
                                                                  if($this->form->value("week_day_".$key)== 'on'){
                                                                      echo "checked";
                                                                  }?> > 
                                                            <?php echo $value ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                   <?php 
                                                 }
                                            ?>           
                                        <span style=" clear: both; color: red">
                                                <?php
                                                if(strlen($this->form->error('days'))>0){
                                                    echo "<br/>";
                                                    echo $this->form->error('days');   
                                                }
                                                   
                                                ?>
                                        </span> 
                                    </div>
                            </div>
                            
                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                <a href="#" onclick="discard_changes();" value="Discard Setting's" class="btn ">Discard </a>
                                        <input type="submit" name="submit" value="Create Time Table" class="btn btn-primary" />
                            </div>
                            
                            
                        </form>
                    </div>
            </div>
      </div>
    </div>
</div>
<script type="text/javascript">

    function discard_changes(){
       window.location.replace("<?php echo base_url(); ?>index.php/timetable/view");
    }
    
</script>

<?php
$this->load->view('structure/footer');
?>