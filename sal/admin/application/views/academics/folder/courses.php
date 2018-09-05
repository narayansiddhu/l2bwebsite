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
                    <a href="">Course Structure</a>
                </li>
            </ul>
            
    </div>
    <div class="col-sm-12">
    <div class="box box-bordered box-color">
            <div class="box-title">
                    <h3><i class="fa fa-th-list"></i>Course Structure </h3>
            </div>
        <div class="box-content nopadding">
            <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Class - Section</th>
                        <th>Action's</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    echo "SELECT s.sid,s.name as section,c.name as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."'";
                    $query = $this->db->query("SELECT s.sid,s.name as section,c.name as class FROM section s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."'");
                    if ($query->num_rows() > 0) {
                        $i=1;
                        foreach ($query->result() as $row) {
                           ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row->class; ?>&nbsp;-&nbsp;<?php echo $row->section; ?></td>
                        <td><a href="<?php echo base_url() ?>index.php/academics/view_section/<?php echo $row->sid ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                           <?php
                        }
                    }else{
                        ?>
                    <tr>
                        <td colspan="3">No Subjects Created</td>
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
    



    
    
<?php
$this->load->view('structure/footer');
?>
