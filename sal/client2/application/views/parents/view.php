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
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Manage Parents</a>
                </li>
            </ul> 
        </div>
    
    <?php
        if(strlen($this->session->userdata('parent_add_Sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $this->session->userdata('parent_add_Sucess'); ?>
                </div>
            <script>
                 $("#successMsg").fadeIn();
                 $("#successMsg").delay(2000).fadeOut();
            </script>
            <?php
             $this->session->unset_userdata('parent_add_Sucess');
         }
         ?> 
   
        <div class="box box-bordered box-color">
            <div class="box-title">
                    <h3><i class="fa fa-male"></i>Parents List</h3>
                    <div class="actions"> 
                        <a  class="btn btn-primary" href="<?php echo base_url() ?>index.php/parents/parent_add"   rel="tooltip" title="" data-original-title="Create Parent" ><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;Create Parent</a>
<!--                     <a style="color: #ffffff ; background-color:  #318EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/parents/bulk"   rel="tooltip" title="" data-original-title="Create Bulk Parents" ><i class="fa fa-users"></i></a>
                    -->
                    </div>
            </div>
                        
            <div class="box-content nopadding">
                <table class="table  datatable table-hover table-nomargin">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>E-mail</th>
                            <th>Password</th>
                            <th>Address</th>
                            <th>Profession</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
//                        if($page==0){
//                            $i=1;
//                        }else{
//                           // $i=$page*$per_page+1;
//                        }
                        if(sizeof($results)  >0){
                             foreach($results as $row) {

                                 ?>
                                <tr>
                                 
                                    <td><?php echo $row->name;?></td>  
                                    <td><?php echo $row->phone;?></td>  
                                    <td><?php echo $row->email;?></td>  
                                    <td><?php echo $row->password;?></td>  
                                    <td><?php echo $row->address;?></td>  
                                    <td><?php echo $row->profession;?></td>  
                                    <td>
                                        <a href="<?php echo base_url() ?>index.php/Parents/view_parent/<?php echo $row->parent_id  ?>" rel="tooltip" title="" data-original-title="View Parent" class="btn btn-mini"><i class="fa fa-eye"></i></a>
                                        <a href="<?php echo base_url() ?>index.php/Parents/link_students/<?php echo $row->parent_id  ?>" rel="tooltip" title="" data-original-title="Link Student Account's" class="btn btn-mini"><i class="fa fa-link"></i></a>
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