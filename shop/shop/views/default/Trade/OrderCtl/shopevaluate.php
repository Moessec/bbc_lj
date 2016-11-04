<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>评价管理</h3>
        <h5>商品交易评价及店铺动态评价管理</h5>
      </div>
		<ul class="tab-base nc-row">
			<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Order&met=evaluate"><span>来自买家的评价</span></a></li>
			<li><a class="current"><span>店铺动态评价</span></a></li
		</ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em> </div>
    <ul>
      <li>买家可在订单完成后对店铺进行动态评价操作</li>
      <li>评价统计信息将显示在对应的店铺相应页面</li>
    </ul>
  </div>
    
    <div class="grid-wrap">
        <table id="grid">
        </table>
        <div id="page"></div>
    </div>
</div>
<script type="text/javascript" src="<?= $this->view->js ?>/controllers/trade/order/shopevaluate.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>