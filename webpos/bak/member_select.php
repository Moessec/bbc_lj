<?php
	include_once("config.php");

	//会员管理列表
	if($_GET['op']=='index')
	{	
		if($_GET['skey']){
			//会员信息查询
			$scl=" and a.`card_num` like '%$_GET[skey]%' or a.`mobile` like '$_GET[skey]%' or a.`identification` like '$_GET[skey]%' or a.`user` like '$_GET[skey]%'";
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
			$operator = $_SESSION['ADMIN_USER'];
			$id = $_SESSION['ADMIN_USER_ID'];
			//$sql = "";
			//$db->query($sql);
			//$array = $db->getRows();
			$scl.=" and (a.operator='$operator' or a.userid IN ( SELECT DISTINCT buyer_id FROM  `mallbuilder_product_order` WHERE  buyer_id > 0 AND userid = $id))";
		}

		$sql="
			SELECT
				a.userid AS member_id,
				a.userid AS id,
				a.`user` AS member_name,
				a.sex AS member_sex,
				a.mobile AS member_mobile,
				a.qq AS member_qq,
				a.ww As member_ww,
				IFNULL(a.`real_name`,a.`user`) AS member_realname,
				a.card_num AS member_cardnum,
				a.email AS member_email,
				a.regtime,
				a.identification,
				b.points AS member_points
			FROM
				mallbuilder_member a
			LEFT JOIN mallbuilder_member_info b ON a.userid = b.member_id
			WHERE
				a.`statu`=2  $scl
			ORDER BY
				a.lastLoginTime  
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
	
	//获取当前会员的信息
	if($_GET['met']=='get')
	{	
		$member_id = $_REQUEST['buid'];
		$sql="
			SELECT
				a.userid AS member_id,
				a.userid AS id,
				a.`user` AS member_name,
				a.sex AS member_sex,
				a.mobile AS member_mobile,
				a.qq AS member_qq,
				a.ww As member_ww,
				IFNULL(a.`real_name`,a.`user`) AS member_realname,
				a.card_num AS member_cardnum,
				a.regtime,
				a.email AS member_email,
				b.points AS member_points
			FROM
				mallbuilder_member a
			LEFT JOIN mallbuilder_member_info b ON a.userid = b.member_id
			WHERE
				a.userid = $member_id
		";
		$db->query($sql);
		$res = $db->fetchRow();
		
		$sql = "select cash from pay_member where userid=$member_id";
		$db->query($sql);
		$cash = $db->fetchField('cash');
		
		$result['status']=200;
		$result['msg']='success';
		$data['empId']=$res['member_id'];
		$data['cash']=$cash;
		$result['data'] = $data;
		header('Content-type: application/json');
		echo json_encode($result);
		die;
	}
	
	if($_GET['action']=='getCustomer'){
		
		//获取会员信息
		
		$skey = trim($_REQUEST['skey']);
		
		$sql ="
			SELECT
				userid AS member_id,
				userid AS id,
				CONCAT(`real_name`,' ',card_num) AS member_name
			FROM
				mallbuilder_member 
			WHERE 
				`user` LIKE '$skey%'
				or `real_name` LIKE '$skey%'
			AND `statu`=2
		";
		//{"status":200,"msg":"success","data":{"contactName":"四捷科技应用材料","buId":1294592492204,"cLevel":0}}
		$db->query($sql);
		$res = $db->fetchRow();
		if(!empty($skey)){
			if(!empty($res)){
				$result['status']=200;
			}else{
				$result['status']=100;
			}
		}else{
			$result['status']=300;
		}
		$result['msg']='success';
		$data['contactName']=$res['member_name'];
		$data['buId']=$res['member_id'];
		$result['data'] = $data;
		header('Content-type: application/json');
		echo json_encode($result);
		die;
	}
  
	$tpl->display('member_select.htm');
?>