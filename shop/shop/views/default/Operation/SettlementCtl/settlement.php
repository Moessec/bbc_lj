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
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>结算管理</h3>
                <h5>实物商品订单结算索引及商家账单表</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>结算管理</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>账单计算公式：订单金额(含运费) - 佣金金额 - 退单金额 + 退还佣金 - 店铺促销费用 + 定金订单中的未退定金 + 下单时使用的平台红包 - 全部退款时应扣除的平台红包</li>
            <li>账单处理流程为：系统出账 > 商家确认 > 平台审核 > 财务支付(完成结算) 4个环节，其中平台审核和财务支付需要平台介入，请予以关注</li>
        </ul>
    </div>
    
        <div class="mod-toolbar-top cf">
            <div class="left">
                <div id="assisting-category-select" class="ui-tab-select">
                    <ul class="ul-inline">
                        <li>
                            <input type="text" id="settleId" class="ui-input ui-input-ph con" placeholder="请输入结算单编号...">
                        </li>
                        <li>
                            <input type="text" id="shopName" class="ui-input ui-input-ph con" placeholder="请输入商铺名称...">
                        </li>
                        <li>
                            <span id="source"></span>
                        </li>
                        <li>
                            <input id="start_time" class="ui-input ui-datepicker-input" type="text" readonly placeholder="开始时间"/>
                            至
                            <input id="end_time" class="ui-input ui-datepicker-input" type="text"  readonly placeholder="结束时间"/>
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
        </div>
    <script src="<?=$this->view->js?>/controllers/operation/settlement_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>