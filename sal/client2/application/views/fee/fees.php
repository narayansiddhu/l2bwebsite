<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

    
<div class="row">
   <div class="col-sm-12">
        <div class="box">
            <br/>
             <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php//attendance/">Fee </a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Fee Structure</a>
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
                $fee_cat = unserialize (Fee_Category);
            ?>  
                    
         
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3>
                                <i class="fa fa-th-list"></i> Fee Structure</h3>
                        <div class="actions">
                            <a style=" background-color: white; color: #368EE0" href="<?php echo base_url() ?>index.php/fee/add_new" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
                        </div>
                </div>
                <div class="box-content nopadding">

                    <?php

                    $cat_array =array();
                    $fee_cat_array = array();
                    $cat =$this->db->query("SELECT * FROM `fee_category` where iid='".$this->session->userdata('staff_Org_id')."' ");
                    $cat = $cat->result();
                    foreach($cat as $val){
                        $cat_array[$val->cid] =$val->category;
                    }
                   // print_r($cat_array);
                    $query = $this->db->query("SELECT f.fid,fc.cid as catid,fc.category,f.fee,c.name,c.id as class_id FROM `fee_class` f JOIN fee_category fc ON f.category=fc.cid JOIN class c ON f.cls_id=c.id  WHERE f.`iid`='".$this->session->userdata('staff_Org_id')."'  ORDER BY c.numeric_val DESC , f.category ASC  ");
                    $query=$query->result();
                    foreach($query as $val ){
                        $fee_cat_array[$val->class_id]['cls_name'] = $val->name;
                        $fee_cat_array[$val->class_id][$val->catid] =$val->fee;
                    }
                    ?>
                    <table class="table table-hover table-nomargin" style="overflow-x: scroll;">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <?php
                                   foreach($cat_array as $cr){
                                     ?>
                                        <th><?php echo $cr;  ?></th>   
                                     <?php  
                                   }
                                ?>
                                <th>Total</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 

                                foreach($fee_cat_array as $key =>$f ){
                                    $total=0;
                                    ?><tr>
                                        <td><?php echo $f['cls_name'];  ?></td>   
                                    <?php  
                                    foreach($cat_array as $kc => $cr){
                                        ?><td><?php
                                       if(isset($f[$kc])){
                                           echo $f[$kc];
                                           $total =$total+$f[$kc];
                                       } else{
                                           echo "0";
                                       }
                                       ?></td><?php
                                   }
                                   ?>
                                        <td><?php echo $total; ?></td>
                                        <td><a href="<?php echo base_url() ?>/index.php/fee/edit/<?php echo $key ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                                    </tr>
                                    <?php
                                }

                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
                    
                    
        </div>
    </div>
</div>   
<?php
$this->load->view('structure/footer');
?>
