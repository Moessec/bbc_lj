<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>
    .webuploader-pick{ padding:1px; }
    
</style>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>经验值管理&nbsp;</h3>
                <h5>商城会员经验值设定及获取日志</h5>
            </div> 
			<ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=User_Grade&met=grade"><span>经验值明细</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=grade&config_type%5B%5D=grade"><span>规则设置</span></a></li>
				<li><a class="current"><span>等级设置</span></a></li>
            </ul>
        </div>
    </div>
     <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>			
            <li>“会员级别设置”提交后，当会员符合某个级别后将自动升至该级别，请谨慎设置会员级别。</li>                	
            <li>建议：级别应该是逐层递增，例如“级别2”所需经验值高于“级别1”；二、设置的第一个级别所需经验值应为0；三、信息应填写完整。</li>                	
        </ul>
    </div>
	
    <form method="post" enctype="multipart/form-data" id="grade-edit-form" name="form">		
        <div class="ncap-form-default ncap-form-default_reset">
			<div class="title">
				<h3>会员级别设置：</h3>
			</div>
			<?php foreach($data as $key=>$val){ ?>
			<dl id="row_0" class="row">
				<dt class="tit"><span>会员等级</span></dt>
				<dd class="opt">
				  <input type="text"  style="width:100px;height:25px;" value="<?=$val['user_grade_name'];?>" name="gr[<?=$key;?>][user_grade_name]">晋级需 <input type="text"  style="width:100px;height:25px;" value="<?=$val['user_grade_demand'];?>" name="gr[<?=$key;?>][user_grade_demand]"> 经验值; 会员折扣率 <input type="text"  style="width:50px;height:25px;" value="<?=$val['user_grade_rate'];?>" name="gr[<?=$key;?>][user_grade_rate]">
				  <input type="hidden" readonly="" class="w50" value="<?=$val['user_grade_id'];?>" name="gr[<?=$key;?>][user_grade_id]">	
				</dd>
			 </dl>
			 <dl  class="row">
				<dt class="tit"><strong>等级图片</strong></dt>
				<dd class="opt">				  
				  <img id="grade_logo_image<?=$key;?>" name="gr[<?=$key;?>][user_grade_logo]" alt="选择图片" src="<?=$val['user_grade_logo'];?>" width="100px" height="100px"/>

                    <div class="image-line upload-image"  id="grade_logo_upload<?=$key;?>">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="grade_logo<?=$key;?>"  name="gr[<?=$key;?>][user_grade_logo]" value="<?=$val['user_grade_logo'];?>" class="ui-input w400" type="hidden"/>
                    <div class="notic">默认经验值LOGO,，最佳显示尺寸为100*100像素</div>
				</dd>
			 </dl>
			<?php }?> 
          <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script>
  //图片上传
    $(function(){
		<?php foreach($data as $key=>$val){ ?>
		$('#grade_logo_upload<?=$key?>').on('click', function () {
		 $.dialog({
					title: '图片裁剪',
					content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
					data: { SHOP_URL: SHOP_URL, width: 100, height: 100, callback: callback },    // 需要截取图片的宽高比例
					width: '800px',
					lock: true
				})
			});
		
		function callback ( respone , api ) {
				$('#grade_logo_image<?=$key?>').attr('src', respone.url);
				$('#grade_logo<?=$key?>').attr('value', respone.url);
				api.close();
			}
		<?php }?>
       
    })
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/user/grade/log.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>