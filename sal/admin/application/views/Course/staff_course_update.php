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
                        <a href="<?php echo base_url() ?>index.php/course/view_Staff_course">Academic Structure</a>
                    </li>
                    <li>
                        <a href="">Academic Structure Of <?php echo $course_structure->class_name ." - ".$course_structure->sec_name ?></a>
                    </li>
                </ul>
        </div>
            <br/>
        <div class="box">
                            <?php
                    $chapters ="SELECT * from course_main_topic WHERE csid='".$course_structure->csid."' ";
                    $chapters=$this->db->query($chapters)->result();
                 //   print_r($chapters);
                    ?>
                            <table  class="table table-bordered" style=" color:  teal; width: 100%; text-align: center">
                                <tr>
                                     <td>Class - Section</td>
                                     <td>Subject</td>
                                     <td>Chapters</td>
                                </tr>
                                <tr>
                                     <td><?php echo $course_structure->class_name ." - ".$course_structure->sec_name ?></td>
                                     <td><?php echo $course_structure->subject ?></td>
                                     <td><?php echo sizeof($chapters) ?></td>
                                </tr>
                            </table>
                    
                        <div class="box  box-bordered">
							<div class="box-title ">
								<h3>
									<i class="fa fa-sitemap"></i>Academic Structure</h3>
							</div>
							<div class="box-content nopadding">
                                                            <div style="  float: left">
                                                                <ul class="tabs tabs-inline tabs-left">
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
                                                    
                                                    <table class="table table-bordered table-striped" style=" width: 100%">
                                                      <?php
                                                      $sub_topics="SELECT * from course_main_sub_topic  WHERE tid='".$value->tid."'  ";
                                                       $sub_topics =$this->db->query($sub_topics)->result();
                                                      // print_r($sub_topics);
                                                       ?>
                                                        <thead>
                                                        <tr>
                                                            <th>Sub-Topic</th>
                                                            <th>No.Of.Classes</th>
                                                            <th style=" text-align: center">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                           foreach ($sub_topics as $sub)
                                                           {
                                                              // $sub
                                                               ?>
                                                            <tr>
                                                                <td><?php echo $sub->sub_topic ?></td>
                                                                <td><?php echo $sub->classess ?></td>
                                                                <td style=" text-align: center"><?php
                                                                //print_r($sub);
                                                                $q="SELECT * FROM `course_topic_completion` WHERE `course_id` = '".$course_structure->cid."' AND `sec_id`='".$course_structure->sid."' AND t_id='".$sub->msid."'  ";
                                                                $q =$this->db->query($q);
                                                                if($q->num_rows()==0){
                                                                    ?>
                                                                    Not Updated Yet  ,
                                                                    <a onclick="Update_status(<?php echo $sub->msid ?>);"   href="#modal-1" role="button"  data-toggle="modal" rel="tooltip" title="" data-original-title="Update status" >Click Here To Update</a>
                                                                     <?php
                                                                }else{
                                                                    $q=$q->row();
                                                                    ?>
                                                                    <?php echo $q->remarks ?><hr/>
                                                                    <div style=" color : #ff6633"><span style=" float: left"><?php echo date('d-m-Y',$q->start); ?> To <?php echo date('d-m-Y',$q->comp_date); ?></span>
                                                                    <span style=" float: right">Update ON <?php echo date('d-m-Y',$q->timestamp) ?></span>
                                                                    </div>
                                                                     
                                                                     
                                                                     
                                                                    <?php
                                                                }
                                                                ?></td>
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
            
            <div id="modal-1" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Status</h4>
				</div>
				<!-- /.modal-header -->
				<div id="modal_body" class="modal-body">
                                    <div class="box">
                                        <div class="col-sm-6 nopadding">
                                            <input type="text" class="form-control datepick" name="start_date" id="start_date" placeholder="Select Starting Date" />
                                            <span id="start_date_err" style="color: red"></span>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <input type="text" class="form-control datepick" name="cdate" id="cdate" placeholder="Select Completion Date" />
                                            <span id="cdate_err" style="color: red"></span>
                                        </div>
                                    </div>
                                    
                                    <br style=" clear: both"/><br/>
                                    <textarea name="Remarks" id="Remarks" class="form-control" style=" resize: none" placeholder="Please enter Reason For rejection"></textarea>
                                    <span id="Remarks_err" style="color: red"></span>
                                    <input type="hidden" id="msid" name="msid" value="" />
                                    <span id="errors"></span>
                                </div>
				<!-- /.modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="button" onclick="submit_Status();" class="btn btn-primary">Submit Status</button>
				</div>
				<!-- /.modal-footer -->
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
            <script>
                function Update_status(val){
                    $('#msid').val(val);
                }
               function submit_Status(){
                   $('#cdate_err').html("");
                   $('#Remarks_err').html("");
                   msid=$('#msid').val();
                   cdate=$('#cdate').val();
                   start_date=$('#start_date').val();
                   Remarks=$('#Remarks').val();
                   setState('errors','<?php echo base_url() ?>index.php/course/update_status/','msid='+msid+'&start_date='+start_date+'&cdate='+cdate+'&Remarks='+Remarks+'&sec_id=<?php echo $course_structure->sid ?>&course=<?php echo $course_structure->cid ?>');
          
               }
            
            </script>
            
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>
