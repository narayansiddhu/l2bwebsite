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
                    <a href="<?php echo base_url(); ?>index.php//attendance/">Attendance</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php//attendance/view/">View Attendance </a>
                </li>
            </ul>

    </div> 
    
    
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i> Attendance of <?php echo $section->class ?> , <?php echo $section->section ?> </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin" style=" width: 100%">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Day</th>
                                <th>action's</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $query=$this->db->query("SELECT * FROM `attendance_date` WHERE section='".$section->sid."' ORDER BY day ASC ");
                             $query=$query->result();
                             $i=1;
                             foreach($query as $details){
                                 ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d-m-y',$details->day); ?></td>
                                <td><a href="<?php echo base_url(); ?>index.php/attendance/attendance/<?php echo $section->sid ?>/<?php echo $details->id ?>">View/Edit</a></td>
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
