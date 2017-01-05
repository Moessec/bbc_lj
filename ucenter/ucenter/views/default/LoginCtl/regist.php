<html class="root61">
<?php
$re_url = '';
$re_url = Yf_Registry::get('re_url');

$from = 'mall';
$callback = $re_url;
$t = '';
$code = '';

extract($_GET);
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="msapplication-tap-highlight" content="no" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<title>个人注册</title>
	<link rel="stylesheet" href="<?=$this->view->css?>/register.css">
	<link rel="stylesheet" href="<?=$this->view->css?>/base.css">
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css" />
	<script src="<?=$this->view->js?>/jquery-1.9.1.js"></script>
	<script src="<?=$this->view->js?>/respond.js"></script>
</head>

<body>
<div id="form-header" class="header">
    <div class="logo-con w clearfix">
        <a href="#" class="index_logo">
         <img src="<?= $web['site_logo'] ?>" alt="logo" height="60">
        </a>
        <div class="logo-title">欢 迎 注 册</div>

        <div class="have-account">已有账号 <a href="<?=sprintf('%s?ctl=Login&met=index&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" target="_blank">请登录</a></div>
    </div>

</div>
	<div class="container w">
		<div id="header">欢迎注册</div>
		<div class="main clearfix" id="form-main">
			<div class="reg-form fl">
				<form action="" id="register-form" method="post" novalidate="novalidate" onsubmit="return false;">

					<input type="hidden" name="from" class="from" value="<?php echo $from;?>">
					<input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback);?>">
					<input type="hidden" name="t" class="t" value="<?php echo $t;?>">
					<input type="hidden" name="code" class="code" value="<?php echo $code;?>">
					<input type="hidden" name="re_url" class="re_url" value="<?php echo $re_url;?>">


					<!--chrome autocomplete off bug hack-->
					<input style="display:none" name="hack">
					<input type="password" style="display:none" name="hack1">

					<div class="form-item form-item-account disb" id="form-item-account">
						<label>用　户　名</label>

						<input type="text" id="re_user_account"  class="field re_user_account" autocomplete="off" maxlength="20" placeholder="您的账户名和登录名" default="<i class=&quot;i-def&quot;></i>支持中文、字母、数字、“-”“_”的组合，至多20个字符" onfocus="checkAcount()" onblur="userCallback()">
						<i class="i-status"></i>
					</div>
					<div class="input-tip">
						<span></span>
					</div>
					<div id="form-item-password" class="form-item" style="z-index: 12;">
						<label>设 置 密 码</label>
						<input type="password" id="re_user_password" class="field re_user_password" maxlength="20" placeholder="请设置密码" default="<i class=i-def></i><?=$pwd_str?>" onfocus="checkPwd()" onblur="pwdCallback()">
						<i class="i-status"></i>
					</div>
					<div class="input-tip">
						<span></span>
					</div>
					<div id="form-item-rpassword" class="form-item disb">
						<label>确 认 密 码</label>
						<input type="password" name="form-equalTopwd" id="form-equalTopwd" class="field" placeholder="请再次输入密码" maxlength="20" default="<i class=&quot;i-def&quot;></i>请再次输入密码" onblur="checkRpwd()" onfocus="showTip(this)">
						<i class="i-status"></i>
					</div>
					<div class="input-tip disb">
						<span></span>
					</div>
					<div class="item-phone-wrap">
						<div class="form-item form-item-mobile" id="form-item-mobile">
							<label class="select-country" id="select-country">手 机 号 码
							</label>

							<input type="text" id="re_user_mobile"  class="field re_user_mobile" placeholder="请输入手机号" maxlength="11" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该手机登录和找回密码" onblur="checkMobile()" onfocus="showTip(this)">
							<i class="i-status"></i>
						</div>
						<div class="input-tip">
							<span></span>
						</div>
					</div>
					
					<div class="form-item form-item-authcode" id="form-item-authcode">
						<label>验　证　码</label>
						<input type="text" autocomplete="off" name="authcode" id="form-authcode" maxlength="6" class="field form-authcode" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码" onfocus="showTip(this)" onblur="checkCode()">
						<img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php'/>
					</div>
					<div class="input-tip ">
						<span></span>
					</div>
					<div class="form-item form-item-phonecode">
						<label>手机验证码</label>

						<input type="text" name="mobileCode" maxlength="6" id="phoneCode" class="field phonecode  re_mobile" placeholder="请输入手机验证码" autocomplete="off">
						<button id="getPhoneCode" class="btn-phonecode" type="button" onclick="get_randcode()">获取验证码</button>
						<i class="i-status"></i>
					</div>
					<div class="input-tip">
						<span></span>
					</div>
					<div class="form-agreen">
						<!--<div>
							<input type="checkbox" name="agreen" checked="">我已阅读并同意<a href="javascript:;" id="protocol">《用户注册协议》</a> </div>-->
						<div class="input-tip">
							<span></span>
						</div>
					</div>
					<div>
						<button type="submit" class="btn-register" onclick="registclick()">立即注册</button>
					</div>

				</form>
			</div>
			<div id="form-company" class="reg-other disb">
				<div class="phone-fast-reg">
				广告位
				</div>
			</div>
		</div>
		<?php
		include $this->view->getTplPath() . '/' . 'footer.php';
		?>
	</div>

	<script>

		var pwdLength = <?=$reg_row['reg_pwdlength']['config_value']?>


		var form_account = $("#re_user_account");
		var form_pwd = $("#re_user_password");
		var form_rpwd = $("#form-equalTopwd");
		var form_mobile = $("#re_user_mobile");
		var form_authcode = $("#form-authcode");

		suggestsList = {};
		function get_randfunc(obj)
		{
			var sj = new Date();
			url = obj.src;
			obj.src = url + '?' + sj;
		}
		var icons = {
			def: '<i class="i-def"></i>',
			error: '<i class="i-error"></i>',
			weak: '<i class="i-pwd-weak"></i>',
			medium: '<i class="i-pwd-medium"></i>',
			strong: '<i class="i-pwd-strong"></i>'
		};

		var pwdStrength = {
			1: {
				reg: /^.*([\W_])+.*$/i,
				msg: icons.weak + '有被盗风险,建议使用字母、数字和符号两种及以上组合'
			},
			2: {
				reg: /^.*([a-zA-Z])+.*$/i,
				msg: icons.medium + '安全强度适中，可以使用三种以上的组合来提高安全强度'
			},
			3: {
				reg: /^.*([0-9])+.*$/i,
				msg: icons.strong + '你的密码很安全'
			}
		};

		var weakPwds = [
		];

		function filterKey(e) {
			var excludedKeys = [13, 9, 16, 17, 18, 20, 35, 36, 37, 38,
				39,
				40, 45, 144, 225
			];
			return $.inArray(e.keyCode, excludedKeys) !== -1;
		}

		function hideError(input, msg) {
			var item = input.parent();
			var msg = msg || input.attr('default');
			item.removeClass('form-item-error form-item-valid');
			item.next().find('span').removeClass('error').html(msg).show();
			item.next().removeClass('phone-bind-tip');
			item.removeClass('phone-binded');
		}

		/**输入过程中处理标签的状态**/
		function onKeyupHandler(input, msg) {
			var item = input.parent();
			if (!item.hasClass('form-item-error')) {
				item.addClass('form-item-error')
			}
			item.removeClass('form-item-valid');
			item.next().find('span').addClass('error').html(msg).show();
		}

		//显示提示语
		function showTip(e)
		{
			var msg = $(e).attr('default');

			if(!$(e).parent().next().find("span").html())
			{
				$(e).parent().next().find("span").html(msg);
			}


		}

		function getStringLength(str){
			if(!str){
				return;
			}
			var bytesCount=0;
			for (var i = 0; i < str.length; i++)
			{
				var c = str.charAt(i);
				if (/^[\u0000-\u00ff]$/.test(c))
				{
					bytesCount += 1;
				}
				else
				{
					bytesCount += 2;
				}
			}
			return bytesCount;
		}

		//验证用户名
		function checkAcount() {
			var msg = form_account.attr('default');

			var s = form_account.parent().next().find("span").html();
			if(!s)
			{
				form_account.parent().next().find("span").html(msg);
			}

			//输入过程中需要
			//判断是否有特殊字符
			//关闭重名提醒提示面板
			var item = form_account.parent();
			var reg = /^[A-Za-z0-9_\-\u4e00-\u9fa5]+$/;
			var errormsg = icons.error +
				'格式错误，仅支持汉字、字母、数字、“-”“_”的组合';
			form_account.on('keyup', function (e) {
				if (filterKey(e)) {
					return;
				}
				var value = $(this).val();

				hideError(form_account);
				if (value != '' && !reg.test(value)) {
					onKeyupHandler(form_account, errormsg);
				}
				//如果提示面板存在则隐藏
				if (suggestsList['username']) {
					suggestsList['username'].hide();
				}
			})
			$("#form-phone,#form-email").on('keyup', function (e) {
				if (filterKey(e)) {
					return;
				}
				var value = $(this).val();
				if(value==''){
					hideError($(e.target));
				}
			})
		}

		/*
		 * 用户名验证错误回调
		 * @element  input元素
		 * @repsonse 服务器返回的数据
		 */
		function userCallback() {
			var user_account = $("#re_user_account").val();
			hideError(form_account);
			if(user_account)
			{
				$("#form-item-account").addClass("pending");

				$.post("./index.php?ctl=Login&met=getUserByName&typ=json",{"user_name":user_account} ,function(data) {
					console.info(data);
					if(data.status == 250)
					{
						$("#form-item-account").addClass("form-item-valid");
						$("#form-item-account").next().find("span").html("");
						$("#form-item-account").removeClass("pending");
					}else{
						$("#form-item-account").removeClass("pending");
						var errormsg = icons.error +
							'该用户名已被使用，请重新输入';
						onKeyupHandler(form_account, errormsg);
					}
				});
			}
			else
			{
				$("#form-item-account").removeClass("pending");
				$("#form-item-account").next().find("span").html("");
			}
		}


		//检测密码
		function checkPwd() {
			var msg = form_pwd.attr('default');

			var s = form_pwd.parent().next().find("span").html();
			if(!s)
			{
				form_pwd.parent().next().find("span").html(msg);
			}

			form_pwd.on('keyup', function (e) {
				var value = $(this).val();
				pwdStrengthRule(form_pwd, value);
			})
		}

		function pwdStrengthRule(element, value) {
			var level = 0;
			var typeCount=0;
			var flag = true;
			var valueLength=getStringLength(value);
			if (valueLength < pwdLength) {
				element.parent().removeClass('form-item-valid').removeClass(
					'form-item-error');
				element.parent().next().find('span').removeClass('error').html(
					$(element).attr('default'));
				return;
			}

			for (key in pwdStrength) {
				if (pwdStrength[key].reg.test(value)) {
					typeCount++;
				}
			}
			if(typeCount==1){
				if(valueLength>10){
					level=2;
				}else{
					level=1;
				}
			}else if(typeCount==2){
				if(valueLength<11&&valueLength>5){
					level=2;
				}
				if(valueLength>10){
					level=3;
				}
			}else if(typeCount==3){
				if(valueLength>6){
					level=3;
				}
			}

			if ($.inArray(value, weakPwds) !== -1) {
				flag = false;
				level=1;
			}

			if (flag && level > 0) {
				element.parent().removeClass('form-item-error').addClass(
					'form-item-valid');
			} else {
				element.parent().addClass('form-item-error').removeClass(
					'form-item-valid');
			}
			if (pwdStrength[level] !== undefined) {
				pwdStrength[level]>3?pwdStrength[level]=3:pwdStrength[level];
				element.parent().next().html('<span>' + pwdStrength[level].msg +
					'</span>')
			}
			return flag;
		}


		function pwdCallback()
		{
			var user_pwd = $("#re_user_password").val();
			hideError(form_pwd);

			if(user_pwd)
			{
				var flag = true;

				//必须包含数字
				if(<?=$reg_row['reg_number']['config_value']?>)
				{
					if (/[0-9]+/.test(user_pwd))
					{
						flag = flag && true;
					}
					else
					{
						flag = flag && false;
					}
				}

				//必须小写字母
				if(<?=$reg_row['reg_lowercase']['config_value']?>)
				{
					if (/[a-z]+/.test(user_pwd))
					{
						flag = flag && true;
					}
					else
					{
						flag = flag && false;
					}
				}

				//必须大写字母
				if(<?=$reg_row['reg_uppercase']['config_value']?>)
				{
					if (/[A-Z]+/.test(user_pwd))
					{
						flag = flag && true;
					}
					else
					{
						flag = flag && false;
					}
				}

				//必须字符
				if(<?=$reg_row['reg_symbols']['config_value']?>)
				{
					if (/[ !@#$%^&*()_+<>]+/.test(user_pwd))
					{
						flag = flag && true;
					}
					else
					{
						flag = flag && false;
					}
				}

				if(<?=$reg_row['reg_pwdlength']['config_value']?>)
				{
					if (user_pwd.length >= <?=$reg_row['reg_pwdlength']['config_value']?>)
					{
						flag = flag && true;
					}
					else
					{
						flag = flag && false;
					}
				}

				if(flag)
				{
					$("#form-item-password").addClass("form-item-valid");
					$("#form-item-password").next().find("span").html("");
					$("#form-item-password").removeClass("pending");
				}else
				{
					$("#form-item-password").removeClass("pending");
					var errormsg = icons.error + "<?=$pwd_str?>";
					onKeyupHandler(form_pwd, errormsg);
				}
			}
			else
			{
				$("#form-item-password").removeClass("pending");
				$("#form-item-password").next().find("span").html("");
			}
		}

		function checkRpwd()
		{
			var rpwd = $("#form-equalTopwd").val();
			var pwd = $("#re_user_password").val();

			hideError(form_rpwd);

			if(rpwd)
			{
				if(rpwd == pwd)
				{
					$("#form-item-rpassword").addClass("form-item-valid");
					$("#form-item-rpassword").next().find("span").html("");
				}
				else
				{
					$("#form-item-rpassword").removeClass("form-item-valid");
					var errormsg = icons.error + '两次密码输入不一致';
					onKeyupHandler(form_rpwd, errormsg);
				}
			}
			else
			{
				$("#form-item-rpassword").next().find("span").html("");
			}

		}

		//验证手机
		function checkMobile()
		{
			hideError(form_mobile);

			var mobile = $("#re_user_mobile").val();
			var result = false;

			if(mobile)
			{
				//先匹配是否为手机号
				if(!isNaN(mobile) && mobile.length == 11)
				{
					//验证该手机号是否被注册过
					var ajaxurl = './index.php?ctl=Login&met=checkMobile&typ=json&mobile='+mobile;
					$.ajax({
						type: "POST",
						url: ajaxurl,
						dataType: "json",
						async: false,
						success: function (respone)
						{
							if(respone.status == 250)
							{
								var errormsg = icons.error + '该手机号已被注册';
								onKeyupHandler(form_mobile, errormsg);

								result = false;
							}
							else
							{
								$("#form-item-mobile").addClass("form-item-valid");
								$("#form-item-mobile").next().find("span").html("");
								result = true;
							}
						}
					});
				}else
				{
					var errormsg = icons.error + '请输入正确的手机号';
					onKeyupHandler(form_mobile, errormsg);
					result = false;
				}
			}
			else
			{
				$("#form-item-mobile").next().find("span").html("");
			}

			return result;

		}

		function checkCode()
		{
			hideError(form_authcode);
			$("#form-item-authcode").next().find("span").html("");
		}

		function codeCallback()
		{
			var result = false;
			if($("#form-authcode").val())
			{
				var ajaxurl = './index.php?ctl=Login&met=checkCode&typ=json&yzm='+$("#form-authcode").val();
				$.ajax({
					type: "POST",
					url: ajaxurl,
					dataType: "json",
					async: false,
					success: function (respone)
					{
						if(respone.status == 250)
						{
							$("#form-authcode").val("");
							$(".img-code").click();
							var errormsg = icons.error + '验证不正确或已过期';
							onKeyupHandler(form_authcode, errormsg);
							result = false;
						}
						else
						{
							result = true;
						}
					}
				});
			}
			else
			{
				var errormsg = icons.error + '请输入图片验证码';
				onKeyupHandler(form_authcode, errormsg);
				result = false;
			}

			return result;
		}

			//获取注册验证码
		    function get_randcode(){
				//手机号码
				var mobile = $("#re_user_mobile").val();
				if(mobile)
				{
					if(!checkMobile())
					{
						return;
					}
				}else
				{
					var errormsg = icons.error + '请填写手机号';
					onKeyupHandler(form_mobile, errormsg);
					return;
				}
				//验证码
				if(!codeCallback())
				{
					return;
				}

		 		if (!window.randStatus)
				{
					return;
				}

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
								var errormsg = icons.error + '该手机号已被注册';
								onKeyupHandler(form_mobile, errormsg);
							}
							else
							{
								window.countDown();
								alert('请查看手机短信获取验证码!');
							}

							console.info(respone);
						}
					});

				$('.btn-phonecode').html('重新获取验证码');

		    }

			msg = "<?=_('获取验证码')?>";
			var delayTime = 60;
			window.randStatus = true;
			window.countDown = function ()
			{
				window.randStatus = false;
				delayTime--;
				$('.btn-phonecode').html(delayTime + "<?=_(' 秒后重新获取')?>");
				if (delayTime == 0) {
					delayTime = 60;
					$('.btn-phonecode').html(msg);

					clearTimeout(t);

					window.randStatus = true;
				}
				else
				{
					t=setTimeout(countDown, 1000);
				}
			}

			$("#register-form").keydown(function(e){
				var e = e || event,
					keycode = e.which || e.keyCode;

				if(keycode == 13)
				{
					registclick();
				}
			});

			$from = $(".from").val();
			$callback = $(".callback").val();
			$t = $(".t").val();
			$re_url = $(".re_url").val();

		    //注册按钮
		    function registclick(){

				var user_account = $('#re_user_account').val();
				if($(".disb").css("display")=='none' && !user_account)
				{
					var user_account = $('#re_user_mobile').val();
				}

		    	var user_password = $('#re_user_password').val();
		    	var user_code = $("#phoneCode").val();
		    	var mobile = $("#re_user_mobile").val();

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
		              }else{
						  $("#form-authcode").val("");
						  $(".img-code").click();
						  alert(data.msg);
		              }
		            });
		    }

	</script>
</body>

</html>