<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>

<div class="row">
    
    <div class="col-sm-12">
        <div class="box">
            
        <br/><br/>
            <?php
                $s=$this->db->query("SELECT status,count(*) as counter FROM `lib_books` WHERE iid='".$this->session->userdata('staff_Org_id')."'  group by status ");
                $s=$s->result();
                $lib_data=array(
                    'issued' =>0,
                    'available'=>0,
                    'total'=>0
                );



                foreach($s as $value){
                   if($value->status==2){
                      $lib_data['issued'] =$lib_data['issued']+$value->counter;
                   }elseif($value->status==1){
                      $lib_data['available'] =$lib_data['available']+$value->counter;
                   }

                }

                $lib_data['total']=$lib_data['available']+$lib_data['issued'];
            ?>
            
            <div class="col-sm-12">
                                        
                <ul style=" width: 100%" class="stats">

                    <li style=" width: 23%" class='orange'>
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <div class="details">
                                    <span class="big"><?php  echo $lib_data['total'] ?></span>
                                    <span class="value">Total Books</span>
                            </div>
                    </li>
                    <li style=" width: 23%"  class='red'>
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <div class="details">
                                    <span class="big"><?php  echo $lib_data['issued'] ?></span>
                                    <span>Books Issued</span>
                            </div>
                    </li>
                    <li style=" width: 23%"  class='blue'>
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <div class="details">
                                    <span class="big"><?php  echo $lib_data['available'] ?></span>
                                    <span>Book Available</span>
                            </div>
                    </li>
                    <li style=" width: 23%"  class='lime'>
                            <i class="fa fa-book" aria-hidden="true"></i>
                           <div class="details">
                                <span class="big">
                                    <?php
                                      $nw= getdate();
                                      $t=mktime(0, 0, 0, $nw['mon'], $nw['mday'], $nw['year']);
                                      $q=$this->db->query("SELECT * FROM `lib_issues` WHERE `issued_date`>'".$t."' and  `issued_date`<'".($t+86400)."'");
                                      echo $q->num_rows();                                  
                                    ?>
                                </span>
                                    <span>Issued Today</span>
                            </div>
                    </li>
                </ul>
            </div>
             <br/><br/><br/>
       
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                       
                        Recent Issues
                        </h3>
                    </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin">
                        <thead>
                               <tr>
                                   <th>S.no</th>
                                   <th>Trans ID</th>
                                   <th>Student</th>
                                   <th>Book</th>
                                   <th>Time</th>
                               </tr>
                           </thead>
                           <tbody>
                                <?php
                                   $query=$this->db->query("SELECT li.issue_id,li.trans_id,li.issued_date,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id WHERE li.iid='".$this->session->userdata('staff_Org_id')."' ORDER BY  li.`issued_date` DESC LIMIT 0 , 10 ");
                                   $query=$query->result();$i=1;
                                   foreach($query as $val){
                                       ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $val->issue_id ?>" ><?php echo $val->trans_id ?></a></td>
                                            <td><?php echo $val->studname ?></td>
                                            <td><?php echo $val->book ?></td>
                                            <td><?php echo date('d-m-y',$val->issued_date) ?></td>
                                        </tr>
                                       <?php
                                   }
                                ?>
                           </tbody>
                       </table>
                       <div class="table-pagination">
                           <a  href="<?php echo base_url(); ?>index.php/library/issue_list">View More&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                       </div>
                   </div>
               </div>
           
           
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                          Recent Returns
                        </h3>
                    </div>
                <div class="box-content nopadding">
                <table class="table table-hover table-nomargin">
                           <thead>
                               <tr>
                                   <th>S.no</th>
                                   <th>Trans ID</th>
                                   <th>Student</th>
                                   <th>Book</th>
                                   <th>Time</th>
                               </tr>
                           </thead>
                           <tbody>
                                <?php
                                   $query=$this->db->query("SELECT li.issue_id,li.trans_id,li.return_date,s.name as studname,s.userid as admissionid,lb.name as book,lb.buid FROM `lib_issues` li join student s ON li.student_id=s.student_id JOIN lib_books lb ON li.bookid=lb.book_id WHERE li.iid='".$this->session->userdata('staff_Org_id')."' AND li.status=2 ORDER BY  li.`return_date` ASC LIMIT 0 , 10 ");
                                   $query=$query->result();$i=1;
                                   foreach($query as $val){
                                       ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><a href="<?php echo base_url(); ?>index.php/library/view_issue/<?php echo $val->issue_id ?>" ><?php echo $val->trans_id ?></a></td>
                                            <td><?php echo $val->studname ?></td>
                                            <td><?php echo $val->book ?></td>
                                            <td><?php echo date('d-m-y',$val->return_date) ?></td>
                                        </tr>
                                       <?php
                                   }
                                ?>
                           </tbody>
                       </table>
                       <div class="table-pagination">
                       <a  href="<?php echo base_url(); ?>index.php/library/issue_list">View More&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                       </div>
                   </div>
               </div>
          
        
      
    </div>
        
     </div>
</div>
<?php
$this->load->view('structure/footer');
?>