<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$day_details = getdate($day->day);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box"><br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/staff/Monthly_attendance?month=<?php echo $day_details['mon'] ?>&year=<?php echo $day_details['year'] ?>">Staff Monthly Attendance</a>
                        </li>
                         <li>
                            <a href="">Attendance Of <?php echo date("d-m-Y",$day->day); ?></a>
                        </li>
                    </ul>
            </div> 
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                            <i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;Staff Attendance List On <?php echo date("d-m-Y",$day->day); ?>
                        </h3>
                    <div class="actions">
                        <a  class="btn btn-mini" href="<?php echo base_url() ?>/index.php/staff/edit_attendance/<?php echo $day->id; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Edit </a>
                    </div>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                       <thead>
                           <tr>
                               <th>S.No</th>
                               <th>Name</th>
                               <th>E-mail</th>
                               <th>Status</th>
                           </tr>
                       </thead>      
                       <tbody>
                           <?php
                             $i=1;
                             $query=$this->db->query("SELECT sa.id,sa.status,s.name,s.email FROM `staff_attendance` sa JOIN staff s ON sa.staff=s.id  WHERE sa.date_id='".$day->id."' ");
                             $query=$query->result();
                             foreach ($query as $value) {
                               ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->email ?></td>
                                    <td>
                                        <?php
                                          switch($value->status){
                                             case 0 : echo "--";break;
                                             case 1 : echo "present";break;
                                             case 2 : echo "Absent";break;
                                          }
                                        ?>
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
<script>
  function select_all(){
      var ids=$('#id_values').val();
      arr=ids.split(",");
      for(i in arr){
         
         $("#status_"+arr[i]).val("1");
        
     }
  }
  function unselect_all(){
      var ids=$('#id_values').val();
      arr=ids.split(",");
      for(i in arr){
         $("#status_"+arr[i]).val("2");
        }
  } 
</script>
<?php
$this->load->view('structure/footer');
?>