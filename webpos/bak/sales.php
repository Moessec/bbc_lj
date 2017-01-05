<?php
	include_once("config.php");
	
	
	if($_GET['op']=='update'){
		
		$id = $_GET['id'];
		
		$sql = "
				SELECT
					id,
					inorder,
					buyer AS buId,
					sales_id AS salesId,
					total_qty AS totalQty,
					total_tax_amount AS totalTaxAmount,
					discount_amount AS disAmount,
					total_discount AS totalDiscount,
					total_tax AS totalTax,
					total_amount AS totalAmount,
					price AS amount,
					payment_method,
					discount_rate AS disRate,
					FROM_UNIXTIME(create_time, '%Y-%m-%d') AS date,
					FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%m:%s') AS modifyTime
				FROM
					mallbuilder_product_union_order
				WHERE id = $id
			";
		$db->query($sql);
		$res = $db->fetchRow();
		
		if(!empty($res))
		{	
			//获取客户名
			$sql ="select CONCAT(IFNULL(`real_name`,''),' ',IFNULL(`card_num`,`user`)) as user from mallbuilder_member where userid = $res[buId]";
			$db->query($sql);
			$buyer = $db->fetchField('user');
			$res['contactName'] = $buyer;
			
			$res['status'] = 'view';
			$res['checked'] = 1;
 
			$entries = Array();
			$temp = Array();
			
			$order = explode(",", $res['inorder']);
			foreach($order as $key=>$val)
			{
				//获取卖家ID
				$sql ="
					SELECT
						seller_id
					FROM
						`mallbuilder_product_order`
					WHERE
						order_id = '$val'
					AND buyer_id = ''
				";
				$db->query($sql);
				$seller_id = $db->fetchField('seller_id');
				
				//获取店铺名称
				$sql = "
					SELECT
						company
					FROM
						`mallbuilder_shop`
					WHERE
						userid = '$seller_id'
				";
				$db->query($sql);
				$shop = $db->fetchField('company');
				
				$sql = "
					SELECT
						`num` AS qty,
						`name` AS goods,
						`price`,
						`spec_value` AS skuName,
						discountRate,
						deduction,
						amount,
						taxRate,
						tax,
						taxAmount,
						'$shop' AS locationName,
						status AS `delete`,
						id
					FROM
						mallbuilder_product_order_pro
					WHERE
						order_id = '$val'";
				$db->query($sql);
				$temp = $db->getRows();
				$entries = array_merge($entries,$temp);
			}
		
			$data=$res;
			$data['entries'] = $entries;
		}
		
		$body_data_rows = array();
        $body_data_rows['status'] = 200;
		$body_data_rows['msg']    = 'success';
		$body_data_rows['data'] = $data;
        $pro_data_rows = array('cmd_id'=>1) + $body_data_rows;
		header('Content-type: application/json');
		echo json_encode($pro_data_rows);		
		die;
	}
	
	
	if($_GET['action']=='return'){
		include("../module/product/includes/plugin_refund_class.php");
		$refund = new refund();
		$id = $_REQUEST['id'];
		$paymentMethod = $_REQUEST['paymentMethod'];
		
		//获取该订单产品的信息
		$sql = "SELECT * FROM mallbuilder_product_order_pro WHERE id=$id";
		$db->query($sql);
		$re = $db->fetchRow();
		
		//卖家账户
		if($_SESSION['IDENTITY']!=1){
			$userid = $_SESSION['ADMIN_USER_ID'];
			$sql = "select pay_id,pay_email from pay_member where userid='$userid'";
			$db->query($sql);
			$re2 = $db->fetchRow();
		}else{
			$sql = "select pay_id,pay_email from pay_member where pay_email='admin@systerm.com'";
			$db->query($sql);
			$re2 = $db->fetchRow();
		}
		
		//获取卖家的id
		$sql = "SELECT member_id FROM mallbuilder_product WHERE id=$re[pid]";
		$db->query($sql);
		$seller_id = $db->fetchField('member_id');
		
		//申请退货，插入订单退货表		
		$T = time();
		$R = "R".$T;
		$sql="insert into mallbuilder_return (order_id,refund_id,product_id,seller_id,member_id,refund_price,create_time,reason,status,goods_status,type) values ('$re[order_id]','".$R."','$re[id]','$seller_id','$re[buyer_id]','$re[taxAmount]','".time()."','','5','1','2')";
		$db->query($sql);
		
		$msg = "买家于 ".date("Y-m-d H:i:s",$T)." 创建了退款申请。退款金额：$re[price]元";
		$refund->add_talk($R,$re['order_id'],$msg,'');
		
		if($paymentMethod==2)
		{			
			$commission = $re['commission'];
			//退货 更改状态
			$sql="update pay_cashflow set refund_amount = refund_amount + '$re[taxAmount]' , is_refund = 'true' where order_id='$re[order_id]'";
			$db->query($sql);
	
			//返还钱
			$sql="update pay_member set cash = cash + '$re[taxAmount]' where userid='$re[buyer_id]'";
			$db->query($sql);
			
			//卖家减钱
			$sql = "update pay_member set cash = cash - '$re[taxAmount]' where pay_id='$re2[pay_id]'";
			$db->query($sql);	
			
			if($_SESSION['IDENTITY']!=1&&$commission>0){
				//--------------写入流水账
				$post['type']=1;//直接到账
				$post['action']='add';//
				$post['buyer_email']='0';//
				$post['seller_email']=$re2[pay_email];//
				$post['order_id']='C'.time();//外部订单号
				$post['extra_param'] = 'Commission';
				$post['price'] = $commission;//订单总价，单价元
				$post['name1'] = '线下订单'.$re[order_id].'佣金退还';
				$post['name'] = '线下订单'.$re[order_id].'佣金退还';
				pay_get_url($post,true);//跳转至订单生成页面
			}
		}
		
		//更改订单产品状态
		$sql = "UPDATE mallbuilder_product_order_pro set `status`=5 where id=$id";
		$db->query($sql);
		
		
		$msg = "卖家于 ".date("Y-m-d H:i:s",time())." 同意退款申请。";
		$refund->add_talk($R,$re['order_id'],$msg,'');
		echo '{"status":200,"msg":"success"}';
		die;
	}
	$tpl->display('sales.htm');
?>