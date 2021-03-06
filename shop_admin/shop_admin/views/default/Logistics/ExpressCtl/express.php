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
                <h3>快递公司管理&nbsp;</h3>
                <h5>提供给商家可选择的物流快递公司</h5>
            </div>
           <ul class="tab-base nc-row">
                <li><a class="current"><span>快递公司</span></a></li>               
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>		
            <li>系统内置的快递公司不得删除，只可编辑状态，平台可禁用不需要的快递公司，常用的快递公司将会排在靠前位置。</li>
        </ul>
    </div>

    <div class="wrapper">
         <!--<div class="mod-toolbar-top cf">
           
           <div class="fr">
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div> 
        </div>-->
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>

</div>
<script type="text/javascript">

</script>
<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/logistics/express/express_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>