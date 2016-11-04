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
                <h3>消息模板</h3>
                <h5>商城对邮件/手机类消息模板设定</h5>
            </div>

            <ul class="tab-base nc-row">
				<li><a id="2" href="<?= Yf_Registry::get('url') ?>?ctl=News_Message&met=template&type=2"><span>商家消息模板</span></a></li>
				<li><a id="1" href="<?= Yf_Registry::get('url') ?>?ctl=News_Message&met=template&type=1"><span>用户消息模板</span></a></li>
				<li><a   class="current"><span>系统消息模板</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>系统自动给注册用户发送邮件或手机短信等信息所使用的模板，可根据需求编辑其中内容。</li>
        </ul>
    </div>
    <div class="cf wrapper">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>
</div>

<script>
    var msg_tpl_data = <?=encode_json(array_values($data)) ?>;
</script>
<script>
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/msg_tpl_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
