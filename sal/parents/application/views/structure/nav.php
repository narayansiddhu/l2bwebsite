 </head>
            <body>
        <div id="navigation">
		<div class="container-fluid">
                    
                    <a href="<?php echo base_url() ?>index.php/Login/dashboard" class="brand" style=" text-decoration: none" >
                        &nbsp;&nbsp;&nbsp;&nbsp;
                       <i style=" color: white" class="fa fa-home fa-2x "></i> &nbsp;&nbsp;&nbsp;&nbsp;
                    </a>
                        <span style="  font-weight:  bold; color: whitesmoke ; font-family: sans-serif; font-size: 20px; ">
                        <?php
                         echo strtoupper($this->session->userdata("institute_name"));
                        ?>
                        </span>
                    

			
			<div class="user">
                            <ul class="icon-nav">
                                <li class="dropdown">
                                    <a href="<?php echo base_url() ?>index.php/Notices/staff_notices">
                                        <i class="fa fa-globe"></i>
                                    <span class="label label-lightred"><?php
                                    $date= getdate();
                                    $q="SELECT * FROM `notice_board` where expiry >= '".  mktime(0,0, 0, $date['mon'],$date['mday'],$date['year'])."' AND `for`=2 ";
                                   
                                    $q= $this->db->query($q);
                                    echo$q->num_rows();
                                    ?>
                                    </span>
                                    </a>
                                </li>
                            <li class="dropdown sett">
                                    <a href="<?php echo base_url() ?>index.php/settings/"><i class="fa fa-cogs"></i></a>
			        </li>
                            </ul><div style=" margin-top: 8px;" class="dropdown">
                                <span style=" color: white; " class='dropdown-toggle' data-toggle="dropdown">
                                        <?php echo $this->session->userdata('parent_email') ?>
					</span>	
				</div>
                            <ul class="icon-nav">
                                <li class="dropdown sett">
                                    <a href="<?php echo base_url() ?>index.php/login/logout/"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
			        </li>
                            </ul>
			</div>
		</div>
	</div>