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
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exam/view">Manage Exams</a>
                </li>                
            </ul>
        </div>
               
    
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Exam's List</h3>
                                <div class="actions">
                                    <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/exam/add" class="btn btn-primary" ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Create New</a>
                                </div>
                </div>
            <div class="box-content nopadding">
                <table class="table table-hover table-nomargin">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>exam Name</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        $query = $this->db->query("SELECT * FROM `examinations` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'");
                        if ($query->num_rows() > 0) {
                            $i=1;
                            foreach ($query->result() as $row) {
                               ?>
                        <tr id="tr_<?php echo $row->id; ?>" >
                            <td id="sno_<?php echo $row->id; ?>"><?php echo $i++; ?></td>
                            <td><?php echo $row->exam; ?></td>
                            <td><?php echo date('d-m-y',$row->startdate); ?></td>
                            <td><?php echo date('d-m-y',$row->enddate); ?></td>
                            <td>
                                <?php 
                                   if($row->status==1){
                                       echo "Active";
                                   }else{
                                       echo "In-Active";
                                   }

                                ?>
                            </td>

                            <td>
                                 <?php
                                  if($row->status==1){
                                      ?>
                                      <a href="<?php echo base_url(); ?>index.php/exam/view_settings/<?php echo  $row->id ?>" class="btn " rel="tooltip" title="" data-original-title="View Setting's">
                                           <i class="fa fa-eye" aria-hidden="true"></i>
                                       </a> &nbsp;&nbsp;
                                       <a href="<?php echo base_url(); ?>index.php/exam/settings/<?php echo  $row->id ?>" class="btn " rel="tooltip" title="" data-original-title="Edit Setting's" >
                                           <i class="fa fa-cogs" aria-hidden="true"></i>
                                       </a> &nbsp;&nbsp;
                                       <a href="<?php echo base_url(); ?>index.php/exam/assign/<?php echo  $row->id ?>" class="btn"  rel="tooltip" title="" data-original-title="Re-Assign Exam" >
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                       &nbsp;&nbsp;
                                       <a  onclick="deactivate('<?php echo $row->id; ?>');"  class="btn">
                                            <i class="fa fa-toggle-off" aria-hidden="true"></i>
                                        </a>
                                    <?php
                                  }else{
                                      ?>
                                      <a onclick="activate('<?php echo $row->id; ?>');" class="btn">
                                            <i class="fa fa-toggle-on" aria-hidden="true"></i>
                                        </a>
                                            <?php
                                  }

                                 ?>

                            </td>
                        </tr>
                               <?php
                            }
                        }else{
                            ?>
                        <tr>
                            <td colspan="5">No Exam's Created</td>
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

<script>

   function activate(value){
       sno=$('#sno_'+value).html();
      
       if(value.length>0){
          setState('tr_'+value,'<?php echo base_url() ?>index.php/ajax/active_exam','examid='+value+'&serial='+sno);  
       }
   }
   
   function deactivate(value){
     
      sno=$('#sno_'+value).html();
     
        if(value.length>0){
           setState('tr_'+value,'<?php echo base_url() ?>index.php/ajax/inactive_exam','examid='+value+'&serial='+sno);  
       }
   }

</script>
   
<?php
$this->load->view('structure/footer');
?>
