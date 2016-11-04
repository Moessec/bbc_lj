<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
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
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Points&met=points"><span>积分明细</span></a></li>
                <li><a class="current"><span>规则设置</span></a></li>
				<li><a  href="<?= Yf_Registry::get('url') ?>?ctl=User_Points&met=addPoints"><span>积分增减</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>会员获取积分设定</li>
        </ul>
    </div>
    
    <form method="post" enctype="multipart/form-data" id="points-setting-form" name="form">
        <input type="hidden" name="config_type[]" value="points"/>

        <div class="ncap-form-default">
			<div class="title">
				<h3>会员日常获取积分设定</h3>
			</div>
            <dl class="row">
                <dt class="tit">
                    <label>会员注册</label>
                </dt>
                <dd class="opt">
					<input id="points_reg" name="points[points_reg]" value="<?=($data['points_reg']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员每天登陆</label>
                </dt>
                <dd class="opt">
					<input id="points_login" name="points[points_login]" value="<?=($data['points_login']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>订单商品评论</label>
                </dt>
                <dd class="opt">
					<input id="points_evaluate" name="points[points_evaluate]" value="<?=($data['points_evaluate']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<div class="title">
				<h3>会员购物并付款时积分获取设定</h3>
			</div>
			<dl class="row">
                <dt class="tit">
                    <label>每订单最多赠送经验值</label>
                </dt>
                <dd class="opt">
					<input id="points_recharge" name="points[points_recharge]" value="<?=($data['points_recharge']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">例:设置为10，表明消费10单位货币赠送1积分</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>消费额与赠送积分比例</label>
                </dt>
                <dd class="opt">
					<input id="points_order" name="points[points_order]" value="<?=($data['points_order']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">例:设置为100，表明每订单赠送积分最多为100积分</p>
                </dd>
            </dl>
          <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<script>
  
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>