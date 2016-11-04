<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
    .webuploader-pick{ padding:1px; }
    
</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>模板风格</h3>
                <h5>首页幻灯将在首页展示</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=siteTheme&config_type%5B%5D=site"><span>风格设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Floor_Adpage&met=adpage"><span>首页模板</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_slider&config_type%5B%5D=index_slider"><span>首页幻灯片</span></a></li>
                <li><a class="current" ><span>首页联动小图</span></a></li>

             
            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
              <li>该组图片应用于首页使用，最多可上传2张图片。</li>
              <li>图片要求使用宽度为236像素，高度为236像素jpg/gif/png格式的图片。</li>
              <li>上传图片后请添加格式为“http://网址...”链接地址。</li>
        </ul>
    </div>

   <form method="post" enctype="multipart/form-data" id="index_liandong-setting-form" name="form1">
    <input type="hidden" name="config_type[]" value="index_liandong"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>首页图片1</label>
        </dt>
        <dd class="opt">
                <img id="index_liandong1_review" src="<?=@($data['index_liandong1_image']['config_value'])?>" width="236" height="236"/>
                <input type="hidden" id="index_liandong1_image" name="index_liandong[index_liandong1_image]" value="<?=@($data['index_liandong1_image']['config_value'])?>" />
                <div  id='index_liandong1_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_liandong[index_liandong_url1]" value="<?=@($data['index_liandong_url1']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_liandong_url1" class="error valid"></label></span>
           <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

     <dl class="row">
        <dt class="tit">
          <label>首页图片2</label>
        </dt>
        <dd class="opt">
                <img id="index_liandong2_review" src="<?=@($data['index_liandong2_image']['config_value'])?>" width="236" height="236"/>
                <input type="hidden" id="index_liandong2_image" name="index_liandong[index_liandong2_image]" value="<?=@($data['index_liandong2_image']['config_value'])?>" />
                <div  id='index_liandong2_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_liandong[index_liandong_url2]" value="<?=@($data['index_liandong_url2']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_liandong_url2" class="error valid"></label></span>
           <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
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
				  //图片裁剪

        var $imagePreview, $imageInput, imageWidth, imageHeight;

        $('#index_liandong1_upload, #index_liandong2_upload').on('click', function () {

            if ( this.id == 'index_liandong1_upload' ) {
                $imagePreview = $('#index_liandong1_review');
                $imageInput = $('#index_liandong1_image');
                imageWidth = 236, imageHeight = 236;
            }else {
                $imagePreview = $('#index_liandong2_review');
                $imageInput = $('#index_liandong2_image');
                imageWidth = 236, imageHeight = 236;
            }
            console.info($imagePreview);
            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                data: { SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                width: '800px',
                lock: true
            })
        });

        function callback ( respone , api ) {
            console.info($imagePreview);
            $imagePreview.attr('src', respone.url);
            $imageInput.attr('value', respone.url);
            api.close();
        }
           //图片上传
         /*  index_liandong1_image_upload= new UploadImage({
              thumbnailWidth: 236,
              thumbnailHeight: 236,
              imageContainer: '#index_liandong1_review',
              uploadButton: '#index_liandong1_upload',
              inputHidden: '#index_liandong1_image'
          });

          index_liandong2_image_upload= new UploadImage({
              thumbnailWidth: 236,
              thumbnailHeight: 236,
              imageContainer: '#index_liandong2_review',
              uploadButton: '#index_liandong2_upload',
              inputHidden: '#index_liandong2_image'
          }); */



   })
    </script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>