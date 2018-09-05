<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
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
    <div  style="float: left; width: 20%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">
        
        <div  style=" width: 100%;">
      <?php
				  $inst="select logo from institutes where id='".$this->session->userdata("staff_Org_id")."'";
				  $inst=$this->db->query($inst);
                  $inst = $inst->row();
				
				  $inst =$inst->logo;
				  if(strlen($inst)==0){
					
					  ?>
           <img src="<?php echo assets_path  ?>/uploads/snetwork.png" alt="..." style=" width: 100%; height:133px;">
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
                  <div  style="float: left; width: 58%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">
                     <?php
                     $total =0;
                         $fee="SELECT sum(fee) as t_fee , (select count(*) from student where class_id=f.cls_id )  as stud FROM `fee_class` f where f.iid='".$this->session->userdata("staff_Org_id")."'   GROUP BY cls_id";  
                           $fee = $this->db->query($fee)->result();
                          // print_r($fee);
                           foreach ($fee as $value) {
                            //   echo "<br/>".($value->t_fee*$value->stud);
                               $total+=($value->t_fee*$value->stud);
                           }
                           $con= "SELECT sum(amount) as total  FROM `fee_concession` WHERE iid='".$this->session->userdata("staff_Org_id")."'  ";
                           $con = $this->db->query($con)->row();
                           if(strlen($con->total)>0){
                                   $con=  $con->total;
                               }else{
                                   $con= 0;
                               }
                           $collected="SELECT sum(amount) as total  from fee_accounts where iid='".$this->session->userdata('staff_Org_id')."' ";
                               $collected =$this->db->query($collected)->row();
                               if(strlen($collected->total)>0){
                                   $collected=  $collected->total;
                               }else{
                                   $collected= 0;
                               }
                           $balanace = $total-($con+$collected);
                          
                    ?>
                      <script>
             $(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Fee Payments'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Paid (<?php echo ($collected) ?>)', <?php echo ($collected /$total)*100 ?>],
                ['Concession (<?php echo $con ?>)', <?php echo ($con /$total)*100 ?>],
                ['Balaance (<?php echo ($balanace) ?>)', <?php echo ($balanace/$total)*100 ?>],
            ]
        }]
    });
});
             </script>
                      <div class="col-sm-11 nopadding">
                          <div id="container" style=" margin-left: 5px;padding-right: 8px; margin-top: 5px; width : 100%; height: 450px; ">
                        <h4 style=" text-align:center;">Fee Payments</h4>
                             <h4 style=" text-align: center; color:  red ; margin-left: 15px; margin-top:130px; padding-right: 10px;"> ** No Fee Records Found.. </h4>
                     </div>
                      </div>
                      
                      
                   </div> 
                   <div  style="float: left; width: 18%;; margin-top: 10px; margin-left: 5px; margin-right: 10px; ">
        
                       <div class="box  box-bordered " style="clear: both;margin-top:2px;">
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color:  #ff6600">
                            <i class="fa fa-user fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;
                               <?php 
                               $s="SELECT count(*) as total  from staff where iid='".$this->session->userdata('staff_Org_id')."' ";
                               $s =$this->db->query($s)->row();
                               echo $s->total;
                               ?>
                            </strong>
                            <br/>
                            <p>Staff</p>
                        </div>
                    </div>
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color: #669900">
                            <i class="fa fa-child fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php 
                               $s="SELECT count(*) as total  from student where iid='".$this->session->userdata('staff_Org_id')."' ";
                               $s =$this->db->query($s)->row();
                              if(strlen($s->total)>0){
                                   echo $s->total;
                               }else{
                                   echo "0";
                               }
                               ?>
                            </strong>
                            <br/>
                            <p>Students</p>
                        </div>
                    </div>
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color: #ffcc00">
                            <i class="fa fa-male fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;
                                <?php 
                               $s="SELECT count(*) as total  from parent where iid='".$this->session->userdata('staff_Org_id')."' ";
                               $s =$this->db->query($s)->row();
                               if(strlen($s->total)>0){
                                   echo $s->total;
                               }else{
                                   echo "0";
                               }
                               ?>
                            </strong><br/>
                            <p>Parents</p>
                        </div>
                    </div>
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color:  #999999">
                            <i class="fa fa-inr fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;
                                <?php 
                           echo     $collected
                               ?>
                            </strong>
                            <br/>
                            <p>Fee Collected</p>
                        </div>
                    </div>
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color:  #006666">
                            <i class="fa fa-usd fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;
                                <?php
                       $day = getdate();
                  //     print_r($day);
                 $day=       mktime(0, 0, 0, $day['mon'], $day['mday'], $day['year']);
               $s="SELECT sum(amount) as total  from fee_accounts where iid='".$this->session->userdata('staff_Org_id')."' AND time >'".$day."' ";
                               $s =$this->db->query($s)->row();
                               if(strlen($s->total)>0){
                                   echo $s->total;
                               }else{
                                   echo "0";
                               }
                               
                     ?>
                            </strong>
                            <br/>
                            <p>Today's Collections</p>
                        </div>
                    </div>
                    <div class="display_tiles1">
                        <div class="left_part" style=" background-color: #990099">
                            <i class="fa fa-thumbs-o-up fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;
                                <?php
                                $s= "select count(*) as total from fee_accounts where iid='".$this->session->userdata("staff_Org_id")."' AND status=2 ";
                               $s =$this->db->query($s)->row();
                               if(strlen($s->total)>0){
                                   echo $s->total;
                               }else{
                                   echo "0";
                               }
                                ?>
                            </strong>
                            <br/>
                            <p>Pending Fee Approvals</p>
                        </div>
                    </div>
                </div>
                       
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