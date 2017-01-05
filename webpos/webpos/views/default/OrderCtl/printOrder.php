<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<link media="all" href="<?=$this->view->css?>/unicorn.css?14.4" rev="stylesheet" rel="stylesheet">
<link media="all" href="<?=$this->view->css?>/custom.css?14.4" rev="stylesheet" rel="stylesheet">

</head>
<style>
	#matchCon { width: 220px; }
	#print{margin-left:10px;}
	a.ui-btn{margin-left:10px;}
	#reAudit,#audit{display:none;}
	body{color:#080000;font-weight:600;}
</style>

<body class="flat" data-color="grey">
<div class="minibar" id="wrapper">
<div class="clearfix sales_content_minibar" id="content">
	<div class="receipt_small" id="receipt_wrapper">
		<div id="receipt_header" >
			<div id="company_name">WebPos收银系统</div>
		</div>

		<div id="receipt_general_info" >
			<div id="customer">姓名：<?php echo $data['buyer']['name']; ?></div>
			<div>手机号码：<?php echo $data['buyer']['user_mobile']; ?></div>
			<div>创建时间：<?php echo $data['order']['order_create_time']; ?></div>
			<div>订单编号：<?php echo $data['order']['order_id']; ?></div>
		</div>

		<table id="receipt_items" >
			<tbody style="width:100%;">
			<tr>
				<th style="width:49%;" class="left_text_align">商品名称</th>
				<th style="width:20%;" class="gift_receipt_element left_text_align">单价</th>
				<th style="width:15%;" class="left_text_align">数量</th>
				<th style="width:16%;" class="gift_receipt_element left_right_align">合计</th>
			</tr>

			<?php foreach($data['order']['goods_list'] as $key=>$val){ ?>
				<tr>
					<td class="left_text_align"><?php echo $val['goods_name']; ?></td>
					<td class="gift_receipt_element left_text_align">￥<?php echo $val['goods_price']; ?></td>
					<td class="left_text_align"><?php echo $val['order_goods_num']; ?></td>
					<td class="gift_receipt_element right_text_align">￥<?php echo $val['order_goods_amount']; ?></td>
				</tr>
				<?php if($val['order_goods_discount_fee']!=0){ ?>
					<tr>
						<td class="left_text_align"></td>
						<td class="gift_receipt_element left_text_align"></td>
						<td class="left_text_align"></td>
						<td class="gift_receipt_element right_text_align">-￥<?php echo $val['order_goods_discount_fee']; ?></td>
					</tr>
				<?php } ?>

			<?php } ?>

			<tr>
				<td align="left" colspan="3"></td>
				<td colspan="1"></td>
			</tr>

			<tr class="gift_receipt_element">
				<td style="border-top:2px solid #000000;" colspan="3" class="right_text_align">总计</td>
				<td style="border-top:2px solid #000000;" colspan="1" class="right_text_align"><b>￥<?php echo $data['order']['order_goods_amount']; ?></b></td>
			</tr>

			<?php if($data['order']['order_discount_fee']!=0){ ?>
				<tr class="gift_receipt_element">
					<td colspan="3" class="right_text_align">优惠金额:</td>
					<td colspan="1" class="right_text_align">-￥<?php echo $data['order']['order_discount_fee']; ?></td>
				</tr>
			<?php } ?>

			<tr class="gift_receipt_element">
				<td colspan="3" class="right_text_align ">付款金额</td>
				<td colspan="1" class="right_text_align"><b>￥<?php echo $data['order']['order_payment_amount']; ?></b></td>
			</tr>

			<tr class="gift_receipt_element">
				<td style="width:30%;">=====================</td>
				<td style="word-spacing: 0.5em;font-size:18px;font-weight:600;" colspan="2" class="center_text_align">谢谢惠顾</td>
				<td style="width:30%;">=====================</td>
			</tr>
			</tbody>
		</table>

	</div>
</div>
<script type="text/javascript">
	function doPrint(){
		window.print();
	}
	window.doPrint = doPrint;
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>