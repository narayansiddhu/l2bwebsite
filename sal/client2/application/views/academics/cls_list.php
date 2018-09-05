<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
 $q="SELECT c.id,c.name,c.medium, s.sid ,s.name as sec_name , (select count(*) from student st where st.section_id=s.sid ) as students FROM `class` c JOIN section s ON s.class_id = c.id WHERE c.iid='".$this->session->userdata('staff_Org_id')." '";
$q = $this->db->query($q);
$q= $q->result();
$cls_array = array();
foreach ($q as $value) {
   if(isset($cls_array[$value->id])){
       $cls_array[$value->id]['section'][$value->sid] = array('sec_id'=>$value->sid,'sec_name'=>$value->sec_name,'students'=>$value->students);
   }else{
         $cls_array[$value->id]= array('cls_id'=>$value->id,'cls_name'=>$value->name,'medium'=>$value->medium);
         $cls_array[$value->id]['section'][$value->sid] = array('sec_id'=>$value->sid,'sec_name'=>$value->sec_name,'students'=>$value->students);
   }
    
}

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
                        <a href="">Manage Class</a>
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
            <h3><i class="fa fa-sitemap"></i>Class List</h3>
            <div class="actions">
                <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/academics/create_cls" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Create Class</a>
            </div>
        </div>
        <div  class="box-content nopadding">
            <table class="table datatable table-hover table-nomargin  table-bordered"  style="width: 100%;">
                <thead>
                    <tr>
                        <th  >Class Name</th>
                        <th>Medium</th>
                        <th >Sections</th>
                        <?php
                        if($this->session->userdata('staff_level')>3){
                         ?>
                        <th>Actions</th>
                         <?php
                        }
                        ?>
                        
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    
             $medium = unserialize (medium);
                    foreach ($cls_array as $value) {
                        ?>
                    <tr>
                        <td><?php echo $value['cls_name'] ?></td>
                        <td><?php echo $medium[$value['medium']] ?></td>
                        <td>
                            <?php 
                                $i=1;$max= sizeof($value['section']);
                                foreach ($value['section'] as $sec) {
                                    ?>
                            <a href="<?php echo base_url(); ?>index.php/academics/view_section/<?php  echo $sec['sec_id'];  ?>"  rel="tooltip" title="" data-original-title="View section Info Of <?php echo $sec['sec_name']; ?>" ><?php echo $sec['sec_name']; ?> &nbsp;&nbsp;(<?php echo $sec['students']; ?>)</a>
                                <?php
                                    if($i<$max){
                                         echo ",";
                                    }
                                   
                                    $i++;
                                }
                            ?>
                        </td>
                        
                            <?php
                            if($this->session->userdata('staff_level')>3){
                             ?>
                            <td>
                                <a class="btn" href="<?php echo base_url(); ?>index.php/academics/edit_class/<?php echo $value['cls_id'] ?>" rel="tooltip" title="" data-original-title="Edit Class" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                <a class="btn" href="<?php echo base_url(); ?>index.php/academics/sections/<?php echo $value['cls_id'] ?>" rel="tooltip" title="" data-original-title="Add Section" ><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </td>
                             <?php
                            }
                            ?>
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
    
</div>

    
<?php
$this->load->view('structure/footer');
?>
