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
                <li><a class="current"><span>首页幻灯片</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_liandong&config_type%5B%5D=index_liandong"><span>首页联动小图</span></a></li>
            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
              <li>该组幻灯片滚动图片应用于首页使用，最多可上传5张图片。</li>
              <li>图片要求使用宽度为1900像素，高度为500像素jpg/gif/png格式的图片。</li>
              <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>
        </ul>
    </div>

   <form method="post" enctype="multipart/form-data" id="index_slider-setting-form" name="form1">
    <input type="hidden" name="config_type[]" value="index_slider"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>滚动图片1</label>
        </dt>
        <dd class="opt">
                <img id="index_slider1_review" src="<?=@($data['index_slider1_image']['config_value'])?>" width="760" height="200"/>
                <input type="hidden" id="index_slider1_image" name="index_slider[index_slider1_image]" value="<?=@($data['index_slider1_image']['config_value'])?>" />
                <div  id='index_slider1_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_slider[index_live_link1]" value="<?=@($data['index_live_link1']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_live_link1" class="error valid"></label></span>
           <p class="notic">请使用宽度1900像素，高度500像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

     <dl class="row">
        <dt class="tit">
          <label>滚动图片2</label>
        </dt>
        <dd class="opt">
                <img id="index_slider2_review" src="<?=@($data['index_slider2_image']['config_value'])?>" width="760" height="200"/>
                <input type="hidden" id="index_slider2_image" name="index_slider[index_slider2_image]" value="<?=@($data['index_slider2_image']['config_value'])?>" />
                <div  id='index_slider2_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_slider[index_live_link2]" value="<?=@($data['index_live_link2']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_live_link2" class="error valid"></label></span>
           <p class="notic">请使用宽度1900像素，高度500像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>


    <dl class="row">
        <dt class="tit">
          <label>滚动图片3</label>
        </dt>
        <dd class="opt">
                <img id="index_slider3_review" src="<?=@($data['index_slider3_image']['config_value'])?>" width="760" height="200"/>
                <input type="hidden" id="index_slider3_image" name="index_slider[index_slider3_image]" value="<?=@($data['index_slider3_image']['config_value'])?>" />
                <div  id='index_slider3_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_slider[index_live_link3]" value="<?=@($data['index_live_link3']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_live_link3" class="error valid"></label></span>
           <p class="notic">请使用宽度1900像素，高度500像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

    <dl class="row">
        <dt class="tit">
          <label>滚动图片4</label>
        </dt>
        <dd class="opt">
                <img id="index_slider4_review" src="<?=@($data['index_slider4_image']['config_value'])?>" width="760" height="200"/>
                <input type="hidden" id="index_slider4_image" name="index_slider[index_slider4_image]" value="<?=@($data['index_slider4_image']['config_value'])?>" />
                <div  id='index_slider4_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="index_slider[index_live_link4]" value="<?=@($data['index_live_link4']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_live_link4" class="error valid"></label></span>
           <p class="notic">请使用宽度1900像素，高度500像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>滚动图片5</label>
        </dt>
        <dd class="opt">
                <img id="index_slider5_review" src="<?=@($data['index_slider5_image']['config_value'])?>" width="760" height="200"/>
                <input type="hidden" id="index_slider5_image" name="index_slider[index_slider5_image]" value="<?=@($data['index_slider5_image']['config_value'])?>" />
                <div  id='index_slider5_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="index_slider[index_live_link5]" value="<?=@($data['index_live_link5']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="index_live_link5" class="error valid"></label></span>
           <p class="notic">请使用宽度1900像素，高度500像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
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

        $('#index_slider1_upload, #index_slider2_upload, #index_slider3_upload,#index_slider4_upload,#index_slider5_upload').on('click', function () {

            if ( this.id == 'index_slider1_upload' ) {
                $imagePreview = $('#index_slider1_review');
                $imageInput = $('#index_slider1_image');
                imageWidth = 1900, imageHeight = 500;
            } else if ( this.id == 'index_slider2_upload' ) {
                $imagePreview = $('#index_slider2_review');
                $imageInput = $('#index_slider2_image');
                imageWidth = 1900, imageHeight = 500;
            }  else if ( this.id == 'index_slider3_upload' ) {
                $imagePreview = $('#index_slider3_review');
                $imageInput = $('#index_slider3_image');
                imageWidth = 1900, imageHeight = 500;
            }else if ( this.id == 'index_slider4_upload' ) {
                $imagePreview = $('#index_slider4_review');
                $imageInput = $('#index_slider4_image');
                imageWidth = 1900, imageHeight = 500;
            }else {
                $imagePreview = $('#index_slider5_review');
                $imageInput = $('#index_slider5_image');
                imageWidth = 1900, imageHeight = 500;
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
         /*  index_slider1_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#index_slider1_review',
              uploadButton: '#index_slider1_upload',
              inputHidden: '#index_slider1_image'
          });


          index_slider2_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#index_slider2_review',
              uploadButton: '#index_slider2_upload',
              inputHidden: '#index_slider2_image'
          });


            index_slider3_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#index_slider3_review',
              uploadButton: '#index_slider3_upload',
              inputHidden: '#index_slider3_image'
          });


           index_slider4_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#index_slider4_review',
              uploadButton: '#index_slider4_upload',
              inputHidden: '#index_slider4_image'
          });
          

           index_slider5_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#index_slider5_review',
              uploadButton: '#index_slider5_upload',
              inputHidden: '#index_slider5_image'
          }); */
   })
    </script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>