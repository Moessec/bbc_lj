<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<style>
.matchCon {
    width: 280px;
}
</style>
<body>
<div class="wrapper page">

    <div class="wrapper">
		<div class="mod-search cf">
			<div class="fl">
				<ul class="ul-inline">
					<li><span id="catorage"></span></li>
					<li><input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="用户名"></li>
					<li><a class="ui-btn mrb" id="search">查询</a></li>
				</ul>
			</div>
			<div class="fr">
				<a href="#" class="ui-btn ui-btn-sp mrb" id="btn-refresh">刷新</a>
			</div>
		</div>
		<div class="grid-wrap">
			<table id="grid"></table>
			<div id="page"></div>
		</div>
	</div>

<script src="<?= Yf_Registry::get('base_url') ?>/webpos/static/default/js/controllers/user/user_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>