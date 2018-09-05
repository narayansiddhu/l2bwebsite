<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
		<script type="text/javascript">
$(function () {

    // Parse the data from an inline table using the Highcharts Data plugin
    Highcharts.chart('container', {
        data: {
            table: 'freq',
            startRow: 1,
            endRow: 17,
            endColumn: 7
        },

        chart: {
            polar: true,
            type: 'column'
        },

        title: {
            text: 'Total School Fee Summary'
        },

        subtitle: {
            text: ''
        },

        pane: {
            size: '80%'
        },

        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 100,
            layout: 'vertical'
        },

        xAxis: {
            tickmarkPlacement: 'on'
        },

        yAxis: {
            min: 0,
            endOnTick: false,
            showLastLabel: true,
            title: {
                text: 'Frequency (%)'
            },
            labels: {
                formatter: function () {
                    return this.value + '%';
                }
            },
            reversedStacks: false
        },

        tooltip: {
            valueSuffix: '%'
        },

        plotOptions: {
            series: {
                stacking: 'normal',
                shadow: false,
                groupPadding: 0,
                pointPlacement: 'on'
            }
        }
    });
});
		</script>

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
                            <a href="<?php echo base_url(); ?>index.php/accounts/view/">Manage Fees</a>
                        </li>
                    </ul>
            </div> 
            
            <?php
                if(strlen($this->session->userdata('Fee_added_Sucess'))>0 ){
                    ?><br/>
                        <div  id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('Fee_added_Sucess'); ?>
                        </div>
                    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(2000).fadeOut();
                        </script>
                   <?php
                    $this->session->unset_userdata('Fee_added_Sucess');
                }
            ?>
            <div class="box">
                <div class="col-sm-8 nopadding" >
                    <div class="box box-bordered box-color ">
                <div class="box-title">
                    <h3><i class="fa fa-inr"></i>Manage Fee</h3>
                </div>
                <div  class="box-content nopadding">
                        <table class="table table-hover table-nomargin dataTable table-bordered" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Class - Section</th>
                                    <th>Fee</th>
                                    <th>Students</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Concession</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                  $query ="SELECT s.sid,c.id as class_id,s.name as section,c.name as class,(SELECT SUM(`fee`) FROM `fee_class` where cls_id= c.id) as total, ( SELECT sum(amount) FROM `fee_accounts` fc JOIN student s ON fc.student_id=s.student_id WHERE s.section_id=s.sid ) AS paid ,( SELECT sum(amount) FROM `fee_concession` fcs JOIN student s ON fcs.std_id=s.student_id WHERE s.section_id=s.sid ) AS concession , (select count(*) from student where section_id=s.sid ) as students FROM `section` s JOIN class c ON s.class_id=c.id WHERE s.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY c.numeric_val DESC ,s.sid ASC";
                                $paid=0;$total=0;$concession=0;
                                 $query=$this->db->query($query);
                                 $query=$query->result();
                                 foreach ($query as $value) {
                                       if($value->total!=NULL){
                                           $total+=($value->total*$value->students);
                                           $paid+=$value->paid;
                                           $concession+= $value->concession;
                                           }
                                                    ?>
                                <tr>
                                    <td><?php echo $value->class." - ". $value->section ?></td>
                                    <td><?php 
                                    if($value->total!=NULL){
                                        echo $value->total;
                                    }else{
                                        echo "0";
                                    } 

                                    ?></td>
                                    <td><?php   echo $value->students; ?></td>
                                    <td><?php 
                                    if($value->total!=NULL){
                                        echo ($value->total * $value->students) ;
                                    }else{
                                        echo "0";
                                    } 

                                    ?></td>
                                    <td><?php 
                                    if($value->total!=NULL){
                                        echo ($value->paid);
                                    }else{
                                        echo "0";
                                    } 

                                    ?></td>
                                    <td><?php 
                                    if($value->total!=NULL){
                                        echo ($value->concession);
                                    }else{
                                        echo "0";
                                    } 

                                    ?></td>
                                    <td>
                                        <?php
                                    if($value->total!=NULL){
                                    ?>       
                                    <a  href="<?php echo base_url(); ?>index.php/accounts/view/<?php echo ($value->class_id) ?>/<?php echo ($value->sid) ?>"><i class="fa fa-eye"></i></a>

                                    <a  target="_blank" href="<?php echo base_url(); ?>index.php/accounts/print_report/<?php echo ($value->class_id) ?>/<?php echo ($value->sid) ?>"><i class="fa fa-print"></i></a>

                                      <?php
                                        } 
                                        ?>
                                        <?php
                                          if($this->session->userdata("staff_level")>7){
                                              if($value->total!=NULL){
                                                ?>
                                                    <a rel="tooltip" title="" data-original-title="Edit Fee Structure" href="<?php echo base_url() ?>index.php/fee/edit/<?php echo $value->class_id ?>"> <i class=" fa fa-cogs"></i></a> 
                                                   <?php
                                                 }else{
                                                    ?>
                                                  <a rel="tooltip" title="" data-original-title="Create Fee Structure" href="<?php echo base_url() ?>index.php/fee/add_new/?cls_id=<?php echo $value->class_id ?>"> <i class=" fa fa-cog"></i></a> 
                                                   <?php
                                                }
                                        }
                                        ?>
                                        
                                        
                                    </td>
                                </tr>
                                    <?php
                                 }
                                ?>
                            </tbody>
                        </table>
                </div>
            </div>
                </div>
                <?php
                 if($total >0){
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
            text: 'Total Fee Summary : <?php echo $total ?>'
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
            name: 'Total Fee : (<?php echo $total ?>)',
            data: [
                ['Paid (<?php echo $paid  ?>)', <?php echo ($paid/$total)*100 ?>],
                ['Concession (<?php echo $concession  ?>)', <?php echo ($concession/$total)*100 ?>],
                ['Balance (<?php echo ($total-$paid) ?>)', <?php echo ( ($total-($paid+$concession)) /( $total+$paid))*100 ?>],
                
            ]
        }]
    });
});

		</script>
                         <?php
                 }
                ?>
                
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

                <div class="col-sm-4 nopadding" style=" margin-top: 20px;">
                    <div class="col-sm-12 ">
                        <a href="<?php echo base_url() ?>index.php/fee/category" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-object-group fa-2x" aria-hidden="true"></i>&nbsp;Fee Categories</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/fee/manage_concessions" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-minus-square fa-2x" aria-hidden="true"></i>&nbsp;Manage Concessions</h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/accounts/search_record" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-search fa-2x" aria-hidden="true"></i>&nbsp;Search </h4></a>
                    </div>
                    <div class="col-sm-12 " style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/accounts/search_record" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp;Payment Approvals </h4></a>
                    </div>
                    <div class="col-sm-12 "  style=" margin-top: 8px;">
                        <a href="<?php echo base_url() ?>index.php/accounts/add_record" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-money fa-2x" aria-hidden="true"></i>&nbsp;Pay Fee</h4></a>
                    </div>
                    <div id="container" style=" padding-left: 10px; margin-top: 10px; width : 400px; height: 250px; ">
                        <br/><br/><br/><h4 style=" color: red; text-align: center">Fee Structure Not Yet Configured..</h4>
                    </div>

                </div>
            </div>
            
                            
     </div>
    </div>
</div>
    

<?php

$this->load->view('structure/footer');

?>