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
                    <a href="<?php echo base_url(); ?>index.php/academics/Cls_structure">Class Structure</a>
                </li>
            
        </div>
    
    <div class="col-sm-12">
        
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Class Structure</h3>
                            </div>
                        
                        <div class="box-content nopadding">
                            <table class="table table-hover table-nomargin">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">Class Name</th>                                        
                                        <th style=" width: 36%">Section Name</th>
                                        <th style=" width: 36%" >Class In-charge</th>
                                        <th style=" width: 20%">Action</th>                                             
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                      if(sizeof($results) >0){
                                          $prev="";
                                          foreach ($results as $value) {
                                             if($prev==$value->cls_name){
                                                  ?>
                                                 <tr>
                                                     <td>&nbsp;</td>
                                                    <td><?php echo $value->name ?></td>
                                                    <td><?php if( $value->cls_tch_id=="0")
                                                    {
                                                        echo "Not Assignned";
                                                    }else{
                                                        echo $value->staff_name;
                                                    } ?></td>
                                                    <td>
                                                         <a  href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="Edit Section" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                         <a href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Section Info" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                         <a  href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Timetable" ><i class="fa fa-calendar" aria-hidden="true"></i></a>
                                                         <a  href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Section Info" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                             
                                                    </td>
                                                </tr>
                                                 <?php
                                              }else{
                                               
                                                  ?>
                                                
                                                <tr style=" border: none">
                                        <td><?php echo $value->cls_name ; $j=1;?></td>
                                        
                                        <td style="width: 30%" ><?php echo $value->name ?></td>
                                        <td style="width: 30%"><?php  if( $value->cls_tch_id=="0")
                                        {
                                            echo "Not Assignned";

                                        }else{
                                            echo $value->staff_name;
                                        }
                                                ?></td>
                                        <td>
                                             <a  href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="Edit Section" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                             <a  href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Section Info" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                             <a  href="<?php echo base_url(); ?>index.php/academics/edit_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="Edit Section" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                             <a  href="<?php echo base_url(); ?>index.php/academics/view_section/<?php echo $value->sid ?>" rel="tooltip" title="" data-original-title="View Section Info" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                             
                                        </td>
                                    </tr>
                                            
                                               <?php
                                             }
                                             $prev=$value->cls_name; 
                                          }
                                          
                                      }else{
                                          ?>
                                    <tr><td colspan="6"><p>No Records To Display , Create Class's </p></td></tr>
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
