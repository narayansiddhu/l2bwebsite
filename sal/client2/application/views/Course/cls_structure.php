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
     $course_Str ="SELECT c. * , (SELECT count(*) FROM `course_main_topic`  where csid=c.csid ) as chapters FROM `course_structure` c  where  iid='".$this->session->userdata('staff_Org_id')."' AND class_id='".$class_data->id."'  ";
     $course_Str = $this->db->query($course_Str);
     $course_Str = $course_Str->result();
     $course_Str_array =array();
      foreach ($course_Str as $value) {
          $course_Str_array[$value->sub_id]= array("sub"=>$value->sub_id,"cs_id"=>$value->csid ,"chapters" =>$value->chapters);
      }
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
              $course ="SELECT s.class_id,c.subid ,su.subject FROM `course` c JOIN section s ON c.secid=s.sid JOIN subjects su ON  c.subid= su.sid  WHERE c.iid='".$this->session->userdata('staff_Org_id')."' AND s.class_id='".$class_data->id."'  ";
              $course = $this->db->query($course);
              $course = $course->result();
              $course_Arr =array();
              foreach ($course as $value) {
                  $course_Arr[$value->subid]= array("sub_id"=>$value->subid,"subject"=>$value->subject);
              }
              ?>
            <table class=" table table-striped table-bordered" style=" width: 100%" >
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Subjects</th>
                        <th>Chapters</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      if(sizeof($course_Arr)==0){
                          ?>
                    <tr>
                        <td colspan="4" style=" text-align: center ">** No Course Structure Found .. , Please Configure Course Structure</td>
                    </tr>
                              <?php
                      }else{
                          $i=1;
                          foreach ($course_Arr as $value) {
                              ?>
                    <tr>
                        <td><?php echo $i++ ?></td>
                        <td><?php echo $value["subject"] ?></td>
                        <td><?php 
                        if(isset($course_Str_array[$value['sub_id']])){
                            echo $course_Str_array[$value['sub_id']]["chapters"];
                        }else{
                            echo "0";
                        }
                        ?></td>
                        <td>
                            <?php 
                        if(isset($course_Str_array[$value['sub_id']])){
                           
                             ?>
                            <a class="btn" rel="tooltip" title="" data-original-title="Add Chapter" href="<?php echo base_url() ?>index.php/Course/add_chapter/<?php echo  $class_data->id ?>/<?php echo $course_Str_array[$value['sub_id']]['sub'] ?>"><i class="fa fa-plus"></i></a>
                            <a class="btn" rel="tooltip" title="" data-original-title="View Structure" href="<?php echo base_url() ?>index.php/Course/view_subject_structure/<?php echo  $class_data->id ?>/<?php echo $course_Str_array[$value['sub_id']]['sub'] ?>" ><i class="fa fa-eye"></i></a>
                            <?php
                        }else{
                            ?>
                            <a onclick="create_Structure(<?php echo $value['sub_id'] ?>);" class="btn" ><i class="fa fa-plus"></i></a>
                            <?php
                        }
                        ?>
                            <span id="error_<?php echo $value['sub_id']  ?>" style=" color: red" > </span>
                        </td>
                        
                    </tr>    
                              <?php
                          }
                      }
                    ?>
                </tbody>
            </table>
        </div>
     </div>
            <script>
                function create_Structure(sub){
                    setState('error_'+sub,'<?php echo base_url() ?>index.php/Course/add_course_Structure','subject='+sub+'&class=<?php echo $class_data->id ?>');
                }
                </script>
            
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>

