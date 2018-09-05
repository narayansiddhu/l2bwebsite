<?php

if($this->session->userdata('staff_level')>3){
?>


<div class="page-header">
        <div class="pull-left">
            <h1 style=" color:#0c4472">Student-parent Management</h1>
            
        </div>
        <div class="pull-right">
            <ul class="minitiles">
                <li class="teal">
                    <a  href="<?php echo base_url(); ?>index.php/students/add" rel="tooltip" title="" data-original-title="Add Students" >
                      <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="brown">
                    <a  href="<?php echo base_url(); ?>index.php/students/bulk_add" rel="tooltip" title="" data-original-title="Add Students Bulk" >
                      <i class="fa fa-users" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="lime">
                    <a  href="<?php echo base_url(); ?>index.php/students/View" rel="tooltip" title="" data-original-title="View Students" >
                     <i class="fa fa-eye" aria-hidden="true"></i>  
                    </a>
                </li>
                <li class="orange">
                    <a  href="<?php echo base_url(); ?>index.php/parents/parent_add" rel="tooltip" title="" data-original-title="Create Parent" >
                      <i class="fa fa-male" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="lightgrey">
                    <a  href="<?php echo base_url(); ?>index.php/parents/View" rel="tooltip" title="" data-original-title="View Parents" >
                      <i class="fa fa-user-secret" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="purple">
                    <a  href="<?php echo base_url(); ?>index.php/students/search" rel="tooltip" title="" data-original-title="Add Students" >
                      <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </li>
                
            </ul>
        </div>
        
    </div>

<?php
}

?>
<br/>