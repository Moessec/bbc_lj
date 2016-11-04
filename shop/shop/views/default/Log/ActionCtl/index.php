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
                <h3>权限管理&nbsp;</h3>
                <h5>管理中心管理操作日志内容</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Base&met=index"><span>管理员</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Rights_Group&met=index"><span>权限组</span></a></li>
                <li><a class="current"  ><span>操作日志</span></a></li>
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
            <li>系统默认关闭了操作日志，如需开启，请编辑插件管理,启用日志插件</li>
            <li>开启操作日志可以记录管理人员的关键操作，但会轻微加重系统负担</li>
        </ul>
    </div>
    
    <div class="mod-search cf">
        <div class="fl">
            <ul class="ul-inline">
                <li>
                    <span id="user"></span> <input type="text" id="matchCon" class="ui-input ui-input-ph" value="请输入查询内容">
                </li>
                <li>
                    <label>日期:</label>
                    <input type="text" id="begin_date" class="ui-input ui-datepicker-input">
                    <i>-</i>
                    <input type="text" id="end_date" class="ui-input ui-datepicker-input">
                </li>
                <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a><!--<a class="ui-btn ui-btn-refresh" id="refresh" title="刷新"><b></b></a>--></li>
            </ul>
        </div>
    </div>

    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>
</div>
<script>
</script>
<script>
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/log_action_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>