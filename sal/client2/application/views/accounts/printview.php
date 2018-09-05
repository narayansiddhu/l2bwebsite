<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $recipt->recipt ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">                   
        <link rel="stylesheet" href="http://localhost:82/school/assests/css/bootstrap.min.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body style=" padding: 1% ; color:#0c4472 ;">
        <div class="container-fluid" id="content">
           
  <div class="row"  style="  ">
    <div class="col-sm-12 nopadding" style=" padding: 1% ;">
        <div class="col-sm-12">
            <div class="pull-left"><br/>
                <img src="<?php echo assets_path ?>/uploads/<?php echo $institute->logo ?>" style=" width: 100px">            
            </div>        
            <div class="pull-right">
                <h3><strong style=" color:#0c4472 ; float: right" ><?php echo $institute->name ?></strong></h3>
                <strong style=" color:#0c4472 ; float: right ;  text-align: right" ><br/> 
                 <?php 
                   echo str_replace("\n", "<br/>", $institute->address) ;
                 ?>
                </strong>
            </div>
         </div>
        <div style=" clear: both" class="col-sm-12">
            <br/>
            <h3 style="text-align: center"><strong>Fee-Recipt</strong></h3>
            
            <div class="col-sm-12">
                 <h3 style=" color:#0c4472 ; text-align: center " ><u>
                                    Recipt No :  <?php echo $recipt->recipt ?></u>
                            </h3>
            <table class="table table-hover table-nomargin  table-bordered " style=" width: 100%; ">
                
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td>:</td>
                        <td><?php echo $recipt->name ?></td>
                    </tr>
                    <tr>
                        <td>Admission No</td>
                        <td>:</td>
                        <td><?php echo $recipt->userid ?></td>
                    </tr>
                    <tr>
                        <td>Class - section</td>
                        <td>:</td>
                        <td><?php echo $recipt->clsname ."  -  ".$recipt->section  ?></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td>:</td>
                        <td><?php echo $recipt->amount ?> /-</td>
                    </tr>
                    <tr>
                        <td>Amount ( In Words)</td>
                        <td>:</td>
                        <td><?php echo  NumbersToWords::convert($recipt->amount); ?>  Only</td>
                    </tr>
                    <tr>
                        <td>Date & time</td>
                        <td>:</td>
                        <td><?php  echo date("d-m-Y H:i", $recipt->time); ?></td>
                    </tr>
                </tbody>
            </table>
            </div><br/><br/>
            <br/><br/><br/><br/>
            <div class="col-sm-12 nopadding">
              <div class="pull-left">
                  <img src="<?php echo base_url() ?>/index.php/barcode/barcode/<?php echo $recipt->recipt ?>" alt="echo no image found.."  />
              </div>        
              <div class="pull-right">
                  <strong>Issued By</strong>
                  <br/><?php echo $recipt->staff_name ?>
              </div>
            </div>
            
        </div>

    
</div>
  </div>
  </div>
        
        <button onClick="$(this).hide();window.print();">print</button>
        
    </body>
     
 </html>
<?php
 
class NumbersToWords{
    
    public static $hyphen      = '-';
    public static $conjunction = ' and ';
    public static $separator   = ', ';
    public static $negative    = 'negative ';
    public static $decimal     = ' point ';
    public static $dictionary  = array(
      0                   => 'zero',
      1                   => 'one',
      2                   => 'two',
      3                   => 'three',
      4                   => 'four',
      5                   => 'five',
      6                   => 'six',
      7                   => 'seven',
      8                   => 'eight',
      9                   => 'nine',
      10                  => 'ten',
      11                  => 'eleven',
      12                  => 'twelve',
      13                  => 'thirteen',
      14                  => 'fourteen',
      15                  => 'fifteen',
      16                  => 'sixteen',
      17                  => 'seventeen',
      18                  => 'eighteen',
      19                  => 'nineteen',
      20                  => 'twenty',
      30                  => 'thirty',
      40                  => 'fourty',
      50                  => 'fifty',
      60                  => 'sixty',
      70                  => 'seventy',
      80                  => 'eighty',
      90                  => 'ninety',
      100                 => 'hundred',
      1000                => 'thousand',
      1000000             => 'million',
      1000000000          => 'billion',
      1000000000000       => 'trillion',
      1000000000000000    => 'quadrillion',
      1000000000000000000 => 'quintillion'
    );
    public static function convert($number){
      if (!is_numeric($number) ) return false;
      $string = '';
      switch (true) {
        case $number < 21:
            $string = self::$dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = self::$dictionary[$tens];
            if ($units) {
                $string .= self::$hyphen . self::$dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = self::$dictionary[$hundreds] . ' ' . self::$dictionary[100];
            if ($remainder) {
                $string .= self::$conjunction . self::convert($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = self::convert($numBaseUnits) . ' ' . self::$dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? self::$conjunction : self::$separator;
                $string .= self::convert($remainder);
            }
            break;
      }
      return $string;
    }
  }//end class

?>