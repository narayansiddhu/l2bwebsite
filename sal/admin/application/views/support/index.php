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
                        <a href="<?php echo base_url(); ?>index.php/students/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Support</a>
                    </li>
                </ul>
            </div>
            
            <?php
            if(strlen($this->session->userdata('Query_Add'))>0 ){
                ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                        
                    <button type="button" class="close" data-dismiss="alert">×</button>
                     <?php echo $this->session->userdata('Query_Add'); ?>
                    </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(7000).fadeOut();
                    </script>
               <?php
                $this->session->unset_userdata('Query_Add');
            }
            ?>
            
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i>Support Query</h3>
                                <div class="actions">
                                    <a  style=" background-color: white; color: #368EE0" class="btn btn-primary" href="<?php echo base_url()  ?>index.php/Support/Add_query"><i class="fa fa-plus"></i>&nbsp;Add Query</a>  
                                </div>
                </div>

            <div class="box-content nopadding">
                <table class="table table-hover datatable table-nomargin">
                    <thead>
                        <tr>
                            <th>S No</th>
                            <th>Title</th>
                            <th>Query</th>
                            <th>Posted On</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $query ="SELECT * FROM `support_queries` where   iid = '".$this->session->userdata("staff_Org_id")."' AND userid='s".$this->session->userdata('staff_id')."' ";
                          $query = $this->db->query($query);
                          if($query->num_rows()>0){
                              $query = $query->result();
                              $i=1;
                              foreach($query as $value){
                                ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $value->title  ?></td>
                                    <td><?php echo substr($value->query, 0, 50)  ?></td>
                                    <td><?php echo date("d-m-y H:i",$value->time);  ?></td>
                                    <td><?php 
                                        switch ($value->status) {
                                            case 1:echo "Submitted";
                                                break;
                                            case 2:echo "Processing";
                                                break;
                                            case 3:echo "Closed";
                                                break;
                                            default:
                                                break;
                                        }
                                    ?></td>
                                    <td>
                                        <a href="<?php echo base_url() ?>index.php/support/view_query/<?php echo $value->qid ?>"><i class="fa fa-eye"></i></a>
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