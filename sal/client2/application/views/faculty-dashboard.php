<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style >
    
.container_timeline ul {
  margin: 0;
  margin-top: 30px;
  list-style: none;
  position: relative;
  padding: 1px 100px;
  color:  #660000;
  font-size: 13px;
}
.container_timeline ul:before {
  content: "";
  width: 1px;
  height: 100%;
  position: absolute;
  border-left: 2px dashed #660000;
}
.container_timeline ul li {
  position: relative;
  margin-left: 30px;
  background-color: rgba(255, 255, 255, 0.2);
  padding: 3px;
  border-radius: 6px;
  width: 250px;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.12), 0 2px 2px rgba(0, 0, 0, 0.08);
}
.container_timeline ul li:not(:first-child) {
  margin-top: 30px;
}
.container_timeline ul li > span {
  width: 2px;
  height: 100%;
  background: #660000;
  left: -30px;
  top: 0;
  position: absolute;
}
.container_timeline ul li > span:before, .container_timeline ul li > span:after {
  content: "";
  width: 8px;
  height: 8px;
  border-radius: 50%;
  border: 2px solid #660000;
  position: absolute;
  background: #86b7e7;
  left: -5px;
  top: 0;
}
.container_timeline ul li span:after {
  top: 100%;
}
.container_timeline ul li > div {
  margin-left: 10px;
}
.container_timeline div .title, .container_timeline div .type {
  font-weight: 600;
  font-size: 12px;
}
.container_timeline div .info {
  font-weight: 300;
}
.container_timeline div > div {
  margin-top: 5px;
}
.container_timeline span.number {
  height: 100%;
}
.container_timeline span.number span {
  position: absolute;
  font-size: 10px;
  left: -35px;
  font-weight: bold;
  color:  #339900;
}
.container_timeline span.number span:first-child {
  top: 0;
}
.container_timeline span.number span:last-child {
  top: 100%;
}

</style>    
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
               <div style=" clear: both;" class="box">
     <?php
    $q=$this->db->query("SELECT * FROM `staff` where id= '".$this->session->userdata("staff_id")."' ");
    $q = $q->row();
   ?>
    <div  style="float: left; width: 20%;; margin-left: 5px; margin-right: 10px;">
        
        <div  style=" width: 100%;">
      <?php
				  $inst="select logo from institutes where id='".$this->session->userdata("staff_Org_id")."'";
				  $inst=$this->db->query($inst);
                  $inst = $inst->row();
				
				  $inst =$inst->logo;
				  if(strlen($inst)==0){
					
					  ?>
           <img src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style=" width: 100%; height:133px;"   >
                <?php
				  }else{
					  if(!file_exists(assets_path."/uploads/".$inst)){
					  ?>
				<img src="<?php echo assets_path  ?>/uploads/<?php  echo $inst ?>" alt="..." style="height:133px; width:100%;" alt="<?php echo assets_path  ?>/uploads/snetwork.png"   >
					 <?php
					
					 }
                 else{
					 
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style=" height:133px; width:100%;"   >
            
                <?php
				 }
					  
					  
				  }
			?>
    </div>
        <hr/>
        <div style=" border: 1px solid #999999; border-radius: 10px; height: 250px;   " class="box">
            <div  style=" float: left;text-align: center; width: 100%; padding-top: 25px; padding-left: 5px; margin-bottom: 5px; border-bottom: 1px solid #cccccc">
                <?php
              if(strlen($q->img)==0){
                 ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 80px;; height: 80px;"   >
            
                <?php
              }else{
                  if(file_exists(assets_path."/uploads/".$q->img)){
                  ?>
            <img src="<?php echo assets_path  ?>/uploads/<?php  echo $q->img  ?>" alt="..." style=" width: 80px;; height: 80px;" alt="<?php echo assets_path  ?>/uploads/dummy_user.png"   >
                 <?php
                 }
                 else{
                     ?>
           <img src="<?php echo assets_path  ?>/uploads/dummy_user.png" alt="..." style=" width: 80px;; height: 80px;"   >
            
                <?php
                 }
              }
              ?>
           <br/><br/>
            </div>
            <div  style=" float: left; width: 100%;padding-top: 25px; font-size: 15px; padding-left: 17px;" >
                <i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo ucfirst ($q->name) ?><br/>
                <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $q->email ?><br/>
                <i class="fa fa-phone-square"></i>&nbsp;&nbsp;<?php echo $q->phone ?><br/>
                <i class="fa fa-briefcase"></i>&nbsp;&nbsp;<?php echo ($q->qualification) ?></strong>
                             
            </div>
           
        </div>
        
    </div>
                   <div  style="float: left; width: 40%;; margin-left: 5px; margin-right: 10px; height: 430px; border-radius: 10px; border: 1px solid #cccccc">
