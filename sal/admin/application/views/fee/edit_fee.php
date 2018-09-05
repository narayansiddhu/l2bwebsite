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
                    <a href="<?php echo base_url(); ?>index.php">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage Fee </a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Edit Fee Structure</a>
                </li>
            </ul>

    </div>     
    <?php
        if(strlen($this->session->userdata('Fee_added_Sucess'))>0 ){
            ?><br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Fee_added_Sucess'); ?>
                </div>
           <?php
            $this->session->unset_userdata('Fee_added_Sucess');
        }
        ?>
    <?php
      $fee_categories = "SELECT * from fee_category  where iid='".$this->session->userdata("staff_Org_id")."'";
      $fee_categories = $this->db->query($fee_categories)->result();
    $fee_cat_array=array();
      foreach ($fee_categories as $val){
         $fee_cat_array[$val->cid]= array("name"=>$val->category); 
      }
       
 $sections ="SELECT count(*) as total, group_concat(sid) as section_ids from section where class_id='".$cls->id."' ";
 $sections = $this->db->query($sections)->row();
 $students="SELECT count(*) as total from student where section_id IN (".$sections->section_ids.") ";
  $students = $this->db->query($students)->row();
foreach ($fee as $value) {
     $fee_cat_array[$value->catid]['prev']=array("id"=>$value->fid,"amount"=>$value->fee);
}
?>
            <div class="box ">
                <div class="col-sm-4 nopadding">
                    <div class="box box-bordered box-color ">
                            <div class="box-title">
                                    <h3><i class="fa fa-info-circle"></i>Class Info</h3> 
                            </div>
                            <div class="box-content nopadding">     
                                <table class="table table-bordered ">
                                    <tr>
                                        <th>Class Name</th>
                                        <td><?php echo $cls->name ?></td>
                                    </tr>
                                    <tr>
                                        <th>No.Of.Sections</th>
                                        <td><?php echo $sections->total ?></td>
                                    </tr>
                                    <tr>
                                        <th>Students</th>
                                        <td><?php echo $students->total ?></td>
                                    </tr>
                                </table>
                            </div>
                    </div>
                    
                </div>
                <div class="col-sm-8 nopadding" >
                    <div class="box box-bordered box-color" style=" padding-left: 10px">
                            <div class="box-title">
                                    <h3><i class="fa fa-pencil-square-o"></i>Edit  Fee Structure of <?php echo $cls->name ?></h3> 
                            </div>
                            <div class="box-content nopadding">                                
                                <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/fee/edit_fee" method="post" >
                                    <input type="hidden" name="cls_name" value="<?php echo $cls->id ?>" />
                                    <?php
                                       $ids="";
                                     
                                   
                                        foreach ($fee_cat_array as $key => $value) {
    $ids.=$key.",";
                                            ?>
                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2"><?php echo $value['name'] ?></label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="fee_<?php echo $key ?>" value="<?php 
                                                    if(strlen($this->form->value("fee_".$key)) >0 ){
                                                          echo $this->form->value("fee_".$key);
                                                    }else{
                                                      if(isset($value['prev'])){
                                                                echo $value['prev']['amount'];
                                                       }else{
                                                           echo "0";
                                                      }
                                                    }?>" id='fee' placeholder="Enter  Fee Amount" class="form-control" value="" > 
                                                    <input type="hidden" name="fee_id_<?php echo $key ?>" value="<?php
                                                            if(isset($value['prev'])){
                                                                echo $value['prev']['id'];
                                                            }?>"/>
                                                    <span  id='fee_error' style=" color: red">
                                                            <?php
                                                               echo $this->form->error("fee_".$key);   
                                                            ?>
                                                        </span>    
                                                </div>
                                            
                                            </div>
                                           <?php
                                        }
                                        
                                    ?>
                                    <input type="hidden" name="ids" value="<?php echo substr($ids, 0, strlen($ids)-1)  ?>"  />     
                                    <div  class="form-actions col-sm-offset-2 col-sm-10">
                                        <input type="submit"  name="submit"  value="Edit Fee's" class="btn btn-primary" />
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
    
                    
            
            </div>           
        </div>
    </div>    
<?php

$this->load->view('structure/footer');
//SELECT c.cid,c.iid,s.subject,se.name as sec_name,se.class_id,cl.name,cl.numeric_val FROM `course` c join subjects s ON c.sid=s.sid join section se ON c.`secid`=se.sid JOIN class cl ON se.class_id =cl.id  order by cl.numeric_val DESC  

?>