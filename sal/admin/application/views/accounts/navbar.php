<?php

if($this->session->userdata('staff_level')>3){
    ?>
   <div class="page-header">
        <div class="pull-left">
            <h1 style=" color:#0c4472">Accounts Management</h1> 
        </div>
        <div class="pull-right">
            <ul class="minitiles">
                <li class="orange">
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">
                      <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="teal">
                    <a href="<?php echo base_url(); ?>index.php/accounts/add_record">
                      <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="blue">
                 <a href="<?php echo base_url(); ?>index.php/accounts/search_record">
                      <i class="fa fa-search" aria-hidden="true"></i>
                  </a>
                </li>
            </ul>
        </div>
        
    </div>
   <?php
}else{
    echo "<br/>";
}
?>
