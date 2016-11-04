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
                <li><a class="current"><span>物流查询选用设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=kuaidi100&config_type%5B%5D=kuaidi100"><span>快递100</span></a></li>
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
            <li>选择快递方式</li>
        </ul>
    </div>
    
    <?php
    ?>
    <form method="post" id="logistics-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="logistics"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">是否启用</dt>
                <dd class="opt">
                    <div>
                        <input id="kuaidi100" name="logistics[logistics_channel]"  value="kuaidi100" type="radio" <?=($data['logistics_channel']['config_value']=='kuaidi100' ? 'checked' : '')?>>
						<label title="开启"  for="kuaidi100">快递100</label>

						&nbsp;&nbsp;&nbsp;
                        <input id="kuaidiniao" name="logistics[logistics_channel]"  value="kuaidiniao" type="radio" <?=($data['logistics_channel']['config_value']=='kuaidiniao' ? 'checked' : '')?>>
						<label title="开启"  for="kuaidiniao">快递鸟</label>
                    </div>
                   <p class="notic"></p>
                </dd>
            </dl>

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