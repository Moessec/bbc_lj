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
                <h3>物流设置&nbsp;</h3>
                <h5>快递状态查询设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=logistics&config_type%5B%5D=logistics&typ=e"><span>物流查询选用设置</span></a></li>
                <li><a class="current"><span>快递100</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=kuaidiniao&config_type%5B%5D=kuaidiniao"><span>快递鸟</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>点击提交申请快递100APP</li>
        </ul>
    </div>
    
    <?php
  ?>
    <form method="post" id="kuaidi100-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="kuaidi100"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">App Id</label>
                </dt>
                <dd class="opt">
                    <input id="kuaidi100_app_id" name="kuaidi100[kuaidi100_app_id]" value="<?=$data['kuaidi100_app_id']['config_value']?>" class="w400 ui-input " type="text"/>

                    <p class="notic">尚无App Id,<a href="https://www.kuaidi100.com/openapi/applyapi.shtml" target="_blank">申请</a></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">APP Key</label>
                </dt>
                <dd class="opt">
                    <input id="kuaidi100_app_key" name="kuaidi100[kuaidi100_app_key]" value="<?=$data['kuaidi100_app_key']['config_value']?>" class="w400 ui-input " type="text"/>
                    <p class="notic">APP Key</p>
                </dd>
            </dl>

			<!--
            <dl class="row">
                <dt class="tit">是否启用</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="kuaidi100_status1" name="kuaidi100[kuaidi100_status]"  value="1" type="radio" <?=($data['kuaidi100_status']['config_value']==1 ? 'checked' : '')?>>
						<label title="开启" class="cb-enable <?=($data['kuaidi100_status']['config_value']==1 ? 'selected' : '')?> " for="kuaidi100_status1">开启</label>

                        <input id="kuaidi100_status0" name="kuaidi100[kuaidi100_status]"  value="0" type="radio" <?=($data['kuaidi100_status']['config_value']==0 ? 'checked' : '')?>>
						<label title="关闭" class="cb-disable <?=($data['kuaidi100_status']['config_value']==0 ? 'selected' : '')?>" for="kuaidi100_status0">关闭</label>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            -->

            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript">
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>