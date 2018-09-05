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
                    <a href="<?php echo base_url(); ?>index.php">Home</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/staff">Manage staff</a>
                </li>
            </ul>
    </div> 
        <?php
if(strlen($this->session->userdata('staff_add_Sucess'))>0 ){
    ?><br/>
        <div id="successMsg" class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $this->session->userdata('staff_add_Sucess'); ?>
        </div>
    <script>
                             $("#successMsg").fadeIn();
                             $("#successMsg").delay(2000).fadeOut();
                        </script>
   <?php
    $this->session->unset_userdata('staff_add_Sucess');
}
?>  
<div class="box">
<div class="col-sm-9 nopadding">
<div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-users"></i>&nbsp;&nbsp;Staff List</h3>
                            
                </div>

            <div class="box-content nopadding" style=" max-height: 550px;  overflow-y: scroll;">
                <table class="table table-hover datatable table-nomargin table-bordered"  style=" width:100%;" >
                    <thead>
                        <tr >
                            <th>Name</th>
                            <th >Phone</th>
                            <th>E-mail</th>
                            <th >Gender</th>
                            <th >Salary</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody style=" overflow-y: scroll ; height: 500px; ">
                        <?php
                        $blood_group = unserialize (blood_groups);
                        $staff_level = unserialize (staff_level);
                        $i=0;
                        $counter_arr=array("Management"=>0,"Faculty"=>0,"Librarian"=>0,"OfficeStaff"=>0);
                       
                        if(sizeof($results)  >0){
                             foreach($results as $row) {
                                    switch($row->level){
                                       case   $row->level>=6: $counter_arr['Management']++;break;
                                        case   $row->level==5: $counter_arr['Librarian']++;break;
                                         case   $row->level==3: $counter_arr['OfficeStaff']++;break;
                                       case   $row->level==1: $counter_arr['Faculty']++;break;
                                    }
                                 ?>
                       
                                <tr  <?php 
                                   if($row->status==0){
                                       ?>
                                    style=" background-color : #cccccc"
                                       <?php 
                                   }
                                ?> >
                                    <td><?php echo $row->name;?></td>  
                                    <td><?php echo $row->phone;?></td>  
                                    <td><?php echo $row->email;?></td>   
                                    <td><?php 
                                            if($row->sex==1){
                                                echo "Male";
                                            }else{
                                                echo "female"; 
                                            }
                                        ?>
                                    </td> 
                                    <td><?php echo $row->amount;?>   </td>  
                                   <td><?php
                                  if(isset($staff_level[$row->level])){ 
                                       echo $staff_level[$row->level];
                                   }else{
                                       echo "Driver";
                                   }?></td>       
                                   <td>
                                       <a rel="tooltip" title="" data-original-title="View Staff Details" href="<?php echo base_url() ?>index.php/staff/view_staff_details/<?php echo  $row->id  ?>"><i class="fa fa-eye"></i></a>
                                       <?php 
                                        if($row->status==0){
                                            ?>
                                       <a href="<?php echo base_url(); ?>index.php/staff/change_status/<?php echo  $row->id  ?>/activate" rel="tooltip" title="" data-original-title="Activate Staff"> <i class="fa fa-toggle-on" aria-hidden="true"></i></a>
                                            <?php 
                                        }else{
                                            ?>
                                        <a  href="<?php echo base_url(); ?>index.php/staff/change_status/<?php echo  $row->id  ?>/deactivate" rel="tooltip" title="" data-original-title="De-Activate Staff"> <i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                            <?php 
                                        }
                                     ?>
                                     <?php 
                                        if($row->status==0){
                                            ?>
                                            
                                            <?php 
                                        }else{
                                            ?>
                                       <a rel="tooltip" title="" data-original-title="Edit Staff Details" href="<?php echo base_url() ?>index.php/staff/edit/<?php echo  $row->id  ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                         <?php 
                                        }
                                     ?>
                                   </td>
                                </tr>
                              <?php
                              $i++;
                             }
                         }  
                        ?>
                    </tbody>

                </table>
            </div>
        </div>

</div>
<div class="col-sm-3 nopadding">
<br/>
<div class="col-sm-12  "  >
                <a href="<?php echo base_url()  ?>index.php/staff/Add_staff" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke ;  text-align: center;  margin-top:5px;"><h4><i class="fa fa-user-plus fa-2x" aria-hidden="true"></i>&nbsp;Create Staff</h4></a>
            </div>
<!--            <div class="col-sm-12" style="margin-top: 5px;">
                <a href="<?php echo base_url()  ?>index.php/staff/bulk" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke; text-align: center"><h4><i class="fa fa-users fa-2x" aria-hidden="true"></i>&nbsp; Create Bulk</h4></a>
            </div>-->
            <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;">
                <a   href="<?php echo base_url() ?>index.php/staff/view_incharges" class=" btn btn-primary" style=" width: 100%; color: whitesmoke; text-align: center"><h4><i class="fa fa-user fa-2x" aria-hidden="true"></i>&nbsp; View In-charges</h4></a>
            </div>
            <div class="col-sm-12 " style=" margin-top: 5px; margin-bottom: 5px;">
                <a target="_blank"  href="<?php echo base_url() ?>index.php/staff/print_all" class=" btn btn-primary" style=" width: 100%;  color: whitesmoke; text-align: center"><h4><i class="fa fa-print fa-2x" aria-hidden="true"></i>&nbsp; Print Staff</h4></a>
            </div>

<?php
$s="";
if($i!=0){
    
foreach( $counter_arr as $k=>$value){
    $s.="['".$k."[ ".$value." ]', ".(($value/$i)*100)."],";
}
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
            text: 'Staff Brief Report'
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
            name: 'Percentage ',
            data: [
                <?php echo $s ?>
            ]
        }]
    });
});
             </script>  
                                           <?php

}
$s = substr($s ,0,strlen($s)-1 );
?>               

          
            
            <div id="container" style=" padding-left: 5px; margin-top: 10px; width : 330px; height: 250px; ">
               <br/><br/> <br/><br/><h4 style=" text-align: center">Staff Brief Report</h4><br/><br/><br/>
                <h4 style=" color: red; text-align: center">** No Staff Records Found..</h4>
            </div> 
             <script src="<?php echo assets_path  ?>graphs/code//highcharts.js"></script>
<script src="<?php echo assets_path  ?>graphs/code//modules/exporting.js"></script>
<script src="<?php echo assets_path  ?>graphs/code//highcharts-3d.js"></script>


</div>
</div>


    
        
    </div>
       </div>    
</div>
<?php
$this->load->view('structure/footer');
?>