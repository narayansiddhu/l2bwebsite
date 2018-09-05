<?php
include("threads.php");
if(isset($_SERVER["argv"][1]))
{
	$thread=$_SERVER["argv"][1];
	echo "Thread ".$thread." started.\n";
	$threadInit->startThread($thread);
}
else if(isset($_GET['thread']))
{
	$thread=$_GET['thread'];
	echo "Thread ".$thread." started.\n";
	$threadInit->startThread($thread);
}
?>
