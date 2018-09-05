<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$date= $this->input->get("date");
$slot= $this->input->get("slot");
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
                            <a href="">Add Attendance </a>
                        </li>
                    </ul>
                </div> 
                
                <div class="box box-bordered box-color">
                    
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>View Class Attendance Report </h3> 
                    </div>
                    
                    <div class="box-content nopadding"> 
                        <?php
                          $section = $this->db->query("SELECT s.sid ,s.name as sec_name , c.name as cls_name FROM `section` s  JOIN class c ON s.class_id=c.id where s.iid='".$this->session->userdata('staff_Org_id')."'   ORDER BY c.id");
                          $section = $section->result();
                          
                          
                          ?>
                        
                       <table class="table table-hover table-nomargin table-bordered" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th >Class - Section</th>
                                    <th >Actions</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                    foreach ($section as $value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php  echo $i++; ?></td>
                                            <td><?php  echo $value->cls_name ." - ".$value->sec_name  ?></td>
                                            <td><a href="<?php echo base_url() ?>index.php/attendance/attendance_report/<?php echo $value->sid  ?>"><i class="fa fa-eye"></i></a></td>
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