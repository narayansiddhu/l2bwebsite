<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$text = $this->input->get('text');
$text_err=0;
if(strlen($text)==0){
    $route_err="** Please enter Any text To Search..";
}
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/Hostel/">Hostel</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Search</a>
                        </li>
                    </ul>
            </div>
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-search"></i>Search</h3>

                        </div>
                        <div class="box-content nopadding">
                                <div class="tab-content nopadding">
                                    <div class="form-actions col-sm-offset-2 col-sm-8">
                                         <input type="text" style=" float: left; width: 90%;" name="srch_txt"  id='srch_txt'  class="form-control" placeholder="Enter Text To Search" />
                                         <button onclick="search_fun();" style=" float: left; width: 10%; height: 34px " class="btn btn-primary"><i class="fa fa-search"></i></button>  
                                         <span id='srch_error' style=" color: red">
                                             <?php
                                              if($text_err!=0){
                                                  echo $text_err;
                                              }
                                             ?>
                                         </span>
                                    </div>

                                </div>
                        </div>
                    <script>
                      function search_fun(){
                          txt = $('#srch_txt').val();
                          txt=$.trim(txt)
                          $('#srch_error').html("");
                          if(txt.length==0){
                           $('#srch_error').html("** Please Enter Text To Search.. ");
                          }else{
                            window.location.href = '<?php echo base_url() ?>index.php/hostel/search?text='+txt;
                          }
                      }    
                    </script>
                </div>

                    <div class="box">
                      <?php

                            if( ($text_err==0)&&(strlen($text)!=0)){
//SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND hr.room LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND hb.block_name LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND s.name LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND s.email LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND s.admission_no LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND s.phone LIKE '%b%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='33' AND hs.fee LIKE '%b%'

                                 $s="SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms  hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND hr.room LIKE '%$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND hb.block_name LIKE '%$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND s.name LIKE '%$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND s.email LIKE '%$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND s.admission_no LIKE '%$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND s.phone LIKE '$text%' UNION SELECT hs.*,hr.*, hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND hs.fee LIKE '$text%'";
                                        $query = $this->db->query($s);
                                 if($query->num_rows()>0){
                                     $query = $query->result();

                                     ?>
                        
                                    <!--<div class="col-sm-4 nopadding">
                                      <div class="box box-bordered box-color" style=" padding-right:  5px;">
                                        <div class="box-title">
                                                <h3><i class="fa fa-road"></i>Hostel Details</h3> 
                                        </div>
                                        <div class="box-content nopadding"> 
                                            <table class="table table-bordered Datatable table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Block</th>
                                                        <th>Room</th>
                                                        <th>Fee Amount</th>
                                                        <th>pick-up </th>
                                                        <th>Drop</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <?php
                                                 $trips="";
                                                     foreach ($query as $value) {
                                                         //$trips.=$value->trid.",";
                                                        ?>
                                                    <tr>
                                                       <td><?php echo $value->block_name ?></td>
                                                       <td><?php echo $value->room ?></td>
                                                       <td><?php echo $value->fee ?></td>
                                                       <td><?php echo $value->pick_up ?></td>
                                                        <td><?php echo $value->drop ?></td>
                                                    </tr>
                                                        <?php 
                                                     }
                                                     //$trips = substr($trips, 0,strlen($trips)-1);
                                                 ?>
                                                </tbody>
                                            </table>
                                        </div>
                                      </div>
                                
                                    </div>-->
                        <div class="col-sm-12 nopadding">
                            
                            <div class="box box-bordered box-color" style=" padding-left: 5px;">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Student Details</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>Admission No</th>
                                        <th>Student</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Block</th>
                                        <th>Room</th>
                                        <th>Fee Amount</th>
                                        <!--<th>pick-up </th>
                                        <th>Drop</th> -->  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //$stud="SELECT hs.*,hr.* , hb.*,s.* FROM `hostel_students` hs JOIN hostel_rooms  hr ON hs.room_id=hr.room_id jOIN hostel_blocks hb ON hr.block_id=hb.block_id JOIn student s ON hs.student_id=s.student_id where hs.iid='".$this->session->userdata("staff_Org_id")."' AND hr.room LIKE '%$text%' OR hb.block_name LIKE '%$text%' OR s.name LIKE '%$text%' OR s.email LIKE '%$text%'";
                                    //$stud = $this->db->query($stud)->result();
                                    foreach ($query as $value) {
                                       ?>
                                    <tr>
                                        <td><?php echo $value->userid ?></td>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->phone ?></td>
                                        <td><?php echo $value->email ?></td>
                                        <td><?php echo $value->block_name ?></td>
                                        <td><?php echo $value->room ?></td>
                                        <td><?php echo $value->fee ?></td>
                                        <!--<td><?php echo $value->pick_up ?></td>
                                        <td><?php echo $value->drop ?></td>-->
                                    </tr>
                                       <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                </div>
                        </div> 
                                     <?php
                                 }else{
                                     ?> <br/>
                        <h4 style=" text-align: center; color: red">** No Records Found..</h4>;
                                     <?php
                                 }
                            
                            }
                         ?>
                         
                        
                    </div>                    
                    
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>