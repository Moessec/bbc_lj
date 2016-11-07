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
        //
    $qq_url = sprintf('%s?ctl=Connect_Qq&met=login&callback=%s&from=%s', Yf_Registry::get('url'), $callback ,$from);
	$wx_url = sprintf('%s?ctl=Connect_Weixin&met=login&callback=%s&from=%s', Yf_Registry::get('url'), $callback ,$from);
    $wb_url = sprintf('%s?ctl=Connect_Weibo&met=login&callback=%s&from=%s', Yf_Registry::get('url'), $callback ,$from);


    $connect_config = Yf_Registry::get('connect_rows');
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

<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>用户登录中心</title>
<!-- <link href="css/style.css" rel='stylesheet' type='text/css' /> -->
<link href="<?=$this->view->css?>/style.css" media="screen" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="qc:admins" content="340166442164526151665670216375" />
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
		<input type="hidden" name="from" class="from" value="<?php echo $from;?>">
		<input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback);?>">
		<input type="hidden" name="t" class="t" value="<?php echo $t;?>">
        <input type="hidden" name="type" class="type" value="<?php echo $type;?>">
		<input type="hidden" name="act" class="act" value="<?php echo $act;?>">
		<input type="hidden" name="code" class="code" value="<?php echo $code;?>">
        <input type="hidden" name="re_url" class="re_url" value="<?php echo $re_url;?>">

		
		<div class="h1"><img src="<?=$this->view->img?>/lg_title.png" alt=""/></div>
<!--        <div class="sap_tabs"><img src="images/login_ic.png" alt=""/></div>
-->	 <div class="sap_tabs">	
			<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
			  <ul class="resp-tabs-list">
			  	  <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><div class="top-img"><img src="<?=$this->view->img?>/top-lock.png" alt=""/></div><span>登录</span></li>
				  <li id="register" class="resp-tab-item" aria-controls="tab_item-1" role="tab"><div class="top-img"><img src="<?=$this->view->img?>/top-note.png" alt=""/></div><span>注册</span></li>
				  <li id="resetpassword" class="resp-tab-item lost" aria-controls="tab_item-2" role="tab"><div class="top-img"><img src="<?=$this->view->img?>/top-key.png" alt=""/></div><span>找回密码</span></li>
				  <div class="clear"></div>
			  </ul>		
			  <!---->		  	 
			<div class="resp-tabs-container">
			 <div class="tab-2 resp-tab-content" aria-labelledby="tab_item-1">
					 	<div class="facts">
							 <div class="login">
							
							<!-- <form> -->
								<input type="text" class="text lo_user_account" placeholder="请输入用户名/Email/手机号"><a href="#" class=" icon email"></a>

								<input class="lo_user_password" id="password" type="password" placeholder="请输入您的账户密码" ><a href="#" class=" icon lock"></a>

								<div class="p-container">
									<div class="submit three">
									<input type="submit" onclick="loginclick()" value="登         录" >
									</div>
									<div class="clear"> </div>
								</div>

							<!-- </form> -->
                            <!--<div class="buttons">
								<ul>
								    <li class="fb">
										<a href="#" class="hvr-bounce-to-bottom">FACEBOOK</a>
									</li>
									<li class="twr">
										<a href="#" class="hvr-bounce-to-top">TWITTER</a>
									</li>
								    <li class="fb">
										<a href="#" class="hvr-bounce-to-bottom">FACEBOOK</a>
									</li>
									<div class="clear"> </div>
								</ul>
							</div>-->
                            <div class="buttons">
                            <ul>
                            <?php if($qq == 1) {?> <!-- 1-开启 2-关闭 -->
                            <li class="fb"><a href="<?=$qq_url;?>"><img src="<?=$this->view->img?>/fast_qq.png" alt=""/></a></li>
                            <?php }
                            if($wx == 1){
                            ?>
                            <li class="fb"><a href="<?=$wx_url;?>"><img src="<?=$this->view->img?>/fast_wx.png" alt=""/></a></li>
                            <?php }
                            if($wb == 1){
                            ?>
                            <li class="fb"><a href="<?=$wb_url;?>"><img src="<?=$this->view->img?>/fast_wb.png" alt=""/></a></li>
                            <?php }?>
                             </ul>
                             <div class="clear"></div>
                           </div>
					</div>
				</div> 
			</div> 			        					 
					<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
					<div class="facts">
							<!--login1-->
							 <div class="login">
							<!-- <form> -->
								<input type="text" class="text re_user_account" placeholder="请输入用户账号" ><a href="#" class=" icon email"></a>

								<input type="password" id="password" class="re_user_password" placeholder="请输入您的账户密码" ><a href="#" class=" icon lock"></a>
								<input type="phone" class="text re_mobile" placeholder="请输入手机号码" ><a href="#" class=" icon phone"></a>
								<input type="identify" class="text re_user_code" placeholder="输入验证码"><div class="id_get id_get_de" onclick="get_randfunc(this)" style="cursor:pointer;">获取验证码</div>
									<div class="submit three">
									<input type="submit" onclick="registclick()" value="注         册" >
									</div>
									<div class="clear"> </div>

							<!-- </form> -->

						</div>
					</div>
				</div>		
				 <div class="tab-3 resp-tab-content" aria-labelledby="tab_item-2 item3">
				     	<div class="facts">
