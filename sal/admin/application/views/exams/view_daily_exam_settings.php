<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
 $query=$this->db->query("SELECT ec.id as ec,s.sid,s.name as section,c.name as class , de.*  FROM `examination_cls` ec LEFT  JOIN daily_exams de ON de.ecid=ec.id JOIN section s ON ec.sectionid=s.sid JOIN class c ON s.class_id=c.id where ec.examid='".$exam->id."' AND ec.status=1 ");
 $query=$query->result();

 ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box" >
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/exams/">Manage Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">View exam Settings</a>
                </li>
            </ul>
        </div>
            <div class="row" style=" padding-top: 15px;">
           <div class="col-sm-12">
                <div class="pull-left">
                    <h3 style=" color: #66cc00;"><?php echo strtoupper($exam->exam) ?>(<?php echo date('d-m-y',$exam->startdate) ?> TO <?php echo date('d-m-y',$exam->enddate) ?>)</h3>
                </div>
                <div class="pull-right">
                    <ul class="minitiles">   
                        <?php
                         if(time()<$exam->startdate){
                             ?>
                        <li class="blue">
                            <a href="<?php echo base_url() ?>index.php/exams/settings/<?php echo $exam->id ?>" rel="tooltip" title="" data-original-title="Edit exam settings " ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        </li>
                        <li class="green">
                            <a href="<?php echo base_url(); ?>index.php/exams/assign/<?php echo  $exam->id ?>"   rel="tooltip"  title="" data-original-title="Re-Assign Exam" >
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </a>
                        </li>
                        
                             <?php
                         }
                        ?>
                        
                        <li class="orange">
                            <a target="_blank" href="<?php echo base_url() ?>index.php/exams/print_daily_schedule/<?php echo $exam->id ?>" rel="tooltip" title="" data-original-title="View Exam Result" >
                                <i class="fa fa-print"></i>
                            </a>
                        </li>
                        
                    </ul>
                </div>
           </div>
           
       </div>
       <hr/>
        <?php
        if(strlen($this->session->userdata('exam_Settings'))>0 ){
            ?><br/>
                <div id='successMsg' class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('exam_Settings'); ?>
                </div>
            <script>
                            $("#successMsg").fadeIn();
                            $("#successMsg").delay(2000).fadeOut();
                       </script>
           <?php
            $this->session->unset_userdata('exam_Settings');
        }
        ?>
   
            <div class="box box-bordered box-color" >
                <div class="box-title">
                    <h3>Exam Settings Of <?php echo ucfirst($exam->exam) ?> </h3>
                </div>
                <div class="box-content nopadding"> 
                    <table class="table table-bordered table-striped " >
                        <tr>
                            <th>S.no</th>
                            <th>class-section</th>
                            <th>exam Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>max-marks</th>
                            <th>Min-Marks</th>
                            <?php
                             if($exam->enddate<time()){
                                 ?> <th>Actions</th><?php
                             }
                            ?>
                           
                        </tr>
                    
                    <?php
                    $i=1;
                    foreach ($query as $value) {
                        ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $value->class." - ".$value->section ?></td>
                        <td><?php
                          if(strlen($value->examdate)==0){
                              echo "--";
                          }else{
                              echo date("d-m-Y",$value->examdate);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->starttime)==0){
                              echo "--";
                          }else{
                              echo date("H:i",$value->starttime);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->endtime)==0){
                              echo "--";
                          }else{
                              echo date("H:i",$value->endtime);
                          }
                        ?></td>
                        <td><?php
                          if(strlen($value->maxmarks)==0){
                              echo "--";
                          }else{
                              echo $value->maxmarks;
                          }
                        ?></td>
                        
                        <td><?php
                          if(strlen($value->minmarks)==0){
                              echo "--";
                          }else{
                              echo $value->minmarks;
                          }
                        ?></td>
                        <?php
                             if($exam->enddate<time()){
                                 ?> <td><?php
                          if($value->examdate < time() ){
                              ?><a href="<?php echo base_url(); ?>index.php/exams/view_daily_exammarks/<?php echo $exam->id ?>/<?php echo $value->sid ?>" target="_blank" rel="tooltip" title="" data-original-title="Add Marks" ><i class="fa fa-plus"></i></a>
                              <?php
                          }else{
                              echo "--";
                          }
                        ?></td><?php
                             }
                            ?>
                        
                    </tr>
                        <?php
                    }
                    
                    ?>
                    </table>
                </div>
            </div>                     
 
</div>
    </div>
</div>
<script>
    function PrintElem()
    {
      var elem="exam_schedule";
      var mywindow = window.open('', 'PRINT', 'height=400,width=600');


        mywindow.document.write('<html><head><title>' + document.title  + '</title>');

        mywindow.document.write('</head><body >');
      mywindow.document.write('<h1>' + document.title  + '</h1>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;

        }
    function fetch_settings(){
        section=$('#section_id').val();
        if(section.length!=0){
      setState('view_setting','<?php echo base_url() ?>index.php/ajax/viewexam_Settings','examid='+<?php echo $exam->id ?>+'&ecid='+section+'&from='+<?php echo $exam->startdate?>+'&end='+<?php echo $exam->enddate ?>);  
    }else{
        $('#subname_err').html("** Please select Secton");
    }
    }
</script>
<?php
$this->load->view('structure/footer');
?>