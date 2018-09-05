<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box"> <br/>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>index.php/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            
            <li >
                <a href="">Requested Book List</a>
            </li>
            </ul>
    </div>                  
    
           <div class="box box-bordered box-color">
                    
                        <div class="box-title">
                            <h3><i class="fa fa-hand-paper-o" aria-hidden="true"></i>List Of Books Requested</h3>
                            <div class="actions">
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/library/request" style=" color: #3866E0; background-color: white" class="btn btn-primary"><i class="fa fa-hand-paper-o fa-2x" aria-hidden="true"></i>&nbsp;Request New</a>
                                </div>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <table  class="table datatable table-hover table-nomargin table-bordered ">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Title</th>
                                        <th>category</th>
                                        <th>price</th>
                                        <th>author</th>
                                        <th>status</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php
                                    $id=1;
                                      foreach( $results as $value){
                                         ?>
                                    <tr>
                                        <td><?php echo $id++; ?></td>
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

