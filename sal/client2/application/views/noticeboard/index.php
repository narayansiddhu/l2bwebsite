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
            <?php
        if(strlen($this->session->userdata('notice_add_Sucess'))>0 ){
            ?><br/>
            <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                 <?php echo $this->session->userdata('notice_add_Sucess'); ?>
                </div>
               <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(2000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('notice_add_Sucess');
        }
    ?>  
            <div class="box box-bordered box-color">
        
                <div class="box-title">
                    <h3><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;Notifications</h3>
                    <div class="actions">
                        <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/Notices/add_new" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Create Notice</a>
                    </div>
                </div>
        
                <div  class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th >Title</th>
                                <th >Description</th>
                                <th>Status</th>
                                <th >Expires On</th>
                                <th>For</th>
                                <th>Action</th>
                            </tr>
                        </thead> 
                        <tbody>
                            <?php
                              $query=$this->db->query("SELECT  *  FROM `notice_board` WHERE iid='".$this->session->userdata('staff_Org_id')."' AND added_by = '".$this->session->userdata('staff_id')."' ");

                              $query=$query->result();
                              $i=1;
                              foreach($query as $value){
                                 ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                         <td><?php echo $value->title ?></td>
                                        <td><?php echo $value->description ?></td>
                                        <td><?php 
                                                    if($value->status ==1){
                                                        echo "Active";
                                                    }else{
                                                        echo "De-Active";
                                                    }
                                        ?></td>
                                        <td><?php echo date("d-m-y H:i ",$value->expiry); ?></td>
                                        <td><?php 
                                        switch($value->status){
                                            case 1: echo "Staff";break;
                                            case 2: echo "Parent's";break;
                                            case 3: echo "Student's";break;
                                        }
                                        ?></td>
                                        <td><?php 
                                                if($value->status ==1){
                                                       ?>
                                            <a href="#"  rel="tooltip" title="" data-original-title="De-Activate" onclick="deactivate('<?php echo $value->id ?>');"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                                       <?php 
                                                    }else{
                                                         ?>
                                            <a href="#"  rel="tooltip" title="" data-original-title="Activate" onclick="activate('<?php echo $value->id ?>');"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>
                                                       <?php 
                                                    }
                                                    ?> &nbsp;&nbsp;
                                            <a href="<?php echo base_url() ?>index.php/Notices/edit_post/<?php echo $value->id ?>"  rel="tooltip" title="" data-original-title="Edit"  ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        
                                            
                                        </td>
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
<script>
  function deactivate(id){
      alert(" Deactivate  "+id);
      setState('error_msg','<?php echo base_url() ?>index.php/Notices/change_status','id='+id+'&status=0');
         
  } 
  function activate(id){
      setState('error_msg','<?php echo base_url() ?>index.php/Notices/change_status','id='+id+'&status=1');
  }
</script>

<?php
$this->load->view('structure/footer');
?>