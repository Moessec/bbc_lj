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
                <h3>微信二维码</h3>
                <h5>微信公众账号二维码图片上传</h5>
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
            <li>上传商城的微信公众账号二维码图片后，前台用户可使用微信扫一扫进行关注</li>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="setting-mobile_wx" name="form" class="nice-validator n-yellow" novalidate="novalidate">
        <input type="hidden" name="config_type[]" value="setting">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>微信二维码图片</label>
                </dt>
                <dd class="opt">
                    <img id="mobile_wx" name="mobile_wx" alt="选择图片" src="http://127.0.0.1/yf_shop_admin/shop_admin/static/default/images/default_user_portrait.gif">

                    <div class="image-line upload-image" id="mobile_wx_upload">上传图片</div>

                    <input id="mobile_wx_code_img" name="setting[mobile_wx]" value="" class="ui-input w400" type="hidden">
                    <div class="notic">选择上传...选择文件建议大小90px*90px</div>
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
    $(function() {
        new UploadImage({
            thumbnailWidth: 90,
            thumbnailHeight: 90,
            imageContainer: '#mobile_wx',
            uploadButton: '#mobile_wx_upload',
            inputHidden: '#mobile_wx_code_img'
        });

        Public.ajaxGet(SITE_URL + '?ctl=Config&met=shop&config_type%5B%5D=setting&typ=json', {}, function (data){
            if (data.status == 200) {
                var rowData = data.data;
                $('#mobile_wx').prop('src', rowData.mobile_wx.config_value);
                $('#mobile_wx_code_img').val(rowData.mobile_wx.config_value);
            } else {
                Public.tips({type:1, content:data.msg});
            }
        })
    })
</script>
