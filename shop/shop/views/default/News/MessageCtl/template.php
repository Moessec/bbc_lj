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
                <h3>消息通知&nbsp;</h3>
                <h5>商城设置-商城对商品交易投诉管理及仲裁</h5>
            </div>
            <ul class="tab-base nc-row">
                <li class="current"><a id="2" href="<?= Yf_Registry::get('url') ?>?ctl=News_Message&met=template&type=2"><span>商家消息模板</span></a></li>
                <li><a id="1" href="<?= Yf_Registry::get('url') ?>?ctl=News_Message&met=template&type=1"><span>用户消息模板</span></a></li>
                <li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=getMsgTpl&config_type%5B%5D=msg_tpl"><span>系统消息模板</span></a></li>

            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
			<?php  if(request_int('type')==1){?>
            <li>平台可给商家提供站内信、手机短信、邮件三种通知方式。平台可以选择开启一种或多种通知方式供商家选择。</li>
            <li>开启强制接收后，商家不能取消该方式通知的接收。</li>
            <li>短消息、邮件需要商家设置正确号码后才能正常接收。</li>
            <li class="red">编辑完成后请清理“商家消息模板”缓存。</li>
			<?php }else{ ?>	
			<li>平台可以选择开启一种或多种消息通知方式。</li>
            <li>短消息、邮件需要用户绑定手机、邮箱后才能正常接收。</li>
            <li class="red">编辑完成后请清理“用户消息模板”缓存。</li>
			<?php }?>
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

    var template_type = '<?=(request_int('type'))?> ';

$(function(){
	$("#"+template_type).addClass('current');
	$("#"+template_type).removeAttr("href");
});
</script>
<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/news/message/template_list.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>