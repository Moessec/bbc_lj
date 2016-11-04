<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="wrapper page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>物流自提服务站</h3>
				<h5>商城对线下物流自提点的设定集管理</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a <?php if($data['tab']=="manage"){ echo "class='current'";}else{?> href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Delivery&met=delivery" <?php } ?>><span>管理</span></a></li>
				<li><a <?php if($data['tab']=="check"){ echo "class='current'";}else{?> href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Delivery&met=delivery&dtyp=check" <?php } ?>><span>等待审核</span></a></li>
			</ul>
		</div>
	</div>
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>物流自提服务站关闭后，被用户选择设置成收货地址的记录会被删除，请谨慎操作。</li>
		</ul>
	</div>
        <div class="mod-toolbar-top cf">
			<div class="left">
				<div id="assisting-category-select" class="ui-tab-select">
					<ul class="ul-inline">
						<li>
							<input type="text" id="user_account" class="ui-input ui-input-ph con" placeholder="用户名">
						</li>
						<li>
							<input type="text" id="delivery_real_name" class="ui-input ui-input-ph con" placeholder="真实姓名">
						</li>
						<li>
							<input type="text" id="delivery_name" class="ui-input ui-input-ph con" placeholder="服务站名称">
						</li>
						<li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
					</ul>
				</div>
			</div>
            <div class="fr">
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    <script src="<?=$this->view->js?>/controllers/operation/delivery_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

