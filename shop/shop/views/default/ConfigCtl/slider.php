<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
</head>
<style>
.image-line {
  margin-bottom:5px;
}
</style>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>团购管理</h3>
                <h5>实物商品团购促销活动相关设定及管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=index"><span>团购活动</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=cat"><span>团购分类</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=price"><span>团购价格区间</span></a></li>
                <li><a class="current"><span>首页幻灯片</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=vrArea"><span>虚拟团购地区</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=groupbuy&config_type%5B%5D=groupbuy"><span>团购设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_GroupBuy&met=quota"><span>已开通店铺</span></a></li>
            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
              <li>该组幻灯片滚动图片应用于团购聚合页上部使用，最多可上传4张图片。</li>
              <li>图片要求使用宽度为1043像素，高度为396像素jpg/gif/png格式的图片。</li>
              <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>
              <li>清空操作将删除聚合页上的滚动图片，请注意操作</li>
        </ul>
    </div>

   <form method="post" enctype="multipart/form-data" id="slider-setting-form" name="form1">
    <input type="hidden" name="config_type[]" value="slider"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>滚动图片1</label>
        </dt>
        <dd class="opt">
                <img id="slider1_review" src="<?=@($data['slider1_image']['config_value'])?>" width="400"/>
                <input type="hidden" id="slider1_image" name="slider[slider1_image]" value="<?=@($data['slider1_image']['config_value'])?>" />
                <div  id='slider1_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                <input class="ui-input w400" type="text" name="slider[live_link1]" value="<?=@($data['live_link1']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="live_link1" class="error valid"></label></span>
           <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

     <dl class="row">
        <dt class="tit">
          <label>滚动图片2</label>
        </dt>
        <dd class="opt">
                <img id="slider2_review" src="<?=@($data['slider2_image']['config_value'])?>" width="400"/>
                <input type="hidden" id="slider2_image" name="slider[slider2_image]" value="<?=@($data['slider2_image']['config_value'])?>" />
                <div  id='slider2_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                <input class="ui-input w400" type="text" name="slider[live_link2]" value="<?=@($data['live_link2']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="live_link2" class="error valid"></label></span>
           <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>


    <dl class="row">
        <dt class="tit">
          <label>滚动图片3</label>
        </dt>
        <dd class="opt">
                <img id="slider3_review" src="<?=@($data['slider3_image']['config_value'])?>" width="400"/>
                <input type="hidden" id="slider3_image" name="slider[slider3_image]" value="<?=@($data['slider3_image']['config_value'])?>" />
                <div  id='slider3_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                <input class="ui-input w400" type="text" name="slider[live_link3]" value="<?=@($data['live_link3']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="live_link3" class="error valid"></label></span>
           <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

    <dl class="row">
        <dt class="tit">
          <label>滚动图片4</label>
        </dt>
        <dd class="opt">
                <img id="slider4_review" src="<?=@($data['slider4_image']['config_value'])?>" width="400" />
                <input type="hidden" id="slider4_image" name="slider[slider4_image]" value="<?=@($data['slider4_image']['config_value'])?>" />
                <div  id='slider4_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                <input class="ui-input w400" type="text" name="slider[live_link4]" value="<?=@($data['live_link4']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="live_link4" class="error valid"></label></span>
           <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

     <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
  </form>

    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>

    <script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
    <script>
$(function(){
           //图片上传
           $('#slider1_upload').on('click', function () {
               $.dialog({
                   title: '图片裁剪',
                   content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                   data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback1 },    // 需要截取图片的宽高比例
                   width: '800px',
                   lock: true
               })
           });

           function callback1 ( respone , api ) {
               $('#slider1_review').attr('src', respone.url);
               $('#slider1_image').attr('value', respone.url);
               api.close();
           }

            $('#slider2_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                    data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback2 },    // 需要截取图片的宽高比例
                    width: '800px',
                    lock: true
                })
            });

           function callback2 ( respone , api ) {
               $('#slider2_review').attr('src', respone.url);
               $('#slider2_image').attr('value', respone.url);
               api.close();
           }

           $('#slider3_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                    data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback3 },    // 需要截取图片的宽高比例
                    width: '800px',
                    lock: true
                })
            });

           function callback3 ( respone , api ) {
               $('#slider3_review').attr('src', respone.url);
               $('#slider3_image').attr('value', respone.url);
               api.close();
           }


           $('#slider4_upload').on('click', function () {
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                    data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback4 },    // 需要截取图片的宽高比例
                    width: '800px',
                    lock: true
                })
            });

           function callback4 ( respone , api ) {
               $('#slider4_review').attr('src', respone.url);
               $('#slider4_image').attr('value', respone.url);
               api.close();
           }
   })
</script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>