<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>&nbsp;</h3>
        <h5>商城所有商品索引及管理</h5>
      </div>
      <ul class="tab-base nc-row"><li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common" <?=(('Goods_Goods' == request_string('ctl') && -1 == request_int('common_state', '-1') && -1 == request_int('common_verify', '-1')) ? 'class="current"' : '')?>><span>所有商品</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_state=10" <?=(10 == request_int('common_state', '-1') ? 'class="current"' : '')?> ><span>违规下架</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_verify=10" <?=(10 == request_int('common_verify', '-1') ? 'class="current"' : '')?>><span>等待审核</span></a></li><li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=goods&config_type%5B%5D=goods" <?=('Config' == request_string('ctl') ? 'class="current"' : '')?>><span>商品设置</span></a></li></ul> </div>
  </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>商品审核设置</li>
        </ul>
    </div>
    
    <form method="post" id="dump-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="goods"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">商品是否需要审核</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="goods_verify_flag1" name="goods[goods_verify_flag]" value="1" type="radio" <?=($data['goods_verify_flag']['config_value']==1 ? 'checked' : '')?> >
						<label title="开启" class="cb-enable <?=($data['goods_verify_flag']['config_value']==1 ? 'selected' : '')?> " for="goods_verify_flag1">开启</label>

                        <input id="goods_verify_flag0" name="goods[goods_verify_flag]" value="0" type="radio"  <?=($data['goods_verify_flag']['config_value']==0 ? 'checked' : '')?> >
						<label title="关闭" class="cb-disable <?=($data['goods_verify_flag']['config_value']==0 ? 'selected' : '')?>" for="goods_verify_flag0">关闭</label>
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