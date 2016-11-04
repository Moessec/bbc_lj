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
        <h3>商品管理</h3>
        <h5>商城所有商品索引及管理</h5>
      </div>
      <ul class="tab-base nc-row"><li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common" <?=((-1 == request_int('common_state', '-1') && -1 == request_int('common_verify', '-1')) ? 'class="current"' : '')?>><span>所有商品</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_state=10" <?=(10 == request_int('common_state', '-1') ? 'class="current"' : '')?> ><span>违规下架</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_verify=10" <?=(10 == request_int('common_verify', '-1') ? 'class="current"' : '')?>><span>等待审核</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=goods&config_type%5B%5D=goods" <?=('Config' == request_string('ctl') ? 'class="current"' : '')?>><span>商品设置</span></a></li></ul> </div>
  </div>
  <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em> </div>
    <ul>
      <li>上架，当商品处于非上架状态时，前台将不能浏览该商品，店主可控制商品上架状态</li>
      <li>违规下架，当商品处于违规下架状态时，前台将不能购买该商品，只有管理员可控制商品违规下架状态，并且商品只有重新编辑后才能上架</li>
      <li>设置项中可以查看商品详细、查看商品SKU。查看商品详细，跳转到商品详细页。查看商品SKU，显示商品的SKU、图片、价格、库存信息。</li>
    </ul>
  </div>
    
	<div class="mod-search cf" id="report-search">
		<div class="l" id="filter-menu">
			<ul class="ul-inline fix">
				<li>
					<span id="user"></span>
					<input type="text" id="common_name" name="common_name" class="ui-input ui-input-ph" placeholder="输入商品名称"   autocomplete="off" >
					<input type="text" id="common_id" name="common_id" class="ui-input ui-input-ph" placeholder="输入商品平台货号"   autocomplete="off" >
					<input type="text" id="shop_name" name="shop_name" class="ui-input ui-input-ph" placeholder="输入商品所属店铺名称"   autocomplete="off" >
				</li>
				<li id="brand" style="display: list-item;"><span class="mod-choose-input" id="filter-brand"><input type="text" class="ui-input" id="brand_id" autocomplete="off" placeholder="输入品牌名称" ><span class="ui-icon-ellipsis"></span></span></li>
				<li>
					<span id="common_state"></span>
					<span id="common_verify"></span>
					<span id="goods_cat"></span>
				</li>
				<li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a><!--<a class="ui-btn ui-btn-refresh" id="refresh" title="刷新"><b></b></a>--></li>
			</ul>
		</div>
	</div>


    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

    </div>
</div>
<script type="text/javascript">
	var common_state = <?=request_int('common_state', -1)?>;
	var common_verify = <?=request_int('common_verify', -1)?>;
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/common_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
