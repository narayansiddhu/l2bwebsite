<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php
if(strlen($this->session->userdata('incharge_add_Sucess'))>0 ){
    ?><br/>
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Success!</strong>
         <?php echo $this->session->userdata('incharge_add_Sucess'); ?>
        </div>
   <?php
    $this->session->unset_userdata('incharge_add_Sucess');
}
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Create Incharges</h3> 
                </div>
                <div class="box-content nopadding"> 
                    <div class='form-horizontal form-bordered' >
                        
                        <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2">Class/Section</label>
                                    <div class="col-sm-10">
                                        <?php
                                           $query=$this->db->query("SELECT s.sid,s.name as section ,c.name as class FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC , s.sid asc");
                                           $query=$query->result();
                                        ?>
                                        <select style="width: 100%" id="section" class="select2-me" >
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
                                        <span id='section_err' style=" color: red">
                                                <?php
                                                   echo $this->form->error('section');   
                                                ?>
                                            </span>        
                                    </div>
                            </div>  
                        
                        <div class="form-group">
                        <label for="textfield" class="control-label col-sm-2">Teacher</label>
                        <div class="col-sm-10">
                            <select  id="staff"   class='select2-me' style="width:100%;" >
                                <option value="">Select A Teacher</option>
                                 <?php
                                 $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                 $query1 = $this->db->get_where('staff', $credential);
                                 $query1=$query1->result();
                                 foreach($query1 as $val){
                                    ?>
                                    <option value="<?php echo $val->id ?>" <?php
                                   if($this->form->value('sub_teacher')== $val->id){
                                       echo "selected";
                                   }
                                  ?>  ><?php echo $val->name ?></option>
                                  <?php
                                 }

                                 ?>
                            </select>  
                             <span id='staff_err'  style=" color: red">
                                   <?php  echo $this->form->error('sub_teacher'); ?>
                            </span>
                        </div>
                        </div>
                        
                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                            <input type="button"  name="submit" value="Create Incharge" onclick="create_incharge();" class="btn btn-primary" />
                            <span id='errors'></span>
                        </div>
                        
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
   function create_incharge(){
       var section=$('#section').val();
       var staff=$('#staff').val();
       count=0;
        if(section.length==0){
            count++;
           $('#section_err').html('Please select section');
       }
       if(staff.length==0){
            count++;
           $('#staff_err').html('Please select section');
       }
       
       if(count==0){
          setState('errors','<?php echo base_url() ?>index.php/staff/add_incharge','section='+section+'&staff='+staff);
       }
       
   }
</script>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Class In-charges List</h3> 
                        <div class="actions">
                            <a class="btn btn-primary" href="<?php echo base_url();  ?>index.php/attendance/add"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Create New</a>
                        </div>
                </div>
                <div class="box-content nopadding"> 
                     <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" >
                         <thead>
                             <tr>
                                 <th>S.no</th>
                                 <th>Class</th>
                                 <th>Section</th>
                                 <th>Incharge</th>
                                 
                             </tr>
                         </thead>
                         <tbody>
                            <?php  
                             $timings=$this->db->query("SELECT i.id,s.name as section,c.name as class,st.name as staff FROM `incharges` i JOIN section s ON i.section =s.sid JOIN class c ON s.class_id=c.id JOIN staff st ON i.staff=st.id WHERE i.iid='".$this->session->userdata('staff_Org_id')."'");
                             if( $timings->num_rows()==0){
                                 ?>
                             <tr><td colspan="5">No Records To display</td></tr>
                              <?php
                             }else{
                                 $timings=$timings->result();
                                 $i=1;
                                 foreach ($timings as $s) {
                                     ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $s->class ?></td>
                                        <td><?php echo $s->section ?></td>
                                        <td><?php echo $s->staff ?></td>
                                    </tr>
                                  <?php
                                 }
                                 
                                 ?>
                             
                               <?php
                             }
                                
                            ?>
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php
$this->load->view('structure/footer');
?>


