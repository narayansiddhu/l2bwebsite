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
                        <a href="<?php echo base_url(); ?>index.php/students/">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Support</a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li >
                        <a href="<?php echo base_url(); ?>index.php/students/add">Add Query</a>
                        
                    </li>
                </ul>
            </div>
            <div class="box box-bordered box-color ">
                <div class="box-title">
                        <h3><?php echo $query->title ?></h3>
                </div>
                <div class="box-content nopadding">
                    <?php
                     $q="select * from support_reply where qid='".$query->qid."'";
                     $q=$this->db->query($q);
                     $q=$q->result();
                    ?>
                    <ul class="messages nopadding">
                        
                            
                        
                           <li class="left">
                                    <div class="message">
                                            <span class="caret"></span>
                                            <span class="name">You</span>
                                            <?php
                                              if(strlen($query->file)!=0){
                                                  ?><br/> 
<!--                                            <image src="<?php echo assets_path.'uploads/'.$query->file ?>" style=" width: 100px; height: 100px" />                                          
                                              <br/> -->
                                               <?php
                                              }
                                            ?>
                                            <p><?php echo $query->query ?></p>
                                            <span class="time">
                                                   <?php echo date("d-m-Y H:i",$query->time) ?>
                                            </span>
                                    </div>
                            </li> 
                           <?php
                                foreach ($q as $value) {
                                  ?>
                                        <li class="<?php
                                                   if($value->post_by=='support'){
                                                        echo "right";
                                                    } else{
                                                        echo "left";
                                                    }
                                              ?>">
                                           <div class="message">
                                                   <span class="caret"></span>
                                                   <span class="name">
                                                       <?php
                                                       if($value->post_by=='support'){
                                                            echo "Support";
                                                        } else{
                                                            echo "you";
                                                        }
                                                       ?></span>
                                                   <p><?php echo $value->message ?></p>
                                                   <span class="time">
                                                          <?php echo date("d-m-Y H:i",$value->timestamp); ?>
                                                   </span>
                                           </div>
                                        </li>
                                   <?php
                                                      
                                 }
                            ?>
                       
                            <li class="insert">
                                    <div id="message-form" method="POST" action="#">
                                            <div class="text">
                                                    <input type="text" id="new_msg" name="new_msg" placeholder="Write here..." class="form-control">
                                            </div>
                                            <div class="submit">
                                                <button type="button" onclick="add_new_mesage();">
                                                            <i class="fa fa-share"></i>
                                                    </button>
                                            </div>
                                    </div>
                            </li>
                    </ul>
            </div>
            </div>
            <script>
                function add_new_mesage(){
                    
                }
                </script>
            
        </div>
    </div>
</div>
<?php
$this->load->view('structure/footer');
?>