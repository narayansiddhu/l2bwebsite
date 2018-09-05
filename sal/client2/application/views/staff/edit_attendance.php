<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$day_details = getdate($day->day);
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
                            <a href="<?php echo base_url(); ?>index.php/staff/Monthly_attendance?month=<?php echo $day_details['mon'] ?>&year=<?php echo $day_details['year'] ?>">Staff Monthly Attendance</a>
                        </li>
                         <li>
                            <a href="">Attendance Of <?php echo date("d-m-Y",$day->day); ?></a>
                        </li>
                    </ul>
            </div> 
        <div class="box box-color box-bordered">
                <div class="box-title">
                        <h3>
                            <i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;Staff Attendance List On <?php echo date("d-m-Y",$day->day); ?>
                        </h3>
                    <div class="actions">
                        <a onclick="select_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as present"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>
                        <a onclick="unselect_all();" class="btn" rel="tooltip" title="" data-original-title="Set all as absent"><i class="fa fa-minus-square-o" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="box-content nopadding">
                    <form method="post" action="<?php echo base_url(); ?>index.php/staff/save_attendance">
                     
                      <input type="hidden" name="att_date" value="<?php echo $day->id ?>" />
                      <table class="table table-hover table-nomargin  table-bordered"   style="width: 100%;">
                       <thead>
                           <tr>
                               <th>S.No</th>
                               <th>Name</th>
                               <th>E-mail</th>
                               <th>Status</th>
                           </tr>
                       </thead>      
                       <tbody>
                           <?php
                             $i=1;$ids="";
                         //    echo "SELECT sa.id,sa.status,s.name,s.email FROM `staff_attendance` sa JOIN staff s ON sa.staff=s.id  WHERE sa.date_id='".$day->id."' and s.status!=0";
                             $query=$this->db->query("SELECT sa.id,sa.status,s.name,s.email FROM `staff_attendance` sa JOIN staff s ON sa.staff=s.id  WHERE sa.date_id='".$day->id."' and s.status!=0 ");
                             $query=$query->result();
                             foreach ($query as $value) {
                                  $ids.=$value->id.",";
                               ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $value->name ?></td>
                                    <td><?php echo $value->email ?></td>
                                    <td>
                                        <select <?php  
                                              if(strlen($this->form->error('status_'.$value->id)) >0){
                                                ?>
                                                    style=" border-color: red;"
                                                   <?php
                                              }
                                           ?> id="status_<?php echo $value->id ?>" name="status_<?php echo $value->id ?>" >
                                                   <option value="">select</option>
                                                   <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==1) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==1){
                                                        echo "selected";
                                                        }
                                                   ?> value="1">Present</option>
                                                  <option
                                                        <?php 
                                                         if(strlen($this->form->value('status_'.$value->id))>0){
                                                            if($this->form->value('status_'.$value->id)==2) {
                                                                 echo "selected";
                                                            }   
                                                         }elseif($value->status==2){
                                                        echo "selected";
                                                        }
                                                   ?> value="2">Absent</option>
                                               </select>
                                    </td>
                                    
                                </tr>
                               <?php
                             }
                             $ids=  substr($ids, 0, strlen($ids)-1);
                          ?>
                        </tbody>
                   </table>
                      <input type="hidden" id="id_values" name="ids" value="<?php echo $ids ?>" />
                    <br/><br/>  
                        <div class="col-sm-12"> 
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                            <div class="col-sm-4">
                                <input type="submit"  name="submit" value="Save Attendance" class="btn btn-primary btn-block  " />  
                            </div>
                            <div class="col-sm-4">
                                &nbsp;
                            </div>
                           
                        </div>
                           <br/><br/>  
                    </form>
                </div>
        </div>
        
      </div>
    </div>
</div>
<script>
  function select_all(){
      var ids=$('#id_values').val();
     
     arr=ids.split(",");
      for(i in arr){
          
        $("#status_"+arr[i]).val("1");
     }
  }
  function unselect_all(){
      var ids=$('#id_values').val();
      arr=ids.split(",");
      for(i in arr){
         $("#status_"+arr[i]).val("2");
        }
  } 
</script>
<?php
$this->load->view('structure/footer');
?>