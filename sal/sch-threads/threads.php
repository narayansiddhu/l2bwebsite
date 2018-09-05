<?php 
class Threads
{
	var $appcon;
	var $db_name = "schooln";
	var $db_servername = "localhost";
	var $db_password = "ctrls@123";
	var $db_port = "";
	var $db_username = "root";
	
	
	function __construct()
	{
		$this->appcon = mysql_pconnect($this->db_servername,$this->db_username,$this->db_password) or die(mysql_error);
		mysql_select_db($this->db_name, $this->appcon) or die(mysql_error());
		
		date_default_timezone_set('Asia/Kolkata');
	}
	
	function query($query)
	{
		return mysql_query($query);
	}
	
	function num_rows($sql)
	{
		return mysql_num_rows($sql);
	}
	
	function fetch_array($sql)
	{
		return mysql_fetch_array($sql);
	}
	
	function startThread($thread)
	{
		$i=0;
		$j=0;
		for(;;)
		{
			$sql = $this->query("SELECT * FROM msg_senthistory WHERE schedule != 1  AND status=0 ");
			
			if($this->num_rows($sql) > 0)
			{
				while($data = $this->fetch_array($sql))
				{
				        $url = "http://sch.leonas.in/spanelv2/api.php?username=".$data['username']."&password=".$data['password']."&from=".$data['senderid']."&to=".$data['mobile']."&message=".urlencode($data['message']);
					file($url);
                    $this->query("update  msg_senthistory SET status=1 WHERE id='".$data['id']."' ");
			
				}
		             		
			}
			sleep(5);
		}
	}
	
	function scheduleThreads($thread)
	{
		$i=0;
		$j=0;
		for(;;)
		{
			$time = time()+360;//6 Mins Adv for Schedule
 			
			$sql = $this->query("SELECT * FROM msg_senthistory WHERE schedule = 1 AND time <= ".$time." AND status=0 ");
			
			if($this->num_rows($sql) > 0)
			{
				while($data = $this->fetch_array($sql))
				{
					$date = date("H")."".(date("i")+300)."".date("m")."".date("d")."".date("Y");
					echo $url = "http://sch.leonas.in/spanelv2/api.php?username=".$data['username']."&password=".$data['password']."&from=".$data['senderid']."&to=".$data['mobile']."&message=".urlencode($data['message'])."&date=".$date;
					file($url);
                    $this->query("update  msg_senthistory SET status=1 WHERE id='".$data['id']."' ");
			
				}
				
			}
			sleep(5);
		}
	}
}
$threadInit = new Threads();
?>
