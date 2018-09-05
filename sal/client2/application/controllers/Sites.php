<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sites extends CI_Controller {
    
  /**
   * The __construct function is called so that I don't have to load the model each and every time.
   * And any change while refactoring or something else would mean change in only one place.
   */
  function __construct() {
    parent::__construct();
    $this->load->library('HighCharts_lib.php');
  }
    
  /**
   * This is the first method which get's executed when someone will go to the site section.
   */
  private function index(){
        $data['charts'] = $this->getChart($studentName);
        $this->load->view('charts',$data);
}

private function getChart($stuName) {

        $this->highcharts->set_title('Name of Student :' . $stuName);
        $this->highcharts->set_dimensions(740, 300); 
        $this->highcharts->set_axis_titles('Date', 'Age');
        $credits->href = base_url();
        $credits->text = "Class Structure";
        $this->highcharts->set_credits($credits);
        $this->highcharts->render_to("content_top");

        $result = $this->student_name->getStudentDetails($stuName);

            if ($myrow = mysql_fetch_array($result)) {
                do {
                    $value[] = intval($myrow["age"]);
                    $date[] = ($myrow["date"]);
                } while ($myrow = mysql_fetch_array($result));
            }

            $this->highcharts->push_xcategorie($date);

            $serie['data'] = $value;
            $this->highcharts->export_file("Code 2 Learn Chart".date('d M Y')); 
            $this->highcharts->set_serie($serie, "Age");

            return $this->highcharts->render();

        }
    }


?>