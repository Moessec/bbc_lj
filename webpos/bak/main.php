<?php
include_once("config.php");
 
//====================================
if(empty($_SESSION["ADMIN_USER"]))
{	
	msg("index.php"); 
}
$config['temp']='default';
$_SESSION['temp'] = $config['temp'];
$tpl->assign("config",$config);
$tpl->display("main.htm");
?>

