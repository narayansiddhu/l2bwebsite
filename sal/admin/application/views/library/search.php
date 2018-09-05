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
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/issue">Search Book</a>
            </li>
        </ul>
    </div>  
    
        
           <div class="box box-bordered box-color nopadding">
                    
                        <div class="box-title">
                            <h3>Search Transactions</h3>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered' > 
                                <div class="col-sm-6 nopadding">
                                  <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select style="width: 100%" class="select2-me" id="student" name="student">
                                                <option value="">Select student</option>
                                                <?php
                                                  $query=$this->db->query("SELECT s.student_id,s.userid,s.name,c.name as class,se.name as section  FROM student s JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id =se.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."'");
                                                  $query=$query->result();
                                                  foreach ($query as $value) {
                                                    ?>
                                                <option value="<?php echo $value->student_id ?>"><?php echo $value->class."-".$value->section."-".$value->name ?></option>
                                                        <?php  
                                                  }
                                                ?>
                                            </select>
                                            <span id="student_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Class/Section</label>
                                        <div class="col-sm-10">
                                            <select style="width: 100%" class="select2-me" id="section" name="section">
                                                <option value="">Select Section</option>
                                                <?php
                                                  $query=$this->db->query("select s.sid,s.name as section,c.name as class FROM section s JOIN class c on s.class_id=c.id    WHERE s.iid='".$this->session->userdata('staff_Org_id')."'");
                                                  $query=$query->result();
                                                  foreach ($query as $value) {
                                                    ?>
                                                     <option value="<?php echo $value->sid ?>"><?php echo $value->class."-".$value->section ?></option>
                                                    <?php  
                                                  }
                                                ?>
                                            </select>
                                            <span id="section_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Book</label>
                                        <div class="col-sm-10">
                                            <select style="width: 100%" class="select2-me" id="book" name="book">
                                                <option value="">Select Book</option>
                                                <?php
                                                  $query=$this->db->query("SELECT * FROM `lib_books` WHERE iid='".$this->session->userdata('staff_Org_id')."'");
                                                  $query=$query->result();
                                                  foreach ($query as $value) {
                                                    ?>
                                                <option  value="<?php echo $value->book_id ?>"><?php echo $value->buid."-".$value->name ?></option>
                                                        <?php  
                                                  }
                                                ?>
                                            </select>
                                            <span id="book_err" style=" color: red">
                                                  
                                            </span> 
                                        </div>
                                    </div>
                                  <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Transaction id</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="search" id='search' placeholder="Please enter search keyword" class="form-control" />
                                            <span id="search_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                                </div>
                                <div style=" align-content: center; align-items: center"  class="form-actions  col-sm-12">
                                      <input type="button"  onclick="fetch();"  name="submit"  value="Search Transactions" class="btn btn-long btn-primary" />
                                       <span id="errors" style=" color: red">
                                                
                                       </span> 
                                  </div> 
                                 
                            </div>
                        </div> 
                    
          </div>
       
    
   <script>
    function fetch(){
       $('#errors').html('');
        
        student=$('#student').val();
        book=$('#book').val();
        search=$('#search').val().trim();
        section=$('#section').val();
        count=0;
        if(student.length>0){
           count++; 
        }
        if(book.length>0){
           count++; 
        }
        if(section.length>0){
           count++; 
        }
        if(search.length>0){
           count++; 
        }
        if(count==0){
            $('#errors').html('<br/>Please select any of the above');
        }else{
            $('#results').show();
            setState('results_holder','<?php echo base_url() ?>index.php/library/get_results','student='+student+'&search='+search+'&book='+book+'&section='+section);
        }
    } 
  </script>  
   
           <div id='results' style=" display: none " class="box box-bordered box-color">
                   
                        <div class="box-title">
                            <h3>Search Results</h3>
                        </div>
                        <div   class="box-content nopadding">
                            <table class="table table-hover table-nomargin">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Transaction id</th>
                                        <th>Book </th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Status</th>
                                        <th>Issued</th>
                                        <th>return</th>
                                        <th>Fine</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="results_holder" >
                                    
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