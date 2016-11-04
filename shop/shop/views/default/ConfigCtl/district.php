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
				<h3>基础设置&nbsp;</h3>
				<h5>可对系统内置的地区进行编辑</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>站点设置</span></a></li>

				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=upload&config_type%5B%5D=upload"><span>上传设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
				<li><a class="current" ><span>地区设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=sphinx&config_type%5B%5D=sphinx"><span>搜索引擎</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Base_FilterKeyword&met=index"><span>敏感词设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=licence&config_type%5B%5D=licence"><span>授权证书</span></a></li>
			</ul>
		</div>
	</div>

	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
		</div>
		<ul>
			<li>全站所有涉及的地区均来源于此处，强烈建议对此处谨慎操作。</li>
			<li>编辑地区信息后，需手动更新地区缓存(平台  > 设置 > 清理缓存 > 地区)，前台才会生效。</li>
			<li>所属大区为默认的全国性的几大区域，只有省级地区才需要填写大区域，目前全国几大区域有：华北、东北、华东、华南、华中、西南、西北、港澳台、海外</li>
			<li>所在层级为该地区的所在的层级深度，如北京>北京市>朝阳区,其中北京层级为1，北京市层级为2，朝阳区层级为3</li>
		</ul>
	</div>
	
	<div class="mod-search cf">
		<div class="fr">
			<a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a>
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
<script type="text/javascript" src="<?=$this->view->js?>/controllers/district/district_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
