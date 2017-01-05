<?php
	
	include_once("config.php");
	if($_GET['id'])
	{
		$id = $_GET['id'];
		$sql = "SELECT `inorder`,buyer,create_time,order_id,total_amount,total_tax,price,discount_rate,discount_amount FROM `mallbuilder_product_union_order` WHERE id=$id";
		$db->query($sql);
		$res = $db->fetchRow();		
		$inorder = $res['inorder'];
		
		$sql ="SELECT mobile,IFNULL(`real_name`,`user`) AS name FROM mallbuilder_member WHERE userid='$res[buyer]'";
		$db->query($sql);
		$user = $db->fetchRow();
		
		$order = explode(',',$inorder);
		
		$row = Array();
		foreach($order as $k=>$v)
		{
			$sql = "SELECT * FROM mallbuilder_product_order_pro WHERE order_id='$v'";
			$db->query($sql);
			$temp= $db->getRows();
			$row = array_merge($row,$temp);
		}
		
		//print_r($row);
		
	}

?>
<html>
<head>
   	<title>WebPos收银系统</title>
	<meta charset="UTF-8"> 
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<link media="all" href="css/unicorn.css?14.4" rev="stylesheet" rel="stylesheet">
	<link media="all" href="css/custom.css?14.4" rev="stylesheet" rel="stylesheet">
	<script src="js/libs/jquery/jquery-1.10.2.min.js"></script>
</head>
	<body class="flat" data-color="grey">
		<div class="minibar" id="wrapper">
			<div class="clearfix sales_content_minibar" id="content">		
				<div class="receipt_small" id="receipt_wrapper">
					<div id="receipt_header">
						<div id="company_name">WebPos收银系统</div>
						<!-- <div id="sale_time">2015-09-23 13:43:52</div> -->
					</div>
					
					<div id="receipt_general_info">
						<div id="customer">姓名：<?php echo $user['name']; ?></div>
						<div>手机号码：<?php echo $user['mobile']; ?></div>			
						<div>创建时间：<?php echo date('Y-m-d H:m:s',$res['create_time']); ?></div>
						<div>订单编号：<?php echo $res['order_id']; ?></div>
						<!-- <div id="sale_id">Sale ID: POS 38</div>				
						<div id="sale_register_used">Register Name: Default</div>		
						<div id="employee">Employee: John Doe</div> -->
					</div>
					
					<table id="receipt_items">
						<tbody>
							<tr>
								<th style="width:49%;" class="left_text_align">商品名称</th>
								<th style="width:20%;" class="gift_receipt_element left_text_align">单价</th>
								<th style="width:15%;" class="left_text_align">数量</th>
								<th style="width:16%;" class="gift_receipt_element left_right_align">合计</th>
							</tr>
							
							<?php foreach($row as $key=>$val){ ?>
							<tr>
								<td class="left_text_align"><?php echo $val['name']; ?></td>
								<td class="gift_receipt_element left_text_align">￥<?php echo $val['price']; ?></td>
								<td class="left_text_align"><?php echo $val['num']; ?></td>
								<td class="gift_receipt_element right_text_align">￥<?php echo $val['price']; ?></td>
							</tr>
							<?php if($val['deduction']!=0){ ?>
							<tr>
								<td class="left_text_align"><!--商品折扣额--></td>
								<td class="gift_receipt_element left_text_align"></td>
								<td class="left_text_align"></td>
								<td class="gift_receipt_element right_text_align">-￥<?php echo $val['deduction']; ?></td>
							</tr>
							<?php } ?>
							
							<?php } ?>

							<tr>
								<td align="left" colspan="3"></td>
								<td colspan="1"></td>						
							</tr>

							<tr class="gift_receipt_element">
								<td style="border-top:2px solid #000000;" colspan="3" class="right_text_align">总计</td>
								<td style="border-top:2px solid #000000;" colspan="1" class="right_text_align"><b>￥<?php echo $res['total_amount']; ?></b></td>
							</tr>
							
							<?php if($res['total_tax']!=0){ ?>
							<tr class="gift_receipt_element">
								<td colspan="3" class="right_text_align">税额:</td>
								<td colspan="1" class="right_text_align">￥<?php echo $res['total_tax']; ?></td>
							</tr>
							<?php } ?>
							
							<?php if($res['discount_amount']!=0){ ?>
							<tr class="gift_receipt_element">
								<td colspan="3" class="right_text_align">优惠金额:</td>
								<td colspan="1" class="right_text_align">￥<?php echo $res['discount_amount']; ?></td>
							</tr>
							<?php } ?>
 
							<tr class="gift_receipt_element">
								<td colspan="3" class="right_text_align ">付款金额</td>
								<td colspan="1" class="right_text_align"><b>￥<?php echo $res['price']; ?></b></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div><!--end #content-->
		</div><!--end #wrapper-->
 
	<!--<script type="text/javascript">$(window).bind("load", function() {window.print();});</script>-->
	<script type="text/javascript">
		//function print_receipt(){window.print();}
		function doPrint(){
			window.print();
		}
		window.doPrint = doPrint;
	</script>
</body>
</html>