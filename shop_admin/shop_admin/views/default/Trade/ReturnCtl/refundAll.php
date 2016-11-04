<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css" rel="stylesheet" type="text/css">
    
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript">
    var BASE_URL = "<?= Yf_Registry::get('base_url') ?>";
</script>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>退款管理</h3>
                <h5>商品订单退款申请及审核处理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Return&met=refundWait&otyp=<?=$otyp?>"><span>待处理</span></a></li>
                <li><a class="current"><span>所有记录</span></a></li>
                <?php if($otyp==1){ ?>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Return&met=reason"><span>退款退货原因</span></a></li>
                <?php }?>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>待处理退款所有记录</li>
        </ul>
    </div>
    <div class="mod-toolbar-top cf">
        <div class="left">
            <div id="assisting-category-select" class="ui-tab-select">
                <ul class="ul-inline">
                    <li>
                        <input type="text" id="return_code" class="ui-input ui-input-ph con" placeholder="请输入退单编号...">
                    </li>
                    <li>
                        <input type="text" id="seller_user_account" class="ui-input ui-input-ph con" placeholder="请输入商铺名称...">
                    </li>
                    <li>
                        <input type="text" id="buyer_user_account" class="ui-input ui-input-ph con" placeholder="请输入买家名称...">
                    </li>
                    <li>
                        <input type="text" id="order_goods_name" class="ui-input ui-input-ph con" placeholder="请输入商品名称...">
                    </li>
                    <li>
                        <input type="text" id="order_number" class="ui-input ui-input-ph con" placeholder="请输入订单编号...">
                    </li>
                    <li>
                        <input id="start_time" class="ui-input ui-datepicker-input" type="text" readonly placeholder="开始时间"/>
                        至
                        <input id="end_time" class="ui-input ui-datepicker-input" type="text"  readonly placeholder="结束时间"/>
                    </li>
                    <li>
                        <input type="text" id="min_cash" class="ui-input ui-input-ph con" placeholder="请输入退款金额...">
                        -
                        <input type="text" id="max_cash" class="ui-input ui-input-ph con" placeholder="请输入退款金额...">
                    </li>
                    <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                </ul>
            </div>
        </div> 
        <div class="fr">
            <a class="ui-btn" id="btn-excel">导出<i class="iconfont icon-btn04"></i></a>
            <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
        </div>
    </div>
    <div class="grid-wrap">
        <table id="grid">
        </table>
        <div id="page"></div>
    </div></div>
<script src="<?=$this->view->js?>/controllers/trade/return/return_all_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>