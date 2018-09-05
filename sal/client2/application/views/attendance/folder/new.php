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
                    <a href="<?php echo base_url(); ?>index.php//attendance/">Attendance</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php//attendance/view/">View Settings</a>
                </li>
            </ul>

    </div> 
    
    
    
             <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Create attendance Settings</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <form  class='form-horizontal form-bordered' method="post" action="<?php echo base_url() ?>index.php/attendance/create"  >
                        <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Class/Section</label>
                                    <div class="col-sm-10">
                                        <?php
                                           $query=$this->db->query("SELECT s.sid,s.name as section ,c.name as class FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC , s.sid asc");
                                           $query=$query->result();
                                        ?>
                                        <select style="width: 100%" name="section" class="select2-me" >
                                            <option value="">Select a section</option>
                                            <?php 
                                                foreach ($query as $value) {
                                                    ?><option  <?php 
                                                           if($this->form->value('section')==$value->sid){
                                                               echo "selected";
                                                           }
                                                        ?> value="<?php echo $value->sid ?>"><?php echo $value->class." - ".$value->section ?></option>
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
                        
                        <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">No of Times</label>
                                    <div class="col-sm-10">
                                        <input type="text" onblur="fetch_timings();" id="noof_times" name="noof_times" value="<?php echo $this->form->value('noof_times');  ?>" class="form-control" />
                                        <span id="noof_times_err" style=" color: red">
                                            <?php
                                               echo $this->form->error('noof_times');   
                                            ?>
                                        </span>        
                                    </div>
                        </div>  
                        
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="submit" name="submit" value="Create Time Table" class="btn btn-primary" />
                        </div>
                    </form>   
                </div>
            </div>
        </div>
     </div>       
</div>

<?php
$this->load->view('structure/footer');
?>