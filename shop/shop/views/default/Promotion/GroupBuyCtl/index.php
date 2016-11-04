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
                <li><a class="current"><span>团购活动</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=cat"><span>团购分类</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=price"><span>团购价格区间</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=slider&config_type%5B%5D=slider"><span>首页幻灯片</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=vrArea"><span>虚拟团购地区</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=groupbuy&config_type%5B%5D=groupbuy"><span>团购设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=quota"><span>已开通店铺</span></a></li>
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
            <li>管理员可以审核新的团购活动申请、取消进行中的团购活动或者删除团购活动。</li>
        </ul>
    </div>

    <div class="wrapper">
        <div class="mod-search cf">
            <div class="fl">
                <ul class="ul-inline">
                    <li>
                        <span id="source"></span>
                    </li>
                    <li>
                         <input type="text" id="groupbuy_name" class="ui-input ui-input-ph matchCon" name="groupbuy_name" placeholder="团购名称...">
                         <input type="text" id="goods_name" class="ui-input ui-input-ph matchCon" name="goods_name" placeholder="商品名称...">
                         <input type="text" id="shop_name" class="ui-input ui-input-ph matchCon" name="shop_name" placeholder="店铺名称...">
                    </li>
                    <li><a class="ui-btn mrb" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                </ul>
            </div>
            <div class="fr">
                <a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>

        <div class="grid-wrap">
            <table id="grid"></table>
            <div id="page"></div>
        </div>
    </div>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/groupbuy/group_buy_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>