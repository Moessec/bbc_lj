<?php
@set_time_limit(0);
// include_once("includes/global.php");
/**
 * 获取某地址的经纬度
 * by http://www.jbxue.com
*/
function getLatLong($address){ 
    if (!is_string($address))die("All Addresses must be passed as a string"); 
    $_url = sprintf('http://api.map.baidu.com/geocoder?address='.$address.'&output=json&key=6eea93095ae93db2c77be9ac910ff311&city=上海市'); 
   
    
    
    $_result = false; 
    if($_result = file_get_contents($_url)) { 
        if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false; 
        preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match); 
        $_coords['lat'] = $_match[1]; 
        $_coords['long'] = $_match[2]; 
    } 
    return $_coords; 
}

if(isset($_POST['info']))
{
	$addr = $_POST['info'];
	$re = getLatLong($addr);
	var_dump($re);
}
// echo getLatLong('上海市闵行区');
// //关键字
// if(!empty($_POST['search_flag'])&&!empty($_POST['key']))
// {	
// 	include_once("includes/global.php");
// 	$sql="select * from ".SWORD." where keyword like '$_POST[key]%' or char_index like '$_POST[key]%' order by nums desc limit 0,10";
// 	$db->query($sql);
// 	$re=$db->getRows();
// 	foreach($re as $v)
// 	{
// 		echo "<a onclick=\"select_word('$v[keyword]')\" href='#'>$v[keyword]</a>";
// 	}
// 	die;
// }

// if($_POST['qd']==1&&is_numeric($_POST['id']))
// {
// 	$buid=$_POST['id'];
// 	include_once("module/member/includes/plugin_member_class.php");
// 	$member = new member();
// 	$num=$member->is_qd($buid);
// 	if($num==0)
// 	{
// 		$num=rand(1,5);
// 		$flag=$member->add_points($num,4,'',$buid);
// 	}
// 	die;
// }

// //验证问题是否正确
// if(isset($_POST["wtyz"])&&$_POST["wtyz"]=='1')
// {	
// 	include_once("includes/global.php");
// 	$sql="select * from ".REGVERFCODE." order by rand() limit 0,1";
// 	$db->query($sql);
// 	$re=$db->fetchRow();
// 	echo $re['question'];
// 	$_SESSION['YZWT']=$re['answer'];
// }
// //验证问题是否正确
// if(isset($_POST["ckyzwt"]))
// {	
// 	if($_POST["ckyzwt"]==$_SESSION['YZWT'])
// 		echo "true";
// 	else
// 		echo "false";
// }
// //验证码是否正确
// if(isset($_GET["yzm"]))
// {
// 	if(strtolower($_GET["yzm"])!=strtolower($_SESSION["auth"]))
// 	{
// 		echo 1;
// 	}
//     die;
// }
// if(isset($_POST["yzm"]))
// {
// 	if(strtolower($_POST["yzm"])!=strtolower($_SESSION["auth"]))
// 		echo "false";
// 	else
// 		echo "true";
// 	die;
// }

// if(isset($_POST["verify_field"])&&isset($_POST["verify_type"]))
// {	
// 	include_once("includes/global.php");
// 	include_once("config/reg_config.php");
// 	$config = array_merge($config,$reg_config);
	
// 	$verify_field = trim($_POST['verify_field']);
// 	$verify_type = trim($_POST['verify_type']);

// 	if($verify_type == 'user')
// 	{
// 		if($config['censoruser'])
// 		{
// 			$censoruser = explode("\r\n",$config['censoruser']);
// 			if(in_array($verify_field,$censoruser))
// 			{
// 				echo "false";
// 				die;
// 			}
// 		}
// 		if(!empty($config['openbbs'])&&$config['openbbs']==2)
// 		{
// 			include_once('uc_client/client.php');
// 			echo (uc_user_checkname($verify_field) == 1) ? "true" : "false";
// 			die;
// 		}
// 	}
// 	$sql = "select * from ".MEMBER." where $verify_type = '$verify_field'";
// 	$db -> query($sql);
// 	$num = $db->num_rows();
// 	echo ($num > 0) ? "false" : "true";
// 	die;
// }


// // 返回APP端用户登录信息

// if(isset($_GET['act']) && $_GET['act'] == "getUserInfo")
// {
// 	$buid = $buid ? $buid : 0;
// 	echo json_encode(array(session_id(),$buid ));exit;
// }

// if(isset($_POST["mobile"]))
// {
// 	$mobile = trim($_POST["mobile"]);
// 	$sql = "select message from ".MAILMOD." where `flag` ='register_mobile' and type=2 ";
// 	$db -> query($sql);
// 	$me = $db -> fetchField("message");

// 	//$serial = randomkeys();
// 	$serial = rand(1000,9999);
// 	$_SESSION['auth'] = $serial;
// 	$me = str_replace("[webname]", $config['company'], $me);
// 	$me = str_replace("[serial]", $serial, $me);