<!--                      <form style=" float: right;  ">
                          <input style=" width: 85%; float: left" type="text" id="date" name="date" placeholder="Place Select date" class="form-control datepick" value="<?php echo $this->input->get('date') ?>">
                            <button style=" width: 15%; height: 34px; float: left" ><i class="fa fa-search"></i></button>
                      </form>-->
                      <?php
                       $t=time();
         if( isset($_GET['date'])){
             $t= explode('/', urldecode($_GET['date'])) ;
             $t= mktime(0, 0, 0, $t[1], $t[0], $t[2]);
          }
         $time=  getdate($t);
         $query=$this->db->query("SELECT t.time_start,t.time_end,t.day,s.name as section,c.name as cls_name,sub.subject FROM class_routine t JOIN course cr ON t.course_id=cr.cid JOIN section s ON cr.secid=s.sid JOIN class c ON s.class_id=c.id JOIN subjects sub ON cr.subid=sub.sid WHERE cr.tid='".$this->session->userdata('staff_id')."' AND t.day='".$time['wday']."' ORDER BY  t.time_start ASC ");
         $query=$query->result();

                      ?>
                      <div id="container_timeline" class="container_timeline" style=" width: 100%; ">
      
                    <?php
          if(sizeof($query)>0){
              ?>
                          <h3 style=" clear: both; text-align: center;">Schedule On <?php echo date("d-m-Y", $t) ?></h3>
                  <ul><?php
              foreach ($query as $value) {
                ?>
          <li><span></span>
          <div>
            <div class="title"><?php echo $value->cls_name ."  --  ". $value->section ?></div>
            <div class="info"><?php echo  $value->subject  ?></div>
           </div> <span class="number"><span><?php echo substr($value->time_start, 0, strlen($value->time_start)-2).":".substr($value->time_start, strlen($value->time_start)-2,strlen($value->time_start) ) ?></span> <span><?php echo substr($value->time_end, 0, strlen($value->time_end)-2).":".substr($value->time_end, strlen($value->time_end)-2,strlen($value->time_end) ) ?></span></span>
        </li>
                    <?php
              }
              ?>
                  </ul><?php
          }else{
              ?>
                          <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                          <h5 style=" text-align: center; color: red;">** No Classes Found Today..</h5>
              <?php
          }
            
?>
     
    </div>
                   </div> 
                   <div  style="float: left; width: 36%; padding-left: 7px ; padding-right: 7px; padding-top: 0px ;  margin-left: 5px; margin-right: 10px;border-radius: 10px; border: 1px solid #cccccc; height: 430px ">
                    <?php
                        $t= getdate();
                       
                        $t= mktime(0, 0, 0, $t['mon'], $t['mday'], $t['year']);
                        $sections=array();
                        $sec_ids="";
                        /*$query=$this->db->query("SELECT s.sid,s.name as section ,cl.name as class ,(select count(*) from student where section_id=s.sid  ) as students FROM `section`  s  JOIN class cl ON s.class_id=cl.id WHERE cls_tch_id='".$this->session->userdata('staff_id')."'");
                        $query=$query->result();
                        foreach($query as $value){
                            $sec_ids.=$value->sid.",";
                          $sections[$value->sid]=array('id'=>$value->sid,'section'=>$value->section,'class'=>$value->class,'students'=>$value->students);       
                        }*/
                        $query=$this->db->query("SELECT  DISTINCT(c.secid) ,s.name as section, cl.name as class ,(select count(*) from student where section_id=c.secid  ) as students FROM `course` c JOIN section s ON c.secid=s.sid JOIN class cl ON s.class_id=cl.id where tid='".$this->session->userdata('staff_id')."'");
                        $query=$query->result();
                        foreach($query as $value){
                             $sec_ids.=$value->secid.",";
                              $sections[$value->secid]=array('id'=>$value->secid,'section'=>$value->section,'class'=>$value->class,'students'=>$value->students);       
                        }
                        $sec_ids = substr($sec_ids, 0,strlen($sec_ids)-1);
                        $att= "SELECT (SELECT count( DISTINCT(student)) from attendance WHERE date_id IN (group_concat(d.id))  ) as students ,d.section  from attendance_date d  WHERE d.day='".$t."' AND d.section IN (".$sec_ids.")   GROUP BY d.section ";
                        $att = $this->db->query($att)->result();
                        foreach ($att as $value) {
                            $sections[$value->section]['absenties']=$value->students;
                        }
                        ?>
                       <h3 style=" text-align: center">Students Attendance </h3>
                        <?php
                        foreach($sections as $key=>$value){
                      
                            ?>
                       <div style=" color: #006699; font-size:  18px;" >
                           <h5 style=" float: left;  font-variant: bold; width: 100%; font-weight:  bold "><?php echo $value['class']." - ".$value['section'] ?></h5>
                       </div>
                       <br/> <br/>
                       <?php
                        if( (isset($value['absenties'])) &&($value['students']!=0) ) {
                            $per = (  ($value['students']-$value['absenties'])/($value['students']))*100;
                            $per= number_format($per,2)
                             ?>
                       <div class="skills html" style=" clear: both;  text-align:right;  padding-right: 20px; 
  line-height: 30px;
  color: white;    width: 100%; background-color: #006699 ">
                           <span style="text-align: right; float: left; padding-left: 10px">Total (<?php echo ($value['students']) ?>)</span>
                           Present (<?php echo ($value['students']-$value['absenties']) ?>) : <?php echo  $per ?>%</div>
  
                                 
                                 <?php
                        }else{
                            ?>
                       <div class="skills html" style=" clear: both;  text-align:center;  padding-right: 20px; 
  line-height: 30px;
  color: white;    width: 100%; background-color: #006699 ">
                       Attendance Record Not Found
                       </div>
                           <?php
                        }
                         
                       ?>
                            <?php
                        }
                        ?>
                   </div>
            </div>
       </div>
    </div>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>




<?php
$this->load->view('structure/footer');
?>