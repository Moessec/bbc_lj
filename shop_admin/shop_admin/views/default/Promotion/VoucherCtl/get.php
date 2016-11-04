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
                <li><a class="current"><span>店铺代金券</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=quota"><span>套餐列表</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Voucher&met=price"><span>面额设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=voucher&config_type%5B%5D=voucher"><span>设置</span></a></li>

            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
            <li>手工设置代金券失效后,用户将不能领取该代金券,但是已经领取的代金券仍然可以使用。</li>
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
                        <input type="text" id="voucher_t_title" name="voucher_t_title" class="ui-input ui-input-ph matchCon" placeholder="代金券名称">
                        <input type="text" id="voucher_t_shop_name" name="voucher_t_shop_name" class="ui-input ui-input-ph matchCon" placeholder="店铺名称">
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
</div>
<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/voucher/vouchertemp_list.js"></script>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>