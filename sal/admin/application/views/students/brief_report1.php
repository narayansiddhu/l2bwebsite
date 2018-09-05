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
            <li >
                <a href="<?php echo base_url(); ?>index.php/students/view">Students</a>
            </li>
        </ul>
    </div>
    
        <?php
         $q="SELECT s.name as sec_name, s.sid ,c.id ,c.medium,c.name as cls_name , (select count(*) from student  where section_id = s.sid AND sex =2  ) as female ,(select count(*) from student  where section_id = s.sid AND sex =1  ) as male   FROM section s JOIN class c ON s.class_id = c.id AND s.iid= '".$this->session->userdata('staff_Org_id')."' ";
        $q = $this->db->query($q);
        
        ?> 
   
    <div class="box">
        <div class="col-sm-9 nopadding">
            <div class="box box-color box-bordered nopadding">
   <div class="box-title">
       <h3><i class="fa fa-child"></i>View Students</h3> 
       <div class="actions" style=" color: whitesmoke; font-size: 18px; ">
           <a target="_blank" href="<?php echo base_url() ?>index.php/students/print_brief_report" class="btn btn-primary " style=" background-color: white; color: #386EE0" ><i class="fa fa-print"></i>&nbsp;Print</a> 
       </div>
   </div>
       <div class="box-content nopadding" style=" max-height: 500px; overflow-y: scroll" >
            <table class="table datatable table-hover table-nomargin  table-bordered">
                <thead>
                    <tr>
                           <th>Class</th>
                           <th>Section</th>
                           <th>Medium</th>
                            <th style="   text-align: center">Boys</th>
                            <th style="    text-align: center" >Girls</th>
                            <th style="  text-align: center" >Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                      if($q->num_rows()>0){
                          $q = $q->result();
                        $i=1;$total_boys=0;$total_girls=0;
                          foreach ($q as $value) {
                              ?> 
                            <tr>
                                <td><a href="<?php echo base_url() ?>index.php/academics/view_section/<?php echo $value->sid ?>"><?php echo $value->cls_name ?></a></td> 
                                <td><a href="<?php echo base_url() ?>index.php/academics/view_section/<?php echo $value->sid?>"><?php echo $value->sec_name ?></a></td> 
                                <td><?php echo $medium[$value->medium] ?></td>
                                <td style=" color: #006699; text-align: center"><?php echo $value->male ?></td>
                                <td style=" color: #ff00cc; text-align: center"><?php echo $value->female ?></td>
                                <td style=" color: #ff9933; text-align: center"><?php echo ($value->female + $value->male ) ?></td>
                            </tr>
                              <?php
                              $total_boys =$total_boys+$value->male;
                              $total_girls =$total_girls+$value->female;
                          }
                          ?>
                            <tr>
                                <td colspan="3" style=" text-align: right">Total : </td>
                                <td style=" color: #006699; text-align: center"><?php echo $total_boys ?></td>
                                <td style=" color: #ff00cc; text-align: center"><?php echo $total_girls ?></td>
                                <td style=" color: #ff9933; text-align: center"><?php echo ($total_boys + $total_girls ) ?></td>
                            </tr>
                          <?php
                      }else{
                          ?>
                    <tr>
                        <td colspan="5" style=" text-align: center">No Records Found</td>
                    </tr>
                         <?php
                      }
                    ?>
                </tbody>
            </table>
   </div>
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
            text: 'Total School Strength : <?php echo $total_boys+$total_girls ?>'
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
            name: 'Total Strength (<?php echo $total_boys+$total_girls ?>)',
            data: [
                ['Boys (<?php echo $total_boys ?>)', <?php echo ($total_boys/( $total_boys+$total_girls))*100 ?>],
                ['Girls (<?php echo $total_girls ?>)', <?php echo ($total_girls/( $total_boys+$total_girls))*100 ?>],
                
            ]
        }]
    });
});

		</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

        </div>
        </div>
        
        <div class="col-sm-3 nopadding">
            <br/>
            <div class="col-sm-12 ">
                <a href="<?php echo base_url() ?>index.php/students/add" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-user-plus fa-2x" aria-hidden="true"></i>&nbsp;Create Student</h4></a>
            </div>
            <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;" >
                <a href="<?php echo base_url() ?>index.php/Admissions/" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ; text-align: center"><h4><i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;Admissions</h4></a>
            </div>
<!--            <div class="col-sm-12" style=" margin-top: 5px; margin-bottom: 5px;">
                <a  href="<?php echo base_url() ?>index.php/students/bulk_add" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke; text-align: center"><h4><i class="fa fa-users fa-2x" aria-hidden="true"></i>&nbsp; Create Bulk Student</h4></a>
            </div>-->
            <div class="col-sm-12" style="margin-top: 5px; margin-bottom: 40px;">
                <a href="<?php echo base_url() ?>index.php/students/search" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke; text-align: center"><h4><i class="fa fa-search fa-2x" aria-hidden="true"></i>&nbsp; Search Student</h4></a>
            </div>

            
            
            <div id="container" style=" padding-left: 10px; margin-top: 10px; width : 300px; height: 250px; ">

            </div>     
        </div>
    </div>       
            

  
    </div>
</div>
</div>
<?php
$this->load->view('structure/footer');
?>
