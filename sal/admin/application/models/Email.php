<?php  
   class Email extends CI_Model  
   {  
      function __construct()  
      {  
         // Call the Model constructor  
         parent::__construct();  
          $this->load->library('Email'); $this->load->helper('email_helper'); 
         
      }  
      
      public function send_supportmail_alert($from,$sqid){

          $staff_level=  unserialize(staff_level);
  
          $sqid ="SELECT * from support_queries where qid='".$sqid."'";
          $sqid= $this->db->query($sqid)->row();
     
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'snetwork.supprt@gmail.com';
        $config['smtp_pass']    = 'pavan576';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      
$message ='<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<meta charset="utf-8"> 
            <meta name="viewport" content="width=device-width" > 
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="x-apple-disable-message-reformatting">	
	<link href="https://fonts.googleapis.com/css?family=Uncial+Antiqua" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Baloo+Tammudu" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
	<title></title> 
	<style>
		html,
		body {
			margin: 0 auto !important;
			padding: 0 !important;
			height: auto !important;
			width: 700px !important;
		}
		* {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		div[style*="margin: 16px 0"] {
			margin:0 !important;
		}

		table,
		td {
			mso-table-lspace: 0pt !important;
			mso-table-rspace: 0pt !important;
		}
		table {
			border-spacing: 0 !important;
			border-collapse: collapse !important;
			table-layout: fixed !important;
			margin: 0 auto !important;
		}
		table table table {
			table-layout: auto;
		}
		img {
			-ms-interpolation-mode:bicubic;
		}

		*[x-apple-data-detectors],	/* iOS */
		.x-gmail-data-detectors, 	/* Gmail */
		.x-gmail-data-detectors *,
		.aBn {
			border-bottom: 0 !important;
			cursor: default !important;
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}
		.a6S {
			 display: none !important;
			 opacity: 0.01 !important;
		}
		img.g-img + div {
			 display:none !important;
			}
		.button-link {
			text-decoration: none !important;
		}

		@media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
			.email-container {
				min-width: 375px !important;
			}
		}
		.button-td,
		.button-a {
			transition: all 100ms ease-in;
		}
		.button-td:hover,
		.button-a:hover {
			background: #555555 !important;
			border-color: #555555 !important;
		}

		@media screen and (max-width: 600px) {

			.email-container {
				width: 650px !important;
                                height: auto !important;
				margin: auto !important;
			}

			.fluid {
				max-width: 100% !important;
				height: auto !important;
				margin-left: auto !important;
				margin-right: auto !important;
			}
			.stack-column,
			.stack-column-center {
				display: block !important;
				width: 100% !important;
				max-width: 100% !important;
				direction: ltr !important;
			}
			.stack-column-center {
				text-align: center !important;
			}
			.center-on-narrow {
				text-align: center !important;
				display: block !important;
				margin-left: auto !important;
				margin-right: auto !important;
				float: none !important;
			}
			table.center-on-narrow {
				display: inline-block !important;
			}
            .email-container p {
				font-size: 17px !important;
				line-height: 22px !important;
			}

		}
		


	</style>
</head>
<body  bgcolor="#66ccff" style=";margin: 0; width:700px">
    <br/><br/>
		<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600px" style="margin: auto;" class="email-container">

			<!-- Hero Image, Flush : BEGIN -->
			<tr>
				<td colspan="3" bgcolor="#ffffff" align="center">
				<h3 style="color : goldenrod; font-size : 28px;font-family: '."Cinzel".', serif; ">L2B&Co. ONLINE SUPPORT</h3>
				</td>
			</tr>
			<tr>
				<td colspan="3" bgcolor="teal" style="padding:9px; font-family: sans-serif; line-height: 20px;  text-align: center;">
                                    <p style=" color: white; font-size: 22px; font-family: '."Baloo Tammudu".', cursive;">Support Query</p>
				</td>
			</tr>
			<tr>
                            <td colspan="3" bgcolor="#ffffff" valign="middle" style="text-align: center; background-position: center center !important; background-size: cover !important;">
                                
                                <br/>
                                <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="color : green;height:auto; background-color : #ffffff; " >
                                   <tr>
                                     <td colspan="3">Concern Name : '.$this->session->userdata("staff_name").' </td>
                                   </tr>
                                   <tr>
                                     <td colspan="3" >Login Email  : '.$this->session->userdata("staff_user").' </td>
                                         </tr>
                                   <tr>
                                     <td colspan="3">User Level  : '.$staff_level[$this->session->userdata("staff_level")].' </td></tr>
                                   <tr>
                                     <td colspan="3" >Institute Name  : '.$this->session->userdata("inst_name").' </td>
                                         </tr>
                                
                                   </tr>
                                       <tr>
                                            <td colspan="3" valign="left" style="text-align: left;  color: teal;">
                                                    <p style="margin: 0;font-family: '."Courgette".';">Title : '.$sqid->title.'
                                                    </p>
                                            </td>
                                            </tr>
                                            <tr>
                                            <td colspan="3" valign="middle" style="text-align: center; padding-top: 6px; font-family: '."Courgette".', cursive; font-size: 15px; line-height: 20px; color: teal;">
                                                   <h4 style="text-align: left; ">Query : </h4> <p style="margin: 0;font-family: '."Courgette".', cursive;">'.$sqid->query.'
                                                    </p>
                                            </td>
                                            <tr>
                                            <td colspan="3"  style="text-align: left; padding-top: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: teal;">
                                                    <p style="margin: 0;font-family: '."Courgette".', cursive;"> Posted ON : '.date("d-m-Y h:i A",$sqid->time).'
                                                    </p>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td colspan="3">&nbsp;</td>
                                        </tr>
                                </table>
                            </td>
			</tr>
		</table>
</body>
</html>';
//echo $message;exit;

        $this->email->initialize($config);

        $this->email->from('snetwork.supprt@gmail.com', 'L2B&Co. Support');
        $this->email->to('support@snetworkit.com'); 
        $this->email->subject('L2B&Co. Support Issue');
        $this->email->message($message); 
        if(strlen(trim($sqid->file))!=0){
             $this->email->attach( upload_path."/".$sqid->file);
         }
          $this->email->send();
        $this->email->print_debugger();       
      }
      
      
   }
   
?>