<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>

	.ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
	.img_flied img{width: 60px; height: 60px;}

</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>预约管理</h3>
                <h5>保修预约、活动预约、租赁预约</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Bespeak&met=bespeak" class=""><span>报修预约</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Bespeak&met=bespeak_act" class="current"><span>活动预约</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Bespeak&met=bespeak_rent" class=""><span>租赁管理</span></a></li>
            </ul>
        </div>
    </div>
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
		</div>
		<ul>
			<li>规格将会对应到商品发布的规格，规格值由店铺自己添加。</li>
			<li>默认安装中会添加一个默认颜色规格，请不要删除，只有这个颜色规格才能在商品详细页显示为图片。</li>
		</ul>
	</div>

	<div class="mod-search cf">
		<div class="fr">
			<a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a>
			<a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
		</div>
	</div>

	<div class="grid-wrap">
		<table id="grid">
		</table>
		<div id="page"></div>
	</div>

</div>


<script type="text/javascript">


</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/bespeak_act.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>


