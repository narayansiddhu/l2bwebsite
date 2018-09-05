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
                    <a href="<?php echo base_url(); ?>index.php/">Home </a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/accounts/view">Manage View</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">Create Fee Structure</a>
                </li>
            </ul>

    </div>     
    <?php
        if(strlen($this->session->userdata('Fee_added_Sucess'))>0 ){
            ?><br/>
                <div id="successMsg" class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('Fee_added_Sucess'); ?>
                </div>
                <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(7000).fadeOut();
                   </script>
           <?php
            $this->session->unset_userdata('Fee_added_Sucess');
        }
        ?>
    
    
        <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>Create Fee Structure</h3> 
                        <div class="actions">
                            <a style=" background-color: white; color: #368EE0" href="<?php echo base_url(); ?>index.php/fee/category" class="btn btn-primary"><i class="fa fa-plus"></i> Add Category</a>
                        </div>
                </div>
                <div class="box-content nopadding">                                
                    <form class='form-horizontal form-bordered' action="<?php echo base_url(); ?>index.php/fee/add_fee" method="post" >
                      <div class="form-group">
                                <label for="textfield" class="control-label col-sm-2">Class Name</label>
                                <div class="col-sm-10">
                                    <?php
                                    $credential = array('iid' =>$this->session->userdata('staff_Org_id'),'id'=> $this->input->get('cls_id'));
                                    $query = $this->db->get_where('class', $credential);$query=$query->row();
                                    ?><label class="form-control"><?php echo $query->name ?></label>
                                    <input type="hidden" name="fclass_name" id="fclass_name" value="<?php  echo $query->id ?>" />
                                    <span id='fclass_name_err' style=" color: red">
                                            <?php
                                               echo $this->form->error('fclass_name');   
                                            ?>
                                    </span>  
                                </div>
                        </div>
                        <?php
                          $cat = $this->db->query("SELECT *  FROM `fee_category` WHERE `iid`='".$this->session->userdata('staff_Org_id')."'   ");
                          $cat1=$cat->result();
                          $ids="";
                          foreach($cat1 as $c){
                              $ids.=$c->cid.",";
                            ?>
                            <div class="form-group">
                                    <label for="textfield" class="control-label col-sm-2"><?php echo $c->category ?></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fee_<?php echo $c->cid ?>" value="<?php 
                                        if(strlen($this->form->value("fee_".$c->cid)) >0 ){
                                              echo $this->form->value("fee_".$c->cid);
                                        }else{
                                            echo "0";
                                        }?>" id='fee' placeholder="Enter  Fee Amount" class="form-control" value="" > 
                                        <span  id='fee_error' style=" color: red">
                                                <?php
                                                   echo $this->form->error("fee_".$c->cid);   
                                                ?>
                                            </span>    
                                    </div>

                            </div>
                            <?php
                          }
                        $ids=  substr($ids, 0, strlen($ids)-1);
                         ?>
                       
                        <input type="hidden" name="ids" value="<?php echo $ids ?>" />

                        <div  class="form-actions col-sm-offset-2 col-sm-10">
                             <?php
                                if($cat->num_rows()==0){
                                  ?>
                            <span style=" color: red; ">** No Fee Categories Created </span><a href="<?php echo base_url(); ?>index.php/fee/category">Click Here To create Fee Category</a>
                                <?php
                                }else{
                                    ?>
                                   <input type="submit"  name="submit"  value="Add Fee's" class="btn btn-primary" />
                                     <?php
                                }
                                ?>
                            
                        </div>
                    </form>
                </div>
        </div>
                
           </div>           
        </div>
    </div>    
<?php

$this->load->view('structure/footer');
//SELECT c.cid,c.iid,s.subject,se.name as sec_name,se.class_id,cl.name,cl.numeric_val FROM `course` c join subjects s ON c.sid=s.sid join section se ON c.`secid`=se.sid JOIN class cl ON se.class_id =cl.id  order by cl.numeric_val DESC  

?>