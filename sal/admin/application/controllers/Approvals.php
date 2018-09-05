<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvals extends CI_Controller {

	function __construct() {
            parent::__construct();
            $this->load->library('session');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('operations');
            $this->load->model('form');
            $this->load->model('logs');
            $this->load->model('barcode');
            $this->load->library("pagination");
            /* cache control */
            $this->operations->is_login();
        }
        
	public function library(){
            $this->load->view("approvals/library");
        }
        
        public function change_status(){
           
            $new_val = $this->input->post("new_val");
            $id = $this->input->post("id");
            $data=array(
                    'status'=>$new_val,
                );
               $this->db->where('req_id', $id);
               $this->db->update('lib_request', $data);
               $this->load_tr($id);
               ?>
                <script>
                    $("#successMsg").show();
                    $("#successMsg").fadeIn();
                    $("#successMsg").delay(2000).fadeOut();
                </script>
               <?php
               
        }
        public function load_tr($id){
            $query="SELECT b.req_id,b.name,c.category,b.price,b.author,b.status FROM lib_request b JOIN book_category c on b.category=c.catid WHERE b.iid='".$this->session->userdata('staff_Org_id')."' AND b.req_id ='".$id."' ";  
            $query=$this->db->query($query);
            $query = $query->row();
            ?>
             
                                        <td><?php echo $query->name ?></td>
                                        <td><?php echo $query->category ?></td>
                                        <td><?php echo $query->price ?></td>
                                        <td><?php echo $query->author ?></td>
                                        <td><?php 
                                        switch($query->status){
                                            case 0: echo "Rejected";break;
                                            case 1: echo "Requested";break;
                                            case 2: echo "Processing";break;
                                            case 3: echo "Issued";break;
                                        }
                                        ?></td>
                                        <td>
                                            <button class="btn btn-primary" id='mod_action_<?php echo $query->req_id ?>' onclick="modify('<?php echo $query->req_id ?>');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            <div id='action_<?php echo $query->req_id ?>' style="display: none;">
                                                <select style=" float: left; width: 70%;" class="select2-me" id="modify_<?php echo $query->req_id ?>" >
                                                    <option value="">Select Status</option>
                                                    <option value="0"
                                                            <?php
                                            if($query->status==0){
                                                echo "selected";
                                            }
                                                            ?>>Rejected</option>
                                                    <option value="1"
                                                            <?php
                                            if($query->status==1){
                                                echo "selected";
                                            }
                                                            ?>>Requested</option>
                                                    <option value="2"
                                                            <?php
                                            if($query->status==2){
                                                echo "selected";
                                            }
                                                            ?>>Processing</option>
                                                    <option value="3"
                                                            <?php
                                            if($query->status==3){
                                                echo "selected";
                                            }
                                                            ?>>Issued</option>
                                                </select>
                                                <button onclick="save_new_action('<?php echo $query->req_id ?>');" class="btn btn-primary" style=" float: left; width: 25%;" id='save_action_<?php echo $query->req_id ?>' onclick="modify('<?php echo $query->req_id ?>');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                <span id='error_msg_<?php echo $query->req_id ?>'></span>
                                            </div>
                                            
                                        </td>
            <?php
        }
        
        
}


?>
