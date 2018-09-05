<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<style>
    .display_tiles{
     width: 24%; 
     margin: 3px; 
     height: 65px;float: left; border : 1px solid #cccccc;
    }
    .display_tiles .left_part{
        float: left; text-align: center;
        width: 25%; height: 100%;
    }
    .display_tiles .left_part i{
        padding-top: 10px;
        color: white;
    }
    .display_tiles .rigth_part{
        float: left;padding-top: 5px;
        width: 75%;height: 100%;padding-left: 5px;
    }
    .display_tiles .rigth_part p{
        color:  #666666;
    }
    </style>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
$medium= unserialize(medium);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/timetable/view/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/timetable/view/">Transportation</a>
                        </li>

                    </ul>
            </div>
                       <br/>
            <div class="box">
                <div class="col-sm-9 nopadding">
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color:  #006699">
                            <i class="fa fa-bus fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color:  #006699">&nbsp;&nbsp;
                              <?php
                              if(strlen($this->session->userdata("vechile_count"))==0){
                                    //staff_Org_id
                                   $query=$this->db->query("SELECT count(*) as total  FROM `vehicles` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $query->total;
                                   $this->session->set_userdata("vechile_count",$query->total);  
                                }else{
                                  echo $this->session->userdata("vechile_count");  
                                }
                              ?>
                            </strong><br/>
                            <p>Vehicles</p>
                        </div>
                    </div>
                    
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #ff00cc">
                            <i class="fa fa-user fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: #ff00cc">&nbsp;&nbsp;
                              <?php
                              if(strlen($this->session->userdata("driver_count"))==0){
                                    //staff_Org_id
                                   $query=$this->db->query("SELECT count(*) as total  FROM `staff` WHERE iid='".$this->session->userdata("staff_Org_id")."' AND level=0"); 
                                   $query=$query->row();
                                   echo $query->total;
                                   $this->session->set_userdata("driver_count",$query->total);  
                                }else{
                                  echo $this->session->userdata("driver_count");  
                                }
                              ?>
                            </strong><br/>
                            <p>Drivers</p>
                        </div>
                    </div>
                    
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: gray">
                            <i class="fa fa-road fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: gray">&nbsp;&nbsp;
                              <?php
                              if(strlen($this->session->userdata("route_count"))==0){
                                    //staff_Org_id
                                   $query=$this->db->query("SELECT count(*) as total  FROM `routes` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $query->total;
                                   $this->session->set_userdata("route_count",$query->total);  
                                }else{
                                  echo $this->session->userdata("route_count");  
                                }
                              ?>
                            </strong><br/>
                            <p>Routes</p>
                        </div>
                    </div>
                    
                    <div class="display_tiles">
                        <div class="left_part" style=" background-color: #ff3333">
                            <i class="fa fa-child fa-3x"></i>
                        </div>
                        <div class="rigth_part">
                            <strong style=" font-size:  20px; color: #ff3333  ">&nbsp;&nbsp;
                              <?php
                              if(strlen($this->session->userdata("stud_trans_count"))==0){
                                    //staff_Org_id
                                   $query=$this->db->query("SELECT count(*) as total  FROM `stud_transport` WHERE iid='".$this->session->userdata("staff_Org_id")."' "); 
                                   $query=$query->row();
                                   echo $query->total;
                                   $this->session->set_userdata("stud_trans_count",$query->total);  
                                }else{
                                  echo $this->session->userdata("stud_trans_count");  
                                }
                              ?>
                            </strong><br/>
                            <p>Students using</p>
                        </div>
                    </div>
                    <?php
                    $trans_reason= unserialize(trans_reason);
                      $total= "SELECT sum(fee_amount) as total FROM `stud_transport`  where iid='".$this->session->userdata("staff_Org_id")."' ";
                      $total = $this->db->query($total)->row();
                      $total =$total->total;
                      if(strlen($total)==0){
                        $total =0;  
                      }
                      
                      $collected= "SELECT sum(fee) as total FROM `transport_fee`  where iid='".$this->session->userdata("staff_Org_id")."' ";
                      $collected = $this->db->query($collected)->row();
                      $collected =$collected->total;
                      if(strlen($total)==0){
                        $collected =0;  
                      }
                      $day =getdate();
               //       print_r($day);
                      $main_array=array();    
                      $main="SELECT sum(amount) as total, type  FROM `trans_maintaince` WHERE iid='".$this->session->userdata("staff_Org_id")."' AND (timestamp>='".mktime(0, 0, 0, $day['mon'], 1, $day['year'])."' AND timestamp<'".mktime(0, 0, 0, $day['mon']+1, 1, $day['year'])."' )GROUP BY type";
                      $main = $this->db->query($main);
                      $main = $main->result();
                      //['Collected', 100% ]
                      $mtotal=0;
                      foreach ($main as $value) {
                        $main_array[$value->type] = array('type'=>$trans_reason[$value->type],'amount' =>$value->total);  
                        $mtotal+=$value->total;
                      }
                      $str ="";
                      foreach($trans_reason as $key=>$value){
                          $m=0;
                          if(isset($main_array[$key])){
                              $m=$main_array[$key]['amount'];
                          }
                        if($m==0){
                              $str.="['".$value." (0) ', 0  ],";  
                        }else{
                          $str.="['".$value." (".$m.")', ".(($m/$mtotal)*100)."  ],";  
                        }
                      }
                      
                       $str = substr($str, 0,strlen($str)-1);
                      ?>
                    
                    
                    <script type="text/javascript">
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
            text: 'Transportation Fee '
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
            name: 'Total Amount (<?php echo $total ?>)',
            data: [
                ['Collected (<?php echo $collected ?>)', <?php echo ($collected/( $total))*100 ?>],
                ['Balance (<?php echo ($total-$collected) ?>)', <?php echo (($total-$collected)/( $total))*100 ?>],
                
            ]
        }]
    });
});

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
            text: 'Maintenance of <?php echo $day['month'] .",".$day['year'] ?> '
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
            name: 'Total :<?php echo $mtotal ?>',
            data: [
                <?php echo $str ?>
            ]
        }]
    });
});

	
		</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<hr/><br/><br/>
                    <div class="row " style=" height: auto; padding-top: 15px  ">
                        
                        <div class="col-sm-6 nopadding" style=" padding-top: 15px;">
                          <div id="container" style="margin-top: 10px; min-width : 300px; width: 90%; height: 250px; ">
                               <h4 style=" width: 100%; text-align: center; padding-top: 30px; color: red">** Add Records To View Graphical Representation</h4>
                          </div>  
                        </div>
                        <div class="col-sm-6 nopadding" style=" border-left: 1px solid #cccccc ; padding-top: 15px;">
                          <div id="container1" style="margin-top: 10px;  width: 400px; height: 250px;">
                             <h4 style=" width: 100%; text-align: center; padding-top: 30px; color: red">** Add Records To View Graphical Representation</h4>
                            
                            </div>  
                        </div>
                    </div>
                    
                </div>
                <div class="col-sm-3 nopadding">
                    <div class="col-sm-12 ">
                        <a href="<?php echo base_url() ?>index.php/transport/vehicles" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-bus fa-3x" aria-hidden="true"></i>&nbsp;Buses / vehicles</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/drivers" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-user fa-3x" aria-hidden="true"></i>&nbsp;Drivers</h4></a>
                    </div>
                    <div class="col-sm-12 "  style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/routes" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-road fa-3x" aria-hidden="true"></i>&nbsp;Routes</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/stud_details" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-child fa-3x" aria-hidden="true"></i>&nbsp;Student</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/search" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-search fa-3x" aria-hidden="true"></i>&nbsp;Search</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/maintenance" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-cogs fa-3x" aria-hidden="true"></i>&nbsp;maintenance</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/Transport/fee_payment" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-money fa-3x" aria-hidden="true"></i>&nbsp;Fee Payments</h4></a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>