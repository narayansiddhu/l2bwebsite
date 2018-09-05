<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
       <div class="box">
        <div class="col-sm-12 col-sm-offset-11"><a href="<?php echo base_url(); ?>index.php/accounts/logout"><button type="button" class="btn btn-primary"><i class="fa fa-angle-left" aria-hidden="true"></i> Back</button></a></div>
        <br><br>
    <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/expenditure/">Expenditure</a>
                </li>
            </ul>

    </div> 
   
    <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
           <div class="box ">
               <div class="col-sm-8 nopadding ">
                   <?php
                   if($this->session->userdata("staff_level")>=6){
                         ?>
                   <div class="box">
                       <div class="col-sm-6 nopadding">
                             <?php
                              $q=" SELECT c.name , (select sum(amount) from expenditure where category=c.cat_id AND status=2 ) as t_amount  FROM `expense_category` c  where  iid='".$this->session->userdata("staff_Org_id")."' ";
                              $q=$this->db->query($q)->result();
                             if(sizeof($q)>0){
                                 $str=""; $total=0;
                                 foreach($q as $value){
                                   $total+=$value->t_amount;
                                 }
                                 foreach($q as $value){
                                   $str.="{
            name: '".$value->name."',
            y: ".( number_format(($value->t_amount/$total)*100,2) )."
        },";
                                 }
                                 $str = substr($str, 0, strlen($str)-1);
                                 ?>
                           
                             <div id="container_list"  >
                             </div>
		<script type="text/javascript">

Highcharts.chart('container_list', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Approved Expenditure'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [<?php echo $str ?>]
    }]
});
		</script>
                                     <?php
                             }else{
                                 ?>
                <h4 style=" color: red ; text-align: center">Approved Expenditure</h4>
            <br/>
                                         <h4 style=" margin-top: 20px; color: red ">** No Records Found..</h4>
                             
                                     <?php
                             }
                              
                             ?>
                             
                       </div>
                       <div class="col-sm-6 nopadding">
                           <?php
                            $s=$this->db->query(" SELECT status,sum(amount) as  total FROM `expenditure` where iid='".$this->session->userdata("staff_Org_id")."' GROUP BY status  ")->result();
                             if(sizeof($s)>0){
                                 $t=0;
                                 $approved=$pending=$rejected=0;
                                 foreach($s as $value){
                                     $t+=$value->total;
                                    switch($value->status){
                                        case 0 :$rejected+=$value->total;break;
                                        case 1 :$pending+=$value->total;break;
                                        case 2 :$approved+=$value->total;break;
                                    }
                                 }
                                
                                $approved=  number_format(($approved/$t)*100);
                                   $pending=  number_format(($pending/$t)*100);
                                  $rejected=  number_format(($rejected/$t)*100);
                                 ?>
                                  <div id="container_category"  >
                             </div>
		<script type="text/javascript">

Highcharts.chart('container_category', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Expenditure List'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{ name: 'Approved ', y: <?php echo $approved ?> },{ name: 'Rejected', y: <?php echo $rejected ?> },{ name: 'Pending', y: <?php echo  $pending ?> }]
    }]
});
		</script>    
                                 <?php
                             }else{
                                 ?>
                <h4  style=" text-align: center; ">Expenditure Status</h4>
                           
                                 <br/><br/><br/>
                                 <h4 style=" color: red; text-align: center">** No Expenditure Records Added yet</h4>
                                  <?php
                             }
                            
                            ?>
                       </div>
                   </div>
                         <?php
                         }else{
                             ?>
                         
                           <div class="box">
                       <div class="col-sm-6 nopadding">
                             <?php
                              $q=" SELECT c.name , (select sum(amount) from expenditure where category=c.cat_id AND status=2 AND  staff_id='".$this->session->userdata("staff_id")."' ) as t_amount  FROM `expense_category` c  where  iid='".$this->session->userdata("staff_Org_id")."' ";
                              $q=$this->db->query($q)->result();
                             if(sizeof($q)>0){
                                 $str=""; $total=0;
                                 foreach($q as $value){
                                   $total+=$value->t_amount;
                                 }
                                 
                                 foreach($q as $value){
                                   $str.="{
            name: '".$value->name."',
            y: ".( number_format(($value->t_amount/$total)*100,2) )."
        },";
                                 }
                                 $str = substr($str, 0, strlen($str)-1);
                                 ?>
                           
                             <div id="container_list"  >
                             </div>
		<script type="text/javascript">

Highcharts.chart('container_list', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Approved Expenditure'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [<?php echo $str ?>]
    }]
});
		</script>
                                     <?php
                             }else{
                                 ?>
                <h4 style=" color: red ; text-align: center">Approved Expenditure</h4>
            <br/>
                                         <h4 style=" margin-top: 20px; color: red ">** No Records Found..</h4>
                             
                                     <?php
                             }
                              
                             ?>
                             
                       </div>
                       <div class="col-sm-6 nopadding">
                           <?php
                            $s=$this->db->query(" SELECT status,sum(amount) as  total FROM `expenditure` where iid='".$this->session->userdata("staff_Org_id")."' AND staff_id='".$this->session->userdata("staff_id")."'  GROUP BY status  ")->result();
                             if(sizeof($s)>0){
                                 $t=0;
                                 $approved=$pending=$rejected=0;
                                 foreach($s as $value){
                                     $t+=$value->total;
                                    switch($value->status){
                                        case 0 :$rejected+=$value->total;break;
                                        case 1 :$pending+=$value->total;break;
                                        case 2 :$approved+=$value->total;break;
                                    }
                                 }
                                
                                $approved=  number_format(($approved/$t)*100);
                                   $pending=  number_format(($pending/$t)*100);
                                  $rejected=  number_format(($rejected/$t)*100);
                                 ?>
                                  <div id="container_category"  >
                             </div>
		<script type="text/javascript">

Highcharts.chart('container_category', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Expenditure List'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{ name: 'Approved ', y: <?php echo $approved ?> },{ name: 'Rejected', y: <?php echo $rejected ?> },{ name: 'Pending', y: <?php echo  $pending ?> }]
    }]
});
		</script>    
                                 <?php
                             }else{
                                 ?>
                <h4  style=" text-align: center; ">Expenditure Status</h4>
                           
                                 <br/><br/><br/>
                                 <h4 style=" color: red; text-align: center">** No Expenditure Records Added yet</h4>
                                  <?php
                             }
                            
                            ?>
                       </div>
                   </div>  
                   <?php
                         }
                   ?>
               </div>
               <div class="col-sm-4 nopadding" style=" margin-top: 20px;">
                    <div class="col-sm-12 ">
                        <a href="<?php echo base_url() ?>index.php/expenditure/add_new" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-plus fa-2x" aria-hidden="true"></i>&nbsp;Add Expenditure</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/category" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-object-group fa-2x" aria-hidden="true"></i>&nbsp;Expenditure Categories</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/history" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-eye fa-2x" aria-hidden="true"></i>&nbsp;View History </h4></a>
                    </div>
                   <?php 
                     if($this->session->userdata("staff_level")>=6){
                         ?>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/view" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View All  </h4></a>
                    </div>
                    
                   <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/approvals" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp; Approvals </h4></a>
                    </div>
                   <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/approved_list" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;Approved List </h4></a>
                    </div>
                    <div class="col-sm-12 "  style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/expenditure/rejected_list" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-o-down fa-2x" aria-hidden="true"></i>&nbsp;Rejected List</h4></a>
                    </div>
                         <?php
                     }
                   ?>
                    
                    
                </div>
           </div>
           
     
</div>
    </div>
</div>

<?php

$this->load->view('structure/footer');

?>