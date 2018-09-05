<?php
// New Theme implementation headers->css and js plugines
$this->load->view('structure/header');
$this->load->view('structure/js');
//--> New Theme implementation headers->css and js plugines
?>

<body>
<div id="wrapper">

    <?php
    // Navigation Links in right side panel
    $this->load->view('structure/body');
    //--> Navigation Links in right side panel

    ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <?php $this->load->view('structure/nav'); ?>
        </div>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-8">
                <h2>Staff Users</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url() ?>index.php/Dashboard"> Home </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/staff/View_staff">Manage Staff</a>
                    </li>
                    <li class="active">
                        <strong>List Users</strong>
                    </li>
                </ol>
            </div>
            <div class="col-sm-4">
                <div class="title-action">
                    <a href="<?php echo base_url() ?>index.php/staff/Add_staff" class="btn btn-primary "><i class="fa fa-user-plus"></i> Create Staff</a>
                   <a href="<?php echo base_url() ?>index.php/staff/print_all" class="btn btn-default font-bold"><i class="fa fa-print"></i> Print All</a>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5> All the Users</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
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
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>E-mail</th>
                                        <th>Gender</th>
                                        <th>Salary</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    function check_avalible($data){
                                        if($data ){
                                            return $data;
                                        }else{
                                            return '0';
                                        }
                                    }

                                    ?>
                                    <?php

                                    // var_dump($results);

                                    $blood_group = unserialize (blood_groups);
                                    $staff_level = unserialize (staff_level);
                                    $i=0;
                                    $counter_arr=array("Management"=>0,"Faculty"=>0,"Librarian"=>0,"OfficeStaff"=>0);

                                     // print_r($results);

                                    if(sizeof($results)  > 0){
                                        foreach($results as $row) {
                                            switch($row->level){
                                                case   $row->level >= 6: $counter_arr['Management']++;break;
                                                case   $row->level == 5: $counter_arr['Librarian']++;break;
                                                case   $row->level == 3: $counter_arr['OfficeStaff']++;break;
                                                case   $row->level == 1: $counter_arr['Faculty']++;break;
                                            }
                                            ?>

                                            <tr  <?php
                                            if($row->status==0){
                                                ?>
                                                style=" background-color : #eee"
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
                                                        echo "Female";
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $row->amount;?>   </td>
                                                <td><?php
                                                    if(isset($staff_level[$row->level])){
                                                        echo $staff_level[$row->level];
                                                    }else{
                                                        echo "Driver";
                                                    }?>
                                                </td>


<!--                                                <td>--><?php //echo date("M jS, Y", check_avalible($row->doj) ) ;?><!--   </td>-->
                                                <td class="text-center">
                                                    <?php
                                                    if($row->status==0){
                                                        ?>
                                                        <span class="badge badge-warning">Inactive</span>
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <span class="badge badge-primary">Active</span>
                                                        <?php
                                                    }
                                                    ?>

                                                </td>
                                                <td class="text-center" style="font-size: 15px;" >
                                                    <a href="<?php echo base_url() ?>index.php/staff/view_staff_details/<?php echo  $row->id  ?>" class="text-primary" rel="tooltip" title="" data-original-title="View Staff Details"><i class="fa fa-eye"></i></a>
                                                    <?php if($row->status==0){  ?>
                                                        <a href="<?php echo base_url(); ?>index.php/staff/change_status/<?php echo  $row->id  ?>/activate" class="text-info" rel="tooltip" title="" data-original-title="Activate Staff"> <i class="fa fa-toggle-on" aria-hidden="true"></i></a>
                                                    <?php } else { ?>
                                                        <a  href="<?php echo base_url(); ?>index.php/staff/change_status/<?php echo  $row->id  ?>/deactivate" class="text-danger" rel="tooltip" title="" data-original-title="De-Activate Staff"> <i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                                    <?php } ?>
                                                    <?php if($row->status !=0 ){ ?>
                                                        <a href="<?php echo base_url() ?>index.php/staff/edit/<?php echo  $row->id  ?>" class="text-info" rel="tooltip" title="" data-original-title="Edit Staff Details" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                    <?php } ?>
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
                </div>
            </div>
        </div>



        <?php   $this->load->view('structure/footer'); ?>
    </div>
</div>

</body>

</html>
