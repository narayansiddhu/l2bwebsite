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
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/view">Manage Exams</a>
                </li>                
            </ul>
        </div>
              
        <div class="box box-bordered box-color">
            <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Exam's List</h3>
                <div class="actions">
                    <a  href="<?php echo base_url(); ?>index.php/exams/add" class="btn btn-primary" ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Create New</a>
<!--                     <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/exams/progress_report" class="btn btn-primary" ><i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;Progress Report</a>-->

                </div>
            </div>
            <div class="box-content nopadding">
                <table class="table table-hover table-bordered datatable table-striped table-nomargin">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>exam Name</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Exam Type</th>
                            <th>Status</th>
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
                            <td><a rel="tooltip" title="" data-original-title="View Results" href="<?php echo base_url() ?>/index.php/exams/view_settings/<?php echo $row->id ?>"><?php echo $row->exam; ?></a>
                                </td>
                            <td><?php echo date('d-m-y',$row->startdate); ?></td>
                            <td><?php echo date('d-m-y',$row->enddate); ?></td>
                            <td><?php 
                           // echo $row->type;
                                 switch($row->type){
                                    case 1 : echo "Descriptive";break;
                                    case 2 : echo "Multiple Choice";break;
                                    case 3 : echo "Daily Test";break;
                                    case 4 : echo "Formative";break;
                                     case 5: echo "Summative";break;
                                      case 6 : echo "Online";break;
                                 }                            
                            ?></td>
                            
                            <td>
                                <?php 
                                   if($row->status==1){
                                       echo "Active";
                                   }else{
                                       echo "In-Active";
                                   }

                                ?>
                            </td>

<!--                            <td>
                                <a href="<?php echo base_url(); ?>index.php/exams/view_settings/<?php echo  $row->id ?>"  rel="tooltip" title="" data-original-title="View Setting's">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a> 
                                       
                     
                                 <?php
                                 if($row->startdate > time()){
                                     if($row->status==1){
                                      ?>
                                       <a href="<?php echo base_url(); ?>index.php/exams/assign/<?php echo  $row->id ?>"   rel="tooltip" title="" data-original-title="Re-Assign Exam" >
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                       <a  onclick="deactivate('<?php echo $row->id; ?>');"  >
                                            <i class="fa fa-toggle-off" rel="tooltip" title="" data-original-title="De-Activate Exam" aria-hidden="true"></i>
                                        </a>
                                    <?php
                                  }else{
                                      
                                      ?>
                                      <a onclick="activate('<?php echo $row->id; ?>');" rel="tooltip" title="" data-original-title="Activate Exam" >
                                            <i class="fa fa-toggle-on" aria-hidden="true"></i>
                                        </a>
                                            <?php
                                  }
                                 }else{
                                     
                                      
                                     ?>
                                <a rel="tooltip" title="" data-original-title="View Results" href="<?php echo base_url() ?>/index.php/exams/results/<?php echo $row->id ?>"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                       <?php
                                       if($row->type==2){
                                         ?>
                                        <a  href="<?php echo base_url() ?>/index.php/exams/add_marks/<?php echo $row->id ?>" rel="tooltip" title="" data-original-title="Add Exam Marks" >
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        </a>
                                            <?php 
                                      }
                                 }
                                 
                                 ?>

                            </td>-->
                        </tr>
                               <?php
                            }
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
