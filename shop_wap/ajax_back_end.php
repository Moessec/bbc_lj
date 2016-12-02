<?php


if(isset($_POST['shoplng']))
{
	$shoplng = $_POST['shoplng'];
	$shoplat = $_POST['shoplat'];
	$lat = $_COOKIE['lat'];
	$lng = $_COOKIE['lng'];
   function getDistance($lat1, $lng1, $lat2, $lng2)
   {      
          $earthRadius = 6378138; //近似地球半径米
          // 转换为弧度
          $lat1 = ($lat1 * pi()) / 180;
          $lng1 = ($lng1 * pi()) / 180;
          $lat2 = ($lat2 * pi()) / 180;
          $lng2 = ($lng2 * pi()) / 180;
          // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
          $calcLatitude = $lat2 - $lat1;
          $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  
       $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
          $calculatedDistance = $earthRadius * $stepTwo;
          return round($calculatedDistance);
   }
   echo getDistance($shoplat,$shoplng,$lat,$lng);
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