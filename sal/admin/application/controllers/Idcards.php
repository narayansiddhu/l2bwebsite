<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idcards extends CI_Controller {

        function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('logs');
            $this->load->model('form');
            $this->load->library("pagination");
            $this->load->model("validations");$this->operations->is_login();
        }
        
        public function index(){
            $this->load->view('idcards/index');
        }
        
        public function generate($id){
            if($id==""){
              redirect("idcards/","refresh");  
            }else{
                $id_Cards=$this->db->query("SELECT * from id_cards WHERE ic_id ='".$id."' ")->row();
                $data["id_Card"]=$id_Cards;
                $this->load->view('idcards/gen',$data);
            }
        }
        
        function generate_idcards(){
            
            $post=$_POST;
            $field = 'section';                              
            //  preg_match('/[^A-Z]/i',"pabaghvh  vhgvgg");exit;
            if(strlen($post[$field]) ==  0)
            {
               $this->form->setError($field,'* Select a section');
            }
           
            if($this->form->num_errors >0 ){
             $_SESSION['value_array'] = $_POST;
             $_SESSION['error_array'] = $this->form->getErrorArray();
            redirect('idcards/generate/'.$this->input->post("id_id"), 'refresh'); 
         }else{
            $id_Cards=$this->db->query("SELECT * from id_cards WHERE ic_id ='".$this->input->post("id_id")."' ")->row();
            $inst=$this->db->query("SELECT * FROM `institutes` where id= '".$this->session->userdata("staff_Org_id")."' ");
                $inst =$inst->row();           
              $inst_logo =assets_path. "uploads/".$inst->logo;
              $inst_name =$inst->name;
              $std_img = assets_path. "uploads/dummy_user.png";
              $inst_address =$inst->address;
              $bg_img=assets_path. "uploads/".$id_Cards->bg_image;
                $i=$id_Cards->print_css;
                //<#id_bg_img#>
                $i=  str_replace("<#id_bg_img#>", $bg_img, $i);
                $i=  str_replace("<#inst_logo#>", $inst_logo, $i);
                $i=  str_replace("<#Institute_name#>", $inst_name, $i);
            //    $i=  str_replace("<#stud_image#>", $std_img, $i);
                $i=  str_replace("<#inst_address#>", $inst_address, $i);
                
                $students="SELECT * from student where iid='".$this->session->userdata("staff_Org_id")."' AND section_id='".$this->input->post("section")."'  ";
                if(strlen($this->input->post("students"))>0){
                    $students.= " AND student_id='".$this->input->post("students")."' ";
                }
                $students=$this->db->query($students)->result();
                ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ID CARDS</title>
<link href="style.css" type="text/css" rel="stylesheet" media="print,screen" />

	
<script type="text/javascript" >
               // Replace the <textarea id="editor1"> with a CKEditor
               // instance, using default configuration.
               CKEDITOR.replace( 'editor1' );
</script>

<style>
body {
 -webkit-print-color-adjust: exact;
}
body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px; font-size:14px;}
.total {width:250px; height:300px;margin-left:auto;padding:10px; margin-right: auto; }
</style>

</head>

<body>
    <?php 
    $section="select s.sid,s.name as sec_name , c.name as cls_name From section s JOIN class c On s.class_id=c.id WHERE s.iid='".$this->session->userdata("staff_Org_id")."' AND s.sid='".$this->input->post("section")."' ";
     $section=$this->db->query($section)->row();
    $section =$section->cls_name." - ".$section->sec_name;
    $i = str_replace("<#class_name#>", $section , $i);
         
    $bg_group =  unserialize(blood_groups);
     foreach($students as $std ){
         $temp=$i;
         $std_img="dummy_user.png";
         //<#stud_image#>
         
              if(strlen($std->photo)!=0){
                 
                  if(file_exists(assets_path."/uploads/".$std->photo)){
                    $std_img =$std->photo;
                   }
              }
        $std_img = assets_path."/uploads/".$std_img;     
         if($std->bloodgroup>0){
             $std_bg=$bg_group[$std->bloodgroup];
         }else{
             $std_bg="--";
         }
         
        $temp=  str_replace( "<#stud_name#>", $std->name,$temp);
        $temp=  str_replace( "<#Admissionno#>", $std->userid,$temp);
        $temp=  str_replace( "<#stud_name#>", $std->name,$temp);
       $temp=  str_replace( "<#Ph_no#>", $std->phone,$temp);//$std_img
       $temp=  str_replace( "<#stud_image#>", $std_img,$temp);//$std_img
       $temp=  str_replace( "<#blood_group#>",$std_bg,$temp );
         ?>
    <div style=" float: left; width: 45%; margin-top: 10px; min-height: 520px;">
        <?php echo $temp; ?>
    </div>
          <?php
     }
    ?>
    <script>
    window.print();
    </script>
</body>
</html>
                <?php
         }
            
        }
        
}
?>
