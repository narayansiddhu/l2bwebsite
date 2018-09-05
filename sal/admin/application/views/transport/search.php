<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$route = $this->input->get('route');
$route_err=0;
if(strlen($route)==0){
    $route_err="** Please enter Route Name To Search..";
}
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
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Search</a>
                        </li>
                    </ul>
            </div>
                <div class="box box-bordered box-color">
                        <div class="box-title">
                                <h3><i class="fa fa-search"></i>Search</h3>

                        </div>
                        <div class="box-content nopadding">
                                <div class="tab-content nopadding">
                                    <div class="form-actions col-sm-offset-2 col-sm-8">
                                         <input type="text" style=" float: left; width: 90%;" name="srch_txt"  id='srch_txt'  class="form-control" placeholder="Enter Pick -up Point To Search" />
                                         <button onclick="search_fun();" style=" float: left; width: 10%; height: 34px " class="btn btn-primary"><i class="fa fa-search"></i></button>  
                                         <span id='srch_error' style=" color: red">
                                             <?php
                                              if($route_err!=0){
                                                  echo $route_err;
                                              }
                                             ?>
                                         </span>
                                    </div>

                                </div>
                        </div>
                    <script>
                      function search_fun(){
                          txt = $('#srch_txt').val();
                          txt=$.trim(txt)
                          $('#srch_error').html("");
                          if(txt.length==0){
                           $('#srch_error').html("** Please Enter Text To Search.. ");
                          }else{
                            window.location.href = '<?php echo base_url() ?>index.php/transport/search?route='+txt;
                          }
                      }    
                    </script>
                </div>

                    <div class="box">
                        <?php
                            if( ($route_err==0)&&(strlen($route)!=0)){
                                 $query ="SELECT tr.trid,tr.pickup_point,tr.pick_up,tr.drop,t.val,r.rname  FROM trip_route tr JOIN trips t ON tr.trip=t.trip_id JOIN routes r On t.route_id = r.route_id  where tr.pickup_point LIKE '%".$route."%' AND  tr.iid='".$this->session->userdata('staff_Org_id')."' ";                   
                                 $query =$this->db->query($query);
                                 if($query->num_rows()>0){
                                     $query = $query->result();
                                     ?>
                        
                                    <div class="col-sm-6 nopadding">
                                      <div class="box box-bordered box-color" style=" padding-right:  5px;">
                                        <div class="box-title">
                                                <h3><i class="fa fa-road"></i>Route Details</h3> 
                                        </div>
                                        <div class="box-content nopadding"> 
                                            <table class="table table-bordered Datatable table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Route</th>
                                                        <th>Trip</th>
                                                        <th>Pick Up Point</th>
                                                        <th>pick-up </th>
                                                        <th>Drop</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <?php
                                                 $trips="";
                                                     foreach ($query as $value) {
                                                         $trips.=$value->trid.",";
                                                        ?>
                                                    <tr>
                                                       <td><?php echo $value->rname ?></td>
                                                       <td>Trip -<?php echo $value->val ?></td>
                                                       <td><?php echo $value->pickup_point ?></td>
                                                       <td><?php echo $value->pick_up ?></td>
                                                        <td><?php echo $value->drop ?></td>
                                                    </tr>
                                                        <?php 
                                                     }
                                                     $trips = substr($trips, 0,strlen($trips)-1);
                                                 ?>
                                                </tbody>
                                            </table>
                                        </div>
                                      </div>
                                
                                    </div>
                        <div class="col-sm-6 nopadding">
                            
                            <div class="box box-bordered box-color" style=" padding-left: 5px;">
                        <div class="box-title">
                                <h3><i class="fa fa-th-list"></i>Student Details</h3> 
                        </div>
                        <div class="box-content nopadding"> 
                            <table class="table table-bordered Datatable table-striped">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Route</th>
                                        <th>Trip</th>
                                        <th>Pick Up Point</th>
                                        <th>pick-up </th>
                                        <th>Drop</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stud="SELECT st.stid,st.fee_amount ,t.val, s.student_id ,s.name ,s.phone,s.userid,tr.pickup_point , r.rname , tr.pick_up,tr.drop FROM stud_transport st JOin student s On st.stud_id= s.student_id JOIN trip_route tr ON tr.trid=st.trip_route_id JOIN trips t ON tr.trip=t.trip_id JOIN routes r On t.route_id = r.route_id where st.iid='".$this->session->userdata('staff_Org_id')."' AND tr.trid IN (".$trips.") ";
                                    $stud = $this->db->query($stud)->result();
                                    foreach ($stud as $value) {
                                       ?>
                                    <tr>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->rname ?></td>
                                        <td>Trip-<?php echo $value->val ?></td>
                                        <td><?php echo $value->pickup_point ?></td>
                                        <td><?php echo $value->pick_up ?></td>
                                        <td><?php echo $value->drop ?></td>
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
                                 }else{
                                     ?> <br/>
                        <h4 style=" text-align: center; color: red">** No Records Found..</h4>;
                                     <?php
                                 }
                            
                            }
                         ?>
                         
                        
                    </div>                    
                    
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>