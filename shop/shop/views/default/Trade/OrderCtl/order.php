<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
<style>
    .ui-icon-receive{
        background-position: -64px -142px;
    }
</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商品订单&nbsp;</h3>
                <h5>商城实物商品交易订单查询及管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current" href="javascript:void(0)"><span>商品订单</span></a></li>
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
            <li>点击查看操作将显示订单（包括订单物品）的详细信息</li>
            <li>点击取消操作可以取消订单（在线支付但未付款的订单和货到付款但未发货的订单）</li>
            <li>如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击收到货款操作(仅限于下单后7日内可更改收款状态)，并填写相关信息后更改订单支付状态</li>
        </ul>
    </div>
        <div class="mod-toolbar-top cf">
            <div class="left">
                <div id="assisting-category-select" class="ui-tab-select">
                    <ul class="ul-inline">
                        <li><span id="source"></span></li>
						<li>
                            <input type="text" id="order_id" class="ui-input ui-input-ph con" placeholder="请输入订单编号...">
                        </li>
						<li>
                            <input type="text" id="buyer_name" class="ui-input ui-input-ph con" placeholder="请输入买家账号...">
                        </li>
						<li>
                            <input type="text" id="shop_name" class="ui-input ui-input-ph con" placeholder="请输入店铺名称...">
                        </li>
                        <li>
                            <input type="text" id="payment_trade_no" class="ui-input ui-input-ph con" placeholder="请输入支付单号...">
                        </li>
                        <li>
                            <label>付款日期:</label>
                            <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate"> - <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate">
                        </li>
                        <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="fr">
                <a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid"></table>
            <div id="page"></div>
        </div>
    <script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/trade/order/order_list.js"></script>
</div>
<?php
	include $this->view->getTplPath() . '/' . 'footer.php';
?>

