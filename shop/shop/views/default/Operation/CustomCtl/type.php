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
				<h3>平台客服</h3>
				<h5>商城对用户咨询类型设定与处理</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Custom&met=custom"><span>平台客服咨询列表</span></a></li>
				<li><a class="current"><span>平台咨询类型</span></a></li>
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
            <div class="fr">
		<a class="ui-btn" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a>
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    <script src="<?=$this->view->js?>/controllers/operation/custom_type_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

