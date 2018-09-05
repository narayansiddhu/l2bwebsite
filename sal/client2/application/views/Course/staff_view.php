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
                        <a href="">Academic Structure</a>
                    </li>
                </ul>
        </div>
       
            <?php 
             $query=$this->db->query("SELECT se.name as sec_name,se.sid,su.sid as sub_id ,su.subject,cl.id as cls_id,cl.name as class_name ,(SELECT csid FROM `course_structure` where class_id= cl.id and sub_id =c.subid ) as csid  FROM `course` c JOIN section se ON c.secid=se.sid JOIN subjects as su ON c.subid=su.sid  JOIN class cl ON se.class_id =cl.id WHERE tid='".$this->session->userdata("staff_id")."'");
             $query =$query->result();
            ?>
            
            <div class="box box-bordered box-color">
        
        <div class="box-title">
            <h3><i class="fa fa-sitemap"></i></h3>
        </div>
        <div  class="box-content nopadding">
            <table class="table table-hover table-nomargin  table-bordered"  style="width: 100%;">
                <thead>
                    <tr>
                        <th>Class-Section</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead> 
                <tbody>
                <?php
                            foreach ($query as $value) {
                              ?>
                    <tr>
                        <td><?php echo $value->class_name." - ".$value->sec_name ?></td>
                        <td><?php echo $value->subject ?></td>
                        <td><?php 
                        if(strlen($value->csid)!=0){
                            ?><a href="<?php echo base_url() ?>index.php/course/view_staff_subject/<?php echo $this->session->userdata("staff_id")  ?>/<?php echo  $value->sid ?>/<?php echo $value->sub_id  ?>/<?php echo $value->csid  ?>">View Academic Structure</a><?php 
                        }else{
                            ?>Structure Not Yet Created<?php
                        }
                       ?></td>
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
