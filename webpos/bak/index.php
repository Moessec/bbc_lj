<?php
include_once("../includes/global.php");
//================================================
if(!empty($_POST["user"]))
{	
	//验证码输入不正确
	if(strtolower($_SESSION["auth"])!=strtolower($_POST["yzm"]))
	{
		msg("index.php?type=2");
		exit();
	}
	else
	{
		$user=trim($_POST["user"]);
		$ps=md5(trim($_POST["password"]));
		$identity = trim($_POST['identity']);
		if($identity==1){
			$sql="
				SELECT
				   a.province,a.city,a.area,a.street,a.type,a.lang,b.group_perms,a.id,a.logonums
				FROM
				  ".ADMIN." a left join ".GROUP." b on a.group_id=b.group_id
				WHERE
					a.user='$user' AND a.password='$ps' AND type=1";
		}else{
			$sql="
				SELECT
					a.userid AS id,
					b.userid
				FROM
					mallbuilder_member a
				LEFT JOIN mallbuilder_shop b ON a.userid = b.userid
				WHERE
					b.userid != '' AND a.user='$user' AND a.password='$ps'
			";
		}
		$db->query($sql);
		$re=$db->fetchRow();
		if($re["id"])
		{	
			if($identity==1){
				$_SESSION["ADMIN_USER_ID"]=$re['id']; 
				$_SESSION["ADMIN_USER"]=$user; 
				$_SESSION["ADMIN_PASSWORD"]=$ps;
				$_SESSION['IDENTITY']=1;
				$_SESSION["ADMIN_TYPE"]=$re['type'];//是否管理员
				$_SESSION["ADMIN_LANG"]=$re['lang']==''?$config['language']:$re['lang']; 
				if(!empty($re["province"]))
					$_SESSION["province"]=$re["province"];
				else
					$_SESSION["province"]=NULL;
				
				if(!empty($re["city"]))
					$_SESSION["city"]=$re["city"];
				else
					$_SESSION["city"]=NULL;
				
				if(!empty($re["area"]))
					$_SESSION["area"]=$re["area"];
				else
					$_SESSION["area"]=NULL;
				
				if(!empty($re["street"]))
					$_SESSION["street"]=$re["street"];
				else
					$_SESSION["street"]=NULL;
					
				$sql="update ".ADMIN." set logonums=logonums+1 where user='".$user."'";
				$db->query($sql);

				if($re['logonums'] == 0)
				{
					$time = time();
					@set_time_limit(0);	
					$dir="../install/data/";
					$files = scandir($dir);
					foreach($files as $val)
					{
						if($val!="." and $val!="..")
						{
							$fp=fopen($dir.$val,"r");
							if($val == "district.sql")
							{
								$sql=fread($fp,filesize($dir.$val));
								fclose($fp);
								$ar=explode(";",$sql);
								foreach($ar as $k=>$ve)
								{
									if($k<(count($ar)-1))
									{
										$ve=str_replace("mallbuilder_",$config['table_pre'],$ve);
									
										$qre=$db->query($ve);
									}
								}
							}
							else
							{
								while(!feof($fp))
								{
									$sql=fgets($fp);
									if($sql)
									{
										$ve=str_replace("mallbuilder_",$config['table_pre'],$sql);
										$ve=str_replace("[WEBURL]",$config['weburl'],$ve);
										$ve=str_replace("[TIME]",$time,$ve);
										$ve=str_replace("[DATE]",date("Y-m-d H:i:s",$time),$ve);
										$qre=$db->query($ve);
									}
								}
								fclose($fp);
							}
						}
					}
				}
			}else{
				$_SESSION["ADMIN_USER_ID"]=$re['id']; 
				$_SESSION["ADMIN_USER"]=$user; 
				$_SESSION["ADMIN_PASSWORD"]=$ps;
				$_SESSION['IDENTITY']=2;
			}
			header("location:main.php");
			exit();
		}
		else
		{	
			//用户名或密码错误
			header("location:index.php?type=1");
			exit();
		}
	}
}
if(!empty($_GET["action"]))
{
	if($_GET["action"]=="logout")
	{
		$_SESSION["ADMIN_USER_ID"]="";
		$_SESSION["ADMIN_USER"]="";
		$_SESSION["ADMIN_PASSWORD"]="";
		$_SESSION["ADMIN_TYPE"]="";
		$_SESSION["ADMIN_LANG"] = '';
		unset($_SESSION["ADMIN_USER_id"]);
		unset($_SESSION["ADMIN_TYPE"]);
		unset($_SESSION["ADMIN_LANG"]);
		unset($_SESSION["ADMIN_USER"]);
		unset($_SESSION["ADMIN_PASSWORD"]);
		unset($_SESSION["province"]);
		unset($_SESSION["city"]);
		unset($_SESSION["area"]);
		msg("index.php");
		exit();
	}
}
//===============================
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
include_once("../lang/".$config['language']."/admin.php");

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>登录 - 线下下单系统 </title>
		<link href="css/login.css" media="screen" rel="stylesheet" type="text/css">
        <!-- Scripts -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	</head>
       
	   <style type="text/css">
        body {
        	background:#153F85;
        	width: 100%;
        	z-index: -10;
        	padding: 0;
        }
        </style>		
		<body>
			<div class="login-layout">
				<div class="fl login-topics">
					<h2 class="shadow">勇于开始,才能找到成功的路</h2>
					<p class="shadow">开心快乐每一天</p>
				</div>

				<div class="fr login-area">
					<div class="top">
						<!--<h5 class="shadow">线下下单系统<em></em></h5>-->
						<h2 class="shadow">用户登录</h2>
						<!-- <h6>请立即注册 或是 找回密码？</h6>-->
					</div>
					<div class="box">						
						<form method="post" action="index.php">
							<?php if(isset($_GET["type"])){ ?>
							<ul class="error">
								<li>
								<?php
									if(isset($_GET["type"]))
									{
									 if($_GET["type"]==1)
										echo lang_show('error_user');
									}
								?>
								<?php
								if(isset($_GET["type"]))
								{
								 if($_GET["type"]==2)
									echo lang_show('error_verfiy');
								}
								?>
								</li>
							</ul>
							<?php } ?>
							
							<span class="identity">
								<strong>身份</strong> 
								<input type="radio" name="identity" value="1" checked>平台&nbsp;&nbsp;
								<input type="radio" name="identity" value="2">商家
							</span>
							
							<span>
								<label for="user_name">帐号</label> 
								<input type="text" name="user"  autocomplete="off" class="input-text text" tabindex="1" value="<?php if(isset($_POST["user"])) echo $_POST["user"];?>">
							</span>
								
							<span>
								<label for="password">密码</label>
								<input type="password"  autocomplete="off" name="password" class="input-password text" tabindex="2">			
							</span>
										
							<span>
								<input type="text" name="yzm" class="input-code text3" autocomplete="off" title="验证码为4个字符" 
										   maxlength="4" placeholder="输入验证码" id="captcha-input" tabindex="3">
								<div class="code" style="display: block;">
									<div id="captcha" class="code-img">
										<img onClick="get_randfunc(this);" style="cursor:pointer;" src='../includes/rand_func.php'/>
									</div>
								</div>
							</span>
								
							<span>
								<input type="submit" value="登录" class="input-button" name="">
							</span>
						</form>
						<!-- <span>
							<a class="ml15 shadow" href="/forget-password/">忘记密码？</a>
							<a class="ml5 shadow" href="/register/">新用户注册</a>
						</span> -->            
					</div>
				</div>
			</div>

			<div class="bottom">
				<h5>Powered by <span class="vol"><font class="b"><a target="_blank" href="http://www.mall-builder.com">mall-builder.com</a></font></span></h5>
				<h6 title="上海远丰信息科技有限责任公司">&copy; 2007-2014 <a target="_blank" href="http://www.yuanfeng021.com/">Shanghai Yuanfeng Networking Inc.</a></h6>
			</div>
		</body>
</html>