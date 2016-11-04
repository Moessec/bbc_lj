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
				<li><a href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Contract&met=service"><span>店铺保障服务</span></a></li>
				<li><a class="current"><span>保障服务管理</span></a></li>
			</ul>
		</div>
	</div>
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>列表为平台消费者保障服务项目。</li>
			<li>当保障项目状态为“开启”时，店铺可以申请加入该服务；状态为“关闭”时，平台将会禁用该保障服务。</li>
		</ul>
	</div>

       <div class="mod-toolbar-top cf">
            <div class="fr">
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    <script src="<?=$this->view->js?>/controllers/operation/contract_type_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

