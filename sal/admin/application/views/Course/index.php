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
                    <a href="">Course Structure</a>
                </li>
            </ul>
    </div>
    <?php
     $course_Str ="SELECT * FROM `Course_structure` where  iid='".$this->session->userdata('staff_Org_id')."' ";
     $course_Str = $this->db->query($course_Str);
     $course_Str = $course_Str->result();
     $course_Str_array ="";
    ?>
    <div class="box box-bordered box-color">
        <div class="box-title">
                <h3><i class="fa fa-sitemap"></i>Course Academic Planning</h3> 
        <div class="actions">
            <a  style="color: #386ee0; background-color: white" href="<?php echo base_url() ?>index.php/Course/Create_new" class="btn">
                <i class="fa fa-plus"></i>&nbsp;Create Structure
            </a>
        </div>
        </div>
        <div class="box-content nopadding">                                
            <?php
              $course ="SELECT s.class_id,c.subid ,su.subject FROM `course` c JOIN section s ON c.secid=s.sid JOIN subjects su ON  c.subid= su.sid  WHERE c.iid='".$this->session->userdata('staff_Org_id')."'  ";
              $course = $this->db->query($course);
              $course = $course->result();
              $course_Arr =array();
              foreach ($course as $value) {
                  $course_Arr[$value->class_id][$value->subid]= array("sub_id"=>$value->subid,"subject"=>$value->subject);
              }
              echo "<pre>";
              print_r($course_Arr);
              ?>
        </div>
     </div>
            
            
            <!--- END OF pAGE ----!>
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>

