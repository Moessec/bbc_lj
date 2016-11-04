<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商城设置&nbsp;</h3>
                <h5>默认词设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=shop&config_type%5B%5D=setting"><span>商城设置</span></a></li>
				 <li><a class="current"><span>默认搜索</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Base_Search&met=search"><span>热门搜索</span></a></li>
			</ul> 
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>用户默认关键词统计</li>
        </ul>
    </div>
	<form method="post" enctype="multipart/form-data" id="search-setting-form" >
        <input type="hidden" name="config_type[]" value="search"/>
		<div class="ncap-form-default">
		<dl class="row">
                <dt class="tit">
                    <label>默认搜索词</label>
                </dt>
                <dd class="opt">
                    <input id="search_words" name="search[search_words]" value="<?=($data['search_words']['config_value'])?>" class="ui-input w400" type="text" />

                    <p class="notic"></p>
                </dd>
            </dl>
		<div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>	
	</div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<script>

</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>