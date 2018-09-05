<?php
redirect("academics/Cls_structure",'refresh');
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    
    <?php $this->load->view('academics/navbar');  ?>
   
    <div class="col-sm-12">
      
            <?php
               
                $s=$this->db->query("SELECT last_id FROM `admission` WHERE iid='".$this->session->userdata('staff_Org_id')."' ");
                $total=0;
                if($s->num_rows()!=0) {
                   $s=$s->row(); 
                   $total=$s->last_id;
                }
            ?>
            
            <div class="row">
                                        
                <ul style="  width: 100%" class="stats">

                    <li style=" width: 23%" class='orange'>
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="details">
                                    <span class="big"><?php  echo $total; ?></span>
                                    <span class="value">No Of Students</span>
                            </div>
                    </li>
                    
                </ul>
            </div>
       
       
     </div>
</div>
<?php
$this->load->view('structure/footer');
?>