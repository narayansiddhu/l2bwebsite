<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    
    <?php $this->load->view('attendance/navbar');  ?>
   
    <div class="col-sm-12">
           
            
            
            <div class="row">
                                        
                <ul style="  width: 100%" class="stats">

                    <li style=" width: 23%" class='orange'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="details">
                                    <span class="big"><?php  echo "0"; ?></span>
                                    <span class="value">No Of Students</span>
                            </div>
                    </li>
                    
                </ul>
            </div>
       
       
     </div>
</div>
<?php
$this->load->view('structure/footer');
?>