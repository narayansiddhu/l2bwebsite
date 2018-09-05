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
                <a href="<?php echo base_url(); ?>index.php/library/">Home</a>
                 <i class="fa fa-angle-right" aria-hidden="true"></i>
            </li>
            <li >
                <a href="<?php echo base_url(); ?>index.php/library/issue">Issue Book</a>
            </li>
        </ul>
    </div>  
    <?php
        if(strlen($this->session->userdata('book_issue_sucess'))>0 ){
            ?><br/>
                <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong>
                 <?php echo $this->session->userdata('book_issue_sucess'); ?>
                </div>
           <?php
            $this->session->unset_userdata('book_issue_sucess');
        }
    ?>
    
    
           <div class="box box-bordered box-color">
                    <div class="box">
                        <div class="box-title">
                            <h3><i class="fa fa-handshake-o"></i>Issue Book</h3>
                            <div class="actions">
                                <div class="actions">
                                    <a href="<?php echo base_url() ?>index.php/library/issue_list"  class="btn btn-primary"><i class="fa fa-handshake-o"></i>&nbsp;View All Issues</a>
                                </div>
                            </div>
                        </div>
                        <div  class="box-content nopadding">
                            <div class='form-horizontal form-bordered' > 
                                  <div class="form-group">
                                        <label for="textfield"  class="control-label col-sm-2">Student</label>
                                        <div class="col-sm-10">
                                            <select style="width: 100%" class="select2-me" id="student" name="student">
                                                <option value="">Select student</option>
                                                <?php
                                                  $query=$this->db->query("SELECT s.userid,s.student_id,s.userid,s.name,c.name as class,se.name as section  FROM student s JOIN class c ON s.class_id=c.id JOIN section se ON s.section_id =se.sid WHERE s.iid='".$this->session->userdata('staff_Org_id')."'");
                                                  $query=$query->result();
                                                  foreach ($query as $value) {
                                                    ?>
                                                <option value="<?php echo $value->student_id ?>"><?php echo $value->userid."-".$value->name ."(".$value->class."-".$value->section.")" ?></option>
                                                        <?php  
                                                  }
                                                ?>
                                            </select>
                                            <span id="student_err" style=" color: red">
                                                 
                                            </span> 
                                        </div>
                                    </div>
                                  
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
                                  
                                  <div  class="form-actions col-sm-offset-2 col-sm-10">
                                      <input type="button"  onclick="assign();"  name="submit"  value="Issue Book" class="btn btn-primary" />
                                       <span id="errors" style=" color: red">
                                                
                                       </span> 
                                  </div> 
                                 
                            </div>
                        </div> 
                    </div>
          </div>
       </div>
    </div>
    
</div>
<script>
  function assign(){
      $('#student_err').html('');
      $('#book_err').html("");
      student=$('#student').val();
      book=$('#book').val();
      count=0;
      if(student.length==0){
          count++;
          $('#student_err').html("Please select Student");
      }
      if(book.length==0){
          count++;
          $('#book_err').html("Please select Book");
      }
      if(count==0){
          setState('errors','<?php echo base_url() ?>index.php/library/issue_new','student='+student+'&book='+book);
      }
      
      
  }
</script>
<?php
$this->load->view('structure/footer');
?>