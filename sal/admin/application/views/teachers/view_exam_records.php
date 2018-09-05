<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <?php
//               echo "<pre>";
//                print_r($_SESSION);
               $failed_records = $this->session->userdata("failed_records");  //dummy_records
               $dummy_records = $this->session->userdata("dummy_records");

               if(sizeof($dummy_records)>0){
                 ?>
                     <div class="box box-color box-bordered">
                        <div class="box-title">
                                <h3>
                                    <i class="fa fa-bar-chart-o"></i>
                                    Dummy records Submitted
                                </h3>
                            
                        </div>
                        <div class="box-content nopadding">

                            <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                <thead>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                     </div>
                 <?php
               }
               
               if(sizeof($failed_records)>0){
                 ?>
                     <div class="box box-color box-bordered">
                        <div class="box-title">
                                <h3>
                                    <i class="fa fa-bar-chart-o"></i>
                                    Failed records Submitted
                                </h3>
                            
                        </div>
                        <div class="box-content nopadding">
                            <form method="post" action="<?php echo base_url() ?>/index.php/teachers/save_exam_reccords"  >
                           
                                <table class="table table-hover table-nomargin table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                    <thead>
                                        <th>Roll No</th>
                                        <th>Name</th>
                                        <th>Marks</th>
                                    </thead>
                                    <tbody>
                                     <?php
                                        $ids="";
                                     foreach ($failed_records as $value) {
                                            ?>
                                        <tr>
                                            <td><?php echo ($value['details']['roll']); ?></td>
                                            <td><?php echo ($value['details']['name']);  ?></td>
                                            <td>
                                                <input type="text" class="form-control" name="stud_marks_<?php  echo $value['details']['stud_id']  ?>" value="<?php
                                                if( (strlen($this->form->value('stud_marks_'.$value['details']['stud_id']))>0 ) || (strlen($this->form->error('stud_marks_'.$value['details']['stud_id']))>0 ) ){
                                                    echo $this->form->value('stud_marks_'.$value['details']['stud_id']);
                                                }else{
                                                echo $value['marks'];
                                                }
                                                ?>" />
                                                <small style="color: red" >
                                                   <?php
                                                        if(strlen($this->form->error('stud_marks_'.$value['details']['stud_id']))>0){
                                                            echo $this->form->error('stud_marks_'.$value['details']['stud_id']);
                                                        }
                                                   ?> 
                                                </small>
                                            </td>
                                        </tr>
                                           <?php  
                                           $ids.=$value['details']['stud_id'].",";
                                        }
                                        
                                        $ids= substr($ids,0,strlen($ids)-1);
                                     ?>
                                    <input type="hidden" name="ids" value="<?php echo $ids ?>" />
                                    <input type="hidden" name="examid" value="<?php echo $this->session->userdata("examid"); ?>" />
                                    <input type="hidden" name="maxmarks" value="<?php echo $this->session->userdata("maxmarks"); ?>" />
                                    <input type="hidden" name="minmarks" value="<?php echo $this->session->userdata("minmarks"); ?>" />
                                    
                                    
                                    </tbody>
                                </table>
                                
                                <div class="row"><br/>
                                    <div class="col-sm-4">
                                        &nbsp;  
                                    </div>
                                    <div class="col-sm-4" style=" text-align: center">
                                        <input type="submit" name="submit" value="Submit Marks" class="btn btn-primary btn-block" />
                                    </div>
                                    <div class="col-sm-4">
                                        &nbsp;  
                                    </div>
                                    <br/><br/>
                                </div>

                                 
                            </form>
                        </div>
                     </div>
                 <?php
               }
               

                ?>

        </div>
    </div>
</div>



<?php
$this->load->view('structure/footer');
?>