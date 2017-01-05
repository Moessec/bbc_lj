<?php
	include_once("config.php");
	if($_GET['action']=='detail'){
		
		$scl='';
		if($_GET['skey']){
			//会员信息查询
			$scl=" and a.`user` like '%$_GET[skey]%'";
		}
		if($_GET['beginDate']){			
			$stime =trim($_GET['beginDate']);
			$scl.=" and FROM_UNIXTIME(a.time, '%Y-%m-%d')>='$stime'";
		}
		
		if($_GET['endDate']){			
			$etime = trim($_GET['endDate']);
			$scl.=" and FROM_UNIXTIME(a.time, '%Y-%m-%d')<= '$etime'";
		}
		
		if($_SESSION['IDENTITY']!=1){
			//判断是否是管理员身份
			$userid = $_SESSION['ADMIN_USER_ID'];
			$name = $_SESSION['ADMIN_USER'];
			$scl.=" and c.member_id=$userid and c.member_name='$name' and order_id IN ( SELECT inorder FROM  `mallbuilder_product_union_order`  WHERE  sales_id =$userid AND sales_name = '$name')";
		}
		$sql ="
			SELECT
				a.`name`,
				a.`num`,
				a.`order_id`,
				a.`price`,
				a.`spec_value`,
				a.discountRate,
				a.deduction,
				a.amount,
				a.tax,
				a.taxRate,
				a.taxAmount,
				FROM_UNIXTIME(a.time, '%Y-%m-%d') AS createTime,
				a.`status`,
				IFNULL(b.`real_name`,b.`user`) AS buName
			FROM
				mallbuilder_product_order_pro a
			LEFT JOIN mallbuilder_member b ON a.buyer_id = b.userid
			LEFT JOIN mallbuilder_product c ON a.pid = c.id
			WHERE a.is_offline=1 $scl
			order by a.time desc
		";
		
		$db->query($sql);		
		$total = $db->num_rows();
		
		$sql = $sql.$limit;
		$db->query($sql);
		$res = $db->getRows();
		
		$body_data_rows = array();
        $body_data_rows['status'] = 200;
		$body_data_rows['msg']    = 'success';
		$body_data_rows['data']['rows']   = $res;
		$body_data_rows['data']['total'] = ceil($total/$_GET['rows']);
		$body_data_rows['data']['records'] =$total;
        $pro_data_rows = array('cmd_id'=>1) + $body_data_rows;
		header('Content-type: application/json');
		echo json_encode($pro_data_rows);		
		die;
	}
	
	if($_GET['met']){
		$now = date('Y-m-d',time());
		$met = trim($_GET['met']);
		switch($met){
			case "1":$begin = $now;break;
			case "2":$begin = date('Y-m-d',strtotime('-6 day'));break;
			case "3":$begin = date('Y-m-d',strtotime('-29 day'));break;
		}
		$tpl->assign("begin",$begin);
	}
	
	$tpl->display('order.htm');
?>