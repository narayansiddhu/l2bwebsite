<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$prev="";
?>
<div class="row">
    
    <?php $this->load->view('academics/navbar');  ?>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/academics/Cls_structure">Academics</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="">View Course Structure Of Class   <?php echo $class; ?></a>
                </li>
            </ul>
            
    </div>
    
    <h3><i class="fa fa-th-large" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Structure of Class <?php echo $class; ?></h3>
    
    <div class="col-sm-12">
        <br/>
        <div class="panel-group panel-widget" id="ac3">
            <?php
            $id=1;
            $prev="";
            foreach ($course as $value) {
                if($prev!=$value->section){
                   if($prev!=""){
                       ?>
        </tbody>
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
                            <a href="#c<?php echo $id ?>" data-toggle="collapse" data-parent="#ac3" class=""><?php echo $value->section;  ?></a>
                            </h4>
                        </div>
                    <div id="c<?php echo $id ?>" class="panel-collapse collapse <?php
                     if($prev==""){
                         echo "in";
                     }
                     ?> " style="height: auto;">
                       <div class="panel-body">
                           <table class="table table-hover table-nomargin" style=" width: 100%">
                               <thead>
                                  <th>S.no</th>
                                  <th>Subject</th>
                                  <th>Faculty Name</th>
                                  <th>Actions</th>
                               </thead>
                               <tbody>
                                   <tr id="tr_<?php   echo $value->cid; ?>">
                                       <td id="id_<?php   echo $value->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                   <td style=" width: 15%" ><?php echo $value->subject; ?></td>
                                   <td  style=" width: 40%"><?php if($value->name !=NULL ){
                                                       echo $value->name;
                                                   }else{
                                                       echo "--";
                                                   }?></td>
                                   <td id="td_actions_<?php   echo $value->cid; ?>" >
                                       <?php if($value->name !=NULL ){
                                           ?>
                                       <button  onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Edit Faculty" >
                                           <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }else{
                                           ?>
                                       <button onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Assign Faculty" >
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }?>
                                       
                                       
                                   </td>
                               </tr> 
                           
                    <?php
                    
                }
                else{
                    ?>
                    <tr id="tr_<?php echo $value->cid; ?>">
                                   <td id="id_<?php   echo $value->cid; ?>" style=" width: 5%"><?php echo $i++; ?></td>
                                   <td><?php echo $value->subject; ?></td>
                                   <td><?php if($value->name !=NULL ){
                                                       echo $value->name;
                                                   }else{
                                                       echo "--";
                                                   }?></td>
                                   <td id="td_actions_<?php   echo $value->cid; ?>" >
                                       <?php if($value->name !=NULL ){
                                           ?>
                                       <button  onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Edit Faculty" >
                                           <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }else{
                                           ?>
                                       <button onclick="assign_faculty('<?php echo $value->cid;  ?>');" class="btn" rel="tooltip" title="" data-placement="right" data-original-title="Assign Faculty" >
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                       </button>
                                          <?php
                                       }?>
                                       
                                       
                                   </td>
                               </tr> 
                    <?php
                }
                ?>
            
              
                       
               <?php
               $prev=$value->section;$id++;
            }
               if($prev!=""){
                       ?>
                               </tbody>
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
    
<script>



function assign_faculty(value){
    //td_actions_
    id='td_actions_'+value;
    setState(id,'<?php echo base_url() ?>index.php/ajax/Load_assign','cid='+value);
}

function save_faculty(value){
    teach=$('#select_'+value).val();
    //id_
    i=$('#id_'+value).html();
    if(teach.length!=0){
      setState('tr_'+value,'<?php echo base_url() ?>index.php/ajax/save_assigned','cid='+value+'&staff='+teach+'&i='+i);  
    }else{
        //error_
        $('#error_'+value).html("<br/>** Please select faculty");
    }
}

</script>


    
    
<?php
$this->load->view('structure/footer');
?>
