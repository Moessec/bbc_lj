<?php
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("../includes/global.php");
$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
include_once("../lang/" . $config['language'] . "/admin.php");
//===========================================
if(empty($_SESSION["ADMIN_USER"])||empty($_SESSION["ADMIN_PASSWORD"]))
	msg('index.php');
?>
<link href="css/index.css" rel="stylesheet" type="text/css">
<script src="js/libs/template.js"></script>
<style>li{list-style:none;}</style>
</head>
<body>
 
<div id="bd" class="index-body cf">
	<div class="col-main">
		<div class="main-wrap cf">
			<ul class="quick-links">
				<li class="purchase-purchase">
					<a style="border-top:0px;border-left:0px;" tabid="purchase-purchase" data-right="BU_QUERY" tabTxt="会员" parentOpen="true" rel="pageTab" href="member.php"><span></span>会员</a>
				</li>
				<li class="sales-sales">
					<a style="border-top:0px;" tabid="sales-sales" data-right="BU_QUERY" tabTxt="下单" parentOpen="true" rel="pageTab" href="sales.php"><span></span>下单</a>
				</li>
				<li class="storage-transfers">
					<a style="border-top:0px;" tabid="storage-transfers" data-right="BU_QUERY" tabTxt="订单" parentOpen="true" rel="pageTab" href="sales_list.php"><span></span>订单</a>
				</li>
				<li class="storage-inventory">
					<a style="border-left:0px;" tabid="storage-inventory" data-right="BU_QUERY" tabTxt="日销售" parentOpen="true" rel="pageTab" href="order.php?met=1"><span></span>日销售</a>
				</li>
				<li class="storage-inventory">
					<a tabid="storage-inventory" data-right="BU_QUERY" tabTxt="周销售" parentOpen="true" rel="pageTab" href="order.php?met=2"><span></span>周销售</a>
				</li>
				<li class="storage-inventory">
					<a tabid="storage-inventory" data-right="BU_QUERY" tabTxt="月销售" parentOpen="true" rel="pageTab" href="order.php?met=3"><span></span>月销售</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php
$lip=getip();
$nt=time();
$sql="update ".ADMIN." set logoip='$lip',lastlogotime='$nt' where user='".$_SESSION["ADMIN_USER"]."'";
$db->query($sql);
?>