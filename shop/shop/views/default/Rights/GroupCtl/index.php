<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
</head>
<body>
<div class="wrapper page">

    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>权限设置&nbsp;</h3>
                <h5>管理权限组权限</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Base&met=index"><span>管理员</span></a></li>
                <li><a class="current"  ><span>权限组</span></a></li>
                <li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Log_Action&met=index"><span>操作日志</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>对权限组权限进行处理</li>
        </ul>
    </div>
    
	<div class="mod-toolbar-top cf">
	    <div class="fl"><strong class="tit">权限组</strong></div>
	    <div class="fr">
	    	<a href="./index.php?ctl=Rights_Group&met=manage" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a><!--<a class="ui-btn mrb" id="btn-disable">禁用</a><a class="ui-btn mrb" id="btn-enable">启用</a>--><!--<a class="ui-btn mrb" id="btn-print">打印</a>--><!--<a class="ui-btn mrb" id="btn-import">导入</a>--><!--<a class="ui-btn mrb" id="btn-export">导出</a>--><a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
	    	<a class="ui-btn" href="./index.php?ctl=User_Base&met=index">返回<i class="iconfont icon-btn05"></i></a>
	    </div>
	  </div>
    <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="./shop_admin/static/default/js/controllers/rights/rights_group_list.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>