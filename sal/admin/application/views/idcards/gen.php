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
                                Generate Id-Card
                        </h3>
                        
                </div>
            <div class="box-content" style=" padding-top: 25px; padding-bottom: 20px" >
                
                <div class='col-sm-6 nopadding' >
                    <form method="post"  action="<?php echo base_url() ?>index.php/idcards/generate_idcards" >
                        <input type="hidden" name="id_id" value="<?php echo $id_Card->ic_id ?>" />
                        <table class="table table-bordered ">
                            <tr>
                                <th>Class-section</th>
                                <td colspan="3"><?php 
                                
                                ?>
                                    <select onchange="load_Studens();" id='class' class="select2-me" name="section" style=" width: 100%;">
                                        
                                        <option value="" >Select A section</option>
                                    <?php
                            $section="select s.sid,s.name as sec_name , c.name as cls_name From section s JOIN class c On s.class_id=c.id WHERE s.iid='".$this->session->userdata("staff_Org_id")."'";
                                 $section=$this->db->query($section)->result();
                                 foreach ($section as $value) {
                                   ?>
                                    <option value="<?php echo $value->sid ?>" ><?php echo $value->cls_name ." - ".$value->sec_name ?></option>
                                   <?php   
                                 }
                                ?>
                                    </select>
                                    <span id='cls_err' style=" color: red"></span>
                                </td>
                                    
                            </tr>
                            <tr>
                                <th>Student</th>
                                <td colspan="3">
                                    <select  id='students' class="select2-me" name="students" style=" width: 100%;">
                                        
                                        <option value="" >Select A section</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style=" text-align: center"><input type="submit" name="submit" value="Generate Id-card" class="btn btn-primary" /></td>
                            </tr>
                        </table>
                    </form>
                    
                    <script>
                     function load_Studens(){
         //class    
         $('#cls_err').html(' ');//cat_err
        
        cls = $('#class').val();  //category       
        if(cls.length==0){
           $('#cls_err').html(' ** please select class');//cat_err
         //cls_err
        }else{
           setState('students','<?php echo base_url() ?>index.php/accounts/load_students','section='+cls);
           
        }

    }
                    </script>
            </div>
                
                <div class='col-sm-6 nopadding' align="center" >
                    
                            <?php
                            $bg_img=assets_path. "uploads/".$id_Card->bg_image;
                            $i=$id_Card->css;
                            //<#id_bg_img#>
                            $i=  str_replace("<#id_bg_img#>", $bg_img, $i);
                            $i=  str_replace("<#inst_logo#>", $inst_logo, $i);
                            $i=  str_replace("<#Institute_name#>", $inst_name, $i);
                            $i=  str_replace("<#stud_image#>", $std_img, $i);
                            $i=  str_replace("<#inst_address#>", $inst_address, $i);
                            //<#stud_image#>
                             echo $i; 
                             ?>
                    </div>
                
                
                           
                  
            </div>
            
        </div>
        
        </div>
    </div>
</div>   
<?php

$this->load->view('structure/footer');

?>