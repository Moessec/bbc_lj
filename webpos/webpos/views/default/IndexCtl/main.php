<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<script src="<?= $this->view->js ?>/libs/template.js"></script>
<style>li{list-style:none;}</style>
</head>
<body>
 
<div id="bd" class="index-body cf">
	<div class="col-main">
		<div class="main-wrap cf">
			<ul class="quick-links">
				<li class="purchase-purchase">
					<a style="border-top:0px;border-left:0px;" tabid="purchase-purchase" data-right="BU_QUERY" tabTxt="会员" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=User&met=user"><span></span>会员</a>
				</li>
				<li class="sales-sales">
					<a style="border-top:0px;" tabid="sales-sales" data-right="BU_QUERY" tabTxt="下单" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=ordering"><span></span>下单</a>
				</li>
				<li class="storage-transfers">
					<a style="border-top:0px;" tabid="storage-transfers" data-right="BU_QUERY" tabTxt="订单" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=orderList"><span></span>订单</a>
				</li>
				<li class="storage-inventory">
					<a style="border-left:0px;" tabid="storage-inventory" data-right="BU_QUERY" tabTxt="日销售" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=daily"><span></span>日销售</a>
				</li>
				<li class="storage-inventory">
					<a tabid="storage-inventory" data-right="BU_QUERY" tabTxt="周销售" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=weekly"><span></span>周销售</a>
				</li>
				<li class="storage-inventory">
					<a tabid="storage-inventory" data-right="BU_QUERY" tabTxt="月销售" parentOpen="true" rel="pageTab" href="<?= Yf_Registry::get('url') ?>?ctl=Order&met=reportForm&period=monthly"><span></span>月销售</a>
				</li>
			</ul>
		</div>
	</div>
</div>