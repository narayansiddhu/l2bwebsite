<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<?php  
   $query=$this->db->query("SELECT s.sid,s.name as section,c.id as cls_id,c.name as class,c.numeric_val   FROM section s JOIN class c ON s.class_id =c.id  WHERE c.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC,s.sid ASC");                  
   $query=$query->result();  
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
   
    <?php  
    if($this->session->userdata('staff_level')>3){
     ?>
        <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                   
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/students/View">View Students</a>
                    </li>
                </ul>
            </div>
     <?php
     }else{
         ?>
        <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">View Students</a>
                    </li>
                </ul>
            </div>
     <?php
     }
     
     ?>
   <br/>
 
       

        <div class="panel-group panel-widget" id="ac3">
            <?php
            $id=1;
            $prev="";
            foreach ($query as $value) {
                if($prev!=$value->class){
                   if($prev!=""){
                       ?>
       
                        </table>
                                    </div>
                                        </div>
                                    </div>
                      <?php
                   }
                    $i=1;
                    ?>
                     <div class="panel panel-default <?php
                     if($prev==""){
                         echo "blue";
                     }
                     ?> ">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                            <a href="#c<?php echo $id ?>" data-toggle="collapse" data-parent="#ac3" class=""><?php echo $value->class;  ?></a>
                            </h4>
                        </div>
                    <div id="c<?php echo $id ?>" class="panel-collapse collapse <?php
                     if($prev==""){
                         echo "in";
                     }
                     ?> " style="height: auto;">
                       <div class="panel-body nopadding">
                           <table class="table table-hover table-nomargin" style=" width: 100%">
                               <tr>
                                   <td><a href="<?php echo base_url() ?>index.php/Students/View/<?php echo $value->cls_id ?>/<?php echo $value->section ?>"><?php echo $value->section ?></a></td>
                               </tr>
                    <?php
                    
                }
                else{
                    ?>
                    <tr>
                        <td><a href="<?php echo base_url() ?>index.php/Students/View/<?php echo $value->cls_id ?>/<?php echo $value->section ?>"><?php echo $value->section ?></a></td>
                    </tr>
                    <?php
                }
                ?>
            
              
                       
               <?php
               $prev=$value->class;$id++;
            }
               if($prev!=""){
                       ?>
                             
                        </table>
                                    </div>
                                        </div>
                                    </div>
                      <?php
                   }
            ?>
            
            
            
                                    </div>
        
    </div>
       </div>
</div>

<?php 
$this->load->view('structure/footer');
?>