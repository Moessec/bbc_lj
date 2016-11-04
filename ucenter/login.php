<?php
$code = '';
$state = '';

extract($_GET);
if($code)
{
	$url = sprintf('%s?ctl=Connect_Qq&met=callback&code=%s&callback=%s','http://ucenter.yuanfeng021.com/' , $code, $state);
	header('Location:'.$url);
}else
{
	$url = sprintf('%s?ctl=Login','http://ucenter.yuanfeng021.com/');
	header('Location:'.$url);
}

?>
