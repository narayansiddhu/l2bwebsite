<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
   
<div class="col-sm-12">
        <div class="box">
             <br/>
            <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php/Alerts">Alerts</a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">View SMS History</a>
                    </li>
                    </ul>
            </div>
            <div class="box box-bordered box-color">
                <div class="box-title">
                        <h3><i class="fa fa-th-list"></i>View Alert History </h3> 
                </div>
                <div class="box-content nopadding"> 
                   <table class="table table-hover table-nomargin table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th  >S.no</th>
                                <th >user details</th>
                                <th >Senderid </th>
                                <th >To</th>
                                <th >Message</th>
                                <th style=" width: 15%">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach ($results as $value) {
                                ?>
                                    <tr>
                                        <td><?php  echo $i++; ?></td>
                                        <td><?php  echo $value->username ."<br/>". $value->password  ?></td>
                                        <td><?php  echo $value->senderid  ?></td>
                                        <td><?php  echo $value->mobile ?></td>
                                        <td><?php  echo  str_replace("\n", "<br/>", $value->message ) ?></td>
                                        <td><?php  echo date("d-m-Y H:i",$value->time) ?></td>
                                    </tr>
                                <?php
                            }
                            
                            
                            ?>
                        </tbody>
                    </table>  
                    <div class="pagination">
                    <?php
                       echo $links;       
                       ?>  </div>
                </div>
            </div>
            
        </div>
    </div>   
</div>
<?php
$this->load->view('structure/footer');
?>


