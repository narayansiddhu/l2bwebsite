<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <?php $this->load->view('academics/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Cls_structure">Academics</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Section</a>
                </li>
            
        </div>
    <div class="col-sm-12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>Section Details</h3>
            </div>
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered'>
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Class Name</label>
                            <div class="col-sm-10">
                                <label class="form-control"><?php echo $section_data->class  ?></label>
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Section Name</label>
                            <div class="col-sm-10">
                                <label class="form-control"><?php echo $section_data->section  ?></label>
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="textfield" class="control-label col-sm-2">Incharge</label>
                            <div class="col-sm-10">
                                <label class="form-control"><?php echo $section_data->staff_name  ?></label>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>Course Details </h3>
                <div class="actions">
                    <a class="btn" href="<?php echo base_url() ?>index.php/academics/assign_sub?section=<?php echo $section_data->sid  ?>" >Add Subject</a> 
                </div>
            </div>
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered'>
                   <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1105px;">
                       <thead>
                                  <th>S.no</th>
                                  <th>Subject</th>
                                  <th>Faculty Name</th>
                                  <th>Actions</th>
                               </thead>
                       <tbody>
                           <?php
                             $query="SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$section_data->sid."' ORDER BY sec.sid , s.sid ASC";
                             $query=$this->db->query($query);
                             $query=$query->result();$i=1;
                             foreach($query as  $value){
                               ?>
                              <tr id="tr_<?php   echo $value->cid; ?>">
                                       <td id="id_<?php   echo $value->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                   <td style=" width: 15%" ><?php echo $value->subject; ?></td>
                                   <td  style=" width: 40%"><?php if($value->name !=NULL ){
                                                       echo $value->name;
                                                   }else{
                                                       echo "--";
                                                   }?></td>
                                   <td id="td_actions_<?php   echo $value->cid; ?>" >
                                       <?php if($value->name !=NULL ){
                                           ?>
                                       <button  onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Edit Faculty" >
                                           <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }else{
                                           ?>
                                       <button onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Assign Faculty" >
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }?>
                                       
                                       
                                   </td>
                               </tr>   
                              <?php
                             }
                             ?>
                           
                           
                           
                       </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   <script>



function assign_faculty(value){
    //td_actions_
    id='td_actions_'+value;
    setState(id,'<?php echo base_url() ?>index.php/ajax/Load_assign','cid='+value);
}

function save_faculty(value){
    teach=$('#select_'+value).val();
    //id_
    i=$('#id_'+value).html();
    if(teach.length!=0){
      setState('tr_'+value,'<?php echo base_url() ?>index.php/ajax/save_assigned','cid='+value+'&staff='+teach+'&i='+i);  
    }else{
        //error_
        $('#error_'+value).html("<br/>** Please select faculty");
    }
}

</script> 
       
</div>
<?php
$this->load->view('structure/footer');
?>
