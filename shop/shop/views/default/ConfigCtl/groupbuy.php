<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>团购管理</h3>
                <h5>实物商品团购促销活动相关设定及管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=index"><span>团购活动</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=cat"><span>团购分类</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=price"><span>团购价格区间</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=slider&config_type%5B%5D=slider"><span>首页幻灯片</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=vrArea"><span>虚拟团购地区</span></a></li>
                <li><a class="current"><span>团购设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=quota"><span>已开通店铺</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>团购单价、审核期设置</li>
        </ul>
    </div>
    
    <form method="post" enctype="multipart/form-data" id="groupbuy-setting-form" name="form1">
        <input type="hidden" name="config_type[]" value="groupbuy"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>购买单价（元/月）</label>
                </dt>
                <dd class="opt">
                    <input id="groupbuy_price" name="groupbuy[groupbuy_price]" value="<?=($data['groupbuy_price']['config_value'])?>" class="input-txt ui-input" type="text">

                    <p class="notic">购买团购活动所需费用，购买后商家可以在所购买周期内发布团购促销活动</p>

                    <p class="notic">相关费用会在店铺的账期结算中扣除</p>

                    <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>团购审核期</label>
                </dt>
                <dd class="opt">
                    <input id="groupbuy_review_day" name="groupbuy[groupbuy_review_day]" value="<?=($data['groupbuy_review_day']['config_value'])?>" class="input-txt ui-input" type="text">
                    <span class="err"></span>

                    <p class="notic">团购审核期(天)，平台预留的审核天数，商家只能发布审核期天数以后的团购活动</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>

    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>