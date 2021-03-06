<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script type="text/javascript">
    var BASE_URL = "<?= Yf_Registry::get('base_url') ?>";
</script>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>举报管理</h3>
                <h5>商品举报处理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Report&met=baseDo"><span>未处理</span></a></li>
                <li><a class="current"><span>已处理</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Report&met=type"><span>类型设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Report&met=subject"><span>主题设置</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>已处理举报</li>
        </ul>
    </div>
        <div class="mod-toolbar-top cf">
            <div class="left">
                <div id="assisting-category-select" class="ui-tab-select">
                    <ul class="ul-inline">
                        <li>
                            <input type="text" id="goods_name" class="ui-input ui-input-ph con" placeholder="请输入商品名称...">
                        </li>
                        <li>
                            <input type="text" id="shop_name" class="ui-input ui-input-ph con" placeholder="请输入商铺名称...">
                        </li>
                        <li>
                            <input type="text" id="user_account" class="ui-input ui-input-ph con" placeholder="请输入举报人...">
                        </li>
                        <li>
                            <input type="text" id="report_subject_name" class="ui-input ui-input-ph con" placeholder="请输入举报主题...">
                        </li>
                        <li>
                            <input type="text" id="report_type_name" class="ui-input ui-input-ph con" placeholder="请输入举报类型...">
                        </li>
                        <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                    </ul>
                </div>
            </div>
	    <div class="fr">
		    <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
  
    <script src="<?=$this->view->js?>/controllers/trade/report/report_done_list.js"></script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>