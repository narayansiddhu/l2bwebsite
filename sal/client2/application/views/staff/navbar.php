<?php
   if($this->session->userdata('staff_level')>3){
      ?>
<div class="page-header">
        <div class="pull-left">
            <h1 style=" color:#0c4472">Staff Management</h1>           
        </div>
        <div class="pull-right">
            <ul class="minitiles">
                <li class="teal">
                    <a  href="<?php echo base_url(); ?>index.php/staff/Add_staff" rel="tooltip" title="" data-original-title="Add Staff" >
                      <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </a>
                </li>
                
                <li class="lime">
                    <a  href="<?php echo base_url(); ?>index.php/staff/View_staff" rel="tooltip" title="" data-original-title="View staff" >
                     <i class="fa fa-eye" aria-hidden="true"></i>  
                    </a>
                </li>
                <li class="orange">
                    <a  href="<?php echo base_url(); ?>index.php/staff/View/Teacher" rel="tooltip" title="" data-original-title="Faculty Members" >
                      <img width="80%" height="80%" src="<?php echo assets_path ?>/img/incharges.png">
                    </a>
                </li>
                <li class="lightgrey">
                    <a  href="<?php echo base_url(); ?>index.php/staff/View/Accountants" rel="tooltip" title="" data-original-title="Accounts Staff" >
                      <i class="fa fa-user-secret" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="green">
                <a  href="<?php echo base_url(); ?>index.php/staff/View/librarian" rel="tooltip" title="" data-original-title="Library Staff" >
                           <i class="fa fa-book" aria-hidden="true"></i>
                </a>
                </li>
               
            </ul>
        </div>
        
    </div>
<hr>
        <?php
   }else{
      echo " <br/>";
   }
 
?>

