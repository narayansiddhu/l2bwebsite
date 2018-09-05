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
                    <a href="<?php echo base_url(); ?>index.php/Alerts">View Alerts</a>
                </li>
                
            </ul>
        </div>
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Alert History </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style=" width: 5%" >S.no</th>
                                <th style=" width: 10%">Regarding</th>
                                <th style=" width: 25%">Staff</th>
                                <th style=" width: 35%">Message</th>
                                <th style=" width: 15%">Time</th>
                                <th style=" width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=$this->db->query("SELECT a.id,a.regarding,a.message,a.time,s.email,s.name FROM `alerts` a JOIN staff s ON a.staff_id=s.id WHERE a.iid='".$this->session->userdata('staff_Org_id')."'  ORDER BY a.time DESC");
                             $query=$query->result();
                             $i=1;
                             foreach($query as $details){
                                 ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php
                                            switch($details->regarding){
                                                    case 1 :echo "Alerts";break;
                                                    case 2 :echo "Results";break;
                                                    case 3 :echo "Attendance";break;
                                                    case 4 :echo "Login Credentials";break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo "<i class='fa fa-user'></i>&nbsp;".$details->name."<br/>"."<i class='fa fa-envelope' aria-hidden='true'></i>&nbsp;".$details->email ?>
                                        </td>
                                        <td><?php  echo $details->message ?></td>
                                        <td><?php echo date("d-m-Y H:i",$details->time); ?></td>
                                        <td><a href="<?php echo base_url() ?>/index.php/alerts/view_campign/<?php echo $details->id ?>">View</a></td>
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


