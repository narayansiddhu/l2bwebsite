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
                    <a href="<?php echo base_url(); ?>index.php/students/View">View Students</a><i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/students/View/<?php  echo $section->class ?>/<?php  echo $section->section ?>"><?php echo $section->class." , ".$section->section ?></a>
                </li>
                </ul>
        </div>
   
   
     
   <div class="box box-color box-bordered">
   <div class="box-title">
       <h3><i class="fa fa-child"></i>&nbsp;Students List Of <?php echo $section->class." , ".$section->section ?> </h3> 
        <?php
        if($this->session->userdata('staff_level')>3){
            ?>
        <div class="actions" >
            <a style="color: #ffffff ; background-color:  #318EE0 " class="btn btn-primary" href="<?php echo base_url() ?>index.php/academics/view_section/<?php echo $section->class_id ?>/<?php echo $section->sid ?>" target="_blank"  rel="tooltip" title="" data-original-title="Section Info" ><i class="fa fa-info-circle" aria-hidden="true"></i></a>
            <a style="color: #ffffff ; background-color:  #318EE0" class="btn btn-primary" href="<?php echo base_url() ?>index.php/accounts/view/<?php echo $section->class_id ?>/<?php echo $section->sid ?>" target="_blank"  rel="tooltip" title="" data-original-title="Accounts" ><i class="fa fa-money"></i></a>
                 <a style="color: #ffffff ;background-color:  #318EE0 " class="btn btn-primary" target="_blank" href="<?php echo base_url() ?>index.php/Students/print_students/<?php echo $section->class_id ?>/<?php echo $section->section ?>"  rel="tooltip" title="" data-original-title="print"  ><i class="fa fa-print" aria-hidden="true"></i></a>
                <a style="color: #ffffff ;background-color:  #318EE0 " class="btn btn-primary" href="<?php echo base_url() ?>index.php/Students/Send_sms/<?php echo $section->class_id ?>/<?php echo $section->section ?>"  rel="tooltip" title="" data-original-title="Send SMS"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
        </div>
             <?php
           }
           ?>
        
   </div>
       <div class="box-content nopadding"  style=" max-height: 450px; overflow-y: scroll" >
                        <table class="table table-hover table-nomargin dataTable table-bordered">
                                <thead>
                                        <tr>
                                                <th >Roll No</th>
                                                <th>Student name</th>
                                                <th >Userid</th>
                                                <?php
                                               if($this->session->userdata('staff_level')>3){
                                                   ?>
                                                    <th >password</th>
                                                    <?php
                                                  }
                                                  ?>
                                               
                                                <th>Gender</th>
                                                <th >mobile</th>
                                                <th >E-mail</th>
                                                <th>Actions</th>                                                
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i=1;
                                       foreach ($students as $value) {
                                         ?>
                                        <tr>
                                            <td><?php echo $value->roll ?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><?php echo $value->userid ?></td>
                                            <?php
                                               if($this->session->userdata('staff_level')>3){
                                                   ?>
                                                    <td><?php echo $value->password ?></td>
                                                    <?php
                                                  }
                                                  ?>
                                            
                                            
                                            <td><?php
                                           // echo $value->sex;
                                            if($value->sex==1)
                                                        {
                                                            echo "Male";
                                                        }else{
                                                            echo "Female";
                                                        }
                                                        ?></td>
                                            <td><?php echo $value->phone ?></td>
                                            <td><?php echo $value->email ?></td>
                                            
                                            <td>
                                                <a href="<?php echo base_url() ?>index.php/Students/view_details/<?php  echo $value->userid   ?>"  rel="tooltip" title="" data-original-title="View Student Details"  ><i class="fa fa-eye" aria-hidden="true"></i>
</a>
                                                <?php
                                                if($this->session->userdata('staff_level')>3){
                                                 ?>
                                                <a href="<?php echo base_url() ?>index.php/Students/edit/<?php  echo $value->student_id   ?>"  rel="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
</a> 
                                                  <?php
                                                }
                                                ?>
                                                
                                                
                                                
                                            </td>
                                           
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