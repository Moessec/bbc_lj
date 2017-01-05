<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<style>
	.matchCon{width:280px;}
	body{background:#fff;}
	.mod-form-rows .label-wrap{font-size:12px;}
	.mod-form-rows .row-item{padding-bottom:15px;margin-bottom:0;}/*兼容IE7 ，重写common的演示*/
	.manage-wrapper{margin:20px auto 10px;width:910px;}
	.manage-wrap .ui-input{width:198px;}
	.base-form{*zoom:1;}
	.base-form:after{content:'.';display:block;clear:both;height:0;overflow:hidden;}
	.base-form li{float:left;width:290px;}
	.base-form li.odd{padding-right:20px;}
	.base-form li.last{width:600px}
	.base-form li.last .ui-input{width:508px;}
	.manage-wrap textarea.ui-input{width:900px;height:48px;line-height:16px;overflow:hidden;}
	.contacters{margin-bottom:15px;}
	.contacters h3{margin-bottom:10px;font-weight:normal;}
	.remark .row-item{padding-bottom:0;}
	.mod-form-rows .ctn-wrap{overflow:visible;}
	.grid-wrap .ui-jqgrid{border-width:1px 0 0 1px;}
	.base-form li.odd{padding-left:20px;padding-right:0px;}
</style>
</style>
<body>
<div class="manage-wrapper">
	<div id="manage-wrap" class="manage-wrap">
		<form id="manage-form" action="">
			<ul class="mod-form-rows base-form cf" id="base-form">
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_name">会员ID</label></div>
					<div class="ctn-wrap"><input type="text" disabled class="ui-input" name="user_id" id="user_id"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_cardnum">会员卡号</label></div>
					<div class="ctn-wrap"><input type="text" disabled class="ui-input" name="user_cardnum" id="user_cardnum"></div>
				</li>

				<li class="row-item odd">
					<div class="label-wrap"><label for="sex">会员性别</label></div>
					<div class="ctn-wrap"><span id="sex"></span></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_realname">真实姓名</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="user_realname" id="user_realname"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_points">会员积分</label></div>
					<div class="ctn-wrap"><input type="text" disabled class="ui-input" name="user_points" id="user_points"></div>
				</li>

				<li class="row-item odd">
					<div class="label-wrap"><label for="identification">身份证号</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="identification" id="identification"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_email">会员邮箱</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="user_email" id="user_email"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_mobile">会员手机</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="user_mobile" id="user_mobile"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_qq">会员QQ</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="user_qq" id="user_qq"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="user_ww">会员旺旺</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="user_ww" id="user_ww"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="date">出生日期</label></div>
					<div class="ctn-wrap"><input id="date" type="text" class="ui-input ui-datepicker-input" name="date"></div>
				</li>
				
				<li class="row-item odd">
					<div class="label-wrap"><label for="operator">操作员</label></div>
					<div class="ctn-wrap"><input type="text" class="ui-input" name="operator" id="operator" value="<{$smarty.session.ADMIN_USER}>" disabled /></div>
				</li>
			</ul>
		</form>
	</div>
	<div class="hideFile dn">
		<input type="text" class="textbox address" name="address" id="address" autocomplete="off" readonly>
	</div>
</div>
<script src="<?= Yf_Registry::get('base_url') ?>/webpos/static/default/js/controllers/user/user_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>