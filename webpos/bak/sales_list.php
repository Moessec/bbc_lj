<?php
	include_once("config.php");
 
	if($_GET['op']=='index')
	{	
		$scl='';
		if($_GET['matchCon']){
			//订单号模糊查询
			$scl=" and a.`order_id` like '$_GET[matchCon]%'";
		}
		
		if($_GET['beginDate']){
			//开始日期
			$stime =trim($_GET['beginDate']);
			$scl.=" and FROM_UNIXTIME(a.create_time, '%Y-%m-%d')>='$stime'";
		}
		
		if($_GET['endDate']){	
			//结束日期
			$etime = trim($_GET['endDate']);
			$scl.=" and FROM_UNIXTIME(a.create_time, '%Y-%m-%d')<= '$etime'";
		}
		
		if($_GET['rows']){
			//分页
			$rows = $_GET['rows'];
			$page = $_GET['page'];
			$begin = ($page-1)*$rows;
			$end = $page*$rows;
			$limit = " limit $begin,$end";
		}
		
		if($_SESSION['IDENTITY']!=1){
			//判断是否是管理员身份
			$userid = $_SESSION['ADMIN_USER_ID'];
			$name = $_SESSION['ADMIN_USER'];
			$scl.=" and a.sales_id=$userid and a.sales_name='$name'";
		}
		
		//CONCAT(b.`user`,'  ',IFNULL(b.`name`,''),'  ',IFNULL(b.`mobile`,''))
		$sql="
			SELECT
				a.id,
				a.order_id,
				FROM_UNIXTIME(a.create_time, '%Y-%m-%d') AS create_time,
				a.total_amount AS totalAmount,
				a.total_qty,
				a.price,
				a.total_tax_amount,
				a.total_tax,
				a.discount_rate,
				a.discount_amount,
				a.total_discount,
				a.des,
				a.inorder,
				IFNULL(b.`real_name`,b.`user`) AS contactName,
				a.sales_name AS salesName
			FROM
				mallbuilder_product_union_order a
			LEFT JOIN mallbuilder_member b ON a.buyer = b.userid
			WHERE
				is_offline = 1 $scl
			ORDER BY a.`create_time` desc
		";
		$db->query($sql);		
		$total = $db->num_rows();
		
		$sql = $sql.$limit;
		$db->query($sql);
		$res = $db->getRows();
		
		//add by windfnn 2016-01-06
		foreach($res as $key=>$val){
			//获取订单ID
			$order = explode(",", $val['inorder']);
			foreach($order as $k=>$v){
				$sql = "select * from `mallbuilder_product_order_pro` where `order_id` = '$v' and `status`=5";
				$db->query($sql);
				$temp = $db->getRows();
				if(!empty($temp)){
					$res[$key]['return']=1;
					continue;
				}
			}
		}
		//end add
		
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
 
	$tpl->display('sales_list.htm');
?>