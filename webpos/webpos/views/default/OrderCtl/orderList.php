<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">

<link href="<?=$this->view->css?>/sales.css?ver=201508241556" rel="stylesheet" type="text/css">
<script src="<?=$this->view->js?>/models/jquery.md5.js" type="text/javascript"></script>
</head>
<style>
	#matchCon { width: 220px; }
	#print{margin-left:10px;}
	a.ui-btn{margin-left:10px;}
	#reAudit,#audit{display:none;}
</style>
<body>


<div class="wrapper">
	<div class="mod-search cf">
		<div class="fl">
			<ul class="ul-inline">
				<li>
				  <input type="text" id="matchCon" class="ui-input ui-input-ph" value="请输入订单编号">
				</li>
				<li>
					<label>日期:</label>
					<input type="text" id="beginDate" value="" class="ui-input ui-datepicker-input">
					<i>-</i>
					<input type="text" id="endDate" value="" class="ui-input ui-datepicker-input">
				</li>
				<li>
					<a class="ui-btn" id="search">查询</a>
				</li>
			</ul>
		</div>
		<div class="fr">
			<a class="ui-btn ui-btn-sp" id="add">下单</a>
			<a href="#" class="ui-btn mrb" id="btn-refresh">刷新</a>
		</div>
	</div>

	<div class="grid-wrap">
		<table id="grid"></table>
		<div id="page"></div>
	</div>
</div>

<script src="<?= Yf_Registry::get('base_url') ?>/webpos/static/default/js/controllers/order/order_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>