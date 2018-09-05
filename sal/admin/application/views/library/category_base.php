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
                    <a href="<?php echo base_url(); ?>index.php/library/">Library</a>
                     <i class="fa fa-angle-right" aria-hidden="true"></i>
                </li>
                <li href="<?php echo base_url(); ?>index.php/library/category">
                    <a href="components-messages.html">Manage Category</a>                   
                </li>
            </ul>            
        </div>
            
            <?php
             $query ="SELECT c.catid,c.category,(select count(*) from lib_books b WHERE b.category=c.catid AND b.status=1 )as available ,(select count(*) from lib_books b WHERE b.category=c.catid AND b.status=2 )as n_available FROM `book_category` c WHERE c.iid= '".$this->session->userdata('staff_Org_id')."'  ";
             $query = $this->db->query($query);
            ?>
        <div class="box box-bordered box-color">
                    
            <div class="box-title">
                <h3>Category Based Brief Report</h3>
            </div>
            <div  class="box-content nopadding">
                <table class="table table-hover table-nomargin table-bordered"  style="width: 100%">
                    <thead>
                        <tr>
                            <th>S.no</th>
                            <th>Category</th>
                            <th style=" color: white; background-color: #006699; text-align: center">Available</th>
                            <th style=" color: white;  background-color: #ff00cc; text-align: center" >Issued</th>
                            <th style=" color: white;  background-color: #ff9933; text-align: center" >Total</th>             
                        </tr>
                    </thead> 
                    <tbody>
                      <?php
                         if($query->num_rows()>0){
                             $query=$query->result();
                             $i=1;
                             foreach ($query as $value) {
                               ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $value->category ?></td>
                            <td style="color: #006699; text-align: center"><?php echo $value->available ?></td>
                            <td style="color: #ff00cc; text-align: center"><?php echo $value->n_available ?></td>
                            <td style="color: #ff9933; text-align: center"><?php echo $value->available+$value->n_available ?></td>
                        </tr>
                               <?php    
                             }                             
                         }else{
                             ?>
                        <tr>
                            <td colspan="5" style=" text-align: center"><span style=" color: red">** No Records Found</span></td>
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
