<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>应用安装</h3>
                <h5>手机客户端应用安装包下载地址等设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>微信二维码</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>当前安卓安装包版本用于安卓包在线升级，请保证所填版本号与提供下载的apk文件保持一致</li>
            <li>下载地址为完整的网址，以“http://”开头，“生成二维码”中网址为程序自动生成</li>
        </ul>
    </div>

    <form method="post" id="app-setting-form" name="settingForm" class="nice-validator n-yellow" novalidate="novalidate">
        <input type="hidden" name="config_type[]" value="setting">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="mobile_apk">安卓安装包</label>
                </dt>
                <dd class="opt">
                    <input id="mobile_apk" name="setting[mobile_apk]" value="" class="w400 ui-input " type="text" aria-required="true">
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">当前安卓安装包版本</label>
                </dt>
                <dd class="opt">
                    <input id="mobile_apk_version" name="setting[mobile_apk_version]" value="" class="w400 ui-input " type="text">
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label for="site_name">iOS版</label>
                </dt>
                <dd class="opt">
                    <input id="mobile_ios" name="setting[mobile_ios]" value="" class="ui-input w400" type="text" aria-required="true">
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<?php
include TPL_PATH . '/' . 'footer.php';
?>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>

<script>
    $(function(){
        Public.ajaxGet(SITE_URL + '?ctl=Config&met=shop&config_type%5B%5D=setting&typ=json', {}, function (data){
            if (data.status == 200) {
                var rowData = data.data;
                $('#mobile_apk').val(rowData.mobile_apk.config_value);
                $('#mobile_apk_version').val(rowData.mobile_apk_version.config_value);
                $('#mobile_ios').val(rowData.mobile_ios.config_value);
            } else {
                Public.tips({type:1, content:data.msg});
            }
        })
    })
</script>