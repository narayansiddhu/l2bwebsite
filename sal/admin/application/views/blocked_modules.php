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
                                <a href="<?php echo base_url(); ?>">Home</a>
                                 <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </li>
                            <li>
                                <a href=""><?php echo $module ?></a>
                            </li>
                        </ul>

            </div>
            <br/><br/><br/>
            <div class="box" >
                <div class="col-sm-offset-2 col-sm-8" style=" border: 1px solid #cccccc; border-radius: 6px; color: red; padding: 15px;">
                    <h4 style=" text-align: center">** <?php echo $module ?> Is Not Enabled In Your Package <br/><br/> Please Contact Your Representative Sales Person /  Support Team For This Module  </h4>
       
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
