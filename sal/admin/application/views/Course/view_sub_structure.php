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
    <h3 style=" text-align: center; color:  #ff3333">Academic Structure</h3>
                    
                <div class="box">
                    <div class="col-sm-4 nopadding">
                        <table  class="table table-bordered" style=" color:  teal; width: 100%; text-align: center">
                                <tr>
                                     <th>Class</th>
                                     <td><?php echo $class_data->name ?></td>
                                </tr>
                                <tr> <th>Subject</th>
                                    <td><?php echo $course->subject ?></td>
                                     
                                </tr>
                                <tr>
                                      <th>Chapters</th>
                                     <td><?php echo $course->chapters ?></td>
                                </tr>
                                
                                
                            </table>
                    </div>
                    <?php
                    $sections= array();
                    $secc_ids="";
                     $faculty ="SELECT s.sid,st.name, s.name as sec_name,st.phone  FROM  `course` c JOIN section s ON c.secid = s.sid JOIN staff st ON c.tid=st.id  WHERE subid ='".$course->sub_id."' AND s.class_id ='".$course->class_id."' ";
                     $faculty = $this->db->query($faculty)->result();
                     ?>
                    <div class="col-sm-8 padding">
                        <table  class="table table-bordered" style=" color:  teal; width: 100%; text-align: center">
                            <thead>
                                <tr >
                                     <th style=" text-align: center;">Section</th>
                                     <th style=" text-align: center;">Faculty</th>
                                     <th style=" text-align: center;">Mobile No</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($faculty as $value) {
                                    $sections[$value->sid]=array("name"=>$value->sec_name,"faculty"=>$value->name);
                                    $secc_ids.=$value->sid.",";
                                    ?>
                                <tr>
                                    <td><?php echo $value->sec_name ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->phone ?></td>
                                </tr>
                                    <?php
                                }
                                $secc_ids= substr($secc_ids, 0, strlen($secc_ids)-1);
                                ?>
                            </tbody>    
                            </table>
                    </div>
                    <hr style=" clear: both"/>
                    <?php
                    $chapters ="SELECT * from course_main_topic WHERE csid='".$course->csid."' ";
                    $chapters=$this->db->query($chapters)->result();
                 //   print_r($chapters);
                    ?>
                        <div class="box " style=" clear: both; border-top: 1px solid #ccccff">
							
                                <div  class="box-content nopadding">
                                    <div style=" border-left: 1px solid #ccccff" class="tabs-container nopadding">
                                        <ul style=" border-left: 1px solid #ccccff" class="tabs tabs-inline tabs-left nopadding">
                       <?php
                       $i=1;
                       foreach ($chapters as $value) {
                           ?>
                               <li 
                                   <?php 
                                   if($i==1){
                                       echo "class='active'";
                                   }
                                   ?>
                                   >
                                        <a href="#chapter_<?php echo $value->tid ?>" data-toggle='tab'>
                                        <?php echo $value->name ?></a>
                                </li>
                           <?php
                           $i++;
                       }
                       ?>

                        </ul>
                                    </div>
                                    <div class="tab-content padding tab-content-inline nopadding">

                                        <?php
                       $i=1;
                       foreach ($chapters as $value) {
                         ?>
                            <div class="tab-pane <?php 
                                   if($i==1){
                                       echo "active";
                                   }
                                   ?> nopadding" id="chapter_<?php echo $value->tid ?>">

                                <h4 style=" text-align: center; color: teal"><?php echo $value->name ?></h4>
                            <table class="table table-bordered table-striped" style=" width: 100%">
                              <?php
                               $sub_topics="SELECT * from course_main_sub_topic WHERE tid='".$value->tid."'  ";
                               $sub_topics =$this->db->query($sub_topics)->result();
                              // print_r($sub_topics);
                               ?>
                                <thead>
                                <tr>
                                    <th>Sub-Topic</th>
                                    <th>No.of.Classes</th>
                                    <?php
                                   foreach ($sections as $value) {
                                       ?>
                                    <th style=" text-align: center"><?php echo $value['name'] ?></th>    
                                       <?php
                                   }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                   foreach ($sub_topics as $sub)
                                   {
                                      $q="SELECT * FROM `course_topic_completion`  where t_id='".$sub->msid."' AND sec_id IN(".$secc_ids.") ";
                                      $q= $this->db->query($q)->result();
                                      $sec_data=array();
                                      foreach($q as $k){
                                        $sec_data[$k->sec_id] = array("Remarks"=>$k->remarks,"time"=>$k->timestamp,"start"=>$k->start,"ending"=>$k->comp_date);  
                                      }
                                     ?>
                                    <tr>
                                        <td><?php echo $sub->sub_topic ?></td>
                                        <td><?php echo $sub->classess ?></td>
                                        <?php
                                            foreach ($sections as $k=>$value) {
                                                ?>
                                        <td style=" text-align: center;  color:  #006666"> 
                                                 <?php
                                                   if(isset($sec_data[$k])){
                                                    echo  $sec_data[$k]['Remarks'];
                                                     ?>
                                                         <hr style=" padding: 0px; margin: 1px; color: #cc0000"/>
                                                        <div style=" color: #ff6600">
                                                            <span style=" float: left; width: 50%"><?php echo date("d-M-Y",$sec_data[$k]['start'])." to " .date("d-M-Y",$sec_data[$k]["ending"])  ?></span> 
                                                            <span style=" float: left ; text-align: right; width: 50%">Updated On: <?php echo  date("d-M-Y",$sec_data[$k]["time"]) ?></span>
                                                        </div>
                                                      <?php
                                                   }else{
                                                       echo "Not Updated";
                                                   }
                                                 ?>
                                                </td>
                                               <?php
                                            }
                                        ?>
                                    </tr>
                                       <?php
                                   }
                                       ?>
                                </tbody>

                            </table> 

                                </div>
                           <?php
                           $i++;
                       }
                       ?>

                                    </div>
                                </div>
                        </div>
          </div>
                    
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>

