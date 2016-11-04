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
                <h3>店铺代金券</h3>
                <h5>商城店铺代金券活动设定与管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=get"><span>店铺代金券</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=quota"><span>套餐列表</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=price"><span>面额设置</span></a></li>
                <li><a class="current"><span>设置</span></a></li>
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
            <li>店铺代金券设置</li>
        </ul>
    </div>
    
    <form method="post" enctype="multipart/form-data" id="voucher-setting-form" name="form1">
        <input type="hidden" name="config_type[]" value="voucher"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>购买单价（元/月）</label>
                </dt>
                <dd class="opt">
                    <input id="promotion_voucher_price" name="voucher[promotion_voucher_price]" value="<?=($data['promotion_voucher_price']['config_value'])?>" class="input-txt ui-input" type="text">

                    <p class="notic">购买代金劵活动所需费用，购买后商家可以在所购买周期内发布代金劵促销活动</p>

                    <p class="notic">相关费用会在店铺的账期结算中扣除</p>

                    <p class="notic">若设置为0，则商家可以免费发布此种促销活动</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>每月活动数量</label>
                </dt>
                <dd class="opt">
                    <input id="promotion_voucher_storetimes_limit" name="voucher[promotion_voucher_storetimes_limit]" value="<?=($data['promotion_voucher_storetimes_limit']['config_value'])?>"
                           class="input-txt ui-input" type="text">

                    <p class="notic">每月最多可以发布的代金劵促销活动数量</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>买家最大领取数量</label>
                </dt>
                <dd class="opt">
                    <input id="promotion_voucher_buyertimes_limit" name="voucher[promotion_voucher_buyertimes_limit]" value="<?=($data['promotion_voucher_buyertimes_limit']['config_value'])?>"
                           class="input-txt ui-input" type="text">

                    <p class="notic">买家最多只能拥有同一个店铺尚未消费抵用的店铺代金券最大数量，该值最大为20</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>

    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>