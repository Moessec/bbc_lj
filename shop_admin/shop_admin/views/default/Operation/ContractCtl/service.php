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
				<h3>消费者保障服务</h3>
				<h5>消费者保障服务查看与管理</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Contract&met=log&log=join"><span>服务加入申请</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Contract&met=log&log=join"><span>服务退出申请</span></a></li>
				<li><a class='current'><span>店铺保障服务</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Contract&met=type"><span>保障服务管理</span></a></li>
			</ul>
		</div>
	</div>
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>买家联系平台客服时所需要选择的类型。</li>
			<li>提交咨询时，咨询类型必须选择，请不要全部删除。</li>
		</ul>
	</div>

        <div class="mod-toolbar-top cf">
			<div class="left">
				<div id="assisting-category-select" class="ui-tab-select">
					<ul class="ul-inline">
						<li>
							<input type="text" id="shopName" class="ui-input ui-input-ph con" placeholder="店铺名称">
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
    <script src="<?=$this->view->js?>/controllers/operation/contract_service_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

