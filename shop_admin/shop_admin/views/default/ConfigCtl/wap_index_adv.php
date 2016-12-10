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
  .upload-image {
    background-color: #48cfae !important;
    border-radius: 2px;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-family: "microsoft yahei",arial;
    font-feature-settings: normal;
    font-kerning: auto;
    font-language-override: normal;
    font-size: 12px;
    font-size-adjust: none;
    font-stretch: normal;
    font-style: normal;
    font-synthesis: weight style;
    font-variant: normal;
    font-weight: normal;
    height: 33px !important;
    line-height: 33px !important;
    padding: 0 12px !important;
    width: 26px;
}
.ui-input {
    border: 1px solid #e2e2e2;
    color: #555;
    height: 18px;
    line-height: 18px;
    outline: 0 none;
    padding: 5px;
    vertical-align: middle;
    width: 105px;
} 
.row{
  width:250px;
  float: left;
}

div.bot {
    display: block;
    /*left: 859px;*/
    padding: 12px 0 10px 17%;
    position: fixed;
    background-color: #f53a59;
    border-radius: 2px;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    height: 55px;
    line-height: 55px;
    padding: 0 6px 0 13px;
    position: fixed;
  top: 100px;
    vertical-align: middle;
}
</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>模板风格</h3>
                <h5>首页广告位</h5>
            </div>
            <ul class="tab-base nc-row">

                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=siteTheme&config_type%5B%5D=site"><span>风格设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Floor_Adpage&met=adpage"><span>首页模板</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_slider&config_type%5B%5D=index_slider"><span>首页幻灯片</span></a></li>

                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_liandong&config_type%5B%5D=index_liandong"><span>首页联动小图</span></a></li>
                <li><a class="current" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=wap_index_adv&config_type%5B%5D=wap_index_adv"><span>wap首页商品广告小图</span></a></li>
                <li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=wap_index_longadv&config_type%5B%5D=wap_index_adv"><span>wap首页广告长图</span></a></li>

            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
        </div>
        <ul>
              <li>该组图片应用于首页下栏广告使用，最多可上传20张图片。</li>
              <li>图片要求使用宽度为170像素，高度为170像素jpg/gif/png格式的图片。</li>
              <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>
        </ul>
    </div>

   <form method="post" enctype="multipart/form-data" id="wap_index_adv-setting-form" name="form1">
    <input type="hidden" name="config_type[]" value="wap_index_adv"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>图片1</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv1_review" src="<?=@($data['wap_index_adv1_image']['config_value'])?>" width="170" height="170"/>
                <input type="hidden" id="wap_index_adv1_image" name="wap_index_adv[wap_index_adv1_image]" value="<?=@($data['wap_index_adv1_image']['config_value'])?>" />
                <div  id='wap_index_adv1_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0;size:auto" type="text" name="wap_index_adv[wap_index_adv_link1]" value="<?=@($data['wap_index_adv_link1']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link1" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
            </p>
        </dd>
      </dl>

     <dl class="row">
        <dt class="tit">
          <label>图片2</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv2_review" src="<?=@($data['wap_index_adv2_image']['config_value'])?>" width="170" height="170"/>
                <input type="hidden" id="wap_index_adv2_image" name="wap_index_adv[wap_index_adv2_image]" value="<?=@($data['wap_index_adv2_image']['config_value'])?>" />
                <div  id='wap_index_adv2_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link2]" value="<?=@($data['wap_index_adv_link2']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link2" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>


    <dl class="row">
        <dt class="tit">
          <label>图片3</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv3_review" src="<?=@($data['wap_index_adv3_image']['config_value'])?>" width="170" height="170"/>
                <input type="hidden" id="wap_index_adv3_image" name="wap_index_adv[wap_index_adv3_image]" value="<?=@($data['wap_index_adv3_image']['config_value'])?>" />
                <div  id='wap_index_adv3_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link3]" value="<?=@($data['wap_index_adv_link3']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link3" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>

    <dl class="row">
        <dt class="tit">
          <label>图片4</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv4_review" src="<?=@($data['wap_index_adv4_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv4_image" name="wap_index_adv[wap_index_adv4_image]" value="<?=@($data['wap_index_adv4_image']['config_value'])?>" />
                <div  id='wap_index_adv4_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link4]" value="<?=@($data['wap_index_adv_link4']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link4" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片5</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv5_review" src="<?=@($data['wap_index_adv5_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv5_image" name="wap_index_adv[wap_index_adv5_image]" value="<?=@($data['wap_index_adv5_image']['config_value'])?>" />
                <div  id='wap_index_adv5_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link5]" value="<?=@($data['wap_index_adv_link5']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link5" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>      
       <dl class="row">
        <dt class="tit">
          <label>图片6</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv6_review" src="<?=@($data['wap_index_adv6_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv6_image" name="wap_index_adv[wap_index_adv6_image]" value="<?=@($data['wap_index_adv6_image']['config_value'])?>" />
                <div  id='wap_index_adv6_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link6]" value="<?=@($data['wap_index_adv_link6']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link6" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片7</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv7_review" src="<?=@($data['wap_index_adv7_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv7_image" name="wap_index_adv[wap_index_adv7_image]" value="<?=@($data['wap_index_adv7_image']['config_value'])?>" />
                <div  id='wap_index_adv7_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link7]" value="<?=@($data['wap_index_adv_link7']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link7" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片8</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv8_review" src="<?=@($data['wap_index_adv8_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv8_image" name="wap_index_adv[wap_index_adv8_image]" value="<?=@($data['wap_index_adv8_image']['config_value'])?>" />
                <div  id='wap_index_adv8_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link8]" value="<?=@($data['wap_index_adv_link8']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link8" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>

       <dl class="row">
        <dt class="tit">
          <label>图片9</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv9_review" src="<?=@($data['wap_index_adv9_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv9_image" name="wap_index_adv[wap_index_adv9_image]" value="<?=@($data['wap_index_adv9_image']['config_value'])?>" />
                <div  id='wap_index_adv9_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link9]" value="<?=@($data['wap_index_adv_link9']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link9" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片10</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv10_review" src="<?=@($data['wap_index_adv10_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv10_image" name="wap_index_adv[wap_index_adv10_image]" value="<?=@($data['wap_index_adv10_image']['config_value'])?>" />
                <div  id='wap_index_adv10_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link10]" value="<?=@($data['wap_index_adv_link10']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link10" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片11</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv11_review" src="<?=@($data['wap_index_adv11_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv11_image" name="wap_index_adv[wap_index_adv11_image]" value="<?=@($data['wap_index_adv11_image']['config_value'])?>" />
                <div  id='wap_index_adv11_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link11]" value="<?=@($data['wap_index_adv_link11']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link11" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片12</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv12_review" src="<?=@($data['wap_index_adv12_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv12_image" name="wap_index_adv[wap_index_adv12_image]" value="<?=@($data['wap_index_adv12_image']['config_value'])?>" />
                <div  id='wap_index_adv12_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link12]" value="<?=@($data['wap_index_adv_link12']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link12" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片13</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv13_review" src="<?=@($data['wap_index_adv13_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv13_image" name="wap_index_adv[wap_index_adv13_image]" value="<?=@($data['wap_index_adv13_image']['config_value'])?>" />
                <div  id='wap_index_adv13_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link13]" value="<?=@($data['wap_index_adv_link13']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link13" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片14</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv14_review" src="<?=@($data['wap_index_adv14_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv14_image" name="wap_index_adv[wap_index_adv14_image]" value="<?=@($data['wap_index_adv14_image']['config_value'])?>" />
                <div  id='wap_index_adv14_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link14]" value="<?=@($data['wap_index_adv_link14']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link14" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片15</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv15_review" src="<?=@($data['wap_index_adv15_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv15_image" name="wap_index_adv[wap_index_adv15_image]" value="<?=@($data['wap_index_adv15_image']['config_value'])?>" />
                <div  id='wap_index_adv15_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link15]" value="<?=@($data['wap_index_adv_link15']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link15" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片16</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv16_review" src="<?=@($data['wap_index_adv16_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv16_image" name="wap_index_adv[wap_index_adv16_image]" value="<?=@($data['wap_index_adv16_image']['config_value'])?>" />
                <div  id='wap_index_adv16_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link16]" value="<?=@($data['wap_index_adv_link16']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link16" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片17</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv17_review" src="<?=@($data['wap_index_adv17_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv17_image" name="wap_index_adv[wap_index_adv17_image]" value="<?=@($data['wap_index_adv17_image']['config_value'])?>" />
                <div  id='wap_index_adv17_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link17]" value="<?=@($data['wap_index_adv_link17']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link17" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label>图片18</label>
        </dt>
        <dd class="opt">
                <img id="wap_index_adv18_review" src="<?=@($data['wap_index_adv18_image']['config_value'])?>"  width="170" height="170"/>
                <input type="hidden" id="wap_index_adv18_image" name="wap_index_adv[wap_index_adv18_image]" value="<?=@($data['wap_index_adv18_image']['config_value'])?>" />
                <div  id='wap_index_adv18_upload' class="image-line upload-image" >上传</div>

           <label title="请输入图片要跳转的链接地址" ><i class="fa fa-link"></i>
                <input class="ui-input  w400" style="margin:5px 0" type="text" name="wap_index_adv[wap_index_adv_link18]" value="<?=@($data['wap_index_adv_link18']['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="wap_index_adv_link18" class="error valid"></label></span>
           <p class="notic" style="width: 180px">宽170px高170px的jpg/png格式<br>
          
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

        $('#wap_index_adv1_upload, #wap_index_adv2_upload, #wap_index_adv3_upload,#wap_index_adv4_upload,#wap_index_adv5_upload,#wap_index_adv6_upload,#wap_index_adv7_upload,#wap_index_adv8_upload,#wap_index_adv9_upload,#wap_index_adv10_upload,#wap_index_adv11_upload,#wap_index_adv12_upload,#wap_index_adv13_upload,#wap_index_adv14_upload,#wap_index_adv15_upload,#wap_index_adv16_upload,#wap_index_adv17_upload,#wap_index_adv18_upload').on('click', function () {

            if ( this.id == 'wap_index_adv1_upload' ) {
                $imagePreview = $('#wap_index_adv1_review');
                $imageInput = $('#wap_index_adv1_image');
                imageWidth = 170, imageHeight = 170;
            } else if ( this.id == 'wap_index_adv2_upload' ) {
                $imagePreview = $('#wap_index_adv2_review');
                $imageInput = $('#wap_index_adv2_image');
                imageWidth = 170, imageHeight = 170;
            }  else if ( this.id == 'wap_index_adv3_upload' ) {
                $imagePreview = $('#wap_index_adv3_review');
                $imageInput = $('#wap_index_adv3_image');
                imageWidth = 170, imageHeight = 170;
            }else if ( this.id == 'wap_index_adv4_upload' ) {
                $imagePreview = $('#wap_index_adv4_review');
                $imageInput = $('#wap_index_adv4_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv5_upload' ){
                $imagePreview = $('#wap_index_adv5_review');
                $imageInput = $('#wap_index_adv5_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv6_upload' ){
                $imagePreview = $('#wap_index_adv6_review');
                $imageInput = $('#wap_index_adv6_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv7_upload' ){
                $imagePreview = $('#wap_index_adv7_review');
                $imageInput = $('#wap_index_adv7_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv8_upload' ){
                $imagePreview = $('#wap_index_adv8_review');
                $imageInput = $('#wap_index_adv8_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv9_upload' ){
                $imagePreview = $('#wap_index_adv9_review');
                $imageInput = $('#wap_index_adv9_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv10_upload' ){
                $imagePreview = $('#wap_index_adv10_review');
                $imageInput = $('#wap_index_adv10_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv11_upload' ){
                $imagePreview = $('#wap_index_adv11_review');
                $imageInput = $('#wap_index_adv11_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv12_upload' ){
                $imagePreview = $('#wap_index_adv12_review');
                $imageInput = $('#wap_index_adv12_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv13_upload' ){
                $imagePreview = $('#wap_index_adv13_review');
                $imageInput = $('#wap_index_adv13_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv14_upload' ){
                $imagePreview = $('#wap_index_adv14_review');
                $imageInput = $('#wap_index_adv14_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv15_upload' ){
                $imagePreview = $('#wap_index_adv15_review');
                $imageInput = $('#wap_index_adv15_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv16_upload' ){
                $imagePreview = $('#wap_index_adv16_review');
                $imageInput = $('#wap_index_adv16_image');
                imageWidth = 170, imageHeight = 170;
            }else if( this.id == 'wap_index_adv17_upload' ){
                $imagePreview = $('#wap_index_adv17_review');
                $imageInput = $('#wap_index_adv17_image');
                imageWidth = 170, imageHeight = 170;
            }else{
                $imagePreview = $('#wap_index_adv18_review');
                $imageInput = $('#wap_index_adv18_image');
                imageWidth = 170, imageHeight = 170;
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
         /*  wap_index_adv1_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#wap_index_adv1_review',
              uploadButton: '#wap_index_adv1_upload',
              inputHidden: '#wap_index_adv1_image'
          });


          wap_index_adv2_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#wap_index_adv2_review',
              uploadButton: '#wap_index_adv2_upload',
              inputHidden: '#wap_index_adv2_image'
          });


            wap_index_adv3_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#wap_index_adv3_review',
              uploadButton: '#wap_index_adv3_upload',
              inputHidden: '#wap_index_adv3_image'
          });


           wap_index_adv4_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#wap_index_adv4_review',
              uploadButton: '#wap_index_adv4_upload',
              inputHidden: '#wap_index_adv4_image'
          });
          

           wap_index_adv5_image_upload= new UploadImage({
              thumbnailWidth: 1900,
              thumbnailHeight: 500,
              imageContainer: '#wap_index_adv5_review',
              uploadButton: '#wap_index_adv5_upload',
              inputHidden: '#wap_index_adv5_image'
          }); */
   })
    </script>
    <?php
  
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>