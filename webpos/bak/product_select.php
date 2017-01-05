<?php
	
	include_once("config.php");

	//产品管理列表
	if($_GET['op']=='index')
	{	
		$scl='';
		if(!empty($_GET['skey'])){
			//会员信息查询
			$scl=" and CONCAT(a.`code`,IFNULL(b.sku,'')) like '%$_GET[skey]%' or a.`name` like '$_GET[skey]%'";
		}
		if(!empty($_GET['assistId'])){
			$scl.=" and a.catid=$_GET[assistId]";
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
			$userid = $_SESSION['ADMIN_USER_ID'];
			$scl.=" and a.member_id=$userid";
		}
		
		$sql ="
			SELECT
				a.`name` AS goodsName,
				a.pic,
				a.id AS pid,
				a.catid AS pcatid,
				a.`name` AS goods_name,
				a.pic AS goods_pic,
				IFNULL(b.stock,a.stock) AS goods_count,
				IFNULL(b.price,a.price) AS salePrice,
				concat(a.id,'+',IFNULL(b.id,0)) AS goods_id,
				b.setmeal AS spec,
				b.setmeal AS skuName,
				b.spec_name AS specName,
				b.id AS skuId,
				CONCAT(a.`code`,IFNULL(b.sku,'')) AS goods_number,
				c.company AS locationName,
				c.userid AS locationId
			FROM
				mallbuilder_product a
			LEFT JOIN mallbuilder_product_setmeal b ON a.id = b.pid
			LEFT JOIN mallbuilder_shop c ON a.member_id = c.userid
			WHERE
				1 $scl
		";
		
		
		$db->query($sql);		
		$total = $db->num_rows();
		
		$sql = $sql.$limit;
		$db->query($sql);
		$res = $db->getRows();
		
		$body_data_rows = array();
        $body_data_rows['status'] = 200;
		$body_data_rows['msg']    = 'success';
		$body_data_rows['data']['items']   = $res;
		$body_data_rows['data']['total'] = ceil($total/$_GET['rows']);
		$body_data_rows['data']['records'] =$total;
        $pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
		header('Content-type: application/json');
		echo json_encode($pro_data_rows);		
		die;		 
	}
	
	//生成销售单号
	if($_GET['met']=='generateNo'){
		$arr['status'] = 200;
		$arr['msg'] = 'success';
		$arr['data']['billNo'] = "XS".$_REQUEST['billDate'];
		echo json_encode($arr);
		die;
	}
	
	//保存数据，生成订单
	if($_GET['met']=='add'){
		
		$datas = json_decode(stripslashes($_REQUEST['postData']),true);
		/* $sql = "select * from mallbuilder_member where userid = $datas[buId]";
		$db->query($sql);
		$res = $db->fetchRow(); */

		$buid = $datas['buId'];//买家ID
		$salesId = $datas['salesId'];//销售ID
		
		$totalQty = $datas['totalQty'];//总的数量
		$totalDiscount = $datas['totalDiscount'];//总的折扣额
		$totalAmount = $datas['totalAmount'];//总的金额
		$totalTax = $datas['totalTax'];//税额
		$totalTaxAmount = $datas['totalTaxAmount']; //价税合计
		
		$des = $datas['description'];//总订单描述
		$disRate = $datas['disRate'];//优惠率
		$disAmount = $datas['disAmount'];//优惠金额
		$amount = $datas['amount'];//最后金额
		
		$cash = $datas['cash'];//账户余额
		$password = $datas['password'];//支付密码
		$paymentMethod = $datas['paymentMethod'];//支付方式
 
		$order = array();
		$uprice = 0;
		$buyer = $buid;
		$inorder = "";
		
		foreach($datas['entries'] as $key=>$val){
			$order[$val['locationId']]['total'] +=$val['price']*$val['qty']; 
			$order[$val['locationId']]['deduction']+=$val['deduction'];
			$order[$val['locationId']]['seller_id'] = $val['locationId']; 
			$order[$val['locationId']]['prolist'][] = $val;		
		}
		
		//print_r($datas['entries']);die;
		
		//判断各个商品的库存 ADD by windfnn 2016-01-08
		if(!empty($datas['entries'])){
			$str='';
			foreach($datas['entries'] as $k=>$v){
				if($v['goods_count']<$v['qty']){
					$str.="商品：$v[goodsName]【$v[skuName]】库存不足（$v[goods_count]）";
				}
			}
			if(!empty($str)){
				$body_data_rows = array();
				$body_data_rows['status'] = 0;
				$body_data_rows['msg'] = $str;
				$body_data_rows['data'] = $data;
				$pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
				header('Content-type: application/json');
				echo json_encode($pro_data_rows);		
				die;
			}
		}
		//END 2016-01-08
		
		switch($paymentMethod){
			case "1":$payment_name = '现金支付';break;
			case "2":$payment_name = '余额支付';break;
		}
		
		//判断支付方式和余额 ADD by windfnn 2016-01-09
		if($paymentMethod==2)
		{
			$errorMsg = '';
			
			//买家支付账户
			$sql = "select pay_pass,pay_id,pay_email from pay_member where userid='$buid'";
			$db->query($sql);
			$re1 = $db->fetchRow();
			
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
			
			$pwd = $re1['pay_pass'];
			
			if($cash<$amount){
				$errorMsg='用户余额不足！';
			}

			if($password==''||$password!=$pwd){
				$errorMsg='密码错误，请重新输入密码！';
			}
					
			if(!empty($errorMsg))
			{
				$body_data_rows = array();
				$body_data_rows['status'] = 0;
				$body_data_rows['msg'] = $errorMsg;
				$body_data_rows['data'] = $data;
				$pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
				header('Content-type: application/json');
				echo json_encode($pro_data_rows);		
				die;
				
			}
		}
		//END 2016-01-09
		
		foreach($order as $key => $val)
		{
			if($val['prolist'])
			{
				$sell_userid=$val['seller_id'];
				if(!empty($sell_userid))
				{	 
					$product_price = $val['total'];//购物总价
					$order_id = date("Ymdhis").rand(0,9);//订单号
					$msg = '';
					$time = time();
                    $deduction = $val['deduction']; //优惠价格
                    $inorder .= $order_id.",";
					
					/***生成买家订单****/
					$sql = "INSERT INTO ".ORDER." (`userid`,`order_id`,`buyer_id`,`seller_id`,`product_price`,`status`,`des`,`create_time`,`uptime`,`deduction`,`is_line`,`payment_name`) VALUES ($buid,$order_id,'0',$sell_userid,'$product_price',4,'$msg','$time','$time','$deduction',1,'$payment_name')";
					$db->query($sql);
					
					/***生成卖家订单****/
					$sql = "INSERT INTO ".ORDER." (`userid`,`order_id`,`buyer_id`,`seller_id`,`product_price`,`status`,`des`,`create_time`,`uptime`,`deduction`,`is_line`,`payment_name`) VALUES ($sell_userid,$order_id,'$buid','0','$product_price',4,'$msg','$time','$time','$deduction',1,'$payment_name')";
					$db->query($sql);
                   
					foreach($val['prolist'] as $k=>$v)
					{    
						$sql="select commission from mallbuilder_product_cat where catid='$v[pcatid]'";//获取佣金比例
						$db->query($sql);
						$commission=$db->fetchField('commission');
						$com = $v['price']*$commission*$v['qty'];
						$one_price+=$v['price']*$commission*$v['qty'];
					
						$v['skuId'] = $v['skuId']?$v['skuId']:"0"; 
						$sql = "INSERT INTO ".ORPRO." (`order_id`,`buyer_id`,`pid`,`pcatid`,`name`,`pic`,`price`,`num`,`time`,`setmeal`,`spec_name`,`spec_value`,`status`,`discountRate`,`deduction`,`amount`,`taxRate`,`tax`,`taxAmount`,`des`,`is_offline`,`commission`) 
						VALUES 
						($order_id,$buid,$v[pid],$v[pcatid],'$v[goodsName]','".$v['pic']."','".$v['price']."','".$v['qty']."','".time()."','$v[skuId]','$v[specName]','$v[skuName]',3,'$v[discountRate]','$v[deduction]','$v[amount]','$v[taxRate]','$v[tax]','$v[taxAmount]','$des',1,'$com')"; 
						$db->query($sql); 
						
						//增加销量
						$sql="update mallbuilder_product set sales= sales + $v[qty] where id=$v[pid]";
						$db->query($sql);
						
						//减少库存
						if($v['skuId'])
						{
							$sql="update ".SETMEAL." set stock = stock - $v[qty] where id = '$v[skuId]'";
							$db->query($sql);
						}		
				
						$sql="update ".PRODUCT." set stock = stock - $v[qty] where id = '$v[pid]'";
						$db->query($sql);
						
					}
 
				}	
			}
		}
		
		// 插入到合并支付表
		$uorder = "U".date("Ymdhis",time()).rand(100,999); // 18位
		$inorder = substr($inorder, 0,-1);
		
		$sales_name = $_SESSION['ADMIN_USER'];
		$sql = "insert into ".UORDER."  (`order_id`,`inorder`,`price`,`create_time`,`buyer`,`sales_id`,`total_discount`,`total_qty`,`total_amount`,`des`,`total_tax_amount`,`discount_rate`,`discount_amount`,`total_tax`,`is_offline`,`sales_name`,`payment_method`) values ('$uorder','$inorder','$amount','".time()."','$buid','$salesId','$totalDiscount','$totalQty','$totalAmount','$des','$totalTaxAmount','$disRate','$disAmount','$totalTax',1,'$sales_name','$paymentMethod')";
		$db->query($sql);
		$data['id'] = $db->lastid();
		
		if($paymentMethod==2&&empty($errorMsg))
		{
			//付款，买家扣款
			$sql = "update pay_member set cash = cash-'$amount' where userid='$buid'";
			$db->query($sql);
				
			//卖家加钱
			$sql = "update pay_member set cash = cash+'$amount' where pay_id='$re2[pay_id]'";
			$db->query($sql);
				
			//买家流水
			$time=time();
			$flow_id=date("Ymdhis").rand(0,9);
			$sql = "insert into pay_cashflow (flow_id,pay_uid,seller_email,order_id,price,time,note,statu,type,mold) values ('$flow_id','$re1[pay_id]','$re2[pay_email]','$inorder','-$amount','$time','线下交易-余额支付','4','1','0')";
			$db->query($sql);
				
			//平台流水
			$sql="insert into pay_cashflow (flow_id,pay_uid,buyer_email,order_id,price,time,note,statu,type,mold) values ('$flow_id','$re2[pay_id]','$re1[pay_email]','$inorder','$amount','$time','线下交易-余额支付','4','1','3')";
			$db->query($sql);
			
			if($_SESSION['IDENTITY']!=1&&$one_price>0){
				//--------------写入流水账。卖家扣相关的费用=总站佣金+分站佣金。
				$post['type']=1;//直接到账
				$post['action']='add';//
				$post['buyer_email']=$re2[pay_email];//
				$post['seller_email']='0';//
				$post['order_id']='C'.time();//外部订单号
				$post['extra_param'] = 'Commission';
				$post['price'] = $one_price;//订单总价，单价元
				$post['name1'] = '线下订单'.$inorder.'佣金收入';
				$post['name'] = '线下订单'.$inorder.'佣金支出';
				pay_get_url($post,true);//跳转至订单生成页面
			}
		}
		
		//给会员增加积分
		include_once("../module/member/includes/plugin_member_class.php");
		$member = new member();				
		$member->add_points(($amount)*1,'1',$uorder,$buid);
  
		//print_r($arr);die;
		$body_data_rows = array();
        $body_data_rows['status'] = 200;
		$body_data_rows['msg'] = 'success';
		$body_data_rows['data'] = $data;
        $pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
		header('Content-type: application/json');
		echo json_encode($pro_data_rows);		
		die;
	}
	
	//获取产品分类
	if($_GET['op']=='cat')
	{	
		$sql="select catid AS id,cat AS name,'0' AS `level` from ".PCAT." where 1 and catid<9999 order by nums,catid";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $k=>$v)
		{	
			//获得二级分类
			$tsql=" and catid < '".$v['id']."99' and catid >'".$v['id']."00' ";
			$sql="select catid AS id,cat AS name,'1' AS `level`,'$v[id]' AS parentId from ".PCAT." where 1 $tsql order by nums,catid";
			$db->query($sql);
			$a=$db->getRows();
			$de = array_merge($de,$a);
			foreach($a as $ks=>$vs)
			{	
				//获得三级分类
				$tsql=" and catid < '".$vs['id']."99' and catid >'".$vs['id']."00' ";
				$sql="select catid AS id,cat AS name,'2' AS `level`,'$vs[id]' AS parentId from ".PCAT." where 1 $tsql order by nums,catid";
				$db->query($sql);
				$b=$db->getRows();
				$de = array_merge($de,$b);
				
				foreach($b as $kss=>$vss)
				{	
					//获得四级分类
					$tsql=" and catid < '".$vss['id']."99' and catid >'".$vss['id']."00' ";
					$sql="select catid AS id,cat AS name,'3' AS `level`,'$vss[id]' AS parentId from ".PCAT." where 1 $tsql order by nums,catid";
					$db->query($sql);
					$c=$db->getRows();
					$de = array_merge($de,$c);
					/* 
					foreach($c as $ksss=>$vsss)
					{	
						//获得五级分类
						$sql="select name from ".TYPE." where id='$vsss[ext_field_cat]'";
						$db->query($sql);
						$c[$ksss]['property']=$db->fetchField('name');
					} */
				}
			}
		}
		$body_data_rows = array();
        $body_data_rows['status'] = 200;
		$body_data_rows['msg'] = 'success';
		$body_data_rows['data']['items'] = $de;
        $pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
		header('Content-type: application/json');
		echo json_encode($pro_data_rows);
		die;
	}
	
	
	if($_GET['action']=='listBySelected'){
		echo '{"status":200,"msg":"success","data":{"result":[{"advanceDays":0,"amount":5,"barCode":"","categoryName":"","currentQty":"","delete":false,"discountRate":0,"id":129459311923097,"invSkus":[],"isSerNum":0,"isWarranty":0,"josl":"","locationId":0,"locationName":"","locationNo":"","name":"qunhong","nearPrice":10,"number":"00000000","pinYin":"","purPrice":1,"quantity":2,"remark":"","retailPrice":0,"safeDays":0,"salePrice":10,"salePrice1":0,"salePrice2":0,"salePrice3":0,"skuAssistId":"","skuBarCode":"","skuClassId":0,"skuId":0,"skuName":"","skuNumber":"","spec":"1g","unitCost":2.5,"unitId":0,"unitName":""}]}}';
		die;
	}
	
	
	$tpl->display('product_select.htm');
?>