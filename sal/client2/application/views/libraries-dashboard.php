<div class="row">
    <div class="col-sm-9">
    <div class="col-sm-12">
            <ul class="tiles">
                    <li class="orange high long">
                        <a href="<?php echo base_url(); ?>/index.php/library/books_new">
							<span class='count'>
                           <i class="fa fa-book" aria-hidden="true"></i>
</span>
						<span class='name'>Add New Books</span>
                        </a>
                    </li>
                   <li class="blue long">
                            <a href="<?php echo base_url(); ?>/index.php/library/request">
                                    <span>
                                      <img src="<?php echo assets_path ?>img/request.png"  />
                                    </span>
                                    <span class='name'>Request Book</span>
                            </a>
                    </li>
                    <li class="green">
                            <a href="<?php echo base_url(); ?>/index.php/library/search">
                                    <span class='count'>
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                    <span class='name'>Search Transaction</span>
                            </span></a>
                    </li>
                    <li class="lime">
                            <a href="<?php echo base_url(); ?>/index.php/library/issue">
                                    <span class='count'>
                                      <img src="<?php echo assets_path ?>img/issue.png"  />
                                      <span class='name'>Issue Book</span>
                           </span> </a>
                    </li>
                    <li class="red">
                            <a href="<?php echo base_url(); ?>/index.php/library/submit">
                                    <span class='count'>
                                      <i class="fa fa-retweet" aria-hidden="true"></i>
                                      <span class='name'>Return Book</span>
                           </span> </a>
                    </li>
                    <li class="darkblue">
                            <a href="<?php echo base_url(); ?>/index.php/library/payments">
                                    <span class='count'>
                               		<i class="fa fa-money" aria-hidden="true"></i>
                                    <span class='name'>Payments</span>
                            </span></a>
                    </li>
                    <li class="lightgrey long">
                            <a href="<?php echo base_url() ?>index.php/logs/view">
                                    <span class='nopadding'>
                                            <h5>User info</h5>
                                            <p>Name :<?php echo $this->session->userdata('staff_name'); ?> <br /> 
                                               email :<?php echo $this->session->userdata('staff_user'); ?> <br />
                                               
                                            </p>
                                    </span>
                                    <span class='name'>
                                            <i class="fa fa-sign-in" aria-hidden="true"></i>
                                            <span class="right">Activity Logs</span>
                                    </span>
                            </a>
                    </li>
                    <li class="pink">
                            <a href="#">
                                    <span class='count'>
                                           <i class="fa fa-cogs" aria-hidden="true"></i>
                                    <span class='name'>Profile & Settings</span>
                                    </span>
                            </a>
                    </li>
                    
                    <li class="lightgrey">
                            <a href="<?php echo base_url(); ?>index.php/teachers/salary">
                                    <span class='count'>
                                          <i class="fa fa-usd" aria-hidden="true"></i>
                                    <span class='name'>View Salary</span>
                           </span> </a>
                    </li>
                    
                    
                    <li class="teal ">
                            <a href="<?php echo base_url(); ?>index.php/library/issue_list">
                                    <span class='count'>
                                           <i class="fa fa-book" aria-hidden="true"></i>
                                    <span class='name'>Issue List</span>
                                    </span>
                            </a>
                    </li>
                    <li class="blue long">
                            <a href="<?php echo base_url(); ?>/index.php/library/view_all_payments">
                                    <span class='count'>
                                           <i class="fa fa-header" aria-hidden="true"></i>
                                    <span class='name'>Payment History</span>
                                    </span>
                            </a>
                    </li>
                    <li class="brown">
                            <a href="<?php echo base_url(); ?>index.php/login/logout">
                              
                                    <span class='count'>
                                          <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    <span class='name'>Log Out</span>
                           </span> </a>
                    </li>
                    
                    
                  
            </ul>

    </div>
    </div>
    <div class="col-sm-3">
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
            ?>
        
	<div class="row">
            <div class="col-md-12">
                <ul class="tiles">
                    <li class="orange long">
                        <a href="#">
                            <span class='count' >
                                <i style=" float: left; width: 15%" class="fa fa-book"></i>&nbsp;<?php echo $lib_data['total'] ?>
                                    </span>
                            <span class='name'>
                                Total Books &nbsp;   
                            </span>         
                        </a>
                    </li>
                    
                    
                    
                    <li class="pink long">
                        <a href="#">
                            <span class='count' >
                                <i style=" float: left; width: 15%" class="fa fa-book"></i>&nbsp;<?php echo $lib_data['available'] ?>
                                    </span>
                            <span class='name'>
                                Total Books Available   
                            </span>         
                        </a>
                    </li>
                    
                    <li class="blue long">
                        <a href="#">
                            <span class='count' >
                                <i style=" float: left; width: 15%" class="fa fa-book"></i>&nbsp;<?php echo $lib_data['issued'] ?>
                                    </span>
                            <span class='name'>
                                 Total Books Issued   
                            </span>         
                        </a>
                    </li>
                </ul>
                
            </div>
            
            
    	</div>
        
  
        
        
    </div>
</div>
<script>
    function request_credits(){
        alert("Request Credits");
    }
</script>
