<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');

//class_exam_error

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
                    <a href="<?php echo base_url(); ?>index.php/exam/">Manage Exam</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li >
                    <a href="">Configure exam </a>
                </li>
            </ul>
        </div>
    
      <?php
if(strlen($this->session->userdata('class_exam_error'))>0 ){
    ?><br/>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Warning !</strong>
             <?php echo $this->session->userdata('class_exam_error'); ?>
        </div>
   <?php
    $this->session->unset_userdata('class_exam_error');
}
?>  
              
                
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                       <i class="fa fa-cogs" aria-hidden="true"></i> Select class's for <?php echo $exam->exam ?>
                                    </h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' method="post" action="<?php echo base_url(); ?>index.php/exams/configure"  >
                                    
                                    <div class="form-group  " style=" max-height: 400px; overflow-y: scroll"> 
                                                    <input type="hidden" name="exam_id" value="<?php echo $exam->id ?>" />
                                                    <?php
                                                      $old=$this->examinations->get_sectionids($exam->id);
                                                      $previous=explode(',',$old);
                                                    ?>
                                                    <input type="hidden" name="previous" value="<?php echo $old; ?>" />
                                                    <input type="hidden" name="action" value="<?php 
                                                     if($old==0){
                                                         echo "insert";
                                                     }else{
                                                         echo "update";
                                                     }
                                                    ?>" />
                                                    <?php 
                                                        $credential = array('iid'=>$this->session->userdata('staff_Org_id') );
                                                        $classes = $this->db->query("SELECT s.sid,s.name,c.name as class FROM `section` s JOIN class c ON s.class_id =c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC ,s.sid ASC")->result_array();	
                                                        $cls="";
                                                        ?>
                                                    <table style="width: 100%" class="table table-hover table-nomargin" >
                                                        <?php
                                                        $sids="";
                                                            foreach($classes as $row){
                                                                $sids.=$row['sid'].",";
                                                                if($cls!=$row['class']){
                                                                  
                                                                    if($cls!=""){
                                                                        ?>
                                                                            </td>
                                                                        </tr>
                                                                      <?php
                                                                    }
                                                                    
                                                                    ?>                                                        
                                                                        <tr style=" border-bottom: thick">
                                                                            <th style="width: 25%"><?php echo $row['class'] ?></th> 
                                                                        <td colspan="3">
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="checkbox" value="<?php echo $row['sid'] ?>" name="section_<?php echo $row['sid'] ?>"
                                                                                           <?php
                                                                                             if (in_array($row['sid'], $previous)){
                                                                                                 echo "checked";
                                                                                             }
                                                                                             ?>  /><?php echo $row['name'] ;?>
                                                                                </label>
                                                                            </div>
                                                                         
                                                                 <?php
                                                                }//if condition for class name check
                                                                else{
                                                                    ?>
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="checkbox" value="<?php echo $row['sid'] ?>" name="section_<?php echo $row['sid'] ?>"
                                                                                           <?php
                                                                                             if (in_array($row['sid'], $previous)){
                                                                                                 echo "checked";
                                                                                             }
                                                                                             ?>  /><?php echo $row['name'] ;?>
                                                                                </label>
                                                                            </div>
                                                                            
                                                                    <?php
                                                                }
                                                                
                                                                
                                                                $cls=$row['class'];
                                                            }// for loop ending 
                                                            
                                                            $sids=  substr($sids,0, strlen($sids)-1) ;
                                                            
                                                        ?>
                                                    </table>
                                                    <input name="section_ids" value="<?php echo $sids ?>" type="hidden" />
                                                
                                    </div> 
                                    
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="submit"   name="submit" value="Configure Exam" class="btn btn-primary" />
                                    </div>
                                    
                                </form>
                            </div>
                    </div>
        
        </div>
    </div>   
</div>
    
<?php
$this->load->view('structure/footer');
?>