<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/jquery/plugins/validator/jquery.validator.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>

<div class="wrapper page">
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>用户注册，设置注册密码长度与密码复杂度。</li>
		</ul>
	</div>
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>注册设置</h3>
				<h5>用户注册设置</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a class="current"><span>注册设置</span></a></li>
			</ul>
		</div>
	</div>
	<form style="" method="post" name="form_index" id="reg-setting-form">
		<input type="hidden" name="config_type[]" value="register"/>
		<div class="ncap-form-default">
			<div id="para-wrapper">
				<div class="para-item">
					<ul class="mod-form-rows" id="establish-form">

						<li class="row-item">
							<div class="label-wrap" style="width: 100px;">
								<label for="reg_pwdlength">密码最小长度:</label>
							</div>
							<div class="ctn-wrap">
								<input id="reg_pwdlength" name="register[reg_pwdlength]" value="<?=($data['reg_pwdlength']['config_value'])?>" class="ui-input w100" type="text" />
								<p class="notic">此处设置的密码的最小长度，密码的最大长度默认是20。请填写小于20的正整数</p>
							</div>
						</li>
						<li class="row-item">
							<div class="label-wrap" style="width: 100px;">
								<label for="sms_pass">强制密码复杂度:</label>
							</div>
							<div class="ctn-wrap">
								<dd class="opt">
									<input id="reg_number" name="register[reg_number]"  value="1" type="checkbox" <?=($data['reg_number']['config_value']=='1' ? 'checked' : '')?>>
									<label title="开启"  for="reg_number">数字</label>

									<br>
									<input id="reg_lowercase" name="register[reg_lowercase]"  value="1" type="checkbox" <?=($data['reg_lowercase']['config_value']=='1' ? 'checked' : '')?>>
									<label title="开启"  for="reg_lowercase">小写字母</label>

									<br>
									<input id="reg_uppercase" name="register[reg_uppercase]"  value="1" type="checkbox" <?=($data['reg_uppercase']['config_value']=='1' ? 'checked' : '')?>>
									<label title="开启"  for="reg_uppercase">大写字母</label>
									<br>
									<input id="reg_symbols" name="register[reg_symbols]"  value="1" type="checkbox" <?=($data['reg_symbols']['config_value']=='1' ? 'checked' : '')?>>
									<label title="开启"  for="reg_symbols">符号</label>
									<p class="notic"></p>
								</dd>

							</div>
						</li>
					</ul>
				</div>
				<div class="btn-wrap bot"> <a name="submit" id="submit" class="ui-btn ui-btn-sp submit-btn">提交</a> </div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
