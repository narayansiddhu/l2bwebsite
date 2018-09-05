<?php
$this->load->view('structure/header');
$this->load->view('structure/js');

$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium = unserialize(medium);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/academics/subjects">Manage Subjects</a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>index.php/academics/subjects">View <?php echo $subject->subject ?> course List </a>
                        </li>
                    </ul>
            </div><br/>
            <h3 style=" color:  #66cc00; text-align: center"><?php echo strtoupper( $subject->subject) ?> COURSE DETAILS</h3>
            <hr/>
                <?php
            $query = $this->db->query("SELECT c.cid,s.subject,st.name,st.img,sec.name as section, cl.name as cls_name ,(select count(*) from class_routine where course_id=c.cid ) as t_clses FROM `course` c LEFT OUTER JOIN  staff st ON c.tid=st.id JOIN subjects s on c.subid=s.sid JOIN section sec ON c.secid=sec.sid JOIN class cl On cl.id =sec.class_id WHERE c.subid='".$subject->sid."' ORDER BY sec.sid , s.sid ASC");
            $query= $query->result();
            ?>
             
            <?php
            foreach ($query as $value) {
                              ?>
            <div  style=" float: left; width: 24.5%; border-radius:8px ; border: 1px solid #cccccc; height: 200px; color:  #3866e0; margin: 2px; text-align: center; font-size: 15px; padding-top:10px">
                <?php
              if(strlen($value->img)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 70px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$value->img)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $value->img  ?>" alt="..." style=" width: 100px;; height: 70px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 100px;; height: 70px;"   >
            
                <?php
                 }
              }
              ?><hr style=" clear: both"/>
                <?php echo $value->cls_name ." - ".$value->section ?>
                <br/>Name  : <?php echo $value->name ?>
              <br/>No.Of Classes :  <?php echo $value->t_clses ?>
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