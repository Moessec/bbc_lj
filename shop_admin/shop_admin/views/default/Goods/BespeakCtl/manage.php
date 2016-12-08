<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?=$this->view->js?>/controllers/goods/jquery.datetimepicker.css"/>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

.input{	
}
.input-wide{
	width: 500px;
}

</style>
<style>
.manage-wrap{margin: 20px auto 10px;width: 80%;}
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
				<div class="label-wrap"><label for="usercontact">联系方式:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="usercontact" id="usercontact"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="opentime">活动开始时间:</label></div>
				<div class="ctn-wrap"><input type="text" class="some_class" value="" name="opentime" id="some_class_1"/></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="outtime">活动截止时间:</label></div>
				<div class="ctn-wrap">
				<input type="text" class="some_class" name="outtime"  value="" id="some_class_2"/></div>
			</li>
			  <li class="row-item odd">
    				<div class="label-wrap"><label for="bespeak_img">图片标识</label></div>
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
    $(function(){
        buyer_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 200,
            imageContainer: '#bespeak_img',
            uploadButton: '#bespeak_upload',
            inputHidden: '#bes_img'
        });
    })
    alert(url_path);
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_bespeak.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<script src="<?=$this->view->js?>/controllers/goods/jquery.js"></script>
<script src="<?=$this->view->js?>/controllers/goods/build/jquery.datetimepicker.full.js"></script>
<script>
/*

window.onerror = function(errorMsg) {
	$('#console').html($('#console').html()+'<br>'+errorMsg)
}*/
var $jq = jQuery.noConflict();
$jq.datetimepicker.setLocale('en');

$jq('#datetimepicker_format').datetimepicker({value:'2015/04/15 05:03', format: $jq("#datetimepicker_format_value").val()});
console.log($jq('#datetimepicker_format').datetimepicker('getValue'));

$jq("#datetimepicker_format_change").on("click", function(e){
	$jq("#datetimepicker_format").data('xdsoft_datetimepicker').setOptions({format: $jq("#datetimepicker_format_value").val()});
});
$jq("#datetimepicker_format_locale").on("change", function(e){
	$jq.datetimepicker.setLocale($jq(e.currentTarget).val());
});

$jq('#datetimepicker').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
disabledDates:['1986-01-08','1986-01-09','1986-01-10'],
startDate:	'1986-01-05'
});
$jq('#datetimepicker').datetimepicker({value:'2015-04-15 05:03',step:10});

$jq('.some_class').datetimepicker();

$jq('#default_datetimepicker').datetimepicker({
	formatTime:'H:i',
	formatDate:'d.m.Y',
	//defaultDate:'8.12.1986', // it's my birthday
	defaultDate:'+03.01.1970', // it's my birthday
	defaultTime:'10:00',
	timepickerScrollbar:false
});

$jq('#datetimepicker10').datetimepicker({
	step:5,
	inline:true
});
$jq('#datetimepicker_mask').datetimepicker({
	mask:'9999-19-39 29:59'
});

$jq('#datetimepicker1').datetimepicker({
	datepicker:false,
	format:'H:i',
	step:5
});
$jq('#datetimepicker2').datetimepicker({
	yearOffset:222,
	lang:'ch',
	timepicker:false,
	format:'d-m-Y',
	formatDate:'Y-m-d',
	minDate:'-1970-01-02', // yesterday is minimum date
	maxDate:'+1970-01-02' // and tommorow is maximum date calendar
});
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>