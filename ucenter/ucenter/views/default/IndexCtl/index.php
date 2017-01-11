<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php 
    $re_url = '';
    $re_url = Yf_Registry::get('re_url');

    $from = 'mall';
    $callback = $re_url;
    $t = '';
    $type = '';
    $act= '';
    $code = '';

    extract($_GET);
    //$qq_url = sprintf('%s?ctl=Connect_Qq&met=login&callback=%s&from=%s', Yf_Registry::get('url'), urlencode($callback) ,$from);
	$wx_url = sprintf('%s?ctl=Connect_Weixin&met=login&callback=%s&from=%s', Yf_Registry::get('url'), urlencode($callback),$from);
    $wb_url = sprintf('%s?ctl=Connect_Weibo&met=login&callback=%s&from=%s', Yf_Registry::get('url'), $callback ,$from);

	$qq_url = sprintf('%s?ctl=Connect_Qq&met=login&from=%s', Yf_Registry::get('url'), $from);
	//$wx_url = sprintf('%s?ctl=Connect_Weixin&met=login&from=%s', Yf_Registry::get('url'),$from);
	
    $connect_config = include_once APP_PATH  . '/configs/connect.ini.php';
    if($connect_config)
    {
      $qq = $connect_config['qq']['status'];
      $wx = $connect_config['weixin']['status'];
      $wb = $connect_config['weibo']['status'];  
    }else
    {
      $qq = 2;
      $wx = 2;
      $wb = 2;  
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>用户登录中心</title>
	<!-- <link href="css/style.css" rel='stylesheet' type='text/css' /> -->
	<link href="<?=$this->view->css?>/style.css" media="screen" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="qc:admins" content="340166442164526151665670216375" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Elegent Tab Forms,Login Forms,Sign up Forms,Registration Forms,News latter Forms,Elements"./>
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	</script>
	<script src="<?=$this->view->js?>/jquery.min.js"></script>
	<script src="<?=$this->view->js?>/easyResponsiveTabs.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#horizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion
				width: 'auto', //auto or any width like 600px
				fit: true   // 100% fit in a container
			});
		});
	</script>

	<!--webfonts-->
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,600,700,200italic,300italic,400italic,600italic|Lora:400,700,400italic,700italic|Raleway:400,500,300,600,700,200,100' rel='stylesheet' type='text/css'>
	<!--//webfonts-->
</head>
<body>
<div class="main">

	<div class="h1" ><img src="<?=$this->view->img?>/lg_title.png" alt=""/></div>

	<div class="copy-right" style="font-size:30px">
		<div class="wrap">
			<p>远丰集团 用户中心首页 - 此页面目前未启用</p>
		</div>
	</div>
</div>
</div>
<!--start-copyright-->
<div class="copy-right">
	<div class="wrap">
		<p>远丰集团 版权所有 沪ICP备11025711号</p>
	</div>
</div>
<!--//end-copyright-->
</body>
</html>