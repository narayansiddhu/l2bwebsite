<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium= unserialize(medium);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/timetable/view/">Manage Time Table</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                
            </ul>
    </div>             
<?php
    if(strlen($this->session->userdata('delete_time_table'))>0 ){
        ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('delete_time_table'); ?>
                </div>
                <script>
                         $("#successMsg").fadeIn();
                         $("#successMsg").delay(2000).fadeOut();
                    </script>
       <?php
        $this->session->unset_userdata('delete_time_table');
    }
    ?>
            
    <div id="view_timetable" class="box box-bordered box-color">
                <div class="box-title">
                        <h3>View Timetable</h3> 
                </div>
                <div class="box-content nopadding">
        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
            <thead>
                <tr>
                  
                    <th>Class</th>
                    <th>Section</th>
                    <th>Medium</th>
                    <th>Total Classes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                 <?php
                 $q="SELECT s.sid,s.name as sec_name ,c.medium, c.name as cls_name ,t.* FROM section s  JOIN class c ON s.class_id=c.id LEFT JOIN timings t ON t.section=s.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY t.tid  DESC";
                    $q=$this->db->query($q);
                    $q=$q->result();
                    $i=1; $weekdays = unserialize (Week_days);
                    foreach ($q as $value) {

                        ?>
                        <tr>
                            <td><?php echo $value->cls_name ?> </td>
                            <td> <?php echo $value->sec_name ?></td>
                            <td> <?php echo $medium[$value->medium] ?></td>
                            <td><?php 
                            if($value->classes != NULL){
                               echo $value->classes; 
                            }else{
                                echo "--";
                            }
                              ?></td>
                            
                            <td>
                                <?php
                                if($value->classes != NULL){
                               ?>
                                <a class="btn btn-primary" style=" padding-top: 10px; float: left; margin-left: 5px; height: 34px;" href="<?php echo base_url(); ?>index.php/timetable/view/<?php echo $value->tid ?>"  rel="tooltip" title="" data-original-title="View Time Table" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                               <a class="btn btn-primary" style=" padding-top: 10px; float: left; margin-left: 5px; height: 34px;" href="<?php echo base_url(); ?>index.php/timetable/delete/<?php echo $value->tid ?>"  rel="tooltip" title="" data-original-title="Delete Time Table"   ><i class="fa fa-trash" aria-hidden="true"></i></a>
                               <a class="btn btn-primary" style=" padding-top: 10px; float: left; margin-left: 5px; height: 34px;" target="blank" href="<?php echo base_url(); ?>index.php/timetable/pdf_print/<?php echo $value->tid ?>"  rel="tooltip" title="" data-original-title="Print Time Table" ><i class="fa fa-print" aria-hidden="true"></i></a>
                               <?php
                            }else{
                                ?>
                               <input min="1" type="number" id='periods_<?php echo $value->sid ?>' name="periods" placeholder="Enter No of periods to create" class="form-control" style=" float: left; width: 65%;" />
                               <a  class=" btn btn-primary " onclick="create_tt('<?php echo $value->sid ?>');" style=" padding-top: 10px; float: left; margin-left: 10px; height: 34px;"   rel="tooltip" title="" data-original-title="Create Time Table" ><i class="fa fa-plus" aria-hidden="true"></i></a>
                               <span  style=" font-size: 11px; color: red;" id='error_<?php echo $value->sid ?>'></span>
                               <?php
                            }?>
                             </td>
                        </tr>
                       <?php 
                    }
                 ?>
            </tbody>
        </table>
                    <script>
                         function create_tt(val){
                             $('#error_'+val).html(""); 
                           n =$('#periods_'+val).val();  
                             if(n.length==0){
                               $('#error_'+val).html("<br/>&nbsp;Enter No Of Classes");  
                             }else{
                                 if(n<1){
                                       $('#error_'+val).html("<br/>&nbsp;Enter Valid Classes");  
                                 }else{
                                       setState('error_'+val,'<?php echo base_url() ?>index.php/timetable/step2','stdsection='+val+'&periods='+n);
                                     //<?php echo base_url(); ?>index.php/timetable/create_step2
                                     // alert(n);
                                 }
                                
                             }
                         }
                        </script>
                </div>
    </div>
        
        </div>
    </div>
</div>
<script type="text/javascript">

    function get_class_sections(class_id) {
        
      if(class_id.length!=0){
         setState('section_selector_holder','<?php echo base_url() ?>index.php/students/fetch_section','class='+class_id); 
      }       
    }
    
    function fetch(){
    
     cl = $('#class_id').val();
    sect = $('#section_selector_holder').val(); 
   
      
    if( (cl.length!=0) && (sect.length!=0) ){
       setState('view_timetable','<?php echo base_url() ?>index.php/timetable/fetch_timetable','class='+cl+'&section='+sect); 
     } 
      
    }
    
    </script>
<?php
$this->load->view('structure/footer');
?>