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
				<h3>商品分类管理</h3>
				<h5>商品分类管理</h5>
			</div>
			<ul class="tab-base nc-row"><li><a  class="current"><span>分类管理</span></a></li></ul> </div>
	</div>
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
		</div>
		<ul>
			<li>当店主添加商品时可选择商品分类，用户可根据分类查询商品列表</li>
			<li>“编辑分类导航”功能可以设置前台左上侧商品分类导航的相关信息，可以设置分类前图标、分类别名、推荐分类、推荐品牌以及两张广告图片。</li>
			<li>修改完成后,必须去清楚缓存,否则无法立即生效。</li>
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

<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/cat_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
