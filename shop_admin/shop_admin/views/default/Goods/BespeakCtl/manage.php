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
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="opentime" id="opentime"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="outtime">活动截止时间:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="outtime" id="outtime"></div>
			</li>
			  <li class="form-item">
                      <style>
                        .txt{ height:28px; border:1px solid #cdcdcd; width:670px;    font-size: 1rem;}
                        .mybtn{ background-color:#FFF; line-height:14px;vertical-align:middle;border:1px solid #CDCDCD;height:30px; width:70px;    font-size: 1rem;margin-left: 40px}
                        .file{ position:absolute; top:0;    font-size: 0.6rem; right:80px; height:24px; filter:alpha(opacity:0);opacity: 0;width:260px }
                        </style>
                        <div class="form-group">
							<div class="label-wrap">
                            <label class="control-label" style=" ">图片上传：</label></div>
                            <div class="ctn-wrap"><span onclick="file.click()" style=""  class="mybtn">浏览...</span>
                        	</div>
                            <input type="file" name="file" class="file" id="file" size="28"  onchange="preImg(this.id,'imgPre');UpladFile();" />
                            <!-- <span onclick="" id="upload" style="font-size: 0.6rem;" class="mybtn">上传</span> -->
                            <div class="input-box" style="display:none">
                                <input type="tel" class="inp" name="img" id="img" autocomplete="off" oninput="writeClear($(this));" />
                                <span class="input-del"></span>
                            </div>
                            <img id="imgPre" src="" style="display: block;border:1px #000;   margin: auto;" />  
                  </li>
		</ul>
	</form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_bespeak.js" charset="utf-8"></script>
        <script type="text/javascript">
    var xhr;
    function createXMLHttpRequest()
    {
        if(window.ActiveXObject)
        {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest)
        {
            xhr = new XMLHttpRequest();
        }
    }
    function UpladFile()
    {
        var fileObj = document.getElementById("file").files[0];
        var FileController = ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=upload&typ=json";
        var form = new FormData();
        form.append("myfile", fileObj);
        createXMLHttpRequest();
        xhr.onreadystatechange = handleStateChange;
        xhr.open("post", FileController, true);
        xhr.send(form);
    }
    function handleStateChange()
    {
        if(xhr.readyState == 4)
        {
            if (xhr.status == 200 || xhr.status == 0)
            {
                var result = xhr.responseText;
                var json = eval("(" + result + ")");
                var img = document.getElementById('img');
                var val = img.value
                img.value=json.file+val;
                alert("上传成功");
            }
        }
    }

    function getFileUrl(sourceId) { 
    var url; 
    if (navigator.userAgent.indexOf("MSIE")>=1) { // IE 
    url = document.getElementById(sourceId).value; 
    } else if(navigator.userAgent.indexOf("Firefox")>0) { // Firefox 
    url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0)); 
    } else if(navigator.userAgent.indexOf("Chrome")>0) { // Chrome 
    url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0)); 
    } 
    return url; 
    } 

    /** 
    * 将本地图片 显示到浏览器上 
    */ 
    function preImg(sourceId, targetId) { 
    var url = getFileUrl(sourceId); 
    var imgPre = document.getElementById(targetId); 
    imgPre.src = url; 
    } 


</script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>