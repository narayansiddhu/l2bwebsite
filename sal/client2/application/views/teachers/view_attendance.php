<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row"> 
    <div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li> 
                        <li>
                            <a href="">attendance</a>
                        </li>
                    </ul>

            </div>
        
          
    
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