<div class="login">
							<!-- <form> -->
								<!-- <input type="text" class="text ps_user_account" value="请输入用户名" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '请输入用户名';}" ><a href="#" class=" icon email"></a> -->

								<input type="password" id="password" class="ps_user_password" placeholder="重置账户密码"><a href="#" class=" icon lock"></a>
								<input type="phone" class="text ps_mobile" placeholder="请输入手机号码"><a href="#" class=" icon phone"></a>
								<input type="identify" class="text ps_user_code" placeholder="输入验证码" ><div class="id_get id_get_ps" onclick="get_ps_randfunc(this)" style="cursor:pointer;">获取验证码</div>
									<div class="submit three">
									<input type="submit" onclick="resetPasswdClick()" value="确         认" >
									</div>
									<div class="clear"> </div>

							<!-- </form> -->
						</div>
                        </div>
				         	</div>           	      
				        </div>	
				     </div>	
		        </div>
	        </div>
	     </div>

<script>
	$(document).ready(function() {

	$from = $(".from").val();
	$callback = $(".callback").val();
	$t = $(".t").val();
    $type = $(".type").val();
	$act = $(".act").val();
    $re_url = $(".re_url").val();
	
	if($act == 'reg')
	{
		$('#register').trigger('click');
	}
    if($act == 'reset')
    {
        $('#resetpassword').trigger('click');
    }

    });

	//登录按钮
    function loginclick(){
    	var user_account = $('.lo_user_account').val();
    	var user_password = $('.lo_user_password').val();
    	/*alert(user_password);
    	alert(user_account);*/

        $.post("./index.php?ctl=Login&met=login&typ=json",{"user_account":user_account,"user_password":user_password,"t":$t,"type":$type} ,function(data) {
            console.info(data);
              if(data.status == 200)
              {
                k = data.data.k;
                u = data.data.user_id;
                if($callback)
                {
                    window.location.href = decodeURIComponent($callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);

                }
                else
                {
                    window.location.href = decodeURIComponent($re_url);
                }
                console.info(k);
                console.info(u);
              }else{
                alert(data.msg);
                //window.history.back(-1);
              }
            });

    };

	//获取注册验证码
    function get_randfunc()
    {

		if (!window.randStatus)
		{
			return;
		}

    	var mobile = $('.re_mobile').val();

        if(!isNaN(mobile) && mobile.length == 11)
        {
            var ajaxurl = './index.php?ctl=Login&met=regCode&typ=json&mobile='+mobile;
            $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: "json",
                    async: false,
                    success: function (respone)
                    {
                        if(respone.status == 250)
                        {
                            alert('该手机号已注册');
                        }
                        else
                        {
							window.countDown();
                            alert('请查看手机短信获取验证码!');
                        }
                        
                        console.info(respone);
                    }
                });
                
            $('.randfuc').html('重新获取验证码');  
        }
        else
        {
           alert('请填写手机号!');
        }
    	
    }

	msg = "<?=_('获取验证码')?>";
	var delayTime = 60;
	window.randStatus = true;
	window.countDown = function ()
	{
		window.randStatus = false;
		delayTime--;
		$('.id_get_de').html(delayTime + "<?=_(' 秒后重新获取')?>");
		if (delayTime == 0) {
			delayTime = 60;
			$('.id_get_de').html(msg);

			clearTimeout(t);

			window.randStatus = true;
		}
		else
		{
			t=setTimeout(countDown, 1000);
		}
	}

	window.randPsStatus = true;
	var delayPsTime = 60;
	window.countPsDown = function ()
	{
		window.randPsStatus = false;
		delayPsTime--;
		$('.id_get_ps').html(delayPsTime + "<?=_(' 秒后重新获取')?>");
		if (delayPsTime == 0) {
			delayPsTime = 60;
			$('.id_get_ps').html(msg);

			clearTimeout(t);

			window.randPsStatus = true;
		}
		else
		{
			t=setTimeout(countPsDown, 1000);
		}
	}


    //注册按钮
    function registclick(){
    	var user_account = $('.re_user_account').val();
    	var user_password = $('.re_user_password').val();
    	var user_code = $('.re_user_code').val();
    	var mobile = $('.re_mobile').val();

    	/*alert(user_password);
    	alert(user_account);
    	alert(user_code);
    	alert(mobile);*/

        
        $.post("./index.php?ctl=Login&met=register&typ=json",{"user_account":user_account,"user_password":user_password,"user_code":user_code,"mobile":mobile,"t":$t} ,function(data) {
            console.info(data);
              if(data.status == 200)
              {
                k = data.data.k;
                u = data.data.user_id;
                if($from)
                {
					window.location.href = decodeURIComponent($callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
                }
                else
                {
                    window.location.href = decodeURIComponent($re_url);
                }
                console.info(k);
                console.info(u);
              }else{
                alert(data.msg);
              }
            });
    }

    //忘记密码获取验证码
    function get_ps_randfunc()
    {
		if (!window.randPsStatus)
		{
			return;
		}

    	var mobile = $('.ps_mobile').val();
        if(!isNaN(mobile) && mobile.length == 11)
        {
            //var user_name = $('.ps_user_account').val();
            var ajaxurl = './index.php?ctl=Login&met=findPasswdCode&typ=json&mobile='+mobile;
            $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: "json",
                    async: false,
                    success: function (respone)
                    {
                        if(respone.status == 250)
                        {
                            alert(respone.msg);
                        }else
                        {
							window.countPsDown();
                            alert('请查看手机短信获取验证码!');
                        }
                        
                        console.info(respone);
                    }
                });
                
            $('.randfuc').html('重新获取验证码');
        }
        else
        {
            alert('请填写手机号!');
        }
    	
    }

    //重置密码
    function resetPasswdClick(){
    	var user_account = $('.ps_user_account').val();
    	var user_password = $('.ps_user_password').val();
    	var user_code = $('.ps_user_code').val();
    	var mobile = $('.ps_mobile').val();
    	/*alert(user_account);
    	alert(user_password);
    	alert(user_code);
    	alert(mobile);*/
        
        $.post("./index.php?ctl=Login&met=resetPasswd&typ=json",{"user_password":user_password,"user_code":user_code,"mobile":mobile} ,function(data) {
            console.info(data);
              if(data.status == 200)
              {
                alert('重置密码成功，请妥善保管新密码！');
                //window.location.reload();
                //window.history.go(-1);

              }else{
                alert(data.msg);
              }
            });

    }
</script>
</body>
</html>