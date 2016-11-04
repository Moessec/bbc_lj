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
                <h3>积分管理&nbsp;</h3>
                <h5>商城会员积分管理及获取日志</h5>
            </div> 
			<ul class="tab-base nc-row">
                <li><a class="current"><span>积分明细</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=points&config_type%5B%5D=points"><span>规则设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Points&met=addPoints"><span>积分增减</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>			
            <li>积分明细，展示了被操作人员（会员）、操作人员（管理员）、操作积分数（积分值，“-”表示减少，无符号表示增加）、操作时间（添加时间）等信息。</li>
            <li>你可以根据条件搜索会员，然后选择相应的操作。</li>          	
        </ul>
    </div>
        <div class="mod-toolbar-top cf">
            <div class="left">
                <div id="assisting-category-select" class="ui-tab-select">
                    <ul class="ul-inline">
                        <li>
                            <span id="source"></span>
                        </li>
                        <li>
                            <input type="text" id="searchName" class="ui-input ui-input-ph con" value="请输入相关数据...">
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
 
</div>
<script type="text/javascript">

</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/user/points/log_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>