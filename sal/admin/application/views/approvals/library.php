<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
$status="";
if(strlen($this->input->get("status"))!=0){
    $status=$this->input->get("status");
}

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
                            <a href="">Library Requests</a>
                        </li>
                    </ul>

            </div>
            
            <br/>
            <div id="successMsg" style="display:none;"  class="alert alert-success alert-dismissable">
                       <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         Status Updated Sucessfully
            </div>
           
            <div class="box box-bordered box-color">
        
                <div class="box-title">
                    <h3>Library Books Request List</h3>
                    <div class="actions">
                        <form>
                            <select name="status" class="select2-me" >
                                                    <option value="">Select Status</option>
                                                    <option value="0"
                                                            <?php
                                            if($status==0){
                                                echo "selected";
                                            }
                                                            ?>>Rejected</option>
                                                    <option value="1"
                                                            <?php
                                            if($status==1){
                                                echo "selected";
                                            }
                                                            ?>>Requested</option>
                                                    <option value="2"
                                                            <?php
                                            if($status==2){
                                                echo "selected";
                                            }
                                                            ?>>Processing</option>
                                                    <option value="3"
                                                            <?php
                                            if($status==3){
                                                echo "selected";
                                            }
                                                            ?>>Issued</option>
                                                </select>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                        
                    </div>
                </div>

                <div  class="box-content nopadding">
                    <?php
                     $query="SELECT b.req_id,b.name,c.category,b.price,b.author,b.status FROM lib_request b JOIN book_category c on b.category=c.catid WHERE b.iid='".$this->session->userdata('staff_Org_id')."' ";  
                        if(strlen($status)!=0){
                                            $query.=" AND b.status ='".$status."'";
                        }
                        
                     $query=$this->db->query($query);
                    ?>
                            <table  class="table table-hover table-nomargin datatable table-bordered ">
                                <thead>
                                    <tr>
                                        
                                        <th>Title</th>
                                        <th>category</th>
                                        <th>price</th>
                                        <th>author</th>
                                        <th>status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    $id=1;
                                    if($query->num_rows()>0){
                                       $query = $query->result();
                                    foreach( $query as $value){
                                         ?>
                                    <tr id='tr_row_<?php echo $value->req_id ?>'>
                                        <td><?php echo $value->name ?></td>
                                        <td><?php echo $value->category ?></td>
                                        <td><?php echo $value->price ?></td>
                                        <td><?php echo $value->author ?></td>
                                        <td><?php 
                                        switch($value->status){
                                            case 0: echo "Rejected";break;
                                            case 1: echo "Requested";break;
                                            case 2: echo "Processing";break;
                                            case 3: echo "Issued";break;
                                        }
                                        ?></td>
                                        <td>
                                            <button class="btn btn-primary" id='mod_action_<?php echo $value->req_id ?>' onclick="modify('<?php echo $value->req_id ?>');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            <div id='action_<?php echo $value->req_id ?>' style="display: none; width: 100%">
                                                <div class="row">
                                                 
                                                <select style=" float: left; width: 70%;"  id="modify_<?php echo $value->req_id ?>" >
                                                    <option value="">Select Status</option>
                                                    <option value="0"
                                                            <?php
                                            if($value->status==0){
                                                echo "selected";
                                            }
                                                            ?>>Rejected</option>
                                                    <option value="1"
                                                            <?php
                                            if($value->status==1){
                                                echo "selected";
                                            }
                                                            ?>>Requested</option>
                                                    <option value="2"
                                                            <?php
                                            if($value->status==2){
                                                echo "selected";
                                            }
                                                            ?>>Processing</option>
                                                    <option value="3"
                                                            <?php
                                            if($value->status==3){
                                                echo "selected";
                                            }
                                                            ?>>Issued</option>
                                                </select>
                                                <button onclick="save_new_action('<?php echo $value->req_id ?>');" class="btn btn-primary" style=" float: left; width: 25%;" id='save_action_<?php echo $value->req_id ?>' onclick="modify('<?php echo $value->req_id ?>');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                </div>
                                                <span style=" clear: both; color: red;"  id='error_msg_<?php echo $value->req_id ?>'></span>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                         <?php
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

<script>
    function modify(id){
       $("#mod_action_"+id).hide();
       $("#action_"+id).show();
    }
    
    function save_new_action(id){
       va= $("#modify_"+id).val();
       $('#error_msg_'+id).html("");
       if(va.length==0){
           $('#error_msg_'+id).html("Please select new Status");
       }else{
           
           setState('tr_row_'+id,'<?php echo base_url() ?>index.php/Approvals/change_status','new_val='+va+'&id='+id);
       }
    }
    
    
</script>


<?php
$this->load->view('structure/footer');
?>