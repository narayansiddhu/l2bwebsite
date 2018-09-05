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
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Manage Fee Concessions</a>
                </li>
            </ul>

    </div>     
    <?php
        if(strlen($this->session->userdata('Concession_adding_sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                
                 <?php echo $this->session->userdata('Concession_adding_sucess'); ?>
                </div>
                <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(4000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('Concession_adding_sucess');
        }
        ?>
            <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Manage Fee Concessions</h3> 
                            <div class="actions">
                                <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/fee/Add_concessions" class="btn btn-primary"><i class="fa fa-plus"></i> Add Concession</a>
                            </div>
                    </div>
                    <div class="box-content nopadding">                                
                        <table class="table table-hover table-nomargin datatable  table-bordered" style=" width: 100%;">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Assigned By</th>
                                    <th>Reason</th>
                                    <th>Assigned On</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q="SELECT c.cid,c.time,c.reason , s.name as staff_name , st.name as stud_name, (select sum(amount)  from fee_concession WHERE conc_id = c.cid ) as total, se.name as sec_name , cl.name as cls_name FROM concessions c JOIN staff s ON c.staff = s.id JOIN student st ON c.student = st.student_id JOIN section se ON se.sid=st.section_id JOIN class cl ON se.class_id=cl.id WHERE c.iid = '".$this->session->userdata('staff_Org_id')."' ";
                                $q =$this->db->query($q);
                                 if($q->num_rows()>0){
                                    $q =$q->result();$i=1;
                                    foreach($q as $value){
                                        ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value->stud_name; ?></td>
                                    <td><?php echo $value->cls_name."<br/>".$value->sec_name ?></td>
                                    <td><?php echo $value->staff_name; ?></td>
                                    <td><?php echo $value->reason; ?></td>
                                    <td><?php echo date('d-m-y',$value->time); ?></td>
                                    <td><?php echo $value->total; ?></td>
                                    <td>
                                        <a href="<?php echo base_url() ?>index.php/fee/view_concession/<?php echo $value->cid ?>" class="btn  "><i class="fa fa-eye"></i></a>
                                        <a href="<?php echo base_url() ?>index.php/fee/edit_concession/<?php echo $value->cid ?>" class="btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    </td>
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
<?php
$this->load->view('structure/footer');
?>