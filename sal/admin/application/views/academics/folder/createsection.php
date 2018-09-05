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
                    <a href="<?php echo base_url(); ?>index.php/academics/sections">Section</a>
                </li>
            
        </div>
    <?php
    if(strlen($this->session->userdata('Section_add_Sucess'))>0 ){
        ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success!</strong>
             <?php echo $this->session->userdata('Section_add_Sucess'); ?>
            </div>
        <script>
                $("#successMsg").fadeIn();
		$("#successMsg").delay(7000).fadeOut();
           </script>
       <?php
        $this->session->unset_userdata('Section_add_Sucess');
    }
    ?>
    <div class="col-sm-12">
        <div class="box">
        <div class="box-content nopadding">
           <div class="row">
               
               <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Create Section's</h3> 
                            </div>
                            <div class="box-content nopadding ">    
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/academics/Add_section" method="post" enctype="multipart/form-data"  >
                                
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Class Name</label>
                                            <div class="col-sm-10">
                                                <select name="class_name" id="class_name" class='select2-me' style="width:100%;" onclick="cls_select();" onselect="cls_select();" >
                                                    <option value="">Select A Class</option>
                                                     <?php
                                                     $credential = array('iid' =>$this->session->userdata('staff_Org_id'));
                                                     $query = $this->db->get_where('class', $credential);
                                                       $query=$query->result();
                                                       foreach($query as $val){
                                                           ?>
                                                     <option value="<?php echo $val->id ?>"  <?php
                                                   if($this->form->value('class_name')== $val->id){
                                                       echo "selected";
                                                   }
                                                  ?> ><?php echo $val->name ?></option>
                                                        <?php
                                                       }
                                                       
                                                     ?>
                                                </select>  
                                            </div>
                                    </div>
                                    
                                   <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Section Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="Sectionname" placeholder="Enter Section Name" class="form-control" value="<?php echo $this->form->value('Sectionname') ?>" > 
                                                <span style=" color: red">
                                                        <?php
                                                           echo $this->form->error('Sectionname');   
                                                        ?>
                                                    </span>        
                                            </div>
                                    </div> 
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Class Teacher</label>
                                            <div class="col-sm-10">
                                                <select name="class_teacher"  class='select2-me' style="width:100%;" onclick="cls_select();" onselect="cls_select();" >
                                                    <option value="">Select A Class</option>
                                                     <?php
                                                     $credential = array('iid' =>$this->session->userdata('staff_Org_id') ,'level '=>1 );
                                                     $query1 = $this->db->get_where('staff', $credential);
                                                     $query1=$query1->result();
                                                     foreach($query1 as $val){
                                                        ?>
                                                        <option value="<?php echo $val->id ?>" <?php
                                                   if($this->form->value('class_teacher')== $val->id){
                                                       echo "selected";
                                                   }
                                                
                                                  ?>  ><?php echo $val->name ?></option>
                                                      <?php
                                                     }
                                                       
                                                     ?>
                                                </select>  
                                            </div>
                                    </div>
                                    
                                        
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                            <input type="submit"  name="submit" value="Create Section" class="btn btn-primary" />
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
                
           </div>
        </div>
        </div>
    </div>
        
    <div class="col-sm-12">
        <div class="box">
           <div class="box box-bordered box-color">
                    <div class="box">
                        <div class="box-title">
                            <h3>List Of Sections</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Section</th>
                                        <th>Class</th>
                                        <th>Incharge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                      $query=$this->db->query("SELECT s.sid,s.name as section,s.cls_tch_id,c.name as class,st.name as staff FROM `section` s JOIN class c ON s.class_id=c.id LEFT JOIN staff st ON s.cls_tch_id=st.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC ");
                                      $query=$query->result();
                                      $i=1;
                                      foreach($query as $value){
                                          ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td> 
                                        <td><?php echo $value->section ?></td> 
                                        <td><?php echo $value->class ?></td> 
                                        <td><?php echo $value->staff ?></td> 
                                        <td>
                                            <a class="btn" href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="Edit Section" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                             <a class="btn" href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Section Info" ><i class="fa fa-eye" aria-hidden="true"></i></a>
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
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
