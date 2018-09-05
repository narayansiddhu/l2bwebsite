<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
 
$inst=$this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
$inst =$inst->row();           
$inst_logo =assets_path. "uploads/".$inst->logo;
$inst_name =$inst->name;
$std_img = assets_path. "uploads/dummy_user.png";
$inst_address =$inst->address;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            
        <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home </a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/idcards/">Id Cards </a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Generate Id Cards</a>
                    </li>
                </ul>

        </div>
         
        <div class="box box-color  box-small box-bordered">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-bars"></i>
                                Id Card's Design
                        </h3>
                        
                </div>
            <div class="box-content" style=" padding-top: 25px; padding-bottom: 20px" >
                     <?php
                        $ids=$this->db->query("SELECT * from id_cards ")->result();
                        foreach($ids as $val){
                            ?>
                <div align="center" class="col-sm-4" >
                
                            <?php
                            $bg_img=assets_path. "uploads/".$val->bg_image;
                            $i=$val->css;
                            //<#id_bg_img#>
                            $i=  str_replace("<#id_bg_img#>", $bg_img, $i);
                            $i=  str_replace("<#inst_logo#>", $inst_logo, $i);
                            $i=  str_replace("<#Institute_name#>", $inst_name, $i);
                            $i=  str_replace("<#stud_image#>", $std_img, $i);
                            $i=  str_replace("<#inst_address#>", $inst_address, $i);
                            //<#stud_image#>
                             echo $i; 
                             ?><br/>
                    <a href="<?php echo base_url() ?>index.php/idcards/generate/<?php echo $val->ic_id ?>  " class="btn btn-primary "  >Generate Id cards</a>
                    </div>
                            <?php
                        }
                     ?>
            </div>
            
        </div>
        
        </div>
    </div>
</div>   
<?php

$this->load->view('structure/footer');

?>