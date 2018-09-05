<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$t=getdate();
if(strlen($this->input->get("month"))>0){
    $month = $this->input->get("month");
}else{
    $month=$t['mon'];
}
if(strlen($this->input->get("year"))>0){
    $year = $this->input->get("year");
}else{
     $year=$t['year'];
}
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
                            <a href="">Staff Monthly Attendance</a>
                        </li>
                    </ul>
            </div> 
            <?php
              $k= $from =mktime(0, 0, 0, $month, 1, $year);
              $to =mktime(0, 0, 0, $month +1 , 1, $year);
              
              $t = getdate($k);            
            ?>
            <div class="row">
              <div class="col-sm-12">
                <div class="box box-bordered box-color">
                    <div class="box-title">
                            <h3><i class="fa fa-th-list"></i>Monthly Attendance </h3> 
                            
                    </div>
                    <div class="box-content nopadding"> 
                
                        <form class="form-horizontal form-bordered" >
                            <div class="col-sm-6  nopadding" style=" border-bottom:  1px solid #999999" >
                            
                            <div class="form-group">
                                <label for="field-2" class="control-label col-sm-2">Month</label>
                                <div class="col-sm-10">
                                    <select class="select2-me" id="att_month" name="month"  style=" width: 90%"   >
                                    <option value="" >Month</option>
                                    <?php
                                      for ($m=1; $m<=12; $m++) {
                                          $time = mktime(0, 0, 0, $m, 1, 2016) ;
                                          $time = getdate($time);
                                          ?> <option value="<?php echo $m ?>"  <?php 
                                            if($month==$m){
                                                echo "selected";
                                            }

                                          ?> ><?php echo date('F', mktime(0,0,0,$m, 1, date('Y'))); ?></option> <?php 
                                        }
                                        ?>
                                </select>
                                </div>
                            </div>
                            
                            </div>
                            <div class="col-sm-6 nopadding" style=" border-bottom:  1px solid #999999" >
                            
                            <div class="form-group">
                                <label for="field-2" class="control-label col-sm-2">Year</label>
                                <div class="col-sm-10">
                            <select class="select2-me" id="att_year" name="year"  style=" width: 90%"   >
                                <option value="" >Year</option>
                                <?php
                                    $start=$this->db->query("SELECT timestamp FROM `institutes` WHERE id='".$this->session->userdata('staff_Org_id')."' ");
                                    $start=$start->row();
                                    $start=$start->timestamp;
                                    $start=getdate($start);
                                    $start=$start['year'];
                                    $now=getdate();
                                    $now=$now['year'];
                                    for($i=$start;$i<=$now;$i++){
                                      ?>
                                <option value="<?php echo $i; ?>"  <?php
                                        if($time['year']==$i){
                                            echo "selected";
                                        }

                                     ?> ><?php echo $i; ?></option>
                                      <?php
                                    }

                                ?>
                            </select> 
                                </div>
                            </div>
                                </div>
                            
                            <div class="form-actions " style="  text-align: center">
                                
                                <button type="submit" style=" color: white; margin-top:  10px;" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;&nbsp;Fetch Attendance</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            
            </div>         

            <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3><i class="fa fa-th-list"></i>View Attendance Of <?php echo $t['month']  ?> ,  <?php echo $t['year']  ?> </h3> 
                                    <div class="actions">
                                       
                                    </div>
                            </div>
                <?php
                $query=$this->db->query("SELECT d.id,d.day,a.status,count(*) as att FROM `staff_attendance` a JOIN  `staff_attendance_date` d ON a.date_id=d.id  WHERE d.iid='".$this->session->userdata('staff_Org_id')."' AND  ( (d.day >='".$k."') AND (d.day <'".$to."') )    GROUP BY a.date_id,a.status ORDER BY d.day   DESC ");
                $query=$query->result();
                $staff_attendance=array();
                foreach($query as $value){
                    $staff_attendance[$value->id]['day']=$value->day;
                    $staff_attendance[$value->id][$value->day][$value->status]=$value->att;
                }
                ?>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin datatable table-bordered"  style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Day</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>action's</th>
                            </tr>
                        </thead>
                        <tbody style=" max-height: 450px; overflow-y: scroll;">
                            <?php
                            $i=1;
                            foreach ($staff_attendance as $key => $value) {
                                ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo date("d-m-y",$value['day']) ?></td>
                                    <td style=" text-align: center"><?php  
                                    if(isset($staff_attendance[$key][$value['day']][1])){
                                        echo $staff_attendance[$key][$value['day']][1];                                        
                                    } else{
                                        echo "0";
                                    }
                                    ?></td>
                                    <td style=" text-align: center"><?php  
                                    if(isset($staff_attendance[$key][$value['day']][2])){
                                        echo $staff_attendance[$key][$value['day']][2];                                        
                                    } else{
                                        echo "0";
                                    }
                                    ?></td>
                                    <td>
                                        <a href="<?php echo base_url() ?>/index.php/staff/view_attendance/<?php echo $key ?>"><i class="fa fa-eye"></i></a>
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
    </div>      
</div>
<?php
$this->load->view('structure/footer');
?>
