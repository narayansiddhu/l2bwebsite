<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
           
        <?php
                $s=$this->db->query("SELECT status,count(*) as counter FROM `lib_books` WHERE iid='".$this->session->userdata('staff_Org_id')."'  group by status ");
                $s=$s->result();
                $lib_data=array(
                    'issued' =>0,
                    'available'=>0,
                    'total'=>0
                );



                foreach($s as $value){
                   if($value->status==2){
                      $lib_data['issued'] =$lib_data['issued']+$value->counter;
                   }elseif($value->status==1){
                      $lib_data['available'] =$lib_data['available']+$value->counter;
                   }

                }

                $lib_data['total']=$lib_data['available']+$lib_data['issued'];
                
                $pay=$this->db->query(" SELECT SUM( amount ) AS total FROM  `lib_payments`  WHERE iid ='".$this->session->userdata('staff_Org_id')."'  ");
                $pay = $pay->row();
                $pay = $pay->total;
                
                ?>
                <div class="box  box-bordered " style="clear: both;margin-top:2px;">
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #ff6600">
                            <i class="fa fa-book fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php echo $lib_data['total'] ?>
                                
                            </strong>
                            <br/>
                            <p>Total Books</p>
                        </div>
                    </div>
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #669900">
                            <i class="fa fa-book fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php echo $lib_data['available'] ?>
                            </strong>
                            <br/>
                            <p>Available Books</p>
                        </div>
                    </div>
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #ff3300">
                            <i class="fa fa-book fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php echo $lib_data['issued'] ?>
                            </strong><br/>
                            <p>Issued Books</p>
                        </div>
                    </div>
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #999999">
                            <i class="fa fa-inr fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php echo $pay ?>
                                
                            </strong>
                            <br/>
                            <p>Fee Collected</p>
                        </div>
                    </div>
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #006666">
                            <i class="fa fa-hand-paper-o fa-3x" aria-hidden="true"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px;">&nbsp;&nbsp;<?php echo $pay ?>
                                
                            </strong>
                            <br/>
                            <p>Pending Requests</p>
                        </div>
                    </div>
                </div>
	    <div style=" clear: both;" class="box"><br/><hr/>
     <?php
    $q=$this->db->query("SELECT * FROM `staff` where id= '".$this->session->userdata("staff_id")."' ");
    $q = $q->row();
   ?>
    <div  style="float: left; width: 24%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">
        
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
        <div style=" border: 1px solid #999999; border-radius: 10px; height: 140px;   " class="box">
            <div  style=" float: left; width: 23%; padding-top: 25px; padding-left: 5px;">
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
            </div>
            <div  style=" float: left; width: 73%;padding-top: 25px; font-size: 15px; padding-left: 17px;" >
                <i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo ucfirst ($q->name) ?><br/>
                <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $q->email ?><br/>
                <i class="fa fa-phone-square"></i>&nbsp;&nbsp;<?php echo $q->phone ?><br/>
                <i class="fa fa-briefcase"></i>&nbsp;&nbsp;<?php echo ($q->qualification) ?></strong>
                             
            </div>
           
        </div>
        
    </div>
                
                <div  style="float: left; width: 70%;; margin-top: 10px; margin-left: 5px; margin-right: 10px;">
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
            text: 'Library Books'
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
                ['Issued (<?php echo ($lib_data['issued']) ?>)', <?php echo ($lib_data['issued'] /$lib_data['total'])*100 ?>],
                ['Available (<?php echo ($lib_data['available']) ?>)', <?php echo ($lib_data['available']/$lib_data['total'])*100 ?>],
            ]
        }]
    });
});
             </script>
             <div class="col-sm-6 nopadding">
                    <div id="container" style=" margin-left: 5px;padding-right: 8px; margin-top: 5px; width : 100%; height: 300px; ">
                        <h4 style=" text-align:center;">Library Books</h4>
                             <h4 style=" text-align: center; color:  red ; margin-left: 15px; margin-top:130px; padding-right: 10px;"> **  Books Added Yet..</h4>
                     </div>
             </div>
             <?php 
                $fine=$this->db->query(" SELECT sum(fee) as fine FROM `lib_fines`  WHERE iid= '".$this->session->userdata("staff_Org_id")."'   ")->row();
                $fine =$fine->fine;
                ?>
              <script>
             $(function () {
    Highcharts.chart('container1', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Library Fee Payments'
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
                ['Collected Amount (<?php echo ($pay) ?>)', <?php echo ($pay /$fine)*100 ?>],
                ['Balance Amount (<?php echo ($fine-$pay) ?>)', <?php echo (($fine-$pay)/$fine)*100 ?>],
            ]
        }]
    });
});
             </script>
             <div class="col-sm-6 nopadding">
                    <div id="container1" style=" margin-left: 5px;padding-right: 8px; margin-top: 5px; width : 100%; height: 300px; ">
                        <h4 style=" text-align:center;">Library Fee Payments</h4>
                             <h4 style=" text-align: center; color:  red ; margin-left: 15px; margin-top:130px; padding-right: 10px;"> ** No Fine Records Found.. </h4>
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