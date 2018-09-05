<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>		
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');

$medium = unserialize (medium);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
    
            <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>index.php/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php/student">Students</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="">Admissions List</a>
            </li>
        </ul>
    </div>
    <?php
        $cls= "SELECT c.id, c.name , (SELECT count(*)  FROM `student_admission` WHERE class_id =c.id AND status=2 ) as total from class c WHERE iid='".$this->session->userdata('staff_Org_id')."'   "; 
        $cls=$this->db->query($cls)->result();  
      
        $cls_array=array();
        $total=0;
        foreach ($cls as $value) {
               $cls_array[$value->id]=array("name" =>$value->name,"adm_total" =>$value->total);
             $total+=$value->total;  
        }
        if($total!=0){
            $gr_str="";
            foreach ($cls_array as $value) {
                $gr_str.="{
                    name: '".$value['name']."',
                    y: ".($value['adm_total']/$total)."
                },";
            }
            $gr_str = substr($gr_str, 0,  strlen($gr_str)-1);
            $gr_str;
             
             ?>
             <script type="text/javascript">


$(document).ready(function () {

    // Build the chart
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Class Wise Report'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [<?php echo $gr_str ?>]
        }]
    });
});
		</script>    
                 
             <?php
        }
        
         $q="SELECT status , count(*) as total  FROM `student_admission` WHERE iid='".$this->session->userdata('staff_Org_id')."' GROUP BY status  ";        
        $q=$this->db->query($q)->result();  
        $total=0;
        $pending=$approved=$rejected="";
        foreach ($q as $value) {
            $total+=$value->total; 
            switch ($value->status) {
                case 0: $rejected+=$value->total;
                        break;
                case 1: $pending+=$value->total;
                                        break; 
                case 2: $approved+=$value->total;
                        break;
            }
            
        }
        if($total!=0){
           //status_container
            ?>
            <script type="text/javascript">


$(document).ready(function () {

    // Build the chart
    Highcharts.chart('status_container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Status Wise Report'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Percenatge',
            colorByPoint: true,
            data: [
                {
                name: 'Rejected',
                y: <?php echo $rejected/$total ?>
            }, {
                name: 'Approved',
                y: <?php echo $approved/$total ?>,
                sliced: true,
                selected: true
            }, {
                name: 'pending',
                y: <?php echo $pending/$total ?>
            }
            ]
        }]
    });
});
		</script> 
                <?php
        }
        
        ?>
            
            <?php
        $s="SELECT s.*,c.name as cls_name FROM `student_admission` s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."'  ";
        $s=$this->db->query($s)->result();  
        
    ?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

            <div class="col-sm-9">
                <br/>
                <div class="col-sm-6 nopaddinng">
                   
                    <div id="container" style="min-width: 310px; height: 400px; width: 100%; margin: 0 auto">
                        <h4 style=" text-align: center">Class Wise Report</h4>
                        <h4 style=" padding-top: 150px; text-align: center; color: red">** No Admissions Approved Till Date</h4>
                    </div>
                </div>
                <div class="col-sm-6 nopaddinng">
                    <div id="status_container" style="min-width: 310px; height: 400px; width: 100%; margin: 0 auto">
                         <h4 style=" text-align: center">Status Wise Report</h4>
                        <h4 style=" padding-top: 150px; text-align: center; color: red">** No Admissions Records Found..</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 nopadding">
                <br/><br/><br/>
                <div class="col-sm-12 ">
                    <a href="<?php echo base_url() ?>index.php/Admissions/add_new" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-plus fa-2x" aria-hidden="true"></i>&nbsp;Add Admission</h4></a>
                </div>
                <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;" >
                    <a href="<?php echo base_url() ?>index.php/Admissions/approved" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-up" aria-hidden="true"></i>&nbsp;Approved Admissions</h4></a>
                </div>
                <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;" >
                    <a href="<?php echo base_url() ?>index.php/Admissions/pending" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-hand-rock-o" aria-hidden="true"></i>&nbsp;Pending Admissions</h4></a>
                </div>
                <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;" >
                    <a href="<?php echo base_url() ?>index.php/Admissions/rejected" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-down" aria-hidden="true"></i>&nbsp;Rejected Admissions</h4></a>
                </div>
                <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;" >
                    <a href="<?php echo base_url() ?>index.php/Admissions/" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-down" aria-hidden="true"></i>&nbsp;Approved Report</h4></a>
                </div>
            </div>
            
            
</div>
</div>
</div>
<?php
$this->load->view('structure/footer');
?>
