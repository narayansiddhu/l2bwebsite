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
                            <a href="<?php echo base_url(); ?>index.php/">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/transport/">Transportation</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/transport/view/">Manage Routes</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Send SMS Alerts</a>
                        </li>
                    </ul>
            </div>
            <?php
              $message_array =$_SESSION['trans_msg_prev'];
              $post_data =$_SESSION['trans_msg_post'];
            ?>
            <br/>
            <div class="box">
                <table class="table table-bordered " style=" text-align: center">
                    <tr>
                        <td>
                             Mobile No <hr/>
                             <?php echo sizeof($message_array) ?>
                        </td>
                        <td>
                             Message Length  <hr/>
                             <?php echo strlen($post_data['message']) ?>
                        </td>
                        <td>
                             Invalid No   <hr/>
                             <?php echo strlen($post_data['invalid_no']) ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="box box-bordered box-color">
                <div class="box box-bordered box-color ">
                                <div class="box-title">
                                    <h3><i class="fa fa-comments-o "></i>&nbsp;SMS Preview</h3>
                                </div>
                    <div class="box-content nopadding">
                        <table class="table table-bordered " style=" max-height: 450px; overflow: scroll">
                            <tr>
                                <th>Mobile No</th>
                                <th>Message</th>
                            </tr>
                            <?php 
                                foreach ($message_array as $value) {
                                 ?>
                            <tr>
                                <td><?php echo $value['mobile'] ?></td>
                                <td><?php echo $value['message'] ?></td>
                            </tr>
                                  <?php            
                                }
                            ?>
                        </table>
                        <div class="box" style=" margin-top: 10px; margin-bottom: 10px;">
                            <div class="col-sm-6">
                                <a  href="<?php echo base_url() ?>index.php/transport/discard_sms" class="btn btn-primary" style=" float: right" ><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;discard</a>
                            </div>
                            <div class="col-sm-6">
                                <a href="<?php echo base_url() ?>index.php/transport/send" class="btn btn-primary" style=" float: left">Send SMS&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                            </div>
                            <br/><br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>