<?php 
$ip = $_SERVER['REMOTE_ADDR']; $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip; $html = file_get_contents($url);
 $json = json_decode($html); 
 var_dump($json);
?>