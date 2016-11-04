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
                <h3>积分兑换</h3>
                <h5>商城积分礼品的发布及兑换礼品的管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>礼品列表</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Points&met=order"><span>兑换列表</span></a></li>
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
            <li>使用积分兑换功能请先确保系统积分状态处于开启状态（促销 -> 促销设定），礼品会出现在积分中心，会员可凭积分兑换，兑换成功后，由系统平台进行发货。</li>
        </ul>
    </div>
    
        <div class="mod-toolbar-top cf">
            <div class="fl">
                <ul class="ul-inline">
                    <li>
                        <input type="text" name="points_goods_name" id="points_goods_name" class="ui-input ui-input-ph matchCon" placeholder="礼品名称...">
                    </li>
                    <li><a class="ui-btn mrb" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                </ul>
            </div>
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

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/points/points_goods_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>