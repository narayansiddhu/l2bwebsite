<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$query=$this->db->query("SELECT  n.* , s.name as staff_name  FROM `notice_board` n JOIN staff s ON n.added_by = s.id WHERE n.iid='".$this->session->userdata('institute_id')."' AND n.for='2' AND n.status=1 AND n.expiry > ".time()."  ");

?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            
            
            <br/>
            
            <div class="box ">
                   
                  <div class="col-sm-1">
                      &nbsp;
                  </div>
                  
                    <div class="col-sm-10">
            
           <ul class="breadcrumb">
                <li>
                    <a href="<?php echo base_url() ?>">Home</a>
                </li>
                <li class="active">
                    <a href="">Student Profiles</a>  
                </li>
            </ul>
               
                        
             <div class="box box-bordered box-color">
        
                <div class="box-title">
                    <h3>Notices</h3>
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
                             if($query->num_rows()>0){
                                 $i =1;
                                 $query=$query->result(); 
                                 foreach($query as $value){
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
                    
                
             
                    </div>
                       
                    <div class="col-sm-1">
                      &nbsp;
                  </div>
            </div>
                
           </div>
    </div>   
</div>

<?php
$this->load->view('structure/footer');
?>

