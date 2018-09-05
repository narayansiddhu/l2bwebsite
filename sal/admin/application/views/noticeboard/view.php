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
                            <a href="<?php echo base_url(); ?>">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">View Notice Board</a>
                        </li>
                    </ul>
            </div>
            
            <div class="box box-bordered box-color">
        
                <div class="box-title">
                    <h3><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Notifications</h3>
                    <?php
                      if($this->session->userdata("staff_level")>=8){
                          ?>
                    <div class="actions">
                                <a  style="color: #386ee0; background-color: white" href="<?php echo base_url() ?>index.php/Notices" class="btn">
                                    <i class="fa fa-plus"></i>&nbsp;Manage Notifications
                                </a>
                            </div>              
                          <?php
                      }
                    ?>
                </div>
        
                <div  class="box-content nopadding">
                    <table class="table table-hover table-nomargin  table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th >Title</th>
                                <th >Description</th>
                                <th>Posted On</th>
                                <th>Issued By</th>                                
                            </tr>
                        </thead> 
                        <tbody>
                            <?php
                             if(sizeof($result)>0){
                                 $i =1;
                                 foreach($result as $value){
                                 ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                         <td><?php echo $value->title ?></td>
                                        <td><?php echo $value->description ?></td>
                                        <td><?php echo date("d-m-y H:i ",$value->timestamp); ?></td>
                                        <td><?php echo $value->staff_name; ?></td>
                                    </tr>
                                 <?php
                                    }
                             }else{
                                 ?>
                            <tr>
                                <td colspan="5" style=" text-align: center; color: red">No Records Found.</td>
                            </tr>
                                 <?php
                             }
                              ?>
                        </tbody>
                    </table>
                </div> 
                
                
            </div>
                   <div id='error_msg' class="box">
                       
                   </div>
        </div>
    </div>
</div>

<?php
$this->load->view('structure/footer');
?>