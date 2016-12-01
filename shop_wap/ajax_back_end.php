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
	// echo $addr;
	// $re = getLatLong($addr);
	echo $return = file_get_contents('api.map.baidu.com/geocoder/v2/?address='.$addr.'&output=json&ak=E4805d16520de693a3fe707cdc96204');
}




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