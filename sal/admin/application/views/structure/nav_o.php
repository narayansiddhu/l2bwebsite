</head>
 
 <body>
     <div id="navigation" style=" color: #012B72; background-color:  #99ccff; "  >
		<div class="container-fluid">
                    
                    <a href="<?php echo base_url() ?>index.php/Dashboard" class="brand" style=" text-decoration: none" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                       <i style=" color: #012B72;"  class="fa fa-home fa-2x "></i> &nbsp;&nbsp;&nbsp;&nbsp;
                    </a>
                    <span style="  font-weight:  bold; font-family: serif; font-size: 20px; ">
                        <?php
                            if(strlen($this->session->userdata("inst_name"))==0){
                              //staff_Org_id
                             $query=$this->db->query("SELECT * FROM `institutes` WHERE id='".$this->session->userdata("staff_Org_id")."' "); 
                             $query=$query->row();
                             echo strtoupper($query->name);
                             $this->session->set_userdata("inst_name",$query->name);  
                          }else{
                            echo strtoupper($this->session->userdata("inst_name"));  
                          }

                           ?>
                    </span>
			
			<div class="user">
                            <ul class="icon-nav">
                                <li class="dropdown">
                                    <a style=" color: #012B72; " href="<?php echo base_url() ?>index.php/Notices/staff_notices">
                                        <i style=" color: #012B72; " class="fa fa-globe"></i>
                                    <span class="label label-lightred">
                                        <?php
                                    $date= getdate();
                                    $q="SELECT * FROM `notice_board` where expiry >= '".  mktime(0,0, 0, $date['mon'],$date['mday'],$date['year'])."' AND `for`=1";
                                   
                                    $q= $this->db->query($q);
                                    echo$q->num_rows();
                                    ?>
                                    </span>
                                    </a>
                                </li>
                                <li class="dropdown sett">
                                    <a style=" color: #012B72;" href="<?php echo base_url() ?>index.php/settings/">
                                        <i style=" color: #012B72; " class="fa fa-cogs"></i>
                                    </a>
                                </li>
                                <li class="dropdown sett">
                                    <a href="<?php echo base_url() ?>index.php/logs/view">
                                        <i  style=" color: #012B72; " class="fa fa-list-ol"></i>
                                    </a>
                                </li>
                            </ul>
                            			
                                <div style=" margin-top: 8px;" class="dropdown">
                                    <span style=" color: #012B72; " class='dropdown-toggle' data-toggle="dropdown"><?php echo $this->session->userdata('staff_user') ?></span>
                                </div>
                            &nbsp;&nbsp;
                            <ul class="icon-nav">
                                <li class="dropdown sett">
                                    <a style=" color: #012B72; " href="<?php echo base_url() ?>index.php/login/logout/"><i style=" color: #012B72; " class="fa fa-sign-out" aria-hidden="true"></i></a>
			        </li>
                            </ul>
			</div>
		</div>
	</div>