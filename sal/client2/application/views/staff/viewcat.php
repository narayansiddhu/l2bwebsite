<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <?php $this->load->view('staff/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/staff/">Staff</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/staff/View/<?php echo $level ?>">View Staff</a>
                </li>
            </ul>

    </div>
                   
    <div class="col-sm-12">
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i><?php echo $label_head ?></h3>
                </div>
            <div class="box-content nopadding">
                <table class="table table-hover table-nomargin">
                    <thead>
                        <tr>
                        <th>S.no</th>
                        <th>Name</th>
                        <th class="hidden-350">Phone</th>
                        <th class="hidden-480">E-mail</th>
                        <th class="hidden-1024">Password</th>
                        <th class="hidden-480">Sex</th>
                        <th class="hidden-480">Blood Group</th>
                        <th class="hidden-480">qualification</th>
                        <th>Role</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $blood_group = unserialize (blood_groups);$staff_level = unserialize (staff_level);
                        if($page==0){
                            $i=1;
                        }else{
                            $i=$page*$per_page+1;
                        }
                        if(sizeof($results)  >0){
                             foreach($results as $row) {
                                 ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $row->name;?></td>  
                                    <td><?php echo $row->phone;?></td>  
                                    <td><?php echo $row->email;?></td>  
                                    <td><?php echo $row->password;?></td>  
                                    <td><?php 
                                    if($row->sex==1){
                                        echo "Male";
                                    }else{
                                        echo "femaile"; 
                                    }?></td>  
                                    <td><?php echo $blood_group[$row->bloodg];?></td>  
                                    <td><?php echo $row->qualification;?></td>  
                                    <td><?php echo $staff_level[$row->level];?></td>  
                                     <td>
                                       <a href="<?php echo base_url() ?>index.php/staff/view_staff_details/<?php echo  $row->id  ?>"><i class="fa fa-eye"></i></a>
                                       <a href="<?php echo base_url() ?>index.php/staff/edit/<?php echo  $row->id  ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                   </td> 
                                </tr>
                              <?php
                             }
                               if(!is_null($links)){
                                  ?>

                                <tr>
                                    <td colspan="9">
                                        <div class="table-pagination">
                                            <?php echo $links ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                               }   
                         }  else{
                             ?>
                        <tr>
                            <td colspan="9">No Records Found.</td>
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