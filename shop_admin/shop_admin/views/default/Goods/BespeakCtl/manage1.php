<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style>
.manage-wrap{margin: 20px auto 10px;width: 80%;}
	.webuploader-element-invisible{display: none}
.mod-form-rows .label-wrap{width: 20%;}
.mod-form-rows .ctn-wrap .ui-input{width: 50%;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="bespeak_title">发布预约:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="bespeak_title" id="bespeak_title"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="bespeak_com">预约详情:</label></div>
				<div class="ctn-wrap"><textarea type="text" value="" class="ui-input" name="bespeak_com" id="bespeak_com"></textarea></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="rent_price">租赁价格:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="rent_price" id="rent_price"></div>
			</li>
			<li class="form-item">
                <div class="label-wrap"><label for="bespeak_com">地区选择:
            	</label></div>
                <div class="ctn-wrap">
                	<select class="valid" id="area_1">
						<option value="">-请选择-</option>
						<option name="北京" value="1">北京</option>
						<option name="广西" value="20">广西</option>
						<option name="海南" value="21">海南</option>
						<option name="重庆" value="22">重庆</option>
						<option name="四川" value="23">四川</option>
						<option name="贵州" value="24">贵州</option>
						<option name="云南" value="25">云南</option>
						<option name="西藏" value="26">西藏</option>
						<option name="陕西" value="27">陕西</option>
						<option name="甘肃" value="28">甘肃</option>
						<option name="青海" value="29">青海</option>
						<option name="宁夏" value="30">宁夏</option>
						<option name="新疆" value="31">新疆</option>
						<option name="台湾" value="32">台湾</option>
						<option name="香港" value="33">香港</option>
						<option name="澳门" value="34">澳门</option>
						<option name="广东" value="19">广东</option>
						<option name="湖南" value="18">湖南</option>
						<option name="湖北" value="17">湖北</option>
						<option name="天津" value="2">天津</option>
						<option name="河北" value="3">河北</option>
						<option name="山西" value="4">山西</option>
						<option name="内蒙古" value="5">内蒙古</option>
						<option name="辽宁" value="6">辽宁</option>
						<option name="吉林" value="7">吉林</option>
						<option name="黑龙江" value="8">黑龙江</option>
						<option name="上海" value="9">上海</option>
						<option name="江苏" value="10">江苏</option>
						<option name="浙江" value="11">浙江</option>
						<option name="安徽" value="12">安徽</option>
						<option name="福建" value="13">福建</option>
						<option name="江西" value="14">江西</option>
						<option name="山东" value="15">山东</option>
						<option name="河南" value="16">河南</option>
						<option name="海外" value="35">海外</option>
					</select>
					<select id="area_2" class="valid" style="display:none">
						<option>请选择</option>
					</select>
					<select id="area_3" class="valid" style="display:none">
						<option>请选择</option>
					</select>
					<!--<input type="hidden" value="" name="region" id="region">-->
					<input type="hidden" value="" name="province" id="_area_1">
					<input type="hidden" value="" name="city" id="_area_2">
					<input type="hidden" value="" name="are" id="_area_3">
					<p></p>
                </div>
            </li>
			<li class="row-item">
				<div class="label-wrap"><label for="true_name">联系人:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="true_name" id="true_name"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="usercontact">联系方式:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="usercontact" id="usercontact"></div>
			</li>
			  <li class="row-item odd">
    				<div class="label-wrap"><label for="bespeak_img">相关图片</label></div>
    				<div class="ctn-wrap" >
                        <img id="bespeak_img" name="setting[bes_img]" alt="选择图片" src="./shop_admin/static/common/images/image.png" class="image-line" />
                        <div class="image-line" style="margin-left: 80px;" id="bespeak_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                        <input id="bes_img" name="setting[bes_img]" value="" class="ui-input w400" type="hidden"/>
                    </div>
    			</li>
		</ul>
	</form>
</div>
<script>
    //图片上传
    $(document).ready(function(){
    	$('#area_1').on('change',function(){
    		var v1 = $('#area_1').find("option:selected").attr('name');
    		$('#_area_1').val(v1);
    	})
    	$('#area_2').on('change',function(){
    		var v2 = $('#area_2').find("option:selected").attr('name');
    		$('#_area_2').val(v2);
    	})
    	$('#area_3').on('change',function(){
    		var v3 = $('#area_3').find("option:selected").attr('name');
    		$('#_area_3').val(v3);
    	})
    })
    $(function(){
        buyer_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 200,
            imageContainer: '#bespeak_img',
            uploadButton: '#bespeak_upload',
            inputHidden: '#bes_img'
        });
    })
</script>
<style type="text/css">
	.webuploader-element-invisible{display: none}
</style>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_bespeak1.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_bespeak1.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>