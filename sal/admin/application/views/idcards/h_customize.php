<?php
$this->load->view('structure/header');
?>
<!-- colorpicker -->
<link rel="stylesheet" href="<?php echo assets_path ?>css/plugins/colorpicker/colorpicker.css">
<!-- Colorpicker -->
<script src="<?php echo assets_path ?>js/plugins/colorpicker/bootstrap-colorpicker.js"></script>

<?php
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<style>
    .card {
        background-color: #e2e2e2;
        height:400px;
        width:250px;
        position:relative;
        -webkit-border-radius:20px;
        border-radius:20px;
        overflow:hidden;
    }
    .card_top{
        background-color: #0591D3;
        height:10px;
        width:250px;
        position:relative;
    }
    .inst_name{
        background-color: #8DD2EF;
        height:70px;
        width:250px;
        position:relative;
    }
    .address{
        background-color: #8DD2EF;
        height:70px;
        width:250px;
        position:relative;
        padding-top: 8px; 
        font-size: 13px; text-align: center; 
        color:  #000000;
    }
    
    .id_content{
        padding-top: 10px;
        height:240px;
        width:250px;
        position:relative;
        text-align: center;
        
    }
    .stdname{
        clear: both; 
        border-bottom: 1px solid #0099cc;
        font-size: 13px;
    }
    .content_table{
       margin-left: 8px; width: 90%;
    }
</style>
<?php
  $institute= $this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
  $institute =$institute->row();  print_r($institute);       
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
                        <a href="<?php echo base_url(); ?>index.php/Idcards">Generate Id </a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Horizontal Card</a>
                    </li>
                </ul>

        </div><br/>
         
        <div class="box box-color   box-bordered">
                <div class="box-title">
                        <h3>
                                Horizontal Id Card Customization
                        </h3>
                </div>
            <div class="box-content" style=" padding-top: 25px; padding-bottom: 20px" >
                          
                <div class="box">
                                
                    <div class="col-sm-6" style=" padding-left: 30px;">
                                    <strong>Customization Preview</strong>
                                    <div class="card" >
                                            <div class="card_top"></div>
                                            <div class="inst_name">
                                                <div style=" width: 25%; height: 75px; float: left; text-align: center; padding-left: 5px; padding-top: 5px;">
                                                    <img src="<?php echo assets_path ?>/uploads/<?php echo $institute->logo ?>" style=" width: 60px; height: 50px" />

                                                </div>
                                                 <div style=" width: 75%; height: 75px; float: left; text-align: center; padding-top: 5px;">
                                                     <strong style=" font-size: 16px"><?php echo $institute->name ?></strong>
                                                </div>
                                            </div>  
                                            <div class="card_top"></div>
                                            <div class="id_content"> 
                                                 <img src="<?php echo assets_path ?>/uploads/dummy_user.png" style=" width: 120px; height: 90px; border: 1px solid #8DD2EF" />
                                                 <br/> <strong class="stdname" > Student name</strong>  
                                                <br/>
                                                <table class='content_table'>
                                                    <tr>
                                                        <td>D.O.B</td>
                                                        <td>:</td>
                                                        <td>dd/mm/yyyy</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Class</td>
                                                        <td>:</td>
                                                        <td>class-section</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Roll No</td>
                                                        <td>:</td>
                                                        <td>xxxx</td>
                                                    </tr>
                                                    <tr>
                                                        <td>User ID</td>
                                                        <td>:</td>
                                                        <td>xxxxxxxx</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone</td>
                                                        <td>:</td>
                                                        <td>xxxxxxxx</td>
                                                    </tr>
                                                </table>

                                            </div>
                                            <div class="address" ><?php echo $institute->address ?></div>

                                        </div>
                                </div>
                    
                    <div class="col-sm-4">
                        <strong style=" text-align: center">Customization</strong>
                        <form method="post" action="<?php echo  base_url() ?>index.php/Idcards/generate_hcards" >
                        <table class="table table-hover  " >
                            <tr>
                                <td >Strips</td>
                                <td>:</td>
                                <td>
                                    <input type="color" name="strip" id="strip" value="#0591D3" onchange="change_strip_color();"  class="form-control"/>   
                                </td>
                            </tr>
                            <tr>
                                <td>Inst Bg</td><td>:</td>
                                <td>
                                    <input type="color" name="inst_bg" id="inst_bg" value="#8DD2EF" onchange="change_inst_bgcolor();"  class="form-control"/>   
                                </td>
                            </tr>
                            <tr>
                                <td>Inst name</td><td>:</td>
                                <td>
                                    <input type="color" name="inst_nme" id="inst_nme" value="#000000" onchange="change_inst_color();"  class="form-control"/>   

                                </td>
                            </tr>
                            <tr>
                                <td>Stud name</td><td>:</td>
                                <td>
                                    <input type="color" name="stud_name" id="stud_name" value="#000000" onchange="change_stud_color();"  class="form-control"/>   
                                </td>
                            </tr>
                            <tr>
                                <td>Content</td><td>:</td>
                                <td>
                                    <input type="color" name="cc" id="cc" value="#000000" onchange="change_body_color();"  class="form-control"/>   
                                </td>
                            </tr>
                            <tr>
                                <td>Address Bg</td><td>:</td>
                                <td>

                                    <input type="color" name="adbg" id="adbg" value="#8DD2EF" onchange="change_adr_bgcolor();"  class="form-control"/>   

                                </td>
                            </tr>
                            <tr>
                                <td>Address</td><td>:</td>
                                <td>
                                       <input type="color" name="addrc" id="addrc" value="#66ffff" onchange="change_adr_color();"  class="form-control"/>   
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style=" text-align: center"><input type="submit" name="generate" value="Generate  Id Cards" class="btn btn-primary" /></td>
                            </tr>
                        </table>

                        </form>
                    </div>
                    
                </div>        

                        
                   
            </div>
        </div>
        
        </div>
    </div>
</div>   
<script>
    //change_strip_color
    function change_strip_color(){
     c=$('#strip').val();
      $('.card_top').css("background-color", c);
    }
    function change_inst_bgcolor(){
       c=$('#inst_bg').val();
       $('.inst_name').css("background-color", c);
    }
    function change_inst_color(){
        c=$('#inst_nme').val();
       $('.inst_name').css("color", c); 
    }
    function change_stud_color(){
        c=$('#stud_name').val();
       $('.stdname').css("color", c); 
    }
    function change_body_color(){
        c=$('#cc').val();alert(c);
       $('.content_table').css("color", c); 
    }
    
    function change_adr_bgcolor(){
        c=$('#adbg').val();
       $('.address').css("background-color", c); 
    }
    function change_adr_color(){
        c=$('#addrc').val();
       $('.address').css("color", c); 
    }
    
    
    
    </script>
<?php

$this->load->view('structure/footer');

?>
