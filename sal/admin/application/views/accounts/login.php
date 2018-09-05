<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>L2B&Co.</title>
<link rel="shortcut icon" href="<?php echo assets_path ?>img/school.ico" />
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo assets_path ?>/style.css" rel="stylesheet" type="text/css" />
<script src="<?php echo assets_path ?>js/jquery.min.js"></script>

<script src="<?php echo assets_path ?>js/ajaxfunction.js"></script>
<script type="text/javascript">
function onSubmit1()
{
	//alert('hi');
	var username =$('#username').val();
	var password = $('#password').val();
	var url = document.URL;
	var id = url.substring(url.lastIndexOf('/') + 1);

	username = username.trim();
	password = password.trim();
	$('#error').html('');
	
	var error = 0;
	if(username.length == 0)
	{
		$('#username').css("border-color",'#F00');
		error=1;
	}
	if(password.length == 0)
	{
		$('#password').css("border-color",'#F00');
		error=1;
	}
	
	if(error == 0)
	{
	   loginsetState('error','<?php echo base_url() ?>index.php/accounts/login_check', 'id='+id+'&username='+username+'&password='+password);
	}
}
</script>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body>
<div class="container" >
<br/><br/><br/><br/><br/>
<div id="logo">
<table cellpadding="5" cellspacing="5" border="0" align="center">
<tr>
<td>
    <img src="<?php echo assets_path ?>images/logo.png" />
</td>
</tr>
</table>
</div>

<div style="clear:both;"></div>
</div>

<div class="container" >


<table cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:5px;" > 
<tr>
<td class="topleft"></td>
<td class="topmidd"></td>
<td class="topright"></td>
</tr>
<tr>
<td class="leftmidd"></td>
<td align="left" valign="top" bgcolor="#FFFFFF">

<h4>
 Login
</h4>

<table cellpadding="2" cellspacing="2" border="0" align="center" style="margin-top:10px;">

<tr>
<td>
<h2>User Name</h2>
</td>
<td>
	
<input type="text" name="textfield" id="username" style=" width:250px;height:18px; 	padding:3px; 	border:1px solid #ccc;" value="admin@snetwork.in" readonly="readonly" />
</td>
</tr>
<tr>
<td>
<h2>Password</h2>
</td>
<td>
    <input type="password" style=" width:250px;height:18px; 	padding:3px; 	border:1px solid #ccc;" name="textfield" id="password"  />
</td>
</tr>
<tr>
    <td  align="right" colspan="2">
        <div style=" width: 70%; float: left; text-align: left; color: red" >
            &nbsp;    
            <span id="error"></span>
        </div>
        <div style=" width: 30%; float: left">
              <button style=" color: white; background-color: teal" type="submit" onclick="onSubmit1();" name="button" id="button" value="Submit" class="submit" >Log In</button>
        </div>
    </td>
</tr>
</table>

</td>
<td class="rightmidd"></td>
</tr>
<tr>
<td class="bottomleft"></td>
<td class="bottommidd"></td>
<td class="bottomright"></td>
</tr>
</table>
    


<div style="clear:both;"></div>
</div>


<div class="container" style="margin-top:10px;">
<table cellpadding="0" cellspacing="0" border="0" align="center">
<tr>

<td >
<div id="footercontent">
© 2016 L2B&Co. All Rights Reserved<br />
+91 7674871305 | support@snetworkit.com
</div>
</td>

</tr>
</table>
</div>

</body>
</html>


