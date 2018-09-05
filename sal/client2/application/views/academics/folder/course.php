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
                    <a href="">Course structure Of <?php echo $section_data->class ." - ".$section_data->section ?></a>
                </li>
            
        </div>
    <?php
      $query="SELECT c.cid,s.subject,st.name,sec.name as section FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid WHERE sec.sid='".$section_data->sid."' ORDER BY sec.sid , s.sid ASC";
      $query=$this->db->query($query);
    ?>
    <div class="col-sm-12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>Course Structure Of <?php echo $section_data->class ." - ".$section_data->section ?> </h3>
                
            </div>
            <div class="box-content nopadding">
                <div class='form-horizontal form-bordered'>
                    <table class="table table-hover table-nomargin " style=" width: 100%" >
                        <thead>
                            <th>&nbsp;</th>
                            <th>Subject</th>
                            <th>Faculty Name</th>
                        </thead>
                        <tbody>
                            <?php
                            $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level'=>1 );
                            $staff = $this->db->get_where('staff', $credential);
                            $staff=$staff->result();
                              $subjects=$this->db->query("SELECT * FROM `subjects` WHERE iid=1")->result();
                              foreach ($subjects as $value) {
                                  ?>
                            <tr>
                                <td><input type="checkbox" name="subject_<?php echo $value->sid  ?>" /></td>
                                <td><?php echo $value->subject  ?></td>
                                <td>
                                    <select class='select2-me' style="width:50%;" id="select_<?php echo $cid ?>" >
                                            <option value="">Select A Teacher</option>
                                             <?php
                                             
                                             foreach($staff as $val){
                                                ?>
                                                <option value="<?php echo $val->id ?>"><?php echo $val->name ?></option>
                                              <?php
                                             }

                                             ?>
                                    </select>
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
