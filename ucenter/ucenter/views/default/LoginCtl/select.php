<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<!DOCTYPE html>
<html>
<head>
<title>用户登录中心</title>
<link href="<?=$this->view->css?>/ss.css" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content=""./>
</head>
<body>
<div class="main">
         <div class="ctclinks">
            <div class="">
				<div class="gl"><img src="<?=$this->view->img?>/link_title.png" alt=""/> </div>
				<div class="head"><a><img src="<?=$this->view->img?>/head_df.png" alt=""/> </a></div>
				<div> 
					<p class="sname">用户名</p>
					<p class="stitle">您的账号尚未关联帐号</p>
					<ul>
                           <li class="lnew"><a href="<?=sprintf('%s?ctl=Login&act=reg&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>">关联新账户</a></li>
                           <li class="lold"><a href="<?=sprintf('%s?ctl=Login&t=%s&type=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('type'), request_string('from'), urlencode(request_string('callback')))?>">关联到已有账户</a></li>
					</ul>
				</div>
                <div class="clear"></div>
				</div>
				</div> 
			</div>
	<!--start-copyright-->
   		<div class="copy-right">
   			<div class="wrap">
				<!-- <p>远丰集团 版权所有 沪ICP备11025711号</p> -->
		</div>
	</div>
	<!--//end-copyright-->
</body>
</html>