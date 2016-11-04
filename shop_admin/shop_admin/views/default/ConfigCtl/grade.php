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
                <h3>经验值管理&nbsp;</h3>
                <h5>商城会员经验值设定及获取日志</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Grade&met=grade"><span>经验值明细</span></a></li>
                <li><a class="current"><span>规则设置</span></a></li>
				<li><a  href="<?= Yf_Registry::get('url') ?>?ctl=User_Grade&met=setGrade"><span>等级设置</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>会员获取经验值设定</li>
        </ul>
    </div>


    <form method="post" enctype="multipart/form-data" id="grade-setting-form" name="form">
        <input type="hidden" name="config_type[]" value="grade"/>

        <div class="ncap-form-default">
			<div class="title">
				<h3>会员日常获取经验值设定</h3>
			</div>
            <dl class="row">
                <dt class="tit">
                    <label>会员每天第一次登录</label>
                </dt>
                <dd class="opt">
					<input id="grade_login" name="grade[grade_login]" value="<?=($data['grade_login']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>          
			<dl class="row">
                <dt class="tit">
                    <label>订单商品评论</label>
                </dt>
                <dd class="opt">
					<input id="grade_evaluate" name="grade[grade_evaluate]" value="<?=($data['grade_evaluate']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<div class="title">
				<h3>会员购物获取经验值设定</h3>
			</div>
			<dl class="row">
                <dt class="tit">
                    <label>消费额与赠送积分比例</label>
                </dt>
                <dd class="opt">
					<input id="grade_recharge" name="grade[grade_recharge]" value="<?=($data['grade_recharge']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">该值为大于0的数，例:设置为10，表明消费10单位货币赠送1积分</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>每订单最多赠送经验值</label>
                </dt>
                <dd class="opt">
					<input id="grade_order" name="grade[grade_order]" value="<?=($data['grade_order']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">该值为大于0的数，例:设置为100，表明每订单赠送积分最多为100积分</p>
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