// 	$url = $config['weburl']."/api/msg.php?mobile=".$mobile."&me=".urlencode($me);
// 	$str = file_get_contents($url);
// 	echo  "true";
// }
// if(isset($_POST["email"]))
// {	
// 	include_once("config/seo_config.php");
// 	$config = array_merge($config,$config);
	
// 	$email = trim($_POST["email"]);

// 	$rand = create_password("4");
// 	$_SESSION['auth'] = $rand;
	
// 	$mail_temp = get_mail_template('active');

// 	$con = $mail_temp['message'];
// 	$title = $mail_temp['title'];
// 	$time = date("Y-m-d H:i:s");
// 	$user = "user";
// 	$logo='<img height="24" src="'.$config['weburl'].'/image/logo.gif"  style="border:none;margin:0;">';

// 	$ar1=array('[time]','[logo]','[weburl_name]','[link]','[weburl_email]','[weburl_tel]','[weburl_url]','[weburl_desc]','[weburl_desc]');
// 	$ar2=array($time,$logo,$config['company'],$rand,$config['email'],$config['owntel'],$config['weburl'],$config['description']);
// 	$con=str_replace($ar1,$ar2,$con);
	
// 	$ar3=array('[weburl_name]');
// 	$ar4=array($config['company']);
// 	$title=str_replace($ar3,$ar4,$title);
	
// 	send_mail($email,$user,$title,$con);
// 	unset($con);unset($title);
// 	die;
// }
// //地区联动
// if(isset($_POST["d_id"]))
// {	
// 	include_once("includes/global.php");
// 	$sql="select id,name from ".DISTRICT." where pid='$_POST[d_id]' order by sorting";
// 	$db->query($sql);
// 	$num=$db->num_rows();
// 	$str="";
// 	if($num!=0)
// 	{
// 		$str="{";
// 		$i=0;
// 		while($k=$db->fetchRow())
// 		{
// 			$i++;
// 			$id=$k["id"];
// 			$name=$k["name"];
// 			if($i<$num)
// 				$str.="'$i':{'0':'$id','1':'$name'},";
// 			else
// 				$str.="'$i':{'0':'$id','1':'$name'}";
// 		}
// 		//------------------------------------------------------------
// 		$str.="};";
// 	}
// 	echo $str;
// }

// wap端定位后同步到SESSION中
if(isset($_POST['act']) && $_POST['act'] == "reposition")
{
	$_SESSION["lng"] = $_POST['lng'];
	$_SESSION["lat"] = $_POST['lat'];
}

// 异步获取附近的店铺
if(isset($_POST['act']) && $_POST['act'] == "getNearInfo")
{
	if(!isset($_SESSION['lng']) || empty($_SESSION['lng']) || !isset($_SESSION['lat']) || empty($_SESSION['lat']))
	{
		echo 1;
	}
	else
	{
		$data = file_get_contents($config['weburl']."/?m=shop&s=list&action=ajax&x=".$_SESSION['lng']."&y=".$_SESSION['lat']."&limit=".$_POST['limit']);
		echo $data;
	}
}

// //产品商铺分类联动
// if(!empty($_POST["pcatid"]))
// {
// 	include_once("includes/global.php");
	
// 	$s=$_POST["pcatid"]."00";$b=$_POST["pcatid"]."99";
// 	if(!empty($_POST['cattype'])&&$_POST['cattype']=='pro')
// 		$db->query("SELECT * FROM ".PCAT." WHERE catid>'$s' and catid<'$b' ORDER BY nums ASC");
	
// 	if(!empty($_POST['cattype'])&&$_POST['cattype']=='com')
// 		$db->query("SELECT * FROM ".SHOPCAT." WHERE parent_id='$_POST[pcatid]' ORDER BY displayorder");
	
// 	$num=$db->num_rows();
// 	$str="{";
// 	$i=0;
// 	while($k=$db->fetchRow())
// 	{
// 		$i++;
// 		if($_POST['cattype']=='com')
// 		{
// 			$city_id=$k["id"];
// 			$cat=str_replace("\r",'',$k['name']);
// 		}
// 		else
// 		{
// 			$city_id=$k["catid"];
// 			$cat=str_replace("\r",'',$k['cat']);
// 		}
// 		if($i<$num)
// 			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"},";
// 		else
// 			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"}";
// 	}
// 	$str.="};";
// 	echo $str;
// }

// // 手机端 分类联动
// if(isset($_POST['act']) && $_POST['act'] == "showChildCat")
// {
// 	$catid = $_POST['catid'] * 1;
// 	$n = strlen($catid);
// 	$m = $n * 1 + 2;
// 	$sql = "SELECT `catid`,`cat` from ".PCAT." WHERE LEFT(`catid`,$n) = '$catid' and LENGTH(`catid`) = '$m' order by `nums` asc";
// 	$db -> query($sql);
// 	$re = $db -> getRows();

// 	foreach ($re as $key => $val)
// 	{
// 		$str .="<option value='".$val['catid']."'>".$val['cat']."</option>";
// 	}

// 	echo $str;
// }
?>