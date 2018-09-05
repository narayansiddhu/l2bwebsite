<?php $nav_active = $this->uri->segment(1); ?>

<nav class="navbar navbar-static-top <?php if($nav_active == 'Dashboard') echo 'white-bg'?>" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        <form role="search" class="navbar-form-custom" action="#">
            <div class="form-group">
                <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
            </div>
        </form>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <span class="m-r-sm text-muted welcome-message font-bold site__title" id="animationSandbox">Welcome to <?php
                if(strlen($this->session->userdata("inst_name")) == 0){
                    //staff_Org_id
                    $query=$this->db->query("SELECT * FROM `institutes` WHERE id='".$this->session->userdata("staff_Org_id")."' ");
                    $query=$query->row();
                    echo strtoupper($query->name);
                    $this->session->set_userdata("inst_name",$query->name);
                } else {
                    echo strtoupper($this->session->userdata("inst_name"));
                }

                ?>
            </span>
        </li>
        <li>
            <a href="<?php echo base_url() ?>index.php/Dashboard">
                <i class="fa fa-home fa-2x"></i>
            </a>
        </li>
        <li class="dropdown" >
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
            </a>
            <ul class="dropdown-menu dropdown-messages" style="">
                <li>
                    <div class="dropdown-messages-box">
                        <a href="profile.html" class="pull-left">
                            <img alt="image" class="img-circle" src="<?= assets_path_admin ?>img/profile_small.jpg">
                        </a>
                        <div>
                            <small class="pull-right">46h ago</small>
                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                        </div>
                    </div>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="dropdown-messages-box">
                        <a href="profile.html" class="pull-left">
                            <img alt="image" class="img-circle" src="<?= assets_path_admin ?>img/profile_small.jpg">
                        </a>
                        <div>
                            <small class="pull-right text-navy">5h ago</small>
                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                        </div>
                    </div>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="dropdown-messages-box">
                        <a href="profile.html" class="pull-left">
                            <img alt="image" class="img-circle" src="<?= assets_path_admin ?>img/profile_small.jpg">
                        </a>
                        <div>
                            <small class="pull-right">23h ago</small>
                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                        </div>
                    </div>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="text-center link-block">
                        <a href="mailbox.html">
                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                        </a>
                    </div>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="<?php echo base_url() ?>index.php/Notices/staff_notices">
                <i class="fa fa-bell"></i>
                <span class="label label-primary">
                    <?php
                    $date= getdate();
                    $q="SELECT * FROM `notice_board` where expiry >= '". mktime(0,0, 0, $date['mon'],$date['mday'],$date['year'])."' AND `for`=1";

                    $q= $this->db->query($q);
                    echo$q->num_rows();
                    ?>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
                <li>
                    <a href="mailbox.html">
                        <div>
                            <?php echo 'sample<br/>'; ?>
                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="profile.html">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="grid_options.html">
                        <div>
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <div class="text-center link-block">
                        <a href="<?php echo base_url() ?>index.php/Notices/staff_notices">
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </li>
            </ul>
        </li>

        <li>
            <a href="<?php echo base_url() ?>index.php/settings/">
                <i class="fa fa-gears"></i>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url() ?>index.php/logs/view">
                <i class="fa fa-tasks"></i>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url() ?>index.php/login/logout/">
                <i class="fa fa-sign-out-alt"></i> <span class="logout">Log out</span>
            </a>
        </li>


    </ul>

</nav>