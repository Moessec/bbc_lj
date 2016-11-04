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
                <h3>平台购物卡列表</h3>
                <h5>商城购物卡设置生成及用户充值使用明细</h5>
            </div>
			<ul class="tab-base nc-row">
				<li><a <?php if($data['detail']){ ?>href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Card&met=getDetail"<?php }else{?>class='current'<?php } ?>><span>卡号</span></a></li>
				<li><a <?php if($data['detail']){ ?>class='current'<?php }else{?>href="<?= Yf_Registry::get('url') ?>/?ctl=Operation_Card&met=getDetail&detail=1"<?php } ?>><span>明细</span></a></li>
			</ul>
        </div>
    </div>
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>平台发布购物卡，用户可在会员中心通过输入正确购物卡号的形式对其购物卡账户进行充值。</li>
			<li>已经被领取的平台购物卡不能被删除。</li>
		</ul>
	</div>

    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">购物卡名称</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['card_name']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">购物卡数量</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['card_num']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">金额</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=@$data['prize']['m']?$data['prize']['m']:0?> 元</span>
                    </li>
                </ul>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">积分</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=@$data['prize']['p']?$data['prize']['p']:0?></span>
                    </li>
                </ul>
            </dd>
        </dl>
		<dl class="row">
            <dt class="tit">描述</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['card_desc']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
		<dl class="row">
            <dt class="tit">图标</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <img src="<?=$data['card_image']?>" />
                    </li>
                </ul>
            </dd>
        </dl>
		<dl class="row">
            <dt class="tit">时间</dt>
            <dd class="opt">
                <ul class="nofloat">
                    <li>
                        <span><?=$data['card_start_time']?> 至 <?=$data['card_end_time']?></span>
                    </li>
                </ul>
            </dd>
        </dl>
	</div>

         <div class="mod-toolbar-top cf">
            <div class="fr">
                <a class="ui-btn ui-btn-sp" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

	<?php if($data['detail']==1){ ?>
	<script src="<?=$this->view->js?>/controllers/operation/card_use_list.js"></script>
	<?php }else{ ?>
    <script src="<?=$this->view->js?>/controllers/operation/card_info_list.js"></script>
	<?php } ?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>