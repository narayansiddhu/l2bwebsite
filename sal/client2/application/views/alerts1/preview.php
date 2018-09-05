<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script src="<?php echo assets_path ?>js/selector.js"></script>
<script src="<?php echo assets_path ?>js/unicode.js"></script>
<script src="<?php echo assets_path ?>js/parser.js"></script>
<?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
$pane=$this->form->value('pane');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <br/>
            <div class="breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>">Home</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>/index.php/alerts">Send Alerts</a>
                             <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </li>
                        <li>
                            <a href="">Preview</a>
                        </li>
                    </ul>

            </div>
             <div class="box box-bordered box-color ">
                <div class="box-title">
                        <h3><i class="fa fa-bars"></i>SMS Details</h3>
                </div>
                 <div class="box-content nopadding">
                     <div class="row nopadding">
                           <?php
                               if($schedule==0){
                                 ?>
                                    <div class="col-sm-12" style=" text-align: center">
                                        <h3>Total Messages :<?php echo sizeof($message) ?></h3>
                                    </div>
                                 <?php
                               }else{
                                   ?>
                                    <div class="col-sm-6" style=" text-align: center ;">
                                        <h3>Total Messages :<?php echo sizeof($message) ?></h3>
                                    </div>
                                    <div class="col-sm-6" style=" text-align: center">
                                        <h3>Scheduled Time:<?php echo date("d-m-y h:i",$schedule_time); ?></h3>
                                    </div>
                                   <?php
                               }
                           ?>
                     </div>
                     <hr/>
                     <div class="row nopadding">
                         <div class="col-sm-6 ">
                             <button class="btn btn-default" onclick="discard_alerts();" style=" float: right;"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;&nbsp; Back</button>
                         </div>
                         <div class="col-sm-6 ">
                             <button onclick="send_alerts();" style=" float: left" class="btn btn-primary">Send SMS&nbsp;&nbsp;<i class="fa fa-share"></i></button>
                         </div>
                     </div><br/>
                 </div>
             </div>
            <div id="action_name"></div>
             <script> 
                 function discard_alerts(){
                    setState('action_name','<?php echo base_url() ?>index.php/alerts/back',"discard=discard");
                 }
                 function send_alerts(){
                    setState('action_name','<?php echo base_url() ?>index.php/alerts/send_sms',"sendsms=yes");
                 }
             </script>            
             <div class="box box-bordered box-color ">
                <div class="box-title">
                        <h3><i class="fa fa-bars"></i>SMS Preview</h3>
                </div>
                <div class="box-content nopadding">
                    <table class="table datatable table-hover table-nomargin table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>mobile</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                                foreach($message as $value){
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $value['mobile']; ?></td>
                                        <td><?php echo str_replace("\n","<br/>", $value['msg_content']); ?></td>
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